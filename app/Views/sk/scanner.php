<?php
date_default_timezone_set('Asia/Manila');
$page = 'scanner';

$reservationModel = new \App\Models\ReservationModel();
$allReservations  = $reservationModel
    ->select('reservations.*, resources.name as resource_name, users.name as visitor_name, users.email as user_email')
    ->join('resources', 'resources.id = reservations.resource_id', 'left')
    ->join('users',     'users.id = reservations.user_id',         'left')
    ->orderBy('reservations.created_at', 'DESC')
    ->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Scanner | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <style>
        body { font-family:'Plus Jakarta Sans',sans-serif; background-color:#f1f5f9; background-image:radial-gradient(ellipse 900px 500px at 75% -5%,rgba(22,163,74,0.06) 0%,transparent 65%),radial-gradient(ellipse 600px 400px at 0% 100%,rgba(22,163,74,0.04) 0%,transparent 60%); background-attachment:fixed; color:#1e293b; }

        .sidebar-card { background:white; border-radius:32px; border:1px solid #e2e8f0; height:calc(100vh - 48px); position:sticky; top:24px; box-shadow:0 4px 24px rgba(0,0,0,0.06),0 1px 3px rgba(0,0,0,0.04); display:flex; flex-direction:column; overflow:hidden; }
        .sidebar-header { flex-shrink:0; padding:20px 20px 16px; border-bottom:1px solid #f1f5f9; }
        .sidebar-nav { flex:1; overflow-y:auto; overflow-x:hidden; padding:10px; }
        .sidebar-nav::-webkit-scrollbar { width:4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:4px; }
        .sidebar-footer { flex-shrink:0; padding:16px; border-top:1px solid #f1f5f9; }
        .sidebar-item { transition:all 0.18s; display:flex; align-items:center; gap:14px; padding:12px 18px; border-radius:16px; font-weight:600; font-size:.875rem; text-decoration:none; color:#64748b; }
        .sidebar-item:hover { background:#f0fdf4; color:#16a34a; }
        .sidebar-item.active { background:#16a34a; color:white; box-shadow:0 8px 20px -4px rgba(22,163,74,0.35); }
        .sidebar-item i { width:20px; text-align:center; font-size:1rem; flex-shrink:0; }

        .mobile-nav-pill { position:fixed; bottom:calc(16px + env(safe-area-inset-bottom,0px)); left:50%; transform:translateX(-50%); width:92%; max-width:600px; background:rgba(15,23,42,0.97); backdrop-filter:blur(12px); border-radius:24px; padding:6px; z-index:100; box-shadow:0 20px 25px -5px rgba(0,0,0,0.3); }
        .mobile-scroll-container { display:flex; gap:4px; overflow-x:auto; -webkit-overflow-scrolling:touch; scrollbar-width:none; }
        .mobile-scroll-container::-webkit-scrollbar { display:none; }

        .content-card { background:white; border-radius:32px; border:1px solid #e2e8f0; box-shadow:0 4px 24px rgba(0,0,0,0.04),0 1px 3px rgba(0,0,0,0.03); padding:1.5rem; }

        #scanner-viewport { width:100%; border-radius:20px; overflow:hidden; border:2px solid #e2e8f0; min-height:280px; background:#f8fafc; display:flex; align-items:center; justify-content:center; position:relative; transition:border-color 0.3s; }
        @media (min-width:640px) { #scanner-viewport { min-height:340px; } }
        #scanner-viewport.active { border-color:#86efac; }

        #scan-overlay { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; pointer-events:none; }
        .scan-frame { width:200px; height:200px; position:relative; }
        @media (min-width:640px) { .scan-frame { width:220px; height:220px; } }
        .scan-frame::before,.scan-frame::after { content:''; position:absolute; width:36px; height:36px; border-color:#22c55e; border-style:solid; }
        .scan-frame::before { top:0; left:0; border-width:3px 0 0 3px; border-radius:4px 0 0 0; }
        .scan-frame::after  { bottom:0; right:0; border-width:0 3px 3px 0; border-radius:0 0 4px 0; }
        .scan-frame-tr,.scan-frame-bl { position:absolute; width:36px; height:36px; border-color:#22c55e; border-style:solid; }
        .scan-frame-tr { top:0; right:0; border-width:3px 3px 0 0; border-radius:0 4px 0 0; }
        .scan-frame-bl { bottom:0; left:0; border-width:0 0 3px 3px; border-radius:0 0 0 4px; }
        .scan-line { position:absolute; left:8px; right:8px; height:2px; background:linear-gradient(90deg,transparent,#22c55e,transparent); top:8px; animation:scanLine 2s ease-in-out infinite; border-radius:1px; }
        @keyframes scanLine { 0%{top:8px;opacity:0} 10%{opacity:1} 90%{opacity:1} 100%{top:calc(100% - 10px);opacity:0} }

        .result-idle    { background:#f8fafc; border-color:#e2e8f0; }
        .result-success { background:#f0fdf4; border-color:#86efac; }
        .result-warning { background:#fffbeb; border-color:#fcd34d; }
        .result-error   { background:#fff1f2; border-color:#fca5a5; }
        .result-claimed { background:#faf5ff; border-color:#d8b4fe; }

        @keyframes flashGreen { 0%{box-shadow:0 0 0 0 rgba(22,163,74,0.5)} 70%{box-shadow:0 0 0 20px rgba(22,163,74,0)} 100%{box-shadow:0 0 0 0 rgba(22,163,74,0)} }
        .scan-flash { animation:flashGreen 0.6s ease; }

        input[type="text"] { font-family:'Plus Jakarta Sans',sans-serif; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.75rem 1rem; font-size:0.875rem; background:#f8fafc; color:#1e293b; transition:all 0.2s; width:100%; }
        input[type="text"]:focus { outline:none; border-color:#16a34a; background:white; box-shadow:0 0 0 4px rgba(22,163,74,0.1); }

        .stat-row { display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0.75rem; border-radius:12px; transition:background 0.15s; }
        .stat-row:hover { background:#f8fafc; }

        .cam-status { display:flex; align-items:center; gap:6px; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; }
        .cam-dot { width:7px; height:7px; border-radius:50%; background:#94a3b8; transition:background 0.3s; }
        .cam-dot.live { background:#22c55e; box-shadow:0 0 6px rgba(34,197,94,0.6); animation:livePulse 1.5s ease-in-out infinite; }
        @keyframes livePulse { 0%,100%{opacity:1} 50%{opacity:0.5} }

        .btn-start { background:linear-gradient(135deg,#16a34a 0%,#15803d 100%); color:white; box-shadow:0 4px 12px rgba(22,163,74,0.3); transition:all 0.18s; }
        .btn-start:hover { box-shadow:0 8px 20px rgba(22,163,74,0.4); transform:translateY(-1px); }
        .btn-stop { background:#f1f5f9; color:#475569; border:1.5px solid #e2e8f0; transition:all 0.18s; }
        .btn-stop:hover { background:#e2e8f0; color:#1e293b; }
        .btn-verify { background:#0f172a; color:white; transition:all 0.18s; }
        .btn-verify:hover { background:#1e293b; transform:translateY(-1px); }

        .history-item { transition:all 0.18s; border:1.5px solid #f1f5f9; }
        .history-item:hover { border-color:#bbf7d0; background:#f0fdf4; transform:translateX(2px); }

        .result-overlay { display:none; position:fixed; inset:0; z-index:200; align-items:flex-end; justify-content:center; }
        .result-overlay.open { display:flex; animation:fadeBg 0.18s ease; }
        @keyframes fadeBg { from{opacity:0} to{opacity:1} }
        .result-overlay-bg { position:absolute; inset:0; background:rgba(15,23,42,0.45); backdrop-filter:blur(6px); }
        .result-sheet { position:relative; width:100%; background:white; border-radius:28px 28px 0 0; max-height:88vh; overflow-y:auto; z-index:1; animation:sheetUp 0.26s cubic-bezier(0.34,1.2,0.64,1) both; box-shadow:0 -8px 32px rgba(0,0,0,0.14); }
        @keyframes sheetUp { from{opacity:0;transform:translateY(60px)} to{opacity:1;transform:none} }
        .result-sheet::-webkit-scrollbar { width:4px; }
        .result-sheet::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:4px; }
        .sheet-handle { width:40px; height:4px; background:#e2e8f0; border-radius:9999px; margin:12px auto 0; }

        @media (min-width:640px) { .result-overlay { display:none !important; } .result-panel-inline { display:block !important; } }
        .result-panel-inline { display:none; }

        .permission-error { background:#fff1f2; border:1.5px solid #fecdd3; border-radius:16px; padding:1.5rem; text-align:center; }

        #debugPanel { position:fixed; bottom:20px; left:20px; background:white; border:1px solid #e2e8f0; border-radius:16px; padding:12px; font-size:12px; max-width:420px; max-height:320px; overflow-y:auto; display:none; z-index:1000; box-shadow:0 8px 24px rgba(0,0,0,0.1); }
        .debug-show { display:block !important; }
        .debug-content { font-family:monospace; white-space:pre-wrap; word-break:break-all; }
    </style>
</head>
<body class="flex">

<?php
$navItems = [
    ['url'=>'/sk/dashboard',            'icon'=>'fa-house',           'label'=>'Dashboard',        'key'=>'dashboard'],
    ['url'=>'/sk/reservations',         'icon'=>'fa-calendar-alt',    'label'=>'All Reservations', 'key'=>'reservations'],
    ['url'=>'/sk/new-reservation',      'icon'=>'fa-plus',            'label'=>'New Reservation',  'key'=>'new-reservation'],
    ['url'=>'/sk/user-requests',        'icon'=>'fa-users',           'label'=>'User Requests',    'key'=>'user-requests'],
    ['url'=>'/sk/my-reservations',      'icon'=>'fa-calendar',        'label'=>'My Reservations',  'key'=>'my-reservations'],
    ['url'=>'/sk/claimed-reservations', 'icon'=>'fa-check-double',    'label'=>'Claimed',          'key'=>'claimed-reservations'],
    ['url'=>'/sk/books',                'icon'=>'fa-book-open',       'label'=>'Library',          'key'=>'books'],
    ['url'=>'/sk/scanner',              'icon'=>'fa-qrcode',          'label'=>'Scanner',          'key'=>'scanner'],
    ['url'=>'/sk/profile',              'icon'=>'fa-regular fa-user', 'label'=>'Profile',          'key'=>'profile'],
];
?>

<div id="debugPanel">
    <strong>Debug Info:</strong>
    <div id="debugContent" class="debug-content"></div>
    <button onclick="clearDebug()" class="mt-2 text-xs bg-slate-100 px-2 py-1 rounded">Clear</button>
    <button onclick="showAllReservations()" class="mt-2 text-xs bg-green-100 px-2 py-1 rounded ml-1">Show All</button>
</div>

<div class="result-overlay" id="resultOverlay">
    <div class="result-overlay-bg" onclick="closeResultSheet()"></div>
    <div class="result-sheet" id="resultSheet">
        <div class="sheet-handle"></div>
        <div id="resultSheetBody" class="px-6 pt-3 pb-8"></div>
    </div>
</div>

<!-- ════════ SIDEBAR ════════ -->
<aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">SK Officer</span>
            <h1 class="text-2xl font-extrabold text-slate-800 mt-0.5">Portal<span class="text-green-600">.</span></h1>
        </div>
        <nav class="sidebar-nav space-y-1">
            <?php foreach ($navItems as $item):
                $active = ($page == $item['key']) ? 'active' : '';
            ?>
                <a href="<?= $item['url'] ?>" class="sidebar-item <?= $active ?>">
                    <i class="fa-solid <?= $item['icon'] ?>"></i>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all text-sm">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
            </a>
        </div>
    </div>
</aside>

<!-- ════════ MOBILE NAV ════════ -->
<nav class="lg:hidden mobile-nav-pill">
    <div class="mobile-scroll-container text-white px-2">
        <?php foreach ($navItems as $item):
            $btnClass = ($page == $item['key']) ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
        ?>
            <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
        <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
            <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
            <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
        </a>
    </div>
</nav>

<!-- ════════ MAIN ════════ -->
<main class="flex-1 min-w-0 p-4 lg:p-12 pb-32">
    <header class="flex justify-between items-center mb-6 lg:mb-10">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">SK Portal</p>
            <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">QR Scanner</h2>
            <p class="text-slate-500 font-medium text-sm mt-0.5">Verify reservations in real-time.</p>
        </div>
        <div class="cam-status text-slate-400">
            <span class="cam-dot" id="camDot"></span>
            <span id="camStatusLabel">Camera off</span>
        </div>
    </header>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 lg:gap-8">
        <div class="xl:col-span-2 space-y-5">
            <div class="content-card">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-5 gap-3">
                    <h3 class="text-base font-extrabold text-slate-800">Live Camera Feed</h3>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button id="startBtn" onclick="startScanner()" class="btn-start flex-1 sm:flex-none px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 justify-center">
                            <i class="fa-solid fa-camera"></i> Start Camera
                        </button>
                        <button id="switchBtn" onclick="switchCamera()" style="display:none" class="btn-stop flex-none px-3 py-2.5 rounded-xl font-bold text-sm flex items-center justify-center" title="Switch Camera">
                            <i class="fa-solid fa-camera-rotate"></i>
                        </button>
                        <button id="stopBtn" onclick="stopScanner()" style="display:none" class="btn-stop flex-1 sm:flex-none px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 justify-center">
                            <i class="fa-solid fa-stop"></i> Stop
                        </button>
                    </div>
                </div>
                <div id="scanner-viewport">
                    <div class="text-center p-8">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400"><i class="fa-solid fa-qrcode text-2xl"></i></div>
                        <p class="text-slate-500 font-semibold text-sm">Camera is inactive.</p>
                        <p class="text-slate-400 text-xs mt-1">Tap "Start Camera" to begin scanning.</p>
                    </div>
                </div>
                <div class="mt-5 pt-5 border-t border-slate-100">
                    <label class="block text-[0.68rem] font-black uppercase tracking-widest text-slate-400 mb-2">Manual Code Entry</label>
                    <div class="flex gap-2">
                        <input type="text" id="manualCode" placeholder="Paste or type the reservation code…" onkeydown="if(event.key==='Enter') processCode(this.value)">
                        <button onclick="processCode(document.getElementById('manualCode').value)" class="btn-verify px-5 py-3 rounded-xl font-bold text-sm whitespace-nowrap flex-shrink-0">Verify</button>
                    </div>
                </div>
            </div>
            <div id="resultPanelInline" class="result-panel-inline content-card !p-0 border-2 result-idle overflow-hidden transition-all duration-300 hidden">
                <div id="resultBodyInline" class="p-6"></div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="content-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-extrabold text-slate-800 text-sm">Recent Scans</h3>
                    <button onclick="clearHistory()" class="text-red-400 text-xs font-bold hover:bg-red-50 hover:text-red-600 px-2 py-1 rounded-lg transition">Clear</button>
                </div>
                <div id="historyList" class="space-y-2"><p class="text-slate-400 text-sm text-center py-6 italic">No recent scans</p></div>
            </div>
            <div class="content-card">
                <h3 class="font-extrabold text-slate-800 text-sm mb-3">Session Stats</h3>
                <div class="space-y-1">
                    <div class="stat-row"><span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Scanned</span><span id="statTotal" class="font-black text-slate-800">0</span></div>
                    <div class="stat-row"><span class="text-xs font-black uppercase tracking-widest text-emerald-500">Approved / Valid</span><span id="statApproved" class="font-black text-emerald-600">0</span></div>
                    <div class="stat-row"><span class="text-xs font-black uppercase tracking-widest text-purple-500">Already Claimed</span><span id="statClaimed" class="font-black text-purple-600">0</span></div>
                    <div class="stat-row"><span class="text-xs font-black uppercase tracking-widest text-amber-500">Pending</span><span id="statPending" class="font-black text-amber-600">0</span></div>
                    <div class="stat-row"><span class="text-xs font-black uppercase tracking-widest text-red-400">Invalid</span><span id="statInvalid" class="font-black text-red-500">0</span></div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// ── PHP data ──────────────────────────────────────────────────────
const reservations = <?= json_encode($allReservations) ?>;
const validateUrl  = '<?= base_url("sk/validateETicket") ?>';
const csrfToken    = '<?= csrf_hash() ?>';
const csrfName     = '<?= csrf_token() ?>';

// ── State ─────────────────────────────────────────────────────────
let videoStream=null, videoEl=null, canvasEl=null, rafId=null;
let scanHistory=[], lastScanned=null, currentCode=null, currentReservation=null;
let stats={total:0,approved:0,claimed:0,pending:0,invalid:0};
let debugMode=false, scanning=false, availableCameras=[], currentCamIndex=0;

// ── Debug helpers ─────────────────────────────────────────────────
function debug(msg,data){if(!debugMode)return;const c=document.getElementById('debugContent'),t=new Date().toLocaleTimeString();c.innerHTML=`[${t}] ${msg}`+(data?'\n'+JSON.stringify(data,null,2):'')+'\n'+c.innerHTML;}
function clearDebug(){document.getElementById('debugContent').innerHTML='';}
function showAllReservations(){debug('All:',reservations.map(r=>({id:r.id,code:r.e_ticket_code,status:r.status,claimed:r.claimed})));}
document.addEventListener('keydown',e=>{if(e.ctrlKey&&e.shiftKey&&e.key==='D'){e.preventDefault();debugMode=!debugMode;document.getElementById('debugPanel').classList.toggle('debug-show');if(debugMode)showAllReservations();}});

// ── BUG FIX #1: Robust claimed check ─────────────────────────────
// PostgreSQL booleans JSON-encode as true/false (not 1/0).
// parseInt(true)=NaN, parseInt('t')=NaN — both fail the old ===1 check.
// Every claimed ticket was showing as "Access Granted" and could be re-validated.
function isClaimed(res) {
    return [true, 1, 't', 'true', '1'].includes(res.claimed);
}

function setCamStatus(live){document.getElementById('camDot').classList.toggle('live',live);document.getElementById('camStatusLabel').textContent=live?'LIVE':'Camera off';}

function buildScannerUI(){
    const vp=document.getElementById('scanner-viewport');
    vp.innerHTML='';vp.classList.add('active');
    videoEl=document.createElement('video');
    videoEl.setAttribute('autoplay','');videoEl.setAttribute('muted','');videoEl.setAttribute('playsinline','');
    videoEl.style.cssText='width:100%;height:100%;object-fit:cover;border-radius:18px;display:block;';
    vp.appendChild(videoEl);
    const overlay=document.createElement('div');
    overlay.id='scan-overlay';
    overlay.innerHTML='<div class="scan-frame"><div class="scan-frame-tr"></div><div class="scan-frame-bl"></div><div class="scan-line"></div></div>';
    vp.appendChild(overlay);
    canvasEl=document.createElement('canvas');
    canvasEl.style.display='none';
    document.body.appendChild(canvasEl);
}

async function startScanner(camIndex) {
    if (location.protocol!=='https:'&&location.hostname!=='localhost'&&location.hostname!=='127.0.0.1') {
        showCameraError('Camera requires HTTPS.'); return;
    }
    if (!navigator.mediaDevices?.getUserMedia) {
        showCameraError('Your browser does not support camera access.'); return;
    }
    document.getElementById('startBtn').style.display='none';
    document.getElementById('stopBtn').style.display='inline-flex';
    buildScannerUI();

    try {
        const tmp=await navigator.mediaDevices.getUserMedia({video:{facingMode:{ideal:'environment'}}});
        tmp.getTracks().forEach(t=>t.stop());
    } catch(e) { showCameraError('Could not access camera. Please allow camera permissions.'); return; }

    try {
        const devices=await navigator.mediaDevices.enumerateDevices();
        availableCameras=devices.filter(d=>d.kind==='videoinput');
        debug('Cameras:',availableCameras.map(c=>c.label||c.deviceId));
    } catch(e) { availableCameras=[]; }

    if (camIndex!=null) { currentCamIndex=camIndex; }
    else {
        const rearIdx=availableCameras.findIndex(c=>/back|rear|environment/i.test(c.label));
        currentCamIndex=rearIdx>=0?rearIdx:0;
    }
    document.getElementById('switchBtn').style.display=availableCameras.length>1?'inline-flex':'none';
    await openCamera(currentCamIndex);
}

async function openCamera(index) {
    if (videoStream) {
        scanning=false;
        if(rafId){cancelAnimationFrame(rafId);rafId=null;}
        videoStream.getTracks().forEach(t=>t.stop());
        videoStream=null;
    }
    const device=availableCameras[index];
    let stream=null;
    const attempts=[
        device?.deviceId?{video:{deviceId:{exact:device.deviceId},width:{ideal:1280},height:{ideal:720}}}:null,
        {video:{facingMode:{ideal:'environment'},width:{ideal:1280},height:{ideal:720}}},
        {video:{facingMode:'environment'}},
        {video:true}
    ].filter(Boolean);

    for (const constraint of attempts) {
        try { stream=await navigator.mediaDevices.getUserMedia(constraint); if(stream) break; }
        catch(e) { debug('Constraint failed:',e.message); }
    }
    if (!stream) { showCameraError('Could not open camera. Please check permissions.'); return; }

    videoStream=stream;
    videoEl.srcObject=stream;
    videoEl.onloadedmetadata=()=>{
        videoEl.play().then(()=>{
            scanning=true;setCamStatus(true);
            debug('Camera opened:',device?(device.label||device.deviceId):'default');
            requestAnimationFrame(scanFrame);
        }).catch(e=>showCameraError('Could not play video: '+e.message));
    };
    videoEl.onerror=()=>showCameraError('Video error');
}

async function switchCamera(){
    if(availableCameras.length<2)return;
    const btn=document.getElementById('switchBtn');
    btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i>';
    currentCamIndex=(currentCamIndex+1)%availableCameras.length;
    await openCamera(currentCamIndex);
    btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-camera-rotate"></i>';
}

function scanFrame(){
    if(!scanning||!videoEl)return;
    if(videoEl.readyState>=2){
        const w=videoEl.videoWidth,h=videoEl.videoHeight;
        if(w&&h){
            canvasEl.width=w;canvasEl.height=h;
            try{
                const ctx=canvasEl.getContext('2d');
                ctx.drawImage(videoEl,0,0,w,h);
                const code=jsQR(ctx.getImageData(0,0,w,h).data,w,h,{inversionAttempts:'dontInvert'});
                if(code?.data&&code.data!==lastScanned){
                    lastScanned=code.data;
                    setTimeout(()=>{lastScanned=null;},3000);
                    const vp=document.getElementById('scanner-viewport');
                    vp.classList.add('scan-flash');
                    vp.addEventListener('animationend',()=>vp.classList.remove('scan-flash'),{once:true});
                    processCode(code.data);
                }
            }catch(e){debug('scanFrame error:',e.message);}
        }
    }
    rafId=requestAnimationFrame(scanFrame);
}

function stopScanner(){
    scanning=false;
    if(rafId){cancelAnimationFrame(rafId);rafId=null;}
    if(videoStream){videoStream.getTracks().forEach(t=>t.stop());videoStream=null;}
    if(canvasEl?.parentNode){canvasEl.parentNode.removeChild(canvasEl);canvasEl=null;}
    videoEl=null;availableCameras=[];
    document.getElementById('switchBtn').style.display='none';
    setCamStatus(false);resetViewport();
}

function resetViewport(){
    const vp=document.getElementById('scanner-viewport');
    vp.classList.remove('active');
    vp.innerHTML='<div class="text-center p-8"><div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400"><i class="fa-solid fa-qrcode text-2xl"></i></div><p class="text-slate-500 font-semibold text-sm">Camera is inactive.</p><p class="text-slate-400 text-xs mt-1">Tap "Start Camera" to begin scanning.</p></div>';
    document.getElementById('startBtn').style.display='inline-flex';
    document.getElementById('stopBtn').style.display='none';
}

function showCameraError(msg){
    const vp=document.getElementById('scanner-viewport');
    vp.classList.remove('active');
    vp.innerHTML=`<div class="permission-error w-full"><div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-red-400"><i class="fa-solid fa-camera-slash text-xl"></i></div><p class="text-red-600 font-bold text-sm mb-1">Camera unavailable</p><p class="text-slate-500 text-xs">${msg}</p><button onclick="startScanner()" class="mt-4 btn-start px-5 py-2 rounded-xl font-bold text-sm flex items-center gap-2 mx-auto"><i class="fa-solid fa-rotate-right"></i> Try Again</button></div>`;
    document.getElementById('startBtn').style.display='inline-flex';
    document.getElementById('stopBtn').style.display='none';
    setCamStatus(false);
}

// ── Core: process a scanned/entered code ─────────────────────────
function processCode(code){
    code=(code||'').trim();
    if(!code)return;
    debug('Processing:',code);
    currentCode=code;
    addToHistory(code);
    const res=findReservationByCode(code);
    if(!res){
        stats.invalid++;updateStats();
        showResult('error','fa-circle-xmark','text-red-500','bg-red-50','Code Not Recognised',`"${code}" doesn't match any reservation.`,[],false);
        return;
    }
    debug('Found:',{id:res.id,status:res.status,claimed:res.claimed});
    currentReservation=res;

    let pcLabel='—';
    if(res.pc_numbers){
        try{const a=typeof res.pc_numbers==='string'?JSON.parse(res.pc_numbers):res.pc_numbers;pcLabel=Array.isArray(a)?a.join(', '):a;}
        catch{pcLabel=res.pc_numbers;}
    }else if(res.pc_number){pcLabel=res.pc_number;}

    // ── BUG FIX #1 applied: use isClaimed() instead of parseInt(res.claimed)===1
    const effectiveStatus = isClaimed(res) ? 'claimed' : res.status;

    const cfgMap={
        approved:{state:'success',icon:'fa-circle-check',iconColor:'text-emerald-600',iconBg:'bg-emerald-50',title:'Access Granted',  sub:'Reservation is approved and valid.'},
        pending: {state:'warning',icon:'fa-clock',       iconColor:'text-amber-600',  iconBg:'bg-amber-50',  title:'Pending Approval',sub:'This reservation has not been approved yet.'},
        declined:{state:'error',  icon:'fa-ban',         iconColor:'text-red-500',    iconBg:'bg-red-50',    title:'Access Denied',  sub:'This reservation has been declined.'},
        canceled:{state:'error',  icon:'fa-ban',         iconColor:'text-red-500',    iconBg:'bg-red-50',    title:'Access Denied',  sub:'This reservation was canceled.'},
        claimed: {state:'claimed',icon:'fa-check-double',iconColor:'text-purple-600', iconBg:'bg-purple-50', title:'Already Claimed',sub:'This ticket has already been used.'},
    };
    const cfg=cfgMap[effectiveStatus]||cfgMap.pending;

    if     (effectiveStatus==='approved') stats.approved++;
    else if(effectiveStatus==='claimed')  stats.claimed++;
    else if(effectiveStatus==='pending')  stats.pending++;
    else                                  stats.invalid++;
    updateStats();

    const details=[
        {label:'Reservation ID',value:'#'+res.id},
        {label:'Name',          value:res.visitor_name||res.full_name||'Guest'},
        {label:'Email',         value:res.visitor_email||res.user_email||'—'},
        {label:'Asset',         value:res.resource_name||`Resource #${res.resource_id}`},
        {label:'Workstation',   value:pcLabel},
        {label:'Date',          value:res.reservation_date},
        {label:'Time',          value:`${res.start_time} – ${res.end_time}`},
        {label:'Purpose',       value:res.purpose||'—'},
        {label:'E-Ticket Code', value:res.e_ticket_code||'—'},
        {label:'Status',        value:effectiveStatus.charAt(0).toUpperCase()+effectiveStatus.slice(1)},
    ];
    if(isClaimed(res)&&res.claimed_at) details.push({label:'Claimed At',value:new Date(res.claimed_at).toLocaleString()});

    showResult(cfg.state,cfg.icon,cfg.iconColor,cfg.iconBg,cfg.title,cfg.sub,details,effectiveStatus==='approved');
    document.getElementById('manualCode').value='';
}

function findReservationByCode(code){
    if(!code)return null;
    let r=reservations.find(r=>r.e_ticket_code===code); if(r)return r;
    if(!isNaN(code)){r=reservations.find(r=>r.id===parseInt(code)||String(r.id)===code); if(r)return r;}
    const m1=code.match(/(?:SK|ADMIN|RES)-(\d+)-/i); if(m1){r=reservations.find(r=>r.id===parseInt(m1[1])); if(r)return r;}
    const m2=code.match(/(?:SK|ADMIN|RES)(\d+)/i);   if(m2){r=reservations.find(r=>r.id===parseInt(m2[1])); if(r)return r;}
    for(const rv of reservations){if(rv.e_ticket_code&&code.includes(rv.e_ticket_code))return rv;}
    return null;
}

function buildResultHTML(state,icon,iconColor,iconBg,title,sub,details,showValidate,idSuffix){
    const rows=details.map(d=>`<div class="flex justify-between items-start py-2.5 border-b border-slate-100 last:border-0 gap-3"><span class="text-[0.65rem] font-black uppercase tracking-widest text-slate-400 flex-shrink-0 mt-0.5">${d.label}</span><span class="font-bold text-slate-800 text-sm text-right break-all">${d.value}</span></div>`).join('');
    const validateSection=showValidate?`<div class="mt-5 pt-5 border-t border-slate-100"><button id="validateBtn${idSuffix}" onclick="validateTicket()" class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2"><i class="fa-solid fa-circle-check"></i> Mark as Used / Check In</button></div>`:'';
    return `<div class="flex items-center gap-4 mb-5"><div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl flex-shrink-0 ${iconBg} ${iconColor}"><i class="fa-solid ${icon}"></i></div><div class="flex-1 min-w-0"><p class="font-extrabold text-base text-slate-900 leading-tight">${title}</p><p class="text-sm text-slate-500 font-medium mt-0.5">${sub}</p></div></div><div class="space-y-0">${rows}</div>${validateSection}`;
}

function showResult(state,icon,iconColor,iconBg,title,sub,details,showValidate){
    const isMobile=window.innerWidth<640;
    const html=buildResultHTML(state,icon,iconColor,iconBg,title,sub,details,showValidate,isMobile?'Sheet':'Inline');
    if(isMobile){
        document.getElementById('resultSheetBody').innerHTML=html;
        document.getElementById('resultOverlay').classList.add('open');
        document.body.style.overflow='hidden';
    }else{
        const panel=document.getElementById('resultPanelInline');
        panel.className=`result-panel-inline content-card !p-0 border-2 result-${state} overflow-hidden transition-all duration-300`;
        document.getElementById('resultBodyInline').innerHTML=html;
        panel.classList.remove('hidden');
        panel.style.display='block';
        panel.scrollIntoView({behavior:'smooth',block:'nearest'});
    }
}

function closeResultSheet(){document.getElementById('resultOverlay').classList.remove('open');document.body.style.overflow='';}

// ── BUG FIX #2: validateTicket updates local array ───────────────
// After a successful check-in, the local reservations[] must be updated
// so re-scanning the same QR immediately shows "Already Claimed"
// instead of "Access Granted" again.
function validateTicket(){
    if(!currentCode||!currentReservation)return;
    const isMobile=window.innerWidth<640;
    const btn=document.getElementById('validateBtn'+(isMobile?'Sheet':'Inline'));
    if(!btn)return;
    btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Checking in…';
    const codeToValidate=currentReservation.e_ticket_code||currentCode;
    const body=new URLSearchParams();
    body.append(csrfName,csrfToken);body.append('code',codeToValidate);
    fetch(validateUrl,{method:'POST',body,headers:{'X-Requested-With':'XMLHttpRequest','Content-Type':'application/x-www-form-urlencoded'}})
    .then(r=>r.json())
    .then(data=>{
        debug('Validate response:',data);
        if(data.status==='success'||data.updated){
            // ── Update local array so re-scan shows "Already Claimed"
            const localRes=reservations.find(r=>r.id===currentReservation.id);
            if(localRes){ localRes.claimed=true; localRes.claimed_at=new Date().toISOString(); }
            stats.approved=Math.max(0,stats.approved-1);stats.claimed++;updateStats();
            btn.innerHTML='<i class="fa-solid fa-circle-check"></i> Checked In!';
            btn.classList.replace('bg-green-600','bg-emerald-500');
            btn.disabled=true;
            const panel=document.getElementById(isMobile?'resultSheet':'resultPanelInline');
            if(panel&&!isMobile) panel.className='result-panel-inline content-card !p-0 border-2 result-claimed overflow-hidden transition-all duration-300';
            renderHistory();
        }else if(data.status==='error'&&(data.message||'').toLowerCase().includes('claimed')){
            const localRes=reservations.find(r=>r.id===currentReservation.id);
            if(localRes) localRes.claimed=true;
            stats.approved=Math.max(0,stats.approved-1);stats.claimed++;updateStats();
            btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-check-double"></i> Already claimed';
            btn.className='w-full py-3.5 bg-purple-500 text-white rounded-2xl font-bold text-sm flex items-center justify-center gap-2 cursor-default';
        }else{
            btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> '+(data.message||'Failed');
            btn.classList.replace('bg-green-600','bg-red-600');
        }
    })
    .catch(()=>{btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> Network error';btn.classList.replace('bg-green-600','bg-red-600');});
}

function addToHistory(code){
    const time=new Date().toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'});
    // Remove duplicate so latest scan bubbles to top with fresh time
    scanHistory=scanHistory.filter(h=>h.code!==code);
    scanHistory.unshift({code,time});
    if(scanHistory.length>10)scanHistory.pop();
    renderHistory();
}

function renderHistory(){
    const list=document.getElementById('historyList');
    if(!scanHistory.length){list.innerHTML='<p class="text-slate-400 text-sm text-center py-6 italic">No recent scans</p>';return;}
    const colorMap={approved:'bg-emerald-100 text-emerald-600',pending:'bg-amber-100 text-amber-600',declined:'bg-red-100 text-red-500',canceled:'bg-red-100 text-red-400',claimed:'bg-purple-100 text-purple-600'};
    const iconMap ={approved:'fa-check',pending:'fa-clock',declined:'fa-ban',canceled:'fa-ban',claimed:'fa-check-double'};
    list.innerHTML=scanHistory.map(item=>{
        const res=findReservationByCode(item.code);
        // ── BUG FIX #1 applied in history too
        const eff=res?(isClaimed(res)?'claimed':res.status):null;
        const sc=eff?(colorMap[eff]||'bg-slate-100 text-slate-400'):'bg-red-100 text-red-400';
        const si=eff?(iconMap[eff]||'fa-question'):'fa-xmark';
        return `<div class="history-item flex items-center justify-between p-3 bg-slate-50 rounded-2xl cursor-pointer" onclick="processCode('${item.code.replace(/'/g,"\\'")}')"><div class="overflow-hidden mr-3"><p class="font-bold text-slate-800 text-xs truncate">${item.code}</p><p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">${item.time}</p></div><div class="w-8 h-8 rounded-xl ${sc} flex items-center justify-center flex-shrink-0"><i class="fa-solid ${si} text-xs"></i></div></div>`;
    }).join('');
}

function clearHistory(){
    if(!scanHistory.length)return;
    if(!confirm('Clear all scan history?'))return;
    scanHistory=[];stats={total:0,approved:0,claimed:0,pending:0,invalid:0};
    updateStats();renderHistory();
    document.getElementById('resultPanelInline').classList.add('hidden');
    document.getElementById('resultPanelInline').style.display='none';
    closeResultSheet();currentReservation=null;currentCode=null;
}

function updateStats(){
    stats.total=scanHistory.length;
    document.getElementById('statTotal').textContent   =stats.total;
    document.getElementById('statApproved').textContent=stats.approved;
    document.getElementById('statClaimed').textContent =stats.claimed;
    document.getElementById('statPending').textContent =stats.pending;
    document.getElementById('statInvalid').textContent =stats.invalid;
}

document.addEventListener('visibilitychange',()=>{if(document.hidden&&scanning)stopScanner();});
window.addEventListener('beforeunload',()=>{if(scanning)stopScanner();});
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeResultSheet();});
renderHistory();
</script>
</body>
</html>