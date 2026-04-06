<?php
$page            = 'books';
$books           = $books       ?? [];
$borrowings      = $borrowings  ?? [];
$totalBooks      = count($books);
$activeBooks     = count(array_filter($books, fn($b) => ($b['status'] ?? '') === 'active'));
$ragReady        = count(array_filter($books, fn($b) => !empty($b['preface'])));
$totalBorrows    = count($borrowings);
$pendingBorrows  = count(array_filter($borrowings, fn($b) => ($b['status'] ?? '') === 'pending'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
  <title>Library | SK Officer</title>
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <script>
    (function() {
      try { if (localStorage.getItem('sk_theme') === 'dark') document.documentElement.classList.add('dark-pre'); } catch(e) {}
    })();
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <link rel="stylesheet" href="<?= base_url('css/sk_app.css') ?>">
  <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
  <style>
    /* ── Responsive: desktop table vs mobile cards ── */
    #desktopTable  { display: none; }
    #bookCardList  { display: flex; flex-direction: column; gap: 10px; }
    #desktopBorrowTable { display: none; }
    #borrowCardList { display: flex; flex-direction: column; gap: 10px; }

    @media (min-width: 768px) {
      #desktopTable       { display: block; }
      #bookCardList       { display: none !important; }
      #desktopBorrowTable { display: block; }
      #borrowCardList     { display: none !important; }
    }
  </style>
</head>
<body>

<?php include APPPATH . 'Views/partials/sk_layout.php'; ?>

<!-- ═══════════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════════ -->
<div id="deleteModal" class="modal-back" onclick="if(event.target===this)closeModal('deleteModal')">
  <div class="modal-card sm">
    <div style="text-align:center;margin-bottom:16px;">
      <div style="width:52px;height:52px;background:#fef2f2;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
        <i class="fa-solid fa-trash" style="color:#ef4444;font-size:1.1rem;"></i>
      </div>
      <div class="card-title" style="margin-bottom:4px;">Delete Book?</div>
      <div class="card-sub">This will permanently remove:</div>
      <div id="deleteBookTitle" style="margin:12px 0;font-weight:700;font-size:.9rem;background:#f8fafc;border:1px solid rgba(99,102,241,.08);border-radius:var(--r-sm);padding:10px 14px;">—</div>
      <div style="font-size:.72rem;color:#94a3b8;font-weight:500;">This cannot be undone.</div>
    </div>
    <div style="display:flex;gap:10px;margin-top:8px;">
      <button onclick="closeModal('deleteModal')" class="modal-cancel" style="flex:1;">Cancel</button>
      <form id="deleteForm" method="post" action="" style="flex:1;">
        <?= csrf_field() ?>
        <button type="submit" class="modal-danger" style="width:100%;"><i class="fa-solid fa-trash" style="font-size:.75rem;margin-right:5px;"></i>Delete</button>
      </form>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════
     ADD MODAL
═══════════════════════════════════════════ -->
<div id="addModal" class="modal-back" onclick="if(event.target===this)closeModal('addModal')">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div class="modal-title-lbl">New Entry</div>
        <div class="modal-title">Add New Book</div>
      </div>
      <button onclick="closeModal('addModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.8rem;"></i></button>
    </div>
    <form method="post" action="/sk/books/store">
      <?= csrf_field() ?>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Title *</label><input class="form-input" name="title" required placeholder="Book title"></div>
        <div><label class="form-lbl">Author *</label><input class="form-input" name="author" required placeholder="Author name"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Genre</label><input class="form-input" name="genre" placeholder="e.g. Fiction"></div>
        <div><label class="form-lbl">Published Year</label><input class="form-input" name="published_year" type="number" min="1000" max="2099" placeholder="2024"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">ISBN</label><input class="form-input" name="isbn" placeholder="Optional"></div>
        <div><label class="form-lbl">Total Copies</label><input class="form-input" name="total_copies" type="number" min="1" value="1"></div>
      </div>
      <div style="margin-bottom:14px;"><label class="form-lbl">Call Number</label><input class="form-input" style="font-family:var(--mono);" name="call_number" placeholder="e.g. 823.914"></div>
      <div style="margin-bottom:20px;"><label class="form-lbl">Preface / Description</label><textarea class="form-input" name="preface" rows="3" placeholder="Describe the book…"></textarea></div>
      <div style="display:flex;gap:10px;">
        <button type="button" onclick="closeModal('addModal')" class="modal-cancel" style="flex:1;">Cancel</button>
        <button type="submit" class="modal-submit" style="flex:1;"><i class="fa-solid fa-plus" style="font-size:.75rem;margin-right:5px;"></i>Add Book</button>
      </div>
    </form>
  </div>
</div>

<!-- ═══════════════════════════════════════════
     PDF / AI MODAL
═══════════════════════════════════════════ -->
<div id="pdfModal" class="modal-back" onclick="if(event.target===this)closeModal('pdfModal')">
  <div class="modal-card wide">
    <div class="modal-head">
      <div style="display:flex;align-items:center;gap:12px;">
        <div class="card-icon" style="background:var(--indigo-light);"><i class="fa-solid fa-wand-magic-sparkles" style="color:var(--indigo);font-size:.9rem;"></i></div>
        <div>
          <div class="modal-title-lbl">AI Extraction</div>
          <div class="modal-title">Upload PDF — AI extracts details</div>
        </div>
      </div>
      <button onclick="closeModal('pdfModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.8rem;"></i></button>
    </div>
    <div style="display:flex;align-items:center;gap:6px;margin-bottom:22px;">
      <div class="step-dot active" id="aStepDot1">1</div>
      <div class="step-line pending" id="aStepLine1"></div>
      <div class="step-dot pending" id="aStepDot2">2</div>
      <div class="step-line pending" id="aStepLine2"></div>
      <div class="step-dot pending" id="aStepDot3">3</div>
      <span style="font-size:.72rem;font-weight:600;color:#94a3b8;margin-left:8px;" id="aStepLabel">Upload PDF</span>
    </div>
    <div id="aPdfStep1">
      <div class="drop-zone" id="aDropZone">
        <input type="file" id="aPdfFileInput" accept=".pdf" onchange="aHandlePdfFile(event)">
        <div class="card-icon" style="background:var(--indigo);width:48px;height:48px;border-radius:var(--r-md);margin:0 auto 12px;"><i class="fa-solid fa-file-pdf" style="color:white;font-size:1.1rem;"></i></div>
        <p style="font-weight:800;font-size:.9rem;margin-bottom:4px;">Drop your PDF here</p>
        <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-bottom:12px;">or click to browse files</p>
        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;background:white;border:1px solid rgba(99,102,241,.15);border-radius:var(--r-sm);font-size:.72rem;font-weight:700;color:#475569;"><i class="fa-solid fa-file-pdf" style="color:#ef4444;font-size:.75rem;"></i>PDF files only · Max 10MB</span>
      </div>
      <div style="margin-top:10px;display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--indigo-light);border:1px solid var(--indigo-border);border-radius:var(--r-sm);">
        <i class="fa-solid fa-shield-halved" style="color:var(--indigo);font-size:.8rem;flex-shrink:0;"></i>
        <p style="font-size:.72rem;font-weight:600;color:#3730a3;">Text is extracted locally in your browser — only extracted text is sent to the AI.</p>
      </div>
      <div id="aFilePreview" class="hidden" style="margin-top:12px;padding:12px 14px;background:#f8fafc;border-radius:var(--r-md);border:1px solid rgba(99,102,241,.08);display:flex;align-items:center;gap:12px;">
        <div class="card-icon" style="background:#fee2e2;"><i class="fa-solid fa-file-pdf" style="color:#ef4444;font-size:.9rem;"></i></div>
        <div style="flex:1;min-width:0;">
          <p id="aFilePreviewName" style="font-weight:700;font-size:.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></p>
          <p id="aFilePreviewSize" style="font-size:.7rem;color:#94a3b8;margin-top:2px;"></p>
        </div>
        <button onclick="aClearPdfFile()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:.85rem;"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <button id="aExtractBtn" onclick="aExtractFromPdf()" disabled style="margin-top:16px;width:100%;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;font-size:.88rem;border:none;cursor:pointer;font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:8px;transition:all var(--ease);box-shadow:0 3px 10px rgba(55,48,163,.25);">
        <i class="fa-solid fa-wand-magic-sparkles" style="font-size:.8rem;"></i> Extract with AI
      </button>
    </div>
    <div id="aPdfStep2" style="display:none;text-align:center;padding:24px 0;">
      <div class="card-icon" style="background:var(--indigo-light);width:52px;height:52px;border-radius:var(--r-md);margin:0 auto 14px;"><i class="fa-solid fa-robot" style="color:var(--indigo);font-size:1.2rem;"></i></div>
      <p style="font-weight:800;font-size:.9rem;margin-bottom:4px;">AI is reading your PDF…</p>
      <p style="font-size:.78rem;color:#94a3b8;font-weight:500;" id="aAiStatusText">Extracting text…</p>
      <div class="ai-progress-bar" style="max-width:280px;margin:12px auto 0;">
        <div class="ai-progress-fill" id="aAiProgressFill" style="width:10%"></div>
      </div>
    </div>
    <div id="aPdfStep3" style="display:none;">
      <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;background:#f0fdf4;border:1px solid #86efac;border-radius:var(--r-md);margin-bottom:18px;">
        <i class="fa-solid fa-circle-check" style="color:#22c55e;font-size:.9rem;"></i>
        <p style="font-size:.82rem;font-weight:700;color:#14532d;">AI extraction complete! Review and edit below, then save.</p>
      </div>
      <form method="post" action="/sk/books/store" id="aPdfBookForm">
        <?= csrf_field() ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
          <div><label class="form-lbl">Title * <span class="field-badge-ai" id="aBadgeTitle" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="title" id="aPdfTitle" required placeholder="Book title"></div>
          <div><label class="form-lbl">Author * <span class="field-badge-ai" id="aBadgeAuthor" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="author" id="aPdfAuthor" required placeholder="Author name"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
          <div><label class="form-lbl">Genre <span class="field-badge-ai" id="aBadgeGenre" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="genre" id="aPdfGenre" placeholder="e.g. Fiction"></div>
          <div><label class="form-lbl">Year <span class="field-badge-ai" id="aBadgeYear" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="published_year" id="aPdfYear" type="number" min="1000" max="2099" placeholder="2024"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
          <div><label class="form-lbl">ISBN <span class="field-badge-ai" id="aBadgeIsbn" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" name="isbn" id="aPdfIsbn" placeholder="Optional"></div>
          <div><label class="form-lbl">Total Copies</label><input class="form-input" name="total_copies" id="aPdfCopies" type="number" min="1" value="1"></div>
        </div>
        <div style="margin-bottom:14px;"><label class="form-lbl">Call Number <span class="field-badge-ai" id="aBadgeCallNumber" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><input class="form-input" style="font-family:var(--mono);" name="call_number" id="aPdfCallNumber" placeholder="e.g. 823.914"></div>
        <div style="margin-bottom:14px;"><label class="form-lbl">Preface / Description <span class="field-badge-ai" id="aBadgePreface" style="display:none"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI</span></label><textarea class="form-input" name="preface" id="aPdfPreface" rows="3"></textarea></div>
        <div id="aAiConfidenceNote" class="hidden" style="margin-bottom:14px;padding:10px 14px;background:#fef3c7;border:1px solid #fde68a;border-radius:var(--r-sm);font-size:.75rem;font-weight:600;color:#92400e;"><i class="fa-solid fa-circle-info" style="margin-right:6px;"></i>Title or author wasn't detected — please fill those in before saving.</div>
        <div style="display:flex;gap:10px;">
          <button type="button" onclick="aResetPdfModal()" class="modal-cancel" style="flex:1;"><i class="fa-solid fa-rotate-left" style="font-size:.75rem;margin-right:5px;"></i>Try Another</button>
          <button type="submit" class="modal-submit" style="flex:1;"><i class="fa-solid fa-check" style="font-size:.75rem;margin-right:5px;"></i>Save Book</button>
        </div>
      </form>
    </div>
    <div id="aPdfStepError" style="display:none;text-align:center;padding:24px 0;">
      <div class="card-icon" style="background:#fef2f2;width:52px;height:52px;border-radius:var(--r-md);margin:0 auto 14px;"><i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;font-size:1.1rem;"></i></div>
      <p style="font-weight:800;font-size:.9rem;margin-bottom:4px;">Extraction Failed</p>
      <p style="font-size:.82rem;font-weight:700;color:#ef4444;margin-bottom:4px;" id="aPdfErrorText">Could not read the PDF.</p>
      <div id="skDebugPanel" style="display:none;margin-top:10px;padding:10px 14px;background:#fef2f2;border:1px solid #fca5a5;border-radius:var(--r-sm);font-size:.72rem;font-family:var(--mono);color:#991b1b;word-break:break-all;white-space:pre-wrap;max-height:120px;overflow-y:auto;"></div>
      <p style="font-size:.72rem;color:#94a3b8;margin:12px 0 20px;">Make sure the PDF has readable text (not a scanned image).</p>
      <button onclick="aResetPdfModal()" class="modal-cancel" style="display:inline-block;width:auto;padding:10px 24px;">Try Again</button>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════
     EDIT MODAL
═══════════════════════════════════════════ -->
<div id="editModal" class="modal-back" onclick="if(event.target===this)closeModal('editModal')">
  <div class="modal-card">
    <div class="modal-head">
      <div>
        <div class="modal-title-lbl">Edit Entry</div>
        <div class="modal-title">Edit Book</div>
      </div>
      <button onclick="closeModal('editModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.8rem;"></i></button>
    </div>
    <form method="post" id="editForm" action="">
      <?= csrf_field() ?>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Title *</label><input class="form-input" name="title" id="editTitle" required></div>
        <div><label class="form-lbl">Author *</label><input class="form-input" name="author" id="editAuthor" required></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Genre</label><input class="form-input" name="genre" id="editGenre"></div>
        <div><label class="form-lbl">Published Year</label><input class="form-input" name="published_year" id="editYear" type="number"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
        <div><label class="form-lbl">Total Copies</label><input class="form-input" name="total_copies" id="editCopies" type="number" min="1"></div>
        <div><label class="form-lbl">Status</label>
          <select class="form-input" name="status" id="editStatus">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div style="margin-bottom:14px;"><label class="form-lbl">Call Number</label><input class="form-input" style="font-family:var(--mono);" name="call_number" id="editCallNumber" placeholder="e.g. 823.914"></div>
      <div style="margin-bottom:20px;"><label class="form-lbl">Preface / Description</label><textarea class="form-input" name="preface" id="editPreface" rows="3"></textarea></div>
      <div style="display:flex;gap:10px;">
        <button type="button" onclick="closeModal('editModal')" class="modal-cancel" style="flex:1;">Cancel</button>
        <button type="submit" class="modal-submit" style="flex:1;"><i class="fa-solid fa-check" style="font-size:.75rem;margin-right:5px;"></i>Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- ═══════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════ -->
<main class="main-area">

  <div class="topbar fade-up">
    <div>
      <div class="greeting-eyebrow">SK Officer Portal</div>
      <div class="greeting-name">Library <span style="color:var(--indigo);">Management</span></div>
      <div class="greeting-sub"><?= date('l, F j, Y') ?></div>
    </div>
    <div class="topbar-right">
      <?php if ($pendingBorrows > 0): ?>
        <div class="pending-pill"><i class="fa-solid fa-clock" style="font-size:.75rem;"></i><?= $pendingBorrows ?> pending borrow<?= $pendingBorrows > 1 ? 's' : '' ?></div>
      <?php endif; ?>
      <div class="icon-btn" onclick="layoutToggleDark()" title="Toggle dark mode">
        <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
      </div>
      <button onclick="openModal('pdfModal')" class="action-btn-outline" style="font-size:.82rem;">
        <i class="fa-solid fa-wand-magic-sparkles" style="font-size:.78rem;color:var(--indigo);"></i> Upload PDF
      </button>
      <button onclick="openModal('addModal')" class="action-btn">
        <i class="fa-solid fa-plus" style="font-size:.78rem;"></i> Add Book
      </button>
    </div>
  </div>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="flash-ok fade-up"><i class="fa-solid fa-circle-check"></i><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="flash-err fade-up"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <p class="section-label fade-up-1">Library Overview</p>
  <div class="stats-grid fade-up-1">
    <div class="stat-card" onclick="switchTab('books')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#eef2ff;"><i class="fa-solid fa-book" style="color:var(--indigo);font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:var(--indigo);"><?= $activeBooks ?> active</span>
      </div>
      <div class="stat-lbl">Total Books</div>
      <div class="stat-num"><?= $totalBooks ?></div>
      <div class="stat-hint">in collection</div>
    </div>
    <div class="stat-card" onclick="switchTab('books')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#ede9fe;"><i class="fa-solid fa-wand-magic-sparkles" style="color:#7c3aed;font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:#7c3aed;"><?= $totalBooks > 0 ? round($ragReady / $totalBooks * 100) : 0 ?>%</span>
      </div>
      <div class="stat-lbl">AI Ready</div>
      <div class="stat-num" style="color:#7c3aed;"><?= $ragReady ?></div>
      <div class="prog-bar" style="margin-top:10px;">
        <div class="prog-fill" style="width:<?= $totalBooks > 0 ? round($ragReady / $totalBooks * 100) : 0 ?>%;background:#7c3aed;"></div>
      </div>
    </div>
    <div class="stat-card" onclick="switchTab('borrowings')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#f3e8ff;"><i class="fa-solid fa-clock-rotate-left" style="color:#9333ea;font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:#9333ea;">all time</span>
      </div>
      <div class="stat-lbl">Total Borrows</div>
      <div class="stat-num" style="color:#9333ea;"><?= $totalBorrows ?></div>
      <div class="stat-hint">borrowing requests</div>
    </div>
    <div class="stat-card" onclick="switchTab('borrowings');filterBorrowings('pending')">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
        <div class="card-icon" style="background:#fef3c7;"><i class="fa-regular fa-clock" style="color:#d97706;font-size:.9rem;"></i></div>
        <span style="font-size:.6rem;font-weight:800;color:#d97706;">need action</span>
      </div>
      <div class="stat-lbl">Pending</div>
      <div class="stat-num" style="<?= $pendingBorrows > 0 ? 'color:#d97706' : '' ?>"><?= $pendingBorrows ?></div>
      <div class="stat-hint">awaiting approval</div>
    </div>
  </div>

  <div class="tab-bar fade-up-2">
    <button id="tabBooks" class="tab-btn active" onclick="switchTab('books')">
      <i class="fa-solid fa-book" style="font-size:.8rem;"></i> Books Catalog
    </button>
    <button id="tabBorrowings" class="tab-btn" onclick="switchTab('borrowings')">
      <i class="fa-solid fa-clock-rotate-left" style="font-size:.8rem;"></i> Borrowings
      <?php if ($pendingBorrows > 0): ?><span class="tab-badge"><?= $pendingBorrows ?></span><?php endif; ?>
    </button>
  </div>

  <!-- ══════════════════════════════════
       BOOKS PANE
  ══════════════════════════════════ -->
  <div id="paneBooks" class="fade-up-2">
    <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:16px;">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input id="bookSearch" type="text" class="search-input" placeholder="Search title, author, genre, call number…" oninput="applyFilter()">
      </div>
    </div>

    <?php if (empty($books)): ?>
      <div class="empty-state">
        <div class="card-icon" style="background:#eef2ff;width:48px;height:48px;border-radius:var(--r-md);margin:0 auto 12px;"><i class="fa-solid fa-book-open" style="color:var(--indigo);font-size:1.1rem;"></i></div>
        <div class="card-title" style="margin-bottom:6px;">No books yet</div>
        <div class="card-sub" style="margin-bottom:20px;">Add the first book manually or upload a PDF.</div>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
          <button onclick="openModal('pdfModal')" class="action-btn-outline" style="font-size:.82rem;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.78rem;"></i> Upload PDF</button>
          <button onclick="openModal('addModal')" class="action-btn" style="font-size:.82rem;"><i class="fa-solid fa-plus" style="font-size:.78rem;"></i> Add Manually</button>
        </div>
      </div>
    <?php else: ?>

      <!-- FIX: Desktop table — no Tailwind hidden/block classes, controlled by CSS media query -->
      <div class="tbl-wrap" id="desktopTable">
        <div style="overflow-x:auto;">
          <table id="booksTable">
            <thead>
              <tr>
                <th style="width:32px;">#</th>
                <th style="min-width:150px;">Title / Author</th>
                <th style="width:90px;">Call #</th>
                <th style="width:90px;">ISBN</th>
                <th style="width:90px;">Genre</th>
                <th style="width:52px;">Year</th>
                <th style="width:110px;">Copies</th>
                <th style="width:60px;">RAG</th>
                <th style="width:72px;">Status</th>
                <th style="width:100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($books as $i => $b): ?>
                <tr data-search="<?= strtolower(htmlspecialchars(($b['title'] ?? '') . ' ' . ($b['author'] ?? '') . ' ' . ($b['genre'] ?? '') . ' ' . ($b['call_number'] ?? ''), ENT_QUOTES)) ?>">
                  <td style="color:#94a3b8;font-weight:700;font-size:.72rem;font-family:var(--mono);"><?= $i + 1 ?></td>
                  <td>
                    <p style="font-weight:700;font-size:.85rem;max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($b['title'] ?? '') ?></p>
                    <p style="font-size:.7rem;color:#94a3b8;margin-top:2px;"><?= htmlspecialchars($b['author'] ?? '') ?></p>
                  </td>
                  <td>
                    <?php if (!empty($b['call_number'])): ?>
                      <span class="call-badge"><?= htmlspecialchars($b['call_number']) ?></span>
                    <?php else: ?><span style="color:#94a3b8;font-size:.72rem;">—</span><?php endif; ?>
                  </td>
                  <td style="font-size:.72rem;color:#64748b;font-family:var(--mono);"><?= htmlspecialchars(!empty($b['isbn']) ? $b['isbn'] : '—') ?></td>
                  <td style="font-size:.82rem;font-weight:500;"><?= htmlspecialchars($b['genre'] ?? '—') ?></td>
                  <td style="font-size:.82rem;font-weight:500;font-family:var(--mono);"><?= htmlspecialchars($b['published_year'] ?? '—') ?></td>
                  <td>
                    <div class="copies-ctl">
                      <button class="cpy-btn" onclick="adjustCopies(<?= $b['id'] ?>, -1, this)">−</button>
                      <span class="cpy-val" id="copiesVal-<?= $b['id'] ?>"><?= (int)($b['available_copies'] ?? 0) ?></span>
                      <button class="cpy-btn" onclick="adjustCopies(<?= $b['id'] ?>, 1, this)">+</button>
                      <span class="cpy-total">/ <?= (int)($b['total_copies'] ?? 1) ?></span>
                    </div>
                  </td>
                  <td>
                    <?php if (!empty($b['preface'])): ?>
                      <span class="tag tag-rag-yes"><i class="fa-solid fa-check" style="font-size:.55rem;"></i> Yes</span>
                    <?php else: ?>
                      <span class="tag tag-rag-no">—</span>
                    <?php endif; ?>
                  </td>
                  <td><span class="tag tag-<?= ($b['status'] ?? '') === 'active' ? 'active' : 'inactive' ?>"><?= ucfirst($b['status'] ?? 'inactive') ?></span></td>
                  <td>
                    <div class="act-pair">
                      <button onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>)" class="act-btn act-edit"><i class="fa-solid fa-pen" style="font-size:.65rem;"></i> Edit</button>
                      <button onclick="confirmDelete(<?= $b['id'] ?>, <?= htmlspecialchars(json_encode($b['title']), ENT_QUOTES) ?>)" class="act-btn act-del"><i class="fa-solid fa-trash" style="font-size:.65rem;"></i> Delete</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- FIX: Mobile cards — no Tailwind classes, controlled by CSS media query -->
      <div id="bookCardList">
        <?php foreach ($books as $b):
          $avail  = (int)($b['available_copies'] ?? 0);
          $total  = (int)($b['total_copies'] ?? 1);
          $hasRag = !empty($b['preface']);
        ?>
          <div class="book-card" data-search="<?= strtolower(htmlspecialchars(($b['title'] ?? '') . ' ' . ($b['author'] ?? '') . ' ' . ($b['genre'] ?? '') . ' ' . ($b['call_number'] ?? ''), ENT_QUOTES)) ?>">
            <div style="display:flex;align-items:flex-start;gap:12px;padding-bottom:12px;border-bottom:1px solid rgba(99,102,241,.05);margin-bottom:12px;">
              <div class="book-letter"><?= mb_strtoupper(mb_substr($b['title'] ?? 'B', 0, 1)) ?></div>
              <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                  <p style="font-weight:800;font-size:.88rem;line-height:1.3;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;"><?= htmlspecialchars($b['title'] ?? '') ?></p>
                  <span class="tag tag-<?= ($b['status'] ?? '') === 'active' ? 'active' : 'inactive' ?>" style="flex-shrink:0;margin-top:2px;"><?= ucfirst($b['status'] ?? '') ?></span>
                </div>
                <p style="font-size:.72rem;color:#94a3b8;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($b['author'] ?? '') ?></p>
                <div style="display:flex;gap:5px;flex-wrap:wrap;margin-top:7px;">
                  <?php if (!empty($b['call_number'])): ?><span class="call-badge" style="font-size:.6rem;"><?= htmlspecialchars($b['call_number']) ?></span><?php endif; ?>
                  <?php if (!empty($b['genre'])): ?><span class="book-meta-chip"><i class="fa-solid fa-tag" style="font-size:.6rem;"></i><?= htmlspecialchars($b['genre']) ?></span><?php endif; ?>
                  <?php if (!empty($b['published_year'])): ?><span class="book-meta-chip"><i class="fa-regular fa-calendar" style="font-size:.6rem;"></i><?= htmlspecialchars($b['published_year']) ?></span><?php endif; ?>
                </div>
              </div>
            </div>
            <div class="book-copies-row">
              <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:.72rem;color:#94a3b8;font-weight:600;">Available:</span>
                <div class="copies-ctl">
                  <button class="cpy-btn" onclick="adjustCopies(<?= $b['id'] ?>, -1, this)">−</button>
                  <span class="cpy-val" id="copiesVal-<?= $b['id'] ?>-m"><?= $avail ?></span>
                  <button class="cpy-btn" onclick="adjustCopies(<?= $b['id'] ?>, 1, this)">+</button>
                </div>
                <span class="cpy-total">of <?= $total ?></span>
              </div>
              <?php if ($hasRag): ?>
                <span class="tag tag-rag-yes" style="font-size:.62rem;"><i class="fa-solid fa-wand-magic-sparkles" style="font-size:.6rem;"></i> AI Ready</span>
              <?php else: ?>
                <span class="tag tag-rag-no" style="font-size:.62rem;"><i class="fa-solid fa-circle-info" style="font-size:.6rem;"></i> No preface</span>
              <?php endif; ?>
            </div>
            <div class="book-card-actions" onclick="event.stopPropagation()">
              <button onclick="openEditModal(<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>)" class="act-btn act-edit" style="justify-content:center;padding:10px;"><i class="fa-solid fa-pen" style="font-size:.75rem;"></i> Edit</button>
              <button onclick="confirmDelete(<?= $b['id'] ?>, <?= htmlspecialchars(json_encode($b['title']), ENT_QUOTES) ?>)" class="act-btn act-del" style="justify-content:center;padding:10px;"><i class="fa-solid fa-trash" style="font-size:.75rem;"></i> Delete</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div id="noResultsMsg" style="display:none;margin-top:16px;">
        <div class="empty-state">
          <i class="fa-solid fa-magnifying-glass" style="font-size:1.5rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
          <p style="font-size:.85rem;font-weight:600;color:#94a3b8;">No books match your search.</p>
        </div>
      </div>

      <div id="paginationControls" style="display:none;margin-top:16px;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <p id="pageInfo" style="font-size:.72rem;font-weight:700;color:#94a3b8;"></p>
        <div id="pageButtons" style="display:flex;align-items:center;gap:6px;"></div>
      </div>

    <?php endif; ?>
  </div>

  <!-- ══════════════════════════════════
       BORROWINGS PANE
  ══════════════════════════════════ -->
  <div id="paneBorrowings" style="display:none;" class="fade-up-2">

    <?php if (empty($borrowings)): ?>
      <div class="empty-state">
        <div class="card-icon" style="background:#eef2ff;width:48px;height:48px;border-radius:var(--r-md);margin:0 auto 12px;"><i class="fa-solid fa-clock-rotate-left" style="color:var(--indigo);font-size:1.1rem;"></i></div>
        <div class="card-title" style="margin-bottom:6px;">No borrowing requests yet</div>
        <div class="card-sub" style="margin-bottom:16px;">Requests from users will appear here.</div>
        <button onclick="switchTab('books')" class="action-btn" style="font-size:.82rem;margin:0 auto;"><i class="fa-solid fa-book" style="font-size:.78rem;"></i> View Catalog</button>
      </div>
    <?php else: ?>

      <div class="filter-row">
        <div class="search-wrap" style="max-width:280px;">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input id="borrowSearch" type="text" class="search-input" placeholder="Search resident or book…" oninput="applyBorrowFilter()">
        </div>
        <button class="fpill active" id="bpill-all"      onclick="filterBorrowings('all')">All</button>
        <button class="fpill fp-pending"  id="bpill-pending"  onclick="filterBorrowings('pending')">
          Pending <?php if ($pendingBorrows > 0): ?><span style="background:rgba(245,158,11,.25);color:#92400e;font-size:.6rem;font-weight:800;padding:1px 6px;border-radius:999px;margin-left:3px;"><?= $pendingBorrows ?></span><?php endif; ?>
        </button>
        <button class="fpill fp-approved" id="bpill-approved" onclick="filterBorrowings('approved')">Approved</button>
        <button class="fpill fp-returned" id="bpill-returned" onclick="filterBorrowings('returned')">Returned</button>
        <button class="fpill fp-rejected" id="bpill-rejected" onclick="filterBorrowings('rejected')">Rejected</button>
      </div>

      <!-- FIX: Desktop borrow table — CSS media query controlled -->
      <div class="tbl-wrap" id="desktopBorrowTable">
        <div style="overflow-x:auto;">
          <table id="borrowingsTable">
            <thead>
              <tr>
                <th style="width:32px;">#</th>
                <th style="min-width:140px;">Resident</th>
                <th style="min-width:140px;">Book</th>
                <th style="width:110px;">Borrowed</th>
                <th style="width:100px;">Due Date</th>
                <th style="width:80px;">Status</th>
                <th style="width:120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($borrowings as $i => $bw): $s = strtolower($bw['status'] ?? 'pending'); ?>
                <tr data-status="<?= $s ?>" data-search="<?= strtolower(htmlspecialchars(($bw['resident_name'] ?? '') . ' ' . ($bw['book_title'] ?? ''), ENT_QUOTES)) ?>">
                  <td style="color:#94a3b8;font-weight:700;font-size:.72rem;font-family:var(--mono);"><?= $i + 1 ?></td>
                  <td>
                    <p style="font-weight:700;font-size:.85rem;"><?= htmlspecialchars($bw['resident_name'] ?? 'Unknown') ?></p>
                    <p style="font-size:.7rem;color:#94a3b8;margin-top:2px;"><?= htmlspecialchars($bw['email'] ?? '') ?></p>
                  </td>
                  <td>
                    <p style="font-weight:700;font-size:.85rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px;"><?= htmlspecialchars($bw['book_title'] ?? '') ?></p>
                    <p style="font-size:.7rem;color:#94a3b8;margin-top:2px;"><?= htmlspecialchars($bw['book_author'] ?? '') ?></p>
                  </td>
                  <td style="font-size:.78rem;font-family:var(--mono);"><?= htmlspecialchars($bw['borrowed_at'] ?? '—') ?></td>
                  <td style="font-size:.78rem;font-family:var(--mono);"><?= htmlspecialchars($bw['due_date'] ?? '—') ?></td>
                  <td><span class="tag tag-<?= $s ?>"><?= ucfirst($s) ?></span></td>
                  <td>
                    <div class="act-pair">
                      <?php if ($s === 'pending'): ?>
                        <form method="post" action="/sk/borrowings/approve/<?= $bw['id'] ?>"><?= csrf_field() ?><button class="act-btn act-approve" style="width:100%;"><i class="fa-solid fa-check" style="font-size:.65rem;"></i> Approve</button></form>
                        <form method="post" action="/sk/borrowings/reject/<?= $bw['id'] ?>"><?= csrf_field() ?><button class="act-btn act-reject" style="width:100%;"><i class="fa-solid fa-xmark" style="font-size:.65rem;"></i> Reject</button></form>
                      <?php elseif ($s === 'approved'): ?>
                        <form method="post" action="/sk/borrowings/return/<?= $bw['id'] ?>"><?= csrf_field() ?><button class="act-btn act-return" style="width:100%;"><i class="fa-solid fa-rotate-left" style="font-size:.65rem;"></i> Returned</button></form>
                      <?php else: ?>
                        <span style="font-size:.72rem;color:#94a3b8;font-weight:600;"><?= ucfirst($s) ?></span>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- FIX: Mobile borrow cards — CSS media query controlled -->
      <div id="borrowCardList">
        <?php foreach ($borrowings as $bw): $s = strtolower($bw['status'] ?? 'pending'); ?>
          <div class="borrow-card" data-status="<?= $s ?>" data-search="<?= strtolower(htmlspecialchars(($bw['resident_name'] ?? '') . ' ' . ($bw['book_title'] ?? ''), ENT_QUOTES)) ?>">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;padding-bottom:12px;border-bottom:1px solid rgba(99,102,241,.05);margin-bottom:10px;">
              <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                <div class="card-icon" style="background:#f1f5f9;flex-shrink:0;"><i class="fa-solid fa-user" style="color:#64748b;font-size:.85rem;"></i></div>
                <div style="min-width:0;">
                  <p style="font-weight:700;font-size:.88rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($bw['resident_name'] ?? 'Unknown') ?></p>
                  <p style="font-size:.7rem;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($bw['email'] ?? '') ?></p>
                </div>
              </div>
              <span class="tag tag-<?= $s ?>" style="flex-shrink:0;"><?= ucfirst($s) ?></span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
              <div class="card-icon" style="background:var(--indigo-light);flex-shrink:0;"><i class="fa-solid fa-book" style="color:var(--indigo);font-size:.8rem;"></i></div>
              <div style="min-width:0;">
                <p style="font-weight:700;font-size:.82rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($bw['book_title'] ?? '') ?></p>
                <p style="font-size:.7rem;color:#94a3b8;"><?= htmlspecialchars($bw['book_author'] ?? '') ?></p>
              </div>
            </div>
            <div style="display:flex;gap:16px;font-size:.7rem;font-weight:600;color:#94a3b8;font-family:var(--mono);margin-bottom:<?= in_array($s, ['pending', 'approved']) ? '12px' : '0' ?>;">
              <span style="display:flex;align-items:center;gap:5px;"><i class="fa-regular fa-calendar" style="font-size:.65rem;"></i><?= htmlspecialchars($bw['borrowed_at'] ?? '—') ?></span>
              <span style="display:flex;align-items:center;gap:5px;<?= $s === 'approved' ? 'color:#ef4444;font-weight:700;' : '' ?>"><i class="fa-regular fa-calendar-xmark" style="font-size:.65rem;"></i>Due: <?= htmlspecialchars($bw['due_date'] ?? '—') ?></span>
            </div>
            <?php if ($s === 'pending'): ?>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;padding-top:10px;border-top:1px solid rgba(99,102,241,.05);">
                <form method="post" action="/sk/borrowings/approve/<?= $bw['id'] ?>"><?= csrf_field() ?><button class="act-btn act-approve" style="width:100%;justify-content:center;padding:10px;"><i class="fa-solid fa-check" style="font-size:.7rem;"></i> Approve</button></form>
                <form method="post" action="/sk/borrowings/reject/<?= $bw['id'] ?>"><?= csrf_field() ?><button class="act-btn act-reject" style="width:100%;justify-content:center;padding:10px;"><i class="fa-solid fa-xmark" style="font-size:.7rem;"></i> Reject</button></form>
              </div>
            <?php elseif ($s === 'approved'): ?>
              <div style="padding-top:10px;border-top:1px solid rgba(99,102,241,.05);">
                <form method="post" action="/sk/borrowings/return/<?= $bw['id'] ?>"><?= csrf_field() ?><button class="act-btn act-return" style="width:100%;justify-content:center;padding:10px;"><i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i> Mark as Returned</button></form>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div id="noBorrowResultsMsg" style="display:none;margin-top:16px;">
        <div class="empty-state">
          <p style="font-size:.85rem;font-weight:600;color:#94a3b8;">No borrowings match your filter.</p>
        </div>
      </div>

    <?php endif; ?>
  </div>

</main>

<script>
  if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
  }

  /* ── Tab switching ── */
  function switchTab(t) {
    document.getElementById('paneBooks').style.display       = t === 'books'      ? '' : 'none';
    document.getElementById('paneBorrowings').style.display  = t === 'borrowings' ? '' : 'none';
    document.getElementById('tabBooks').className            = 'tab-btn' + (t === 'books'      ? ' active' : '');
    document.getElementById('tabBorrowings').className       = 'tab-btn' + (t === 'borrowings' ? ' active' : '');
  }
  if (window.location.hash === '#borrowings') switchTab('borrowings');

  /* ── Modals ── */
  function openModal(id)  { document.getElementById(id).classList.add('show');    document.body.style.overflow = 'hidden'; }
  function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') ['addModal','editModal','pdfModal','deleteModal'].forEach(closeModal);
  });

  function confirmDelete(id, title) {
    document.getElementById('deleteBookTitle').textContent = title;
    document.getElementById('deleteForm').action = '/sk/books/delete/' + id;
    openModal('deleteModal');
  }

  function openEditModal(b) {
    document.getElementById('editTitle').value      = b.title          || '';
    document.getElementById('editAuthor').value     = b.author         || '';
    document.getElementById('editGenre').value      = b.genre          || '';
    document.getElementById('editYear').value       = b.published_year || '';
    document.getElementById('editCopies').value     = b.total_copies   || 1;
    document.getElementById('editStatus').value     = b.status         || 'active';
    document.getElementById('editPreface').value    = b.preface        || '';
    document.getElementById('editCallNumber').value = b.call_number    || '';
    document.getElementById('editForm').action      = '/sk/books/update/' + b.id;
    openModal('editModal');
  }

  /* ── Books search & filter ── */
  function applyFilter() {
    const q = (document.getElementById('bookSearch')?.value || '').toLowerCase().trim();
    let visible = 0;
    document.querySelectorAll('#booksTable tbody tr').forEach(r => {
      const m = !q || r.dataset.search.includes(q);
      r.style.display = m ? '' : 'none';
      if (m) visible++;
    });
    document.querySelectorAll('#bookCardList .book-card').forEach(c => {
      c.style.display = (!q || c.dataset.search.includes(q)) ? '' : 'none';
    });
    const noMsg = document.getElementById('noResultsMsg');
    if (noMsg) noMsg.style.display = (q && visible === 0) ? 'block' : 'none';
    initPagination(q);
  }

  /* ── Pagination ── */
  let _currentPage = 1;
  const PAGE_SIZE  = 20;

  function initPagination(f) {
    const rows       = Array.from(document.querySelectorAll('#booksTable tbody tr')).filter(r => r.style.display !== 'none');
    const total      = rows.length;
    const totalPages = Math.ceil(total / PAGE_SIZE);
    const ctrl       = document.getElementById('paginationControls');
    if (!ctrl) return;
    if (totalPages <= 1) { ctrl.style.display = 'none'; showPage(1, rows); return; }
    ctrl.style.display = 'flex';
    _currentPage = Math.min(_currentPage, totalPages);
    showPage(_currentPage, rows);
    const btns = document.getElementById('pageButtons');
    btns.innerHTML = '';
    const prev = document.createElement('button');
    prev.className = 'page-btn'; prev.textContent = '‹'; prev.disabled = _currentPage === 1;
    prev.onclick = () => goToPage(_currentPage - 1, f);
    btns.appendChild(prev);
    paginationRange(_currentPage, totalPages).forEach(p => {
      const btn = document.createElement('button');
      btn.className = 'page-btn' + (p === _currentPage ? ' active' : '');
      btn.textContent = p;
      btn.style.pointerEvents = p === '…' ? 'none' : '';
      if (p !== '…') btn.onclick = () => goToPage(p, f);
      btns.appendChild(btn);
    });
    const next = document.createElement('button');
    next.className = 'page-btn'; next.textContent = '›'; next.disabled = _currentPage === totalPages;
    next.onclick = () => goToPage(_currentPage + 1, f);
    btns.appendChild(next);
    const start = (_currentPage - 1) * PAGE_SIZE + 1;
    document.getElementById('pageInfo').textContent = `Showing ${start}–${Math.min(_currentPage * PAGE_SIZE, total)} of ${total}`;
  }

  function showPage(p, rows) {
    rows.forEach((r, i) => { r.style.display = (i >= (p-1)*PAGE_SIZE && i < p*PAGE_SIZE) ? '' : 'none'; });
  }

  function goToPage(p, f) { _currentPage = p; initPagination(f); }

  function paginationRange(c, t) {
    if (t <= 7) return Array.from({length: t}, (_, i) => i + 1);
    const r = [1];
    if (c > 3) r.push('…');
    for (let i = Math.max(2, c-1); i <= Math.min(t-1, c+1); i++) r.push(i);
    if (c < t-2) r.push('…');
    r.push(t);
    return r;
  }

  document.addEventListener('DOMContentLoaded', () => initPagination(''));

  /* ── Adjust copies ── */
  function adjustCopies(bookId, delta, btn) {
    const sels = [`#copiesVal-${bookId}`, `#copiesVal-${bookId}-m`];
    const els  = sels.map(s => document.querySelector(s)).filter(Boolean);
    if (!els.length) return;
    const cur = parseInt(els[0].textContent) || 0;
    const nv  = Math.max(0, cur + delta);
    els.forEach(el => el.textContent = nv);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    btn.disabled = true;
    fetch('/sk/books/update-copies/' + bookId, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ available_copies: nv })
    }).then(res => {
      const t = res.headers.get('X-CSRF-TOKEN');
      if (t) document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', t);
      btn.disabled = false;
      if (!res.ok) els.forEach(el => el.textContent = cur);
    }).catch(() => { els.forEach(el => el.textContent = cur); btn.disabled = false; });
  }

  /* ── Borrowings filter ── */
  let _borrowStatus = 'all';

  function filterBorrowings(status) {
    _borrowStatus = status;
    ['all','pending','approved','returned','rejected'].forEach(s => {
      const p = document.getElementById('bpill-' + s);
      if (!p) return;
      p.classList.toggle('active', s === status);
    });
    applyBorrowFilter();
  }

  function applyBorrowFilter() {
    const q = (document.getElementById('borrowSearch')?.value || '').toLowerCase().trim();
    let visible = 0;
    const matches = el => (_borrowStatus === 'all' || el.dataset.status === _borrowStatus) && (!q || el.dataset.search.includes(q));
    document.querySelectorAll('#borrowingsTable tbody tr').forEach(r => {
      const s = matches(r); r.style.display = s ? '' : 'none'; if (s) visible++;
    });
    document.querySelectorAll('#borrowCardList .borrow-card').forEach(c => {
      c.style.display = matches(c) ? '' : 'none';
    });
    const noMsg = document.getElementById('noBorrowResultsMsg');
    if (noMsg) noMsg.style.display = ((_borrowStatus !== 'all' || q) && visible === 0) ? 'block' : 'none';
  }

  /* ── PDF / AI extraction ── */
  let _aPdfText = null, _aPI = null;

  if (typeof pdfjsLib !== 'undefined') {
    const dz = document.getElementById('aDropZone');
    if (dz) {
      dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('dragover'); });
      dz.addEventListener('dragleave', () => dz.classList.remove('dragover'));
      dz.addEventListener('drop', e => {
        e.preventDefault(); dz.classList.remove('dragover');
        const f = e.dataTransfer.files[0]; if (f) aProcessPdfFile(f);
      });
    }
  }

  function aHandlePdfFile(e) { const f = e.target.files[0]; if (f) aProcessPdfFile(f); }

  function aProcessPdfFile(file) {
    if (file.type !== 'application/pdf') { alert('Please upload a PDF file.'); return; }
    if (file.size > 10 * 1024 * 1024)   { alert('File too large (max 10MB).'); return; }
    document.getElementById('aFilePreviewName').textContent = file.name;
    document.getElementById('aFilePreviewSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    document.getElementById('aFilePreview').classList.remove('hidden');
    document.getElementById('aDropZone').style.borderColor = 'var(--indigo)';
    const reader = new FileReader();
    reader.onload = async ev => {
      try {
        const arr  = new Uint8Array(ev.target.result);
        const pdf  = await pdfjsLib.getDocument({ data: arr }).promise;
        const pages = [];
        for (let p = 1; p <= Math.min(pdf.numPages, 8); p++) {
          const page = await pdf.getPage(p);
          const c    = await page.getTextContent();
          pages.push(c.items.map(s => s.str).join(' '));
        }
        _aPdfText = pages.join('\n\n');
        document.getElementById('aExtractBtn').disabled = !_aPdfText || _aPdfText.trim().length < 20;
        if (!_aPdfText || _aPdfText.trim().length < 20) {
          alert('Could not extract readable text. This PDF may be a scanned image.');
          _aPdfText = null;
        }
      } catch (err) { alert('Error reading PDF: ' + err.message); _aPdfText = null; }
    };
    reader.readAsArrayBuffer(file);
  }

  function aClearPdfFile() {
    _aPdfText = null;
    document.getElementById('aPdfFileInput').value = '';
    document.getElementById('aFilePreview').classList.add('hidden');
    document.getElementById('aExtractBtn').disabled = true;
    document.getElementById('aDropZone').style.borderColor = '';
  }

  function aSetStep(n) {
    [1,2,3].forEach(i => {
      document.getElementById('aStepDot' + i).className = 'step-dot ' + (i < n ? 'done' : i === n ? 'active' : 'pending');
    });
    [1,2].forEach(i => {
      document.getElementById('aStepLine' + i).className = 'step-line ' + (i < n ? 'done' : 'pending');
    });
    document.getElementById('aStepLabel').textContent = {1:'Upload PDF',2:'AI Extracting…',3:'Review & Save'}[n] || '';
  }

  function aShowPanel(id) {
    ['aPdfStep1','aPdfStep2','aPdfStep3','aPdfStepError'].forEach(p => {
      document.getElementById(p).style.display = p === id ? '' : 'none';
    });
  }

  function aAnimProgress(tp, dur) {
    const fill = document.getElementById('aAiProgressFill');
    const s = parseFloat(fill.style.width) || 0, t0 = Date.now();
    clearInterval(_aPI);
    _aPI = setInterval(() => {
      const pct = Math.min(s + (tp - s) * ((Date.now() - t0) / dur), tp);
      fill.style.width = pct + '%';
      if (pct >= tp) clearInterval(_aPI);
    }, 30);
  }

  function aShowError(msg, dbg) {
    clearInterval(_aPI);
    document.getElementById('aPdfErrorText').textContent = msg;
    const d = document.getElementById('skDebugPanel');
    if (dbg) { d.textContent = dbg; d.style.display = 'block'; } else { d.style.display = 'none'; }
    aShowPanel('aPdfStepError');
    aSetStep(1);
  }

  async function aExtractFromPdf() {
    if (!_aPdfText) return;
    aSetStep(2); aShowPanel('aPdfStep2'); aAnimProgress(40, 2000);
    const msgs = ['Sending text to AI…','Extracting title and author…','Identifying genre and year…','Generating description…','Finalizing…'];
    let mi = 0;
    const me   = document.getElementById('aAiStatusText');
    const mint = setInterval(() => { mi = (mi + 1) % msgs.length; me.textContent = msgs[mi]; }, 2200);
    try {
      aAnimProgress(75, 8000);
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const response  = await fetch('/sk/books/extract-pdf', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ pdf_text: _aPdfText })
      });
      const newToken = response.headers.get('X-CSRF-TOKEN');
      if (newToken) document.querySelector('meta[name="csrf-token"]')?.setAttribute('content', newToken);
      clearInterval(mint);
      aAnimProgress(95, 500);
      const ct = response.headers.get('Content-Type') || '';
      if (!ct.includes('application/json')) {
        const rb      = await response.text();
        const snippet = rb.replace(/<[^>]*>/g,' ').replace(/\s+/g,' ').trim().slice(0,300);
        const errMap  = {419:'CSRF expired — refresh and try again.',403:'Access forbidden.',401:'Session expired.',404:'Route not found.',500:'Server error.'};
        aShowError(errMap[response.status] || 'HTTP ' + response.status, snippet);
        return;
      }
      const rb = await response.text();
      let result;
      try { result = JSON.parse(rb); } catch(e) { aShowError('Server returned invalid JSON.', rb.slice(0,300)); return; }
      if (!result.ok) { aShowError(result.error || 'Extraction failed.', null); return; }
      aAnimProgress(100, 200);
      setTimeout(() => { aPopulate(result.data); aSetStep(3); aShowPanel('aPdfStep3'); }, 400);
    } catch (err) { clearInterval(mint); aShowError('Network error: ' + err.message, null); }
  }

  function aPopulate(data) {
    const fields = {
      aPdfTitle:      { key: 'title',          badge: 'aBadgeTitle'      },
      aPdfAuthor:     { key: 'author',         badge: 'aBadgeAuthor'     },
      aPdfGenre:      { key: 'genre',          badge: 'aBadgeGenre'      },
      aPdfYear:       { key: 'published_year', badge: 'aBadgeYear'       },
      aPdfIsbn:       { key: 'isbn',           badge: 'aBadgeIsbn'       },
      aPdfCallNumber: { key: 'call_number',    badge: 'aBadgeCallNumber' },
      aPdfPreface:    { key: 'preface',        badge: 'aBadgePreface'    },
    };
    let anyMissing = false;
    Object.entries(fields).forEach(([elId, cfg]) => {
      const el    = document.getElementById(elId);
      const badge = document.getElementById(cfg.badge);
      const val   = (data[cfg.key] || '').trim();
      el.value = val;
      if (val) {
        el.classList.add('filled');
        if (badge) badge.style.display = 'inline-flex';
      } else {
        el.classList.remove('filled');
        if (badge) badge.style.display = 'none';
        if (['title','author'].includes(cfg.key)) anyMissing = true;
      }
    });
    const note = document.getElementById('aAiConfidenceNote');
    if (note) note.classList.toggle('hidden', !anyMissing);
  }

  function aResetPdfModal() {
    _aPdfText = null; clearInterval(_aPI);
    document.getElementById('aPdfFileInput').value = '';
    document.getElementById('aFilePreview').classList.add('hidden');
    document.getElementById('aExtractBtn').disabled = true;
    document.getElementById('aDropZone').style.borderColor = '';
    document.getElementById('aAiProgressFill').style.width = '0%';
    document.getElementById('aAiStatusText').textContent   = 'Extracting text…';
    document.getElementById('skDebugPanel').style.display  = 'none';
    ['aPdfTitle','aPdfAuthor','aPdfGenre','aPdfYear','aPdfIsbn','aPdfCallNumber','aPdfPreface'].forEach(id => {
      const el = document.getElementById(id); if (el) { el.value = ''; el.classList.remove('filled'); }
    });
    ['aBadgeTitle','aBadgeAuthor','aBadgeGenre','aBadgeYear','aBadgeIsbn','aBadgeCallNumber','aBadgePreface'].forEach(id => {
      const el = document.getElementById(id); if (el) el.style.display = 'none';
    });
    const note = document.getElementById('aAiConfidenceNote');
    if (note) note.classList.add('hidden');
    aSetStep(1); aShowPanel('aPdfStep1');
  }
</script>

<?php include(APPPATH . 'Views/partials/onboarding_help.php'); ?>
</body>
</html>