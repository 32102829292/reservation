<?php
/* ─────────────────────────────────────────────
   DATA + STATUS PROCESSING
───────────────────────────────────────────── */
$unclaimedCount = 0;
$claimedCount   = 0;
$processedRecent = [];
foreach (($reservations ?? []) as $r) {
    $isCl = in_array($r['claimed'] ?? false, [true, 1, 't', 'true', '1'], true)
        || strtolower($r['status'] ?? '') === 'claimed'
        || !empty($r['claimed_at']);
    $s = $isCl ? 'claimed' : strtolower($r['status'] ?? 'pending');
    if ($s === 'approved') {
        $edt = strtotime(($r['reservation_date'] ?? '') . ' ' . ($r['end_time'] ?? '23:59:59'));
        if ($edt && $edt < time()) $s = 'unclaimed';
    } elseif ($s === 'pending') {
        $rdt = strtotime($r['reservation_date'] ?? '');
        if ($rdt && $rdt < strtotime('today')) $s = 'expired';
    }
    if ($s === 'unclaimed') $unclaimedCount++;
    if ($s === 'claimed')   $claimedCount++;
    $r['_status'] = $s;
    $processedRecent[] = $r;
}

$remaining      = $remainingReservations ?? 3;
$maxSlots       = 3;
$featuredBooks  = $featuredBooks  ?? [];
$myBorrowings   = $myBorrowings   ?? [];
$availableCount = $availableCount ?? 0;
$totalBooks     = $totalBooks     ?? 0;

$upcoming = null;
if (!empty($reservations)) {
    $now = time();
    foreach ($reservations as $r) {
        if (($r['status'] ?? '') === 'approved' && empty($r['claimed'])) {
            $dt = strtotime($r['reservation_date'] . ' ' . ($r['end_time'] ?? '23:59'));
            if ($dt > $now) { $upcoming = $r; break; }
        }
    }
}

$nextAction = null;
if ($pending > 0)
    $nextAction = ['type'=>'pending','msg'=>"You have {$pending} reservation".($pending>1?'s':'')." awaiting approval. SK officers usually respond within 24 hours.",'cta'=>'View Reservations','url'=>'/reservation-list','color'=>'amber'];
elseif ($upcoming)
    $nextAction = ['type'=>'upcoming','msg'=>"Your approved slot is coming up. Download your e-ticket from My Reservations and scan it at the entrance when you arrive.",'cta'=>'Get E-Ticket','url'=>'/reservation-list','color'=>'blue'];
elseif ($unclaimedCount > 0)
    $nextAction = ['type'=>'unclaimed','msg'=>"You missed {$unclaimedCount} approved slot".($unclaimedCount>1?'s':'')." without showing up. Please cancel in advance if you can't attend.",'cta'=>'See Details','url'=>'/reservation-list','color'=>'orange'];
elseif ($remaining === 0)
    $nextAction = ['type'=>'quota','msg'=>"You've used all 3 reservation slots for this month. Your quota resets on the 1st of next month.",'cta'=>'Browse Library','url'=>'/books','color'=>'slate'];
elseif (empty($reservations))
    $nextAction = ['type'=>'empty','msg'=>"Welcome! You haven't made any reservations yet. Book a computer, study space, or other facility anytime.",'cta'=>'Make First Reservation','url'=>'/reservation','color'=>'blue'];

$nextColors = [
    'amber'  => ['bg'=>'rgba(251,191,36,.08)', 'border'=>'rgba(251,191,36,.25)', 'icon_bg'=>'rgba(251,191,36,.15)', 'icon_fg'=>'#d97706','btn_bg'=>'#d97706','icon'=>'clock'],
    'blue'   => ['bg'=>'rgba(84,72,200,.06)',  'border'=>'rgba(84,72,200,.2)',   'icon_bg'=>'rgba(84,72,200,.12)', 'icon_fg'=>'#5448C8','btn_bg'=>'#5448C8','icon'=>'ticket'],
    'orange' => ['bg'=>'rgba(234,88,12,.06)',  'border'=>'rgba(234,88,12,.2)',   'icon_bg'=>'rgba(234,88,12,.1)',  'icon_fg'=>'#ea580c','btn_bg'=>'#ea580c','icon'=>'triangle'],
    'slate'  => ['bg'=>'rgba(100,116,139,.05)','border'=>'rgba(100,116,139,.15)','icon_bg'=>'rgba(100,116,139,.1)','icon_fg'=>'#64748b','btn_bg'=>'#64748b','icon'=>'calendar-x'],
];

/* ── Sidebar initials ── */
$nameWords = array_slice(explode(' ', trim($user_name ?? 'U')), 0, 2);
$initials  = implode('', array_map(fn($w) => strtoupper($w[0] ?? '?'), $nameWords));
$hour      = (int)date('H');
$greeting  = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

/* ── Icon renderer ── */
function icon(string $name, int $size = 16, string $stroke = 'currentColor', string $extra = ''): string {
    static $icons = [
        'plus'         => ['<path d="M12 5v14M5 12h14" stroke-linecap="round"/>','1.8'],
        'calendar'     => ['<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>','1.5'],
        'calendar-days'=> ['<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>','1.5'],
        'calendar-x'   => ['<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="10" y1="14" x2="14" y2="18"/><line x1="14" y1="14" x2="10" y2="18"/>','1.5'],
        'book-open'    => ['<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round"/>','1.5'],
        'user'         => ['<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round"/>','1.8'],
        'users'        => ['<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>','1.8'],
        'logout'       => ['<path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round"/>','1.8'],
        'clock'        => ['<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>','1.8'],
        'check-circle' => ['<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-linecap="round"/><polyline points="22 4 12 14.01 9 11.01"/>','1.8'],
        'check-double' => ['<path d="M17 1l-8.5 8.5L6 7M22 6l-8.5 8.5" stroke-linecap="round"/><path d="M7 13l-4 4 1.5 1.5" stroke-linecap="round"/>','1.8'],
        'ticket'       => ['<path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round"/>','1.8'],
        'triangle'     => ['<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke-linecap="round"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>','1.8'],
        'bell'         => ['<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>','1.8'],
        'check'        => ['<polyline points="20 6 9 17 4 12"/>','1.8'],
        'x'            => ['<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>','1.8'],
        'arrow-right'  => ['<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>','1.8'],
        'chevron-right'=> ['<polyline points="9 18 15 12 9 6"/>','1.8'],
        'ban'          => ['<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>','1.8'],
        'hourglass'    => ['<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round"/>','1.8'],
        'layers'       => ['<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>','1.8'],
        'list-check'   => ['<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke-linecap="round"/>','1.8'],
        'sparkles'     => ['<path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" stroke-linecap="round"/>','1.8'],
        'search'       => ['<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>','1.8'],
        'bookmark'     => ['<path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/>','1.5'],
        'robot'        => ['<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/><circle cx="12" cy="5" r="1"/>','1.5'],
        'info'         => ['<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>','1.8'],
        'moon'         => ['<path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>','1.8'],
        'sun'          => ['<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>','1.5'],
        'grid'         => ['<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>','1.8'],
        'message'      => ['<path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>','1.8'],
        'settings'     => ['<circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/>','1.8'],
        'dots-v'       => ['<circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>','2.5'],
        'trending-up'  => ['<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>','1.8'],
        'bar-chart'    => ['<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>','1.5'],
    ];
    [$d, $sw] = $icons[$name] ?? ['<circle cx="12" cy="12" r="10"/>','1.8'];
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="%s" stroke-width="%s" style="width:%dpx;height:%dpx;flex-shrink:0;" %s>%s</svg>',
        $size,$size,htmlspecialchars($stroke,ENT_QUOTES),$sw,$size,$size,$extra,$d
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"/>
<title>Dashboard | <?= esc($user_name) ?></title>
<link rel="manifest" href="/manifest.json">
<?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
<meta name="theme-color" content="#5448C8">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Sora:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
(function(){try{if(localStorage.getItem('theme')==='dark')document.documentElement.classList.add('dark');}catch(e){}})();
</script>
<style>
/* ════ TOKENS ════ */
:root{
  --p:#5448C8;--p2:#6C5FD8;--p-light:#ECEAFE;--p-border:rgba(84,72,200,.18);
  --bg:#F4F3FC;--card:#fff;
  --text:#1C1744;--muted:#8985A8;--faint:#C4C1DD;--border:#ECEAF4;
  --green:#22C997;--amber:#F5A623;--red:#F16063;
  --font:'Plus Jakarta Sans',sans-serif;
  --display:'Sora',sans-serif;
  --mono:'JetBrains Mono',monospace;
  --shadow:0 2px 16px rgba(84,72,200,.07);
  --shadow-md:0 6px 28px rgba(84,72,200,.13);
  --shadow-lg:0 12px 40px rgba(84,72,200,.16);
  --sidebar-w:228px;
  --r-sm:10px;--r-md:14px;--r-lg:18px;--r-xl:22px;
  --ease:.18s ease;
  --indigo:#5448C8;--indigo-light:#ECEAFE;--indigo-border:rgba(84,72,200,.18);
  --input-bg:#F4F3FC;--border-subtle:#ECEAF4;
  --text-sub:#8985A8;--text-muted:#8985A8;--text-faint:#C4C1DD;
}
html.dark{
  --bg:#0b1220;--card:#111c30;--border:#1a2d48;--border-subtle:#1a2d48;
  --text:#e8eaf6;--muted:#7b82a8;--faint:#2e3a55;--input-bg:#0f1a2e;
  --text-sub:#7b82a8;--text-muted:#7b82a8;--text-faint:#2e3a55;
  --p-light:rgba(84,72,200,.15);--p-border:rgba(84,72,200,.25);
  --indigo-light:rgba(84,72,200,.15);--indigo-border:rgba(84,72,200,.25);
  --shadow:0 2px 16px rgba(0,0,0,.3);--shadow-md:0 6px 28px rgba(0,0,0,.4);--shadow-lg:0 12px 40px rgba(0,0,0,.5);
}

/* ════ RESET ════ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;font-family:var(--font);background:var(--bg);color:var(--text);}
body{display:flex;flex-direction:column;}
a{text-decoration:none;color:inherit;}

/* ════ LAYOUT ════ */
.l-shell{display:flex;height:100dvh;overflow:hidden;}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(28,23,68,.45);z-index:199;opacity:0;transition:opacity .25s;}
.sidebar-overlay.open{opacity:1;}

