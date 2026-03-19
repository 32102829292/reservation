<?php
/**
 * Views/admin/books.php
 * Admin (Chairman) — Library Management + PDF Upload & AI Extraction
 * Mobile-enhanced: card list, bottom-sheet modals, synced filter, responsive stat cards
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<title>Library Management | Admin</title>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name"  content="<?= csrf_token() ?>">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#2563eb">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; overflow-x: hidden; }

/* ── Sidebar ── */
.sidebar-card {
    background: white; border-radius: 32px; border: 1px solid #e2e8f0;
    height: calc(100vh - 48px); position: sticky; top: 24px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    display: flex; flex-direction: column; overflow: hidden; width: 100%;
}
.sidebar-header { flex-shrink: 0; padding: 20px 20px 16px; border-bottom: 1px solid #f1f5f9; }
.sidebar-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 10px; }
.sidebar-nav::-webkit-scrollbar { width: 4px; }
.sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
.sidebar-footer { flex-shrink: 0; padding: 16px; border-top: 1px solid #f1f5f9; }
.sidebar-item { transition: all 0.18s; }
.sidebar-item.active { background: #2563eb; color: white !important; box-shadow: 0 8px 20px -4px rgba(37,99,235,0.35); }

/* ── Mobile nav ── */
.mobile-nav-pill {
    position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
    width: 92%; max-width: 600px; background: rgba(15,23,42,0.97);
    backdrop-filter: blur(12px); border-radius: 24px; padding: 6px;
    z-index: 100; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
}
.mobile-scroll-container { display: flex; gap: 4px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
.mobile-scroll-container::-webkit-scrollbar { display: none; }

/* ── Stat cards ── */
.analytics-card {
    background: white; border-radius: 24px; padding: 1.25rem;
    border: 1px solid #e2e8f0; transition: all 0.2s;
}
.analytics-card:hover { transform: translateY(-2px); box-shadow: 0 12px 28px -6px rgba(0,0,0,0.08); }

/* ── Badges ── */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.25rem 0.65rem; border-radius: 8px;
    font-size: 0.67rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.04em; white-space: nowrap;
}
.badge-active   { background: #dcfce7; color: #166534; }
.badge-inactive { background: #fee2e2; color: #991b1b; }
.badge-pending  { background: #fef3c7; color: #92400e; }
.badge-approved { background: #dcfce7; color: #166534; }
.badge-returned { background: #dbeafe; color: #1d4ed8; }
.badge-rejected { background: #fee2e2; color: #991b1b; }
.badge-rag-yes  { background: #dcfce7; color: #166534; cursor: pointer; }
.badge-rag-no   { background: #f1f5f9; color: #64748b; }

/* ── Table ── */
table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 640px; }
thead { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
thead th { padding: 0.75rem 1rem; text-align: left; font-weight: 700; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; }
tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #f8fafc; }
td { padding: 0.8rem 1rem; vertical-align: middle; }

/* Actions column wraps gracefully */
.actions-cell { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }

/* ── Mobile book card ── */
.book-card {
    background: white; border: 1px solid #e2e8f0; border-radius: 20px;
    padding: 1rem 1.1rem; cursor: pointer; transition: all 0.18s;
    display: flex; flex-direction: column; gap: 0.5rem;
}
.book-card:active { transform: scale(0.985); background: #f8fafc; }
.book-card-row { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; }
.book-avatar {
    width: 40px; height: 40px; border-radius: 12px;
    background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 1rem;
}
.book-card-actions { display: flex; gap: 8px; margin-top: 0.25rem; }
.book-card-actions form,
.book-card-actions button { flex: 1; }
.book-card-actions button { width: 100%; }

/* ── Mobile borrowing card ── */
.borrow-card {
    background: white; border: 1px solid #e2e8f0; border-radius: 20px;
    padding: 1rem 1.1rem; transition: all 0.18s;
    display: flex; flex-direction: column; gap: 0.5rem;
}
.borrow-card-actions { display: flex; gap: 8px; margin-top: 0.25rem; }
.borrow-card-actions form,
.borrow-card-actions button { flex: 1; }
.borrow-card-actions button { width: 100%; }

/* ── Modal (centered, desktop default) ── */
.modal-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(15,23,42,0.55); backdrop-filter: blur(6px);
    z-index: 300; padding: 1.25rem; overflow-y: auto;
    align-items: center; justify-content: center;
}
.modal-backdrop.open { display: flex; animation: fadeIn 0.15s ease; }
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

.modal-box {
    position: relative; margin: auto;
    background: white; border-radius: 32px;
    width: 94%; max-width: 560px;
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35);
    animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1) both;
}
.modal-box.wide { max-width: 620px; }
.modal-box.xlg  { max-width: 680px; }
@keyframes popIn { from { opacity:0; transform: scale(0.92) translateY(16px); } to { opacity:1; transform: none; } }
.modal-box::-webkit-scrollbar { width: 4px; }
.modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

/* ── Bottom-sheet override on mobile ── */
@media (max-width: 639px) {
    /* Wrap aligns sheet to bottom */
    .modal-backdrop {
        padding: 0;
        align-items: flex-end;
    }
    .modal-box,
    .modal-box.wide,
    .modal-box.xlg {
        max-width: 100%;
        width: 100%;
        margin: 0;
        border-radius: 28px 28px 0 0;
        max-height: 92vh;
        animation: slideUp 0.28s cubic-bezier(0.32,0.72,0,1) both;
    }
    @keyframes slideUp {
        from { opacity:0; transform: translateY(100%); }
        to   { opacity:1; transform: translateY(0); }
    }
}

/* Drag-handle pill inside sheet header */
.sheet-handle {
    display: none;
    width: 40px; height: 5px; border-radius: 999px;
    background: #e2e8f0; margin: 0 auto 12px;
}
@media (max-width: 639px) {
    .sheet-handle { display: block; }
}

/* ── Form inputs ── */
.form-input {
    width: 100%; padding: 0.625rem 0.875rem;
    background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.875rem; color: #1e293b;
    outline: none; transition: border-color 0.2s, box-shadow 0.2s;
}
.form-input:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
textarea.form-input { resize: vertical; min-height: 90px; }

/* ── RAG preview ── */
.rag-preview {
    background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px;
    padding: 1rem; font-size: 0.8rem; font-family: monospace;
    white-space: pre-wrap; max-height: 260px; overflow-y: auto; color: #1e40af;
}

/* ── PDF Upload ── */
.drop-zone {
    border: 2.5px dashed #bfdbfe; border-radius: 20px; padding: 2.5rem 1.5rem;
    text-align: center; background: #eff6ff; cursor: pointer; transition: all 0.2s; position: relative;
}
.drop-zone:hover, .drop-zone.dragover { border-color: #2563eb; background: #dbeafe; }
.drop-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }

.ai-progress-bar { height: 4px; background: #e2e8f0; border-radius: 999px; overflow: hidden; margin-top: 8px; }
.ai-progress-fill {
    height: 100%; background: linear-gradient(90deg, #2563eb, #60a5fa, #2563eb);
    background-size: 200% 100%; border-radius: 999px;
    animation: shimmer 1.4s infinite; width: 0%; transition: width 0.4s ease;
}
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

.field-filled { border-color: #2563eb !important; background: #eff6ff !important; }
.field-badge-ai { display: inline-flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 999px; background: #dbeafe; color: #1d4ed8; margin-left: 6px; }

/* ── Stepper ── */
.step-dot { width: 28px; height: 28px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; flex-shrink: 0; transition: all 0.3s; }
.step-dot.done    { background: #2563eb; color: white; }
.step-dot.active  { background: #dbeafe; color: #2563eb; border: 2px solid #2563eb; }
.step-dot.pending { background: #f1f5f9; color: #94a3b8; }
.step-line { flex: 1; height: 2px; border-radius: 999px; transition: background 0.3s; }
.step-line.done    { background: #2563eb; }
.step-line.pending { background: #e2e8f0; }

/* ── Debug panel ── */
#adminDebugPanel { display: none; margin-top: 10px; padding: 10px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; font-size: 0.72rem; font-family: monospace; color: #991b1b; word-break: break-all; white-space: pre-wrap; max-height: 120px; overflow-y: auto; text-align: left; }

/* ── Misc ── */
@keyframes fadeUp { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: none; } }
.fade-up { animation: fadeUp 0.35s ease both; }

/* Result count chip */
#resultCount { display:inline-flex; align-items:center; font-size: 0.72rem; font-weight: 700; color: #64748b; }
</style>
</head>
<body class="flex min-h-screen">

<?php
$page = 'books';
$navItems = [
    ['url' => '/admin/dashboard',           'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
    ['url' => '/admin/new-reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'new-reservation'],
    ['url' => '/admin/manage-reservations', 'icon' => 'fa-calendar',        'label' => 'Reservations',    'key' => 'manage-reservations'],
    ['url' => '/admin/manage-pcs',          'icon' => 'fa-desktop',         'label' => 'Manage PCs',      'key' => 'manage-pcs'],
    ['url' => '/admin/manage-sk',           'icon' => 'fa-user-shield',     'label' => 'Manage SK',       'key' => 'manage-sk'],
    ['url' => '/admin/books',               'icon' => 'fa-book-open',       'label' => 'Library',         'key' => 'books'],
    ['url' => '/admin/login-logs',          'icon' => 'fa-clock',           'label' => 'Login Logs',      'key' => 'login-logs'],
    ['url' => '/admin/scanner',             'icon' => 'fa-qrcode',          'label' => 'Scanner',         'key' => 'scanner'],
    ['url' => '/admin/activity-logs',       'icon' => 'fa-list',            'label' => 'Activity Logs',   'key' => 'activity-logs'],
    ['url' => '/admin/profile',             'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
];

$totalBooks     = count($books ?? []);
$activeBooks    = count(array_filter($books ?? [], fn($b) => $b['status'] === 'active'));
$ragReady       = count(array_filter($books ?? [], fn($b) => !empty($b['preface'])));
$totalBorrows   = count($borrowings ?? []);
$pendingBorrows = count(array_filter($borrowings ?? [], fn($b) => $b['status'] === 'pending'));
?>

<!-- ══ ADD BOOK MODAL ══ -->
<div class="modal-backdrop" id="addModal">
    <div class="modal-box">
        <div class="px-7 pt-5 pb-0">
            <div class="sheet-handle"></div>
        </div>
        <div class="flex items-center justify-between px-7 pt-2 pb-4 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">New Entry</p>
                <h3 class="text-lg font-black text-slate-900">Add New Book</h3>
            </div>
            <button onclick="closeModal('addModal')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="post" action="/admin/books/store" class="px-7 py-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Title *</label>
                    <input class="form-input" name="title" required placeholder="Book title">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Author *</label>
                    <input class="form-input" name="author" required placeholder="Author name">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Genre</label>
                    <input class="form-input" name="genre" placeholder="e.g. Fiction">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Published Year</label>
                    <input class="form-input" name="published_year" type="number" min="1000" max="2099" placeholder="2024">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">ISBN</label>
                    <input class="form-input" name="isbn" placeholder="Optional">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Copies</label>
                    <input class="form-input" name="total_copies" type="number" min="1" value="1">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                    Preface / Description
                    <span class="ml-1 normal-case font-semibold text-blue-600">(powers AI suggestions)</span>
                </label>
                <textarea class="form-input" name="preface" rows="4" placeholder="Describe the book — the AI uses this to suggest books to residents…"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('addModal')"
                    class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 rounded-2xl font-bold text-slate-600 text-sm transition">Cancel</button>
                <button type="submit"
                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-blue-200">Add Book</button>
            </div>
        </form>
    </div>
</div>

<!-- ══ PDF UPLOAD + AI EXTRACTION MODAL ══ -->
<div class="modal-backdrop" id="pdfModal">
    <div class="modal-box xlg">
        <div class="px-7 pt-5 pb-0">
            <div class="sheet-handle"></div>
        </div>
        <div class="flex items-center justify-between px-7 pt-2 pb-4 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">AI Extraction</p>
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-wand-magic-sparkles text-blue-600 text-xs"></i>
                    </span>
                    Upload PDF — AI will extract details
                </h3>
            </div>
            <button onclick="closeModal('pdfModal')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition flex-shrink-0">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="px-7 py-6">
            <!-- Step Indicator -->
            <div class="flex items-center gap-2 mb-6">
                <div class="step-dot active" id="aStepDot1">1</div>
                <div class="step-line pending" id="aStepLine1"></div>
                <div class="step-dot pending" id="aStepDot2">2</div>
                <div class="step-line pending" id="aStepLine2"></div>
                <div class="step-dot pending" id="aStepDot3">3</div>
                <span class="text-xs font-semibold text-slate-400 ml-2" id="aStepLabel">Upload PDF</span>
            </div>

            <!-- Step 1: Drop Zone -->
            <div id="aPdfStep1">
                <div class="drop-zone" id="aDropZone">
                    <input type="file" id="aPdfFileInput" accept=".pdf" onchange="aHandlePdfFile(event)">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-file-pdf text-2xl text-blue-600"></i>
                    </div>
                    <p class="font-black text-slate-700 text-base mb-1">Drop your PDF here</p>
                    <p class="text-sm text-slate-400 font-medium mb-3">or click to browse files</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-500">
                        <i class="fa-solid fa-file-pdf text-red-400"></i> PDF files only · Max 10MB
                    </span>
                </div>

                <div id="aFilePreview" class="hidden mt-4 px-4 py-3 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file-pdf text-red-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="aFilePreviewName" class="font-bold text-sm text-slate-800 truncate"></p>
                        <p id="aFilePreviewSize" class="text-xs text-slate-400 mt-0.5"></p>
                    </div>
                    <button onclick="aClearPdfFile()" class="text-slate-400 hover:text-slate-600 transition text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <button id="aExtractBtn" onclick="aExtractFromPdf()" disabled
                    class="mt-5 w-full py-3.5 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed text-white rounded-2xl font-bold text-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Extract with AI
                </button>
            </div>

            <!-- Step 2: Processing -->
            <div id="aPdfStep2" style="display:none">
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-robot text-blue-500 text-2xl animate-pulse"></i>
                    </div>
                    <h4 class="font-black text-slate-800 text-base mb-1">AI is reading your PDF…</h4>
                    <p class="text-sm text-slate-400 font-medium" id="aAiStatusText">Analyzing document structure…</p>
                    <div class="ai-progress-bar mt-4 mx-auto max-w-xs">
                        <div class="ai-progress-fill" id="aAiProgressFill" style="width:15%"></div>
                    </div>
                    <p class="text-xs text-slate-300 font-semibold mt-3">This usually takes 5–15 seconds</p>
                </div>
            </div>

            <!-- Step 3: Review & Save -->
            <div id="aPdfStep3" style="display:none">
                <div class="flex items-center gap-2 mb-5 px-4 py-3 bg-blue-50 border border-blue-200 rounded-2xl">
                    <i class="fa-solid fa-circle-check text-blue-500"></i>
                    <p class="text-sm font-bold text-blue-700">AI extraction complete! Review and edit fields below, then save.</p>
                </div>

                <form method="post" action="/admin/books/store" id="aPdfBookForm" class="space-y-4">
                    <?= csrf_field() ?>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                Title * <span class="field-badge-ai" id="aBadgeTitle" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span>
                            </label>
                            <input class="form-input" name="title" id="aPdfTitle" required placeholder="Book title">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                Author * <span class="field-badge-ai" id="aBadgeAuthor" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span>
                            </label>
                            <input class="form-input" name="author" id="aPdfAuthor" required placeholder="Author name">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                Genre <span class="field-badge-ai" id="aBadgeGenre" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span>
                            </label>
                            <input class="form-input" name="genre" id="aPdfGenre" placeholder="e.g. Fiction">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                Published Year <span class="field-badge-ai" id="aBadgeYear" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span>
                            </label>
                            <input class="form-input" name="published_year" id="aPdfYear" type="number" min="1000" max="2099" placeholder="2024">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                                ISBN <span class="field-badge-ai" id="aBadgeIsbn" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span>
                            </label>
                            <input class="form-input" name="isbn" id="aPdfIsbn" placeholder="Optional">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Copies</label>
                            <input class="form-input" name="total_copies" id="aPdfCopies" type="number" min="1" value="1">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                            Preface / Description
                            <span class="field-badge-ai" id="aBadgePreface" style="display:none"><i class="fa-solid fa-wand-magic-sparkles text-[8px]"></i> AI</span>
                            <span class="ml-1 normal-case font-semibold text-blue-600">(powers AI suggestions)</span>
                        </label>
                        <textarea class="form-input" name="preface" id="aPdfPreface" rows="4" placeholder="AI-generated description…"></textarea>
                    </div>

                    <div id="aAiConfidenceNote" class="px-3 py-2.5 bg-amber-50 border border-amber-200 rounded-xl text-xs font-semibold text-amber-700 hidden">
                        <i class="fa-solid fa-circle-info mr-1.5"></i>
                        Almost there! Title or author wasn't detected — please fill those in, then hit Save.
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="aResetPdfModal()"
                            class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 rounded-2xl font-bold text-slate-600 text-sm transition">
                            <i class="fa-solid fa-rotate-left text-xs mr-1"></i> Try Another
                        </button>
                        <button type="submit"
                            class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-blue-200">
                            <i class="fa-solid fa-check mr-1"></i> Save Book
                        </button>
                    </div>
                </form>
            </div>

            <!-- Error State -->
            <div id="aPdfStepError" style="display:none">
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-red-400 text-2xl"></i>
                    </div>
                    <h4 class="font-black text-slate-800 text-base mb-1">Extraction Failed</h4>
                    <p class="text-sm text-red-500 font-bold mb-1" id="aPdfErrorText">Could not read the PDF.</p>
                    <div id="adminDebugPanel"></div>
                    <p class="text-xs text-slate-300 font-semibold mt-4 mb-5">Make sure the PDF has readable text (not a scanned image).</p>
                    <button onclick="aResetPdfModal()"
                        class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ EDIT BOOK MODAL ══ -->
<div class="modal-backdrop" id="editModal">
    <div class="modal-box">
        <div class="px-7 pt-5 pb-0">
            <div class="sheet-handle"></div>
        </div>
        <div class="flex items-center justify-between px-7 pt-2 pb-4 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Edit Entry</p>
                <h3 class="text-lg font-black text-slate-900">Edit Book</h3>
            </div>
            <button onclick="closeModal('editModal')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="post" id="editForm" action="" class="px-7 py-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Title *</label>
                    <input class="form-input" name="title" id="editTitle" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Author *</label>
                    <input class="form-input" name="author" id="editAuthor" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Genre</label>
                    <input class="form-input" name="genre" id="editGenre">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Published Year</label>
                    <input class="form-input" name="published_year" id="editYear" type="number">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Copies</label>
                    <input class="form-input" name="total_copies" id="editCopies" type="number" min="1">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Status</label>
                    <select class="form-input" name="status" id="editStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">
                    Preface / Description
                    <span class="ml-1 normal-case font-semibold text-blue-600">(AI context)</span>
                </label>
                <textarea class="form-input" name="preface" id="editPreface" rows="4"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('editModal')"
                    class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 rounded-2xl font-bold text-slate-600 text-sm transition">Cancel</button>
                <button type="submit"
                    class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-blue-200">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ══ RAG PREVIEW MODAL ══ -->
<div class="modal-backdrop" id="ragModal">
    <div class="modal-box wide">
        <div class="px-7 pt-5 pb-0">
            <div class="sheet-handle"></div>
        </div>
        <div class="flex items-center justify-between px-7 pt-2 pb-4 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">AI Preview</p>
                <h3 class="text-lg font-black text-slate-900">RAG Context Block</h3>
            </div>
            <button onclick="closeModal('ragModal')" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="px-7 py-6">
            <p class="text-xs text-slate-400 font-semibold mb-3">Exact context sent to the AI when this book is retrieved:</p>
            <div class="rag-preview" id="ragPreviewText"></div>
            <div class="flex justify-end mt-5">
                <button onclick="closeModal('ragModal')"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 rounded-2xl font-bold text-slate-600 text-sm transition">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ══ SIDEBAR ══ -->
<aside class="hidden lg:block w-80 flex-shrink-0 p-6">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <span class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase">Control Room</span>
            <h1 class="text-2xl font-extrabold text-slate-800">Admin<span class="text-blue-600">.</span></h1>
        </div>
        <nav class="sidebar-nav space-y-1">
            <?php foreach ($navItems as $item):
                $active = ($page === $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-blue-600';
            ?>
            <a href="<?= $item['url'] ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                <?= $item['label'] ?>
                <?php if ($item['key'] === 'books' && $pendingBorrows > 0): ?>
                <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingBorrows ?></span>
                <?php endif; ?>
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

<!-- ══ MOBILE NAV ══ -->
<nav class="lg:hidden mobile-nav-pill">
    <div class="mobile-scroll-container text-white px-2">
        <?php foreach ($navItems as $item):
            $btnClass = ($page === $item['key']) ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500/30';
        ?>
        <a href="<?= $item['url'] ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[68px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
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

<!-- ══ MAIN ══ -->
<main class="flex-1 min-w-0 p-4 lg:p-10 pb-32">

    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 fade-up">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Control Room</p>
            <h2 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                Library <span class="text-blue-600">Management</span>
            </h2>
            <p class="text-slate-400 font-medium text-sm mt-0.5"><?= date('l, F j, Y') ?></p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <?php if ($pendingBorrows > 0): ?>
            <span class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 px-3 py-2 rounded-2xl font-bold text-xs">
                <i class="fa-solid fa-clock text-xs"></i> <?= $pendingBorrows ?> pending
            </span>
            <?php endif; ?>
            <button onclick="openModal('pdfModal')"
                class="hidden sm:flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-blue-400 hover:bg-blue-50 text-slate-700 hover:text-blue-700 rounded-2xl font-bold text-sm transition">
                <i class="fa-solid fa-wand-magic-sparkles text-blue-500 text-xs"></i> Upload PDF
            </button>
            <button onclick="openModal('addModal')"
                class="hidden sm:flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition shadow-sm shadow-blue-200">
                <i class="fa-solid fa-plus text-xs"></i> Add Book
            </button>
        </div>
    </header>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold rounded-2xl flex items-center gap-3 text-sm">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3 text-sm">
        <i class="fa-solid fa-circle-exclamation text-red-400"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <!-- ── Stat cards: 2-col always, 4-col from sm ── -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="analytics-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-book text-blue-600"></i>
                </div>
                <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full"><?= $activeBooks ?> active</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Books</p>
            <p class="text-3xl font-black text-slate-800"><?= $totalBooks ?></p>
            <p class="text-[11px] text-slate-400 mt-1">in catalog</p>
        </div>
        <div class="analytics-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-bolt text-emerald-600"></i>
                </div>
                <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                    <?= $totalBooks > 0 ? round($ragReady / $totalBooks * 100) : 0 ?>%
                </span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">AI Ready</p>
            <p class="text-3xl font-black text-slate-800"><?= $ragReady ?></p>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width:<?= $totalBooks > 0 ? round($ragReady / $totalBooks * 100) : 0 ?>%"></div>
            </div>
        </div>
        <div class="analytics-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-purple-600"></i>
                </div>
                <span class="text-xs font-black text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">all time</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Borrows</p>
            <p class="text-3xl font-black text-slate-800"><?= $totalBorrows ?></p>
            <p class="text-[11px] text-slate-400 mt-1">borrowing requests</p>
        </div>
        <div class="analytics-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-2xl flex items-center justify-center">
                    <i class="fa-regular fa-clock text-amber-500"></i>
                </div>
                <span class="text-xs font-black text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">need action</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Pending</p>
            <p class="text-3xl font-black <?= $pendingBorrows > 0 ? 'text-amber-600' : 'text-slate-800' ?>"><?= $pendingBorrows ?></p>
            <p class="text-[11px] text-slate-400 mt-1">awaiting approval</p>
        </div>
    </div>

    <!-- ── Tabs ── -->
    <div class="flex rounded-2xl border border-slate-200 bg-white w-fit mb-6 overflow-hidden">
        <button id="tabBooks" onclick="switchTab('books')" class="px-5 py-2.5 text-sm font-bold transition">
            <i class="fa-solid fa-book text-xs mr-1.5"></i>Books Catalog
        </button>
        <button id="tabBorrowings" onclick="switchTab('borrowings')" class="px-5 py-2.5 text-sm font-bold transition">
            <i class="fa-solid fa-clock-rotate-left text-xs mr-1.5"></i>Borrowings
            <?php if ($pendingBorrows > 0): ?>
            <span class="ml-1.5 bg-amber-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full"><?= $pendingBorrows ?></span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ══ BOOKS PANE ══ -->
    <div id="paneBooks">
        <div class="flex gap-3 items-center flex-wrap mb-4">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input id="bookSearch" type="text" placeholder="Search title, author, genre…" oninput="applyFilter()"
                    class="w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
            </div>
            <!-- Result count (shown when filtering) -->
            <span id="resultCount" class="hidden text-slate-400 text-xs font-bold"></span>

            <div class="flex gap-2 sm:hidden">
                <button onclick="openModal('pdfModal')" class="px-3 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm transition">
                    <i class="fa-solid fa-wand-magic-sparkles text-blue-500 text-xs"></i>
                </button>
                <button onclick="openModal('addModal')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Add
                </button>
            </div>
        </div>

        <?php if (empty($books)): ?>
        <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-book-open text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-700 mb-1">No books yet</h3>
            <p class="text-sm text-slate-400 font-medium mb-4">Add the first book manually or upload a PDF.</p>
            <div class="flex gap-3 justify-center">
                <button onclick="openModal('pdfModal')" class="px-5 py-2.5 bg-white border border-slate-200 hover:border-blue-400 text-slate-700 rounded-2xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-blue-500 text-xs"></i> Upload PDF
                </button>
                <button onclick="openModal('addModal')" class="px-5 py-2.5 bg-blue-600 text-white rounded-2xl font-bold text-sm hover:bg-blue-700 transition">
                    <i class="fa-solid fa-plus mr-1.5"></i> Add Manually
                </button>
            </div>
        </div>
        <?php else: ?>

        <!-- ── Desktop table (md+) ── -->
        <div class="hidden md:block bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table id="booksTable">
                    <thead>
                        <tr>
                            <th>#</th><th>Title / Author</th><th>Genre</th><th>Year</th>
                            <th>Copies</th><th>RAG</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($books as $i => $b): ?>
                    <tr data-search="<?= strtolower(htmlspecialchars(($b['title'] ?? '') . ' ' . ($b['author'] ?? '') . ' ' . ($b['genre'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>">
                        <td class="text-slate-400 font-bold text-xs"><?= $i + 1 ?></td>
                        <td>
                            <p class="font-bold text-sm text-slate-800"><?= htmlspecialchars($b['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($b['author'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        </td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($b['genre'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($b['published_year'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <span class="text-sm font-black text-slate-800"><?= (int)($b['available_copies'] ?? 0) ?></span>
                            <span class="text-xs text-slate-400">/ <?= (int)($b['total_copies'] ?? 1) ?></span>
                        </td>
                        <td>
                            <?php if (!empty($b['preface'])): ?>
                            <button onclick="previewRag(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)"
                                class="badge badge-rag-yes hover:opacity-80 transition">
                                <i class="fa-solid fa-eye text-[9px]"></i>Preview
                            </button>
                            <?php else: ?>
                            <span class="badge badge-rag-no">No preface</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= $b['status'] === 'active' ? 'active' : 'inactive' ?>">
                                <?= ucfirst($b['status'] ?? 'inactive') ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">Edit</button>
                                <form method="post" action="/admin/books/delete/<?= $b['id'] ?>" style="display:inline"
                                      onsubmit="return confirm('Delete this book?')">
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

        <!-- ── Mobile card list (below md) ── -->
        <div class="md:hidden space-y-3" id="bookCardList">
            <?php foreach ($books as $i => $b): ?>
            <div class="book-card"
                 data-search="<?= strtolower(htmlspecialchars(($b['title'] ?? '') . ' ' . ($b['author'] ?? '') . ' ' . ($b['genre'] ?? ''), ENT_QUOTES, 'UTF-8')) ?>"
                 onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)">

                <!-- Top row: avatar + title/author + status badge -->
                <div class="book-card-row">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="book-avatar">
                            <i class="fa-solid fa-book text-blue-500"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-slate-800 truncate"><?= htmlspecialchars($b['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-slate-400 mt-0.5 truncate"><?= htmlspecialchars($b['author'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                    <span class="badge badge-<?= $b['status'] === 'active' ? 'active' : 'inactive' ?> flex-shrink-0">
                        <?= ucfirst($b['status'] ?? 'inactive') ?>
                    </span>
                </div>

                <!-- Meta row: genre · year · copies -->
                <div class="flex items-center gap-3 flex-wrap text-xs text-slate-400 font-medium px-0.5">
                    <?php if (!empty($b['genre'])): ?>
                    <span><i class="fa-solid fa-tag text-[10px] mr-1 text-slate-300"></i><?= htmlspecialchars($b['genre'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($b['published_year'])): ?>
                    <span><i class="fa-solid fa-calendar text-[10px] mr-1 text-slate-300"></i><?= htmlspecialchars($b['published_year'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <span>
                        <i class="fa-solid fa-copy text-[10px] mr-1 text-slate-300"></i>
                        <span class="font-black text-slate-700"><?= (int)($b['available_copies'] ?? 0) ?></span>
                        <span class="text-slate-300">/</span><?= (int)($b['total_copies'] ?? 1) ?> copies
                    </span>
                    <?php if (!empty($b['preface'])): ?>
                    <span class="badge badge-rag-yes !py-0.5" onclick="event.stopPropagation(); previewRag(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)">
                        <i class="fa-solid fa-eye text-[9px]"></i>RAG
                    </span>
                    <?php else: ?>
                    <span class="badge badge-rag-no !py-0.5">No preface</span>
                    <?php endif; ?>
                </div>

                <!-- Action buttons -->
                <div class="book-card-actions" onclick="event.stopPropagation()">
                    <button onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES, 'UTF-8') ?>)"
                        class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition text-center">
                        <i class="fa-solid fa-pen text-[10px] mr-1"></i> Edit
                    </button>
                    <form method="post" action="/admin/books/delete/<?= $b['id'] ?>"
                          onsubmit="return confirm('Delete this book?')">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="py-2 w-full bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-xs transition text-center">
                            <i class="fa-solid fa-trash text-[10px] mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- No results message (shared) -->
        <div id="noResultsMsg" class="hidden mt-6 text-center py-10 bg-white border border-slate-200 rounded-3xl">
            <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300 mb-2"></i>
            <p class="text-sm font-bold text-slate-500">No books match your search.</p>
        </div>

        <?php endif; ?>
    </div>

    <!-- ══ BORROWINGS PANE ══ -->
    <div id="paneBorrowings" style="display:none">
        <?php if (empty($borrowings)): ?>
        <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-clock-rotate-left text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-700 mb-1">No borrowing requests yet</h3>
            <p class="text-sm text-slate-400 font-medium">Requests from residents will appear here.</p>
        </div>
        <?php else: ?>

        <!-- Desktop table (md+) -->
        <div class="hidden md:block bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr><th>#</th><th>Resident</th><th>Book</th><th>Borrowed</th><th>Due Date</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($borrowings as $i => $bw):
                        $s = strtolower($bw['status'] ?? 'pending');
                    ?>
                    <tr>
                        <td class="text-slate-400 font-bold text-xs"><?= $i + 1 ?></td>
                        <td>
                            <p class="font-bold text-sm text-slate-800"><?= htmlspecialchars($bw['resident_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($bw['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        </td>
                        <td>
                            <p class="font-bold text-sm text-slate-800"><?= htmlspecialchars($bw['book_title'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($bw['book_author'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        </td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($bw['borrowed_at'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-sm text-slate-600 font-medium"><?= htmlspecialchars($bw['due_date'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><span class="badge badge-<?= $s ?>"><?= ucfirst($s) ?></span></td>
                        <td>
                            <div class="actions-cell">
                            <?php if ($s === 'pending'): ?>
                                <form method="post" action="/admin/borrowings/approve/<?= $bw['id'] ?>" style="display:inline">
                                    <?= csrf_field() ?>
                                    <button class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs transition">Approve</button>
                                </form>
                                <form method="post" action="/admin/borrowings/reject/<?= $bw['id'] ?>" style="display:inline">
                                    <?= csrf_field() ?>
                                    <button class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-xs transition">Reject</button>
                                </form>
                            <?php elseif ($s === 'approved'): ?>
                                <form method="post" action="/admin/borrowings/return/<?= $bw['id'] ?>" style="display:inline">
                                    <?= csrf_field() ?>
                                    <button class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">Mark Returned</button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-slate-400 font-semibold"><?= ucfirst($s) ?></span>
                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile borrowing cards (below md) -->
        <div class="md:hidden space-y-3">
            <?php foreach ($borrowings as $i => $bw):
                $s = strtolower($bw['status'] ?? 'pending');
            ?>
            <div class="borrow-card">
                <!-- Resident + book -->
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-slate-800 truncate"><?= htmlspecialchars($bw['resident_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-xs text-slate-400 mt-0.5 truncate"><?= htmlspecialchars($bw['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <span class="badge badge-<?= $s ?> flex-shrink-0"><?= ucfirst($s) ?></span>
                </div>

                <!-- Book info -->
                <div class="flex items-center gap-2 px-0.5">
                    <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-book text-blue-400 text-[10px]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-700 truncate"><?= htmlspecialchars($bw['book_title'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-[11px] text-slate-400 truncate"><?= htmlspecialchars($bw['book_author'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="flex gap-4 text-[11px] text-slate-400 font-medium px-0.5">
                    <span><i class="fa-solid fa-calendar-check text-[10px] mr-1 text-slate-300"></i><?= htmlspecialchars($bw['borrowed_at'] ?? '—', ENT_QUOTES, 'UTF-8') ?></span>
                    <span><i class="fa-solid fa-calendar-xmark text-[10px] mr-1 text-slate-300"></i>Due: <?= htmlspecialchars($bw['due_date'] ?? '—', ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <!-- Action buttons -->
                <?php if ($s === 'pending'): ?>
                <div class="borrow-card-actions">
                    <form method="post" action="/admin/borrowings/approve/<?= $bw['id'] ?>">
                        <?= csrf_field() ?>
                        <button class="py-2.5 w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl font-bold text-xs transition">
                            <i class="fa-solid fa-check mr-1"></i> Approve
                        </button>
                    </form>
                    <form method="post" action="/admin/borrowings/reject/<?= $bw['id'] ?>">
                        <?= csrf_field() ?>
                        <button class="py-2.5 w-full bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-xs transition">
                            <i class="fa-solid fa-xmark mr-1"></i> Reject
                        </button>
                    </form>
                </div>
                <?php elseif ($s === 'approved'): ?>
                <div class="borrow-card-actions">
                    <form method="post" action="/admin/borrowings/return/<?= $bw['id'] ?>">
                        <?= csrf_field() ?>
                        <button class="py-2.5 w-full bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">
                            <i class="fa-solid fa-rotate-left text-[10px] mr-1"></i> Mark Returned
                        </button>
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
/* ── Tab constants ── */
const CLS_ACTIVE   = 'bg-blue-600 text-white shadow-sm shadow-blue-200';
const CLS_INACTIVE = 'text-slate-500 hover:text-slate-700 hover:bg-slate-50';

function switchTab(t) {
    document.getElementById('paneBooks').style.display      = t === 'books'      ? '' : 'none';
    document.getElementById('paneBorrowings').style.display = t === 'borrowings' ? '' : 'none';
    document.getElementById('tabBooks').className      = 'px-5 py-2.5 text-sm font-bold transition ' + (t === 'books'      ? CLS_ACTIVE : CLS_INACTIVE);
    document.getElementById('tabBorrowings').className = 'px-5 py-2.5 text-sm font-bold transition ' + (t === 'borrowings' ? CLS_ACTIVE : CLS_INACTIVE);
}
switchTab('books');
if (window.location.hash === '#borrowings') switchTab('borrowings');

/* ── Modal helpers ── */
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }

document.querySelectorAll('.modal-backdrop').forEach(function(el) {
    el.addEventListener('click', function(e) { if (e.target === this) closeModal(this.id); });
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') ['addModal','editModal','ragModal','pdfModal'].forEach(closeModal);
});

/* ── Edit modal ── */
function openEditModal(b) {
    document.getElementById('editTitle').value   = b.title          || '';
    document.getElementById('editAuthor').value  = b.author         || '';
    document.getElementById('editGenre').value   = b.genre          || '';
    document.getElementById('editYear').value    = b.published_year || '';
    document.getElementById('editCopies').value  = b.total_copies   || 1;
    document.getElementById('editStatus').value  = b.status         || 'active';
    document.getElementById('editPreface').value = b.preface        || '';
    document.getElementById('editForm').action   = '/admin/books/update/' + b.id;
    openModal('editModal');
}

/* ── RAG preview ── */
function previewRag(b) {
    var avail = parseInt(b.available_copies || 0) > 0 ? 'Available' : 'Currently checked out';
    document.getElementById('ragPreviewText').textContent =
        '[1] "' + b.title + '" by ' + (b.author || 'Unknown') +
        '\nGenre: ' + (b.genre || 'General') + ' | Year: ' + (b.published_year || 'N/A') + ' | ' + avail +
        '\n\nDescription:\n' + (b.preface || '(No preface)');
    openModal('ragModal');
}

/* ── Unified filter: syncs desktop table rows AND mobile cards ── */
function applyFilter() {
    var q = document.getElementById('bookSearch').value.toLowerCase().trim();

    /* desktop table rows */
    var tableRows = document.querySelectorAll('#booksTable tbody tr');
    /* mobile cards */
    var cards = document.querySelectorAll('#bookCardList .book-card');

    var visibleCount = 0;

    tableRows.forEach(function(r) {
        var match = !q || r.dataset.search.includes(q);
        r.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });

    cards.forEach(function(c) {
        var match = !q || c.dataset.search.includes(q);
        c.style.display = match ? '' : 'none';
    });

    /* result count chip */
    var countEl = document.getElementById('resultCount');
    var noMsg   = document.getElementById('noResultsMsg');

    if (q) {
        countEl.textContent = visibleCount + ' result' + (visibleCount !== 1 ? 's' : '');
        countEl.classList.remove('hidden');
        if (noMsg) noMsg.classList.toggle('hidden', visibleCount > 0);
    } else {
        countEl.classList.add('hidden');
        if (noMsg) noMsg.classList.add('hidden');
    }
}

/* ══════════════════════════════════════════
   ADMIN PDF UPLOAD + AI EXTRACTION
══════════════════════════════════════════ */
var _aPdfBase64 = null;
var _aProgressInterval = null;

var aDropZone = document.getElementById('aDropZone');
if (aDropZone) {
    aDropZone.addEventListener('dragover',  function(e) { e.preventDefault(); aDropZone.classList.add('dragover'); });
    aDropZone.addEventListener('dragleave', function()  { aDropZone.classList.remove('dragover'); });
    aDropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        aDropZone.classList.remove('dragover');
        var file = e.dataTransfer.files[0];
        if (file) aProcessPdfFile(file);
    });
}

function aHandlePdfFile(e) {
    var file = e.target.files[0];
    if (file) aProcessPdfFile(file);
}

function aProcessPdfFile(file) {
    if (file.type !== 'application/pdf') { alert('Please upload a PDF file.'); return; }
    if (file.size > 10 * 1024 * 1024)   { alert('File is too large. Maximum size is 10MB.'); return; }

    document.getElementById('aFilePreviewName').textContent = file.name;
    document.getElementById('aFilePreviewSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    document.getElementById('aFilePreview').classList.remove('hidden');
    document.getElementById('aExtractBtn').disabled = false;
    document.getElementById('aDropZone').style.borderColor = '#2563eb';

    var reader = new FileReader();
    reader.onload = function() { _aPdfBase64 = reader.result.split(',')[1]; };
    reader.readAsDataURL(file);
}

function aClearPdfFile() {
    _aPdfBase64 = null;
    document.getElementById('aPdfFileInput').value = '';
    document.getElementById('aFilePreview').classList.add('hidden');
    document.getElementById('aExtractBtn').disabled = true;
    document.getElementById('aDropZone').style.borderColor = '';
}

function aSetStep(n) {
    [1,2,3].forEach(function(i) {
        var dot = document.getElementById('aStepDot' + i);
        dot.className = 'step-dot ' + (i < n ? 'done' : i === n ? 'active' : 'pending');
    });
    [1,2].forEach(function(i) {
        document.getElementById('aStepLine' + i).className = 'step-line ' + (i < n ? 'done' : 'pending');
    });
    var labels = {1:'Upload PDF', 2:'AI Extracting…', 3:'Review & Save'};
    document.getElementById('aStepLabel').textContent = labels[n] || '';
}

function aShowPdfPanel(id) {
    ['aPdfStep1','aPdfStep2','aPdfStep3','aPdfStepError'].forEach(function(p) {
        document.getElementById(p).style.display = p === id ? '' : 'none';
    });
}

function aAnimateProgress(targetPct, durationMs) {
    var fill = document.getElementById('aAiProgressFill');
    var start = parseFloat(fill.style.width) || 0;
    var startTime = Date.now();
    clearInterval(_aProgressInterval);
    _aProgressInterval = setInterval(function() {
        var pct = Math.min(start + (targetPct - start) * ((Date.now() - startTime) / durationMs), targetPct);
        fill.style.width = pct + '%';
        if (pct >= targetPct) clearInterval(_aProgressInterval);
    }, 30);
}

function aShowExtractError(message, debugInfo) {
    clearInterval(_aProgressInterval);
    document.getElementById('aPdfErrorText').textContent = message;
    var dbg = document.getElementById('adminDebugPanel');
    if (debugInfo) { dbg.textContent = debugInfo; dbg.style.display = 'block'; }
    else           { dbg.style.display = 'none'; }
    aShowPdfPanel('aPdfStepError');
    aSetStep(1);
}

async function aExtractFromPdf() {
    if (!_aPdfBase64) return;

    aSetStep(2);
    aShowPdfPanel('aPdfStep2');
    document.getElementById('adminDebugPanel').style.display = 'none';
    aAnimateProgress(40, 3000);

    var messages = ['Analyzing document structure…','Extracting title and author…','Identifying genre and year…','Generating description…','Finalizing extraction…'];
    var msgIdx = 0;
    var msgEl  = document.getElementById('aAiStatusText');
    var msgInterval = setInterval(function() {
        msgIdx = (msgIdx + 1) % messages.length;
        msgEl.textContent = messages[msgIdx];
    }, 2200);

    try {
        aAnimateProgress(75, 8000);

        var csrfName  = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content')  || 'csrf_test_name';
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        var response = await fetch('/admin/books/extract-pdf', {
            method:  'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN':     csrfToken,
            },
            body: JSON.stringify({ pdf_base64: _aPdfBase64 }),
        });

        var newToken = response.headers.get('X-CSRF-TOKEN');
        if (newToken) document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);

        clearInterval(msgInterval);
        aAnimateProgress(95, 500);

        var ct = response.headers.get('Content-Type') || '';
        if (!ct.includes('application/json')) {
            var rawBody = await response.text();
            var snippet = rawBody.replace(/<[^>]*>/g,' ').replace(/\s+/g,' ').trim().slice(0,300);
            var statusMsg = {419:'CSRF expired — refresh and try again.',403:'Access forbidden.',401:'Session expired — log in again.',404:'Route not found — check Routes.php.',500:'Server error — check writable/logs.'}[response.status] || ('HTTP ' + response.status);
            aShowExtractError(statusMsg, snippet);
            return;
        }

        var rawBody = await response.text();
        var result;
        try { result = JSON.parse(rawBody); }
        catch(e) { aShowExtractError('Server returned invalid JSON.', rawBody.slice(0,300)); return; }

        if (!result.ok) {
            aShowExtractError(result.error || 'Server returned ok:false with no error message.', null);
            return;
        }

        aAnimateProgress(100, 200);
        setTimeout(function() {
            aPopulateExtractedFields(result.data);
            aSetStep(3);
            aShowPdfPanel('aPdfStep3');
        }, 400);

    } catch(err) {
        clearInterval(msgInterval);
        aShowExtractError('Network error: ' + err.message, null);
    }
}

function aPopulateExtractedFields(data) {
    var fields = {
        aPdfTitle:   { key: 'title',          badge: 'aBadgeTitle' },
        aPdfAuthor:  { key: 'author',         badge: 'aBadgeAuthor' },
        aPdfGenre:   { key: 'genre',          badge: 'aBadgeGenre' },
        aPdfYear:    { key: 'published_year', badge: 'aBadgeYear' },
        aPdfIsbn:    { key: 'isbn',           badge: 'aBadgeIsbn' },
        aPdfPreface: { key: 'preface',        badge: 'aBadgePreface' },
    };

    var anyMissing = false;

    Object.entries(fields).forEach(function([elId, cfg]) {
        var el    = document.getElementById(elId);
        var badge = document.getElementById(cfg.badge);
        var val   = (data[cfg.key] || '').trim();
        el.value = val;
        if (val) {
            el.classList.add('field-filled');
            if (badge) badge.style.display = 'inline-flex';
        } else {
            el.classList.remove('field-filled');
            if (badge) badge.style.display = 'none';
            if (['title','author'].includes(cfg.key)) anyMissing = true;
        }
    });

    var note = document.getElementById('aAiConfidenceNote');
    if (note) note.classList.toggle('hidden', !anyMissing);
}

function aResetPdfModal() {
    _aPdfBase64 = null;
    clearInterval(_aProgressInterval);
    document.getElementById('aPdfFileInput').value          = '';
    document.getElementById('aFilePreview').classList.add('hidden');
    document.getElementById('aExtractBtn').disabled         = true;
    document.getElementById('aDropZone').style.borderColor  = '';
    document.getElementById('aAiProgressFill').style.width  = '0%';
    document.getElementById('aAiStatusText').textContent    = 'Analyzing document structure…';
    document.getElementById('adminDebugPanel').style.display = 'none';

    ['aPdfTitle','aPdfAuthor','aPdfGenre','aPdfYear','aPdfIsbn','aPdfPreface'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) { el.value = ''; el.classList.remove('field-filled'); }
    });
    ['aBadgeTitle','aBadgeAuthor','aBadgeGenre','aBadgeYear','aBadgeIsbn','aBadgePreface'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    var note = document.getElementById('aAiConfidenceNote');
    if (note) note.classList.add('hidden');

    aSetStep(1);
    aShowPdfPanel('aPdfStep1');
}
</script>
</body>
</html>