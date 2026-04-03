<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>mySpace · Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Sora:wght@700;800&display=swap" rel="stylesheet"/>
<style>
/* ── Reset & tokens ─────────────────────────────────────── */
*{box-sizing:border-box;margin:0;padding:0;}
:root{
  --bg:#f0f4fd;
  --surface:#ffffff;
  --surface2:#f5f8ff;
  --blue:#2563eb;
  --blue-lt:#eff6ff;
  --blue-mid:#bfdbfe;
  --blue-dk:#1e3a8a;
  --text:#0f172a;
  --text2:#475569;
  --text3:#94a3b8;
  --border:rgba(37,99,235,.09);
  --border2:rgba(37,99,235,.18);
  --sb:#1e3a8a;
  --sb-text:rgba(255,255,255,.60);
  --sb-act:#ffffff;
  --sb-act-bg:rgba(255,255,255,.13);
  --amber-lt:#fef3c7;--amber-txt:#92400e;
  --green-lt:#dcfce7;--green-txt:#166534;
  --purple-lt:#f3e8ff;--purple-txt:#6b21a8;
  --rose-lt:#fee2e2;--rose-txt:#991b1b;
  --orange-lt:#fff7ed;--orange-txt:#c2410c;
  --slate-lt:#f1f5f9;--slate-txt:#475569;
  --font:'Inter',sans-serif;
  --font-display:'Sora',sans-serif;
}
[data-dark]{
  --bg:#06101f;
  --surface:#0c1a30;
  --surface2:#101e35;
  --blue:#60a5fa;
  --blue-lt:rgba(37,99,235,.18);
  --blue-mid:rgba(37,99,235,.35);
  --blue-dk:#93c5fd;
  --text:#e8f0fe;
  --text2:#7fb3e8;
  --text3:#3d6690;
  --border:rgba(96,165,250,.10);
  --border2:rgba(96,165,250,.22);
  --sb:#040d1c;
  --sb-text:rgba(148,197,253,.60);
  --sb-act:#dbeafe;
  --sb-act-bg:rgba(96,165,250,.18);
  --amber-lt:#422006;--amber-txt:#fcd34d;
  --green-lt:#052e16;--green-txt:#4ade80;
  --purple-lt:#2e1065;--purple-txt:#d8b4fe;
  --rose-lt:#4c0519;--rose-txt:#fca5a5;
  --orange-lt:#431407;--orange-txt:#fb923c;
  --slate-lt:#1e293b;--slate-txt:#94a3b8;
}

html,body{height:100%;background:var(--bg);color:var(--text);font-family:var(--font);font-size:14px;line-height:1.5;transition:background .3s,color .3s;}

.app{display:flex;height:100vh;overflow:hidden;}

