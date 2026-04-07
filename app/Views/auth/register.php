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
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --navy: #0f1628;
      --navy-card: #151f38;
      --navy-border: rgba(99, 132, 255, .15);
      --indigo: #3730a3;
      --indigo-mid: #4338ca;
      --indigo-dark: #312e81;
      --gold: #f59e0b;
      --gold-light: #fcd34d;
      --text: #e2e8f0;
      --text-dim: #94a3b8;
      --text-dimmer: #64748b;
      --border: rgba(99, 132, 255, .12);
      --border2: rgba(99, 132, 255, .07);
      --green: #22c55e;
      --green-bg: rgba(34, 197, 94, .12);
      --red: #ef4444;
      --red-bg: rgba(239, 68, 68, .12);
      --amber: #f59e0b;
      --amber-bg: rgba(245, 158, 11, .12);
      --shadow-lg: 0 20px 60px rgba(0, 0, 0, .5), 0 4px 16px rgba(0, 0, 0, .3);
      --r: 20px;
      --r-md: 14px;
      --r-sm: 10px;
      --r-xs: 8px;
      --font: 'Plus Jakarta Sans', system-ui, sans-serif;
    }

    html {
      height: 100%;
    }

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
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 900px 600px at 85% -5%, rgba(67, 56, 202, .18) 0%, transparent 60%),
        radial-gradient(ellipse 700px 500px at -10% 105%, rgba(99, 102, 241, .12) 0%, transparent 55%),
        radial-gradient(ellipse 500px 400px at 50% 50%, rgba(245, 158, 11, .04) 0%, transparent 65%);
      pointer-events: none;
      z-index: 0;
    }

    body::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image: linear-gradient(rgba(99, 132, 255, .03) 1px, transparent 1px), linear-gradient(90deg, rgba(99, 132, 255, .03) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
      z-index: 0;
    }

    /* ── Wrapper ── */
    .register-wrapper {
      width: 100%;
      max-width: 480px;
      position: relative;
      z-index: 1;
      padding-bottom: 1.5rem;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }

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
      background: rgba(255, 255, 255, .04);
      backdrop-filter: blur(8px);
      transition: all .2s;
      cursor: pointer;
      font-family: var(--font);
      align-self: flex-start;
      width: fit-content;
    }

    .back-btn:hover {
      color: var(--text);
      border-color: rgba(99, 132, 255, .3);
      background: rgba(255, 255, 255, .07);
      transform: translateX(-2px);
    }

    .back-btn i {
      font-size: 11px;
    }

    /* ── Brand ── */
    .brand-bar {
      text-align: center;
      margin-bottom: 22px;
    }

    .brand-tag {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--text-dimmer);
      display: block;
      margin-bottom: 5px;
    }

    .brand-name {
      font-size: 28px;
      font-weight: 900;
      color: #fff;
      letter-spacing: -.04em;
      line-height: 1;
    }

    .brand-name em {
      font-style: normal;
      color: var(--gold);
    }

    /* ── Card ── */
    .register-card {
      background: rgba(21, 31, 56, 0.85);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border-radius: var(--r);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-lg), 0 0 0 1px rgba(99, 102, 241, .06);
      padding: 2.25rem 2rem;
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .register-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 200px;
      height: 1px;
      background: linear-gradient(90deg, transparent, rgba(99, 132, 255, .5), transparent);
    }

    @media (min-width: 480px) {
      .register-card {
        padding: 2.5rem 2.5rem;
      }
    }

    /* ── Logo Hero ── */
    .logo-hero {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.75rem;
    }

    .logo-ring {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: radial-gradient(circle at 40% 35%, rgba(245, 158, 11, .2) 0%, rgba(67, 56, 202, .15) 60%, transparent 80%);
      border: 1.5px solid rgba(245, 158, 11, .35);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
      overflow: hidden;
      box-shadow: 0 0 0 4px rgba(245, 158, 11, .08), 0 0 20px rgba(245, 158, 11, .15), 0 0 50px rgba(67, 56, 202, .2), 0 8px 24px rgba(0, 0, 0, .4);
      position: relative;
    }

    .logo-ring::before {
      content: '';
      position: absolute;
      inset: -2px;
      border-radius: 50%;
      background: conic-gradient(from 0deg, transparent 60%, rgba(245, 158, 11, .4) 80%, transparent 100%);
      animation: logoSpin 4s linear infinite;
      z-index: 0;
    }

    @keyframes logoSpin {
      to {
        transform: rotate(360deg);
      }
    }

    .logo-ring img {
      width: 84%;
      height: 84%;
      object-fit: contain;
      border-radius: 50%;
      position: relative;
      z-index: 1;
    }

    .logo-hero h1 {
      font-size: 1.4rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: -.03em;
      text-align: center;
      line-height: 1.2;
    }

    .logo-hero p {
      font-size: 12px;
      color: var(--text-dimmer);
      font-weight: 500;
      margin-top: 4px;
      text-align: center;
    }

    /* ── Welcome pill ── */
    .welcome-pill {
      background: rgba(99, 102, 241, .08);
      border: 1px solid rgba(99, 132, 255, .2);
      border-radius: var(--r-sm);
      padding: 10px 14px;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .wp-icon {
      width: 30px;
      height: 30px;
      border-radius: 9px;
      background: linear-gradient(135deg, var(--indigo-mid), var(--indigo));
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      flex-shrink: 0;
      box-shadow: 0 4px 10px rgba(67, 56, 202, .4);
    }

    .wp-text h3 {
      font-size: 12px;
      font-weight: 800;
      color: #a5b4fc;
      letter-spacing: -.01em;
    }

    .wp-text p {
      font-size: 11px;
      color: var(--text-dimmer);
      font-weight: 500;
      margin-top: 1px;
    }

    /* ── SK Notice ── */
    .sk-notice {
      display: none;
      background: rgba(245, 158, 11, .08);
      border: 1px solid rgba(245, 158, 11, .2);
      border-radius: var(--r-sm);
      padding: 11px 14px;
      margin-bottom: 1.25rem;
      align-items: flex-start;
      gap: 10px;
      font-size: 12px;
      font-weight: 600;
      color: #fcd34d;
      line-height: 1.55;
      animation: fadeDown .3s ease;
    }

    .sk-notice.show {
      display: flex;
    }

    .sk-notice i {
      color: var(--gold);
      margin-top: 1px;
      flex-shrink: 0;
      font-size: 14px;
    }

    .sk-notice strong {
      color: #fde68a;
    }

    @keyframes fadeDown {
      from {
        opacity: 0;
        transform: translateY(-8px);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    /* ── Fields ── */
    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .9rem;
    }

    @media (max-width: 440px) {
      .field-row {
        grid-template-columns: 1fr;
      }
    }

    .field-lbl {
      display: block;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .16em;
      color: var(--text-dimmer);
      margin-bottom: 6px;
      margin-left: 2px;
    }

    .field-wrap {
      position: relative;
      margin-bottom: 1.1rem;
    }

    .field-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-dimmer);
      font-size: 13px;
      pointer-events: none;
      transition: color .2s;
      z-index: 2;
    }

    .field-input {
      width: 100%;
      height: 48px;
      padding: 0 14px 0 40px;
      border: 1.5px solid var(--border);
      border-radius: var(--r-sm);
      font-family: var(--font);
      font-size: 14px;
      background: rgba(255, 255, 255, .04);
      color: var(--text);
      transition: all .2s;
      outline: none;
      appearance: none;
    }

    .field-input:focus {
      border-color: rgba(99, 132, 255, .5);
      background: rgba(255, 255, 255, .07);
      box-shadow: 0 0 0 3px rgba(67, 56, 202, .15), 0 0 12px rgba(67, 56, 202, .1);
    }

    .field-wrap:focus-within .field-icon {
      color: #a5b4fc;
    }

    .field-input::placeholder {
      color: rgba(148, 163, 184, .35);
      font-weight: 400;
    }

    .field-input.pr {
      padding-right: 44px;
    }

    /* field error highlight */
    .field-input.is-invalid {
      border-color: rgba(239, 68, 68, .5) !important;
      background: rgba(239, 68, 68, .04) !important;
    }

    .field-error {
      font-size: 11px;
      font-weight: 600;
      color: #fca5a5;
      margin-top: 4px;
      margin-left: 2px;
      display: none;
    }

    .field-error.show {
      display: block;
    }

    .select-wrap {
      position: relative;
    }

    .select-wrap::after {
      content: '\f078';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-dimmer);
      font-size: 11px;
      pointer-events: none;
      z-index: 3;
    }

    .field-input option {
      background: #1a2545;
      color: var(--text);
    }

    .eye-btn {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-dimmer);
      cursor: pointer;
      padding: 4px;
      display: flex;
      align-items: center;
      font-size: 14px;
      transition: color .18s;
      z-index: 3;
    }

    .eye-btn:hover {
      color: #a5b4fc;
    }

    .eye-btn:focus {
      outline: none;
    }

    .str-row {
      display: flex;
      gap: 4px;
      margin-top: 6px;
    }

    .str-seg {
      flex: 1;
      height: 3px;
      border-radius: 2px;
      background: var(--border);
      transition: background .3s;
    }

    .str-lbl {
      font-size: 11px;
      font-weight: 700;
      margin-top: 4px;
      text-align: right;
    }

    .pw-reqs {
      background: rgba(255, 255, 255, .03);
      border: 1px solid var(--border);
      border-radius: var(--r-xs);
      padding: 10px 13px;
      margin-top: 8px;
      display: none;
    }

    .pw-reqs-title {
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .12em;
      color: var(--text-dimmer);
      margin-bottom: 7px;
    }

    .req {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 12px;
      font-weight: 600;
      color: var(--text-dimmer);
      padding: 2px 0;
      transition: color .2s;
    }

    .req i {
      font-size: 11px;
      width: 13px;
      transition: color .2s;
    }

    .req.met {
      color: #86efac;
    }

    .req.met i {
      color: #86efac;
    }

    .pw-match {
      display: none;
      align-items: center;
      gap: 7px;
      font-size: 12px;
      font-weight: 700;
      margin-top: 7px;
      padding: 7px 11px;
      border-radius: var(--r-xs);
    }

    .pw-match.bad {
      display: flex;
      color: #fca5a5;
      background: var(--red-bg);
      border: 1px solid rgba(239, 68, 68, .25);
    }

    .pw-match.good {
      display: flex;
      color: #86efac;
      background: var(--green-bg);
      border: 1px solid rgba(34, 197, 94, .25);
    }

    .check-wrap {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      cursor: pointer;
      user-select: none;
      margin-bottom: 1.25rem;
    }

    .check-input {
      appearance: none;
      width: 17px;
      height: 17px;
      min-width: 17px;
      border: 1.5px solid var(--border);
      border-radius: 6px;
      background: rgba(255, 255, 255, .05);
      cursor: pointer;
      position: relative;
      flex-shrink: 0;
      transition: all .18s;
      margin-top: 2px;
    }

    .check-input:checked {
      background: var(--indigo-mid);
      border-color: var(--indigo-mid);
    }

    .check-input:checked::after {
      content: '\f00c';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      color: white;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 9px;
    }

    .check-input:focus {
      box-shadow: 0 0 0 3px rgba(67, 56, 202, .2);
      outline: none;
    }

    .check-lbl {
      font-size: 13px;
      color: var(--text-dim);
      font-weight: 500;
      line-height: 1.5;
    }

    .check-lbl a {
      color: #a5b4fc;
      font-weight: 700;
      text-decoration: none;
      transition: color .18s;
    }

    .check-lbl a:hover {
      color: #c7d2fe;
      text-decoration: underline;
    }

    /* ── Submit btn ── */
    .btn-submit {
      width: 100%;
      height: 50px;
      background: linear-gradient(135deg, var(--indigo-mid), var(--indigo));
      color: #fff;
      font-family: var(--font);
      font-weight: 800;
      font-size: 14px;
      border: none;
      border-radius: var(--r-sm);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all .2s;
      box-shadow: 0 4px 20px rgba(67, 56, 202, .45), 0 1px 0 rgba(255, 255, 255, .1) inset;
      position: relative;
      overflow: hidden;
      letter-spacing: -.01em;
    }

    .btn-submit::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 60%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .08), transparent);
      transition: left .5s;
    }

    .btn-submit:hover {
      background: linear-gradient(135deg, #4f46e5, #3730a3);
      box-shadow: 0 6px 28px rgba(67, 56, 202, .6);
      transform: translateY(-1px);
    }

    .btn-submit:hover::after {
      left: 150%;
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .btn-submit:disabled {
      opacity: .45;
      cursor: not-allowed;
      transform: none !important;
      box-shadow: none !important;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 1.25rem 0;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    .divider span {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .12em;
      color: var(--text-dimmer);
    }

    .login-row {
      text-align: center;
      font-size: 13px;
      color: var(--text-dim);
      font-weight: 500;
    }

    .login-row a {
      color: #a5b4fc;
      font-weight: 700;
      text-decoration: none;
      transition: color .18s;
    }

    .login-row a:hover {
      color: #c7d2fe;
      text-decoration: underline;
    }

    .footer-note {
      text-align: center;
      margin-top: 1.25rem;
      font-size: 11px;
      color: var(--text-dimmer);
      line-height: 1.7;
    }

    .footer-note button {
      color: #a5b4fc;
      font-weight: 700;
      font-size: 11px;
      background: none;
      border: none;
      cursor: pointer;
      font-family: var(--font);
      transition: color .18s;
      padding: 0;
    }

    .footer-note button:hover {
      color: #c7d2fe;
      text-decoration: underline;
    }

    /* ══ MODALS ══ */
    .overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 300;
      background: rgba(5, 10, 25, .7);
      backdrop-filter: blur(8px);
      align-items: center;
      justify-content: center;
      padding: 1.25rem;
    }

    .overlay.open {
      display: flex;
      animation: overlayIn .2s ease;
    }

    @keyframes overlayIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .modal-box {
      background: var(--navy-card);
      border-radius: var(--r);
      border: 1px solid var(--border);
      box-shadow: 0 32px 80px rgba(0, 0, 0, .6);
      width: 100%;
      max-width: 560px;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      animation: modalUp .25s cubic-bezier(.34, 1.4, .64, 1);
      overflow: hidden;
    }

    @keyframes modalUp {
      from {
        opacity: 0;
        transform: translateY(20px) scale(.97);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    .modal-head {
      flex-shrink: 0;
      padding: 18px 22px 16px;
      border-bottom: 1px solid var(--border2);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .modal-head-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .modal-icon {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }

    .modal-icon.indigo {
      background: rgba(99, 102, 241, .15);
      color: #a5b4fc;
      border: 1px solid rgba(99, 132, 255, .2);
    }

    .modal-icon.purple {
      background: rgba(139, 92, 246, .12);
      color: #c4b5fd;
      border: 1px solid rgba(139, 92, 246, .2);
    }

    .modal-head-title {
      font-size: 15px;
      font-weight: 800;
      color: #fff;
      letter-spacing: -.02em;
    }

    .modal-head-sub {
      font-size: 11px;
      color: var(--text-dimmer);
      font-weight: 500;
      margin-top: 1px;
    }

    .modal-close-btn {
      width: 32px;
      height: 32px;
      border-radius: 9px;
      background: rgba(255, 255, 255, .06);
      border: 1px solid var(--border);
      color: var(--text-dim);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      transition: all .15s;
      flex-shrink: 0;
    }

    .modal-close-btn:hover {
      background: rgba(239, 68, 68, .15);
      border-color: rgba(239, 68, 68, .3);
      color: #fca5a5;
    }

    .modal-prog-bar {
      height: 2px;
      background: rgba(255, 255, 255, .05);
      flex-shrink: 0;
    }

    .modal-prog-fill {
      height: 100%;
      width: 0%;
      border-radius: 0 2px 2px 0;
      transition: width .1s linear;
    }

    .fill-indigo {
      background: linear-gradient(90deg, var(--indigo-mid), #818cf8);
    }

    .fill-purple {
      background: linear-gradient(90deg, #7c3aed, #a78bfa);
    }

    .modal-body {
      flex: 1;
      overflow-y: auto;
      padding: 20px 22px;
    }

    .modal-body::-webkit-scrollbar {
      width: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb {
      background: rgba(99, 132, 255, .2);
      border-radius: 4px;
    }

    .ts {
      margin-bottom: 1.5rem;
    }

    .ts:last-child {
      margin-bottom: 0;
    }

    .ts-head {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
    }

    .ts-num {
      width: 24px;
      height: 24px;
      border-radius: 7px;
      font-size: 10px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .ts-num.in {
      background: rgba(99, 102, 241, .15);
      color: #a5b4fc;
    }

    .ts-num.pu {
      background: rgba(139, 92, 246, .12);
      color: #c4b5fd;
    }

    .ts h3 {
      font-size: 13px;
      font-weight: 800;
      color: #fff;
    }

    .ts p,
    .ts li {
      font-size: 13px;
      color: var(--text-dim);
      line-height: 1.7;
      font-weight: 500;
    }

    .ts ul {
      margin-top: 6px;
      padding-left: 0;
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .ts li {
      display: flex;
      align-items: flex-start;
      gap: 7px;
    }

    .ts li::before {
      content: '';
      width: 5px;
      height: 5px;
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 8px;
    }

    .li-in::before {
      background: #6366f1;
    }

    .li-pu::before {
      background: #8b5cf6;
    }

    .ts-hl {
      border-radius: 10px;
      padding: 10px 13px;
      margin-top: 10px;
      font-size: 12px;
      font-weight: 600;
      line-height: 1.6;
    }

    .hl-in {
      background: rgba(99, 102, 241, .1);
      border: 1px solid rgba(99, 132, 255, .2);
      color: #a5b4fc;
    }

    .hl-pu {
      background: rgba(139, 92, 246, .1);
      border: 1px solid rgba(139, 92, 246, .2);
      color: #c4b5fd;
    }

    .modal-foot {
      flex-shrink: 0;
      padding: 14px 22px;
      border-top: 1px solid var(--border2);
      background: rgba(255, 255, 255, .02);
    }

    .modal-foot-note {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      color: var(--text-dimmer);
      font-weight: 600;
      margin-bottom: 10px;
    }

    .must-hint {
      font-size: 11px;
      color: var(--amber);
      font-weight: 700;
      text-align: center;
      display: none;
      margin-bottom: 8px;
    }

    .must-hint.show {
      display: block;
    }

    .modal-foot-btns {
      display: flex;
      gap: 8px;
    }

    .btn-modal-cancel {
      flex: 1;
      height: 42px;
      background: rgba(255, 255, 255, .05);
      color: var(--text-dim);
      border: 1px solid var(--border);
      border-radius: var(--r-xs);
      font-family: var(--font);
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      transition: all .15s;
    }

    .btn-modal-cancel:hover {
      background: rgba(255, 255, 255, .08);
    }

    .btn-modal-accept {
      flex: 2;
      height: 42px;
      color: #fff;
      border: none;
      border-radius: var(--r-xs);
      font-family: var(--font);
      font-weight: 800;
      font-size: 13px;
      cursor: pointer;
      transition: all .18s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .btn-modal-accept.c-in {
      background: linear-gradient(135deg, var(--indigo-mid), var(--indigo));
      box-shadow: 0 4px 14px rgba(67, 56, 202, .4);
    }

    .btn-modal-accept.c-in:hover {
      background: linear-gradient(135deg, #4f46e5, #3730a3);
    }

    .btn-modal-accept.c-pu {
      background: linear-gradient(135deg, #7c3aed, #6d28d9);
      box-shadow: 0 4px 14px rgba(124, 58, 237, .4);
    }

    .btn-modal-accept.c-pu:hover {
      background: linear-gradient(135deg, #6d28d9, #5b21b6);
    }

    .btn-modal-accept:disabled {
      opacity: .35;
      cursor: not-allowed;
    }

    /* ══ RESULT MODAL ══ */
    .overlay.result-overlay {
      z-index: 500;
    }

    .result-box {
      background: var(--navy-card);
      border-radius: var(--r);
      border: 1px solid var(--border);
      box-shadow: 0 32px 80px rgba(0, 0, 0, .6);
      width: 100%;
      max-width: 400px;
      padding: 2.25rem 2rem;
      text-align: center;
      animation: modalUp .3s cubic-bezier(.34, 1.4, .64, 1);
    }

    .result-icon-wrap {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 18px;
      font-size: 2rem;
      position: relative;
    }

    .result-icon-wrap.success {
      background: var(--green-bg);
      color: var(--green);
      box-shadow: 0 12px 28px rgba(34, 197, 94, .2);
    }

    .result-icon-wrap.error {
      background: var(--red-bg);
      color: var(--red);
      box-shadow: 0 12px 28px rgba(239, 68, 68, .2);
    }

    .result-icon-wrap.warning {
      background: var(--amber-bg);
      color: var(--amber);
      box-shadow: 0 12px 28px rgba(245, 158, 11, .2);
    }

    .result-icon-wrap::before {
      content: '';
      position: absolute;
      inset: -5px;
      border-radius: 50%;
      border: 2px solid transparent;
      animation: ringRot 2.5s linear infinite;
    }

    .result-icon-wrap.success::before {
      border-top-color: rgba(34, 197, 94, .3);
      border-right-color: rgba(34, 197, 94, .15);
    }

    .result-icon-wrap.error::before {
      border-top-color: rgba(239, 68, 68, .3);
      border-right-color: rgba(239, 68, 68, .15);
    }

    .result-icon-wrap.warning::before {
      border-top-color: rgba(245, 158, 11, .3);
      border-right-color: rgba(245, 158, 11, .15);
    }

    @keyframes ringRot {
      to {
        transform: rotate(360deg);
      }
    }

    .result-box h2 {
      font-size: 1.35rem;
      font-weight: 800;
      color: #fff;
      letter-spacing: -.03em;
      margin-bottom: 8px;
    }

    .result-box p {
      font-size: 13px;
      color: var(--text-dim);
      font-weight: 500;
      line-height: 1.65;
      margin-bottom: 1.5rem;
    }

    .result-actions {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .btn-result {
      width: 100%;
      height: 46px;
      border: none;
      border-radius: var(--r-sm);
      font-family: var(--font);
      font-weight: 800;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all .18s;
      color: #fff;
    }

    .btn-result.green {
      background: linear-gradient(135deg, #22c55e, #16a34a);
      box-shadow: 0 4px 14px rgba(34, 197, 94, .3);
    }

    .btn-result.green:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(34, 197, 94, .4);
    }

    .btn-result.red {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      box-shadow: 0 4px 14px rgba(239, 68, 68, .3);
    }

    .btn-result.red:hover {
      transform: translateY(-1px);
    }

    .btn-result.blue {
      background: linear-gradient(135deg, var(--indigo-mid), var(--indigo));
      box-shadow: 0 4px 14px rgba(67, 56, 202, .3);
    }

    .btn-result.blue:hover {
      transform: translateY(-1px);
    }

    .btn-result.amber {
      background: linear-gradient(135deg, var(--amber), #d97706);
      box-shadow: 0 4px 14px rgba(245, 158, 11, .3);
    }

    .btn-result.amber:hover {
      transform: translateY(-1px);
    }

    .countdown-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      margin-top: 12px;
      font-size: 12px;
      color: var(--text-dimmer);
      font-weight: 600;
    }

    .countdown-num {
      font-weight: 800;
      font-size: 13px;
    }

    .countdown-num.green {
      color: var(--green);
    }

    .countdown-num.amber {
      color: var(--amber);
    }

    @keyframes toastIn {
      from {
        opacity: 0;
        transform: translateX(-50%) translateY(8px);
      }

      to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
      }
    }

    .spin {
      width: 14px;
      height: 14px;
      border: 2px solid rgba(255, 255, 255, .3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin .6s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body>
  <div class="register-wrapper">

    <!-- Back Button — now properly left-aligned -->
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

        <!-- First Name & Last Name -->
        <div class="field-row" style="margin-bottom:1.1rem">
          <div>
            <label class="field-lbl" for="first_name">First Name</label>
            <div style="position:relative">
              <i class="field-icon fa-regular fa-user"></i>
              <input
                type="text"
                id="first_name"
                name="first_name"
                class="field-input <?= session('errors.first_name') ? 'is-invalid' : '' ?>"
                placeholder="Juan"
                value="<?= esc(old('first_name')) ?>"
                required
                autocomplete="given-name">
            </div>
            <?php if (session('errors.first_name')): ?>
              <div class="field-error show"><?= esc(session('errors.first_name')) ?></div>
            <?php endif; ?>
          </div>
          <div>
            <label class="field-lbl" for="last_name">Last Name</label>
            <div style="position:relative">
              <i class="field-icon fa-regular fa-user"></i>
              <input
                type="text"
                id="last_name"
                name="last_name"
                class="field-input <?= session('errors.last_name') ? 'is-invalid' : '' ?>"
                placeholder="Dela Cruz"
                value="<?= esc(old('last_name')) ?>"
                required
                autocomplete="family-name">
            </div>
            <?php if (session('errors.last_name')): ?>
              <div class="field-error show"><?= esc(session('errors.last_name')) ?></div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Email -->
        <div style="margin-bottom:1.1rem">
          <label class="field-lbl" for="email">Email Address</label>
          <div style="position:relative">
            <i class="field-icon fa-regular fa-envelope"></i>
            <input
              type="email"
              id="email"
              name="email"
              class="field-input <?= session('errors.email') ? 'is-invalid' : '' ?>"
              placeholder="juan@example.com"
              value="<?= esc(old('email')) ?>"
              required
              autocomplete="email">
          </div>
          <?php if (session('errors.email')): ?>
            <div class="field-error show"><?= esc(session('errors.email')) ?></div>
          <?php endif; ?>
        </div>

        <!-- Role -->
        <div style="margin-bottom:1.1rem">
          <label class="field-lbl" for="role">I am a&hellip;</label>
          <div style="position:relative" class="select-wrap">
            <i class="field-icon fa-solid fa-id-badge"></i>
            <select
              id="role"
              name="role"
              class="field-input <?= session('errors.role') ? 'is-invalid' : '' ?>"
              required
              onchange="onRoleChange(this.value)">
              <option value="" disabled <?= old('role') ? '' : 'selected' ?>>Select your role</option>
              <option value="resident" <?= old('role') === 'resident' ? 'selected' : '' ?>>Resident</option>
              <option value="SK" <?= old('role') === 'SK'       ? 'selected' : '' ?>>SK Officer</option>
            </select>
          </div>
          <?php if (session('errors.role')): ?>
            <div class="field-error show"><?= esc(session('errors.role')) ?></div>
          <?php endif; ?>
        </div>

        <!-- Password -->
        <div style="margin-bottom:.5rem">
          <label class="field-lbl" for="password">Password</label>
          <div style="position:relative">
            <i class="field-icon fa-solid fa-lock"></i>
            <input
              type="password"
              id="password"
              name="password"
              class="field-input pr <?= session('errors.password') ? 'is-invalid' : '' ?>"
              placeholder="Create a strong password"
              required
              autocomplete="new-password">
            <button type="button" class="eye-btn" onclick="togglePwd('password','pwdEye')" aria-label="Toggle password visibility">
              <i class="fa-regular fa-eye" id="pwdEye"></i>
            </button>
          </div>
          <div class="str-row">
            <div class="str-seg" id="ss1"></div>
            <div class="str-seg" id="ss2"></div>
            <div class="str-seg" id="ss3"></div>
            <div class="str-seg" id="ss4"></div>
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
          <?php if (session('errors.password')): ?>
            <div class="field-error show"><?= esc(session('errors.password')) ?></div>
          <?php endif; ?>
        </div>

        <!-- Confirm Password -->
        <div style="margin-bottom:1rem">
          <label class="field-lbl" for="confirm_password">Confirm Password</label>
          <div style="position:relative">
            <i class="field-icon fa-solid fa-lock"></i>
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              class="field-input pr <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>"
              placeholder="Re-enter your password"
              required
              autocomplete="new-password">
            <button type="button" class="eye-btn" onclick="togglePwd('confirm_password','cpwdEye')" aria-label="Toggle confirm password visibility">
              <i class="fa-regular fa-eye" id="cpwdEye"></i>
            </button>
          </div>
          <div class="pw-match" id="pwMatch"></div>
          <?php if (session('errors.confirm_password')): ?>
            <div class="field-error show"><?= esc(session('errors.confirm_password')) ?></div>
          <?php endif; ?>
        </div>

        <!-- Terms checkbox -->
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
    </div><!-- /.register-card -->
  </div><!-- /.register-wrapper -->

  <!-- ══ RESULT MODAL ══ -->
  <div class="overlay result-overlay" id="resultOverlay">
    <div class="result-box">
      <div class="result-icon-wrap" id="resultIconWrap"><i id="resultIconEl"></i></div>
      <h2 id="resultTitle"></h2>
      <p id="resultMsg"></p>
      <div class="result-actions" id="resultActions"></div>
      <div class="countdown-row" id="resultCountdown" style="display:none">
        <i class="fa-solid fa-clock"></i>
        <span>Redirecting in <span class="countdown-num" id="cdNum">5</span>s&hellip;</span>
      </div>
    </div>
  </div>

  <!-- ══ TERMS MODAL ══ -->
  <div class="overlay" id="termsOverlay">
    <div class="modal-box">
      <div class="modal-head">
        <div class="modal-head-left">
          <div class="modal-icon indigo"><i class="fa-solid fa-file-contract"></i></div>
          <div>
            <div class="modal-head-title">Terms of Service</div>
            <div class="modal-head-sub">E-Learning Resource Reservation System</div>
          </div>
        </div>
        <button class="modal-close-btn" onclick="closeModalT('terms')"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-prog-bar">
        <div class="modal-prog-fill fill-indigo" id="termsProg"></div>
      </div>
      <div class="modal-body" id="termsBody">
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num in">01</div>
            <h3>Acceptance of Terms</h3>
          </div>
          <p>By accessing and using the E-Learning Resource Reservation System of Brgy. F De Jesus, Unisan Quezon, you accept and agree to be bound by these Terms of Service. If you do not agree, you may not use this system.</p>
          <div class="ts-hl hl-in"><i class="fa-solid fa-circle-info" style="margin-right:5px"></i>These terms apply to all users including students, faculty, and administrators.</div>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num in">02</div>
            <h3>System Use &amp; Eligibility</h3>
          </div>
          <p>This system is intended exclusively for authorized members. You agree to:</p>
          <ul>
            <li class="li-in">Provide accurate and truthful information when creating an account or making a reservation.</li>
            <li class="li-in">Use reserved resources only during your approved reservation period.</li>
            <li class="li-in">Not share your login credentials with any other person.</li>
            <li class="li-in">Notify administrators of any unauthorized access to your account.</li>
          </ul>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num in">03</div>
            <h3>SK Officer Accounts</h3>
          </div>
          <p>SK Officer accounts require email verification followed by explicit approval from the Barangay Chairman. Registration alone does not grant portal access.</p>
          <div class="ts-hl hl-in"><i class="fa-solid fa-shield-halved" style="margin-right:5px"></i>You will be notified via email once your account has been reviewed.</div>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num in">04</div>
            <h3>Reservation Policy</h3>
          </div>
          <p>All reservations are subject to approval by authorized SK personnel or administrators.</p>
          <ul>
            <li class="li-in">Reservations are not confirmed until officially approved.</li>
            <li class="li-in">You must present your e-ticket QR code upon arrival.</li>
            <li class="li-in">Failure to appear within 15 minutes may result in cancellation.</li>
            <li class="li-in">Misuse of reserved resources may result in account suspension.</li>
          </ul>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num in">05</div>
            <h3>Responsible Use of Resources</h3>
          </div>
          <ul>
            <li class="li-in">Treat all equipment with care and report any damage immediately.</li>
            <li class="li-in">Not install unauthorized software or modify system settings.</li>
            <li class="li-in">Use resources solely for educational and approved purposes.</li>
            <li class="li-in">Leave the workstation clean and in its original condition after use.</li>
          </ul>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num in">06</div>
            <h3>Amendments</h3>
          </div>
          <p>These terms may be updated from time to time. Continued use of the system after changes constitutes your acceptance.</p>
          <p style="margin-top:6px;font-size:11px;color:var(--text-dimmer);font-weight:600">Last updated: <?= date('F j, Y') ?></p>
        </div>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-note"><i class="fa-solid fa-eye" style="color:var(--text-dimmer)"></i><span id="termsNote">Scroll through all sections to enable acceptance.</span></div>
        <div class="must-hint" id="termsMust"><i class="fa-solid fa-arrow-down" style="margin-right:4px"></i> Please scroll to the bottom to accept.</div>
        <div class="modal-foot-btns">
          <button class="btn-modal-cancel" onclick="closeModalT('terms')">Decline</button>
          <button class="btn-modal-accept c-in" id="termsAccept" onclick="acceptT('terms')" disabled>
            <i class="fa-solid fa-circle-check"></i> I Accept These Terms
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ PRIVACY MODAL ══ -->
  <div class="overlay" id="privacyOverlay">
    <div class="modal-box">
      <div class="modal-head">
        <div class="modal-head-left">
          <div class="modal-icon purple"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <div class="modal-head-title">Privacy Policy</div>
            <div class="modal-head-sub">E-Learning Resource Reservation System</div>
          </div>
        </div>
        <button class="modal-close-btn" onclick="closeModalT('privacy')"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-prog-bar">
        <div class="modal-prog-fill fill-purple" id="privacyProg"></div>
      </div>
      <div class="modal-body" id="privacyBody">
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">01</div>
            <h3>Introduction</h3>
          </div>
          <p>We are committed to full compliance with the Data Privacy Act of 2012 (RA 10173) of the Philippines.</p>
          <div class="ts-hl hl-pu"><i class="fa-solid fa-shield-halved" style="margin-right:5px"></i>Your data is protected under the Data Privacy Act of 2012 (Republic Act No. 10173).</div>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">02</div>
            <h3>Information We Collect</h3>
          </div>
          <ul>
            <li class="li-pu">Full name and contact details (email address).</li>
            <li class="li-pu">Login credentials (stored in encrypted form).</li>
            <li class="li-pu">Reservation history including dates, times, and resources used.</li>
            <li class="li-pu">Activity logs such as login timestamps and system actions.</li>
            <li class="li-pu">Device and browser information for security purposes.</li>
          </ul>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">03</div>
            <h3>How We Use Your Information</h3>
          </div>
          <ul>
            <li class="li-pu">To process and manage your resource reservations.</li>
            <li class="li-pu">To verify your identity and authenticate your access.</li>
            <li class="li-pu">To send reservation confirmations, updates, and e-tickets.</li>
            <li class="li-pu">To generate reports for barangay administration and accountability.</li>
            <li class="li-pu">To improve system functionality and user experience.</li>
          </ul>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">04</div>
            <h3>Data Sharing &amp; Disclosure</h3>
          </div>
          <p>We do not sell, rent, or trade your personal information to third parties.</p>
          <div class="ts-hl hl-pu"><i class="fa-solid fa-lock" style="margin-right:5px"></i>Your data is never sold or shared with advertisers or commercial third parties.</div>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">05</div>
            <h3>Data Retention</h3>
          </div>
          <p>We retain your personal information for as long as your account is active. Reservation records are kept for a minimum of one (1) year for audit and accountability purposes.</p>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">06</div>
            <h3>Data Security</h3>
          </div>
          <ul>
            <li class="li-pu">Password hashing using industry-standard encryption algorithms.</li>
            <li class="li-pu">Secure HTTPS connections for all data transmission.</li>
            <li class="li-pu">Role-based access controls limiting data access to authorized personnel only.</li>
            <li class="li-pu">Regular security reviews and activity monitoring.</li>
          </ul>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">07</div>
            <h3>Your Rights (RA 10173)</h3>
          </div>
          <ul>
            <li class="li-pu"><strong>Right to be informed</strong> — about how your data is collected and used.</li>
            <li class="li-pu"><strong>Right to access</strong> — to request a copy of your personal data.</li>
            <li class="li-pu"><strong>Right to rectification</strong> — to correct inaccurate or incomplete data.</li>
            <li class="li-pu"><strong>Right to erasure</strong> — to request deletion, subject to legal limits.</li>
            <li class="li-pu"><strong>Right to object</strong> — to processing for specific purposes.</li>
          </ul>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">08</div>
            <h3>Cookies &amp; Session Data</h3>
          </div>
          <p>This system uses session cookies to maintain your login state. These cookies do not track you across other websites.</p>
        </div>
        <div class="ts">
          <div class="ts-head">
            <div class="ts-num pu">09</div>
            <h3>Changes to This Policy</h3>
          </div>
          <p>We may update this Privacy Policy periodically to reflect changes in our practices or legal requirements.</p>
          <p style="margin-top:6px;font-size:11px;color:var(--text-dimmer);font-weight:600">Last updated: <?= date('F j, Y') ?></p>
        </div>
      </div>
      <div class="modal-foot">
        <div class="modal-foot-note"><i class="fa-solid fa-eye" style="color:var(--text-dimmer)"></i><span id="privacyNote">Scroll through all sections to enable acceptance.</span></div>
        <div class="must-hint" id="privacyMust"><i class="fa-solid fa-arrow-down" style="margin-right:4px"></i> Please scroll to the bottom to accept.</div>
        <div class="modal-foot-btns">
          <button class="btn-modal-cancel" onclick="closeModalT('privacy')">Close</button>
          <button class="btn-modal-accept c-pu" id="privacyAccept" onclick="acceptT('privacy')" disabled>
            <i class="fa-solid fa-shield-halved"></i> I Acknowledge This Policy
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // ── Flash data from CodeIgniter ──
    const FLASH_ERROR = <?= json_encode(session()->getFlashdata('error'))   ?? 'null' ?>;
    const FLASH_SUCCESS = <?= json_encode(session()->getFlashdata('success')) ?? 'null' ?>;
    const FLASH_INFO = <?= json_encode(session()->getFlashdata('info'))    ?? 'null' ?>;

    // ── SK Notice ──
    function onRoleChange(v) {
      document.getElementById('skNotice').classList.toggle('show', v === 'SK');
    }
    (function() {
      const r = document.getElementById('role');
      if (r.value) onRoleChange(r.value);
    })();

    // ── Password toggle ──
    function togglePwd(id, iconId) {
      const input = document.getElementById(id);
      const icon = document.getElementById(iconId);
      input.type = input.type === 'password' ? 'text' : 'password';
      icon.className = input.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
    }

    // ── Password strength ──
    const RULES = {
      'r-len': {
        test: p => p.length >= 8,
        label: 'At least 8 characters'
      },
      'r-up': {
        test: p => /[A-Z]/.test(p),
        label: 'One uppercase letter'
      },
      'r-lo': {
        test: p => /[a-z]/.test(p),
        label: 'One lowercase letter'
      },
      'r-num': {
        test: p => /\d/.test(p),
        label: 'One number'
      },
      'r-sp': {
        test: p => /[^A-Za-z0-9]/.test(p),
        label: 'One special character'
      },
    };
    const STRENGTH_C = ['', '#ef4444', '#f97316', '#eab308', '#22c55e'];
    const STRENGTH_L = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    const STRENGTH_LC = ['', '#fca5a5', '#fdba74', '#fde047', '#86efac'];

    document.getElementById('password').addEventListener('focus', function() {
      document.getElementById('pwReqs').style.display = 'block';
    });
    document.getElementById('password').addEventListener('input', function() {
      updateStrength(this.value);
      updateReqs(this.value);
      checkMatch();
    });
    document.getElementById('confirm_password').addEventListener('input', checkMatch);

    function updateStrength(p) {
      let sc = 0;
      if (p.length >= 8) sc++;
      if (/[A-Z]/.test(p)) sc++;
      if (/\d/.test(p)) sc++;
      if (/[^A-Za-z0-9]/.test(p)) sc++;
      [1, 2, 3, 4].forEach(j => {
        document.getElementById('ss' + j).style.background = j <= sc ? STRENGTH_C[sc] : 'var(--border)';
      });
      const lb = document.getElementById('strLbl');
      lb.textContent = p ? STRENGTH_L[sc] : '';
      lb.style.color = p ? STRENGTH_LC[sc] : 'var(--text-dimmer)';
    }

    function updateReqs(p) {
      for (const [id, rule] of Object.entries(RULES)) {
        const el = document.getElementById(id);
        const ok = rule.test(p);
        el.className = 'req' + (ok ? ' met' : '');
        el.innerHTML = (ok ?
          '<i class="fa-solid fa-circle-check"></i>' :
          '<i class="fa-regular fa-circle"></i>'
        ) + ' ' + rule.label;
      }
    }

    function checkMatch() {
      const p = document.getElementById('password').value;
      const c = document.getElementById('confirm_password').value;
      const m = document.getElementById('pwMatch');
      if (!c) {
        m.className = 'pw-match';
        return;
      }
      if (p !== c) {
        m.className = 'pw-match bad';
        m.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Passwords do not match';
      } else {
        m.className = 'pw-match good';
        m.innerHTML = '<i class="fa-solid fa-circle-check"></i> Passwords match';
      }
    }

    // ── Form submit client-side validation ──
    document.getElementById('regForm').addEventListener('submit', function(e) {
      const fn = document.getElementById('first_name').value.trim();
      const ln = document.getElementById('last_name').value.trim();
      const email = document.getElementById('email').value.trim();
      const role = document.getElementById('role').value;
      const pwd = document.getElementById('password').value;
      const cpwd = document.getElementById('confirm_password').value;
      const terms = document.getElementById('termsChk').checked;

      if (!fn || !ln || !email || !role || !pwd || !cpwd) {
        e.preventDefault();
        showResult('error', 'Missing Information', 'Please fill in all required fields before creating your account.', [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          cls: 'red',
          action: 'close'
        }]);
        return;
      }

      if (!terms) {
        e.preventDefault();
        showResult('error', 'Terms Not Accepted', 'You must agree to the Terms of Service and Privacy Policy.', [{
            label: 'Read Terms',
            icon: 'fa-file-contract',
            cls: 'blue',
            action: () => {
              closeResult();
              openModalT('terms');
            }
          },
          {
            label: 'Go Back',
            icon: 'fa-arrow-left',
            cls: 'red',
            action: 'close'
          }
        ]);
        return;
      }

      if (pwd !== cpwd) {
        e.preventDefault();
        showResult('error', "Passwords Don't Match", "The passwords you entered don't match. Please re-enter them.", [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          cls: 'red',
          action: 'close'
        }]);
        return;
      }

      if (pwd.length < 8) {
        e.preventDefault();
        showResult('error', 'Password Too Short', 'Your password must be at least 8 characters long.', [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          cls: 'red',
          action: 'close'
        }]);
        return;
      }

      // All good — show loading state
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<div class="spin"></div> Creating account&hellip;';
    });

    // ── Result modal ──
    let cdTimer = null;

    function showResult(type, title, msg, actions, countdown) {
      const ov = document.getElementById('resultOverlay');
      const wrap = document.getElementById('resultIconWrap');
      const ic = document.getElementById('resultIconEl');
      const icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation'
      };

      wrap.className = 'result-icon-wrap ' + type;
      ic.className = 'fa-solid ' + (icons[type] || 'fa-circle-info');
      document.getElementById('resultTitle').textContent = title;
      document.getElementById('resultMsg').textContent = msg;

      const actEl = document.getElementById('resultActions');
      actEl.innerHTML = '';
      (actions || []).forEach(a => {
        const btn = document.createElement('button');
        btn.className = 'btn-result ' + (a.cls || 'blue');
        btn.innerHTML = `<i class="fa-solid ${a.icon || 'fa-check'}"></i> ${a.label}`;
        btn.onclick = () => {
          if (a.action === 'close') closeResult();
          else if (a.action === 'login') window.location.href = '/login';
          else if (typeof a.action === 'function') a.action();
        };
        actEl.appendChild(btn);
      });

      const cdWrap = document.getElementById('resultCountdown');
      const cdNum = document.getElementById('cdNum');
      clearInterval(cdTimer);
      if (countdown) {
        cdWrap.style.display = 'flex';
        cdNum.textContent = countdown;
        cdNum.className = 'countdown-num ' + (type === 'success' ? 'green' : 'amber');
        let left = countdown;
        cdTimer = setInterval(() => {
          left--;
          cdNum.textContent = left;
          if (left <= 0) {
            clearInterval(cdTimer);
            window.location.href = '/login';
          }
        }, 1000);
      } else {
        cdWrap.style.display = 'none';
      }

      ov.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeResult() {
      document.getElementById('resultOverlay').classList.remove('open');
      document.body.style.overflow = '';
      clearInterval(cdTimer);
    }

    document.getElementById('resultOverlay').addEventListener('click', function(e) {
      if (e.target === this) closeResult();
    });

    // ── Show flash messages on page load ──
    window.addEventListener('DOMContentLoaded', () => {
      if (FLASH_SUCCESS) {
        const isSK = FLASH_SUCCESS.toLowerCase().includes('pending') || FLASH_SUCCESS.toLowerCase().includes('chairman');
        if (isSK) {
          showResult('warning', 'Account Created — Pending Approval', FLASH_SUCCESS, [{
            label: 'Go to Login',
            icon: 'fa-right-to-bracket',
            cls: 'amber',
            action: 'login'
          }], 8);
        } else {
          showResult('success', 'Account Created!', FLASH_SUCCESS, [{
            label: 'Sign In Now',
            icon: 'fa-right-to-bracket',
            cls: 'green',
            action: 'login'
          }], 5);
        }
      } else if (FLASH_INFO) {
        showResult('warning', 'One More Step', FLASH_INFO, [{
          label: 'Go to Login',
          icon: 'fa-right-to-bracket',
          cls: 'amber',
          action: 'login'
        }], 8);
      } else if (FLASH_ERROR) {
        showResult('error', 'Registration Failed', FLASH_ERROR, [{
          label: 'Try Again',
          icon: 'fa-rotate-left',
          cls: 'red',
          action: 'close'
        }]);
      }
    });

    // ── Terms / Privacy modals ──
    const mRead = {
      terms: false,
      privacy: false
    };

    function openModalT(t) {
      const ov = document.getElementById(t + 'Overlay');
      const body = document.getElementById(t + 'Body');
      ov.classList.add('open');
      document.body.style.overflow = 'hidden';
      body.scrollTop = 0;
      updProg(t);
    }

    function closeModalT(t) {
      document.getElementById(t + 'Overlay').classList.remove('open');
      document.body.style.overflow = '';
    }

    function acceptT(t) {
      if (!mRead[t]) {
        document.getElementById(t + 'Must').classList.add('show');
        return;
      }
      closeModalT(t);
      if (t === 'terms') document.getElementById('termsChk').checked = true;
      showToast(t === 'terms' ? 'Terms of Service accepted.' : 'Privacy Policy acknowledged.', 'success');
    }

    function updProg(t) {
      const b = document.getElementById(t + 'Body');
      const tot = b.scrollHeight - b.clientHeight;
      const pct = tot > 0 ? Math.min(100, Math.round((b.scrollTop / tot) * 100)) : 100;
      document.getElementById(t + 'Prog').style.width = pct + '%';

      if (pct >= 95 && !mRead[t]) {
        mRead[t] = true;
        const btn = document.getElementById(t + 'Accept');
        const note = document.getElementById(t + 'Note');
        btn.disabled = false;
        note.textContent = 'You have reviewed the policy. You may now accept.';
        note.style.color = t === 'terms' ? '#a5b4fc' : '#c4b5fd';
        document.getElementById(t + 'Must').classList.remove('show');
      }
    }

    ['terms', 'privacy'].forEach(t => {
      document.getElementById(t + 'Body').addEventListener('scroll', () => updProg(t));
      document.getElementById(t + 'Overlay').addEventListener('click', function(e) {
        if (e.target === this) closeModalT(t);
      });
    });

    // ── Escape key closes all modals ──
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        closeResult();
        ['terms', 'privacy'].forEach(closeModalT);
      }
    });

    // ── Toast notification ──
    function showToast(msg, type) {
      const ex = document.getElementById('_toast');
      if (ex) ex.remove();

      const c = {
        success: {
          bg: 'rgba(34,197,94,.12)',
          br: 'rgba(34,197,94,.25)',
          tx: '#86efac',
          ic: 'fa-circle-check'
        },
        error: {
          bg: 'rgba(239,68,68,.12)',
          br: 'rgba(239,68,68,.25)',
          tx: '#fca5a5',
          ic: 'fa-circle-exclamation'
        },
        info: {
          bg: 'rgba(99,102,241,.12)',
          br: 'rgba(99,132,255,.25)',
          tx: '#a5b4fc',
          ic: 'fa-circle-info'
        },
      } [type] || {
        bg: 'rgba(99,102,241,.12)',
        br: 'rgba(99,132,255,.25)',
        tx: '#a5b4fc',
        ic: 'fa-circle-info'
      };

      const toast = document.createElement('div');
      toast.id = '_toast';
      toast.style.cssText = `
    position:fixed; bottom:2rem; left:50%; transform:translateX(-50%);
    background:${c.bg}; border:1px solid ${c.br}; color:${c.tx};
    padding:11px 18px; border-radius:12px;
    font-family:var(--font); font-size:13px; font-weight:600;
    box-shadow:0 8px 28px rgba(0,0,0,.4); z-index:9999;
    white-space:nowrap; display:flex; align-items:center; gap:8px;
    animation:toastIn .3s ease; backdrop-filter:blur(12px);
  `;
      toast.innerHTML = `<i class="fa-solid ${c.ic}"></i>${msg}`;
      document.body.appendChild(toast);
      setTimeout(() => {
        toast.style.transition = 'opacity .4s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 400);
      }, type === 'success' ? 4500 : 3500);
    }

    // ── Service Worker ──
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js', {
          scope: '/'
        }).catch(() => {});
      });
    }
  </script>
</body>

</html> $flashError = session()->getFlashdata('error');
$flashSuccess = session()->getFlashdata('success');
$flashInfo = session()->getFlashdata('info');
?>

<!-- SK notice -->
<div class="sk-notice" id="skNotice">
  <i class="fa-solid fa-shield-halved"></i>
  <p><strong>SK Officer Registration:</strong> After verifying your email, your account requires <strong>approval by the Barangay Chairman</strong> before you can log in. You will be notified via email once a decision has been made.</p>
</div>

<form action="/register-action" method="post" id="regForm" novalidate>
  <?= csrf_field() ?>

  <!-- Full Name -->
  <div class="input-wrapper">
    <label for="full_name">Full Name</label>
    <div class="input-container">
      <i class="fa-regular fa-user input-icon"></i>
      <input type="text" id="full_name" name="full_name"
        placeholder="Juan Dela Cruz"
        value="<?= esc(old('full_name')) ?>"
        autocomplete="name" required>
    </div>
  </div>

  <!-- Email -->
  <div class="input-wrapper">
    <label for="email">Email Address</label>
    <div class="input-container">
      <i class="fa-regular fa-envelope input-icon"></i>
      <input type="email" id="email" name="email"
        placeholder="juan@example.com"
        value="<?= esc(old('email')) ?>"
        autocomplete="email" required>
    </div>
  </div>

  <!-- Role -->
  <div class="input-wrapper">
    <label for="role">I am a…</label>
    <div class="input-container select-wrapper">
      <i class="fa-solid fa-id-badge input-icon"></i>
      <select name="role" id="role" required onchange="onRoleChange(this.value)">
        <option value="" disabled selected>Select your role</option>
        <option value="resident" <?= old('role') === 'resident' ? 'selected' : '' ?>>Resident</option>
        <option value="SK" <?= old('role') === 'SK'       ? 'selected' : '' ?>>SK Officer</option>
      </select>
    </div>
  </div>

  <!-- Password -->
  <div class="input-wrapper">
    <label for="password">Password</label>
    <div class="input-container">
      <i class="fa-solid fa-lock input-icon"></i>
      <div class="password-wrapper">
        <input type="password" id="password" name="password"
          placeholder="Create a strong password"
          autocomplete="new-password" required>
        <button type="button" class="password-toggle" onclick="togglePw('password','iconPw')" aria-label="Toggle">
          <i class="fa-regular fa-eye" id="iconPw"></i>
        </button>
      </div>
    </div>
    <div class="pw-reqs" id="pwReqs" style="display:none">
      <div class="pw-reqs-title">Password must contain:</div>
      <div class="req" id="r-len"><i class="fa-regular fa-circle"></i> At least 8 characters</div>
      <div class="req" id="r-up"> <i class="fa-regular fa-circle"></i> One uppercase letter</div>
      <div class="req" id="r-lo"> <i class="fa-regular fa-circle"></i> One lowercase letter</div>
      <div class="req" id="r-num"><i class="fa-regular fa-circle"></i> One number</div>
      <div class="req" id="r-sp"> <i class="fa-regular fa-circle"></i> One special character</div>
    </div>
  </div>

  <!-- Confirm Password -->
  <div class="input-wrapper">
    <label for="confirm_password">Confirm Password</label>
    <div class="input-container">
      <i class="fa-solid fa-lock input-icon"></i>
      <div class="password-wrapper">
        <input type="password" id="confirm_password" name="confirm_password"
          placeholder="Re-enter your password"
          autocomplete="new-password" required>
        <button type="button" class="password-toggle" onclick="togglePw('confirm_password','iconCpw')" aria-label="Toggle">
          <i class="fa-regular fa-eye" id="iconCpw"></i>
        </button>
      </div>
    </div>
    <div class="pw-match" id="pwMatch"></div>
  </div>

  <!-- Terms checkbox -->
  <label class="checkbox-wrapper">
    <input type="checkbox" name="terms" id="termsChk" class="checkbox-custom" required>
    <span class="checkbox-label">
      I agree to the
      <a href="#" class="text-link" onclick="openModal('terms');return false;">Terms of Service</a>
      and
      <a href="#" class="text-link" onclick="openModal('privacy');return false;">Privacy Policy</a>
    </span>
  </label>

  <button type="submit" class="btn-primary" id="submitBtn">
    <i class="fa-solid fa-user-plus mr-2"></i> Create Account
  </button>

  <div class="divider"><span>or</span></div>

  <div class="text-center text-sm">
    <span class="text-slate-400 font-medium">Already have an account?</span>
    <a href="/login" class="text-link ml-1">Sign In</a>
  </div>

</form>

<div class="footer-links">
  <p>By creating an account, you agree to our
    <button onclick="openModal('terms')">Terms of Service</button> and
    <button onclick="openModal('privacy')">Privacy Policy</button>
  </p>
</div>

</div>
</div>

<!-- RESULT MODAL -->
<div class="result-modal-backdrop" id="resultModal" role="dialog" aria-modal="true">
  <div class="result-modal">
    <div class="result-icon-wrap" id="resultIcon">
      <i id="resultIconI"></i>
    </div>
    <h2 id="resultTitle"></h2>
    <p id="resultMsg"></p>
    <div class="result-modal-actions" id="resultActions"></div>
    <div class="countdown-wrap" id="resultCountdown" style="display:none">
      <i class="fa-solid fa-clock"></i>
      <span>Redirecting in <span class="countdown-num" id="countdownNum">5</span>s…</span>
    </div>
  </div>
</div>

<!-- TERMS OF SERVICE MODAL -->
<div class="modal-backdrop" id="termsModal" role="dialog" aria-modal="true" aria-labelledby="termsTitleText">
  <div class="modal">
    <div class="modal-header modal-header-bg-blue">
      <div class="modal-header-left">
        <div class="modal-icon blue"><i class="fa-solid fa-file-contract"></i></div>
        <div>
          <h2 id="termsTitleText">Terms of Service</h2>
          <p>E-Learning Resource Reservation System</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('terms')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-progress">
      <div class="modal-progress-bar progress-blue" id="termsProgress"></div>
    </div>
    <div class="modal-body" id="termsBody">
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">01</div>
          <h3>Acceptance of Terms</h3>
        </div>
        <p>By accessing and using the E-Learning Resource Reservation System of Brgy. F De Jesus, Unisan Quezon, you accept and agree to be bound by these Terms of Service.</p>
        <div class="terms-highlight highlight-blue"><i class="fa-solid fa-circle-info"></i>These terms apply to all users including residents, SK officers, and administrators.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">02</div>
          <h3>System Use & Eligibility</h3>
        </div>
        <p>This system is intended exclusively for authorized members of Brgy. F De Jesus, Unisan Quezon. You agree to:</p>
        <ul>
          <li class="li-blue">Provide accurate and truthful information when registering or making a reservation.</li>
          <li class="li-blue">Use reserved resources only during your approved reservation period.</li>
          <li class="li-blue">Not share your login credentials with any other person.</li>
          <li class="li-blue">Notify administrators of any unauthorized access to your account.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">03</div>
          <h3>SK Officer Accounts</h3>
        </div>
        <p>SK Officer accounts require email verification followed by explicit approval from the Barangay Chairman. Registration alone does not grant access to the SK portal.</p>
        <div class="terms-highlight highlight-blue"><i class="fa-solid fa-shield-halved"></i>You will receive an email notification once your account has been reviewed.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">04</div>
          <h3>Reservation Policy</h3>
        </div>
        <p>All reservations are subject to approval by authorized SK personnel or administrators. By submitting a reservation, you acknowledge:</p>
        <ul>
          <li class="li-blue">Reservations are not confirmed until officially approved.</li>
          <li class="li-blue">You must present your e-ticket QR code upon arrival to claim your reservation.</li>
          <li class="li-blue">Failure to appear within 15 minutes of your reserved start time may result in cancellation.</li>
          <li class="li-blue">Misuse of reserved resources may result in account suspension.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">05</div>
          <h3>Responsible Use of Resources</h3>
        </div>
        <p>Users are responsible for the proper care of all reserved e-learning equipment and facilities.</p>
        <ul>
          <li class="li-blue">Treat all equipment with care and report any damage immediately.</li>
          <li class="li-blue">Do not install unauthorized software or modify system settings.</li>
          <li class="li-blue">Use resources solely for educational and approved purposes.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">06</div>
          <h3>Amendments</h3>
        </div>
        <p>These terms may be updated from time to time. Continued use of the system after changes are posted constitutes your acceptance of the revised terms.</p>
        <p style="margin-top:0.5rem;font-size:0.78rem;color:#94a3b8;font-weight:600;">Last updated: <?= date('F j, Y') ?></p>
      </div>
    </div>
    <div class="modal-footer">
      <div class="modal-footer-note">
        <i class="fa-solid fa-eye"></i>
        <span id="termsReadNote">Scroll through all sections to enable acceptance.</span>
      </div>
      <p class="must-read-hint" id="termsMustRead"><i class="fa-solid fa-arrow-down mr-1"></i> Please scroll to the bottom to accept.</p>
      <div class="modal-footer-actions">
        <button class="btn-modal-decline" onclick="closeModal('terms')">Decline</button>
        <button class="btn-modal-accept blue-btn" id="termsAcceptBtn" onclick="acceptTerms()" disabled>
          <i class="fa-solid fa-circle-check"></i> I Accept These Terms
        </button>
      </div>
    </div>
  </div>
</div>

<!-- PRIVACY POLICY MODAL -->
<div class="modal-backdrop" id="privacyModal" role="dialog" aria-modal="true" aria-labelledby="privacyTitleText">
  <div class="modal">
    <div class="modal-header modal-header-bg-purple">
      <div class="modal-header-left">
        <div class="modal-icon purple"><i class="fa-solid fa-shield-halved"></i></div>
        <div>
          <h2 id="privacyTitleText">Privacy Policy</h2>
          <p>E-Learning Resource Reservation System</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('privacy')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-progress">
      <div class="modal-progress-bar progress-purple" id="privacyProgress"></div>
    </div>
    <div class="modal-body" id="privacyBody">
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">01</div>
          <h3>Introduction</h3>
        </div>
        <p>The E-Learning Resource Reservation System operated by Brgy. F De Jesus, Unisan Quezon is committed to protecting your personal data in full compliance with the Data Privacy Act of 2012 (RA 10173).</p>
        <div class="terms-highlight highlight-purple"><i class="fa-solid fa-shield-halved"></i>Your data is never sold or shared with advertisers or commercial third parties.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">02</div>
          <h3>Information We Collect</h3>
        </div>
        <ul>
          <li class="li-purple">Full name and email address.</li>
          <li class="li-purple">Login credentials (stored encrypted — never in plain text).</li>
          <li class="li-purple">Reservation history including dates, times, and resources used.</li>
          <li class="li-purple">Activity logs such as login timestamps and system actions.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">03</div>
          <h3>How We Use Your Information</h3>
        </div>
        <ul>
          <li class="li-purple">To process and manage your resource reservations.</li>
          <li class="li-purple">To verify your identity and authenticate your access.</li>
          <li class="li-purple">To send reservation confirmations and e-tickets via email.</li>
          <li class="li-purple">To generate reports for barangay administration and accountability.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">04</div>
          <h3>Data Security</h3>
        </div>
        <p>We implement appropriate technical measures to protect your personal data:</p>
        <ul>
          <li class="li-purple">Password hashing using industry-standard encryption algorithms.</li>
          <li class="li-purple">Secure HTTPS connections for all data transmission.</li>
          <li class="li-purple">Role-based access controls limiting data access to authorized personnel only.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">05</div>
          <h3>Your Rights</h3>
        </div>
        <p>Under the Data Privacy Act of 2012, you have the right to access, correct, and request deletion of your personal data. Contact the barangay administration to exercise these rights.</p>
        <p style="margin-top:0.5rem;font-size:0.78rem;color:#94a3b8;font-weight:600;">Last updated: <?= date('F j, Y') ?></p>
      </div>
    </div>
    <div class="modal-footer">
      <div class="modal-footer-note">
        <i class="fa-solid fa-eye"></i>
        <span id="privacyReadNote">Scroll through all sections to enable acceptance.</span>
      </div>
      <p class="must-read-hint" id="privacyMustRead"><i class="fa-solid fa-arrow-down mr-1"></i> Please scroll to the bottom to accept.</p>
      <div class="modal-footer-actions">
        <button class="btn-modal-decline" onclick="closeModal('privacy')">Close</button>
        <button class="btn-modal-accept purple-btn" id="privacyAcceptBtn" onclick="acceptModal('privacy')" disabled>
          <i class="fa-solid fa-shield-halved"></i> I Acknowledge This Policy
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const FLASH_ERROR = <?= json_encode($flashError) ?>;
  const FLASH_SUCCESS = <?= json_encode($flashSuccess) ?>;
  const FLASH_INFO = <?= json_encode($flashInfo) ?>;

  function onRoleChange(v) {
    document.getElementById('skNotice').classList.toggle('show', v === 'SK');
  }
  (function() {
    const r = document.getElementById('role');
    if (r.value) onRoleChange(r.value);
  })();

  function togglePw(inputId, iconId) {
    const el = document.getElementById(inputId);
    const ic = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.className = el.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
  }

  const RULES = {
    'r-len': p => p.length >= 8,
    'r-up': p => /[A-Z]/.test(p),
    'r-lo': p => /[a-z]/.test(p),
    'r-num': p => /[0-9]/.test(p),
    'r-sp': p => /[^A-Za-z0-9]/.test(p),
  };
  const LABELS = {
    'r-len': 'At least 8 characters',
    'r-up': 'One uppercase letter',
    'r-lo': 'One lowercase letter',
    'r-num': 'One number',
    'r-sp': 'One special character',
  };

  const pwEl = document.getElementById('password');
  const cpwEl = document.getElementById('confirm_password');

  pwEl.addEventListener('focus', () => document.getElementById('pwReqs').style.display = 'block');
  pwEl.addEventListener('input', () => {
    updateReqs(pwEl.value);
    checkMatch();
  });
  cpwEl.addEventListener('input', checkMatch);

  function updateReqs(pwd) {
    for (const [id, fn] of Object.entries(RULES)) {
      const el = document.getElementById(id);
      const ok = fn(pwd);
      el.className = 'req' + (ok ? ' met' : '');
      el.innerHTML = (ok ?
        '<i class="fa-solid fa-circle-check"></i>' :
        '<i class="fa-regular fa-circle"></i>') + ' ' + LABELS[id];
    }
  }

  function checkMatch() {
    const m = document.getElementById('pwMatch');
    if (!cpwEl.value) {
      m.className = 'pw-match';
      return;
    }
    if (cpwEl.value !== pwEl.value) {
      m.className = 'pw-match bad';
      m.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Passwords do not match';
    } else {
      m.className = 'pw-match good';
      m.innerHTML = '<i class="fa-solid fa-circle-check"></i> Passwords match';
    }
  }

  document.getElementById('regForm').addEventListener('submit', function(e) {
    const name = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const role = document.getElementById('role').value;
    const pwd = pwEl.value;
    const cpwd = cpwEl.value;
    const terms = document.getElementById('termsChk').checked;

    if (!name || !email || !role || !pwd || !cpwd) {
      e.preventDefault();
      showResultModal('error', 'Missing Information', 'Please fill in all required fields before creating your account.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (pwd !== cpwd) {
      e.preventDefault();
      showResultModal('error', "Passwords Don't Match", 'The passwords you entered do not match. Please re-enter them carefully.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (pwd.length < 8) {
      e.preventDefault();
      showResultModal('error', 'Password Too Short', 'Your password must be at least 8 characters long.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (!terms) {
      e.preventDefault();
      showResultModal('error', 'Terms Not Accepted', 'You must agree to the Terms of Service and Privacy Policy to create an account.',
        [{
            label: 'Read Terms',
            icon: 'fa-file-contract',
            color: 'blue',
            action: () => {
              closeResultModal();
              openModal('terms');
            }
          },
          {
            label: 'Go Back',
            icon: 'fa-arrow-left',
            color: 'red',
            action: 'close'
          },
        ]);
      return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Creating account…';
  });

  let countdownTimer = null;

  function showResultModal(type, title, message, actions, countdown) {
    const backdrop = document.getElementById('resultModal');
    const iconWrap = document.getElementById('resultIcon');
    const iconEl = document.getElementById('resultIconI');
    const titleEl = document.getElementById('resultTitle');
    const msgEl = document.getElementById('resultMsg');
    const actionsEl = document.getElementById('resultActions');
    const cdWrap = document.getElementById('resultCountdown');
    const cdNum = document.getElementById('countdownNum');

    iconWrap.className = 'result-icon-wrap ' + type;
    const icons = {
      success: 'fa-circle-check',
      error: 'fa-circle-xmark',
      warning: 'fa-triangle-exclamation'
    };
    iconEl.className = 'fa-solid ' + (icons[type] || 'fa-circle-info');
    titleEl.textContent = title;
    msgEl.textContent = message;

    actionsEl.innerHTML = '';
    (actions || []).forEach(a => {
      const btn = document.createElement('button');
      btn.className = 'btn-result-primary ' + (a.color || 'blue');
      btn.innerHTML = `<i class="fa-solid ${a.icon || 'fa-check'}"></i> ${a.label}`;
      btn.onclick = () => {
        if (a.action === 'close') closeResultModal();
        else if (a.action === 'login') window.location.href = '/login';
        else if (typeof a.action === 'function') a.action();
      };
      actionsEl.appendChild(btn);
    });

    if (countdown) {
      cdWrap.style.display = 'flex';
      cdNum.textContent = countdown;
      cdNum.style.color = type === 'success' ? '#22c55e' : '#f59e0b';
      let left = countdown;
      countdownTimer = setInterval(() => {
        left--;
        cdNum.textContent = left;
        if (left <= 0) {
          clearInterval(countdownTimer);
          window.location.href = '/login';
        }
      }, 1000);
    } else {
      cdWrap.style.display = 'none';
      clearInterval(countdownTimer);
    }

    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeResultModal() {
    document.getElementById('resultModal').classList.remove('open');
    document.body.style.overflow = '';
    clearInterval(countdownTimer);
  }

  document.getElementById('resultModal').addEventListener('click', function(e) {
    if (e.target === this) closeResultModal();
  });

  window.addEventListener('DOMContentLoaded', () => {
    if (FLASH_SUCCESS) {
      const isSK = FLASH_SUCCESS.toLowerCase().includes('pending') || FLASH_SUCCESS.toLowerCase().includes('chairman');
      if (isSK) {
        showResultModal('warning', 'Account Created — Pending Approval', FLASH_SUCCESS,
          [{
            label: 'Go to Login',
            icon: 'fa-right-to-bracket',
            color: 'amber',
            action: 'login'
          }], 8);
      } else {
        showResultModal('success', 'Account Created!', FLASH_SUCCESS,
          [{
            label: 'Go to Login',
            icon: 'fa-right-to-bracket',
            color: 'green',
            action: 'login'
          }], 5);
      }
    } else if (FLASH_INFO) {
      showResultModal('warning', 'One More Step', FLASH_INFO,
        [{
          label: 'Go to Login',
          icon: 'fa-right-to-bracket',
          color: 'amber',
          action: 'login'
        }], 8);
    } else if (FLASH_ERROR) {
      showResultModal('error', 'Registration Failed', FLASH_ERROR,
        [{
          label: 'Try Again',
          icon: 'fa-rotate-left',
          color: 'red',
          action: 'close'
        }]);
    }
  });

  const modalState = {
    terms: false,
    privacy: false
  };

  function openModal(type) {
    const modal = document.getElementById(type + 'Modal');
    const body = document.getElementById(type + 'Body');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    body.scrollTop = 0;
    updateProgress(type);
  }

  function closeModal(type) {
    document.getElementById(type + 'Modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  function acceptTerms() {
    if (!modalState['terms']) {
      document.getElementById('termsMustRead').classList.add('visible');
      return;
    }
    closeModal('terms');
    document.getElementById('termsChk').checked = true;
    showToast('Terms of Service accepted.', 'success');
  }

  function acceptModal(type) {
    if (!modalState[type]) {
      document.getElementById(type + 'MustRead').classList.add('visible');
      return;
    }
    closeModal(type);
    showToast(type === 'terms' ? 'Terms of Service accepted.' : 'Privacy Policy acknowledged.', 'success');
  }

  function updateProgress(type) {
    const body = document.getElementById(type + 'Body');
    const pct = body.scrollHeight <= body.clientHeight ?
      100 :
      Math.min(100, Math.round((body.scrollTop / (body.scrollHeight - body.clientHeight)) * 100));
    document.getElementById(type + 'Progress').style.width = pct + '%';
    if (pct >= 95 && !modalState[type]) {
      modalState[type] = true;
      const btn = document.getElementById(type + 'AcceptBtn');
      const note = document.getElementById(type + 'ReadNote');
      btn.disabled = false;
      note.textContent = 'You have reviewed the policy. You may now accept.';
      note.style.color = type === 'terms' ? '#2563eb' : '#7c3aed';
      document.getElementById(type + 'MustRead').classList.remove('visible');
    }
  }

  ['terms', 'privacy'].forEach(type => {
    document.getElementById(type + 'Body').addEventListener('scroll', () => updateProgress(type));
    document.getElementById(type + 'Modal').addEventListener('click', function(e) {
      if (e.target === this) closeModal(type);
    });
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      closeResultModal();
      ['terms', 'privacy'].forEach(closeModal);
    }
  });

  function showToast(message, type) {
    const existing = document.getElementById('appToast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.id = 'appToast';
    toast.style.cssText = `
        position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);
        background:${type==='success'?'#f0fdf4':'#fef2f2'};
        border:1px solid ${type==='success'?'#bbf7d0':'#fecaca'};
        color:${type==='success'?'#166534':'#991b1b'};
        padding:0.75rem 1.25rem;border-radius:14px;
        font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,0.1);z-index:3000;white-space:nowrap;
        animation:slideIn 0.3s ease;
      `;
    toast.innerHTML = `<i class="fa-solid ${type==='success'?'fa-circle-check':'fa-circle-exclamation'} mr-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.style.transition = 'opacity 0.4s';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }
</script>
</body>

</html>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AUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAH/2Q==" alt="SK Brgy. F De Jesus" style="width:100%;height:100%;object-fit:contain;"></div>
<h1>Create Account</h1>
<p>Brgy. F De Jesus, Unisan Quezon</p>
</div>

<div class="welcome-text">
  <h3>E-Learning Resource Reservation System</h3>
  <p>Register to start booking resources for your sessions</p>
</div>

<?php
      $flashError   = session()->getFlashdata('error');
      $flashSuccess = session()->getFlashdata('success');
      $flashInfo    = session()->getFlashdata('info');
      ?>

<!-- SK notice -->
<div class="sk-notice" id="skNotice">
  <i class="fa-solid fa-shield-halved"></i>
  <p><strong>SK Officer Registration:</strong> After verifying your email, your account requires <strong>approval by the Barangay Chairman</strong> before you can log in. You will be notified via email once a decision has been made.</p>
</div>

<form action="/register-action" method="post" id="regForm" novalidate>
  <?= csrf_field() ?>

  <!-- Full Name -->
  <div class="input-wrapper">
    <label for="full_name">Full Name</label>
    <div class="input-container">
      <i class="fa-regular fa-user input-icon"></i>
      <input type="text" id="full_name" name="full_name"
        placeholder="Juan Dela Cruz"
        value="<?= esc(old('full_name')) ?>"
        autocomplete="name" required>
    </div>
  </div>

  <!-- Email -->
  <div class="input-wrapper">
    <label for="email">Email Address</label>
    <div class="input-container">
      <i class="fa-regular fa-envelope input-icon"></i>
      <input type="email" id="email" name="email"
        placeholder="juan@example.com"
        value="<?= esc(old('email')) ?>"
        autocomplete="email" required>
    </div>
  </div>

  <!-- Role -->
  <div class="input-wrapper">
    <label for="role">I am a…</label>
    <div class="input-container select-wrapper">
      <i class="fa-solid fa-id-badge input-icon"></i>
      <select name="role" id="role" required onchange="onRoleChange(this.value)">
        <option value="" disabled selected>Select your role</option>
        <option value="resident" <?= old('role') === 'resident' ? 'selected' : '' ?>>Resident</option>
        <option value="SK" <?= old('role') === 'SK'       ? 'selected' : '' ?>>SK Officer</option>
      </select>
    </div>
  </div>

  <!-- Password -->
  <div class="input-wrapper">
    <label for="password">Password</label>
    <div class="input-container">
      <i class="fa-solid fa-lock input-icon"></i>
      <div class="password-wrapper">
        <input type="password" id="password" name="password"
          placeholder="Create a strong password"
          autocomplete="new-password" required>
        <button type="button" class="password-toggle" onclick="togglePw('password','iconPw')" aria-label="Toggle">
          <i class="fa-regular fa-eye" id="iconPw"></i>
        </button>
      </div>
    </div>
    <div class="pw-reqs" id="pwReqs" style="display:none">
      <div class="pw-reqs-title">Password must contain:</div>
      <div class="req" id="r-len"><i class="fa-regular fa-circle"></i> At least 8 characters</div>
      <div class="req" id="r-up"> <i class="fa-regular fa-circle"></i> One uppercase letter</div>
      <div class="req" id="r-lo"> <i class="fa-regular fa-circle"></i> One lowercase letter</div>
      <div class="req" id="r-num"><i class="fa-regular fa-circle"></i> One number</div>
      <div class="req" id="r-sp"> <i class="fa-regular fa-circle"></i> One special character</div>
    </div>
  </div>

  <!-- Confirm Password -->
  <div class="input-wrapper">
    <label for="confirm_password">Confirm Password</label>
    <div class="input-container">
      <i class="fa-solid fa-lock input-icon"></i>
      <div class="password-wrapper">
        <input type="password" id="confirm_password" name="confirm_password"
          placeholder="Re-enter your password"
          autocomplete="new-password" required>
        <button type="button" class="password-toggle" onclick="togglePw('confirm_password','iconCpw')" aria-label="Toggle">
          <i class="fa-regular fa-eye" id="iconCpw"></i>
        </button>
      </div>
    </div>
    <div class="pw-match" id="pwMatch"></div>
  </div>

  <!-- Terms checkbox -->
  <label class="checkbox-wrapper">
    <input type="checkbox" name="terms" id="termsChk" class="checkbox-custom" required>
    <span class="checkbox-label">
      I agree to the
      <a href="#" class="text-link" onclick="openModal('terms');return false;">Terms of Service</a>
      and
      <a href="#" class="text-link" onclick="openModal('privacy');return false;">Privacy Policy</a>
    </span>
  </label>

  <button type="submit" class="btn-primary" id="submitBtn">
    <i class="fa-solid fa-user-plus mr-2"></i> Create Account
  </button>

  <div class="divider"><span>or</span></div>

  <div class="text-center text-sm">
    <span class="text-slate-400 font-medium">Already have an account?</span>
    <a href="/login" class="text-link ml-1">Sign In</a>
  </div>

</form>

<div class="footer-links">
  <p>By creating an account, you agree to our
    <button onclick="openModal('terms')">Terms of Service</button> and
    <button onclick="openModal('privacy')">Privacy Policy</button>
  </p>
</div>

</div>
</div>

<!-- RESULT MODAL -->
<div class="result-modal-backdrop" id="resultModal" role="dialog" aria-modal="true">
  <div class="result-modal">
    <div class="result-icon-wrap" id="resultIcon">
      <i id="resultIconI"></i>
    </div>
    <h2 id="resultTitle"></h2>
    <p id="resultMsg"></p>
    <div class="result-modal-actions" id="resultActions"></div>
    <div class="countdown-wrap" id="resultCountdown" style="display:none">
      <i class="fa-solid fa-clock"></i>
      <span>Redirecting in <span class="countdown-num" id="countdownNum">5</span>s…</span>
    </div>
  </div>
</div>

<!-- TERMS OF SERVICE MODAL -->
<div class="modal-backdrop" id="termsModal" role="dialog" aria-modal="true" aria-labelledby="termsTitleText">
  <div class="modal">
    <div class="modal-header modal-header-bg-blue">
      <div class="modal-header-left">
        <div class="modal-icon blue"><i class="fa-solid fa-file-contract"></i></div>
        <div>
          <h2 id="termsTitleText">Terms of Service</h2>
          <p>E-Learning Resource Reservation System</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('terms')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-progress">
      <div class="modal-progress-bar progress-blue" id="termsProgress"></div>
    </div>
    <div class="modal-body" id="termsBody">
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">01</div>
          <h3>Acceptance of Terms</h3>
        </div>
        <p>By accessing and using the E-Learning Resource Reservation System of Brgy. F De Jesus, Unisan Quezon, you accept and agree to be bound by these Terms of Service.</p>
        <div class="terms-highlight highlight-blue"><i class="fa-solid fa-circle-info"></i>These terms apply to all users including residents, SK officers, and administrators.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">02</div>
          <h3>System Use & Eligibility</h3>
        </div>
        <p>This system is intended exclusively for authorized members of Brgy. F De Jesus, Unisan Quezon. You agree to:</p>
        <ul>
          <li class="li-blue">Provide accurate and truthful information when registering or making a reservation.</li>
          <li class="li-blue">Use reserved resources only during your approved reservation period.</li>
          <li class="li-blue">Not share your login credentials with any other person.</li>
          <li class="li-blue">Notify administrators of any unauthorized access to your account.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">03</div>
          <h3>SK Officer Accounts</h3>
        </div>
        <p>SK Officer accounts require email verification followed by explicit approval from the Barangay Chairman. Registration alone does not grant access to the SK portal.</p>
        <div class="terms-highlight highlight-blue"><i class="fa-solid fa-shield-halved"></i>You will receive an email notification once your account has been reviewed.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">04</div>
          <h3>Reservation Policy</h3>
        </div>
        <p>All reservations are subject to approval by authorized SK personnel or administrators. By submitting a reservation, you acknowledge:</p>
        <ul>
          <li class="li-blue">Reservations are not confirmed until officially approved.</li>
          <li class="li-blue">You must present your e-ticket QR code upon arrival to claim your reservation.</li>
          <li class="li-blue">Failure to appear within 15 minutes of your reserved start time may result in cancellation.</li>
          <li class="li-blue">Misuse of reserved resources may result in account suspension.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">05</div>
          <h3>Responsible Use of Resources</h3>
        </div>
        <p>Users are responsible for the proper care of all reserved e-learning equipment and facilities.</p>
        <ul>
          <li class="li-blue">Treat all equipment with care and report any damage immediately.</li>
          <li class="li-blue">Do not install unauthorized software or modify system settings.</li>
          <li class="li-blue">Use resources solely for educational and approved purposes.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">06</div>
          <h3>Amendments</h3>
        </div>
        <p>These terms may be updated from time to time. Continued use of the system after changes are posted constitutes your acceptance of the revised terms.</p>
        <p style="margin-top:0.5rem;font-size:0.78rem;color:#94a3b8;font-weight:600;">Last updated: <?= date('F j, Y') ?></p>
      </div>
    </div>
    <div class="modal-footer">
      <div class="modal-footer-note">
        <i class="fa-solid fa-eye"></i>
        <span id="termsReadNote">Scroll through all sections to enable acceptance.</span>
      </div>
      <p class="must-read-hint" id="termsMustRead"><i class="fa-solid fa-arrow-down mr-1"></i> Please scroll to the bottom to accept.</p>
      <div class="modal-footer-actions">
        <button class="btn-modal-decline" onclick="closeModal('terms')">Decline</button>
        <button class="btn-modal-accept blue-btn" id="termsAcceptBtn" onclick="acceptTerms()" disabled>
          <i class="fa-solid fa-circle-check"></i> I Accept These Terms
        </button>
      </div>
    </div>
  </div>
</div>

<!-- PRIVACY POLICY MODAL -->
<div class="modal-backdrop" id="privacyModal" role="dialog" aria-modal="true" aria-labelledby="privacyTitleText">
  <div class="modal">
    <div class="modal-header modal-header-bg-purple">
      <div class="modal-header-left">
        <div class="modal-icon purple"><i class="fa-solid fa-shield-halved"></i></div>
        <div>
          <h2 id="privacyTitleText">Privacy Policy</h2>
          <p>E-Learning Resource Reservation System</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('privacy')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-progress">
      <div class="modal-progress-bar progress-purple" id="privacyProgress"></div>
    </div>
    <div class="modal-body" id="privacyBody">
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">01</div>
          <h3>Introduction</h3>
        </div>
        <p>The E-Learning Resource Reservation System operated by Brgy. F De Jesus, Unisan Quezon is committed to protecting your personal data in full compliance with the Data Privacy Act of 2012 (RA 10173).</p>
        <div class="terms-highlight highlight-purple"><i class="fa-solid fa-shield-halved"></i>Your data is never sold or shared with advertisers or commercial third parties.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">02</div>
          <h3>Information We Collect</h3>
        </div>
        <ul>
          <li class="li-purple">Full name and email address.</li>
          <li class="li-purple">Login credentials (stored encrypted — never in plain text).</li>
          <li class="li-purple">Reservation history including dates, times, and resources used.</li>
          <li class="li-purple">Activity logs such as login timestamps and system actions.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">03</div>
          <h3>How We Use Your Information</h3>
        </div>
        <ul>
          <li class="li-purple">To process and manage your resource reservations.</li>
          <li class="li-purple">To verify your identity and authenticate your access.</li>
          <li class="li-purple">To send reservation confirmations and e-tickets via email.</li>
          <li class="li-purple">To generate reports for barangay administration and accountability.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">04</div>
          <h3>Data Security</h3>
        </div>
        <p>We implement appropriate technical measures to protect your personal data:</p>
        <ul>
          <li class="li-purple">Password hashing using industry-standard encryption algorithms.</li>
          <li class="li-purple">Secure HTTPS connections for all data transmission.</li>
          <li class="li-purple">Role-based access controls limiting data access to authorized personnel only.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">05</div>
          <h3>Your Rights</h3>
        </div>
        <p>Under the Data Privacy Act of 2012, you have the right to access, correct, and request deletion of your personal data. Contact the barangay administration to exercise these rights.</p>
        <p style="margin-top:0.5rem;font-size:0.78rem;color:#94a3b8;font-weight:600;">Last updated: <?= date('F j, Y') ?></p>
      </div>
    </div>
    <div class="modal-footer">
      <div class="modal-footer-note">
        <i class="fa-solid fa-eye"></i>
        <span id="privacyReadNote">Scroll through all sections to enable acceptance.</span>
      </div>
      <p class="must-read-hint" id="privacyMustRead"><i class="fa-solid fa-arrow-down mr-1"></i> Please scroll to the bottom to accept.</p>
      <div class="modal-footer-actions">
        <button class="btn-modal-decline" onclick="closeModal('privacy')">Close</button>
        <button class="btn-modal-accept purple-btn" id="privacyAcceptBtn" onclick="acceptModal('privacy')" disabled>
          <i class="fa-solid fa-shield-halved"></i> I Acknowledge This Policy
        </button>
      </div>
    </div>
  </div>
</div>

<script>
<?php
$flashError   = session()->getFlashdata('error');
$flashSuccess = session()->getFlashdata('success');
$flashInfo    = session()->getFlashdata('info');
?>
  function onRoleChange(v) {
    document.getElementById('skNotice').classList.toggle('show', v === 'SK');
  }
  (function() {
    const r = document.getElementById('role');
    if (r.value) onRoleChange(r.value);
  })();

  function togglePw(inputId, iconId) {
    const el = document.getElementById(inputId);
    const ic = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.className = el.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
  }

  const RULES = {
    'r-len': p => p.length >= 8,
    'r-up': p => /[A-Z]/.test(p),
    'r-lo': p => /[a-z]/.test(p),
    'r-num': p => /[0-9]/.test(p),
    'r-sp': p => /[^A-Za-z0-9]/.test(p),
  };
  const LABELS = {
    'r-len': 'At least 8 characters',
    'r-up': 'One uppercase letter',
    'r-lo': 'One lowercase letter',
    'r-num': 'One number',
    'r-sp': 'One special character',
  };

  const pwEl = document.getElementById('password');
  const cpwEl = document.getElementById('confirm_password');

  pwEl.addEventListener('focus', () => document.getElementById('pwReqs').style.display = 'block');
  pwEl.addEventListener('input', () => {
    updateReqs(pwEl.value);
    checkMatch();
  });
  cpwEl.addEventListener('input', checkMatch);

  function updateReqs(pwd) {
    for (const [id, fn] of Object.entries(RULES)) {
      const el = document.getElementById(id);
      const ok = fn(pwd);
      el.className = 'req' + (ok ? ' met' : '');
      el.innerHTML = (ok ?
        '<i class="fa-solid fa-circle-check"></i>' :
        '<i class="fa-regular fa-circle"></i>') + ' ' + LABELS[id];
    }
  }

  function checkMatch() {
    const m = document.getElementById('pwMatch');
    if (!cpwEl.value) {
      m.className = 'pw-match';
      return;
    }
    if (cpwEl.value !== pwEl.value) {
      m.className = 'pw-match bad';
      m.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Passwords do not match';
    } else {
      m.className = 'pw-match good';
      m.innerHTML = '<i class="fa-solid fa-circle-check"></i> Passwords match';
    }
  }

  document.getElementById('regForm').addEventListener('submit', function(e) {
    const name = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const role = document.getElementById('role').value;
    const pwd = pwEl.value;
    const cpwd = cpwEl.value;
    const terms = document.getElementById('termsChk').checked;

    if (!name || !email || !role || !pwd || !cpwd) {
      e.preventDefault();
      showResultModal('error', 'Missing Information', 'Please fill in all required fields before creating your account.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (pwd !== cpwd) {
      e.preventDefault();
      showResultModal('error', "Passwords Don't Match", 'The passwords you entered do not match. Please re-enter them carefully.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (pwd.length < 8) {
      e.preventDefault();
      showResultModal('error', 'Password Too Short', 'Your password must be at least 8 characters long.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (!terms) {
      e.preventDefault();
      showResultModal('error', 'Terms Not Accepted', 'You must agree to the Terms of Service and Privacy Policy to create an account.',
        [{
            label: 'Read Terms',
            icon: 'fa-file-contract',
            color: 'blue',
            action: () => {
              closeResultModal();
              openModal('terms');
            }
          },
          {
            label: 'Go Back',
            icon: 'fa-arrow-left',
            color: 'red',
            action: 'close'
          },
        ]);
      return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Creating account…';
  });

  let countdownTimer = null;

  function showResultModal(type, title, message, actions, countdown) {
    const backdrop = document.getElementById('resultModal');
    const iconWrap = document.getElementById('resultIcon');
    const iconEl = document.getElementById('resultIconI');
    const titleEl = document.getElementById('resultTitle');
    const msgEl = document.getElementById('resultMsg');
    const actionsEl = document.getElementById('resultActions');
    const cdWrap = document.getElementById('resultCountdown');
    const cdNum = document.getElementById('countdownNum');

    iconWrap.className = 'result-icon-wrap ' + type;
    const icons = {
      success: 'fa-circle-check',
      error: 'fa-circle-xmark',
      warning: 'fa-triangle-exclamation'
    };
    iconEl.className = 'fa-solid ' + (icons[type] || 'fa-circle-info');
    titleEl.textContent = title;
    msgEl.textContent = message;

    actionsEl.innerHTML = '';
    (actions || []).forEach(a => {
      const btn = document.createElement('button');
      btn.className = 'btn-result-primary ' + (a.color || 'blue');
      btn.innerHTML = `<i class="fa-solid ${a.icon || 'fa-check'}"></i> ${a.label}`;
      btn.onclick = () => {
        if (a.action === 'close') closeResultModal();
        else if (a.action === 'login') window.location.href = '/login';
        else if (typeof a.action === 'function') a.action();
      };
      actionsEl.appendChild(btn);
    });

    if (countdown) {
      cdWrap.style.display = 'flex';
      cdNum.textContent = countdown;
      cdNum.style.color = type === 'success' ? '#22c55e' : '#f59e0b';
      let left = countdown;
      countdownTimer = setInterval(() => {
        left--;
        cdNum.textContent = left;
        if (left <= 0) {
          clearInterval(countdownTimer);
          window.location.href = '/login';
        }
      }, 1000);
    } else {
      cdWrap.style.display = 'none';
      clearInterval(countdownTimer);
    }

    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeResultModal() {
    document.getElementById('resultModal').classList.remove('open');
    document.body.style.overflow = '';
    clearInterval(countdownTimer);
  }

  document.getElementById('resultModal').addEventListener('click', function(e) {
    if (e.target === this) closeResultModal();
  });

  window.addEventListener('DOMContentLoaded', () => {
    if (FLASH_SUCCESS) {
      const isSK = FLASH_SUCCESS.toLowerCase().includes('pending') || FLASH_SUCCESS.toLowerCase().includes('chairman');
      if (isSK) {
        showResultModal('warning', 'Account Created — Pending Approval', FLASH_SUCCESS,
          [{
            label: 'Go to Login',
            icon: 'fa-right-to-bracket',
            color: 'amber',
            action: 'login'
          }], 8);
      } else {
        showResultModal('success', 'Account Created!', FLASH_SUCCESS,
          [{
            label: 'Go to Login',
            icon: 'fa-right-to-bracket',
            color: 'green',
            action: 'login'
          }], 5);
      }
    } else if (FLASH_INFO) {
      showResultModal('warning', 'One More Step', FLASH_INFO,
        [{
          label: 'Go to Login',
          icon: 'fa-right-to-bracket',
          color: 'amber',
          action: 'login'
        }], 8);
    } else if (FLASH_ERROR) {
      showResultModal('error', 'Registration Failed', FLASH_ERROR,
        [{
          label: 'Try Again',
          icon: 'fa-rotate-left',
          color: 'red',
          action: 'close'
        }]);
    }
  });

  const modalState = {
    terms: false,
    privacy: false
  };

  function openModal(type) {
    const modal = document.getElementById(type + 'Modal');
    const body = document.getElementById(type + 'Body');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    body.scrollTop = 0;
    updateProgress(type);
  }

  function closeModal(type) {
    document.getElementById(type + 'Modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  function acceptTerms() {
    if (!modalState['terms']) {
      document.getElementById('termsMustRead').classList.add('visible');
      return;
    }
    closeModal('terms');
    document.getElementById('termsChk').checked = true;
    showToast('Terms of Service accepted.', 'success');
  }

  function acceptModal(type) {
    if (!modalState[type]) {
      document.getElementById(type + 'MustRead').classList.add('visible');
      return;
    }
    closeModal(type);
    showToast(type === 'terms' ? 'Terms of Service accepted.' : 'Privacy Policy acknowledged.', 'success');
  }

  function updateProgress(type) {
    const body = document.getElementById(type + 'Body');
    const pct = body.scrollHeight <= body.clientHeight ?
      100 :
      Math.min(100, Math.round((body.scrollTop / (body.scrollHeight - body.clientHeight)) * 100));
    document.getElementById(type + 'Progress').style.width = pct + '%';
    if (pct >= 95 && !modalState[type]) {
      modalState[type] = true;
      const btn = document.getElementById(type + 'AcceptBtn');
      const note = document.getElementById(type + 'ReadNote');
      btn.disabled = false;
      note.textContent = 'You have reviewed the policy. You may now accept.';
      note.style.color = type === 'terms' ? '#2563eb' : '#7c3aed';
      document.getElementById(type + 'MustRead').classList.remove('visible');
    }
  }

  ['terms', 'privacy'].forEach(type => {
    document.getElementById(type + 'Body').addEventListener('scroll', () => updateProgress(type));
    document.getElementById(type + 'Modal').addEventListener('click', function(e) {
      if (e.target === this) closeModal(type);
    });
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      closeResultModal();
      ['terms', 'privacy'].forEach(closeModal);
    }
  });

  function showToast(message, type) {
    const existing = document.getElementById('appToast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.id = 'appToast';
    toast.style.cssText = `
        position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);
        background:${type==='success'?'#f0fdf4':'#fef2f2'};
        border:1px solid ${type==='success'?'#bbf7d0':'#fecaca'};
        color:${type==='success'?'#166534':'#991b1b'};
        padding:0.75rem 1.25rem;border-radius:14px;
        font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,0.1);z-index:3000;white-space:nowrap;
        animation:slideIn 0.3s ease;
      `;
    toast.innerHTML = `<i class="fa-solid ${type==='success'?'fa-circle-check':'fa-circle-exclamation'} mr-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.style.transition = 'opacity 0.4s';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }
</script>
</body>

</html>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AUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFABRRRQAUUUUAFFFFAH/2Q==" alt="SK Brgy. F De Jesus" style="width:100%;height:100%;object-fit:contain;"></div>
<h1>Create Account</h1>
<p>Brgy. F De Jesus, Unisan Quezon</p>
</div>

<div class="welcome-text">
  <h3>E-Learning Resource Reservation System</h3>
  <p>Register to start booking resources for your sessions</p>
</div>

<?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        $flashError   = session()->getFlashdata('error');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        $flashSuccess = session()->getFlashdata('success');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        $flashInfo    = session()->getFlashdata('info');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?>

<!-- SK notice -->
<div class="sk-notice" id="skNotice">
  <i class="fa-solid fa-shield-halved"></i>
  <p><strong>SK Officer Registration:</strong> After verifying your email, your account requires <strong>approval by the Barangay Chairman</strong> before you can log in. You will be notified via email once a decision has been made.</p>
</div>

<form action="/register-action" method="post" id="regForm" novalidate>
  <?= csrf_field() ?>

  <!-- Full Name -->
  <div class="input-wrapper">
    <label for="full_name">Full Name</label>
    <div class="input-container">
      <i class="fa-regular fa-user input-icon"></i>
      <input type="text" id="full_name" name="full_name"
        placeholder="Juan Dela Cruz"
        value="<?= esc(old('full_name')) ?>"
        autocomplete="name" required>
    </div>
  </div>

  <!-- Email -->
  <div class="input-wrapper">
    <label for="email">Email Address</label>
    <div class="input-container">
      <i class="fa-regular fa-envelope input-icon"></i>
      <input type="email" id="email" name="email"
        placeholder="juan@example.com"
        value="<?= esc(old('email')) ?>"
        autocomplete="email" required>
    </div>
  </div>

  <!-- Role -->
  <div class="input-wrapper">
    <label for="role">I am a…</label>
    <div class="input-container select-wrapper">
      <i class="fa-solid fa-id-badge input-icon"></i>
      <select name="role" id="role" required onchange="onRoleChange(this.value)">
        <option value="" disabled selected>Select your role</option>
        <option value="resident" <?= old('role') === 'resident' ? 'selected' : '' ?>>Resident</option>
        <option value="SK" <?= old('role') === 'SK'       ? 'selected' : '' ?>>SK Officer</option>
      </select>
    </div>
  </div>

  <!-- Password -->
  <div class="input-wrapper">
    <label for="password">Password</label>
    <div class="input-container">
      <i class="fa-solid fa-lock input-icon"></i>
      <div class="password-wrapper">
        <input type="password" id="password" name="password"
          placeholder="Create a strong password"
          autocomplete="new-password" required>
        <button type="button" class="password-toggle" onclick="togglePw('password','iconPw')" aria-label="Toggle">
          <i class="fa-regular fa-eye" id="iconPw"></i>
        </button>
      </div>
    </div>
    <div class="pw-reqs" id="pwReqs" style="display:none">
      <div class="pw-reqs-title">Password must contain:</div>
      <div class="req" id="r-len"><i class="fa-regular fa-circle"></i> At least 8 characters</div>
      <div class="req" id="r-up"> <i class="fa-regular fa-circle"></i> One uppercase letter</div>
      <div class="req" id="r-lo"> <i class="fa-regular fa-circle"></i> One lowercase letter</div>
      <div class="req" id="r-num"><i class="fa-regular fa-circle"></i> One number</div>
      <div class="req" id="r-sp"> <i class="fa-regular fa-circle"></i> One special character</div>
    </div>
  </div>

  <!-- Confirm Password -->
  <div class="input-wrapper">
    <label for="confirm_password">Confirm Password</label>
    <div class="input-container">
      <i class="fa-solid fa-lock input-icon"></i>
      <div class="password-wrapper">
        <input type="password" id="confirm_password" name="confirm_password"
          placeholder="Re-enter your password"
          autocomplete="new-password" required>
        <button type="button" class="password-toggle" onclick="togglePw('confirm_password','iconCpw')" aria-label="Toggle">
          <i class="fa-regular fa-eye" id="iconCpw"></i>
        </button>
      </div>
    </div>
    <div class="pw-match" id="pwMatch"></div>
  </div>

  <!-- Terms checkbox -->
  <label class="checkbox-wrapper">
    <input type="checkbox" name="terms" id="termsChk" class="checkbox-custom" required>
    <span class="checkbox-label">
      I agree to the
      <a href="#" class="text-link" onclick="openModal('terms');return false;">Terms of Service</a>
      and
      <a href="#" class="text-link" onclick="openModal('privacy');return false;">Privacy Policy</a>
    </span>
  </label>

  <button type="submit" class="btn-primary" id="submitBtn">
    <i class="fa-solid fa-user-plus mr-2"></i> Create Account
  </button>

  <div class="divider"><span>or</span></div>

  <div class="text-center text-sm">
    <span class="text-slate-400 font-medium">Already have an account?</span>
    <a href="/login" class="text-link ml-1">Sign In</a>
  </div>

</form>

<div class="footer-links">
  <p>By creating an account, you agree to our
    <button onclick="openModal('terms')">Terms of Service</button> and
    <button onclick="openModal('privacy')">Privacy Policy</button>
  </p>
</div>

</div>
</div>

<!-- RESULT MODAL -->
<div class="result-modal-backdrop" id="resultModal" role="dialog" aria-modal="true">
  <div class="result-modal">
    <div class="result-icon-wrap" id="resultIcon">
      <i id="resultIconI"></i>
    </div>
    <h2 id="resultTitle"></h2>
    <p id="resultMsg"></p>
    <div class="result-modal-actions" id="resultActions"></div>
    <div class="countdown-wrap" id="resultCountdown" style="display:none">
      <i class="fa-solid fa-clock"></i>
      <span>Redirecting in <span class="countdown-num" id="countdownNum">5</span>s…</span>
    </div>
  </div>
</div>

<!-- TERMS OF SERVICE MODAL -->
<div class="modal-backdrop" id="termsModal" role="dialog" aria-modal="true" aria-labelledby="termsTitleText">
  <div class="modal">
    <div class="modal-header modal-header-bg-blue">
      <div class="modal-header-left">
        <div class="modal-icon blue"><i class="fa-solid fa-file-contract"></i></div>
        <div>
          <h2 id="termsTitleText">Terms of Service</h2>
          <p>E-Learning Resource Reservation System</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('terms')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-progress">
      <div class="modal-progress-bar progress-blue" id="termsProgress"></div>
    </div>
    <div class="modal-body" id="termsBody">
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">01</div>
          <h3>Acceptance of Terms</h3>
        </div>
        <p>By accessing and using the E-Learning Resource Reservation System of Brgy. F De Jesus, Unisan Quezon, you accept and agree to be bound by these Terms of Service.</p>
        <div class="terms-highlight highlight-blue"><i class="fa-solid fa-circle-info"></i>These terms apply to all users including residents, SK officers, and administrators.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">02</div>
          <h3>System Use & Eligibility</h3>
        </div>
        <p>This system is intended exclusively for authorized members of Brgy. F De Jesus, Unisan Quezon. You agree to:</p>
        <ul>
          <li class="li-blue">Provide accurate and truthful information when registering or making a reservation.</li>
          <li class="li-blue">Use reserved resources only during your approved reservation period.</li>
          <li class="li-blue">Not share your login credentials with any other person.</li>
          <li class="li-blue">Notify administrators of any unauthorized access to your account.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">03</div>
          <h3>SK Officer Accounts</h3>
        </div>
        <p>SK Officer accounts require email verification followed by explicit approval from the Barangay Chairman. Registration alone does not grant access to the SK portal.</p>
        <div class="terms-highlight highlight-blue"><i class="fa-solid fa-shield-halved"></i>You will receive an email notification once your account has been reviewed.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">04</div>
          <h3>Reservation Policy</h3>
        </div>
        <p>All reservations are subject to approval by authorized SK personnel or administrators. By submitting a reservation, you acknowledge:</p>
        <ul>
          <li class="li-blue">Reservations are not confirmed until officially approved.</li>
          <li class="li-blue">You must present your e-ticket QR code upon arrival to claim your reservation.</li>
          <li class="li-blue">Failure to appear within 15 minutes of your reserved start time may result in cancellation.</li>
          <li class="li-blue">Misuse of reserved resources may result in account suspension.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">05</div>
          <h3>Responsible Use of Resources</h3>
        </div>
        <p>Users are responsible for the proper care of all reserved e-learning equipment and facilities.</p>
        <ul>
          <li class="li-blue">Treat all equipment with care and report any damage immediately.</li>
          <li class="li-blue">Do not install unauthorized software or modify system settings.</li>
          <li class="li-blue">Use resources solely for educational and approved purposes.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-blue">06</div>
          <h3>Amendments</h3>
        </div>
        <p>These terms may be updated from time to time. Continued use of the system after changes are posted constitutes your acceptance of the revised terms.</p>
        <p style="margin-top:0.5rem;font-size:0.78rem;color:#94a3b8;font-weight:600;">Last updated: <?= date('F j, Y') ?></p>
      </div>
    </div>
    <div class="modal-footer">
      <div class="modal-footer-note">
        <i class="fa-solid fa-eye"></i>
        <span id="termsReadNote">Scroll through all sections to enable acceptance.</span>
      </div>
      <p class="must-read-hint" id="termsMustRead"><i class="fa-solid fa-arrow-down mr-1"></i> Please scroll to the bottom to accept.</p>
      <div class="modal-footer-actions">
        <button class="btn-modal-decline" onclick="closeModal('terms')">Decline</button>
        <button class="btn-modal-accept blue-btn" id="termsAcceptBtn" onclick="acceptTerms()" disabled>
          <i class="fa-solid fa-circle-check"></i> I Accept These Terms
        </button>
      </div>
    </div>
  </div>
</div>

<!-- PRIVACY POLICY MODAL -->
<div class="modal-backdrop" id="privacyModal" role="dialog" aria-modal="true" aria-labelledby="privacyTitleText">
  <div class="modal">
    <div class="modal-header modal-header-bg-purple">
      <div class="modal-header-left">
        <div class="modal-icon purple"><i class="fa-solid fa-shield-halved"></i></div>
        <div>
          <h2 id="privacyTitleText">Privacy Policy</h2>
          <p>E-Learning Resource Reservation System</p>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('privacy')" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-progress">
      <div class="modal-progress-bar progress-purple" id="privacyProgress"></div>
    </div>
    <div class="modal-body" id="privacyBody">
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">01</div>
          <h3>Introduction</h3>
        </div>
        <p>The E-Learning Resource Reservation System operated by Brgy. F De Jesus, Unisan Quezon is committed to protecting your personal data in full compliance with the Data Privacy Act of 2012 (RA 10173).</p>
        <div class="terms-highlight highlight-purple"><i class="fa-solid fa-shield-halved"></i>Your data is never sold or shared with advertisers or commercial third parties.</div>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">02</div>
          <h3>Information We Collect</h3>
        </div>
        <ul>
          <li class="li-purple">Full name and email address.</li>
          <li class="li-purple">Login credentials (stored encrypted — never in plain text).</li>
          <li class="li-purple">Reservation history including dates, times, and resources used.</li>
          <li class="li-purple">Activity logs such as login timestamps and system actions.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">03</div>
          <h3>How We Use Your Information</h3>
        </div>
        <ul>
          <li class="li-purple">To process and manage your resource reservations.</li>
          <li class="li-purple">To verify your identity and authenticate your access.</li>
          <li class="li-purple">To send reservation confirmations and e-tickets via email.</li>
          <li class="li-purple">To generate reports for barangay administration and accountability.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">04</div>
          <h3>Data Security</h3>
        </div>
        <p>We implement appropriate technical measures to protect your personal data:</p>
        <ul>
          <li class="li-purple">Password hashing using industry-standard encryption algorithms.</li>
          <li class="li-purple">Secure HTTPS connections for all data transmission.</li>
          <li class="li-purple">Role-based access controls limiting data access to authorized personnel only.</li>
        </ul>
      </div>
      <div class="terms-section">
        <div class="terms-section-header">
          <div class="terms-section-num num-purple">05</div>
          <h3>Your Rights</h3>
        </div>
        <p>Under the Data Privacy Act of 2012, you have the right to access, correct, and request deletion of your personal data. Contact the barangay administration to exercise these rights.</p>
        <p style="margin-top:0.5rem;font-size:0.78rem;color:#94a3b8;font-weight:600;">Last updated: <?= date('F j, Y') ?></p>
      </div>
    </div>
    <div class="modal-footer">
      <div class="modal-footer-note">
        <i class="fa-solid fa-eye"></i>
        <span id="privacyReadNote">Scroll through all sections to enable acceptance.</span>
      </div>
      <p class="must-read-hint" id="privacyMustRead"><i class="fa-solid fa-arrow-down mr-1"></i> Please scroll to the bottom to accept.</p>
      <div class="modal-footer-actions">
        <button class="btn-modal-decline" onclick="closeModal('privacy')">Close</button>
        <button class="btn-modal-accept purple-btn" id="privacyAcceptBtn" onclick="acceptModal('privacy')" disabled>
          <i class="fa-solid fa-shield-halved"></i> I Acknowledge This Policy
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  const FLASH_ERROR = <?= json_encode($flashError) ?>;
  const FLASH_SUCCESS = <?= json_encode($flashSuccess) ?>;
  const FLASH_INFO = <?= json_encode($flashInfo) ?>;

  function onRoleChange(v) {
    document.getElementById('skNotice').classList.toggle('show', v === 'SK');
  }
  (function() {
    const r = document.getElementById('role');
    if (r.value) onRoleChange(r.value);
  })();

  function togglePw(inputId, iconId) {
    const el = document.getElementById(inputId);
    const ic = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.className = el.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
  }

  const RULES = {
    'r-len': p => p.length >= 8,
    'r-up': p => /[A-Z]/.test(p),
    'r-lo': p => /[a-z]/.test(p),
    'r-num': p => /[0-9]/.test(p),
    'r-sp': p => /[^A-Za-z0-9]/.test(p),
  };
  const LABELS = {
    'r-len': 'At least 8 characters',
    'r-up': 'One uppercase letter',
    'r-lo': 'One lowercase letter',
    'r-num': 'One number',
    'r-sp': 'One special character',
  };

  const pwEl = document.getElementById('password');
  const cpwEl = document.getElementById('confirm_password');

  pwEl.addEventListener('focus', () => document.getElementById('pwReqs').style.display = 'block');
  pwEl.addEventListener('input', () => {
    updateReqs(pwEl.value);
    checkMatch();
  });
  cpwEl.addEventListener('input', checkMatch);

  function updateReqs(pwd) {
    for (const [id, fn] of Object.entries(RULES)) {
      const el = document.getElementById(id);
      const ok = fn(pwd);
      el.className = 'req' + (ok ? ' met' : '');
      el.innerHTML = (ok ?
        '<i class="fa-solid fa-circle-check"></i>' :
        '<i class="fa-regular fa-circle"></i>') + ' ' + LABELS[id];
    }
  }

  function checkMatch() {
    const m = document.getElementById('pwMatch');
    if (!cpwEl.value) {
      m.className = 'pw-match';
      return;
    }
    if (cpwEl.value !== pwEl.value) {
      m.className = 'pw-match bad';
      m.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Passwords do not match';
    } else {
      m.className = 'pw-match good';
      m.innerHTML = '<i class="fa-solid fa-circle-check"></i> Passwords match';
    }
  }

  document.getElementById('regForm').addEventListener('submit', function(e) {
    const name = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const role = document.getElementById('role').value;
    const pwd = pwEl.value;
    const cpwd = cpwEl.value;
    const terms = document.getElementById('termsChk').checked;

    if (!name || !email || !role || !pwd || !cpwd) {
      e.preventDefault();
      showResultModal('error', 'Missing Information', 'Please fill in all required fields before creating your account.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (pwd !== cpwd) {
      e.preventDefault();
      showResultModal('error', "Passwords Don't Match", 'The passwords you entered do not match. Please re-enter them carefully.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (pwd.length < 8) {
      e.preventDefault();
      showResultModal('error', 'Password Too Short', 'Your password must be at least 8 characters long.',
        [{
          label: 'Go Back & Fix',
          icon: 'fa-arrow-left',
          color: 'red',
          action: 'close'
        }]);
      return;
    }
    if (!terms) {
      e.preventDefault();
      showResultModal('error', 'Terms Not Accepted', 'You must agree to the Terms of Service and Privacy Policy to create an account.',
        [{
            label: 'Read Terms',
            icon: 'fa-file-contract',
            color: 'blue',
            action: () => {
              closeResultModal();
              openModal('terms');
            }
          },
          {
            label: 'Go Back',
            icon: 'fa-arrow-left',
            color: 'red',
            action: 'close'
          },
        ]);
      return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Creating account…';
  });

  let countdownTimer = null;

  function showResultModal(type, title, message, actions, countdown) {
    const backdrop = document.getElementById('resultModal');
    const iconWrap = document.getElementById('resultIcon');
    const iconEl = document.getElementById('resultIconI');
    const titleEl = document.getElementById('resultTitle');
    const msgEl = document.getElementById('resultMsg');
    const actionsEl = document.getElementById('resultActions');
    const cdWrap = document.getElementById('resultCountdown');
    const cdNum = document.getElementById('countdownNum');

    iconWrap.className = 'result-icon-wrap ' + type;
    const icons = {
      success: 'fa-circle-check',
      error: 'fa-circle-xmark',
      warning: 'fa-triangle-exclamation'
    };
    iconEl.className = 'fa-solid ' + (icons[type] || 'fa-circle-info');
    titleEl.textContent = title;
    msgEl.textContent = message;

    actionsEl.innerHTML = '';
    (actions || []).forEach(a => {
      const btn = document.createElement('button');
      btn.className = 'btn-result-primary ' + (a.color || 'blue');
      btn.innerHTML = `<i class="fa-solid ${a.icon || 'fa-check'}"></i> ${a.label}`;
      btn.onclick = () => {
        if (a.action === 'close') closeResultModal();
        else if (a.action === 'login') window.location.href = '/login';
        else if (typeof a.action === 'function') a.action();
      };
      actionsEl.appendChild(btn);
    });

    if (countdown) {
      cdWrap.style.display = 'flex';
      cdNum.textContent = countdown;
      cdNum.style.color = type === 'success' ? '#22c55e' : '#f59e0b';
      let left = countdown;
      countdownTimer = setInterval(() => {
        left--;
        cdNum.textContent = left;
        if (left <= 0) {
          clearInterval(countdownTimer);
          window.location.href = '/login';
        }
      }, 1000);
    } else {
      cdWrap.style.display = 'none';
      clearInterval(countdownTimer);
    }

    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeResultModal() {
    document.getElementById('resultModal').classList.remove('open');
    document.body.style.overflow = '';
    clearInterval(countdownTimer);
  }

  document.getElementById('resultModal').addEventListener('click', function(e) {
    if (e.target === this) closeResultModal();
  });

  window.addEventListener('DOMContentLoaded', () => {
    if (FLASH_SUCCESS) {
      const isSK = FLASH_SUCCESS.toLowerCase().includes('pending') || FLASH_SUCCESS.toLowerCase().includes('chairman');
      if (isSK) {
        showResultModal('warning', 'Account Created — Pending Approval', FLASH_SUCCESS,
          [{
            label: 'Go to Login',
            icon: 'fa-right-to-bracket',
            color: 'amber',
            action: 'login'
          }], 8);
      } else {
        showResultModal('success', 'Account Created!', FLASH_SUCCESS,
          [{
            label: 'Go to Login',
            icon: 'fa-right-to-bracket',
            color: 'green',
            action: 'login'
          }], 5);
      }
    } else if (FLASH_INFO) {
      showResultModal('warning', 'One More Step', FLASH_INFO,
        [{
          label: 'Go to Login',
          icon: 'fa-right-to-bracket',
          color: 'amber',
          action: 'login'
        }], 8);
    } else if (FLASH_ERROR) {
      showResultModal('error', 'Registration Failed', FLASH_ERROR,
        [{
          label: 'Try Again',
          icon: 'fa-rotate-left',
          color: 'red',
          action: 'close'
        }]);
    }
  });

  const modalState = {
    terms: false,
    privacy: false
  };

  function openModal(type) {
    const modal = document.getElementById(type + 'Modal');
    const body = document.getElementById(type + 'Body');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    body.scrollTop = 0;
    updateProgress(type);
  }

  function closeModal(type) {
    document.getElementById(type + 'Modal').classList.remove('open');
    document.body.style.overflow = '';
  }

  function acceptTerms() {
    if (!modalState['terms']) {
      document.getElementById('termsMustRead').classList.add('visible');
      return;
    }
    closeModal('terms');
    document.getElementById('termsChk').checked = true;
    showToast('Terms of Service accepted.', 'success');
  }

  function acceptModal(type) {
    if (!modalState[type]) {
      document.getElementById(type + 'MustRead').classList.add('visible');
      return;
    }
    closeModal(type);
    showToast(type === 'terms' ? 'Terms of Service accepted.' : 'Privacy Policy acknowledged.', 'success');
  }

  function updateProgress(type) {
    const body = document.getElementById(type + 'Body');
    const pct = body.scrollHeight <= body.clientHeight ?
      100 :
      Math.min(100, Math.round((body.scrollTop / (body.scrollHeight - body.clientHeight)) * 100));
    document.getElementById(type + 'Progress').style.width = pct + '%';
    if (pct >= 95 && !modalState[type]) {
      modalState[type] = true;
      const btn = document.getElementById(type + 'AcceptBtn');
      const note = document.getElementById(type + 'ReadNote');
      btn.disabled = false;
      note.textContent = 'You have reviewed the policy. You may now accept.';
      note.style.color = type === 'terms' ? '#2563eb' : '#7c3aed';
      document.getElementById(type + 'MustRead').classList.remove('visible');
    }
  }

  ['terms', 'privacy'].forEach(type => {
    document.getElementById(type + 'Body').addEventListener('scroll', () => updateProgress(type));
    document.getElementById(type + 'Modal').addEventListener('click', function(e) {
      if (e.target === this) closeModal(type);
    });
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      closeResultModal();
      ['terms', 'privacy'].forEach(closeModal);
    }
  });

  function showToast(message, type) {
    const existing = document.getElementById('appToast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.id = 'appToast';
    toast.style.cssText = `
        position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);
        background:${type==='success'?'#f0fdf4':'#fef2f2'};
        border:1px solid ${type==='success'?'#bbf7d0':'#fecaca'};
        color:${type==='success'?'#166534':'#991b1b'};
        padding:0.75rem 1.25rem;border-radius:14px;
        font-family:'Plus Jakarta Sans',sans-serif;font-size:0.875rem;font-weight:600;
        box-shadow:0 8px 24px rgba(0,0,0,0.1);z-index:3000;white-space:nowrap;
        animation:slideIn 0.3s ease;
      `;
    toast.innerHTML = `<i class="fa-solid ${type==='success'?'fa-circle-check':'fa-circle-exclamation'} mr-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.style.transition = 'opacity 0.4s';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }
</script>
</body>
</html>