/* ════ SIDEBAR ════ */
.sidebar{
  width:var(--sidebar-w);flex-shrink:0;background:var(--p);
  display:flex;flex-direction:column;position:relative;overflow:hidden;
  transition:transform .3s cubic-bezier(.16,1,.3,1);z-index:200;
}
.sidebar::before{content:'';position:absolute;top:-70px;right:-55px;width:180px;height:180px;background:rgba(255,255,255,.07);border-radius:50%;pointer-events:none;}
.sidebar::after{content:'';position:absolute;bottom:-50px;left:-40px;width:140px;height:140px;background:rgba(255,255,255,.05);border-radius:50%;pointer-events:none;}

.sb-logo{padding:24px 20px 20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:11px;flex-shrink:0;}
.sb-logo-mark{width:38px;height:38px;border-radius:11px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sb-logo-mark svg{width:20px;height:20px;color:#fff;}
.sb-logo-name{font-family:var(--display);font-size:17px;font-weight:800;color:#fff;letter-spacing:-.3px;line-height:1.1;}
.sb-logo-name span{color:rgba(255,255,255,.5);font-weight:600;}

.sb-nav{flex:1;padding:16px 12px;display:flex;flex-direction:column;gap:2px;overflow-y:auto;}
.sb-nav::-webkit-scrollbar{display:none;}
.sb-label{font-size:9.5px;font-weight:700;color:rgba(255,255,255,.3);letter-spacing:.18em;text-transform:uppercase;padding:0 8px;margin:10px 0 4px;}
.sb-item{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:12px;color:rgba(255,255,255,.6);font-size:13px;font-weight:600;cursor:pointer;transition:all .18s;border:1.5px solid transparent;}
.sb-item:hover{background:rgba(255,255,255,.1);color:#fff;}
.sb-item.active{background:rgba(255,255,255,.16);color:#fff;border-color:rgba(255,255,255,.12);box-shadow:0 2px 12px rgba(0,0,0,.15);}
.sb-item svg{width:16px;height:16px;flex-shrink:0;}
.sb-badge{margin-left:auto;background:#F5A623;color:#fff;font-size:9px;font-weight:800;padding:2px 7px;border-radius:999px;}

.sb-foot{padding:12px 12px 20px;border-top:1px solid rgba(255,255,255,.1);flex-shrink:0;}
.sb-user{display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.1);border-radius:13px;padding:10px 12px;cursor:pointer;transition:background .18s;}
.sb-user:hover{background:rgba(255,255,255,.16);}
.sb-av{width:34px;height:34px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-family:var(--display);font-weight:800;font-size:12px;color:#fff;background:linear-gradient(135deg,#F472B6,#A78BFA);}
.sb-uname{font-size:12.5px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;}
.sb-urole{font-size:10px;color:rgba(255,255,255,.45);}

/* ════ MAIN AREA ════ */
.l-main{flex:1;overflow-y:auto;display:flex;flex-direction:column;min-width:0;}
.l-main::-webkit-scrollbar{width:5px;}
.l-main::-webkit-scrollbar-thumb{background:var(--faint);border-radius:999px;}
.l-scroll{padding:20px 24px 40px;display:flex;flex-direction:column;gap:16px;flex:1;}

/* ════ TOPBAR ════ */
.topbar{display:flex;align-items:flex-start;justify-content:space-between;padding:22px 24px 0;gap:12px;flex-shrink:0;flex-wrap:wrap;}
.topbar-left{display:flex;align-items:flex-start;gap:10px;}
.hamburger{display:none;width:38px;height:38px;background:var(--card);border:1.5px solid var(--border);border-radius:11px;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;margin-top:2px;transition:border-color var(--ease);}
.hamburger:hover{border-color:var(--p);}
.hamburger svg{width:18px;height:18px;color:var(--muted);}
.greeting-eyebrow{font-size:11px;font-weight:600;color:var(--muted);}
.greeting-name{font-family:var(--display);font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.5px;line-height:1.15;}
.greeting-sub{font-size:12px;color:var(--muted);margin-top:2px;}
.topbar-right{display:flex;align-items:center;gap:8px;flex-shrink:0;}
.icon-btn{width:38px;height:38px;background:var(--card);border:1.5px solid var(--border);border-radius:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color var(--ease);position:relative;flex-shrink:0;}
.icon-btn:hover{border-color:var(--p);}
.icon-btn svg{width:17px;height:17px;color:var(--muted);}

/* ════ RESERVE BTN ════ */
.reserve-btn{display:none;align-items:center;gap:7px;padding:9px 18px;background:var(--p);color:#fff;border-radius:11px;font-size:.84rem;font-weight:700;border:none;cursor:pointer;transition:all var(--ease);box-shadow:0 4px 12px rgba(84,72,200,.28);}
@media(min-width:480px){.reserve-btn{display:flex;}}
.reserve-btn:hover{background:var(--p2);transform:translateY(-1px);box-shadow:0 6px 18px rgba(84,72,200,.35);}

/* ════ DARK TOGGLE ════ */
.dark-toggle{width:38px;height:38px;background:var(--card);border:1.5px solid var(--border);border-radius:11px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color var(--ease);flex-shrink:0;}
.dark-toggle:hover{border-color:var(--p);}
.dark-toggle svg{width:16px;height:16px;color:var(--muted);}

/* ════ NOTIF ════ */
.notif-bell{position:relative;}
.notif-badge{position:absolute;top:-5px;right:-5px;background:#ef4444;color:#fff;font-size:.55rem;font-weight:700;padding:2px 5px;border-radius:999px;min-width:17px;text-align:center;border:2px solid var(--bg);line-height:1.3;pointer-events:none;display:none;}
.notif-dd{position:fixed;top:80px;right:20px;width:320px;background:var(--card);border-radius:var(--r-xl);box-shadow:var(--shadow-lg),0 0 0 1px var(--p-border);z-index:300;display:none;overflow:hidden;border:1px solid var(--border);}
.notif-dd.show{display:block;}
.notif-item{padding:.85rem 1.1rem;border-bottom:1px solid var(--border-subtle);transition:background .15s;cursor:pointer;}
.notif-item:hover{background:var(--input-bg);}
.notif-item.unread{background:var(--p-light);}
.notif-item:last-child{border-bottom:none;}
@media(max-width:479px){.notif-dd{left:12px;right:12px;width:auto;top:72px;}}

/* ════ FLASH ════ */
.flash-ok{display:flex;align-items:center;gap:8px;background:var(--p-light);border:1px solid var(--p-border);border-radius:var(--r-md);padding:12px 16px;font-size:.83rem;font-weight:600;color:var(--p);}

/* ════ NEXT-ACTION CARD ════ */
.next-card{display:flex;align-items:flex-start;gap:14px;border-radius:var(--r-md);padding:16px 18px;border:1px solid;}
.next-icon-wrap{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.next-eyebrow{font-size:.6rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;margin-bottom:4px;}
.next-msg{font-size:.83rem;color:var(--muted);line-height:1.6;}
.next-cta{display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:9px 16px;border-radius:9px;font-size:.75rem;font-weight:700;color:#fff;transition:opacity var(--ease);}
.next-cta:hover{opacity:.85;}

/* ════ TIMER BANNER ════ */
.timer-banner{display:none;border-radius:var(--r-md);padding:14px 18px;border:1px solid;}
.timer-banner.safe{background:var(--p-light);border-color:var(--p-border);color:#312e81;}
.timer-banner.warning{background:#fefce8;border-color:#fde68a;color:#854d0e;}
.timer-banner.urgent{background:#fff7ed;border-color:#fed7aa;color:#9a3412;}
html.dark .timer-banner.safe{background:rgba(84,72,200,.15);border-color:rgba(84,72,200,.3);color:#a5b4fc;}
html.dark .timer-banner.warning{background:rgba(180,83,9,.2);border-color:rgba(180,83,9,.35);color:#fcd34d;}
html.dark .timer-banner.urgent{background:rgba(154,52,18,.2);border-color:rgba(154,52,18,.35);color:#fb923c;}
.timer-inner{display:flex;align-items:center;gap:11px;flex-wrap:wrap;}
.timer-text-col{flex:1;min-width:140px;}
.timer-digit{display:inline-flex;flex-direction:column;align-items:center;background:rgba(0,0,0,.07);border-radius:8px;padding:.2rem .5rem;min-width:2.6rem;font-weight:700;font-size:1.1rem;line-height:1;font-family:var(--mono);}
.timer-digit span{font-family:var(--font);font-size:.5rem;font-weight:500;opacity:.6;text-transform:uppercase;letter-spacing:.07em;margin-top:3px;}
.timer-pulse{animation:pulse .9s ease-in-out infinite;}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
.timer-progress-wrap{height:3px;border-radius:999px;background:rgba(0,0,0,.08);overflow:hidden;margin-top:10px;}
.timer-progress-fill{height:100%;border-radius:999px;background:currentColor;opacity:.4;transition:width 1s linear;}

/* ════ UPCOMING PILL ════ */
.upcoming-pill{background:var(--p-light);border:1px solid var(--p-border);border-radius:var(--r-md);padding:14px 16px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
.up-icon{width:38px;height:38px;background:var(--p);border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 10px rgba(84,72,200,.28);}
.up-eyebrow{font-size:.6rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--p);margin-bottom:2px;}
.up-name{font-size:.88rem;font-weight:700;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;}
.up-time{font-family:var(--mono);font-size:.72rem;color:var(--p);margin-top:1px;}
.up-btn{margin-left:auto;font-size:.72rem;font-weight:700;color:var(--p);background:var(--card);border:1px solid var(--p-border);border-radius:8px;padding:8px 14px;white-space:nowrap;transition:all var(--ease);}
.up-btn:hover{background:var(--p);color:#fff;}
@media(max-width:479px){.up-name{max-width:100%;}.up-btn{margin-left:0;width:100%;text-align:center;display:block;}}

/* ════ STATS STRIP ════ */
.stats-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
.stat-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r-lg);padding:18px 18px 14px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:10px;transition:transform var(--ease),box-shadow var(--ease);}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md);}
.stat-top{display:flex;align-items:flex-start;justify-content:space-between;}
.stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.stat-lbl{font-size:.6rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-top:2px;}
.stat-num{font-family:var(--mono);font-size:2rem;font-weight:800;color:var(--text);letter-spacing:-.04em;line-height:1;}
.stat-hint{font-size:.72rem;color:var(--faint);font-weight:500;}
@media(max-width:640px){.stats-strip{grid-template-columns:repeat(2,1fr);gap:8px;}.stat-card{padding:14px 14px 12px;gap:8px;}.stat-num{font-size:1.6rem;}}

/* ════ QA TILES ════ */
.qa-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;}
.qa-tile{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r-md);padding:14px 8px;display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;transition:all var(--ease);}
.qa-tile:hover{border-color:var(--p);transform:translateY(-2px);box-shadow:var(--shadow-md);}
.qa-ico{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.qa-lbl{font-size:11px;font-weight:700;color:var(--text);text-align:center;line-height:1.3;}
@media(max-width:400px){.qa-bar{grid-template-columns:repeat(2,1fr);}.qa-tile{flex-direction:row;justify-content:flex-start;padding:12px;}.qa-lbl{text-align:left;font-size:12px;}}

/* ════ MAIN GRID ════ */
.grid-main{display:grid;grid-template-columns:minmax(0,1.9fr) minmax(0,1fr);gap:16px;}
.side-col{display:flex;flex-direction:column;gap:12px;}
@media(max-width:900px){.grid-main{grid-template-columns:1fr;}}

/* ════ CARDS ════ */
.card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--shadow);}
.card-p{padding:18px 20px;}
.card-p-lg{padding:20px 22px;}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.card-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.card-title{font-size:.9rem;font-weight:700;color:var(--text);letter-spacing:-.01em;}
.card-sub{font-size:.7rem;color:var(--muted);margin-top:2px;}
.section-lbl{font-size:.6rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);margin-bottom:14px;display:flex;align-items:center;gap:6px;}
.section-lbl::before{content:'';width:3px;height:13px;border-radius:2px;background:var(--p);flex-shrink:0;}
.link-sm{font-size:.65rem;font-weight:700;color:var(--p);text-decoration:none;letter-spacing:.05em;text-transform:uppercase;transition:opacity .15s;}
.link-sm:hover{opacity:.7;}

/* ════ RES CARDS (recent) ════ */
.res-list{display:flex;flex-direction:column;gap:8px;}
.res-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r-lg);padding:13px 14px 13px 18px;display:flex;align-items:center;gap:12px;box-shadow:var(--shadow);transition:all .2s;cursor:pointer;position:relative;overflow:hidden;}
.res-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;border-radius:4px 0 0 4px;background:var(--stripe,var(--p));}
.res-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md);border-color:var(--p-border);}
.res-icon-wrap{width:44px;height:44px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;}
.res-body{flex:1;min-width:0;}
.res-name{font-family:var(--display);font-size:13.5px;font-weight:800;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.res-desc{font-size:11px;color:var(--muted);margin-top:2px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.res-meta{font-size:11px;color:var(--muted);margin-top:5px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.res-go{width:30px;height:30px;background:var(--p);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(84,72,200,.3);transition:all var(--ease);}
.res-card:hover .res-go{background:var(--p2);transform:scale(1.08);}

/* ════ TAGS ════ */
.tag{display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:999px;font-size:9.5px;font-weight:700;white-space:nowrap;}
.tag-pending{background:#FEF3C7;color:#92400E;}
.tag-approved{background:#D1FAE5;color:#065F46;}
.tag-claimed{background:#EDE9FE;color:#5B21B6;}
.tag-declined{background:#FEE2E2;color:#991B1B;}
.tag-unclaimed,.tag-no-show{background:#FFF7ED;color:#9A3412;}
.tag-expired{background:#F1F5F9;color:#475569;}

/* ════ QA LINKS ════ */
.qa-link{display:flex;align-items:center;gap:11px;padding:11px 12px;border-radius:var(--r-sm);border:1px solid var(--border);background:var(--card);color:var(--muted);font-size:.83rem;font-weight:600;transition:all var(--ease);}
.qa-link:hover{border-color:var(--p);background:var(--p-light);color:var(--p);}
.qa-icon{width:30px;height:30px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.qa-chev{margin-left:auto;color:var(--faint);transition:color var(--ease);}
.qa-link:hover .qa-chev{color:var(--p);}

/* ════ RECENT BOOKING ROWS ════ */
.bk-row{display:flex;align-items:center;gap:11px;padding:8px 6px;border-radius:11px;transition:background var(--ease);}
.bk-row:hover{background:var(--p-light);}
.bk-date{width:38px;height:38px;background:var(--input-bg);border-radius:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--border-subtle);}
.bk-month{font-size:.55rem;font-weight:700;text-transform:uppercase;color:var(--muted);}
.bk-day{font-family:var(--mono);font-size:.95rem;font-weight:800;color:var(--text);line-height:1;}
.bk-name{font-size:.82rem;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.bk-time{font-family:var(--mono);font-size:.68rem;color:var(--muted);margin-top:1px;}

/* ════ FULLCALENDAR ════ */
#calendar{font-family:var(--font)!important;font-size:.8rem;}
.fc .fc-toolbar{flex-wrap:wrap;gap:.4rem;}
.fc-toolbar-title{font-family:var(--display)!important;font-size:.95rem!important;font-weight:800!important;color:var(--text)!important;letter-spacing:-.02em!important;}
.fc-button-primary{background:var(--p)!important;border-color:var(--p)!important;border-radius:9px!important;font-family:var(--font)!important;font-weight:700!important;font-size:.72rem!important;padding:.3rem .6rem!important;box-shadow:none!important;}
.fc-button-primary:hover{background:var(--p2)!important;}
.fc-button-primary:not(:disabled):active,.fc-button-primary:not(:disabled).fc-button-active{background:#3730a3!important;}
.fc-daygrid-event{border-radius:5px!important;font-family:var(--font)!important;font-size:.65rem!important;font-weight:600!important;padding:2px 5px!important;border:none!important;cursor:pointer!important;}
.fc-daygrid-day:hover{background-color:var(--p-light)!important;cursor:pointer;}
.fc-day-today{background:rgba(84,72,200,.06)!important;}
.fc-day-today .fc-daygrid-day-number{color:var(--p)!important;font-weight:800!important;}
.fc-daygrid-day-number{font-family:var(--font);font-size:.72rem;font-weight:600;}
.fc-col-header-cell-cushion{font-family:var(--font);font-size:.72rem;font-weight:700;letter-spacing:.04em;}
html.dark .fc-toolbar-title{color:var(--text)!important;}
html.dark .fc-daygrid-day-number,.html.dark .fc-col-header-cell-cushion{color:#818cf8;}
html.dark .fc-day-today{background:rgba(84,72,200,.15)!important;}
html.dark .fc-theme-standard td,html.dark .fc-theme-standard th,html.dark .fc-theme-standard .fc-scrollgrid{border-color:var(--border)!important;}
html.dark .fc-daygrid-day{background:var(--card)!important;}
@media(max-width:479px){
  .fc .fc-toolbar{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:6px;}
  .fc-toolbar-chunk:nth-child(2){text-align:center;}
  .fc-toolbar-title{font-size:.8rem!important;}
  .fc-button-primary{font-size:.65rem!important;padding:.25rem .45rem!important;}
}

/* ════ CAL LEGEND ════ */
.cal-legend{display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.leg-item{display:flex;align-items:center;gap:5px;}
.leg-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0;}
.leg-lbl{font-size:.68rem;font-weight:600;color:var(--muted);}

/* ════ LIBRARY ════ */
.grid-lib{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:16px;}
@media(max-width:900px){.grid-lib{grid-template-columns:1fr;}}
.lib-banner{background:linear-gradient(135deg,#3730a3 0%,#5448C8 60%,#818cf8 100%);border-radius:var(--r-xl);padding:20px 20px 16px;overflow:hidden;position:relative;}
.lib-banner::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='18' fill='none' stroke='rgba(255,255,255,.05)' stroke-width='1'/%3E%3C/svg%3E") repeat;pointer-events:none;}
.lib-inner{position:relative;z-index:1;}
.lib-banner-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px;}
.lib-eyebrow{font-size:.6rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);margin-bottom:4px;}
.lib-title{font-family:var(--mono);font-size:1.6rem;font-weight:800;color:#fff;letter-spacing:-.04em;line-height:1.1;}
.lib-sub{font-size:.72rem;color:rgba(255,255,255,.5);margin-top:3px;}
.lib-browse{display:inline-flex;align-items:center;gap:6px;padding:9px 13px;background:rgba(255,255,255,.18);color:#fff;border-radius:9px;font-size:.75rem;font-weight:700;border:1px solid rgba(255,255,255,.2);transition:background var(--ease);white-space:nowrap;flex-shrink:0;}
.lib-browse:hover{background:rgba(255,255,255,.28);}
.lib-stats{display:flex;gap:6px;width:100%;}
.lib-stat{flex:1 1 0;min-width:0;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.1);border-radius:9px;padding:7px 8px;}
.lib-stat-lbl{font-size:.5rem;font-weight:600;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.06em;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.lib-stat-val{font-family:var(--mono);font-size:.9rem;font-weight:800;color:#fff;line-height:1.2;display:block;}
.book-letter{width:34px;height:34px;border-radius:9px;background:var(--p-light);color:var(--p);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;}
.book-title{font-size:.82rem;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.book-author{font-size:.7rem;color:var(--muted);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.borrow-row{display:flex;align-items:center;gap:9px;background:var(--input-bg);border-radius:10px;padding:9px 12px;border:1px solid var(--border-subtle);}

/* ════ AI FINDER ════ */
.rag-wrap{position:relative;margin-top:12px;}
.rag-icon-pos{position:absolute;left:11px;top:50%;transform:translateY(-50%);pointer-events:none;color:var(--muted);display:flex;}
.search-input{width:100%;padding:11px 12px 11px 34px;border-radius:var(--r-sm);border:1px solid var(--p-border);font-family:var(--font);font-size:.85rem;background:var(--input-bg);color:var(--text);outline:none;transition:all var(--ease);}
.search-input:focus{border-color:var(--p);background:var(--card);box-shadow:0 0 0 3px rgba(84,72,200,.08);}
.search-input::placeholder{color:var(--muted);}
.ai-result-box{display:none;margin-top:.75rem;background:var(--p-light);border:1px solid var(--p-border);border-radius:var(--r-sm);padding:12px 14px;}
.ai-result-box.show{display:block;}
.find-btn{display:inline-flex;align-items:center;gap:7px;padding:10px 16px;background:var(--p);color:#fff;border-radius:var(--r-sm);font-size:.8rem;font-weight:700;border:none;cursor:pointer;transition:all var(--ease);}
.find-btn:hover{background:var(--p2);}
.find-btn:disabled{opacity:.6;cursor:not-allowed;}
html.dark .ai-result-box{background:rgba(84,72,200,.15)!important;border-color:rgba(84,72,200,.25)!important;}
html.dark #ragText,html.dark #ragText *{color:#a5b4fc!important;}

/* ════ DATE MODAL ════ */
.modal-back{position:fixed;inset:0;background:rgba(28,23,68,.5);z-index:400;display:flex;align-items:flex-end;justify-content:center;opacity:0;pointer-events:none;transition:opacity .25s;}
@media(min-width:640px){.modal-back{align-items:center;}}
.modal-back.show{opacity:1;pointer-events:auto;}
.modal-card{background:var(--card);border-radius:var(--r-xl) var(--r-xl) 0 0;padding:22px 20px 32px;width:100%;max-width:480px;max-height:80vh;overflow-y:auto;transform:translateY(20px);transition:transform .25s cubic-bezier(.16,1,.3,1);border:1.5px solid var(--border);}
@media(min-width:640px){.modal-card{border-radius:var(--r-xl);}}
.modal-back.show .modal-card{transform:none;}
.date-row{display:flex;align-items:center;gap:11px;padding:.75rem;border-bottom:1px solid var(--border-subtle);border-radius:10px;transition:background .15s;}
.date-row:hover{background:var(--input-bg);}
.date-row:last-child{border-bottom:none;}

/* ════ LOGIN TOAST ════ */
.login-toast{position:fixed;bottom:24px;right:16px;z-index:400;max-width:280px;background:#0f172a;border-radius:14px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px;box-shadow:0 8px 32px rgba(0,0,0,.3);transform:translateY(8px);opacity:0;pointer-events:none;transition:all .35s cubic-bezier(.34,1.56,.64,1);}
.login-toast.show{transform:none;opacity:1;pointer-events:auto;}
.toast-icon{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.toast-close{background:rgba(255,255,255,.08);border:none;border-radius:6px;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;margin-top:1px;}
@media(max-width:479px){.login-toast{bottom:16px;left:12px;right:12px;max-width:none;}}

/* ════ SHIMMER ════ */
.shimmer{height:12px;border-radius:6px;background:linear-gradient(90deg,var(--border) 25%,var(--input-bg) 50%,var(--border) 75%);background-size:200%;animation:shimmer 1.4s infinite;margin-bottom:8px;}
@keyframes shimmer{0%{background-position:200%}100%{background-position:-200%}}

/* ════ HOW-TO ════ */
.how-step{display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid var(--border-subtle);}
.how-step:last-child{border-bottom:none;}
.step-num{width:24px;height:24px;border-radius:50%;background:var(--p);color:#fff;font-size:.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;}
.status-guide-row{display:flex;align-items:center;gap:10px;padding:7px 0;border-bottom:1px solid var(--border-subtle);}
.status-guide-row:last-child{border-bottom:none;}

/* ════ RESPONSIVE ════ */
@media(max-width:860px){
  .sidebar{position:fixed;top:0;left:0;bottom:0;transform:translateX(-100%);box-shadow:4px 0 24px rgba(28,23,68,.2);}
  .sidebar.open{transform:translateX(0);}
  .sidebar-overlay{display:block;}
  .l-shell{display:block;}
  .l-main{height:100dvh;overflow-y:auto;}
  .hamburger{display:flex;}
  .topbar{padding:14px 16px 0;}
  .l-scroll{padding:14px 16px 32px;gap:12px;}
}
@media(max-width:600px){
  .topbar{padding:12px 14px 0;flex-wrap:nowrap;align-items:center;}
  .greeting-name{font-size:17px;}
  .grid-main,.grid-lib{gap:10px;}
  .qa-bar{gap:7px;}
}
@media(max-width:400px){
  .topbar-right .icon-btn:first-child{display:none;}
  .stats-strip{gap:7px;}
}

/* ════ STRIPE COLORS ════ */
<?php
$stripeMap=[
  'approved'=>'#22C997','pending'=>'#F5A623',
  'claimed'=>'#818CF8','declined'=>'#F16063',
  'unclaimed'=>'#FB923C','expired'=>'#94A3B8'
];
?>
</style>
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="l-shell">

<!-- ═══════════════ SIDEBAR ═══════════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sb-logo">
    <div class="sb-logo-mark">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/><path d="M9 10h6M9 13h4"/>
      </svg>
    </div>
    <div class="sb-logo-name">eLib<span>Reserve</span></div>
  </div>

  <nav class="sb-nav">
    <div class="sb-label">Main Menu</div>
    <a class="sb-item active" href="<?= base_url('/dashboard') ?>" onclick="closeSidebar()">
      <?= icon('grid',16,'currentColor') ?> Dashboard
    </a>
    <a class="sb-item" href="<?= base_url('/books') ?>" onclick="closeSidebar()">
      <?= icon('book-open',16,'currentColor') ?> Library
    </a>
    <a class="sb-item" href="<?= base_url('/members') ?>" onclick="closeSidebar()">
      <?= icon('users',16,'currentColor') ?> Members
    </a>
    <a class="sb-item" href="<?= base_url('/messages') ?>" onclick="closeSidebar()">
      <?= icon('message',16,'currentColor') ?> Messages
    </a>
    <a class="sb-item" href="<?= base_url('/reservation-list') ?>" onclick="closeSidebar()">
      <?= icon('calendar',16,'currentColor') ?> Schedule
      <?php if ($pending > 0): ?>
        <span class="sb-badge"><?= $pending ?></span>
      <?php endif; ?>
    </a>
    <div class="sb-label" style="margin-top:10px;">Account</div>
    <a class="sb-item" href="<?= base_url('/settings') ?>" onclick="closeSidebar()">
      <?= icon('settings',16,'currentColor') ?> Settings
    </a>
    <a class="sb-item" href="<?= base_url('/profile') ?>" onclick="closeSidebar()">
      <?= icon('user',16,'currentColor') ?> Profile
    </a>
  </nav>

  <div class="sb-foot">
    <div class="sb-user">
      <div class="sb-av"><?= esc($initials) ?></div>
      <div style="flex:1;min-width:0;">
        <div class="sb-uname"><?= esc($user_name) ?></div>
        <div class="sb-urole">SK Member</div>
      </div>
      <div style="color:rgba(255,255,255,.35);flex-shrink:0;"><?= icon('dots-v',14,'currentColor') ?></div>
    </div>
  </div>
</aside>

<!-- ═══════════════ MAIN ═══════════════ -->
<main class="l-main">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="hamburger" onclick="openSidebar()"><?= icon('grid',18,'currentColor') ?></div>
      <div>
        <div class="greeting-eyebrow"><?= $greeting ?></div>
        <div class="greeting-name"><?= esc($user_name) ?></div>
        <div class="greeting-sub"><?= date('l, F j, Y') ?></div>
      </div>
    </div>
    <div class="topbar-right">
      <div class="dark-toggle" onclick="toggleDark()" title="Toggle dark mode">
        <span id="darkIcon"><?= icon('moon',16,'currentColor') ?></span>
      </div>
      <a href="<?= base_url('/reservation') ?>" class="reserve-btn">
        <?= icon('plus',16,'white') ?> Reserve
      </a>
      <div class="notif-bell icon-btn" onclick="toggleNotifications()" title="Notifications">
        <?= icon('bell',17,'currentColor') ?>
        <span class="notif-badge" id="notifBadge">0</span>
      </div>
    </div>
  </header>

  <!-- NOTIFICATION DROPDOWN -->
  <div id="notifDD" class="notif-dd">
    <div style="padding:11px 14px;border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
      <span style="font-weight:700;font-size:13px;color:var(--text);">Notifications</span>
      <button onclick="markAllRead()" style="font-size:11px;color:var(--p);font-weight:600;background:none;border:none;cursor:pointer;">Mark all read</button>
    </div>
    <div id="notifList" style="max-height:280px;overflow-y:auto;-webkit-overflow-scrolling:touch;"></div>
  </div>

  <!-- SCROLL AREA -->
  <div class="l-scroll">

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="flash-ok"><?= icon('check-circle',14,'var(--p)') ?> <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Next-Action Card -->
    <?php if ($nextAction): $nc = $nextColors[$nextAction['color']]; ?>
      <div class="next-card" style="background:<?= $nc['bg'] ?>;border-color:<?= $nc['border'] ?>;">
        <div class="next-icon-wrap" style="background:<?= $nc['icon_bg'] ?>;"><?= icon($nc['icon'],14,$nc['icon_fg']) ?></div>
        <div style="flex:1;min-width:0;">
          <div class="next-eyebrow" style="color:<?= $nc['icon_fg'] ?>;">What to do next</div>
          <div class="next-msg"><?= $nextAction['msg'] ?></div>
          <a href="<?= base_url($nextAction['url']) ?>" class="next-cta" style="background:<?= $nc['btn_bg'] ?>;">
            <?= $nextAction['cta'] ?> <?= icon('arrow-right',12,'white') ?>
          </a>
        </div>
      </div>
    <?php endif; ?>

    <!-- Countdown Timer -->
    <div id="timerBanner" class="timer-banner">
      <div class="timer-inner">
        <div id="timerIconWrap" style="width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.07);flex-shrink:0;"><?= icon('hourglass',16,'currentColor') ?></div>
        <div class="timer-text-col">
          <p style="font-weight:700;font-size:.9rem;" id="timerTitle">Your reservation ends soon</p>
          <p style="font-size:.76rem;opacity:.7;margin-top:2px;" id="timerSub"></p>
        </div>
        <div style="display:flex;align-items:center;gap:4px;flex-shrink:0;">
          <div class="timer-digit"><span id="tdH">00</span><span>hrs</span></div>
          <span style="font-weight:700;font-size:14px;opacity:.4;" class="timer-pulse">:</span>
          <div class="timer-digit"><span id="tdM">00</span><span>min</span></div>
          <span style="font-weight:700;font-size:14px;opacity:.4;" class="timer-pulse">:</span>
          <div class="timer-digit"><span id="tdS">00</span><span>sec</span></div>
        </div>
      </div>
      <div id="timerPW" class="timer-progress-wrap" style="display:none;"><div id="timerPF" class="timer-progress-fill" style="width:0%;"></div></div>
    </div>

    <!-- Upcoming Pill -->
    <?php if ($upcoming): ?>
      <div class="upcoming-pill">
        <div class="up-icon"><?= icon('ticket',16,'white') ?></div>
        <div style="flex:1;min-width:0;">
          <div class="up-eyebrow">Upcoming Reservation</div>
          <div class="up-name"><?= esc($upcoming['resource_name'] ?? 'Resource') ?><?php if (!empty($upcoming['pc_number'])): ?> &middot; <span style="font-weight:400;"><?= esc($upcoming['pc_number']) ?></span><?php endif; ?></div>
          <div class="up-time"><?= date('M j, Y',strtotime($upcoming['reservation_date'])) ?> &nbsp;&middot;&nbsp; <?= date('g:i A',strtotime($upcoming['start_time'])) ?> – <?= date('g:i A',strtotime($upcoming['end_time'])) ?></div>
        </div>
        <a href="<?= base_url('/reservation-list') ?>" class="up-btn">View →</a>
      </div>
    <?php endif; ?>

    <!-- Stats Strip -->
    <div class="stats-strip">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon" style="background:#ECEAFE;"><?= icon('layers',16,'#5448C8') ?></div>
          <div class="stat-lbl">Total</div>
        </div>
        <div class="stat-num"><?= $total ?></div>
        <div class="stat-hint">All time</div>
      </div>
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon" style="background:#FEF3C7;"><?= icon('clock',16,'#D97706') ?></div>
          <div class="stat-lbl">Pending</div>
        </div>
        <div class="stat-num" style="color:#D97706;"><?= $pending ?></div>
        <div class="stat-hint">Awaiting review</div>
      </div>
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon" style="background:#D1FAE5;"><?= icon('check-circle',16,'#059669') ?></div>
          <div class="stat-lbl">Approved</div>
        </div>
        <div class="stat-num" style="color:#059669;"><?= $approved ?></div>
        <div class="stat-hint">Ready to use</div>
      </div>
      <?php if ($unclaimedCount > 0): ?>
        <div class="stat-card" style="border-color:rgba(251,146,60,.25);">
          <div class="stat-top">
            <div class="stat-icon" style="background:#FFF7ED;"><?= icon('ticket',16,'#EA580C') ?></div>
            <div class="stat-lbl">No-show</div>
          </div>
          <div class="stat-num" style="color:#EA580C;"><?= $unclaimedCount ?></div>
          <div class="stat-hint">Slot<?= $unclaimedCount>1?'s':'' ?> missed</div>
        </div>
      <?php elseif ($claimedCount > 0): ?>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon" style="background:#EDE9FE;"><?= icon('check-double',16,'#7C3AED') ?></div>
            <div class="stat-lbl">Claimed</div>
          </div>
          <div class="stat-num" style="color:#7C3AED;"><?= $claimedCount ?></div>
          <div class="stat-hint">Tickets used</div>
        </div>
      <?php else: ?>
        <div class="stat-card">
          <div class="stat-top">
            <div class="stat-icon" style="background:#FEE2E2;"><?= icon('ban',16,'#DC2626') ?></div>
            <div class="stat-lbl">Declined</div>
          </div>
          <div class="stat-num" style="color:#DC2626;"><?= $declined ?></div>
          <div class="stat-hint">Not approved</div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Quick-Action Tiles -->
    <div class="qa-bar">
      <a class="qa-tile" href="<?= base_url('/reservation') ?>">
        <div class="qa-ico" style="background:#ECEAFE;"><?= icon('plus',18,'#5448C8') ?></div>
        <div class="qa-lbl">New Reservation</div>
      </a>
      <a class="qa-tile" href="<?= base_url('/reservation-list') ?>">
        <div class="qa-ico" style="background:#FEF3C7;"><?= icon('calendar',18,'#D97706') ?></div>
        <div class="qa-lbl">My Bookings</div>
      </a>
      <a class="qa-tile" href="<?= base_url('/books') ?>">
        <div class="qa-ico" style="background:#D1FAE5;"><?= icon('book-open',18,'#059669') ?></div>
        <div class="qa-lbl">Browse Library</div>
      </a>
      <a class="qa-tile" href="<?= base_url('/profile') ?>">
        <div class="qa-ico" style="background:#FCE7F3;"><?= icon('user',18,'#BE185D') ?></div>
        <div class="qa-lbl">View Profile</div>
      </a>
    </div>

    <!-- Calendar + Side Column -->
    <div class="grid-main">

      <!-- FullCalendar -->
      <div class="card card-p-lg">
        <div class="card-head" style="flex-wrap:wrap;gap:10px;">
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="card-icon" style="background:var(--p-light);"><?= icon('calendar-days',16,'var(--p)') ?></div>
            <div>
              <div class="card-title">Community Schedule</div>
              <div class="card-sub">Tap any date to see reservations</div>
            </div>
          </div>
          <div class="cal-legend">
            <?php foreach([['#fbbf24','Pending'],['#10b981','Approved'],['#f87171','Declined'],['#a855f7','Claimed']] as [$c,$l]): ?>
              <div class="leg-item"><div class="leg-dot" style="background:<?=$c?>;"></div><span class="leg-lbl"><?=$l?></span></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div id="calendar"></div>
      </div>

      <!-- Side Column -->
      <div class="side-col">

        <!-- Quick action links -->
        <div class="card card-p">
          <div class="section-lbl">Quick Actions</div>
          <div style="display:flex;flex-direction:column;gap:5px;">
            <a href="<?= base_url('/reservation') ?>" class="qa-link">
              <div class="qa-icon" style="background:#ECEAFE;"><?= icon('plus',16,'var(--p)') ?></div>
              New Reservation
              <span class="qa-chev"><?= icon('chevron-right',14,'currentColor') ?></span>
            </a>
            <a href="<?= base_url('/reservation-list') ?>" class="qa-link">
              <div class="qa-icon" style="background:#EDE9FE;"><?= icon('calendar',16,'#7C3AED') ?></div>
              My Reservations
              <?php if ($pending > 0): ?>
                <span style="margin-left:auto;background:#FEF3C7;color:#92400E;font-size:9px;font-weight:700;padding:1px 6px;border-radius:999px;"><?= $pending ?></span>
              <?php else: ?>
                <span class="qa-chev"><?= icon('chevron-right',14,'currentColor') ?></span>
              <?php endif; ?>
            </a>
            <a href="<?= base_url('/books') ?>" class="qa-link">
              <div class="qa-icon" style="background:#FEF3C7;"><?= icon('book-open',16,'#D97706') ?></div>
              Browse Library
              <span class="qa-chev"><?= icon('chevron-right',14,'currentColor') ?></span>
            </a>
            <a href="<?= base_url('/profile') ?>" class="qa-link">
              <div class="qa-icon" style="background:#F3E8FF;"><?= icon('user',16,'#9333EA') ?></div>
              View Profile
              <span class="qa-chev"><?= icon('chevron-right',14,'currentColor') ?></span>
            </a>
          </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card card-p" style="flex:1;">
          <div class="card-head">
            <div class="section-lbl" style="margin-bottom:0;">Recent Bookings</div>
            <a href="<?= base_url('/reservation-list') ?>" class="link-sm">View all →</a>
          </div>
          <?php if (!empty($processedRecent)): ?>
            <?php
            $statusStripe=[
              'approved'=>'#22C997','pending'=>'#F5A623',
              'claimed'=>'#818CF8','declined'=>'#F16063',
              'unclaimed'=>'#FB923C','expired'=>'#94A3B8'
            ];
            $statusIcon=[
              'approved'=>['#ECEAFE','#5448C8','check-circle'],
              'pending' =>['#FEF3C7','#D97706','clock'],
              'claimed' =>['#EDE9FE','#7C3AED','ticket'],
              'declined'=>['#FEE2E2','#DC2626','ban'],
              'unclaimed'=>['#FFF7ED','#EA580C','triangle'],
              'expired' =>['#F1F5F9','#94A3B8','hourglass'],
            ];
            ?>
            <div class="res-list">
              <?php foreach(array_slice($processedRecent,0,5) as $res):
                $s   = $res['_status'];
                $dt  = new DateTime($res['reservation_date']);
                $si  = $statusIcon[$s] ?? ['#F1F5F9','#94A3B8','clock'];
                $str = $statusStripe[$s] ?? '#94A3B8';
              ?>
              <a href="<?= base_url('/reservation-list') ?>" class="res-card" style="--stripe:<?= $str ?>;">
                <div class="res-icon-wrap" style="background:<?= $si[0] ?>;">
                  <?= icon($si[2],18,$si[1]) ?>
                </div>
                <div class="res-body">
                  <div class="res-name"><?= esc($res['resource_name'] ?? 'Resource #'.$res['resource_id']) ?></div>
                  <div class="res-desc"><?= $dt->format('M j, Y') ?></div>
                  <div class="res-meta">
                    <span><?= date('g:i A',strtotime($res['start_time'])) ?> – <?= date('g:i A',strtotime($res['end_time'])) ?></span>
                    <span class="tag tag-<?= $s ?>"><?= $s==='unclaimed'?'No-show':ucfirst($s) ?></span>
                  </div>
                </div>
                <div class="res-go">
                  <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" style="width:11px;height:11px;flex-shrink:0;"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
              </a>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div style="text-align:center;padding:24px 12px;">
              <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--faint);"><?= icon('calendar-x',28,'currentColor') ?></div>
              <p style="font-size:12px;color:var(--muted);">No bookings yet</p>
              <a href="<?= base_url('/reservation') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:9px;font-size:11px;font-weight:700;color:var(--p);">
                <?= icon('plus',12,'var(--p)') ?> Make your first reservation
              </a>
            </div>
          <?php endif; ?>
        </div>

      </div><!-- /side-col -->
    </div><!-- /grid-main -->

    <!-- How-to + Status Guide (conditional) -->
    <?php if (empty($reservations) || $unclaimedCount > 0 || $pending > 0): ?>
    <div class="grid-main">
      <div class="card card-p">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
          <div class="card-icon" style="background:var(--p-light);"><?= icon('list-check',16,'var(--p)') ?></div>
          <div><div class="card-title">How to Reserve</div><div class="card-sub">Step-by-step guide</div></div>
        </div>
        <?php $step=1;foreach([
          ['Click "New Reservation"','Choose a resource, pick your date and time, and describe your purpose.'],
          ['Wait for approval','An SK officer will review your request, usually within 24 hours.'],
          ['Download your e-ticket','Once approved, open My Reservations and download your QR code.'],
          ['Scan at the entrance','Show your e-ticket to be scanned when you arrive.'],
          ["Be on time","Slots expire if you don't show up. Cancel in advance if plans change."],
        ] as [$title,$body]): ?>
          <div class="how-step">
            <div class="step-num"><?= $step++ ?></div>
            <div>
              <p style="font-weight:600;font-size:12.5px;color:var(--text);"><?= $title ?></p>
              <p style="font-size:11px;color:var(--muted);margin-top:2px;"><?= $body ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="card card-p">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
          <div class="card-icon" style="background:var(--p-light);"><?= icon('info',16,'var(--p)') ?></div>
          <div><div class="card-title">Status Reference</div><div class="card-sub">What each status means</div></div>
        </div>
        <?php foreach([
          ['pending','clock','#FEF3C7','#92400E','#D97706','Pending','Waiting for SK officer review.'],
          ['approved','check-circle','#D1FAE5','#166534','#16A34A','Approved','Confirmed. Get your e-ticket.'],
          ['claimed','check-double','#EDE9FE','#5B21B6','#7C3AED','Claimed','E-ticket scanned. Slot used.'],
          ['unclaimed','ticket','#FFF7ED','#C2410C','#EA580C','No-show',"Approved but you didn't attend."],
          ['declined','ban','#FEE2E2','#991B1B','#DC2626','Declined','Not approved. Try another time.'],
          ['expired','hourglass','#F1F5F9','#475569','#64748B','Expired','Date passed before approval.'],
        ] as [$key,$ico,$bg,$fg,$ic,$label,$desc]): ?>
          <div class="status-guide-row">
            <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:7px;font-size:9px;font-weight:700;text-transform:uppercase;flex-shrink:0;min-width:70px;justify-content:center;background:<?=$bg?>;color:<?=$fg?>;">
              <?= icon($ico,8,$ic) ?><?= $label ?>
            </span>
            <p style="font-size:.72rem;color:var(--muted);"><?= $desc ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- ═══ LIBRARY SECTION ═══ -->
    <div class="grid-lib">

      <!-- Left: Banner + AI Finder -->
      <div style="display:flex;flex-direction:column;gap:14px;min-width:0;">
        <div class="lib-banner">
          <div class="lib-inner">
            <div class="lib-banner-top">
              <div>
                <div class="lib-eyebrow">Community Library</div>
                <div class="lib-title"><?= $availableCount ?></div>
                <div class="lib-sub">available · <?= $totalBooks ?> total titles</div>
              </div>
              <a href="<?= base_url('/books') ?>" class="lib-browse"><?= icon('book-open',13,'white') ?> Browse</a>
            </div>
            <div class="lib-stats">
              <div class="lib-stat"><span class="lib-stat-lbl">My Borrows</span><span class="lib-stat-val"><?= count($myBorrowings) ?></span></div>
              <div class="lib-stat"><span class="lib-stat-lbl">Pending</span><span class="lib-stat-val"><?= count(array_filter($myBorrowings,fn($b)=>($b['status']??'')==='pending')) ?></span></div>
              <div class="lib-stat"><span class="lib-stat-lbl">Active</span><span class="lib-stat-val"><?= count(array_filter($myBorrowings,fn($b)=>($b['status']??'')==='approved')) ?></span></div>
            </div>
          </div>
        </div>

        <!-- AI Book Finder -->
        <div class="card card-p">
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="card-icon" style="background:#EDE9FE;"><?= icon('sparkles',16,'#7C3AED') ?></div>
            <div><div class="card-title">AI Book Finder</div><div class="card-sub">Describe what you want to read</div></div>
          </div>
          <div class="rag-wrap">
            <span class="rag-icon-pos"><?= icon('search',13,'currentColor') ?></span>
            <input type="text" id="ragInput" class="search-input" placeholder="e.g. Filipino history, funny stories…" autocomplete="off" onkeydown="if(event.key==='Enter')doRagSearch()">
          </div>
          <div id="ragSkel" style="display:none;margin-top:.5rem;">
            <div class="shimmer" style="width:90%;"></div><div class="shimmer" style="width:70%;"></div><div class="shimmer" style="width:52%;"></div>
          </div>
          <div class="ai-result-box" id="ragResult">
            <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
              <?= icon('robot',14,'var(--p)') ?>
              <p style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.15em;color:var(--p);">Librarian Suggestion</p>
            </div>
            <p style="font-size:12px;color:#312e81;line-height:1.6;" id="ragText"></p>
            <div id="ragBooks" style="margin-top:8px;display:flex;flex-wrap:wrap;gap:5px;"></div>
          </div>
          <div id="ragErr" style="display:none;margin-top:5px;font-size:11px;color:#DC2626;font-weight:500;"></div>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:11px;">
            <button onclick="doRagSearch()" id="ragBtn" class="find-btn"><?= icon('sparkles',13,'white') ?> Find Books</button>
            <a href="<?= base_url('/books') ?>" class="link-sm">Full library →</a>
          </div>
        </div>
      </div>

      <!-- Right: Available + My Borrows -->
      <div style="display:flex;flex-direction:column;gap:14px;min-width:0;">

        <div class="card card-p" style="flex:1;">
          <div class="card-head">
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="card-icon" style="background:var(--p-light);"><?= icon('book-open',16,'var(--p)') ?></div>
              <div><div class="card-title">Available Now</div><div class="card-sub">Books you can borrow today</div></div>
            </div>
            <a href="<?= base_url('/books') ?>" class="link-sm">All →</a>
          </div>
          <?php if (!empty($featuredBooks)): ?>
            <div style="display:flex;flex-direction:column;gap:2px;">
              <?php foreach(array_slice($featuredBooks,0,6) as $book):
                $avail=(int)($book['available_copies']??0);
                $pill=$avail===0?'background:#FEE2E2;color:#991B1B;':($avail<=1?'background:#FEF3C7;color:#92400E;':'background:#D1FAE5;color:#166634;');
                $pillTxt=$avail===0?'Out':($avail<=1?'1 left':$avail.' left');
              ?>
                <a href="<?= base_url('/books') ?>" style="display:flex;align-items:center;gap:10px;padding:7px 6px;border-radius:10px;transition:background .15s;min-width:0;">
                  <div class="book-letter"><?= mb_strtoupper(mb_substr($book['title'],0,1)) ?></div>
                  <div style="flex:1;min-width:0;"><div class="book-title"><?= esc($book['title']) ?></div><div class="book-author"><?= esc($book['author']??'Unknown') ?></div></div>
                  <span style="font-size:.6rem;font-weight:800;padding:2px 8px;border-radius:999px;flex-shrink:0;white-space:nowrap;<?= $pill ?>"><?= $pillTxt ?></span>
                </a>
              <?php endforeach; ?>
            </div>
            <?php if(count($featuredBooks)>6): ?>
              <div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--border-subtle);text-align:center;">
                <a href="<?= base_url('/books') ?>" class="link-sm">+<?= count($featuredBooks)-6 ?> more →</a>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div style="text-align:center;padding:32px 12px;">
              <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--faint);"><?= icon('book-open',28,'currentColor') ?></div>
              <p style="font-size:.78rem;color:var(--muted);font-weight:600;">No books available</p>
            </div>
          <?php endif; ?>
        </div>

        <?php $activeBorrows=array_slice(array_values(array_filter($myBorrowings,fn($b)=>in_array($b['status']??'',['approved','pending']))),0,4); ?>
        <?php if (!empty($activeBorrows)): ?>
          <div class="card card-p">
            <div class="card-head">
              <div style="display:flex;align-items:center;gap:10px;">
                <div class="card-icon" style="background:#D1FAE5;"><?= icon('bookmark',16,'#16A34A') ?></div>
                <div><div class="card-title">My Active Borrows</div><div class="card-sub">Currently checked out</div></div>
              </div>
              <a href="<?= base_url('/books') ?>#mine" class="link-sm">All →</a>
            </div>
            <div style="display:flex;flex-direction:column;gap:7px;">
              <?php foreach($activeBorrows as $borrow):
                $bs=strtolower($borrow['status']??'pending');
                $due=!empty($borrow['due_date'])?strtotime($borrow['due_date']):null;
                $overdue=$due&&$due<time();$dueSoon=$due&&!$overdue&&$due<time()+3*86400;
              ?>
                <div class="borrow-row">
                  <div class="book-letter" style="width:30px;height:30px;font-size:.7rem;"><?= mb_strtoupper(mb_substr($borrow['title']??'B',0,1)) ?></div>
                  <div style="flex:1;min-width:0;">
                    <p style="font-weight:600;font-size:.8rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($borrow['title']??'Unknown Book') ?></p>
                    <?php if($due&&$bs==='approved'): ?>
                      <p style="font-family:var(--mono);font-size:.68rem;color:<?= $overdue?'#ef4444':($dueSoon?'#d97706':'var(--muted)') ?>;"><?= $overdue?'Overdue · ':($dueSoon?'Due soon · ':'') ?><?= date('M j, Y',$due) ?></p>
                    <?php endif; ?>
                  </div>
                  <span class="tag tag-<?= $overdue?'declined':($dueSoon?'pending':$bs) ?>"><?= $overdue?'Overdue':($dueSoon?'Due Soon':ucfirst($bs)) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else: ?>
          <div class="card card-p" style="text-align:center;padding:28px 20px;">
            <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--faint);"><?= icon('bookmark',26,'currentColor') ?></div>
            <p style="font-size:.78rem;color:var(--muted);font-weight:600;">No active borrows</p>
            <a href="<?= base_url('/books') ?>" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;font-size:.72rem;font-weight:700;color:var(--p);"><?= icon('book-open',12,'var(--p)') ?> Borrow a book</a>
          </div>
        <?php endif; ?>

      </div>
    </div><!-- /grid-lib -->

  </div><!-- /l-scroll -->
</main>
</div><!-- /l-shell -->

<!-- DATE MODAL -->
<div id="dateModal" class="modal-back" onclick="if(event.target===this)closeDateModal()">
  <div class="modal-card">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
      <div>
        <h3 style="font-size:16px;font-weight:700;letter-spacing:-.2px;" id="modalDateTitle"></h3>
        <p style="font-size:11px;color:var(--muted);margin-top:2px;" id="modalDateSub"></p>
      </div>
      <button onclick="closeDateModal()" style="width:36px;height:36px;border-radius:9px;background:var(--input-bg);border:none;color:var(--muted);cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <?= icon('x',13,'currentColor') ?>
      </button>
    </div>
    <div id="modalList"></div>
    <div id="modalEmpty" style="display:none;text-align:center;padding:24px 12px;">
      <div style="display:flex;justify-content:center;margin-bottom:8px;color:var(--faint);"><?= icon('calendar-x',26,'currentColor') ?></div>
      <p style="font-size:12px;color:var(--muted);">No reservations for this date.</p>
    </div>
    <button onclick="closeDateModal()" style="margin-top:16px;width:100%;padding:12px;background:var(--input-bg);border-radius:var(--r-sm);font-family:var(--font);font-weight:600;color:var(--muted);border:1px solid var(--border);cursor:pointer;font-size:.82rem;">Close</button>
  </div>
</div>

<!-- LOGIN TOAST -->
<div id="loginToast" class="login-toast">
  <div class="toast-icon" id="toastIcon"></div>
  <div style="flex:1;min-width:0;"><p id="toastTitle" style="font-weight:700;font-size:12px;color:#fff;"></p><p id="toastBody" style="font-size:10px;color:rgba(255,255,255,.6);margin-top:2px;"></p></div>
  <button class="toast-close" onclick="dismissToast()"><?= icon('x',10,'white') ?></button>
</div>

<script>
/* ════ DATA FROM PHP ════ */
const NOTIF_KEY = 'notif_<?= session()->get('user_id') ?>';
const reservations = <?= json_encode($reservations ?? []) ?>;
const allResData   = <?= json_encode($allReservations ?? []) ?>;
const approvedRes  = reservations.filter(r => r.status === 'approved' && !r.claimed);

/* ════ SIDEBAR ════ */
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
  setTimeout(()=>ov.style.display='none',260);
  document.body.style.overflow='';
}

/* ════ DARK MODE ════ */
function toggleDark(){
  const on=document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme',on?'dark':'light');
  document.getElementById('darkIcon').innerHTML=on
    ?'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:16px;height:16px;flex-shrink:0;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>'
    :'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>';
}
(function(){
  if(localStorage.getItem('theme')==='dark'){
    document.getElementById('darkIcon').innerHTML='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:16px;height:16px;flex-shrink:0;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
  }
})();

/* ════ NOTIFICATIONS ════ */
let notifications=[];
const getSeenIds=()=>{try{return JSON.parse(localStorage.getItem(NOTIF_KEY)||'[]');}catch{return[];}};
const saveSeenIds=ids=>localStorage.setItem(NOTIF_KEY,JSON.stringify(ids));
const timeAgo=t=>{const s=Math.floor((Date.now()-new Date(t))/1000);if(s<60)return'Just now';if(s<3600)return`${Math.floor(s/60)}m ago`;if(s<86400)return`${Math.floor(s/3600)}h ago`;return`${Math.floor(s/86400)}d ago`;};

function loadNotifications(){
  const seen=getSeenIds();
  notifications=reservations.filter(r=>r.status==='approved').map(r=>({
    id:parseInt(r.id),title:'Reservation Approved',
    msg:`${r.resource_name||'Resource'} · ${new Date(r.reservation_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}`,
    time:r.updated_at||r.created_at||new Date().toISOString(),
    read:seen.includes(parseInt(r.id))
  }));
  updateBadge();renderNotifs();
}
function markAllRead(){saveSeenIds([...new Set([...getSeenIds(),...notifications.map(n=>n.id)])]);notifications.forEach(n=>n.read=true);updateBadge();renderNotifs();}
function markRead(id){const ids=getSeenIds();if(!ids.includes(id))saveSeenIds([...ids,id]);const n=notifications.find(n=>n.id===id);if(n){n.read=true;updateBadge();renderNotifs();}}
function updateBadge(){const b=document.getElementById('notifBadge');const u=notifications.filter(n=>!n.read).length;b.style.display=u>0?'block':'none';b.textContent=u>9?'9+':u;}
function renderNotifs(){
  const list=document.getElementById('notifList');
  if(!notifications.length){list.innerHTML=`<div style="text-align:center;padding:24px;"><p style="font-size:12px;color:var(--muted);">All caught up!</p></div>`;return;}
  list.innerHTML=notifications.sort((a,b)=>new Date(b.time)-new Date(a.time)).map(n=>`
    <div class="notif-item ${!n.read?'unread':''}" onclick="markRead(${n.id})">
      <div style="display:flex;align-items:flex-start;gap:9px;">
        <div style="width:30px;height:30px;background:var(--p-light);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--p)" stroke-width="1.8" style="width:13px;height:13px;"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div style="flex:1;min-width:0;">
          <p style="font-weight:700;font-size:12px;color:var(--text);">${n.title}</p>
          <p style="font-size:10px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.msg}</p>
          <p style="font-size:9px;color:var(--faint);margin-top:2px;">${timeAgo(n.time)}</p>
        </div>
        ${!n.read?'<span style="width:6px;height:6px;background:var(--p);border-radius:50%;flex-shrink:0;margin-top:3px;"></span>':''}
      </div>
    </div>`).join('');
}
function toggleNotifications(){document.getElementById('notifDD').classList.toggle('show');}
document.addEventListener('click',e=>{const dd=document.getElementById('notifDD');const bell=document.querySelector('.notif-bell');if(dd&&bell&&!bell.contains(e.target)&&!dd.contains(e.target))dd.classList.remove('show');});

/* ════ DATE MODAL ════ */
function to12h(ts){if(!ts)return'—';const[h,m]=(ts||'').split(':');let hr=parseInt(h,10);const mn=(m||'00').padStart(2,'0');if(isNaN(hr))return ts;return`${hr%12||12}:${mn} ${hr<12?'AM':'PM'}`;}
function openDateModal(date,items){
  const d=new Date(date+'T00:00:00');
  document.getElementById('modalDateTitle').textContent=d.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
  document.getElementById('modalDateSub').textContent=items.length?`${items.length} reservation${items.length>1?'s':''}`:'' ;
  const list=document.getElementById('modalList');const empty=document.getElementById('modalEmpty');
  list.innerHTML='';
  if(items.length){
    empty.style.display='none';
    const cmap={approved:'#D1FAE5|#166534',pending:'#FEF3C7|#92400E',declined:'#FEE2E2|#991B1B',canceled:'#FEE2E2|#991B1B',claimed:'#EDE9FE|#5B21B6'};
    items.sort((a,b)=>(a.start_time||'').localeCompare(b.start_time||'')).forEach(r=>{
      const isCl=r.claimed==1||r.status==='claimed'||!!r.claimed_at;
      const s=isCl?'claimed':(r.status||'pending').toLowerCase();
      const[cbg,cfg]=(cmap[s]||'#F1F5F9|#475569').split('|');
      const row=document.createElement('div');row.className='date-row';
      row.innerHTML=`<div style="width:32px;height:32px;background:var(--input-bg);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--border);"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.5" style="width:13px;height:13px;flex-shrink:0;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><div style="flex:1;min-width:0;"><p style="font-weight:600;font-size:13px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${r.resource_name||'Reserved'}</p><p style="font-family:var(--mono);font-size:11px;color:var(--p);margin-top:1px;">${r.start_time?to12h(r.start_time)+' – '+to12h(r.end_time):'All day'}</p></div><span style="padding:2px 8px;border-radius:999px;font-size:9px;font-weight:700;text-transform:uppercase;background:${cbg};color:${cfg};flex-shrink:0;">${s.charAt(0).toUpperCase()+s.slice(1)}</span>`;
      list.appendChild(row);
    });
  }else{empty.style.display='block';}
  document.getElementById('dateModal').classList.add('show');
  document.body.style.overflow='hidden';
}
function closeDateModal(){document.getElementById('dateModal').classList.remove('show');document.body.style.overflow='';}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeDateModal();});

/* ════ COUNTDOWN TIMER ════ */
function initTimer(){
  const banner=document.getElementById('timerBanner'),titleEl=document.getElementById('timerTitle'),subEl=document.getElementById('timerSub'),
        hEl=document.getElementById('tdH'),mEl=document.getElementById('tdM'),sEl=document.getElementById('tdS'),
        iconW=document.getElementById('timerIconWrap'),pw=document.getElementById('timerPW'),pf=document.getElementById('timerPF');
  const mkSvg=(path,sw)=>`<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="${sw}" style="width:16px;height:16px;flex-shrink:0;">${path}</svg>`;
  const icons={
    urgent:mkSvg('<path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>','1.8'),
    warning:mkSvg('<path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2"/>','1.8'),
    safe:mkSvg('<path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>','1.8'),
  };
  function findTarget(){
    const now=Date.now();let active=null,upcoming=null;
    approvedRes.forEach(r=>{
      if(!r.reservation_date||!r.start_time||!r.end_time)return;
      const start=new Date(r.reservation_date+'T'+r.start_time).getTime();
      const end=new Date(r.reservation_date+'T'+r.end_time).getTime();
      const minsToStart=(start-now)/60000;const minsToEnd=(end-now)/60000;
      if(now>=start&&now<end&&!active)active={r,start,end,mode:'active',minsLeft:minsToEnd};
      if(!upcoming&&minsToStart>0&&minsToStart<=30)upcoming={r,start,end,mode:'upcoming',minsLeft:minsToStart};
    });
    return active||upcoming||null;
  }
  function tick(){
    const target=findTarget();if(!target){banner.style.display='none';return;}
    const{r,start,end,mode,minsLeft}=target;const now=Date.now();
    const diff=Math.max(0,(mode==='active'?end:start)-now);
    const h=Math.floor(diff/3600000),m=Math.floor((diff%3600000)/60000),s=Math.floor((diff%60000)/1000);
    hEl.textContent=String(h).padStart(2,'0');mEl.textContent=String(m).padStart(2,'0');sEl.textContent=String(s).padStart(2,'0');
    banner.classList.remove('urgent','warning','safe');
    if(mode==='active'){
      if(minsLeft<=10){banner.classList.add('urgent');iconW.innerHTML=icons.urgent;}
      else if(minsLeft<=20){banner.classList.add('warning');iconW.innerHTML=icons.warning;}
      else{banner.classList.add('safe');iconW.innerHTML=icons.safe;}
      titleEl.textContent=minsLeft<=10?'⚠ Reservation ends very soon!':'Your reservation is active';
      subEl.textContent=`${r.resource_name||'Resource'} · Ends at ${(r.end_time||'').substring(0,5)}`;
      const pct=Math.min(100,Math.max(0,((now-start)/(end-start))*100));
      pw.style.display='block';pf.style.width=pct.toFixed(1)+'%';
    }else{
      banner.classList.add('safe');iconW.innerHTML=icons.safe;
      titleEl.textContent='Your reservation starts soon';
      subEl.textContent=`${r.resource_name||'Resource'} · Starts at ${(r.start_time||'').substring(0,5)}`;
      pw.style.display='none';
    }
    banner.style.display='block';
  }
  tick();setInterval(tick,1000);
}

/* ════ LOGIN TOAST ════ */
function showLoginToast(){
  const key=`toast_<?= session()->get('user_id') ?>_`+new Date().toDateString();
  if(sessionStorage.getItem(key))return;sessionStorage.setItem(key,'1');
  const now=Date.now();let td=null;
  approvedRes.forEach(r=>{
    if(!r.reservation_date||!r.start_time||!r.end_time)return;
    const start=new Date(r.reservation_date+'T'+r.start_time).getTime();
    const end=new Date(r.reservation_date+'T'+r.end_time).getTime();
    const minsToStart=(start-now)/60000;
    const today=new Date().toDateString();const resDay=new Date(r.reservation_date+'T00:00:00').toDateString();
    if(now>=start&&now<end&&!td)td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8" style="width:13px;height:13px;"><polygon points="5 3 19 12 5 21 5 3"/></svg>',bg:'rgba(37,99,235,.2)',title:'Active reservation now!',body:`${r.resource_name||'Resource'} ends at ${(r.end_time||'').substring(0,5)}`};
    if(!td&&resDay===today&&minsToStart>0&&minsToStart<=120)td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8" style="width:13px;height:13px;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>',bg:'rgba(217,119,6,.2)',title:`In ${Math.round(minsToStart)} min`,body:`${r.resource_name||'Resource'} · ${(r.start_time||'').substring(0,5)} – ${(r.end_time||'').substring(0,5)}`};
    if(!td&&resDay===today){const fmt=t=>{const[h,m]=t.split(':');const hr=+h;return`${hr%12||12}:${m} ${hr<12?'AM':'PM'}`;};td={icon:'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8" style="width:13px;height:13px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/></svg>',bg:'rgba(37,99,235,.2)',title:'Reservation today',body:`${r.resource_name||'Resource'} · ${fmt(r.start_time)} – ${fmt(r.end_time)}`};}
  });
  if(!td)return;
  const toast=document.getElementById('loginToast');
  document.getElementById('toastIcon').innerHTML=td.icon;
  document.getElementById('toastIcon').style.background=td.bg;
  document.getElementById('toastTitle').textContent=td.title;
  document.getElementById('toastBody').textContent=td.body;
  setTimeout(()=>toast.classList.add('show'),900);
  setTimeout(()=>toast.classList.remove('show'),7500);
}
function dismissToast(){document.getElementById('loginToast').classList.remove('show');}

/* ════ AI BOOK FINDER ════ */
async function doRagSearch(){
  const query=document.getElementById('ragInput').value.trim();if(query.length<2)return;
  const skel=document.getElementById('ragSkel'),res=document.getElementById('ragResult'),
        err=document.getElementById('ragErr'),btn=document.getElementById('ragBtn');
  res.classList.remove('show');err.style.display='none';skel.style.display='block';btn.disabled=true;
  try{
    const r=await fetch('/rag/suggest',{method:'POST',headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({query})});
    const d=await r.json();skel.style.display='none';btn.disabled=false;
    if(d.message&&!d.suggestion){err.textContent=d.message;err.style.display='block';return;}
    if(d.error&&!d.books){err.textContent=d.error;err.style.display='block';return;}
    document.getElementById('ragText').textContent=d.suggestion||'';
    const booksRow=document.getElementById('ragBooks');booksRow.innerHTML='';
    (d.books||[]).slice(0,4).forEach(b=>{
      const avail=(b.available_copies||0)>0;const chip=document.createElement('a');chip.href='/books';
      chip.style.cssText=`display:inline-flex;align-items:center;gap:4px;padding:4px 9px;border-radius:8px;font-size:10px;font-weight:600;border:1px solid;transition:all .15s;${avail?'background:var(--card);border-color:var(--p-border);color:var(--p);':'background:var(--input-bg);border-color:var(--border);color:var(--muted);'}`;
      const sp=document.createElement('span');sp.style.cssText='white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;';
      sp.textContent=b.title+(!avail?' (out)':'');chip.appendChild(sp);booksRow.appendChild(chip);
    });
    res.classList.add('show');
  }catch(e){skel.style.display='none';btn.disabled=false;err.textContent='Network error. Try again.';err.style.display='block';}
}

/* ════ FULLCALENDAR ════ */
document.addEventListener('DOMContentLoaded',()=>{
  loadNotifications();initTimer();showLoginToast();

  const byDate={};
  allResData.forEach(r=>{if(!r.reservation_date)return;if(!byDate[r.reservation_date])byDate[r.reservation_date]=[];byDate[r.reservation_date].push(r);});
  const colorMap={approved:'#10b981',pending:'#fbbf24',declined:'#f87171',canceled:'#f87171',claimed:'#a855f7'};
  const events=allResData.filter(r=>r.reservation_date).map(r=>{
    const isCl=r.claimed==1||r.status==='claimed'||!!r.claimed_at;
    const s=isCl?'claimed':(r.status||'pending').toLowerCase();
    const d=r.reservation_date.trim();
    return{title:r.resource_name||'Reservation',start:d+(r.start_time?'T'+r.start_time.substring(0,8):''),end:d+(r.end_time?'T'+r.end_time.substring(0,8):''),allDay:!r.start_time,backgroundColor:colorMap[s]||'#94a3b8',borderColor:'transparent',textColor:'#fff',extendedProps:{status:s}};
  });
  const w=window.innerWidth;
  const cal=new FullCalendar.Calendar(document.getElementById('calendar'),{
    initialView:w<480?'listWeek':'dayGridMonth',
    headerToolbar:{left:'prev,next',center:'title',right:'today'},
    events,height:w<640?'auto':360,eventDisplay:'block',eventMaxStack:2,
    dateClick:info=>openDateModal(info.dateStr,byDate[info.dateStr]||[]),
    eventClick:info=>{const d=info.event.startStr.split('T')[0];openDateModal(d,byDate[d]||[]);},
    dayCellDidMount:info=>{
      const d=info.date.toISOString().split('T')[0];const items=byDate[d];
      if(items&&items.length){
        const badge=document.createElement('div');
        badge.style.cssText='font-family:var(--mono);font-size:8px;font-weight:700;color:#fff;background:var(--p);border-radius:999px;width:14px;height:14px;display:flex;align-items:center;justify-content:center;margin-left:auto;margin-right:3px;';
        badge.textContent=items.length;
        info.el.querySelector('.fc-daygrid-day-top')?.appendChild(badge);
      }
    }
  });
  cal.render();
});
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>