/* ── Sidebar ────────────────────────────────────────────── */
.sidebar{
  width:248px;flex-shrink:0;background:var(--sb);
  display:flex;flex-direction:column;padding:22px 12px;
  transition:background .3s;overflow:hidden;
  position:relative;
}
.sidebar::after{
  content:'';position:absolute;right:0;top:0;bottom:0;width:1px;
  background:linear-gradient(to bottom,transparent 0%,rgba(255,255,255,.07) 40%,rgba(255,255,255,.07) 60%,transparent 100%);
}
.sb-brand{padding:2px 10px 18px;border-bottom:1px solid rgba(255,255,255,.07);margin-bottom:10px;}
.sb-tag{font-size:9px;font-weight:700;letter-spacing:.22em;color:rgba(148,197,253,.45);text-transform:uppercase;margin-bottom:4px;}
.sb-name{font-family:var(--font-display);font-size:21px;font-weight:800;color:#fff;letter-spacing:-.3px;}
.sb-name span{color:#60a5fa;}

.nav-link{
  display:flex;align-items:center;gap:10px;
  padding:9px 11px;border-radius:12px;
  font-size:13px;font-weight:600;color:var(--sb-text);
  cursor:pointer;transition:all .15s;text-decoration:none;
  letter-spacing:-.1px;
}
.nav-link:hover{background:rgba(255,255,255,.07);color:rgba(255,255,255,.88);}
.nav-link.active{background:var(--sb-act-bg);color:var(--sb-act);}
.nav-icon{
  width:32px;height:32px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-size:13px;flex-shrink:0;
}
.nav-link.active .nav-icon{background:rgba(255,255,255,.14);}

.quota-wrap{margin:10px 4px 6px;background:rgba(255,255,255,.05);border-radius:12px;padding:11px 12px;}
.quota-lbl{display:flex;justify-content:space-between;font-size:9px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:rgba(148,197,253,.45);margin-bottom:7px;}
.quota-lbl span:last-child{color:rgba(148,197,253,.75);letter-spacing:0;}
.quota-track{height:4px;border-radius:999px;background:rgba(255,255,255,.10);overflow:hidden;}
.quota-fill{height:100%;border-radius:999px;background:#60a5fa;width:33%;}
.quota-note{font-size:10px;font-weight:500;color:rgba(148,197,253,.55);margin-top:6px;}

.sb-footer{margin-top:auto;border-top:1px solid rgba(255,255,255,.06);padding-top:10px;}
.sb-logout{display:flex;align-items:center;gap:10px;padding:9px 11px;border-radius:12px;font-size:13px;font-weight:600;color:rgba(248,113,113,.70);cursor:pointer;transition:all .15s;}
.sb-logout:hover{background:rgba(239,68,68,.10);color:#f87171;}

/* ── Main ───────────────────────────────────────────────── */
.main{flex:1;overflow-y:auto;padding:26px 28px 48px;display:flex;flex-direction:column;gap:18px;}

/* ── Top bar ────────────────────────────────────────────── */
.topbar{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;}
.greeting-eyebrow{font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--text3);margin-bottom:4px;}
.greeting-name{font-family:var(--font-display);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.4px;line-height:1.1;}
.greeting-date{font-size:12px;font-weight:400;color:var(--text3);margin-top:3px;}
.topbar-right{display:flex;align-items:center;gap:8px;flex-shrink:0;margin-top:4px;}

.reserve-btn{
  display:flex;align-items:center;gap:6px;
  padding:8px 15px;background:var(--blue);color:#fff;
  border-radius:10px;font-size:12px;font-weight:600;
  border:none;cursor:pointer;transition:opacity .15s;
  font-family:var(--font);letter-spacing:-.1px;
}
.reserve-btn:hover{opacity:.88;}
.notif-btn{
  width:36px;height:36px;background:var(--surface);
  border:1px solid var(--border2);border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  color:var(--text2);font-size:13px;cursor:pointer;
  position:relative;transition:background .15s;
}
.notif-btn:hover{background:var(--blue-lt);}
.notif-dot{position:absolute;top:7px;right:7px;width:7px;height:7px;background:#ef4444;border-radius:50%;border:2px solid var(--surface);}
.toggle-row{display:flex;align-items:center;gap:7px;}
.tgl-label{font-size:12px;color:var(--text3);}
.tgl{width:40px;height:22px;background:var(--blue-mid);border-radius:999px;border:1.5px solid var(--border2);cursor:pointer;position:relative;transition:background .25s;flex-shrink:0;}
.tgl::after{content:'';position:absolute;top:2px;left:2px;width:14px;height:14px;background:var(--blue);border-radius:50%;transition:transform .25s,background .25s;}
[data-dark] .tgl::after{transform:translateX(18px);background:#60a5fa;}

/* ── Cards / atoms ──────────────────────────────────────── */
.card{background:var(--surface);border:1px solid var(--border);border-radius:18px;transition:background .3s,border .3s;}
.card-p{padding:18px 20px;}
.section-eyebrow{font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--text3);margin-bottom:12px;}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.card-title-wrap{display:flex;align-items:center;gap:9px;}
.card-icon{width:32px;height:32px;background:var(--blue-lt);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;color:var(--blue);flex-shrink:0;}
.card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:-.1px;}
.card-sub{font-size:10px;font-weight:400;color:var(--text3);margin-top:1px;}
.link-sm{font-size:10px;font-weight:700;color:var(--blue);text-decoration:none;letter-spacing:.04em;text-transform:uppercase;}

/* ── Next action ────────────────────────────────────────── */
.next-card{border-radius:16px;padding:13px 15px;border:1.5px solid;display:flex;align-items:flex-start;gap:12px;}
.next-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;}
.next-eyebrow{font-size:9px;font-weight:700;letter-spacing:.16em;text-transform:uppercase;margin-bottom:3px;}
.next-msg{font-size:12px;font-weight:400;color:var(--text2);line-height:1.55;}
.next-btn{
  display:inline-flex;align-items:center;gap:4px;
  margin-top:9px;padding:5px 12px;border-radius:8px;
  font-size:11px;font-weight:600;color:#fff;
  text-decoration:none;cursor:pointer;border:none;
  font-family:var(--font);
}

/* ── Upcoming pill ──────────────────────────────────────── */
.upcoming-pill{
  background:var(--blue-lt);border:1px solid var(--blue-mid);
  border-radius:14px;padding:12px 15px;
  display:flex;align-items:center;gap:12px;
}
.up-icon{width:36px;height:36px;background:var(--blue);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:13px;color:#fff;flex-shrink:0;}
.up-eyebrow{font-size:9px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--blue);}
.up-name{font-size:13px;font-weight:600;color:var(--text);letter-spacing:-.1px;}
.up-time{font-size:11px;font-weight:400;color:var(--blue-dk);}
.up-link{margin-left:auto;font-size:11px;font-weight:600;color:var(--blue);background:var(--surface);border:1px solid var(--border2);border-radius:8px;padding:5px 11px;cursor:pointer;white-space:nowrap;text-decoration:none;}

/* ── Stats grid ─────────────────────────────────────────── */
.stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:16px 18px;transition:background .3s;}
.stat-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:11px;}
.stat-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:13px;}
.stat-lbl{font-size:9px;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--text3);}
.stat-val{font-family:var(--font-display);font-size:28px;font-weight:800;color:var(--text);line-height:1;letter-spacing:-.5px;}
.stat-hint{font-size:11px;font-weight:400;color:var(--text3);margin-top:3px;}

