<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>eLibReserve — Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --p:#5448C8;--p2:#6C5FD8;--p-light:#ECEAFE;
  --bg:#F4F3FC;--card:#fff;
  --text:#1C1744;--muted:#8985A8;--faint:#C4C1DD;--border:#ECEAF4;
  --green:#22C997;--amber:#F5A623;--red:#F16063;
  --font:'Plus Jakarta Sans',sans-serif;--display:'Sora',sans-serif;
  --shadow:0 2px 16px rgba(84,72,200,.07);
  --shadow-md:0 6px 28px rgba(84,72,200,.13);
  --sidebar-w:228px;
}
html,body{height:100%;}
body{font-family:var(--font);background:var(--bg);color:var(--text);display:flex;flex-direction:column;min-height:100%;}

/* ── OVERLAY ── */
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(28,23,68,.45);z-index:199;opacity:0;transition:opacity .25s;}
.sidebar-overlay.open{opacity:1;}

/* ── LAYOUT ── */
.layout{display:flex;flex:1;height:100vh;overflow:hidden;}

/* ── SIDEBAR ── */
.sidebar{
  width:var(--sidebar-w);flex-shrink:0;background:var(--p);
  display:flex;flex-direction:column;position:relative;overflow:hidden;
  transition:transform .3s cubic-bezier(.16,1,.3,1);
  z-index:200;
}
.sidebar::before{content:'';position:absolute;top:-70px;right:-55px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;pointer-events:none;}
.sidebar::after{content:'';position:absolute;bottom:-50px;left:-40px;width:140px;height:140px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none;}

