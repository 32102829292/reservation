<?php
/**
 * Views/app/sk/books.php
 * SK — Manage Books Catalog + Borrowings + PDF Upload & AI Extraction
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<title>Library Management | SK</title>
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#16a34a">
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name"  content="<?= csrf_token() ?>">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
* { box-sizing: border-box; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; }

/* ── Sidebar ── */
.sidebar-card {
    background: white; border-radius: 32px; border: 1px solid #e2e8f0;
    height: calc(100vh - 48px); position: sticky; top: 24px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    display: flex; flex-direction: column; overflow: hidden;
}
.sidebar-header { flex-shrink: 0; padding: 20px 16px 16px; border-bottom: 1px solid #e2e8f0; }
.sidebar-nav { flex: 1; overflow-y: auto; padding: 8px; }
.sidebar-nav::-webkit-scrollbar { width: 4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
.sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #e2e8f0; }
.sidebar-item { transition: all 0.2s; border-radius: 20px; }
.sidebar-item.active { background: #16a34a; color: white; box-shadow: 0 10px 15px -3px rgba(22,163,74,0.3); }

/* ── Mobile Nav ── */
.mobile-nav-pill {
    position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
    width: 92%; max-width: 600px; background: rgba(20,83,45,0.98);
    backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
    z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
}
.mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.mobile-scroll-container::-webkit-scrollbar { display: none; }

/* ── Cards ── */
.dash-card { background: white; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); }
.stat-card { background: white; border-radius: 20px; padding: 1.25rem; border: 1px solid #e2e8f0; transition: all 0.2s; }
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -8px rgba(0,0,0,0.1); }

/* ── Mobile book/borrow cards ── */
.book-card, .borrow-card {
    background: white; border-radius: 18px; border: 1px solid #e2e8f0;
    padding: 1rem 1.1rem; transition: all 0.18s;
}
.book-card:hover, .borrow-card:hover {
    border-color: #bbf7d0; box-shadow: 0 6px 20px -4px rgba(22,163,74,0.12);
    transform: translateY(-1px);
}
.book-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; border-radius: 0 3px 3px 0; background: #16a34a;
}
.book-card { position: relative; overflow: hidden; }

/* ── Overlays ── */
.overlay { display: none; position: fixed; inset: 0; z-index: 300; align-items: center; justify-content: center; }
.overlay.show { display: flex; animation: fadeIn 0.15s ease; }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
.overlay-bg { position: absolute; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(6px); }

/* ── Modal box ── */
.modal-card {
    position: relative; margin: auto; background: white; border-radius: 28px;
    width: 94%; max-width: 580px; padding: 2rem; max-height: 92vh;
    overflow-y: auto; animation: slideUp 0.2s ease;
    box-shadow: 0 40px 80px rgba(0,0,0,0.22);
}
.modal-card.lg { max-width: 680px; }
.modal-card::-webkit-scrollbar { width: 4px; }
.modal-card::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

/* Sheet handle — mobile only */
.sheet-handle { display: none; width: 40px; height: 4px; background: #e2e8f0; border-radius: 9999px; margin: 0 auto 1rem; }

/* Bottom-sheet on mobile */
@media (max-width: 639px) {
    .overlay .modal-card {
        margin: 0; width: 100%; max-width: 100%;
        border-radius: 28px 28px 0 0; max-height: 92vh;
        animation: slideUpSheet 0.28s cubic-bezier(0.34,1.2,0.64,1) both;
    }
    .overlay { align-items: flex-end !important; }
    .sheet-handle { display: block; }
}

@keyframes slideUp       { from { transform:translateY(16px); opacity:0; } to { transform:none; opacity:1; } }
@keyframes slideUpSheet  { from { opacity:0; transform:translateY(60px); } to { opacity:1; transform:none; } }

.form-input { width: 100%; padding: 0.625rem 0.875rem; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #1e293b; outline: none; transition: border-color 0.2s; }
.form-input:focus { border-color: #16a34a; background: white; }
textarea.form-input { resize: vertical; min-height: 90px; }

/* ── Desktop tables ── */
.table-outer { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 660px; }
thead { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
thead th { padding: 0.75rem 1rem; text-align: left; font-weight: 700; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }
tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #f8fafc; }
td { padding: 0.8rem 1rem; vertical-align: middle; }

/* ── Tags ── */
.tag { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; }
.tag-active   { background: #dcfce7; color: #166534; }
.tag-inactive { background: #fee2e2; color: #991b1b; }
.tag-pending  { background: #fef3c7; color: #92400e; }
.tag-approved { background: #dcfce7; color: #166534; }
.tag-returned { background: #e0e7ff; color: #3730a3; }
.tag-rejected { background: #fee2e2; color: #991b1b; }
.tag-yes { background: #dcfce7; color: #166534; }
.tag-no  { background: #f1f5f9; color: #64748b; }

.fade-up { animation: slideUp 0.4s ease both; }

/* ── PDF Drop Zone ── */
.drop-zone {
    border: 2.5px dashed #bbf7d0; border-radius: 20px; padding: 2.5rem 1.5rem;
    text-align: center; background: #f0fdf4; cursor: pointer; transition: all 0.2s; position: relative;
}
.drop-zone:hover, .drop-zone.dragover { border-color: #16a34a; background: #dcfce7; }
.drop-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }

.ai-progress-bar { height: 4px; background: #e2e8f0; border-radius: 999px; overflow: hidden; margin-top: 8px; }
.ai-progress-fill { height: 100%; background: linear-gradient(90deg, #16a34a, #4ade80, #16a34a); background-size: 200% 100%; border-radius: 999px; animation: shimmer 1.4s infinite; width: 0%; transition: width 0.4s ease; }
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

.field-filled { border-color: #16a34a !important; background: #f0fdf4 !important; }
.field-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 999px; background: #dcfce7; color: #166534; margin-left: 6px; }

.step-dot { width: 28px; height: 28px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; flex-shrink: 0; transition: all 0.3s; }
.step-dot.done    { background: #16a34a; color: white; }
.step-dot.active  { background: #dcfce7; color: #16a34a; border: 2px solid #16a34a; }
.step-dot.pending { background: #f1f5f9; color: #94a3b8; }
.step-line { flex: 1; height: 2px; border-radius: 999px; transition: background 0.3s; }
.step-line.done    { background: #16a34a; }
.step-line.pending { background: #e2e8f0; }

#debugPanel { display: none; margin-top: 12px; padding: 10px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; font-size: 0.72rem; font-family: monospace; color: #991b1b; word-break: break-all; white-space: pre-wrap; max-height: 120px; overflow-y: auto; }

/* ── Tabs ── */
.tab-active   { background: #16a34a; color: white; box-shadow: 0 2px 8px rgba(22,163,74,0.2); }
.tab-inactive { color: #64748b; }
.tab-inactive:hover { color: #334155; background: #f8fafc; }

/* ── Card empty ── */
.card-empty { padding: 3rem 1.5rem; text-align: center; }

/* ── Book detail sheet ── */
.book-card { cursor: pointer; }
.detail-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 0.65rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem;
}
.detail-row:last-child { border-bottom: none; }
.detail-label { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; flex-shrink: 0; }
.detail-value { font-weight: 700; color: #1e293b; font-size: 0.875rem; text-align: right; word-break: break-word; max-width: 65%; }
.preface-block {
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 16px;
    padding: 0.875rem 1rem; font-size: 0.8rem; color: #166534; font-weight: 500;
    line-height: 1.6; max-height: 160px; overflow-y: auto;
}
.preface-block::-webkit-scrollbar { width: 4px; }
.preface-block::-webkit-scrollbar-thumb { background: #bbf7d0; border-radius: 4px; }
</style>
</head>
<body class="flex">

<?php
$navItems = [
    ['url' => '/sk/dashboard',            'icon' => 'fa-house',           'label' => 'Dashboard',        'key' => 'dashboard'],
    ['url' => '/sk/reservations',         'icon' => 'fa-calendar-alt',    'label' => 'All Reservations', 'key' => 'reservations'],
    ['url' => '/sk/new-reservation',      'icon' => 'fa-plus',            'label' => 'New Reservation',  'key' => 'new-reservation'],
    ['url' => '/sk/user-requests',        'icon' => 'fa-users',           'label' => 'User Requests',    'key' => 'user-requests'],
    ['url' => '/sk/my-reservations',      'icon' => 'fa-calendar',        'label' => 'My Reservations',  'key' => 'my-reservations'],
    ['url' => '/sk/claimed-reservations', 'icon' => 'fa-check-double',    'label' => 'Claimed',          'key' => 'claimed-reservations'],
    ['url' => '/sk/books',                'icon' => 'fa-book-open',       'label' => 'Library',          'key' => 'books'],
    ['url' => '/sk/scanner',              'icon' => 'fa-qrcode',          'label' => 'Scanner',          'key' => 'scanner'],
    ['url' => '/sk/profile',              'icon' => 'fa-regular fa-user', 'label' => 'Profile',          'key' => 'profile'],
];
$page = 'books';

$totalBooks     = count($books ?? []);
$activeBooks    = count(array_filter($books ?? [], fn($b) => $b['status'] === 'active'));
$totalBorrows   = count($borrowings ?? []);
$pendingBorrows = count(array_filter($borrowings ?? [], fn($b) => $b['status'] === 'pending'));
$ragReady       = count(array_filter($books ?? [], fn($b) => !empty($b['preface'])));
?>

<!-- ══════════════════════════════════════
     ADD BOOK MODAL
     ══════════════════════════════════════ -->
<div class="overlay" id="addModal">
    <div class="overlay-bg" onclick="closeModal('addModal')"></div>
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-black text-slate-900">Add New Book</h3>
                <p class="text-xs text-slate-400 mt-0.5">Fill in the details manually</p>
            </div>
            <button onclick="closeModal('addModal')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="post" action="/sk/books/store">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Title *</label>
                    <input class="form-input" name="title" required placeholder="Book title">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Author *</label>
                    <input class="form-input" name="author" required placeholder="Author name">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Genre</label>
                    <input class="form-input" name="genre" placeholder="e.g. Fiction">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Published Year</label>
                    <input class="form-input" name="published_year" type="number" min="1000" max="2099" placeholder="2024">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">ISBN</label>
                    <input class="form-input" name="isbn" placeholder="Optional">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Total Copies</label>
                    <input class="form-input" name="total_copies" type="number" min="1" value="1">
                </div>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">
                    Preface / Description
                    <span class="ml-1 text-green-600 normal-case font-semibold">(used for AI suggestions)</span>
                </label>
                <textarea class="form-input" name="preface" rows="4" placeholder="Describe the book…"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addModal')" class="flex-1 py-3 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Cancel</button>
                <button type="submit" class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition">Add Book</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════
     PDF UPLOAD + AI EXTRACTION MODAL
     ══════════════════════════════════════ -->
<div class="overlay" id="pdfModal">
    <div class="overlay-bg" onclick="closeModal('pdfModal')"></div>
    <div class="modal-card lg">
        <div class="sheet-handle"></div>
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-wand-magic-sparkles text-green-600 text-xs"></i>
                    </span>
                    Upload PDF — AI will extract details
                </h3>
                <p class="text-xs text-slate-400 mt-1 ml-10">Upload a book PDF and AI will auto-fill the catalog fields</p>
            </div>
            <button onclick="closeModal('pdfModal')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Step Indicator -->
        <div class="flex items-center gap-2 mb-6 px-1">
            <div class="step-dot active" id="stepDot1">1</div>
            <div class="step-line pending" id="stepLine1"></div>
            <div class="step-dot pending" id="stepDot2">2</div>
            <div class="step-line pending" id="stepLine2"></div>
            <div class="step-dot pending" id="stepDot3">3</div>
            <span class="text-xs font-semibold text-slate-400 ml-2" id="stepLabel">Upload PDF</span>
        </div>

        <!-- Step 1: Drop Zone -->
        <div id="pdfStep1">
            <div class="drop-zone" id="dropZone">
                <input type="file" id="pdfFileInput" accept=".pdf" onchange="handlePdfFile(event)">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-file-pdf text-2xl text-green-600"></i>
                </div>
                <p class="font-black text-slate-700 text-base mb-1">Drop your PDF here</p>
                <p class="text-sm text-slate-400 font-medium mb-3">or click to browse files</p>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-500">
                    <i class="fa-solid fa-file-pdf text-red-400"></i> PDF files only · Max 10MB
                </span>
            </div>

            <div id="filePreview" class="hidden mt-4 px-4 py-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p id="filePreviewName" class="font-bold text-sm text-slate-800 truncate"></p>
                    <p id="filePreviewSize" class="text-xs text-slate-400 mt-0.5"></p>
                </div>
                <button onclick="clearPdfFile()" class="text-slate-400 hover:text-slate-600 transition text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <button id="extractBtn" onclick="extractFromPdf()" disabled
                class="mt-5 w-full py-3.5 bg-green-600 hover:bg-green-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Extract with AI
            </button>
        </div>

        <!-- Step 2: AI Processing -->
        <div id="pdfStep2" style="display:none">
            <div class="text-center py-6">
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-robot text-green-500 text-2xl animate-pulse"></i>
                </div>
                <h4 class="font-black text-slate-800 text-base mb-1">AI is reading your PDF…</h4>
                <p class="text-sm text-slate-400 font-medium" id="aiStatusText">Analyzing document structure…</p>
                <div class="ai-progress-bar mt-4 mx-auto max-w-xs">
                    <div class="ai-progress-fill" id="aiProgressFill" style="width:15%"></div>
                </div>
                <p class="text-xs text-slate-300 font-semibold mt-3">This usually takes 5–15 seconds</p>
            </div>
        </div>

        <!-- Step 3: Review & Save -->
        <div id="pdfStep3" style="display:none">
            <div class="flex items-center gap-2 mb-5 px-4 py-3 bg-green-50 border border-green-200 rounded-2xl">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <p class="text-sm font-bold text-green-700">AI extraction complete! Review and edit fields below, then save.</p>
            </div>
            <form method="post" action="/sk/books/store" id="pdfBookForm">
                <?= csrf_field() ?>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Title * <span class="field-badge" id="badgeTitle" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span></label>
                        <input class="form-input" name="title" id="pdfTitle" required placeholder="Book title">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Author * <span class="field-badge" id="badgeAuthor" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span></label>
                        <input class="form-input" name="author" id="pdfAuthor" required placeholder="Author name">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Genre <span class="field-badge" id="badgeGenre" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span></label>
                        <input class="form-input" name="genre" id="pdfGenre" placeholder="e.g. Fiction">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Published Year <span class="field-badge" id="badgeYear" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span></label>
                        <input class="form-input" name="published_year" id="pdfYear" type="number" min="1000" max="2099" placeholder="2024">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">ISBN <span class="field-badge" id="badgeIsbn" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span></label>
                        <input class="form-input" name="isbn" id="pdfIsbn" placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Total Copies</label>
                        <input class="form-input" name="total_copies" id="pdfCopies" type="number" min="1" value="1">
                    </div>
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">
                        Preface / Description
                        <span class="field-badge" id="badgePreface" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span>
                        <span class="ml-1 text-green-600 normal-case font-semibold">(used for AI suggestions)</span>
                    </label>
                    <textarea class="form-input" name="preface" id="pdfPreface" rows="5" placeholder="AI-generated description will appear here…"></textarea>
                </div>
                <div id="aiConfidenceNote" class="mb-4 px-3 py-2.5 bg-amber-50 border border-amber-200 rounded-xl text-xs font-semibold text-amber-700 hidden">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                    Some fields couldn't be auto-detected. Please fill them in manually before saving.
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="resetPdfModal()" class="flex-1 py-3 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">
                        <i class="fa-solid fa-rotate-left text-xs mr-1"></i> Try Another
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition">
                        <i class="fa-solid fa-check mr-1"></i> Save Book
                    </button>
                </div>
            </form>
        </div>

        <!-- Error State -->
        <div id="pdfStepError" style="display:none">
            <div class="text-center py-6">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-red-400 text-2xl"></i>
                </div>
                <h4 class="font-black text-slate-800 text-base mb-1">Extraction Failed</h4>
                <p class="text-sm text-red-500 font-bold mb-1" id="pdfErrorText">Could not read the PDF.</p>
                <div id="debugPanel"></div>
                <p class="text-xs text-slate-300 font-semibold mt-4 mb-5">Make sure the PDF has readable text (not a scanned image).</p>
                <button onclick="resetPdfModal()" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition">Try Again</button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════
     EDIT BOOK MODAL
     ══════════════════════════════════════ -->
<div class="overlay" id="editModal">
    <div class="overlay-bg" onclick="closeModal('editModal')"></div>
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-black text-slate-900">Edit Book</h3>
                <p class="text-xs text-slate-400 mt-0.5">Update book details</p>
            </div>
            <button onclick="closeModal('editModal')" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="post" id="editForm" action="">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Title *</label>
                    <input class="form-input" name="title" id="editTitle" required>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Author *</label>
                    <input class="form-input" name="author" id="editAuthor" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Genre</label>
                    <input class="form-input" name="genre" id="editGenre">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Published Year</label>
                    <input class="form-input" name="published_year" id="editYear" type="number" min="1000" max="2099">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Total Copies</label>
                    <input class="form-input" name="total_copies" id="editCopies" type="number" min="1">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                    <select class="form-input" name="status" id="editStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mb-5">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">
                    Preface / Description <span class="ml-1 text-green-600 normal-case font-semibold">(AI context)</span>
                </label>
                <textarea class="form-input" name="preface" id="editPreface" rows="4"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('editModal')" class="flex-1 py-3 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">Cancel</button>
                <button type="submit" class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════
     BOOK DETAIL MODAL  (bottom-sheet on mobile)
     ══════════════════════════════════════ -->
<div class="overlay" id="bookDetailModal">
    <div class="overlay-bg" onclick="closeModal('bookDetailModal')"></div>
    <div class="modal-card">
        <div class="sheet-handle"></div>

        <!-- Header: cover icon + title/author + close -->
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-green-50 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-book text-blue-500 text-lg"></i>
                </div>
                <div class="min-w-0">
                    <h3 id="bdTitle" class="text-base font-black text-slate-900 leading-snug"></h3>
                    <p id="bdAuthor" class="text-xs text-slate-400 font-semibold mt-0.5"></p>
                </div>
            </div>
            <button onclick="closeModal('bookDetailModal')"
                class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0 ml-3">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Status pill -->
        <div id="bdStatusBar" class="mb-4 px-4 py-2.5 rounded-2xl flex items-center gap-2 text-sm font-bold"></div>

        <!-- Key detail rows -->
        <div class="mb-4">
            <div class="detail-row">
                <span class="detail-label">Genre</span>
                <span id="bdGenre" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Published</span>
                <span id="bdYear" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Copies</span>
                <span id="bdCopies" class="detail-value"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">ISBN</span>
                <span id="bdIsbn" class="detail-value"></span>
            </div>
        </div>

        <!-- Preface / AI context -->
        <div id="bdPrefaceWrap" class="mb-5" style="display:none">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                <i class="fa-solid fa-wand-magic-sparkles text-green-500 text-[10px]"></i> AI Context · Preface
            </p>
            <div id="bdPreface" class="preface-block"></div>
        </div>
        <div id="bdNoPrefaceWrap" class="mb-5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-2.5" style="display:none">
            <i class="fa-solid fa-circle-info text-slate-300 text-sm flex-shrink-0"></i>
            <p class="text-xs text-slate-400 font-semibold">No preface added — edit to add AI context for suggestions.</p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-2 border-t border-slate-100">
            <button id="bdEditBtn"
                class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-pen text-xs"></i> Edit
            </button>
            <form id="bdDeleteForm" method="post" action="" style="display:contents"
                  onsubmit="return confirm('Delete this book?')">
                <?= csrf_field() ?>
                <button type="submit"
                    class="flex-1 py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-trash text-xs"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ── Sidebar ── -->
<aside class="hidden lg:block w-80 flex-shrink-0 p-6">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Youth Portal</span>
            <h1 class="text-2xl font-extrabold text-slate-800">SK<span class="text-green-600">.</span></h1>
        </div>
        <nav class="sidebar-nav space-y-1">
            <?php foreach ($navItems as $item):
                $active = ($page === $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
            ?>
            <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                <?= $item['label'] ?>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="/logout" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
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
        <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[68px] rounded-xl transition flex-shrink-0 <?= $cls ?>">
            <i class="fa-solid <?= $item['icon'] ?> text-base"></i>
            <span class="text-[9px] mt-0.5 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <a href="/logout" class="flex flex-col items-center justify-center py-2 px-3 min-w-[68px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
            <i class="fa-solid fa-arrow-right-from-bracket text-base"></i>
            <span class="text-[9px] mt-0.5 text-center leading-tight whitespace-nowrap">Logout</span>
        </a>
    </div>
</nav>

<!-- ── Main ── -->
<main class="flex-1 min-w-0 p-4 lg:p-8 pb-32">

    <header class="flex items-start justify-between mb-7 gap-4 fade-up">
        <div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Youth Portal</p>
            <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                Library <span class="text-green-600">Management</span>
            </h2>
            <p class="text-slate-400 font-medium text-sm mt-1">Manage books catalog and borrowing requests</p>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <button onclick="openModal('pdfModal')"
                class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-green-400 hover:bg-green-50 text-slate-700 hover:text-green-700 rounded-2xl font-bold text-sm transition">
                <i class="fa-solid fa-wand-magic-sparkles text-green-500 text-xs"></i> Upload PDF
            </button>
            <button onclick="openModal('addModal')"
                class="flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-green-200">
                <i class="fa-solid fa-plus text-xs"></i> Add Manually
            </button>
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

    <!-- Stat cards: 2-col always -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-book text-blue-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Books</span>
            </div>
            <p class="text-3xl font-black text-slate-800"><?= $totalBooks ?></p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium"><?= $activeBooks ?> active</p>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-clock-rotate-left text-purple-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Borrows</span>
            </div>
            <p class="text-3xl font-black text-slate-800"><?= $totalBorrows ?></p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">all time</p>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center"><i class="fa-regular fa-clock text-amber-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-amber-600 uppercase tracking-wider">Pending</span>
            </div>
            <p class="text-3xl font-black text-amber-600"><?= $pendingBorrows ?></p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">awaiting approval</p>
        </div>
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center"><i class="fa-solid fa-bolt text-green-500 text-sm"></i></div>
                <span class="text-[10px] font-black text-green-600 uppercase tracking-wider">AI Ready</span>
            </div>
            <p class="text-3xl font-black text-green-600"><?= $ragReady ?></p>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">books with preface</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex rounded-2xl border border-slate-200 overflow-hidden bg-white w-fit mb-6">
        <button id="tabBooks" onclick="switchTab('books')" class="px-5 py-2.5 text-sm font-bold transition tab-active">
            <i class="fa-solid fa-book text-xs mr-1.5"></i>Books Catalog
        </button>
        <button id="tabBorrowings" onclick="switchTab('borrowings')" class="px-5 py-2.5 text-sm font-bold transition tab-inactive">
            <i class="fa-solid fa-clock-rotate-left text-xs mr-1.5"></i>Borrowings
            <?php if ($pendingBorrows > 0): ?>
            <span class="ml-1.5 bg-amber-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full"><?= $pendingBorrows ?></span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ══════════════════════════════════════
         BOOKS TAB
         ══════════════════════════════════════ -->
    <div id="paneBooks">
        <div class="flex gap-3 items-center flex-wrap mb-4">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input id="bookSearch" type="text" placeholder="Search title, author, genre…"
                    oninput="filterBooks()"
                    class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 outline-none focus:border-green-400 focus:ring-2 focus:ring-green-100 transition">
            </div>
            <div class="flex gap-2 sm:hidden">
                <button onclick="openModal('pdfModal')" class="px-3 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-wand-magic-sparkles text-green-500 text-xs"></i>
                </button>
                <button onclick="openModal('addModal')" class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-2xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Add
                </button>
            </div>
        </div>

        <?php if (empty($books)): ?>
        <div class="dash-card p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-book-open text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-700 mb-1">No books yet</h3>
            <p class="text-sm text-slate-400 font-medium mb-4">Add your first book manually or upload a PDF for AI extraction.</p>
            <div class="flex gap-3 justify-center">
                <button onclick="openModal('pdfModal')" class="px-5 py-2.5 bg-white border border-slate-200 hover:border-green-400 text-slate-700 rounded-2xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-green-500 text-xs"></i> Upload PDF
                </button>
                <button onclick="openModal('addModal')" class="px-5 py-2.5 bg-green-600 text-white rounded-2xl font-bold text-sm hover:bg-green-700 transition">
                    <i class="fa-solid fa-plus mr-1.5"></i> Add Manually
                </button>
            </div>
        </div>
        <?php else: ?>

        <!-- Desktop table (md+) -->
        <div class="hidden md:block dash-card overflow-hidden">
            <div class="table-outer">
                <table id="booksTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title / Author</th>
                            <th>Genre</th>
                            <th>Year</th>
                            <th>Copies</th>
                            <th>Preface</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $i => $b): ?>
                    <tr data-search="<?= strtolower(htmlspecialchars($b['title'] . ' ' . ($b['author'] ?? '') . ' ' . ($b['genre'] ?? ''), ENT_QUOTES)) ?>">
                        <td class="text-slate-400 font-bold text-xs"><?= $i + 1 ?></td>
                        <td>
                            <p class="font-bold text-sm text-slate-800"><?= htmlspecialchars($b['title'], ENT_QUOTES) ?></p>
                            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($b['author'] ?? '', ENT_QUOTES) ?></p>
                        </td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($b['genre'] ?? '—', ENT_QUOTES) ?></td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($b['published_year'] ?? '—', ENT_QUOTES) ?></td>
                        <td>
                            <span class="text-sm font-bold text-slate-800"><?= (int)($b['available_copies'] ?? 0) ?></span>
                            <span class="text-xs text-slate-400">/ <?= (int)($b['total_copies'] ?? 1) ?></span>
                        </td>
                        <td>
                            <?php if (!empty($b['preface'])): ?>
                            <span class="tag tag-yes"><i class="fa-solid fa-check text-[9px] mr-1"></i>Yes</span>
                            <?php else: ?>
                            <span class="tag tag-no">No</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="tag tag-<?= $b['status'] === 'active' ? 'active' : 'inactive' ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td>
                            <div class="flex gap-2">
                                <button onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>)"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">Edit</button>
                                <form method="post" action="/sk/books/delete/<?= $b['id'] ?>" style="display:inline" onsubmit="return confirm('Delete this book?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-xs transition">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile book cards (below md) -->
        <div id="bookCardList" class="md:hidden space-y-3">
            <?php foreach ($books as $i => $b): ?>
                <div class="book-card"
                     data-search="<?= strtolower(htmlspecialchars($b['title'] . ' ' . ($b['author'] ?? '') . ' ' . ($b['genre'] ?? ''), ENT_QUOTES)) ?>"
                     onclick='openBookDetail(<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>)'>

                    <div class="flex items-start gap-3 mb-2">
                        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-book text-blue-500 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-slate-800 leading-tight truncate"><?= htmlspecialchars($b['title'], ENT_QUOTES) ?></p>
                            <p class="text-[11px] text-slate-400 truncate"><?= htmlspecialchars($b['author'] ?? '', ENT_QUOTES) ?></p>
                        </div>
                        <span class="tag tag-<?= $b['status'] === 'active' ? 'active' : 'inactive' ?> flex-shrink-0"><?= ucfirst($b['status']) ?></span>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap mb-2">
                        <?php if (!empty($b['genre'])): ?>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full"><?= htmlspecialchars($b['genre'], ENT_QUOTES) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($b['published_year'])): ?>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full"><?= htmlspecialchars($b['published_year'], ENT_QUOTES) ?></span>
                        <?php endif; ?>
                        <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                            <?= (int)($b['available_copies'] ?? 0) ?>/<?= (int)($b['total_copies'] ?? 1) ?> copies
                        </span>
                        <?php if (!empty($b['preface'])): ?>
                            <span class="tag tag-yes text-[9px] !px-1.5 !py-0.5"><i class="fa-solid fa-wand-magic-sparkles text-[8px] mr-0.5"></i>AI</span>
                        <?php endif; ?>
                    </div>

                    <!-- Tap hint -->
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <p class="text-[10px] text-slate-300 font-semibold">Tap to view details</p>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Books no-results (mobile) -->
        <div id="booksMobileEmpty" class="md:hidden card-empty" style="display:none">
            <i class="fa-solid fa-filter-circle-xmark text-3xl text-slate-200 mb-2 block"></i>
            <p class="font-bold text-slate-400">No books match your search.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- ══════════════════════════════════════
         BORROWINGS TAB
         ══════════════════════════════════════ -->
    <div id="paneBorrowings" style="display:none">
        <?php if (empty($borrowings)): ?>
        <div class="dash-card p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-700 mb-1">No borrowing requests yet</h3>
            <p class="text-sm text-slate-400 font-medium">Requests from residents will appear here.</p>
        </div>
        <?php else: ?>

        <!-- Desktop table (md+) -->
        <div class="hidden md:block dash-card overflow-hidden">
            <div class="table-outer">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Resident</th>
                            <th>Book</th>
                            <th>Borrowed</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($borrowings as $i => $bw):
                        $s = strtolower($bw['status'] ?? 'pending');
                    ?>
                    <tr>
                        <td class="text-slate-400 font-bold text-xs"><?= $i + 1 ?></td>
                        <td>
                            <p class="font-bold text-sm text-slate-800"><?= htmlspecialchars($bw['resident_name'] ?? 'Unknown', ENT_QUOTES) ?></p>
                            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($bw['email'] ?? '', ENT_QUOTES) ?></p>
                        </td>
                        <td>
                            <p class="font-bold text-sm text-slate-800"><?= htmlspecialchars($bw['book_title'] ?? '', ENT_QUOTES) ?></p>
                            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($bw['book_author'] ?? '', ENT_QUOTES) ?></p>
                        </td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($bw['borrowed_at'] ?? '—', ENT_QUOTES) ?></td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($bw['due_date'] ?? '—', ENT_QUOTES) ?></td>
                        <td><span class="tag tag-<?= $s ?>"><?= ucfirst($s) ?></span></td>
                        <td>
                            <div class="flex gap-2">
                            <?php if ($s === 'pending'): ?>
                                <form method="post" action="/sk/borrowings/approve/<?= $bw['id'] ?>" style="display:inline">
                                    <?= csrf_field() ?><button class="px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-700 rounded-xl font-bold text-xs transition">Approve</button>
                                </form>
                                <form method="post" action="/sk/borrowings/reject/<?= $bw['id'] ?>" style="display:inline">
                                    <?= csrf_field() ?><button class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-xs transition">Reject</button>
                                </form>
                            <?php elseif ($s === 'approved'): ?>
                                <form method="post" action="/sk/borrowings/return/<?= $bw['id'] ?>" style="display:inline">
                                    <?= csrf_field() ?><button class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">Mark Returned</button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-slate-400 font-medium"><?= ucfirst($s) ?></span>
                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile borrow cards (below md) -->
        <div class="md:hidden space-y-3">
            <?php foreach ($borrowings as $bw):
                $s = strtolower($bw['status'] ?? 'pending');
            ?>
                <div class="borrow-card">
                    <!-- Header: resident + status -->
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-user text-purple-500 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-slate-800 truncate"><?= htmlspecialchars($bw['resident_name'] ?? 'Unknown', ENT_QUOTES) ?></p>
                            <p class="text-[11px] text-slate-400 truncate"><?= htmlspecialchars($bw['email'] ?? '', ENT_QUOTES) ?></p>
                        </div>
                        <span class="tag tag-<?= $s ?> flex-shrink-0"><?= ucfirst($s) ?></span>
                    </div>

                    <!-- Book info -->
                    <div class="flex items-center gap-1.5 mb-1">
                        <i class="fa-solid fa-book text-[10px] text-slate-400 flex-shrink-0"></i>
                        <p class="text-xs font-bold text-slate-700 truncate"><?= htmlspecialchars($bw['book_title'] ?? '', ENT_QUOTES) ?></p>
                    </div>

                    <!-- Dates -->
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-[10px] text-slate-400 font-semibold">Borrowed: <?= htmlspecialchars($bw['borrowed_at'] ?? '—', ENT_QUOTES) ?></span>
                        <span class="text-[10px] text-rose-500 font-bold">Due: <?= htmlspecialchars($bw['due_date'] ?? '—', ENT_QUOTES) ?></span>
                    </div>

                    <!-- Actions -->
                    <?php if ($s === 'pending'): ?>
                        <div class="flex gap-2 pt-2 border-t border-slate-100">
                            <form method="post" action="/sk/borrowings/approve/<?= $bw['id'] ?>" style="display:contents">
                                <?= csrf_field() ?><button class="flex-1 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-xl font-bold text-xs transition">Approve</button>
                            </form>
                            <form method="post" action="/sk/borrowings/reject/<?= $bw['id'] ?>" style="display:contents">
                                <?= csrf_field() ?><button class="flex-1 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-xs transition">Reject</button>
                            </form>
                        </div>
                    <?php elseif ($s === 'approved'): ?>
                        <div class="pt-2 border-t border-slate-100">
                            <form method="post" action="/sk/borrowings/return/<?= $bw['id'] ?>">
                                <?= csrf_field() ?><button class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">Mark Returned</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</main>

<script>
/* ──────────────────────────────────────────
   TABS
────────────────────────────────────────── */
const TAB_ACTIVE   = 'bg-green-600 text-white shadow-sm shadow-green-200';
const TAB_INACTIVE = 'text-slate-500 hover:text-slate-700 hover:bg-slate-50';

function switchTab(t) {
    document.getElementById('paneBooks').style.display      = t === 'books'      ? '' : 'none';
    document.getElementById('paneBorrowings').style.display = t === 'borrowings' ? '' : 'none';
    document.getElementById('tabBooks').className      = 'px-5 py-2.5 text-sm font-bold transition ' + (t === 'books'      ? TAB_ACTIVE : TAB_INACTIVE);
    document.getElementById('tabBorrowings').className = 'px-5 py-2.5 text-sm font-bold transition ' + (t === 'borrowings' ? TAB_ACTIVE : TAB_INACTIVE);
}
switchTab('books');
if (window.location.hash === '#borrowings') switchTab('borrowings');

/* ──────────────────────────────────────────
   MODAL HELPERS
────────────────────────────────────────── */
function openModal(id)  { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') ['addModal','editModal','pdfModal','bookDetailModal'].forEach(closeModal); });

function openBookDetail(b) {
    // Title / author
    document.getElementById('bdTitle').textContent  = b.title  || '—';
    document.getElementById('bdAuthor').textContent = b.author || '—';

    // Status bar
    const isActive = b.status === 'active';
    const bar = document.getElementById('bdStatusBar');
    bar.style.background = isActive ? '#dcfce7' : '#fee2e2';
    bar.style.color       = isActive ? '#166534' : '#991b1b';
    bar.innerHTML = `<i class="fa-solid ${isActive ? 'fa-circle-check' : 'fa-circle-xmark'}"></i>
        <span>${isActive ? 'Active' : 'Inactive'}</span>
        ${b.preface ? '<span class="ml-auto flex items-center gap-1 text-[11px]"><i class="fa-solid fa-wand-magic-sparkles text-green-500"></i> AI Ready</span>' : ''}`;

    // Detail fields
    document.getElementById('bdGenre').textContent  = b.genre          || '—';
    document.getElementById('bdYear').textContent   = b.published_year || '—';
    const avail = parseInt(b.available_copies ?? 0);
    const total = parseInt(b.total_copies ?? 1);
    document.getElementById('bdCopies').textContent = `${avail} available / ${total} total`;
    document.getElementById('bdIsbn').textContent   = b.isbn || '—';

    // Preface
    const prefaceWrap   = document.getElementById('bdPrefaceWrap');
    const noPrefaceWrap = document.getElementById('bdNoPrefaceWrap');
    if (b.preface && b.preface.trim()) {
        document.getElementById('bdPreface').textContent = b.preface.trim();
        prefaceWrap.style.display   = 'block';
        noPrefaceWrap.style.display = 'none';
    } else {
        prefaceWrap.style.display   = 'none';
        noPrefaceWrap.style.display = 'flex';
    }

    // Wire Edit button
    document.getElementById('bdEditBtn').onclick = () => {
        closeModal('bookDetailModal');
        openEditModal(b);
    };

    // Wire Delete form
    document.getElementById('bdDeleteForm').action = '/sk/books/delete/' + b.id;

    openModal('bookDetailModal');
}

function openEditModal(b) {
    document.getElementById('editTitle').value   = b.title          || '';
    document.getElementById('editAuthor').value  = b.author         || '';
    document.getElementById('editGenre').value   = b.genre          || '';
    document.getElementById('editYear').value    = b.published_year || '';
    document.getElementById('editCopies').value  = b.total_copies   || 1;
    document.getElementById('editStatus').value  = b.status         || 'active';
    document.getElementById('editPreface').value = b.preface        || '';
    document.getElementById('editForm').action   = '/sk/books/update/' + b.id;
    openModal('editModal');
}

/* ──────────────────────────────────────────
   FILTER BOOKS  (synced desktop + mobile)
────────────────────────────────────────── */
function filterBooks() {
    const q = document.getElementById('bookSearch').value.toLowerCase();

    // Desktop
    document.querySelectorAll('#booksTable tbody tr').forEach(r => {
        r.style.display = r.dataset.search.includes(q) ? '' : 'none';
    });

    // Mobile cards
    const cards = Array.from(document.querySelectorAll('#bookCardList .book-card'));
    let cardVisible = 0;
    cards.forEach(c => {
        const show = c.dataset.search.includes(q);
        c.style.display = show ? '' : 'none';
        if (show) cardVisible++;
    });

    const mobileEmpty = document.getElementById('booksMobileEmpty');
    if (mobileEmpty && cards.length > 0) mobileEmpty.style.display = cardVisible === 0 ? 'block' : 'none';
}

/* ──────────────────────────────────────────
   PDF UPLOAD + AI EXTRACTION
────────────────────────────────────────── */
let _pdfBase64 = null;
let _progressInterval = null;

const dropZone = document.getElementById('dropZone');
dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => { e.preventDefault(); dropZone.classList.remove('dragover'); const f = e.dataTransfer.files[0]; if (f) processPdfFile(f); });

function handlePdfFile(e) { const f = e.target.files[0]; if (f) processPdfFile(f); }

function processPdfFile(file) {
    if (file.type !== 'application/pdf') { alert('Please upload a PDF file.'); return; }
    if (file.size > 10 * 1024 * 1024)   { alert('File is too large. Maximum size is 10MB.'); return; }
    document.getElementById('filePreviewName').textContent = file.name;
    document.getElementById('filePreviewSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    document.getElementById('filePreview').classList.remove('hidden');
    document.getElementById('extractBtn').disabled = false;
    document.getElementById('dropZone').style.borderColor = '#16a34a';
    const reader = new FileReader();
    reader.onload = () => { _pdfBase64 = reader.result.split(',')[1]; };
    reader.readAsDataURL(file);
}

function clearPdfFile() {
    _pdfBase64 = null;
    document.getElementById('pdfFileInput').value = '';
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('extractBtn').disabled = true;
    document.getElementById('dropZone').style.borderColor = '';
}

function setStep(n) {
    [1,2,3].forEach(i => {
        const dot = document.getElementById('stepDot' + i);
        dot.className = 'step-dot ' + (i < n ? 'done' : i === n ? 'active' : 'pending');
    });
    [1,2].forEach(i => { document.getElementById('stepLine' + i).className = 'step-line ' + (i < n ? 'done' : 'pending'); });
    document.getElementById('stepLabel').textContent = { 1:'Upload PDF', 2:'AI Extracting…', 3:'Review & Save' }[n] || '';
}

function showPdfPanel(id) {
    ['pdfStep1','pdfStep2','pdfStep3','pdfStepError'].forEach(p => {
        document.getElementById(p).style.display = p === id ? '' : 'none';
    });
}

function animateProgress(targetPct, durationMs) {
    const fill = document.getElementById('aiProgressFill');
    const start = parseFloat(fill.style.width) || 0;
    const startTime = Date.now();
    clearInterval(_progressInterval);
    _progressInterval = setInterval(() => {
        const pct = Math.min(start + (targetPct - start) * ((Date.now() - startTime) / durationMs), targetPct);
        fill.style.width = pct + '%';
        if (pct >= targetPct) clearInterval(_progressInterval);
    }, 30);
}

function showExtractError(message, debugInfo) {
    clearInterval(_progressInterval);
    document.getElementById('pdfErrorText').textContent = message;
    const dbg = document.getElementById('debugPanel');
    if (debugInfo) { dbg.textContent = debugInfo; dbg.style.display = 'block'; }
    else { dbg.style.display = 'none'; }
    showPdfPanel('pdfStepError'); setStep(1);
}

async function extractFromPdf() {
    if (!_pdfBase64) return;
    setStep(2); showPdfPanel('pdfStep2');
    document.getElementById('debugPanel').style.display = 'none';
    animateProgress(40, 3000);

    const messages = ['Analyzing document structure…','Extracting title and author…','Identifying genre and year…','Generating description…','Finalizing extraction…'];
    let msgIdx = 0;
    const msgEl = document.getElementById('aiStatusText');
    const msgInterval = setInterval(() => { msgIdx = (msgIdx + 1) % messages.length; msgEl.textContent = messages[msgIdx]; }, 2200);

    try {
        animateProgress(75, 8000);
        const csrfName  = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content')  ?? 'csrf_test_name';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        const response  = await fetch('/sk/books/extract-pdf', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ pdf_base64: _pdfBase64 }),
        });
        const newToken = response.headers.get('X-CSRF-TOKEN');
        if (newToken) document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);

        clearInterval(msgInterval); animateProgress(95, 500);
        const contentType = response.headers.get('Content-Type') || '';
        if (!contentType.includes('application/json')) {
            const rawBody = await response.text();
            const snippet = rawBody.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim().slice(0, 300);
            const statusMessages = { 419:'CSRF token mismatch (419). Refresh the page and try again.', 403:'Access forbidden (403).', 401:'Session expired (401).', 404:'Endpoint not found (404).', 500:'Server error (500).' };
            showExtractError(statusMessages[response.status] ?? `Unexpected HTTP ${response.status} response.`, snippet || null);
            return;
        }
        const result = await response.json();
        if (!result.ok) { showExtractError(result.error || 'Extraction failed.', null); return; }
        animateProgress(100, 200);
        setTimeout(() => { populateExtractedFields(result.data); setStep(3); showPdfPanel('pdfStep3'); }, 400);
    } catch (err) {
        clearInterval(msgInterval);
        showExtractError('Network error: ' + err.message, null);
    }
}

function populateExtractedFields(data) {
    const fields = {
        pdfTitle:   { key:'title',          badge:'badgeTitle' },
        pdfAuthor:  { key:'author',         badge:'badgeAuthor' },
        pdfGenre:   { key:'genre',          badge:'badgeGenre' },
        pdfYear:    { key:'published_year', badge:'badgeYear' },
        pdfIsbn:    { key:'isbn',           badge:'badgeIsbn' },
        pdfPreface: { key:'preface',        badge:'badgePreface' },
    };
    let anyMissing = false;
    for (const [elId, cfg] of Object.entries(fields)) {
        const el = document.getElementById(elId), badge = document.getElementById(cfg.badge);
        const val = (data[cfg.key] || '').trim();
        el.value = val;
        if (val) { el.classList.add('field-filled'); if (badge) badge.style.display = 'inline-flex'; }
        else { el.classList.remove('field-filled'); if (badge) badge.style.display = 'none'; if (['title','author'].includes(cfg.key)) anyMissing = true; }
    }
    const note = document.getElementById('aiConfidenceNote');
    if (note) note.classList.toggle('hidden', !anyMissing);
}

function resetPdfModal() {
    _pdfBase64 = null; clearInterval(_progressInterval);
    document.getElementById('pdfFileInput').value = '';
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('extractBtn').disabled = true;
    document.getElementById('dropZone').style.borderColor = '';
    document.getElementById('aiProgressFill').style.width = '0%';
    document.getElementById('aiStatusText').textContent = 'Analyzing document structure…';
    document.getElementById('debugPanel').style.display = 'none';
    ['pdfTitle','pdfAuthor','pdfGenre','pdfYear','pdfIsbn','pdfPreface'].forEach(id => { const el = document.getElementById(id); if (el) { el.value = ''; el.classList.remove('field-filled'); } });
    ['badgeTitle','badgeAuthor','badgeGenre','badgeYear','badgeIsbn','badgePreface'].forEach(id => { const el = document.getElementById(id); if (el) el.style.display = 'none'; });
    const note = document.getElementById('aiConfidenceNote'); if (note) note.classList.add('hidden');
    setStep(1); showPdfPanel('pdfStep1');
}
</script>
</body>
</html>