/* ── Main 2-col grid ────────────────────────────────────── */
.grid-main{display:grid;grid-template-columns:minmax(0,1.9fr) minmax(0,1fr);gap:16px;}
.side-col{display:flex;flex-direction:column;gap:14px;}

/* ── Calendar ───────────────────────────────────────────── */
.cal-legend{display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.leg-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0;}
.leg-lbl{font-size:10px;font-weight:500;color:var(--text3);}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;margin-top:8px;}
.cal-dh{text-align:center;font-size:10px;font-weight:600;color:var(--text3);padding:3px 0;letter-spacing:.05em;}
.cal-d{text-align:center;font-size:11px;font-weight:500;color:var(--text2);padding:6px 3px;border-radius:8px;cursor:pointer;transition:background .15s;position:relative;}
.cal-d:hover{background:var(--blue-lt);}
.cal-d.today{background:var(--blue-lt);color:var(--blue);font-weight:700;}
.cal-d.has-ev::after{content:'';display:block;width:4px;height:4px;border-radius:50%;background:var(--blue);margin:2px auto 0;}
.cal-d.other{color:var(--text3);}

/* ── Quick actions ──────────────────────────────────────── */
.qa-list{display:flex;flex-direction:column;gap:5px;margin-top:10px;}
.qa-item{
  display:flex;align-items:center;gap:9px;
  padding:8px 10px;border-radius:11px;
  border:1px solid var(--border);background:var(--surface);
  text-decoration:none;color:var(--text2);
  font-size:12px;font-weight:600;cursor:pointer;transition:all .15s;
  letter-spacing:-.1px;
}
.qa-item:hover{border-color:var(--blue);background:var(--blue-lt);color:var(--blue);}
.qa-icon{width:28px;height:28px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;}
.qa-chevron{margin-left:auto;font-size:9px;color:var(--text3);}