.logo-wrap{padding:26px 22px 22px;border-bottom:1px solid rgba(255,255,255,.1);}
.logo{display:flex;align-items:center;gap:11px;}
.logo-mark{width:38px;height:38px;border-radius:11px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.logo-mark svg{width:20px;height:20px;color:#fff;}
.logo-name{font-family:var(--display);font-size:17px;font-weight:800;color:#fff;letter-spacing:-.3px;line-height:1.1;}
.logo-name span{color:rgba(255,255,255,.5);font-weight:600;}

.nav-body{flex:1;padding:18px 14px;display:flex;flex-direction:column;gap:3px;overflow-y:auto;}
.nav-body::-webkit-scrollbar{display:none;}
.nav-label{font-size:9.5px;font-weight:700;color:rgba(255,255,255,.3);letter-spacing:.18em;text-transform:uppercase;padding:0 8px;margin:10px 0 5px;}
.nav-item{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:12px;color:rgba(255,255,255,.6);font-size:13.5px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .18s;border:1.5px solid transparent;}
.nav-item:hover{background:rgba(255,255,255,.1);color:#fff;}
.nav-item.active{background:rgba(255,255,255,.16);color:#fff;border-color:rgba(255,255,255,.12);box-shadow:0 2px 12px rgba(0,0,0,.15);}
.nav-item svg{width:17px;height:17px;flex-shrink:0;}
.nav-badge{margin-left:auto;background:#F5A623;color:#fff;font-size:9.5px;font-weight:800;padding:2px 7px;border-radius:999px;}

/* ── PRIMARY NAV SECTION (hidden on mobile — bottom nav handles it) ── */
.nav-primary{display:contents;}

.sidebar-footer{padding:14px 14px 22px;border-top:1px solid rgba(255,255,255,.1);}
.user-chip{display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.1);border-radius:13px;padding:10px 12px;cursor:pointer;transition:background .18s;}
.user-chip:hover{background:rgba(255,255,255,.16);}
.user-av{width:34px;height:34px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-family:var(--display);font-weight:800;font-size:12px;color:#fff;background:linear-gradient(135deg,#F472B6,#A78BFA);}
.user-nm{font-size:12.5px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;}
.user-rl{font-size:10px;color:rgba(255,255,255,.45);font-weight:500;}
.user-more{margin-left:auto;color:rgba(255,255,255,.35);flex-shrink:0;}
.user-more svg{width:14px;height:14px;}

/* ── MAIN ── */
.main{flex:1;overflow-y:auto;display:flex;flex-direction:column;min-width:0;}
.main::-webkit-scrollbar{width:5px;}
.main::-webkit-scrollbar-thumb{background:var(--faint);border-radius:999px;}

/* ── TOPBAR ── */
.topbar{display:flex;align-items:flex-start;justify-content:space-between;padding:22px 24px 0;gap:12px;flex-shrink:0;flex-wrap:wrap;}
.topbar-left{display:flex;align-items:flex-start;gap:12px;}
.hamburger{display:none;width:38px;height:38px;background:var(--card);border:1.5px solid var(--border);border-radius:11px;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;margin-top:2px;}
.hamburger svg{width:18px;height:18px;color:var(--muted);}
.page-title{font-family:var(--display);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.5px;}
.filter-row{display:flex;align-items:center;gap:6px;margin-top:10px;flex-wrap:wrap;}
.filter-label{font-size:12px;font-weight:600;color:var(--muted);}
.filter-pill{display:flex;align-items:center;gap:5px;padding:7px 12px;background:var(--card);border:1.5px solid var(--border);border-radius:10px;font-size:12px;font-weight:600;color:var(--muted);cursor:pointer;transition:all .15s;user-select:none;}
.filter-pill:hover{border-color:var(--p);color:var(--p);}
.filter-pill svg{width:12px;height:12px;}

.topbar-right{display:flex;align-items:center;gap:8px;flex-shrink:0;}
.icon-btn{width:38px;height:38px;background:var(--card);border:1.5px solid var(--border);border-radius:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .15s;position:relative;flex-shrink:0;}
.icon-btn:hover{border-color:var(--p);}
.icon-btn svg{width:17px;height:17px;color:var(--muted);}
.badge-dot{position:absolute;top:6px;right:7px;width:7px;height:7px;background:#F16063;border-radius:50%;border:1.5px solid var(--bg);}
.profile-pill{display:flex;align-items:center;gap:9px;background:var(--card);border:1.5px solid var(--border);border-radius:11px;padding:5px 13px 5px 5px;cursor:pointer;transition:border-color .15s;}
.profile-pill:hover{border-color:var(--p);}
.profile-av{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#F472B6,#A78BFA);display:flex;align-items:center;justify-content:center;font-family:var(--display);font-weight:800;font-size:10px;color:#fff;flex-shrink:0;}
.profile-name{font-size:12px;font-weight:700;color:var(--text);line-height:1.2;}
.profile-id{font-size:10px;color:var(--muted);}

/* ── CONTENT ── */
.content{display:grid;grid-template-columns:1fr 284px;gap:20px;padding:18px 24px 32px;align-items:start;}
.left-col{display:flex;flex-direction:column;gap:16px;min-width:0;}
.right-col{display:flex;flex-direction:column;gap:14px;min-width:0;}

/* ── STATS ── */
.stats-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;}
.stat-card{background:var(--card);border:1.5px solid var(--border);border-radius:16px;padding:16px 16px 14px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:10px;transition:transform .18s,box-shadow .18s;}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md);}
.stat-top{display:flex;align-items:flex-start;justify-content:space-between;}
.stat-icon{width:36px;height:36px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-icon svg{width:17px;height:17px;}
.stat-lbl{font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-top:2px;}
.stat-num{font-family:var(--display);font-size:28px;font-weight:800;color:var(--text);letter-spacing:-.05em;line-height:1;}
.stat-hint{font-size:11px;color:var(--faint);font-weight:500;}

/* ── SECTION HEAD ── */
.sec-head{display:flex;align-items:center;justify-content:space-between;}
.sec-title{font-family:var(--display);font-size:15px;font-weight:800;color:var(--text);letter-spacing:-.2px;}
.sec-link{font-size:12px;font-weight:700;color:var(--p);text-decoration:none;cursor:pointer;transition:opacity .15s;}
.sec-link:hover{opacity:.65;}

/* ── QA BAR ── */
.qa-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;}
.qa-tile{background:var(--card);border:1.5px solid var(--border);border-radius:14px;padding:14px 8px;display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;text-decoration:none;color:inherit;transition:all .18s;}
.qa-tile:hover{border-color:var(--p);transform:translateY(-2px);box-shadow:var(--shadow-md);}
.qa-ico{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;}
.qa-ico svg{width:18px;height:18px;}
.qa-lbl{font-size:11px;font-weight:700;color:var(--text);text-align:center;line-height:1.3;}

/* ── RES CARDS ── */
.res-list{display:flex;flex-direction:column;gap:10px;}
.res-card{background:var(--card);border:1.5px solid var(--border);border-radius:18px;padding:16px 16px 16px 20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow);transition:all .2s;cursor:pointer;position:relative;overflow:hidden;}
.res-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:4px 0 0 4px;background:var(--stripe-color,var(--p));}
.res-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md);border-color:rgba(84,72,200,.18);}
.res-icon-wrap{width:50px;height:50px;border-radius:14px;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
.res-icon-wrap svg{width:22px;height:22px;}
.res-body{flex:1;min-width:0;}
.res-name{font-family:var(--display);font-size:14px;font-weight:800;color:var(--text);letter-spacing:-.1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.res-desc{font-size:11.5px;color:var(--muted);margin-top:3px;font-weight:500;}
.res-meta{font-size:11px;color:var(--muted);margin-top:6px;display:flex;align-items:center;gap:7px;flex-wrap:wrap;}
.res-go{width:32px;height:32px;background:var(--p);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .18s;box-shadow:0 4px 12px rgba(84,72,200,.3);}
.res-card:hover .res-go{background:var(--p2);transform:scale(1.08);}
.res-go svg{width:12px;height:12px;color:#fff;}

/* ── TAGS ── */
.tag{display:inline-flex;align-items:center;padding:2px 8px;border-radius:999px;font-size:10px;font-weight:700;white-space:nowrap;}
.tag-pending{background:#FEF3C7;color:#92400E;}
.tag-approved{background:#D1FAE5;color:#065F46;}
.tag-claimed{background:#EDE9FE;color:#5B21B6;}
.tag-declined{background:#FEE2E2;color:#991B1B;}
.tag-unclaimed{background:#FFF7ED;color:#9A3412;}
.tag-expired{background:#F1F5F9;color:#475569;}

/* ── CALENDAR ── */
.cal-card{background:var(--card);border:1.5px solid var(--border);border-radius:20px;padding:20px;box-shadow:var(--shadow);}
.cal-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.cal-month{font-family:var(--display);font-size:15px;font-weight:800;color:var(--text);}
.cal-navs{display:flex;gap:4px;}
.cal-nav{width:28px;height:28px;border-radius:8px;background:var(--bg);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--muted);transition:all .15s;}
.cal-nav:hover{background:var(--p);color:#fff;}
.cal-nav svg{width:13px;height:13px;}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:2px;}
.cal-dow{font-size:9px;font-weight:700;color:var(--faint);text-align:center;padding:4px 0;}
.cal-day{aspect-ratio:1;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:var(--muted);cursor:pointer;transition:all .15s;position:relative;}
.cal-day:hover:not(.empty){background:var(--p-light);color:var(--p);}
.cal-day.today{background:var(--p);color:#fff;font-weight:800;box-shadow:0 3px 10px rgba(84,72,200,.35);}
.cal-day.has-ev::after{content:'';position:absolute;bottom:2px;left:50%;transform:translateX(-50%);width:3px;height:3px;background:var(--amber);border-radius:50%;}
.cal-day.today.has-ev::after{background:rgba(255,255,255,.8);}
.cal-day.empty{cursor:default;}

/* ── USERS ── */
.users-card{background:var(--card);border:1.5px solid var(--border);border-radius:20px;padding:18px 20px;box-shadow:var(--shadow);}
.user-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);}
.user-row:last-child{border-bottom:none;}
.member-av{width:36px;height:36px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-family:var(--display);font-weight:800;font-size:11px;color:#fff;}
.member-name{font-size:12px;font-weight:700;color:var(--text);}
.member-id{font-size:10px;color:var(--faint);}
.online-dot{width:8px;height:8px;background:var(--green);border-radius:50%;margin-left:auto;flex-shrink:0;box-shadow:0 0 0 3px rgba(34,201,151,.15);}

/* ── MOBILE BOTTOM NAV — removed, sidebar is the sole nav ── */

/* ════════ RESPONSIVE ════════ */

@media(max-width:1080px){
  .content{grid-template-columns:1fr 250px;gap:16px;}
  .stats-strip{grid-template-columns:repeat(2,1fr);}
}

/* Tablet portrait: sidebar becomes drawer */
@media(max-width:860px){
  .sidebar{
    position:fixed;top:0;left:0;bottom:0;
    transform:translateX(-100%);
    box-shadow:4px 0 24px rgba(28,23,68,.2);
  }
  .sidebar.open{transform:translateX(0);}
  .sidebar-overlay{display:block;}

  /* Restore full primary nav in drawer */
  .nav-primary{display:contents;}

  .layout{display:block;}
  .main{height:100vh;overflow-y:auto;}

  .hamburger{display:flex;}

  .topbar{padding:16px 16px 0;}

  .content{grid-template-columns:1fr;padding:14px 16px 32px;}
  .right-col{display:grid;grid-template-columns:1fr 1fr;gap:14px;}

  .stats-strip{grid-template-columns:repeat(2,1fr);}
}

@media(max-width:600px){
  .topbar{padding:12px 14px 0;gap:8px;flex-wrap:nowrap;align-items:center;}
  .topbar-left{gap:8px;}
  .page-title{font-size:17px;}
  .filter-row{display:none;}

  .topbar-right{gap:6px;}
  .profile-id,.profile-name:not(:first-child){display:none;}

  .content{padding:12px 12px 32px;gap:12px;}

  .stats-strip{gap:8px;}
  .stat-card{padding:13px 13px 11px;gap:8px;}
  .stat-num{font-size:24px;}
  .stat-hint{display:none;}

  .qa-bar{gap:7px;}
  .qa-tile{padding:11px 6px;gap:6px;border-radius:12px;}
  .qa-ico{width:32px;height:32px;border-radius:9px;}
  .qa-ico svg{width:14px;height:14px;}
  .qa-lbl{font-size:9.5px;}

  .res-card{padding:13px 12px 13px 16px;gap:10px;border-radius:14px;}
  .res-icon-wrap{width:42px;height:42px;border-radius:11px;}
  .res-icon-wrap svg{width:18px;height:18px;}
  .res-name{font-size:13px;}
  .res-desc{font-size:11px;margin-top:2px;}
  .res-go{width:28px;height:28px;}
  .res-go svg{width:10px;height:10px;}

  .right-col{grid-template-columns:1fr;gap:12px;}
  .sec-title{font-size:13.5px;}
}

@media(max-width:400px){
  .topbar-right .icon-btn:first-child{display:none;}
  .content{padding:10px 10px 24px;}
  .stats-strip{gap:7px;}
  .qa-bar{grid-template-columns:repeat(2,1fr);gap:7px;}
  .qa-tile{flex-direction:row;justify-content:flex-start;padding:12px 12px;border-radius:12px;}
  .qa-lbl{text-align:left;font-size:11.5px;}
  .qa-ico{flex-shrink:0;}
  .stat-card{padding:12px 11px 10px;}
  .stat-num{font-size:22px;}
}


</style>
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="layout">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="logo-wrap">
    <div class="logo">
      <div class="logo-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
          <path d="M9 10h6M9 13h4"/>
        </svg>
      </div>
      <div class="logo-name">eLib<span>Reserve</span></div>
    </div>
  </div>

  <nav class="nav-body">
    <!-- PRIMARY NAV — visible on desktop, hidden on mobile (bottom nav handles it) -->
    <div class="nav-primary">
      <div class="nav-label">Main Menu</div>
      <a class="nav-item active" href="#" onclick="closeSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Dashboard
      </a>
      <a class="nav-item" href="#" onclick="closeSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
        Library
      </a>
      <a class="nav-item" href="#" onclick="closeSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        Members
      </a>
      <a class="nav-item" href="#" onclick="closeSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        Messages
      </a>
      <a class="nav-item" href="#" onclick="closeSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Schedule
        <span class="nav-badge">2</span>
      </a>
    </div>

    <!-- SECONDARY NAV — always visible (desktop + mobile drawer) -->
    <div class="nav-label" style="margin-top:10px;">Account</div>
    <a class="nav-item" href="#" onclick="closeSidebar()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/></svg>
      Settings
    </a>
    <a class="nav-item" href="#" onclick="closeSidebar()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="6" height="18" rx="1"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
      Directory
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-chip">
      <div class="user-av" id="sideAv">CE</div>
      <div style="flex:1;min-width:0;">
        <div class="user-nm" id="sideNm">Christine Eva</div>
        <div class="user-rl">SK Member</div>
      </div>
      <div class="user-more">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
      </div>
    </div>
  </div>
</aside>

<!-- MAIN -->
<main class="main">

  <header class="topbar">
    <div class="topbar-left">
      <div class="hamburger" onclick="openSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </div>
      <div>
        <div class="page-title">My Reservations</div>
        <div class="filter-row">
          <span class="filter-label">Filter by:</span>
          <div class="filter-pill">Time <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></div>
          <div class="filter-pill">Status <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></div>
          <div class="filter-pill">Type <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg></div>
        </div>
      </div>
    </div>
    <div class="topbar-right">
      <div class="icon-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>
      <div class="icon-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        <div class="badge-dot"></div>
      </div>
      <div class="profile-pill">
        <div class="profile-av" id="topAv">CE</div>
        <div>
          <div class="profile-name" id="topNm">Christine Eva</div>
          <div class="profile-id" id="topId">1094881999</div>
        </div>
      </div>
    </div>
  </header>

  <div class="content">
    <!-- LEFT -->
    <div class="left-col">

      <div class="stats-strip">
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon" style="background:#ECEAFE;"><svg viewBox="0 0 24 24" fill="none" stroke="#5448C8" stroke-width="2" stroke-linecap="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg></div>
            <div class="stat-lbl">Total</div>
          </div>
          <div class="stat-num">12</div>
          <div class="stat-hint">All reservations</div>
        </div>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon" style="background:#FEF3C7;"><svg viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div class="stat-lbl">Pending</div>
          </div>
          <div class="stat-num" style="color:#D97706;">3</div>
          <div class="stat-hint">Awaiting review</div>
        </div>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon" style="background:#D1FAE5;"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
            <div class="stat-lbl">Approved</div>
          </div>
          <div class="stat-num" style="color:#059669;">7</div>
          <div class="stat-hint">Ready to use</div>
        </div>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon" style="background:#EDE9FE;"><svg viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2" stroke-linecap="round"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg></div>
            <div class="stat-lbl">Claimed</div>
          </div>
          <div class="stat-num" style="color:#7C3AED;">2</div>
          <div class="stat-hint">Tickets used</div>
        </div>
      </div>

      <div class="qa-bar">
        <a class="qa-tile" href="#">
          <div class="qa-ico" style="background:#ECEAFE;"><svg viewBox="0 0 24 24" fill="none" stroke="#5448C8" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></div>
          <div class="qa-lbl">New Reservation</div>
        </a>
        <a class="qa-tile" href="#">
          <div class="qa-ico" style="background:#FEF3C7;"><svg viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
          <div class="qa-lbl">My Bookings</div>
        </a>
        <a class="qa-tile" href="#">
          <div class="qa-ico" style="background:#D1FAE5;"><svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg></div>
          <div class="qa-lbl">Browse Library</div>
        </a>
        <a class="qa-tile" href="#">
          <div class="qa-ico" style="background:#FCE7F3;"><svg viewBox="0 0 24 24" fill="none" stroke="#BE185D" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
          <div class="qa-lbl">View Profile</div>
        </a>
      </div>

      <div>
        <div class="sec-head" style="margin-bottom:12px;">
          <div class="sec-title">Recent Reservations</div>
          <a class="sec-link" href="#">See all →</a>
        </div>
        <div class="res-list" id="resList"></div>
      </div>

    </div>

    <!-- RIGHT -->
    <div class="right-col">
      <div class="cal-card">
        <div class="cal-hd">
          <div class="cal-month" id="calTitle">May 2026</div>
          <div class="cal-navs">
            <button class="cal-nav" onclick="calMove(-1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg></button>
            <button class="cal-nav" onclick="calMove(1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
        </div>
        <div class="cal-grid" id="calGrid"></div>
      </div>
      <div class="users-card">
        <div class="sec-head" style="margin-bottom:10px;">
          <div class="sec-title">Online Users</div>
          <a class="sec-link" href="#">See all</a>
        </div>
        <div id="usersList"></div>
      </div>
    </div>
  </div>

</main>
</div>



<script>
const USER={name:'Christine Eva',id:'1094881999'};
const MEMBERS=[
  {name:'Maren Maureen',id:'1094882001',bg:'linear-gradient(135deg,#F472B6,#FB7185)'},
  {name:'Jenniffer Jane',id:'1094872000',bg:'linear-gradient(135deg,#60A5FA,#818CF8)'},
  {name:'Ryan Herwinds',id:'1094342003',bg:'linear-gradient(135deg,#34D399,#10B981)'},
  {name:'Kierra Culhane',id:'1094662002',bg:'linear-gradient(135deg,#FBBF24,#F59E0B)'},
];
const RES=[
  {name:'Computer Lab A',sub:'PC-04 · Computer Station',start:'09:00',end:'11:00',status:'approved',stripe:'#22C997',iconBg:'#ECEAFE',iconStroke:'#5448C8',icon:'<path d="M20 16V7a2 2 0 00-2-2H6a2 2 0 00-2 2v9m16 0H4m16 0l1.28 2.55a1 1 0 01-.9 1.45H3.62a1 1 0 01-.9-1.45L4 16"/>'},
  {name:'Study Room 2',sub:'4-person room · Floor 2',start:'13:00',end:'15:00',status:'pending',stripe:'#F5A623',iconBg:'#FEF3C7',iconStroke:'#D97706',icon:'<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'},
  {name:'SK Library Corner',sub:'Reading & Research Zone',start:'10:00',end:'12:00',status:'claimed',stripe:'#818CF8',iconBg:'#EDE9FE',iconStroke:'#7C3AED',icon:'<path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>'},
  {name:'Meeting Room 1',sub:'Projector · Whiteboard',start:'14:00',end:'16:00',status:'declined',stripe:'#F16063',iconBg:'#FEE2E2',iconStroke:'#DC2626',icon:'<rect x="2" y="7" width="20" height="15" rx="2"/><polyline points="17 2 12 7 7 2"/>'},
];

const fmt=t=>{const[h,m]=t.split(':');const n=+h;return`${n%12||12}:${m} ${n<12?'AM':'PM'}`;};
const initials=n=>n.trim().split(/\s+/).slice(0,2).map(w=>w[0].toUpperCase()).join('');

function openSidebar(){
  document.getElementById('sidebar').classList.add('open');
  const ov=document.getElementById('overlay');
  ov.style.display='block';
  requestAnimationFrame(()=>ov.classList.add('open'));
  document.body.style.overflow='hidden';
}
function closeSidebar(){
  document.getElementById('sidebar').classList.remove('open');
  const ov=document.getElementById('overlay');
  ov.classList.remove('open');
  setTimeout(()=>{ov.style.display='none';},260);
  document.body.style.overflow='';
}

function setUser(){
  const av=initials(USER.name);
  ['sideAv','topAv'].forEach(id=>document.getElementById(id).textContent=av);
  document.getElementById('sideNm').textContent=USER.name;
  document.getElementById('topNm').textContent=USER.name;
  document.getElementById('topId').textContent=USER.id;
}

function renderRes(){
  const el=document.getElementById('resList');
  RES.forEach(r=>{
    const c=document.createElement('div');
    c.className='res-card';
    c.style.setProperty('--stripe-color',r.stripe);
    c.innerHTML=`<div class="res-icon-wrap" style="background:${r.iconBg};"><svg viewBox="0 0 24 24" fill="none" stroke="${r.iconStroke}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">${r.icon}</svg></div><div class="res-body"><div class="res-name">${r.name}</div><div class="res-desc">${r.sub}</div><div class="res-meta"><span>${fmt(r.start)} – ${fmt(r.end)}</span><span class="tag tag-${r.status}">${r.status.charAt(0).toUpperCase()+r.status.slice(1)}</span></div></div><div class="res-go"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></div>`;
    el.appendChild(c);
  });
}

function renderUsers(){
  const el=document.getElementById('usersList');
  MEMBERS.forEach(m=>{
    const r=document.createElement('div');r.className='user-row';
    r.innerHTML=`<div class="member-av" style="background:${m.bg};">${initials(m.name)}</div><div style="min-width:0;"><div class="member-name">${m.name}</div><div class="member-id">${m.id}</div></div><div class="online-dot"></div>`;
    el.appendChild(r);
  });
}

let calCur=new Date(2026,4,1);
const MONTHS='January February March April May June July August September October November December'.split(' ');
const EV=[1,3,7,14,15,22,28];
function renderCal(){
  document.getElementById('calTitle').textContent=`${MONTHS[calCur.getMonth()]} ${calCur.getFullYear()}`;
  const grid=document.getElementById('calGrid');
  grid.innerHTML='';
  'M T W T F S S'.split(' ').forEach(d=>{const e=document.createElement('div');e.className='cal-dow';e.textContent=d;grid.appendChild(e);});
  let start=new Date(calCur.getFullYear(),calCur.getMonth(),1).getDay();
  start=(start+6)%7;
  const days=new Date(calCur.getFullYear(),calCur.getMonth()+1,0).getDate();
  const now=new Date();
  for(let i=0;i<start;i++){const e=document.createElement('div');e.className='cal-day empty';grid.appendChild(e);}
  for(let d=1;d<=days;d++){
    const e=document.createElement('div');e.className='cal-day';e.textContent=d;
    if(d===now.getDate()&&calCur.getMonth()===now.getMonth()&&calCur.getFullYear()===now.getFullYear())e.classList.add('today');
    if(EV.includes(d))e.classList.add('has-ev');
    grid.appendChild(e);
  }
}
function calMove(dir){calCur=new Date(calCur.getFullYear(),calCur.getMonth()+dir,1);renderCal();}

setUser();renderRes();renderUsers();renderCal();
</script>
</body>
</html>