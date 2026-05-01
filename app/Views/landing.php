<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>EduReserve · SK E-Learning Resource Center</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f6f4ff;--white:#fff;--p:#5b21b6;--pm:#7c3aed;--pl:#8b5cf6;
  --pale:#ede9fe;--xlight:#f5f3ff;--gold:#f59e0b;--gold-l:#fef3c7;
  --dark:#1e1b4b;--muted:#6b7280;--light:#9ca3af;--border:#ddd6fe;
  --font:-apple-system,BlinkMacSystemFont,'Segoe UI','Helvetica Neue',Arial,sans-serif;
}
html,body{font-family:var(--font);background:var(--bg);color:var(--dark);overflow-x:hidden;-webkit-font-smoothing:antialiased}

nav{background:var(--white);border-bottom:1px solid var(--border);padding:0 60px;height:68px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 14px rgba(91,33,182,.06)}
.nb{display:flex;align-items:center;gap:10px;text-decoration:none}
.nl{width:38px;height:38px;border-radius:10px;background:var(--p);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(91,33,182,.3)}
.nl i{color:#fff;font-size:.9rem}
.bn{font-size:1.2rem;font-weight:800;color:var(--dark);letter-spacing:-.04em}
.bn em{font-style:normal;color:var(--pl)}.bn b{font-weight:inherit;color:var(--gold)}
.nav-links{display:flex;align-items:center;gap:32px}
.nav-links a{font-size:.875rem;font-weight:500;color:var(--muted);text-decoration:none;transition:color .18s;position:relative;padding-bottom:2px}
.nav-links a:hover{color:var(--p)}
.nav-links a.active{color:var(--dark);font-weight:700}
.nav-links a.active::after{content:'';position:absolute;bottom:-4px;left:0;right:0;height:2px;background:var(--p);border-radius:2px}
.nav-r{display:flex;align-items:center;gap:10px}
.bo{padding:8px 20px;border:1.5px solid var(--border);border-radius:9px;background:transparent;color:var(--p);font-size:.84rem;font-weight:600;cursor:pointer;text-decoration:none;transition:all .18s;font-family:var(--font)}
.bo:hover{border-color:var(--p);background:var(--pale)}
.bs{padding:9px 22px;border:none;border-radius:9px;background:var(--p);color:#fff;font-size:.84rem;font-weight:700;cursor:pointer;text-decoration:none;transition:all .18s;box-shadow:0 4px 14px rgba(91,33,182,.3);font-family:var(--font)}
.bs:hover{background:var(--pm);transform:translateY(-1px)}

.hero-wrap{background:var(--white);border-bottom:1px solid var(--border)}
.hero{max-width:1160px;margin:0 auto;padding:72px 60px 80px;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center}
.hbadge{display:inline-flex;align-items:center;gap:7px;background:var(--gold-l);border:1px solid rgba(245,158,11,.28);color:#92400e;font-size:.67rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:5px 13px;border-radius:999px;margin-bottom:20px}
.hbadge .dot{width:6px;height:6px;border-radius:50%;background:var(--gold);flex-shrink:0;animation:dp 2s infinite}
@keyframes dp{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.7)}}
.htitle{font-size:clamp(2.4rem,4vw,3.5rem);font-weight:800;line-height:1.07;letter-spacing:-.04em;color:var(--dark);margin-bottom:18px}
.htitle .a{color:var(--pl)}.htitle .b{display:block;color:var(--p)}
.hdesc{font-size:.95rem;color:var(--muted);line-height:1.72;max-width:420px;margin-bottom:10px}
.horg{font-size:.77rem;font-weight:600;color:var(--pl);margin-bottom:32px}
.horg strong{color:var(--gold)}
.hcta{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
.cp{display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:var(--p);color:#fff;border-radius:12px;font-size:.9rem;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all .2s;box-shadow:0 6px 22px rgba(91,33,182,.35);font-family:var(--font)}
.cp:hover{background:var(--pm);transform:translateY(-2px)}
.cs{display:inline-flex;align-items:center;gap:8px;padding:13px 24px;background:transparent;color:var(--p);border-radius:12px;font-size:.9rem;font-weight:600;text-decoration:none;border:1.5px solid var(--border);cursor:pointer;transition:all .2s;font-family:var(--font)}
.cs:hover{background:var(--pale);border-color:var(--p);transform:translateY(-2px)}

.hv{position:relative;display:flex;align-items:center;justify-content:center}
.char-frame{border-radius:50% 45% 50% 45%;background:linear-gradient(145deg,#7c3aed 0%,#5b21b6 60%,#4c1d95 100%);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;box-shadow:0 24px 64px rgba(91,33,182,.35);animation:blobP 7s ease-in-out infinite}
@keyframes blobP{0%,100%{border-radius:50% 45% 50% 45%}33%{border-radius:45% 50% 48% 52%}66%{border-radius:52% 48% 44% 50%}}
.char-frame::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 30% 20%,rgba(255,255,255,.15) 0%,transparent 55%)}
.fchip{position:absolute;background:var(--white);border-radius:11px;padding:9px 13px;box-shadow:0 8px 22px rgba(0,0,0,.12);display:flex;align-items:center;gap:8px;font-size:.71rem;font-weight:700;color:var(--dark);white-space:nowrap;z-index:3;border:1px solid var(--border)}
.fi{width:26px;height:26px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0}
.fc1{top:-14px;right:10px;animation:cf 4s ease-in-out infinite}
.fc2{bottom:60px;right:-30px;animation:cf 4s ease-in-out infinite 1.3s}
.fc3{bottom:20px;left:-24px;animation:cf 4s ease-in-out infinite 2.6s}
@keyframes cf{0%,100%{transform:translateY(0)}50%{transform:translateY(-7px)}}

.trust{background:var(--p);padding:15px 60px;display:flex;align-items:center;justify-content:center;gap:52px;flex-wrap:wrap}
.ti{display:flex;align-items:center;gap:9px;color:rgba(255,255,255,.65);font-size:.79rem;font-weight:600}
.ti i{color:rgba(255,255,255,.4)}.ti strong{color:#fff}

.ss{padding:64px 60px 0;max-width:1160px;margin:0 auto;text-align:center}
.sl{font-size:.67rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--pl);margin-bottom:8px}
.st{font-size:clamp(1.55rem,2.6vw,2.2rem);font-weight:800;color:var(--dark);letter-spacing:-.035em;margin-bottom:8px;line-height:1.1}
.st em{font-style:normal;color:var(--p)}
.sbar{max-width:560px;margin:28px auto 0;display:flex;align-items:center;background:var(--white);border:1.5px solid var(--border);border-radius:12px;padding:5px 5px 5px 16px;box-shadow:0 4px 20px rgba(91,33,182,.08)}
.sbar i{color:var(--light);font-size:.87rem;margin-right:4px}
.sbar input{flex:1;border:none;outline:none;font-size:.87rem;font-family:var(--font);color:var(--dark);background:transparent;padding:8px 0}
.sbar input::placeholder{color:var(--light)}
.sbar button{padding:10px 22px;background:var(--p);color:#fff;border:none;border-radius:9px;font-size:.82rem;font-weight:700;cursor:pointer;font-family:var(--font);transition:all .18s}
.sbar button:hover{background:var(--pm)}

.bsec{padding:72px 60px;max-width:1160px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:68px;align-items:center}
.bv{position:relative;display:flex;justify-content:flex-start}
.bchar-frame{border-radius:40% 50% 45% 50%;display:flex;align-items:flex-end;justify-content:center;overflow:hidden;position:relative}
.bchar-frame::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 30% 15%,rgba(255,255,255,.15) 0%,transparent 50%)}
.bstat{position:absolute;background:var(--white);border-radius:13px;padding:11px 15px;box-shadow:0 8px 22px rgba(0,0,0,.1);border:1px solid var(--border)}
.bsn{font-size:1.45rem;font-weight:800;color:var(--p);letter-spacing:-.04em;line-height:1}
.bsl{font-size:.67rem;font-weight:600;color:var(--muted);margin-top:2px}
.bs1{top:-14px;right:0}.bs2{bottom:36px;right:-20px}
.blist{display:flex;flex-direction:column;gap:24px;margin-top:28px}
.bi{display:flex;gap:15px;align-items:flex-start}
.bico{width:44px;height:44px;border-radius:12px;background:var(--pale);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.bico i{color:var(--p);font-size:.97rem}
.btitle{font-size:.9rem;font-weight:700;color:var(--dark);margin-bottom:4px}
.bdesc{font-size:.79rem;color:var(--muted);line-height:1.6}

.fsec{padding:0 60px 80px;max-width:1160px;margin:0 auto}
.fhdr{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:30px;gap:20px;flex-wrap:wrap}
.fhdr p{font-size:.85rem;color:var(--muted);max-width:380px;line-height:1.6}
.fgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.fcard{background:var(--white);border:1px solid var(--border);border-radius:18px;overflow:hidden;transition:all .22s;cursor:pointer}
.fcard:hover{transform:translateY(-5px);box-shadow:0 14px 40px rgba(91,33,182,.13);border-color:#c4b5fd}
.fthumb{height:170px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
.fbadge{position:absolute;top:10px;left:10px;background:rgba(255,255,255,.9);backdrop-filter:blur(8px);border-radius:7px;padding:3px 10px;font-size:.61rem;font-weight:700;color:var(--p);border:1px solid var(--border)}
.fbody{padding:15px 17px 17px}
.ftag{font-size:.61rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--pl);margin-bottom:5px}
.ftitle{font-size:.88rem;font-weight:700;color:var(--dark);margin-bottom:7px;line-height:1.3}
.fdesc{font-size:.76rem;color:var(--muted);line-height:1.55;margin-bottom:13px}
.ffoot{display:flex;align-items:center;justify-content:space-between}
.fav{display:flex;align-items:center;gap:7px}
.favico{width:23px;height:23px;border-radius:50%;display:flex;align-items:center;justify-content:center}
.favico i{font-size:.52rem;color:#fff}
.favname{font-size:.69rem;font-weight:600;color:var(--muted)}
.facc{font-size:.69rem;font-weight:700;color:var(--p);background:var(--pale);padding:3px 10px;border-radius:6px}

.cta-wrap{max-width:1160px;margin:0 auto 80px;padding:0 60px}
.cta-banner{background:var(--p);border-radius:22px;padding:50px 56px;display:grid;grid-template-columns:1fr auto;align-items:center;gap:40px;position:relative;overflow:hidden}
.cta-banner::before{content:'';position:absolute;right:-50px;top:-50px;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.06)}
.cta-banner::after{content:'';position:absolute;right:100px;bottom:-70px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.04)}
.ctasub{font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:9px}
.ctah{font-size:clamp(1.35rem,2.3vw,1.9rem);font-weight:800;color:#fff;letter-spacing:-.03em;margin-bottom:9px;line-height:1.15}
.ctad{font-size:.84rem;color:rgba(255,255,255,.68);line-height:1.6;max-width:440px}
.cta-btns{display:flex;flex-direction:column;gap:10px;align-items:flex-end;position:relative;z-index:1}
.cbw{padding:12px 26px;background:#fff;color:var(--p);border:none;border-radius:11px;font-size:.87rem;font-weight:800;cursor:pointer;font-family:var(--font);display:inline-flex;align-items:center;gap:8px;white-space:nowrap;transition:all .18s;text-decoration:none}
.cbw:hover{background:var(--xlight);transform:translateY(-1px)}
.cbg{padding:12px 26px;background:rgba(255,255,255,.1);color:#fff;border:1.5px solid rgba(255,255,255,.25);border-radius:11px;font-size:.87rem;font-weight:700;cursor:pointer;font-family:var(--font);text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;transition:all .18s}
.cbg:hover{background:rgba(255,255,255,.18)}

footer{background:var(--dark);padding:26px 60px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.fbdg{display:flex;align-items:center;gap:8px}
.fdg{display:inline-flex;align-items:center;gap:5px;font-size:.64rem;font-weight:700;padding:4px 10px;border-radius:999px;border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.38);background:rgba(255,255,255,.04)}

@media(max-width:900px){
  nav{padding:0 20px}.nav-links{display:none}
  .hero{grid-template-columns:1fr;padding:48px 22px 60px;gap:36px;text-align:center}
  .hcta{justify-content:center}
  .trust{padding:15px 22px;gap:22px}
  .ss{padding:48px 22px 0}
  .bsec{grid-template-columns:1fr;padding:48px 22px;gap:36px}
  .fsec{padding:0 22px 60px}.fgrid{grid-template-columns:1fr}
  .cta-wrap{padding:0 22px;margin-bottom:60px}
  .cta-banner{grid-template-columns:1fr;padding:34px 26px}
  .cta-btns{align-items:stretch}
  .cbw,.cbg{justify-content:center}
  footer{padding:20px 22px;flex-direction:column;align-items:flex-start}
}
@media(min-width:600px) and (max-width:900px){.fgrid{grid-template-columns:repeat(2,1fr)}}
@keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:none}}
.hero>*{animation:fadeUp .6s both}
.hero>*:nth-child(1){animation-delay:.05s}.hero>*:nth-child(2){animation-delay:.18s}

.mov{position:fixed;inset:0;z-index:999;background:rgba(4,10,22,.82);backdrop-filter:blur(14px);display:flex;align-items:flex-start;justify-content:center;padding:20px 16px;opacity:0;pointer-events:none;transition:opacity .28s;overflow-y:auto}
.mov.open{opacity:1;pointer-events:all}
.mmoda{background:#0c1729;border:1px solid rgba(99,102,241,.2);border-radius:18px;width:100%;max-width:820px;box-shadow:0 28px 72px rgba(0,0,0,.55);margin:auto;overflow:hidden}
.mhdr{display:flex;align-items:center;justify-content:space-between;padding:18px 26px;border-bottom:1px solid rgba(99,102,241,.12);background:rgba(55,48,163,.08);gap:10px}
.mhico{width:34px;height:34px;background:rgba(55,48,163,.25);border:1px solid rgba(99,102,241,.3);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#818cf8;font-size:.87rem}
.mhtit{font-size:.92rem;font-weight:800;color:#e2eaf8}
.mhsub{font-size:.66rem;color:#4a6fa5;margin-top:1px}
.mclose{width:30px;height:30px;border-radius:7px;border:1px solid rgba(99,102,241,.2);background:transparent;color:#4a6fa5;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.8rem;transition:all .18s}
.mclose:hover{background:rgba(99,102,241,.12);color:#a5b4fc}
.mbody{padding:24px 26px 32px;display:flex;flex-direction:column;gap:11px}
.mitem{padding:13px 15px;background:rgba(55,48,163,.07);border:1px solid rgba(99,102,241,.11);border-radius:11px}
.mitit{font-size:.77rem;font-weight:800;color:#c7d2fe;margin-bottom:4px}
.midesc{font-size:.73rem;color:#4a6fa5;line-height:1.6}
.mlink{padding:16px 26px;border-top:1px solid rgba(99,102,241,.1);text-align:center;font-size:.76rem;color:#4a6fa5}
.mlink a{color:#818cf8;font-weight:700;text-decoration:none}
</style>
</head>
<body>

<nav>
  <a href="#" class="nb">
    <div class="nl"><i class="fa-solid fa-graduation-cap"></i></div>
    <span class="bn">edu<em>Reserve</em><b>.</b></span>
  </a>
  <div class="nav-links">
    <a href="#" class="active">Home</a>
    <a href="#">Resources</a>
    <a href="#">Library</a>
    <a href="#" onclick="openM()">Manual</a>
  </div>
  <div class="nav-r">
    <a href="/login" class="bo">Sign In</a>
    <a href="/register" class="bs"><i class="fa-solid fa-user-plus" style="font-size:.74rem"></i> Register</a>
  </div>
</nav>

<div class="hero-wrap">
<div class="hero">
  <div>
    <div class="hbadge"><span class="dot"></span>Sangguniang Kabataan · AI-Powered</div>
    <h1 class="htitle">Reserve &amp; Learn<span class="b">in a New<span class="a"> Way.</span></span></h1>
    <p class="hdesc">Book e-learning computers, access the AI-powered library, and manage barangay facilities — all in one digital platform for the youth of Brgy. F. De Jesus.</p>
    <p class="horg"><strong>Sangguniang Kabataan</strong> · Brgy. F. De Jesus, Unisan, Quezon</p>
    <div class="hcta">
      <a href="/register" class="cp"><i class="fa-solid fa-user-plus"></i> Get Started Free</a>
      <a href="/login" class="cs"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
    </div>
  </div>

  <!-- HERO CHARACTER: Student with laptop, glasses, red hair — matches reference style -->
  <div class="hv">
    <div class="char-frame" style="width:320px;height:320px">
      <svg viewBox="0 0 320 320" width="320" height="320" xmlns="http://www.w3.org/2000/svg">
        <circle cx="160" cy="160" r="140" fill="rgba(255,255,255,.04)"/>
        <ellipse cx="160" cy="298" rx="52" ry="9" fill="rgba(0,0,0,.2)"/>
        <!-- shoes -->
        <rect x="124" y="272" width="36" height="13" rx="6" fill="#1e293b"/>
        <rect x="160" y="272" width="36" height="13" rx="6" fill="#1e293b"/>
        <!-- jeans legs -->
        <rect x="128" y="222" width="24" height="56" rx="12" fill="#3b82f6"/>
        <rect x="168" y="222" width="24" height="56" rx="12" fill="#3b82f6"/>
        <!-- white t-shirt body -->
        <rect x="116" y="148" width="88" height="78" rx="22" fill="#f8fafc"/>
        <!-- collar line -->
        <path d="M147 148 Q160 160 173 148" stroke="#e2e8f0" stroke-width="2.5" fill="none"/>
        <!-- left arm extended -->
        <rect x="72" y="175" width="48" height="22" rx="11" fill="#f8fafc"/>
        <!-- right arm -->
        <rect x="200" y="168" width="44" height="22" rx="11" fill="#f8fafc"/>
        <!-- laptop base -->
        <rect x="68" y="195" width="124" height="80" rx="8" fill="#e2e8f0"/>
        <!-- laptop keyboard area -->
        <rect x="72" y="234" width="116" height="8" rx="4" fill="#cbd5e1"/>
        <!-- laptop screen -->
        <rect x="72" y="198" width="116" height="68" rx="7" fill="#1e293b"/>
        <rect x="76" y="202" width="108" height="60" rx="5" fill="#0f172a"/>
        <!-- screen content -->
        <rect x="80" y="207" width="45" height="5" rx="2.5" fill="#818cf8"/>
        <rect x="80" y="216" width="80" height="3" rx="1.5" fill="rgba(255,255,255,.22)"/>
        <rect x="80" y="222" width="65" height="3" rx="1.5" fill="rgba(255,255,255,.16)"/>
        <rect x="80" y="228" width="30" height="10" rx="4" fill="#7c3aed"/>
        <text x="95" y="237" font-size="7" fill="white" font-weight="bold" font-family="Arial">Reserve</text>
        <circle cx="152" cy="232" r="14" fill="rgba(129,140,248,.18)" stroke="#818cf8" stroke-width="1.5"/>
        <text x="152" y="236" text-anchor="middle" font-size="9" fill="#a5b4fc" font-weight="bold" font-family="Arial">AI</text>
        <!-- neck -->
        <rect x="151" y="130" width="18" height="24" rx="9" fill="#fbbf80"/>
        <!-- head -->
        <ellipse cx="160" cy="112" rx="40" ry="42" fill="#fbbf80"/>
        <!-- hair — reddish, styled like reference -->
        <path d="M120 108 Q124 68 160 64 Q196 68 200 108 Q196 76 160 71 Q124 76 120 108Z" fill="#b45309"/>
        <ellipse cx="160" cy="68" rx="32" ry="12" fill="#b45309"/>
        <!-- hair side pieces -->
        <rect x="118" y="96" width="9" height="24" rx="4.5" fill="#b45309"/>
        <rect x="193" y="96" width="9" height="24" rx="4.5" fill="#b45309"/>
        <!-- glasses — matching the reference exactly -->
        <rect x="132" y="108" width="22" height="14" rx="6" fill="none" stroke="#1e293b" stroke-width="2.5"/>
        <rect x="166" y="108" width="22" height="14" rx="6" fill="none" stroke="#1e293b" stroke-width="2.5"/>
        <!-- bridge -->
        <line x1="154" y1="115" x2="166" y2="115" stroke="#1e293b" stroke-width="2"/>
        <!-- arms of glasses -->
        <line x1="120" y1="115" x2="132" y2="115" stroke="#1e293b" stroke-width="2"/>
        <line x1="188" y1="115" x2="200" y2="115" stroke="#1e293b" stroke-width="2"/>
        <!-- eyes behind glasses -->
        <circle cx="143" cy="115" r="4" fill="#1e293b"/>
        <circle cx="177" cy="115" r="4" fill="#1e293b"/>
        <circle cx="144.5" cy="113.5" r="1.5" fill="white"/>
        <circle cx="178.5" cy="113.5" r="1.5" fill="white"/>
        <!-- smile -->
        <path d="M150 128 Q160 136 170 128" stroke="#c2773b" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <!-- cheeks blush -->
        <ellipse cx="130" cy="122" rx="8" ry="5" fill="rgba(251,146,60,.2)"/>
        <ellipse cx="190" cy="122" rx="8" ry="5" fill="rgba(251,146,60,.2)"/>
      </svg>
    </div>
    <div class="fchip fc1">
      <div class="fi" style="background:#ede9fe"><i class="fa-solid fa-calendar-check" style="color:#7c3aed;font-size:.68rem"></i></div>
      <div><div style="font-size:.71rem;font-weight:800">Book a Slot</div><div style="font-size:.6rem;font-weight:500;color:var(--muted)">Real-time</div></div>
    </div>
    <div class="fchip fc2">
      <div class="fi" style="background:#fef3c7"><i class="fa-solid fa-qrcode" style="color:#d97706;font-size:.68rem"></i></div>
      <div><div style="font-size:.71rem;font-weight:800">QR E-Ticket</div><div style="font-size:.6rem;font-weight:500;color:var(--muted)">Paperless</div></div>
    </div>
    <div class="fchip fc3">
      <div class="fi" style="background:#dcfce7"><i class="fa-solid fa-robot" style="color:#16a34a;font-size:.68rem"></i></div>
      <div><div style="font-size:.71rem;font-weight:800">AI Library</div><div style="font-size:.6rem;font-weight:500;color:var(--muted)">RAG-powered</div></div>
    </div>
  </div>
</div>
</div>

<div class="trust">
  <div class="ti"><i class="fa-solid fa-shield-halved"></i><strong>Secure Portal</strong></div>
  <div class="ti"><i class="fa-solid fa-robot"></i><strong>AI-Powered RAG</strong></div>
  <div class="ti"><i class="fa-solid fa-mobile"></i><strong>PWA App</strong></div>
  <div class="ti"><i class="fa-solid fa-qrcode"></i><strong>QR E-Tickets</strong></div>
  <div class="ti"><i class="fa-solid fa-users"></i><strong>Community-First</strong></div>
</div>

<section class="ss">
  <div class="sl">Find Resources</div>
  <h2 class="st">Search <em>Available</em> Resources</h2>
  <div style="font-size:.85rem;color:var(--muted);margin-top:6px">Computers, library books, and barangay facilities</div>
  <div class="sbar">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Search by resource, book title, or topic…"/>
    <button>Search</button>
  </div>
</section>

<!-- BENEFITS — character: woman reading book, green bg -->
<section class="bsec">
  <div class="bv">
    <div class="bchar-frame" style="width:300px;height:370px;background:linear-gradient(145deg,#10b981 0%,#059669 55%,#047857 100%);box-shadow:0 20px 56px rgba(16,185,129,.3)">
      <svg viewBox="0 0 300 370" width="300" height="370" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="150" cy="355" rx="55" ry="9" fill="rgba(0,0,0,.18)"/>
        <!-- legs blue jeans -->
        <rect x="115" y="228" width="24" height="100" rx="12" fill="#1d4ed8"/>
        <rect x="161" y="228" width="24" height="100" rx="12" fill="#1d4ed8"/>
        <!-- shoes -->
        <rect x="102" y="318" width="40" height="14" rx="7" fill="#1e293b"/>
        <rect x="158" y="318" width="40" height="14" rx="7" fill="#1e293b"/>
        <!-- coral/pink top -->
        <rect x="104" y="148" width="92" height="86" rx="24" fill="#fb7185"/>
        <path d="M140 148 Q150 160 160 148" stroke="#f9a8b4" stroke-width="3" fill="none"/>
        <!-- arms wide open holding book -->
        <rect x="55" y="170" width="55" height="24" rx="12" fill="#fb7185"/>
        <rect x="190" y="162" width="55" height="24" rx="12" fill="#fb7185"/>
        <!-- hand skin left -->
        <circle cx="58" cy="183" r="12" fill="#fca5a5"/>
        <!-- book in left hand -->
        <rect x="42" y="178" width="68" height="88" rx="7" fill="#7c3aed"/>
        <rect x="46" y="182" width="30" height="78" rx="4" fill="#6d28d9"/>
        <rect x="78" y="182" width="30" height="78" rx="4" fill="#8b5cf6"/>
        <line x1="76" y1="178" x2="76" y2="266" stroke="#4c1d95" stroke-width="2.5"/>
        <rect x="81" y="196" width="22" height="3" rx="1.5" fill="rgba(255,255,255,.55)"/>
        <rect x="81" y="203" width="18" height="3" rx="1.5" fill="rgba(255,255,255,.4)"/>
        <rect x="81" y="210" width="20" height="3" rx="1.5" fill="rgba(255,255,255,.4)"/>
        <rect x="81" y="217" width="15" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
        <!-- right hand skin -->
        <circle cx="242" cy="175" r="12" fill="#fca5a5"/>
        <!-- neck -->
        <rect x="141" y="134" width="18" height="20" rx="9" fill="#fca5a5"/>
        <!-- head -->
        <ellipse cx="150" cy="114" rx="40" ry="44" fill="#fca5a5"/>
        <!-- dark hair, long, with bun -->
        <path d="M110 110 Q113 66 150 62 Q187 66 190 110 Q187 74 150 70 Q113 74 110 110Z" fill="#1e293b"/>
        <rect x="107" y="98" width="9" height="32" rx="4.5" fill="#1e293b"/>
        <rect x="184" y="98" width="9" height="32" rx="4.5" fill="#1e293b"/>
        <ellipse cx="150" cy="66" rx="34" ry="13" fill="#1e293b"/>
        <!-- bun -->
        <circle cx="150" cy="52" r="14" fill="#374151"/>
        <circle cx="150" cy="52" r="10" fill="#4b5563"/>
        <!-- eyes with lashes -->
        <ellipse cx="136" cy="114" rx="6" ry="6.5" fill="#1e293b"/>
        <ellipse cx="164" cy="114" rx="6" ry="6.5" fill="#1e293b"/>
        <circle cx="138" cy="112.5" r="2" fill="white"/>
        <circle cx="166" cy="112.5" r="2" fill="white"/>
        <!-- upper lashes -->
        <line x1="130" y1="109" x2="128" y2="106" stroke="#1e293b" stroke-width="1.5"/>
        <line x1="136" y1="108" x2="136" y2="105" stroke="#1e293b" stroke-width="1.5"/>
        <line x1="142" y1="109" x2="144" y2="106" stroke="#1e293b" stroke-width="1.5"/>
        <line x1="158" y1="109" x2="156" y2="106" stroke="#1e293b" stroke-width="1.5"/>
        <line x1="164" y1="108" x2="164" y2="105" stroke="#1e293b" stroke-width="1.5"/>
        <line x1="170" y1="109" x2="172" y2="106" stroke="#1e293b" stroke-width="1.5"/>
        <!-- blush -->
        <ellipse cx="122" cy="122" rx="9" ry="6" fill="rgba(251,100,120,.25)"/>
        <ellipse cx="178" cy="122" rx="9" ry="6" fill="rgba(251,100,120,.25)"/>
        <!-- smile -->
        <path d="M140 128 Q150 137 160 128" stroke="#e57373" stroke-width="2.5" fill="none" stroke-linecap="round"/>
        <!-- gold earrings -->
        <circle cx="109" cy="120" r="5" fill="#fbbf24"/>
        <circle cx="191" cy="120" r="5" fill="#fbbf24"/>
        <!-- sparkles -->
        <path d="M25 130 L27 122 L29 130 L37 132 L29 134 L27 142 L25 134 L17 132Z" fill="#fbbf24" opacity=".8"/>
        <path d="M262 90 L263.5 85 L265 90 L270 91.5 L265 93 L263.5 98 L262 93 L257 91.5Z" fill="rgba(255,255,255,.7)"/>
      </svg>
    </div>
    <div class="bstat bs1">
      <div class="bsn">100%</div>
      <div class="bsl">Paperless Process</div>
    </div>
    <div class="bstat bs2">
      <div class="bsn">AI</div>
      <div class="bsl">Smart Library</div>
    </div>
  </div>

  <div>
    <div class="sl">Why EduReserve</div>
    <h2 class="st" style="margin-bottom:10px">Benefits from our<br><em>digital system</em></h2>
    <p style="font-size:.85rem;color:var(--muted);line-height:1.65;margin-bottom:32px">A smarter way to access barangay e-learning resources — no more queues, no paper logs.</p>
    <div class="blist">
      <div class="bi">
        <div class="bico"><i class="fa-solid fa-clock"></i></div>
        <div>
          <div class="btitle">Real-Time Availability</div>
          <div class="bdesc">See which computers and facilities are free right now. Reserve your slot instantly without visiting the barangay hall.</div>
        </div>
      </div>
      <div class="bi">
        <div class="bico"><i class="fa-solid fa-robot"></i></div>
        <div>
          <div class="btitle">AI-Powered Library</div>
          <div class="bdesc">Find books by topic, not just title. Our RAG AI understands meaning and retrieves the most relevant results from the local collection.</div>
        </div>
      </div>
      <div class="bi">
        <div class="bico"><i class="fa-solid fa-users"></i></div>
        <div>
          <div class="btitle">Community-First Access</div>
          <div class="bdesc">Built with fairness in mind — equal, transparent access for every resident of Barangay F. De Jesus.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURE CARDS - each with unique 3D character -->
<section class="fsec">
  <div class="fhdr">
    <div>
      <div class="sl">What You Can Do</div>
      <h2 class="st">Our <em>Key Features</em></h2>
    </div>
    <p>Everything you need to access, reserve, and manage barangay e-learning resources.</p>
  </div>
  <div class="fgrid">

    <!-- Card 1: Student at computer desk -->
    <div class="fcard">
      <div class="fthumb" style="background:linear-gradient(145deg,#ede9fe,#c4b5fd)">
        <span class="fbadge">Free</span>
        <svg viewBox="0 0 170 170" width="170" height="170" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="85" cy="162" rx="38" ry="7" fill="rgba(91,33,182,.2)"/>
          <!-- desk surface -->
          <rect x="18" y="118" width="134" height="10" rx="5" fill="#c4b5fd"/>
          <rect x="28" y="128" width="12" height="32" rx="4" fill="#a78bfa"/>
          <rect x="130" y="128" width="12" height="32" rx="4" fill="#a78bfa"/>
          <!-- monitor -->
          <rect x="45" y="64" width="80" height="56" rx="7" fill="#1e293b"/>
          <rect x="49" y="68" width="72" height="48" rx="5" fill="#0f172a"/>
          <rect x="52" y="72" width="35" height="5" rx="2.5" fill="#818cf8"/>
          <rect x="52" y="80" width="60" height="2.5" rx="1" fill="rgba(255,255,255,.2)"/>
          <rect x="52" y="85" width="48" height="2.5" rx="1" fill="rgba(255,255,255,.15)"/>
          <rect x="52" y="90" width="25" height="9" rx="4" fill="#7c3aed"/>
          <text x="64" y="98" font-size="6" fill="white" font-family="Arial" font-weight="bold">Reserve</text>
          <circle cx="105" cy="95" r="12" fill="rgba(129,140,248,.2)" stroke="#818cf8" stroke-width="1"/>
          <text x="105" y="99" text-anchor="middle" font-size="8" fill="#a5b4fc" font-family="Arial" font-weight="bold">AI</text>
          <!-- monitor stand -->
          <rect x="80" y="120" width="10" height="8" rx="3" fill="#374151"/>
          <rect x="72" y="126" width="26" height="5" rx="2" fill="#374151"/>
          <!-- keyboard -->
          <rect x="42" y="112" width="86" height="8" rx="4" fill="#ddd6fe"/>
          <!-- character body -->
          <rect x="62" y="96" width="46" height="28" rx="13" fill="#f8fafc"/>
          <!-- arms on desk -->
          <rect x="34" y="108" width="30" height="12" rx="6" fill="#f8fafc"/>
          <rect x="106" y="108" width="30" height="12" rx="6" fill="#f8fafc"/>
          <!-- neck -->
          <rect x="80" y="82" width="10" height="17" rx="5" fill="#fbbf80"/>
          <!-- head -->
          <circle cx="85" cy="70" r="19" fill="#fbbf80"/>
          <!-- hair brown -->
          <path d="M66 67 Q68 48 85 46 Q102 48 104 67 Q102 55 85 52 Q68 55 66 67Z" fill="#92400e"/>
          <ellipse cx="85" cy="49" rx="19" ry="8" fill="#92400e"/>
          <!-- eyes -->
          <circle cx="79" cy="70" r="2.5" fill="#1e293b"/>
          <circle cx="91" cy="70" r="2.5" fill="#1e293b"/>
          <circle cx="79.8" cy="69" r="1" fill="white"/>
          <circle cx="91.8" cy="69" r="1" fill="white"/>
          <!-- smile -->
          <path d="M80 77 Q85 82 90 77" stroke="#c2773b" stroke-width="1.8" fill="none" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="fbody">
        <div class="ftag">Reservations</div>
        <div class="ftitle">Reserve E-Learning Computers</div>
        <div class="fdesc">Book computers and workstations with real-time availability and instant QR e-ticket.</div>
        <div class="ffoot">
          <div class="fav"><div class="favico" style="background:var(--p)"><i class="fa-solid fa-user"></i></div><div class="favname">All Residents</div></div>
          <div class="facc">Open Access</div>
        </div>
      </div>
    </div>

    <!-- Card 2: Girl with books stack -->
    <div class="fcard">
      <div class="fthumb" style="background:linear-gradient(145deg,#dcfce7,#86efac)">
        <span class="fbadge" style="color:#16a34a">AI</span>
        <svg viewBox="0 0 170 170" width="170" height="170" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="85" cy="162" rx="38" ry="7" fill="rgba(16,185,129,.2)"/>
          <!-- big book stack -->
          <rect x="30" y="118" width="68" height="14" rx="5" fill="#7c3aed"/>
          <rect x="33" y="105" width="62" height="14" rx="5" fill="#059669"/>
          <rect x="36" y="92" width="56" height="14" rx="5" fill="#d97706"/>
          <rect x="39" y="79" width="50" height="14" rx="5" fill="#dc2626"/>
          <!-- spine details -->
          <rect x="32" y="119" width="3" height="12" rx="1" fill="rgba(255,255,255,.35)"/>
          <rect x="35" y="106" width="3" height="12" rx="1" fill="rgba(255,255,255,.35)"/>
          <rect x="38" y="93" width="3" height="12" rx="1" fill="rgba(255,255,255,.35)"/>
          <rect x="41" y="80" width="3" height="12" rx="1" fill="rgba(255,255,255,.35)"/>
          <!-- character right side -->
          <ellipse cx="125" cy="160" rx="20" ry="7" fill="rgba(0,0,0,.12)"/>
          <!-- legs -->
          <rect x="114" y="130" width="11" height="28" rx="5.5" fill="#1d4ed8"/>
          <rect x="128" y="130" width="11" height="28" rx="5.5" fill="#1d4ed8"/>
          <!-- shoes -->
          <ellipse cx="119" cy="158" rx="10" ry="5" fill="#1e293b"/>
          <ellipse cx="133" cy="158" rx="10" ry="5" fill="#1e293b"/>
          <!-- body pink -->
          <rect x="106" y="98" width="38" height="36" rx="12" fill="#fb7185"/>
          <!-- arms -->
          <rect x="82" y="106" width="27" height="11" rx="5.5" fill="#fb7185"/>
          <rect x="138" y="100" width="20" height="11" rx="5.5" fill="#fb7185"/>
          <!-- neck -->
          <rect x="121" y="86" width="11" height="15" rx="5.5" fill="#fca5a5"/>
          <!-- head -->
          <circle cx="126" cy="74" r="18" fill="#fca5a5"/>
          <!-- ponytail hair -->
          <path d="M108 70 Q110 52 126 50 Q142 52 144 70 Q142 58 126 55 Q110 58 108 70Z" fill="#1e293b"/>
          <ellipse cx="126" cy="53" rx="16" ry="7" fill="#1e293b"/>
          <rect x="140" y="58" width="8" height="20" rx="4" fill="#1e293b"/>
          <!-- eyes -->
          <circle cx="121" cy="74" r="2.5" fill="#1e293b"/>
          <circle cx="131" cy="74" r="2.5" fill="#1e293b"/>
          <circle cx="121.8" cy="73" r="1" fill="white"/>
          <circle cx="131.8" cy="73" r="1" fill="white"/>
          <!-- smile happy -->
          <path d="M120 81 Q126 87 132 81" stroke="#e57373" stroke-width="2" fill="none" stroke-linecap="round"/>
          <!-- sparkle -->
          <path d="M22 72 L23.5 66 L25 72 L31 73.5 L25 75 L23.5 81 L22 75 L16 73.5Z" fill="#fbbf24" opacity=".85"/>
        </svg>
      </div>
      <div class="fbody">
        <div class="ftag">Library</div>
        <div class="ftitle">AI-Powered Book Library</div>
        <div class="fdesc">Search and borrow books using RAG AI — find by topic, author, or subject intelligently.</div>
        <div class="ffoot">
          <div class="fav"><div class="favico" style="background:#16a34a"><i class="fa-solid fa-book"></i></div><div class="favname">SK Library</div></div>
          <div class="facc" style="background:#dcfce7;color:#16a34a">Borrow Free</div>
        </div>
      </div>
    </div>

    <!-- Card 3: Guy with phone showing QR -->
    <div class="fcard">
      <div class="fthumb" style="background:linear-gradient(145deg,#fef3c7,#fde68a)">
        <span class="fbadge" style="color:#d97706">QR</span>
        <svg viewBox="0 0 170 170" width="170" height="170" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="85" cy="162" rx="38" ry="7" fill="rgba(217,119,6,.18)"/>
          <!-- legs dark pants -->
          <rect x="68" y="122" width="16" height="40" rx="8" fill="#374151"/>
          <rect x="88" y="122" width="16" height="40" rx="8" fill="#374151"/>
          <!-- shoes -->
          <ellipse cx="76" cy="162" rx="13" ry="5" fill="#1e293b"/>
          <ellipse cx="96" cy="162" rx="13" ry="5" fill="#1e293b"/>
          <!-- orange body -->
          <rect x="58" y="83" width="54" height="44" rx="15" fill="#f97316"/>
          <!-- arm right holding phone up -->
          <rect x="105" y="70" width="28" height="14" rx="7" fill="#f97316"/>
          <!-- arm left -->
          <rect x="37" y="94" width="26" height="13" rx="6.5" fill="#f97316"/>
          <!-- phone -->
          <rect x="106" y="54" width="28" height="48" rx="6" fill="#1e293b"/>
          <rect x="109" y="57" width="22" height="40" rx="4" fill="#0f172a"/>
          <!-- QR code on phone -->
          <rect x="112" y="61" width="7" height="7" rx="1.5" fill="#fbbf24"/>
          <rect x="121" y="61" width="7" height="7" rx="1.5" fill="#fbbf24"/>
          <rect x="112" y="70" width="7" height="7" rx="1.5" fill="#fbbf24"/>
          <rect x="121" y="70" width="3" height="3" rx=".5" fill="#fbbf24"/>
          <rect x="125" y="70" width="3" height="3" rx=".5" fill="#fbbf24"/>
          <rect x="121" y="74" width="3" height="3" rx=".5" fill="#fbbf24"/>
          <rect x="125" y="74" width="3" height="3" rx=".5" fill="#fbbf24"/>
          <rect x="112" y="80" width="16" height="2" rx="1" fill="rgba(255,255,255,.3)"/>
          <rect x="112" y="84" width="12" height="2" rx="1" fill="rgba(255,255,255,.2)"/>
          <!-- scan lines effect -->
          <line x1="103" y1="63" x2="109" y2="65" stroke="#fbbf24" stroke-width="1.5" stroke-dasharray="2,2" opacity=".8"/>
          <line x1="134" y1="63" x2="134" y2="57" stroke="#fbbf24" stroke-width="1.5" stroke-dasharray="2,2" opacity=".8"/>
          <!-- neck -->
          <rect x="80" y="70" width="10" height="17" rx="5" fill="#fbbf80"/>
          <!-- head -->
          <circle cx="85" cy="58" r="20" fill="#fbbf80"/>
          <!-- black hair curly -->
          <path d="M65 55 Q68 34 85 32 Q102 34 105 55 Q102 40 85 37 Q68 40 65 55Z" fill="#1e293b"/>
          <ellipse cx="85" cy="35" rx="21" ry="9" fill="#1e293b"/>
          <!-- curls texture -->
          <path d="M68 50 Q70 44 74 48" stroke="#374151" stroke-width="2" fill="none"/>
          <path d="M96 50 Q98 44 102 48" stroke="#374151" stroke-width="2" fill="none"/>
          <!-- eyes -->
          <circle cx="79" cy="58" r="3" fill="#1e293b"/>
          <circle cx="91" cy="58" r="3" fill="#1e293b"/>
          <circle cx="80" cy="56.8" r="1.2" fill="white"/>
          <circle cx="92" cy="56.8" r="1.2" fill="white"/>
          <!-- big smile showing teeth -->
          <path d="M78 66 Q85 73 92 66" stroke="#c2773b" stroke-width="2" fill="none" stroke-linecap="round"/>
          <rect x="80" y="66" width="10" height="4" rx="2" fill="white" opacity=".6"/>
        </svg>
      </div>
      <div class="fbody">
        <div class="ftag">E-Tickets</div>
        <div class="ftitle">Digital QR E-Ticket System</div>
        <div class="fdesc">Every approved reservation generates a scannable QR ticket. Paperless and verifiable at entry.</div>
        <div class="ffoot">
          <div class="fav"><div class="favico" style="background:#d97706"><i class="fa-solid fa-qrcode"></i></div><div class="favname">Auto-Generated</div></div>
          <div class="facc" style="background:#fef3c7;color:#d97706">Downloadable</div>
        </div>
      </div>
    </div>

    <!-- Card 4: SK Officer with clipboard, red uniform -->
    <div class="fcard">
      <div class="fthumb" style="background:linear-gradient(145deg,#fee2e2,#fca5a5)">
        <span class="fbadge" style="color:#dc2626">SK</span>
        <svg viewBox="0 0 170 170" width="170" height="170" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="85" cy="162" rx="38" ry="7" fill="rgba(220,38,38,.18)"/>
          <!-- legs dark -->
          <rect x="68" y="122" width="16" height="40" rx="8" fill="#1e293b"/>
          <rect x="88" y="122" width="16" height="40" rx="8" fill="#1e293b"/>
          <!-- shoes -->
          <ellipse cx="76" cy="162" rx="13" ry="5" fill="#0f172a"/>
          <ellipse cx="96" cy="162" rx="13" ry="5" fill="#0f172a"/>
          <!-- red official uniform body -->
          <rect x="55" y="82" width="60" height="46" rx="16" fill="#dc2626"/>
          <!-- SK badge -->
          <rect x="76" y="89" width="18" height="13" rx="3" fill="#fbbf24"/>
          <text x="85" y="99" text-anchor="middle" font-size="7" fill="#92400e" font-weight="bold" font-family="Arial">SK</text>
          <!-- arms -->
          <rect x="30" y="94" width="30" height="14" rx="7" fill="#dc2626"/>
          <rect x="110" y="86" width="30" height="14" rx="7" fill="#dc2626"/>
          <!-- clipboard right -->
          <rect x="136" y="74" width="22" height="30" rx="4" fill="#f1f5f9"/>
          <rect x="143" y="71" width="8" height="6" rx="3" fill="#94a3b8"/>
          <rect x="139" y="82" width="16" height="2.5" rx="1" fill="#cbd5e1"/>
          <rect x="139" y="87" width="12" height="2.5" rx="1" fill="#cbd5e1"/>
          <rect x="139" y="92" width="14" height="2.5" rx="1" fill="#cbd5e1"/>
          <!-- left fist - authoritative -->
          <circle cx="40" cy="101" r="9" fill="#fbbf80"/>
          <!-- neck -->
          <rect x="80" y="69" width="10" height="17" rx="5" fill="#fbbf80"/>
          <!-- head -->
          <circle cx="85" cy="56" r="20" fill="#fbbf80"/>
          <!-- hair neat professional -->
          <path d="M65 53 Q68 33 85 31 Q102 33 105 53 Q102 40 85 37 Q68 40 65 53Z" fill="#1e293b"/>
          <ellipse cx="85" cy="34" rx="20" ry="9" fill="#1e293b"/>
          <rect x="64" y="44" width="8" height="18" rx="4" fill="#1e293b"/>
          <!-- eyes serious/confident -->
          <circle cx="79" cy="56" r="3" fill="#1e293b"/>
          <circle cx="91" cy="56" r="3" fill="#1e293b"/>
          <circle cx="80" cy="54.8" r="1.2" fill="white"/>
          <circle cx="92" cy="54.8" r="1.2" fill="white"/>
          <!-- subtle confident smile -->
          <path d="M80 64 Q85 68 90 64" stroke="#c2773b" stroke-width="1.8" fill="none" stroke-linecap="round"/>
          <!-- shield floating badge -->
          <circle cx="38" cy="76" r="12" fill="#dc2626" opacity=".85"/>
          <path d="M38 68 C34 69 31 72 31 76 L31 80 C31 83 34 85 38 86 C42 85 45 83 45 80 L45 76 C45 72 42 69 38 68Z" fill="white" opacity=".7"/>
          <text x="38" y="81" text-anchor="middle" font-size="7" fill="#dc2626" font-weight="bold" font-family="Arial">✓</text>
        </svg>
      </div>
      <div class="fbody">
        <div class="ftag">Officer Portal</div>
        <div class="ftitle">SK Officer Management Portal</div>
        <div class="fdesc">Approve reservations, scan QR tickets, manage walk-ins and monitor live resource usage.</div>
        <div class="ffoot">
          <div class="fav"><div class="favico" style="background:#dc2626"><i class="fa-solid fa-shield-halved"></i></div><div class="favname">SK Officers</div></div>
          <div class="facc" style="background:#fee2e2;color:#dc2626">Officers Only</div>
        </div>
      </div>
    </div>

    <!-- Card 5: Chairman with crown, tablet -->
    <div class="fcard">
      <div class="fthumb" style="background:linear-gradient(145deg,#f3e8ff,#e9d5ff)">
        <span class="fbadge">Admin</span>
        <svg viewBox="0 0 170 170" width="170" height="170" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="85" cy="162" rx="38" ry="7" fill="rgba(124,58,237,.18)"/>
          <rect x="68" y="122" width="16" height="40" rx="8" fill="#1e293b"/>
          <rect x="88" y="122" width="16" height="40" rx="8" fill="#1e293b"/>
          <ellipse cx="76" cy="162" rx="13" ry="5" fill="#0f172a"/>
          <ellipse cx="96" cy="162" rx="13" ry="5" fill="#0f172a"/>
          <!-- purple formal body -->
          <rect x="55" y="82" width="60" height="46" rx="16" fill="#7c3aed"/>
          <!-- gold tie / sash -->
          <rect x="82" y="82" width="6" height="30" rx="3" fill="#fbbf24"/>
          <path d="M82 82 L85 90 L88 82Z" fill="#d97706"/>
          <!-- arms -->
          <rect x="28" y="93" width="30" height="14" rx="7" fill="#7c3aed"/>
          <rect x="112" y="85" width="30" height="14" rx="7" fill="#7c3aed"/>
          <!-- tablet in hands -->
          <rect x="42" y="106" width="86" height="50" rx="6" fill="#1e293b"/>
          <rect x="45" y="109" width="80" height="44" rx="4" fill="#0f172a"/>
          <rect x="48" y="113" width="35" height="4" rx="2" fill="#818cf8"/>
          <rect x="48" y="120" width="72" height="2.5" rx="1" fill="rgba(255,255,255,.2)"/>
          <rect x="48" y="125" width="55" height="2.5" rx="1" fill="rgba(255,255,255,.15)"/>
          <rect x="48" y="131" width="22" height="9" rx="4" fill="#7c3aed"/>
          <rect x="74" y="131" width="22" height="9" rx="4" fill="#dc2626"/>
          <text x="59" y="139" font-size="5.5" fill="white" font-family="Arial" font-weight="bold">Approve</text>
          <text x="85" y="139" font-size="5.5" fill="white" font-family="Arial" font-weight="bold">Decline</text>
          <!-- neck -->
          <rect x="80" y="69" width="10" height="17" rx="5" fill="#fbbf80"/>
          <!-- head -->
          <circle cx="85" cy="56" r="20" fill="#fbbf80"/>
          <!-- CROWN -->
          <path d="M66 46 L70 36 L78 44 L85 30 L92 44 L100 36 L104 46Z" fill="#fbbf24"/>
          <rect x="66" y="44" width="38" height="6" rx="2" fill="#f59e0b"/>
          <circle cx="72" cy="39" r="3" fill="#ef4444"/>
          <circle cx="85" cy="33" r="3" fill="#3b82f6"/>
          <circle cx="98" cy="39" r="3" fill="#10b981"/>
          <!-- hair under crown -->
          <path d="M68 52 Q70 40 85 38 Q100 40 102 52 Q100 45 85 43 Q70 45 68 52Z" fill="#374151"/>
          <!-- eyes -->
          <circle cx="79" cy="56" r="3" fill="#1e293b"/>
          <circle cx="91" cy="56" r="3" fill="#1e293b"/>
          <circle cx="80" cy="54.8" r="1.2" fill="white"/>
          <circle cx="92" cy="54.8" r="1.2" fill="white"/>
          <path d="M80 64 Q85 69 90 64" stroke="#c2773b" stroke-width="2" fill="none" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="fbody">
        <div class="ftag">Administration</div>
        <div class="ftitle">Chairman Admin Dashboard</div>
        <div class="fdesc">Full control — manage SK accounts, resource status, system logs, and all reservations.</div>
        <div class="ffoot">
          <div class="fav"><div class="favico" style="background:#7c3aed"><i class="fa-solid fa-crown"></i></div><div class="favname">SK Chairman</div></div>
          <div class="facc">Full Access</div>
        </div>
      </div>
    </div>

    <!-- Card 6: Happy girl with phone install -->
    <div class="fcard">
      <div class="fthumb" style="background:linear-gradient(145deg,#e0f2fe,#7dd3fc)">
        <span class="fbadge" style="color:#0284c7">PWA</span>
        <svg viewBox="0 0 170 170" width="170" height="170" xmlns="http://www.w3.org/2000/svg">
          <ellipse cx="85" cy="162" rx="38" ry="7" fill="rgba(2,132,199,.18)"/>
          <rect x="72" y="122" width="14" height="40" rx="7" fill="#0284c7"/>
          <rect x="90" y="122" width="14" height="40" rx="7" fill="#0284c7"/>
          <ellipse cx="79" cy="162" rx="12" ry="5" fill="#1e293b"/>
          <ellipse cx="97" cy="162" rx="12" ry="5" fill="#1e293b"/>
          <!-- sky blue top -->
          <rect x="57" y="84" width="56" height="42" rx="14" fill="#0ea5e9"/>
          <!-- arms raised holding phone high -->
          <rect x="28" y="80" width="32" height="13" rx="6.5" fill="#0ea5e9"/>
          <rect x="110" y="74" width="32" height="13" rx="6.5" fill="#0ea5e9"/>
          <!-- large phone held up -->
          <rect x="52" y="40" width="44" height="72" rx="8" fill="#1e293b"/>
          <rect x="55" y="43" width="38" height="64" rx="6" fill="#0f172a"/>
          <!-- app icons grid on phone -->
          <rect x="58" y="48" width="12" height="12" rx="3.5" fill="#7c3aed"/>
          <rect x="74" y="48" width="12" height="12" rx="3.5" fill="#dc2626"/>
          <rect x="58" y="63" width="12" height="12" rx="3.5" fill="#d97706"/>
          <rect x="74" y="63" width="12" height="12" rx="3.5" fill="#16a34a"/>
          <text x="64" y="57" text-anchor="middle" font-size="7" fill="white" font-family="Arial">e</text>
          <text x="80" y="57" text-anchor="middle" font-size="7" fill="white" font-family="Arial">R</text>
          <text x="64" y="72" text-anchor="middle" font-size="7" fill="white" font-family="Arial">Q</text>
          <text x="80" y="72" text-anchor="middle" font-size="7" fill="white" font-family="Arial">AI</text>
          <!-- home bar -->
          <rect x="65" y="98" width="18" height="3" rx="1.5" fill="rgba(255,255,255,.35)"/>
          <!-- + install icon at top right of phone -->
          <circle cx="82" cy="37" r="8" fill="#22c55e"/>
          <line x1="82" y1="33" x2="82" y2="41" stroke="white" stroke-width="2" stroke-linecap="round"/>
          <line x1="78" y1="37" x2="86" y2="37" stroke="white" stroke-width="2" stroke-linecap="round"/>
          <!-- neck -->
          <rect x="80" y="71" width="10" height="17" rx="5" fill="#fbbf80"/>
          <!-- head -->
          <circle cx="85" cy="59" r="19" fill="#fbbf80"/>
          <!-- light brown hair with bangs -->
          <path d="M66 56 Q68 38 85 36 Q102 38 104 56 Q102 45 85 42 Q68 45 66 56Z" fill="#b45309"/>
          <ellipse cx="85" cy="39" rx="20" ry="9" fill="#b45309"/>
          <rect x="64" y="50" width="9" height="18" rx="4.5" fill="#b45309"/>
          <!-- bangs -->
          <rect x="68" y="52" width="34" height="8" rx="4" fill="#92400e"/>
          <!-- eyes - excited wide -->
          <circle cx="79" cy="59" r="3.5" fill="#1e293b"/>
          <circle cx="91" cy="59" r="3.5" fill="#1e293b"/>
          <circle cx="80.2" cy="57.5" r="1.5" fill="white"/>
          <circle cx="92.2" cy="57.5" r="1.5" fill="white"/>
          <!-- big excited smile -->
          <path d="M77 67 Q85 75 93 67" stroke="#c2773b" stroke-width="2.5" fill="none" stroke-linecap="round"/>
          <!-- blush marks -->
          <ellipse cx="72" cy="64" rx="6" ry="4" fill="rgba(251,146,60,.25)"/>
          <ellipse cx="98" cy="64" rx="6" ry="4" fill="rgba(251,146,60,.25)"/>
          <!-- wifi stars -->
          <path d="M22 70 Q28 64 34 70" stroke="#fbbf24" stroke-width="2" fill="none" stroke-linecap="round"/>
          <path d="M25 73 Q28 70 31 73" stroke="#fbbf24" stroke-width="1.5" fill="none" stroke-linecap="round"/>
          <circle cx="28" cy="75.5" r="2" fill="#fbbf24"/>
          <path d="M148 58 L149.5 52 L151 58 L157 59.5 L151 61 L149.5 67 L148 61 L142 59.5Z" fill="#fbbf24" opacity=".8"/>
        </svg>
      </div>
      <div class="fbody">
        <div class="ftag">App</div>
        <div class="ftitle">Install as Mobile App (PWA)</div>
        <div class="fdesc">Add EduReserve to your home screen for an app-like experience on Android &amp; iPhone.</div>
        <div class="ffoot">
          <div class="fav"><div class="favico" style="background:#0284c7"><i class="fa-solid fa-mobile"></i></div><div class="favname">All Users</div></div>
          <div class="facc" style="background:#e0f2fe;color:#0284c7">Free Install</div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- CTA BANNER -->
<div class="cta-wrap">
  <div class="cta-banner">
    <div>
      <div class="ctasub">User Guide</div>
      <div class="ctah">New to EduReserve?<br>Read the User Manual</div>
      <p class="ctad">Step-by-step guides for residents, SK Officers, and the Chairman — from registration to QR scanning and AI library.</p>
    </div>
    <div class="cta-btns">
      <button class="cbw" onclick="openM()"><i class="fa-solid fa-book-open"></i> Open User Manual</button>
      <a href="/register" class="cbg"><i class="fa-solid fa-user-plus"></i> Create an Account</a>
    </div>
  </div>
</div>

<footer>
  <div>
    <div style="font-size:1.1rem;font-weight:800;color:rgba(255,255,255,.85);letter-spacing:-.03em">edu<em style="font-style:normal;color:#a78bfa">Reserve</em><span style="color:var(--gold)">.</span></div>
    <div style="font-size:.7rem;color:rgba(255,255,255,.3);margin-top:2px">© 2026 SK Brgy. F. De Jesus · Unisan, Quezon · All rights reserved.</div>
  </div>
  <div class="fbdg">
    <span class="fdg"><i class="fa-solid fa-shield-halved" style="font-size:.55rem"></i> Secure</span>
    <span class="fdg"><i class="fa-solid fa-robot" style="font-size:.55rem"></i> AI-Powered</span>
    <span class="fdg"><i class="fa-solid fa-mobile" style="font-size:.55rem"></i> PWA</span>
    <button class="fdg" onclick="openM()" style="cursor:pointer;border-color:rgba(212,160,23,.3);background:rgba(212,160,23,.08);color:#fbbf24"><i class="fa-solid fa-book" style="font-size:.55rem"></i> Manual</button>
  </div>
</footer>

<!-- MANUAL MODAL -->
<div class="mov" id="mov" onclick="if(event.target===this)closeM()">
  <div class="mmoda">
    <div class="mhdr">
      <div style="display:flex;align-items:center;gap:11px">
        <div class="mhico"><i class="fa-solid fa-book"></i></div>
        <div>
          <div class="mhtit">EduReserve · User Manual</div>
          <div class="mhsub">Version 1.0 · 2026 · Brgy. F. De Jesus, Unisan, Quezon</div>
        </div>
      </div>
      <button class="mclose" onclick="closeM()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="mbody">
      <div class="mitem"><div class="mitit">01 · Getting Started</div><div class="midesc">Register with your email, verify your account, then log in. SK Officers require Chairman approval before gaining access.</div></div>
      <div class="mitem"><div class="mitit">02 · Making Reservations</div><div class="midesc">Choose a resource, set date/time and purpose, then submit. Download your QR e-ticket once approved by an SK Officer.</div></div>
      <div class="mitem"><div class="mitit">03 · SK Officer Guide</div><div class="midesc">Approve or decline requests, scan QR tickets using your camera, and create walk-in reservations for immediate use.</div></div>
      <div class="mitem"><div class="mitit">04 · Chairman / Admin Guide</div><div class="midesc">Manage all SK accounts, set PC/resource statuses (Available, Maintenance, Out of Order), and view system logs.</div></div>
      <div class="mitem"><div class="mitit">05 · AI Library (RAG)</div><div class="midesc">Search and borrow books using the AI assistant. Officers can upload PDFs and AI auto-extracts book metadata.</div></div>
      <div class="mitem"><div class="mitit">06 · Profile &amp; PWA Install</div><div class="midesc">Update your profile details and password. Install EduReserve as a PWA from Chrome (Android) or Safari (iPhone).</div></div>
    </div>
    <div class="mlink">Access the system at: <a href="https://reservation-k2eg.onrender.com" target="_blank">https://reservation-k2eg.onrender.com</a></div>
  </div>
</div>

<script>
function openM(){document.getElementById('mov').classList.add('open');document.body.style.overflow='hidden'}
function closeM(){document.getElementById('mov').classList.remove('open');document.body.style.overflow=''}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeM()})
</script>
</body>
</html>