/* ── Recent bookings ────────────────────────────────────── */
.bk-row{display:flex;align-items:center;gap:10px;padding:8px 9px;border-radius:11px;text-decoration:none;color:inherit;transition:background .15s;cursor:pointer;}
.bk-row:hover{background:var(--blue-lt);}
.bk-date{width:36px;height:36px;background:var(--surface2);border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--border);}
.bk-month{font-size:8px;font-weight:700;text-transform:uppercase;color:var(--text3);}
.bk-day{font-size:15px;font-weight:800;color:var(--text);line-height:1;font-family:var(--font-display);}
.bk-name{font-size:12px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;letter-spacing:-.1px;}
.bk-time{font-size:10px;font-weight:400;color:var(--text3);margin-top:1px;}

/* ── Tags ───────────────────────────────────────────────── */
.tag{display:inline-flex;align-items:center;padding:3px 8px;border-radius:999px;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;flex-shrink:0;}
.tag-pending{background:var(--amber-lt);color:var(--amber-txt);}
.tag-approved{background:var(--green-lt);color:var(--green-txt);}
.tag-claimed{background:var(--purple-lt);color:var(--purple-txt);}
.tag-declined,.tag-cancelled{background:var(--rose-lt);color:var(--rose-txt);}
.tag-unclaimed{background:var(--orange-lt);color:var(--orange-txt);}
.tag-expired{background:var(--slate-lt);color:var(--slate-txt);}
.tag-active{background:var(--green-lt);color:var(--green-txt);}

/* ── Library section ────────────────────────────────────── */
.grid-lib{display:grid;grid-template-columns:minmax(0,1.9fr) minmax(0,1fr);gap:16px;}

