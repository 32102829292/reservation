<?php
/**
 * Views/user/books.php
 * Resident — Browse & Borrow Books + RAG Smart Suggestion
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<title>Library — Books</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#16a34a">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root { --green:#16a34a; --green-light:#f0fdf4; --slate-bg:#f8fafc; }
*, *::before, *::after { box-sizing:border-box; }
body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--slate-bg); color:#1e293b; margin:0; }

/* ── Sidebar ── */
.sidebar-card { background:white; border-radius:32px; border:1px solid #e2e8f0; height:calc(100vh - 48px); position:sticky; top:24px; box-shadow:0 4px 6px -1px rgba(0,0,0,.05); display:flex; flex-direction:column; overflow:hidden; width:100%; }
.sidebar-header { flex-shrink:0; padding:20px 16px 16px; border-bottom:1px solid #e2e8f0; }
.sidebar-nav { flex:1; overflow-y:auto; padding:8px; }
.sidebar-nav::-webkit-scrollbar { width:4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:4px; }
.sidebar-footer { flex-shrink:0; padding:16px; border-top:1px solid #e2e8f0; }
.sidebar-item { transition:all .2s; border-radius:20px; display:flex; align-items:center; gap:14px; padding:12px 20px; font-weight:600; font-size:.875rem; color:#64748b; text-decoration:none; }
.sidebar-item:hover { background:#f8fafc; color:var(--green); }
.sidebar-item.active { background:var(--green); color:white; box-shadow:0 10px 20px -5px rgba(22,163,74,.35); }
.sidebar-item .icon-wrap { width:20px; text-align:center; font-size:1rem; flex-shrink:0; }

/* ── Mobile Nav ── */
.mobile-nav-pill { position:fixed; bottom:20px; left:50%; transform:translateX(-50%); width:92%; max-width:480px; background:rgba(20,83,45,.97); backdrop-filter:blur(16px); border-radius:24px; padding:6px; z-index:100; box-shadow:0 20px 40px -10px rgba(0,0,0,.4); }
.mobile-scroll-container { display:flex; gap:4px; overflow-x:auto; -webkit-overflow-scrolling:touch; }
.mobile-scroll-container::-webkit-scrollbar { display:none; }

.dash-card { background:white; border-radius:28px; border:1px solid #e2e8f0; box-shadow:0 4px 6px -1px rgba(0,0,0,.03); }

/* ── Book card ── */
.book-card { background:white; border-radius:20px; border:1px solid #e2e8f0; overflow:hidden; transition:all .25s; display:flex; flex-direction:column; box-shadow:0 2px 8px -2px rgba(0,0,0,.06); cursor:pointer; }
.book-card:hover { transform:translateY(-4px); box-shadow:0 20px 40px -8px rgba(0,0,0,.15); border-color:#bbf7d0; }
.book-card:hover .cover-overlay { opacity:1; }
.book-card.rag-hl { border-color:var(--green); box-shadow:0 0 0 3px rgba(22,163,74,.15); }

/* Cover — overflow:hidden clips badges properly */
.book-cover { height:160px; background:linear-gradient(145deg,#f0fdf4,#dcfce7); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
.book-cover img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.cover-ph { font-size:3rem; font-weight:900; color:rgba(22,163,74,.25); position:relative; z-index:1; }
.cover-overlay { position:absolute; inset:0; background:rgba(20,83,45,.55); backdrop-filter:blur(2px); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .2s; z-index:3; }
.cover-overlay-btn { display:flex; align-items:center; gap:6px; background:white; color:var(--green); font-weight:800; font-size:.72rem; padding:.45rem 1rem; border-radius:999px; box-shadow:0 4px 12px rgba(0,0,0,.2); white-space:nowrap; }

/* Cover badges — constrained width so long genres don't overflow */
.cover-genre-badge { position:absolute; top:8px; left:8px; z-index:2; max-width:calc(100% - 72px); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.cover-avail-badge { position:absolute; top:8px; right:8px; z-index:2; flex-shrink:0; }

/* Tags */
.tag { display:inline-flex; align-items:center; gap:4px; padding:.2rem .6rem; border-radius:999px; font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.04em; }
.tag-pending  { background:#fef3c7; color:#92400e; }
.tag-approved { background:#dcfce7; color:#166534; }
.tag-returned { background:#e0e7ff; color:#3730a3; }
.tag-rejected { background:#fee2e2; color:#991b1b; }
.tag-available { background:#dcfce7; color:#166534; }
.tag-out       { background:#fee2e2; color:#991b1b; }

/* ── Modal backdrop ── */
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(15,23,42,.6); backdrop-filter:blur(6px); z-index:300; padding:1.25rem; overflow-y:auto; align-items:flex-start; justify-content:center; }
.modal-backdrop.show { display:flex; animation:fadeIn .15s ease; }
@keyframes fadeIn { from{opacity:0} to{opacity:1} }

/* ── Modal boxes ── */
.modal-card  { background:white; border-radius:28px; width:100%; max-width:480px; padding:2rem; margin:auto; animation:slideUp .22s cubic-bezier(.34,1.56,.64,1) both; max-height:92vh; overflow-y:auto; }
.detail-card { background:white; border-radius:32px; width:100%; max-width:540px; overflow:hidden; margin:auto; animation:slideUp .22s cubic-bezier(.34,1.56,.64,1) both; box-shadow:0 32px 64px -16px rgba(0,0,0,.3); max-height:92vh; overflow-y:auto; }
@keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:none;opacity:1} }

/* Sheet handle — mobile only */
.sheet-handle { display:none; width:40px; height:4px; background:#e2e8f0; border-radius:9999px; margin:10px auto 0; flex-shrink:0; }

/* Bottom-sheet on mobile */
@media(max-width:639px) {
    .modal-backdrop { padding:0; align-items:flex-end !important; }
    .modal-backdrop .modal-card,
    .modal-backdrop .detail-card { max-width:100%; width:100%; margin:0; border-radius:28px 28px 0 0; max-height:92vh; animation:sheetUp .28s cubic-bezier(.32,.72,0,1) both; }
    .sheet-handle { display:block; }
}
@keyframes sheetUp { from{opacity:0;transform:translateY(100%)} to{opacity:1;transform:translateY(0)} }

/* ── Book detail internals ── */
.detail-cover { height:200px; background:linear-gradient(145deg,#f0fdf4,#bbf7d0); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; flex-shrink:0; }
.detail-cover img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.detail-cover-ph { font-size:5rem; font-weight:900; color:rgba(22,163,74,.2); }
.detail-body { padding:1.75rem 1.75rem 2rem; }

.info-row { display:flex; align-items:flex-start; gap:12px; padding:.6rem 0; border-bottom:1px solid #f1f5f9; }
.info-row:last-of-type { border-bottom:none; }
.info-icon { width:32px; height:32px; border-radius:10px; background:#f0fdf4; color:var(--green); display:flex; align-items:center; justify-content:center; font-size:.75rem; flex-shrink:0; margin-top:1px; }
.info-label { font-size:.65rem; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:2px; }
.info-value { font-size:.88rem; font-weight:700; color:#1e293b; }

/* RAG skeleton */
.rag-skeleton .sk-line { height:12px; background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size:200% 100%; border-radius:6px; animation:shimmer 1.3s infinite; margin-bottom:.5rem; }
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

table { width:100%; border-collapse:collapse; font-size:.875rem; }
thead { background:#f8fafc; border-bottom:2px solid #e2e8f0; }
thead th { padding:.75rem 1rem; text-align:left; font-weight:700; letter-spacing:.04em; font-size:.72rem; text-transform:uppercase; color:#94a3b8; }
tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
tbody tr:last-child { border-bottom:none; }
tbody tr:hover { background:#f8fafc; }
td { padding:.8rem 1rem; vertical-align:middle; }

.fade-in-up { animation:slideUp .4s ease both; }

@media(max-width:1024px) { .sidebar-wrap { display:none; } }
/* 2 columns on mobile, auto-fill wider on larger screens */
@media(max-width:639px)  { .books-grid { grid-template-columns:repeat(2,1fr) !important; gap:.75rem !important; } }
/* RAG row wraps on tiny screens */
.rag-input-row { display:flex; gap:.75rem; }
@media(max-width:400px) { .rag-input-row { flex-direction:column; } }
</style>
</head>
<body class="flex">

<?php
$navItems = [
    ['url' => '/dashboard',        'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
    ['url' => '/reservation',      'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'reservation'],
    ['url' => '/reservation-list', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'reservation-list'],
    ['url' => '/books',            'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
    ['url' => '/profile',          'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
];
$page = 'books';

$booksJson = json_encode(array_map(fn($b) => [
    'id'               => (int)($b['id']               ?? 0),
    'title'            =>       $b['title']            ?? '',
    'author'           =>       $b['author']           ?? 'Unknown',
    'genre'            =>       $b['genre']            ?? '',
    'preface'          =>       $b['preface']          ?? '',
    'published_year'   =>       $b['published_year']   ?? '',
    'cover_image'      =>       $b['cover_image']      ?? '',
    'available_copies' => (int)($b['available_copies'] ?? 0),
    'total_copies'     => (int)($b['total_copies']     ?? 1),
], $books ?? []));
?>

<!-- ── Sidebar ── -->
<aside class="hidden lg:flex flex-col w-80 flex-shrink-0 p-6">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <p class="text-[10px] font-black tracking-[0.22em] text-green-600 uppercase mb-0.5">Resident Portal</p>
            <h1 class="text-[1.6rem] font-extrabold text-slate-800 leading-tight">my<span class="text-green-600">Space.</span></h1>
        </div>
        <nav class="sidebar-nav space-y-0.5 pt-2">
            <?php foreach ($navItems as $item):
                $active = ($page === $item['key']) ? 'active' : '';
            ?>
            <a href="<?= base_url($item['url']) ?>" class="sidebar-item <?= $active ?>">
                <span class="icon-wrap"><i class="fa-solid <?= $item['icon'] ?>"></i></span>
                <?= $item['label'] ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= base_url('/logout') ?>" class="flex items-center gap-3 px-5 py-3 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all text-sm">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
            </a>
        </div>
    </div>
</aside>

<!-- ── Mobile Nav ── -->
<nav class="lg:hidden mobile-nav-pill">
    <div class="mobile-scroll-container text-white px-2">
        <?php foreach ($navItems as $item):
            $cls = ($page === $item['key']) ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
        ?>
        <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 <?= $cls ?>">
            <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
            <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[72px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
            <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
            <span class="text-[9px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
        </a>
    </div>
</nav>

<!-- ══════════════════════════════════════
     BOOK DETAIL MODAL  (bottom-sheet on mobile)
     ══════════════════════════════════════ -->
<div class="modal-backdrop" id="bookDetailModal" onclick="onDetailBackdrop(event)">
    <div class="detail-card">
        <div class="sheet-handle"></div>

        <div class="detail-cover" id="detailCover">
            <span class="detail-cover-ph" id="detailCoverPh"></span>
        </div>

        <div class="detail-body">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div class="flex-1 min-w-0">
                    <p id="detailGenrePill" class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1"></p>
                    <h3 id="detailTitle"  class="text-xl font-black text-slate-900 leading-tight"></h3>
                    <p id="detailAuthor" class="text-sm text-slate-400 font-semibold mt-0.5"></p>
                </div>
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    <button onclick="closeDetailModal()"
                        class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <span id="detailAvailTag" class="tag"></span>
                </div>
            </div>

            <div id="detailPrefaceBox" class="hidden mb-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">About this book</p>
                <p id="detailPreface" class="text-sm text-slate-600 leading-relaxed italic font-medium"></p>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="fa-solid fa-copy"></i></div>
                <div><p class="info-label">Copies</p><p id="detailCopies" class="info-value"></p></div>
            </div>
            <div class="info-row" id="detailYearRow">
                <div class="info-icon"><i class="fa-solid fa-calendar-days"></i></div>
                <div><p class="info-label">Published</p><p id="detailYear" class="info-value"></p></div>
            </div>
            <div class="info-row" id="detailGenreRow">
                <div class="info-icon"><i class="fa-solid fa-tag"></i></div>
                <div><p class="info-label">Genre</p><p id="detailGenreVal" class="info-value"></p></div>
            </div>

            <div id="detailActions" class="flex gap-3 mt-5"></div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════
     BORROW CONFIRM MODAL  (bottom-sheet on mobile)
     ══════════════════════════════════════ -->
<div class="modal-backdrop" id="borrowModal" onclick="onBorrowBackdrop(event)">
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-lg font-black text-slate-900">Confirm Borrow</h3>
                <p class="text-xs text-slate-400 font-medium mt-0.5">14-day loan period</p>
            </div>
            <button onclick="closeBorrowModal()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="flex items-center gap-4 p-4 bg-green-50 rounded-2xl border border-green-100 mb-5">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-book-open text-green-600"></i>
            </div>
            <div>
                <p class="font-bold text-slate-800 text-sm" id="modalBookTitle">—</p>
                <p class="text-xs text-slate-500 mt-0.5">You'll be notified once your request is approved</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="closeBorrowModal()" class="flex-1 py-3 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Cancel</button>
            <form id="borrowForm" method="post" action="" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-green-200">
                    Yes, Borrow
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ── Main ── -->
<main class="flex-1 min-w-0 p-4 lg:p-10 pb-32">

    <header class="flex items-start justify-between mb-8 gap-4 fade-in-up">
        <div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Resident Portal</p>
            <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                Community <span class="text-green-600">Library</span>
            </h2>
            <p class="text-slate-400 font-medium text-sm mt-1">Browse, search, and borrow books available to all residents</p>
        </div>
    </header>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3 text-sm">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm">
        <i class="fa-solid fa-circle-exclamation text-red-400"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- RAG Panel -->
    <div class="dash-card p-5 mb-6">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-bolt text-xs"></i>
            </div>
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">AI Smart Suggestion</span>
        </div>
        <div class="rag-input-row">
            <input id="ragQuery" type="text"
                class="flex-1 min-w-0 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 outline-none focus:border-green-400 focus:ring-2 focus:ring-green-100 transition"
                placeholder="e.g. adventure for kids, Philippine history…"
                onkeydown="if(event.key==='Enter')doRag()">
            <button id="ragBtn" onclick="doRag()"
                class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-green-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap flex-shrink-0">
                <i class="fa-solid fa-magnifying-glass text-xs"></i> Find for Me
            </button>
        </div>
        <div id="ragSkel" class="rag-skeleton hidden mt-4 space-y-2">
            <div class="sk-line" style="width:85%"></div>
            <div class="sk-line" style="width:68%"></div>
            <div class="sk-line" style="width:50%"></div>
        </div>
        <div id="ragErr" class="hidden mt-3 px-4 py-3 bg-red-50 border border-red-200 text-red-600 text-sm font-medium rounded-xl"></div>
        <div id="ragRes" class="hidden mt-4">
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl mb-3">
                <p class="text-[10px] font-black uppercase tracking-widest text-green-600 mb-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-star text-[9px]"></i> Librarian Suggestion
                </p>
                <p id="ragText" class="text-sm text-slate-700 leading-relaxed font-medium italic"></p>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Matching books</p>
            <div id="ragChips" class="flex flex-wrap gap-2"></div>
        </div>
    </div>

    <!-- Controls -->
    <div class="flex gap-3 items-center flex-wrap mb-6">
        <div class="relative flex-1 min-w-[160px]">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
            <input id="searchInput" type="text" placeholder="Search title or author…"
                class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 outline-none focus:border-green-400 focus:ring-2 focus:ring-green-100 transition">
        </div>
        <select id="genreFilter"
            class="flex-shrink-0 px-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 outline-none focus:border-green-400 cursor-pointer w-full sm:w-auto">
            <option value="">All Genres</option>
            <?php foreach ($genres as $g): ?>
            <option value="<?= esc($g) ?>"><?= esc($g) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="flex rounded-2xl border border-slate-200 overflow-hidden bg-white w-full sm:w-auto">
            <button id="tabBrowse" onclick="switchTab('browse')" class="flex-1 sm:flex-none px-4 py-2.5 text-sm font-bold transition">
                <i class="fa-solid fa-grid-2 text-xs mr-1.5"></i>Browse
            </button>
            <button id="tabMine" onclick="switchTab('mine')" class="flex-1 sm:flex-none px-4 py-2.5 text-sm font-bold transition">
                <i class="fa-solid fa-clock-rotate-left text-xs mr-1.5"></i>My Borrowings
            </button>
        </div>
    </div>

    <!-- Browse Tab -->
    <div id="paneBrowse">
        <?php if (empty($books)): ?>
        <div class="dash-card p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-book-open text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-700 mb-1">No books yet</h3>
            <p class="text-sm text-slate-400 font-medium">The library is being stocked — check back soon!</p>
        </div>
        <?php else: ?>
        <div class="books-grid grid gap-4" id="booksGrid" style="grid-template-columns:repeat(auto-fill,minmax(210px,1fr))">
            <?php foreach ($books as $book):
                $available = (int)($book['available_copies'] ?? 0) > 0;
            ?>
            <div class="book-card"
                 id="book-<?= (int)$book['id'] ?>"
                 data-title="<?= strtolower(htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8')) ?>"
                 data-author="<?= strtolower(htmlspecialchars($book['author'] ?? '', ENT_QUOTES, 'UTF-8')) ?>"
                 data-genre="<?= htmlspecialchars($book['genre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 onclick="openBookDetail(<?= (int)$book['id'] ?>)">

                <div class="book-cover">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?= esc($book['cover_image']) ?>" alt="<?= esc($book['title']) ?>">
                    <?php else: ?>
                        <span class="cover-ph"><?= mb_strtoupper(mb_substr($book['title'], 0, 1)) ?></span>
                    <?php endif; ?>

                    <!-- Hover overlay -->
                    <div class="cover-overlay">
                        <span class="cover-overlay-btn">
                            <i class="fa-solid fa-eye text-[10px]"></i> View Details
                        </span>
                    </div>

                    <?php if (!empty($book['genre'])): ?>
                    <span class="cover-genre-badge px-2 py-0.5 bg-white/90 backdrop-blur text-green-700 text-[10px] font-black uppercase tracking-wider rounded-full border border-green-100">
                        <?= esc($book['genre']) ?>
                    </span>
                    <?php endif; ?>

                    <span class="cover-avail-badge tag <?= $available ? 'tag-available' : 'tag-out' ?>">
                        <?= $available ? 'Available' : 'Out' ?>
                    </span>
                </div>

                <div class="p-3 sm:p-4 flex flex-col flex-1">
                    <p class="font-extrabold text-sm text-slate-900 leading-snug mb-0.5" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?= esc($book['title']) ?></p>
                    <p class="text-xs text-slate-400 font-medium mb-2 truncate">by <?= esc($book['author'] ?? 'Unknown') ?></p>

                    <?php if (!empty($book['preface'])): ?>
                    <p class="text-xs text-slate-400 italic leading-relaxed mb-3 hidden sm:block" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?= esc($book['preface']) ?></p>
                    <?php endif; ?>

                    <div class="flex gap-1.5 flex-wrap mt-auto mb-2">
                        <span class="px-2 py-0.5 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500">
                            <?= (int)($book['available_copies'] ?? 0) ?>/<?= (int)($book['total_copies'] ?? 1) ?>
                        </span>
                        <?php if (!empty($book['published_year'])): ?>
                        <span class="px-2 py-0.5 bg-slate-100 rounded-lg text-[10px] font-bold text-slate-500 hidden sm:inline-flex">
                            <?= esc($book['published_year']) ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <p class="text-[10px] text-slate-300 font-semibold text-center">Tap to view</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- My Borrowings Tab -->
    <div id="paneMine" style="display:none">
        <?php if (empty($myBorrowings)): ?>
        <div class="dash-card p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-700 mb-1">No borrowing history</h3>
            <p class="text-sm text-slate-400 font-medium">Books you borrow will appear here.</p>
        </div>
        <?php else: ?>
        <div class="dash-card overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                </div>
                <h3 class="font-extrabold text-slate-800 text-sm">My Borrowing History</h3>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr><th>#</th><th>Book</th><th>Borrowed On</th><th>Due Date</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($myBorrowings as $i => $b):
                        $s = strtolower($b['status'] ?? 'pending');
                    ?>
                        <tr>
                            <td class="text-slate-400 font-bold text-xs"><?= $i + 1 ?></td>
                            <td>
                                <p class="font-bold text-sm text-slate-800"><?= esc($b['title']) ?></p>
                                <p class="text-xs text-slate-400"><?= esc($b['author'] ?? '') ?></p>
                            </td>
                            <td class="text-sm text-slate-600 font-medium"><?= esc($b['borrowed_at'] ?? '—') ?></td>
                            <td class="text-sm text-slate-600 font-medium"><?= esc($b['due_date'] ?? '—') ?></td>
                            <td><span class="tag tag-<?= $s ?>"><?= ucfirst($s) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

</main>

<script>
/* ── Book data ── */
const BOOKS = <?= $booksJson ?? '[]' ?>;
const bookMap = {};
BOOKS.forEach(b => bookMap[b.id] = b);

/* ── Tabs ── */
const ACT = 'bg-green-600 text-white shadow-sm shadow-green-200';
const INQ = 'text-slate-500 hover:text-slate-700 hover:bg-slate-50';
function switchTab(t) {
    document.getElementById('paneBrowse').style.display = t === 'browse' ? '' : 'none';
    document.getElementById('paneMine').style.display   = t === 'mine'   ? '' : 'none';
    document.getElementById('tabBrowse').className = 'px-4 py-2.5 text-sm font-bold transition ' + (t === 'browse' ? ACT : INQ);
    document.getElementById('tabMine').className   = 'px-4 py-2.5 text-sm font-bold transition ' + (t === 'mine'   ? ACT : INQ);
}
switchTab('browse');

/* ── Book Detail Modal ── */
function openBookDetail(id) {
    const b = bookMap[id];
    if (!b) return;
    const avail = b.available_copies > 0;

    /* Cover */
    const coverEl = document.getElementById('detailCover');
    const phEl    = document.getElementById('detailCoverPh');
    const oldImg  = coverEl.querySelector('img');
    if (oldImg) oldImg.remove();
    if (b.cover_image) {
        phEl.style.display = 'none';
        const img = document.createElement('img'); img.src = b.cover_image; img.alt = b.title;
        coverEl.appendChild(img);
    } else { phEl.style.display = ''; phEl.textContent = b.title.charAt(0).toUpperCase(); }

    document.getElementById('detailGenrePill').textContent = b.genre || '';
    document.getElementById('detailTitle').textContent     = b.title;
    document.getElementById('detailAuthor').textContent    = 'by ' + b.author;
    document.getElementById('detailCopies').textContent    = b.available_copies + ' available of ' + b.total_copies + ' total';

    const tag = document.getElementById('detailAvailTag');
    tag.textContent = avail ? 'Available' : 'Not Available';
    tag.className   = 'tag ' + (avail ? 'tag-available' : 'tag-out');

    const prefBox = document.getElementById('detailPrefaceBox');
    if (b.preface) { document.getElementById('detailPreface').textContent = b.preface; prefBox.classList.remove('hidden'); }
    else           { prefBox.classList.add('hidden'); }

    const yr = document.getElementById('detailYearRow');
    if (b.published_year) { document.getElementById('detailYear').textContent = b.published_year; yr.style.display = ''; }
    else yr.style.display = 'none';

    const gr = document.getElementById('detailGenreRow');
    if (b.genre) { document.getElementById('detailGenreVal').textContent = b.genre; gr.style.display = ''; }
    else gr.style.display = 'none';

    const acts = document.getElementById('detailActions');
    if (avail) {
        acts.innerHTML = `
            <button onclick="closeDetailModal(); openBorrowModal(${b.id}, ${JSON.stringify(b.title)})"
                class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-green-200 flex items-center justify-center gap-2">
                <i class="fa-solid fa-book-open text-xs"></i> Borrow This Book
            </button>
            <button onclick="closeDetailModal()"
                class="py-3 px-5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition">Close</button>`;
    } else {
        acts.innerHTML = `
            <button disabled class="flex-1 py-3 bg-slate-100 text-slate-400 rounded-2xl font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                <i class="fa-solid fa-xmark text-xs"></i> Currently Unavailable
            </button>
            <button onclick="closeDetailModal()"
                class="py-3 px-5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition">Close</button>`;
    }

    document.getElementById('bookDetailModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    document.getElementById('bookDetailModal').classList.remove('show');
    document.body.style.overflow = '';
}
function onDetailBackdrop(e) { if (e.target === document.getElementById('bookDetailModal')) closeDetailModal(); }

/* ── Borrow Modal ── */
function openBorrowModal(id, title) {
    document.getElementById('modalBookTitle').textContent = title;
    document.getElementById('borrowForm').action = '/books/borrow/' + id;
    document.getElementById('borrowModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeBorrowModal() {
    document.getElementById('borrowModal').classList.remove('show');
    document.body.style.overflow = '';
}
function onBorrowBackdrop(e) { if (e.target === document.getElementById('borrowModal')) closeBorrowModal(); }

document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeDetailModal(); closeBorrowModal(); } });

/* ── Filter ── */
document.getElementById('searchInput').addEventListener('input', filterBooks);
document.getElementById('genreFilter').addEventListener('change', filterBooks);
function filterBooks() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const g = document.getElementById('genreFilter').value;
    document.querySelectorAll('.book-card').forEach(c => {
        const mQ = c.dataset.title.includes(q) || c.dataset.author.includes(q);
        const mG = !g || c.dataset.genre === g;
        c.style.display = mQ && mG ? '' : 'none';
    });
}

/* ── RAG ── */
async function doRag() {
    const query = document.getElementById('ragQuery').value.trim();
    if (query.length < 2) return;
    const skel = document.getElementById('ragSkel');
    const err  = document.getElementById('ragErr');
    const res  = document.getElementById('ragRes');
    const btn  = document.getElementById('ragBtn');
    res.classList.add('hidden'); err.classList.add('hidden');
    skel.classList.remove('hidden'); btn.disabled = true;
    document.querySelectorAll('.book-card').forEach(c => c.classList.remove('rag-hl'));
    try {
        const r = await fetch('/rag/suggest', {
            method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' },
            body: JSON.stringify({ query })
        });
        const d = await r.json();
        skel.classList.add('hidden'); btn.disabled = false;
        if (!d.suggestion) { err.textContent = d.error || d.message || 'No suggestion found.'; err.classList.remove('hidden'); return; }
        document.getElementById('ragText').textContent = d.suggestion;
        const chips = document.getElementById('ragChips');
        chips.innerHTML = '';
        (d.books || []).forEach(b => {
            const avail = (b.available_copies || 0) > 0;
            const chip  = document.createElement('button');
            chip.className = 'px-3 py-1.5 rounded-xl border font-bold text-xs transition ' +
                (avail ? 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100' : 'bg-slate-50 border-slate-200 text-slate-400 border-dashed');
            chip.innerHTML = b.title + (avail ? '' : ' <span class="opacity-60">(out)</span>');
            chip.onclick = () => {
                openBookDetail(b.id);
                const card = document.getElementById('book-' + b.id);
                if (card) { card.classList.add('rag-hl'); card.scrollIntoView({ behavior:'smooth', block:'center' }); }
            };
            chips.appendChild(chip);
            const card = document.getElementById('book-' + b.id);
            if (card) card.classList.add('rag-hl');
        });
        res.classList.remove('hidden');
    } catch(e) {
        skel.classList.add('hidden'); btn.disabled = false;
        err.textContent = 'Network error. Please try again.'; err.classList.remove('hidden');
    }
}
</script>
</body>
</html>