.lib-banner{
  background:var(--blue-dk);border-radius:18px;padding:20px 22px;
  position:relative;overflow:hidden;
}
[data-dark] .lib-banner{background:#071526;}
.lib-banner::before{
  content:'';position:absolute;right:-30px;top:-30px;
  width:140px;height:140px;border-radius:50%;
  background:rgba(255,255,255,.04);pointer-events:none;
}
.lib-eyebrow{font-size:9px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(148,197,253,.55);margin-bottom:4px;}
.lib-title{font-family:var(--font-display);font-size:19px;font-weight:800;color:#fff;letter-spacing:-.3px;}
.lib-sub{font-size:11px;font-weight:400;color:rgba(148,197,253,.65);margin-top:2px;}
.lib-browse{
  display:inline-flex;align-items:center;gap:6px;
  padding:7px 14px;background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.16);border-radius:10px;
  color:#fff;font-size:11px;font-weight:600;
  cursor:pointer;text-decoration:none;margin-top:14px;
  transition:background .15s;font-family:var(--font);
}
.lib-browse:hover{background:rgba(255,255,255,.17);}
.lib-stats{display:flex;gap:14px;margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,.08);}
.lib-stat{display:flex;align-items:center;gap:8px;}
.lib-stat-icon{width:26px;height:26px;background:rgba(255,255,255,.08);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#93c5fd;}
.lib-stat-lbl{font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:rgba(148,197,253,.50);}
.lib-stat-val{font-size:13px;font-weight:700;color:#fff;font-family:var(--font-display);}

.search-wrap{position:relative;margin:12px 0 10px;}
.search-wrap i{position:absolute;left:11px;top:50%;transform:translateY(-50%);font-size:11px;color:var(--text3);pointer-events:none;}
.search-input{
  width:100%;padding:8px 12px 8px 30px;
  border-radius:11px;border:1.5px solid var(--border2);
  background:var(--surface2);
  font-family:var(--font);font-size:12px;font-weight:400;
  color:var(--text);outline:none;transition:border .15s,background .15s;
}
.search-input:focus{border-color:var(--blue);background:var(--surface);}
.search-input::placeholder{color:var(--text3);}

.find-btn{
  display:inline-flex;align-items:center;gap:5px;
  padding:7px 13px;background:var(--blue);color:#fff;
  border-radius:9px;font-size:11px;font-weight:600;
  border:none;cursor:pointer;font-family:var(--font);
  transition:opacity .15s;
}
.find-btn:hover{opacity:.88;}

/* ── Book rows ──────────────────────────────────────────── */
.book-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);}
.book-row:last-child{border-bottom:none;}
.book-letter{width:34px;height:34px;border-radius:10px;background:var(--blue-lt);color:var(--blue);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;flex-shrink:0;font-family:var(--font-display);}
.book-title{font-size:12px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;letter-spacing:-.1px;}
.book-author{font-size:10px;font-weight:400;color:var(--text3);}
.avail-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0;}
.avail-dot.on{background:#3b82f6;}
.avail-dot.off{background:#f87171;}
.avail-num{font-size:9px;font-weight:600;color:var(--text3);}

/* ── Divider ────────────────────────────────────────────── */
.divider{height:1px;background:var(--border);margin:10px 0;}
</style>
</head>
<body>
<div class="app" id="app">

  <!-- ── Sidebar ── -->
  <aside class="sidebar">
    <div class="sb-brand">
      <div class="sb-tag">Resident Portal</div>
      <div class="sb-name">my<span>Space.</span></div>
    </div>

    <a class="nav-link active" href="#">
      <div class="nav-icon"><i class="fa-solid fa-house" style="font-size:13px;"></i></div>Dashboard
    </a>
    <a class="nav-link" href="#">
      <div class="nav-icon"><i class="fa-solid fa-plus" style="font-size:13px;"></i></div>New Reservation
    </a>
    <a class="nav-link" href="#">
      <div class="nav-icon"><i class="fa-regular fa-calendar" style="font-size:13px;"></i></div>My Reservations
    </a>
    <a class="nav-link" href="#">
      <div class="nav-icon"><i class="fa-solid fa-book-open" style="font-size:13px;"></i></div>Library
    </a>
    <a class="nav-link" href="#">
      <div class="nav-icon"><i class="fa-regular fa-user" style="font-size:13px;"></i></div>Profile
    </a>

    <div class="quota-wrap">
      <div class="quota-lbl">
        <span>Monthly Quota</span>
        <span>1 / 3</span>
      </div>
      <div class="quota-track"><div class="quota-fill"></div></div>
      <div class="quota-note">2 slots remaining this month</div>
    </div>

    <div class="sb-footer">
      <div class="sb-logout">
        <i class="fa-solid fa-arrow-right-from-bracket" style="font-size:13px;width:32px;text-align:center;"></i>Logout
      </div>
    </div>
  </aside>

  <!-- ── Main ── -->
  <main class="main">

    <!-- Top bar -->
    <div class="topbar">
      <div>
        <div class="greeting-eyebrow" id="greeting-time">Good afternoon</div>
        <div class="greeting-name">Juan dela Cruz</div>
        <div class="greeting-date" id="today-date"></div>
      </div>
      <div class="topbar-right">
        <div class="toggle-row">
          <span class="tgl-label" id="tgl-lbl"><i class="fa-regular fa-sun" style="font-size:13px;"></i></span>
          <div class="tgl" id="tgl" onclick="toggleDark()"></div>
        </div>
        <div class="notif-btn">
          <i class="fa-regular fa-bell" style="font-size:13px;"></i>
          <div class="notif-dot"></div>
        </div>
        <button class="reserve-btn">
          <i class="fa-solid fa-plus" style="font-size:10px;"></i> Reserve
        </button>
      </div>
    </div>

    <!-- What to do next -->
    <div class="next-card" style="background:var(--blue-lt);border-color:var(--blue-mid);">
      <div class="next-icon" style="background:rgba(37,99,235,.13);color:var(--blue);">
        <i class="fa-solid fa-ticket" style="font-size:13px;"></i>
      </div>
      <div>
        <div class="next-eyebrow" style="color:var(--blue);">What to do next</div>
        <div class="next-msg">Your approved slot is coming up. Download your e-ticket from My Reservations and scan it at the entrance when you arrive.</div>
        <button class="next-btn" style="background:var(--blue);">
          Get E-Ticket <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i>
        </button>
      </div>
    </div>

    <!-- Upcoming reservation -->
    <div class="upcoming-pill">
      <div class="up-icon"><i class="fa-solid fa-ticket" style="font-size:13px;"></i></div>
      <div>
        <div class="up-eyebrow">Upcoming Reservation</div>
        <div class="up-name">Computer Lab &nbsp;·&nbsp; PC #4</div>
        <div class="up-time">Apr 5, 2026 &nbsp;·&nbsp; 2:00 PM – 4:00 PM</div>
      </div>
      <a class="up-link" href="#">View →</a>
    </div>

    <!-- Stat cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fa-solid fa-layer-group" style="font-size:13px;"></i></div>
          <span class="stat-lbl">Total</span>
        </div>
        <div class="stat-val">12</div>
        <div class="stat-hint">All time</div>
      </div>
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="fa-regular fa-clock" style="font-size:13px;"></i></div>
          <span class="stat-lbl">Pending</span>
        </div>
        <div class="stat-val" style="color:#d97706;">2</div>
        <div class="stat-hint">Awaiting review</div>
      </div>
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon" style="background:#dcfce7;color:#16a34a;"><i class="fa-solid fa-circle-check" style="font-size:13px;"></i></div>
          <span class="stat-lbl">Approved</span>
        </div>
        <div class="stat-val" style="color:#16a34a;">5</div>
        <div class="stat-hint">Ready to use</div>
      </div>
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="fa-solid fa-ban" style="font-size:13px;"></i></div>
          <span class="stat-lbl">Declined</span>
        </div>
        <div class="stat-val" style="color:#dc2626;">1</div>
        <div class="stat-hint">Not approved</div>
      </div>
    </div>

    <!-- Calendar + side panel -->
    <div class="grid-main">

      <!-- Calendar card -->
      <div class="card card-p">
        <div class="card-head">
          <div class="card-title-wrap">
            <div class="card-icon"><i class="fa-solid fa-calendar-days" style="font-size:12px;"></i></div>
            <div>
              <div class="card-title">Community Schedule</div>
              <div class="card-sub">Click any date to see reservations</div>
            </div>
          </div>
          <div class="cal-legend">
            <div style="display:flex;align-items:center;gap:5px;"><div class="leg-dot" style="background:#fbbf24;"></div><span class="leg-lbl">Pending</span></div>
            <div style="display:flex;align-items:center;gap:5px;"><div class="leg-dot" style="background:#10b981;"></div><span class="leg-lbl">Approved</span></div>
            <div style="display:flex;align-items:center;gap:5px;"><div class="leg-dot" style="background:#f87171;"></div><span class="leg-lbl">Declined</span></div>
            <div style="display:flex;align-items:center;gap:5px;"><div class="leg-dot" style="background:#a855f7;"></div><span class="leg-lbl">Claimed</span></div>
          </div>
        </div>
        <div class="cal-grid" id="mini-cal"></div>
      </div>

      <!-- Side panel -->
      <div class="side-col">

        <!-- Quick actions -->
        <div class="card card-p">
          <div class="section-eyebrow">Quick Actions</div>
          <div class="qa-list">
            <a class="qa-item" href="#">
              <div class="qa-icon" style="background:#eff6ff;color:#2563eb;"><i class="fa-solid fa-plus" style="font-size:11px;"></i></div>
              New Reservation
              <i class="fa-solid fa-chevron-right qa-chevron"></i>
            </a>
            <a class="qa-item" href="#">
              <div class="qa-icon" style="background:#eef2ff;color:#4f46e5;"><i class="fa-regular fa-calendar" style="font-size:11px;"></i></div>
              My Reservations
              <i class="fa-solid fa-chevron-right qa-chevron"></i>
            </a>
            <a class="qa-item" href="#">
              <div class="qa-icon" style="background:#fef3c7;color:#d97706;"><i class="fa-solid fa-book-open" style="font-size:11px;"></i></div>
              Browse Library
              <i class="fa-solid fa-chevron-right qa-chevron"></i>
            </a>
            <a class="qa-item" href="#">
              <div class="qa-icon" style="background:#f3e8ff;color:#9333ea;"><i class="fa-regular fa-user" style="font-size:11px;"></i></div>
              View Profile
              <i class="fa-solid fa-chevron-right qa-chevron"></i>
            </a>
          </div>
        </div>

        <!-- Recent bookings -->
        <div class="card card-p" style="flex:1;">
          <div class="card-head">
            <div class="section-eyebrow" style="margin-bottom:0;">Recent Bookings</div>
            <a class="link-sm" href="#">View all →</a>
          </div>
          <div>
            <a class="bk-row" href="#">
              <div class="bk-date"><div class="bk-month">Apr</div><div class="bk-day">5</div></div>
              <div style="flex:1;min-width:0;">
                <div class="bk-name">Computer Lab</div>
                <div class="bk-time">2:00 PM – 4:00 PM</div>
              </div>
              <span class="tag tag-approved">Approved</span>
            </a>
            <a class="bk-row" href="#">
              <div class="bk-date"><div class="bk-month">Apr</div><div class="bk-day">3</div></div>
              <div style="flex:1;min-width:0;">
                <div class="bk-name">Study Room A</div>
                <div class="bk-time">10:00 AM – 12:00 PM</div>
              </div>
              <span class="tag tag-pending">Pending</span>
            </a>
            <a class="bk-row" href="#">
              <div class="bk-date"><div class="bk-month">Mar</div><div class="bk-day">28</div></div>
              <div style="flex:1;min-width:0;">
                <div class="bk-name">Computer Lab</div>
                <div class="bk-time">3:00 PM – 5:00 PM</div>
              </div>
              <span class="tag tag-claimed">Claimed</span>
            </a>
            <a class="bk-row" href="#">
              <div class="bk-date"><div class="bk-month">Mar</div><div class="bk-day">20</div></div>
              <div style="flex:1;min-width:0;">
                <div class="bk-name">Multi-purpose Hall</div>
                <div class="bk-time">9:00 AM – 11:00 AM</div>
              </div>
              <span class="tag tag-unclaimed">No-show</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Library section -->
    <div class="grid-lib">

      <!-- Left: banner + AI search -->
      <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="lib-banner">
          <div class="lib-eyebrow">Community Library</div>
          <div class="lib-title">45 books available</div>
          <div class="lib-sub">62 total titles in the collection</div>
          <a class="lib-browse" href="#"><i class="fa-solid fa-book-open" style="font-size:11px;"></i> Browse All</a>
          <div class="lib-stats">
            <div class="lib-stat">
              <div class="lib-stat-icon"><i class="fa-solid fa-bookmark" style="font-size:11px;"></i></div>
              <div><div class="lib-stat-lbl">My Borrows</div><div class="lib-stat-val">3</div></div>
            </div>
            <div class="lib-stat">
              <div class="lib-stat-icon"><i class="fa-solid fa-hourglass-half" style="font-size:11px;color:#fcd34d;"></i></div>
              <div><div class="lib-stat-lbl">Pending</div><div class="lib-stat-val">1</div></div>
            </div>
            <div class="lib-stat">
              <div class="lib-stat-icon"><i class="fa-solid fa-circle-check" style="font-size:11px;color:#7dd3fc;"></i></div>
              <div><div class="lib-stat-lbl">Active</div><div class="lib-stat-val">2</div></div>
            </div>
          </div>
        </div>

        <!-- AI Book Finder -->
        <div class="card card-p">
          <div class="card-head" style="margin-bottom:0;">
            <div class="card-title-wrap">
              <div class="card-icon" style="background:#eef2ff;color:#4f46e5;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:12px;"></i></div>
              <div>
                <div class="card-title">AI Book Finder</div>
                <div class="card-sub">Describe what you want to read</div>
              </div>
            </div>
          </div>
          <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input class="search-input" type="text" placeholder="e.g. Filipino history, funny stories, adventure for kids…">
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <button class="find-btn">
              <i class="fa-solid fa-wand-magic-sparkles" style="font-size:10px;"></i> Find Books
            </button>
            <a class="link-sm" href="#">See full library →</a>
          </div>
        </div>
      </div>

      <!-- Right: available books + my borrows -->
      <div style="display:flex;flex-direction:column;gap:14px;">
        <div class="card card-p">
          <div class="card-head">
            <div class="section-eyebrow" style="margin-bottom:0;">Available Now</div>
            <a class="link-sm" href="#">All →</a>
          </div>
          <div>
            <div class="book-row">
              <div class="book-letter">N</div>
              <div style="flex:1;min-width:0;">
                <div class="book-title">Noli Me Tangere</div>
                <div class="book-author">Jose Rizal</div>
              </div>
              <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
                <div class="avail-dot on"></div>
                <div class="avail-num">3 left</div>
              </div>
            </div>
            <div class="book-row">
              <div class="book-letter">F</div>
              <div style="flex:1;min-width:0;">
                <div class="book-title">Florante at Laura</div>
                <div class="book-author">Francisco Balagtas</div>
              </div>
              <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
                <div class="avail-dot on"></div>
                <div class="avail-num">2 left</div>
              </div>
            </div>
            <div class="book-row">
              <div class="book-letter">E</div>
              <div style="flex:1;min-width:0;">
                <div class="book-title">El Filibusterismo</div>
                <div class="book-author">Jose Rizal</div>
              </div>
              <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
                <div class="avail-dot off"></div>
                <div class="avail-num">0 left</div>
              </div>
            </div>
            <div class="book-row">
              <div class="book-letter">I</div>
              <div style="flex:1;min-width:0;">
                <div class="book-title">Ibong Adarna</div>
                <div class="book-author">Anonymous</div>
              </div>
              <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
                <div class="avail-dot on"></div>
                <div class="avail-num">1 left</div>
              </div>
            </div>
            <div class="book-row">
              <div class="book-letter">B</div>
              <div style="flex:1;min-width:0;">
                <div class="book-title">Banaag at Sikat</div>
                <div class="book-author">Lope K. Santos</div>
              </div>
              <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
                <div class="avail-dot on"></div>
                <div class="avail-num">2 left</div>
              </div>
            </div>
          </div>
        </div>

        <!-- My Borrows -->
        <div class="card card-p">
          <div class="card-head">
            <div class="section-eyebrow" style="margin-bottom:0;">My Borrows</div>
            <a class="link-sm" href="#">All →</a>
          </div>
          <div>
            <div class="bk-row" style="cursor:default;">
              <div class="book-letter" style="width:34px;height:34px;font-size:12px;flex-shrink:0;">N</div>
              <div style="flex:1;min-width:0;">
                <div class="bk-name">Noli Me Tangere</div>
                <div class="bk-time">Due Apr 15</div>
              </div>
              <span class="tag tag-active">Active</span>
            </div>
            <div class="bk-row" style="cursor:default;">
              <div class="book-letter" style="width:34px;height:34px;font-size:12px;flex-shrink:0;">F</div>
              <div style="flex:1;min-width:0;">
                <div class="bk-name">Florante at Laura</div>
              </div>
              <span class="tag tag-pending">Pending</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

<script>
(function(){
  // Date & greeting
  const now=new Date();
  const h=now.getHours();
  document.getElementById('greeting-time').textContent=h<12?'Good morning':h<17?'Good afternoon':'Good evening';
  document.getElementById('today-date').textContent=now.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});

  // Mini calendar
  function buildCal(){
    const n=new Date();
    const yr=n.getFullYear(),mo=n.getMonth();
    const first=new Date(yr,mo,1).getDay();
    const days=new Date(yr,mo+1,0).getDate();
    const evDays=[3,5,8,12,15,18,22,28];
    const dw=['Su','Mo','Tu','We','Th','Fr','Sa'];
    let h='';
    dw.forEach(d=>h+=`<div class="cal-dh">${d}</div>`);
    for(let i=0;i<first;i++)h+=`<div class="cal-d other"></div>`;
    for(let d=1;d<=days;d++){
      const today=d===n.getDate();
      const ev=evDays.includes(d);
      h+=`<div class="cal-d${today?' today':''}${ev?' has-ev':''}">${d}</div>`;
    }
    document.getElementById('mini-cal').innerHTML=h;
  }
  buildCal();

  // Dark mode
  window.toggleDark=function(){
    const app=document.getElementById('app');
    const body=document.body;
    const lbl=document.getElementById('tgl-lbl');
    const on=app.hasAttribute('data-dark');
    if(on){
      app.removeAttribute('data-dark');
      body.removeAttribute('data-dark');
      lbl.innerHTML='<i class="fa-regular fa-sun" style="font-size:13px;"></i>';
    } else {
      app.setAttribute('data-dark','');
      body.setAttribute('data-dark','');
      lbl.innerHTML='<i class="fa-regular fa-moon" style="font-size:13px;"></i>';
    }
  };
})();
</script>
</body>
</html>