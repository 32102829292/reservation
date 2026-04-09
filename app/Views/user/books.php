<?php /* Views/user/books.php */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>Library — Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <style>
        :root {
            --font: 'Plus Jakarta Sans', system-ui, sans-serif;
            --mono: 'JetBrains Mono', 'Courier New', monospace;
        }

        body {
            font-family: var(--font);
            display: flex;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }

        .ai-card {
            background: var(--card);
            border-radius: var(--r-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 20px 22px;
            margin-bottom: 16px;
            transition: background var(--ease), border-color var(--ease);
        }

        .ai-card-head {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 12px;
        }

        .ai-icon {
            width: 30px;
            height: 30px;
            background: var(--indigo-light);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ai-label {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--text-sub);
        }

        .ai-input-row {
            display: flex;
            gap: 8px;
        }

        .ai-input {
            flex: 1;
            min-width: 0;
            padding: 10px 14px;
            background: var(--input-bg);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--r-sm);
            font-size: .88rem;
            font-family: var(--font);
            color: var(--text);
            outline: none;
            transition: all var(--ease);
        }

        .ai-input:focus {
            border-color: #818cf8;
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .find-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 16px;
            background: var(--indigo);
            color: white;
            border-radius: var(--r-sm);
            font-size: .82rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .find-btn:hover {
            background: #312e81;
        }

        .find-btn:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .ai-result-box {
            display: none;
            margin-top: .75rem;
            background: var(--indigo-light);
            border: 1px solid var(--indigo-border);
            border-radius: var(--r-sm);
            padding: 14px 16px;
        }

        .ai-result-box.show {
            display: block;
            animation: l-slide-up .3s ease;
        }

        .controls-row {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 150px;
        }

        .search-icon-pos {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--text-sub);
        }

        .search-input {
            width: 100%;
            padding: 10px 12px 10px 34px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-family: var(--font);
            color: var(--text);
            outline: none;
            box-shadow: var(--shadow-sm);
            transition: all var(--ease);
        }

        .search-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .genre-select {
            padding: 10px 12px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            font-size: .85rem;
            font-family: var(--font);
            color: var(--text-sub);
            outline: none;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
        }

        .tab-group {
            display: flex;
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            overflow: hidden;
            background: var(--card);
            box-shadow: var(--shadow-sm);
        }

        .tab-btn {
            padding: 10px 16px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--text-sub);
            background: transparent;
            border: none;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .tab-btn.active {
            background: var(--indigo);
            color: white;
            box-shadow: 0 2px 8px rgba(55, 48, 163, .2);
        }

        .tab-btn:hover:not(.active) {
            background: var(--indigo-light);
            color: var(--indigo);
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
        }

        @media(max-width:639px) {
            .books-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        .book-card {
            background: var(--card);
            border-radius: var(--r-md);
            border: 1px solid var(--border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: all var(--ease);
        }

        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--indigo-border);
        }

        .book-card:hover .cover-overlay {
            opacity: 1;
        }

        .book-card.rag-hl {
            border-color: var(--indigo);
            box-shadow: 0 0 0 3px rgba(55, 48, 163, .15);
        }

        .book-cover {
            height: 130px;
            background: var(--indigo-light);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .book-cover img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cover-ph {
            font-size: 2.8rem;
            font-weight: 900;
            color: rgba(55, 48, 163, .18);
            position: relative;
            z-index: 1;
        }

        .cover-overlay {
            position: absolute;
            inset: 0;
            background: rgba(55, 48, 163, .5);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .2s;
            z-index: 3;
        }

        .cover-overlay-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: white;
            color: var(--indigo);
            font-weight: 700;
            font-size: .7rem;
            padding: .4rem .9rem;
            border-radius: 999px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
            white-space: nowrap;
        }

        .cover-genre-badge {
            position: absolute;
            top: 7px;
            left: 7px;
            z-index: 2;
            max-width: calc(100% - 68px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: .62rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .92);
            color: var(--indigo);
            border: 1px solid var(--indigo-border);
        }

        .cover-avail-badge {
            position: absolute;
            top: 7px;
            right: 7px;
            z-index: 2;
            flex-shrink: 0;
            font-size: .6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .book-body {
            padding: 10px 11px 11px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .book-title-txt {
            font-weight: 700;
            font-size: .82rem;
            color: var(--text);
            line-height: 1.35;
            margin-bottom: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author-txt {
            font-size: .7rem;
            color: var(--text-sub);
            font-weight: 500;
            margin-bottom: 9px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .book-meta {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            margin-top: auto;
            margin-bottom: 4px;
        }

        .meta-pill {
            padding: 2px 7px;
            background: var(--input-bg);
            border-radius: 7px;
            font-size: .6rem;
            font-weight: 700;
            color: var(--text-sub);
            border: 1px solid var(--border-subtle);
        }

        .meta-pill-mono {
            padding: 2px 7px;
            background: #f3f0ff;
            border-radius: 7px;
            font-size: .6rem;
            font-weight: 700;
            color: #5b21b6;
            font-family: var(--mono);
            border: 1px solid #ede9fe;
        }

        body.dark .meta-pill-mono {
            background: rgba(91, 33, 182, .2);
            color: #c4b5fd;
            border-color: rgba(91, 33, 182, .3);
        }

        .tap-hint {
            font-size: .6rem;
            color: var(--text-faint);
            font-weight: 600;
            text-align: center;
        }

        .borrow-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .875rem;
        }

        .borrow-table thead {
            background: var(--input-bg);
            border-bottom: 2px solid var(--border);
        }

        .borrow-table thead th {
            padding: .65rem 1rem;
            text-align: left;
            font-weight: 700;
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-sub);
        }

        .borrow-table tbody tr {
            border-bottom: 1px solid var(--input-bg);
            transition: background .12s;
        }

        .borrow-table tbody tr:last-child {
            border-bottom: none;
        }

        .borrow-table tbody tr:hover {
            background: var(--input-bg);
        }

        .borrow-table td {
            padding: .7rem 1rem;
            vertical-align: middle;
            color: var(--text);
        }

        .borrow-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r-md);
            padding: .85rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0;
            box-shadow: var(--shadow-sm);
        }

        .borrow-card-top {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding-bottom: .6rem;
            border-bottom: 1px solid var(--input-bg);
            margin-bottom: .6rem;
        }

        .borrow-card-dates {
            display: flex;
            gap: .75rem;
            font-size: .68rem;
            font-weight: 600;
            color: var(--text-sub);
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(6px);
            z-index: 300;
            padding: 1.25rem;
            overflow-y: auto;
            align-items: flex-start;
            justify-content: center;
        }

        .modal-backdrop.show {
            display: flex;
            animation: l-fade-in .15s ease;
        }

        .detail-card {
            background: var(--card);
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            margin: auto;
            animation: l-slide-up .22s cubic-bezier(.34, 1.56, .64, 1) both;
            box-shadow: var(--shadow-lg);
            max-height: 92vh;
            overflow-y: auto;
        }

        @media(max-width:639px) {
            .modal-backdrop {
                padding: 0;
                align-items: flex-end !important;
            }

            .modal-backdrop .modal-card,
            .modal-backdrop .detail-card {
                max-width: 100%;
                width: 100%;
                margin: 0;
                border-radius: var(--r-xl) var(--r-xl) 0 0;
                max-height: 92vh;
                animation: l-sheet-up .28s cubic-bezier(.32, .72, 0, 1) both;
            }

            .sheet-handle {
                display: block;
            }
        }

        .detail-cover {
            height: 190px;
            background: var(--indigo-light);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .detail-cover img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-cover-ph {
            font-size: 5rem;
            font-weight: 900;
            color: rgba(55, 48, 163, .18);
        }

        .detail-body {
            padding: 1.5rem 1.5rem 2rem;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: .55rem 0;
            border-bottom: 1px solid var(--input-bg);
        }

        .info-row:last-of-type {
            border-bottom: none;
        }

        .info-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: var(--indigo-light);
            color: var(--indigo);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .info-label {
            font-size: .58rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--text-sub);
            margin-bottom: 2px;
        }

        .info-value {
            font-size: .85rem;
            font-weight: 700;
            color: var(--text);
        }

        .call-number-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f3f0ff;
            color: #5b21b6;
            font-size: .72rem;
            font-weight: 700;
            font-family: var(--mono);
            padding: .25rem .65rem;
            border-radius: 8px;
        }

        body.dark .call-number-badge {
            background: rgba(91, 33, 182, .2);
            color: #c4b5fd;
        }

        .empty-state {
            padding: 48px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 56px;
            height: 56px;
            background: var(--input-bg);
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            border: 1px solid var(--border);
        }

        .icon-btn {
            width: 44px;
            height: 44px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-sub);
            cursor: pointer;
            transition: all var(--ease);
            box-shadow: var(--shadow-sm);
        }

        .icon-btn:hover {
            background: var(--indigo-light);
            border-color: var(--indigo-border);
            color: var(--indigo);
        }
    </style>
</head>

<body>

<?php
$page = 'books';
include(APPPATH . 'Views/partials/layout.php');

$booksJson = json_encode(array_map(fn($b) => [
    'id'               => (int)($b['id'] ?? 0),
    'title'            => $b['title'] ?? '',
    'author'           => $b['author'] ?? 'Unknown',
    'genre'            => $b['genre'] ?? '',
    'preface'          => $b['preface'] ?? '',
    'published_year'   => $b['published_year'] ?? '',
    'cover_image'      => $b['cover_image'] ?? '',
    'isbn'             => $b['isbn'] ?? '',
    'call_number'      => $b['call_number'] ?? '',
    'available_copies' => (int)($b['available_copies'] ?? 0),
    'total_copies'     => (int)($b['total_copies'] ?? 1),
], $books ?? []));

function svgIcon(string $name, int $size = 16, string $stroke = 'currentColor'): string
{
    $icons = [
        'book-open'     => '<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/>',
        'bolt'          => '<path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round"/>',
        'search'        => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'grid'          => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
        'history'       => '<path d="M3 12a9 9 0 105.657-8.486"/><path d="M3 4v4h4"/><path d="M12 7v5l3 3"/>',
        'eye'           => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
        'barcode'       => '<path d="M3 5h2v14H3V5zm4 0h1v14H7V5zm3 0h2v14h-2V5zm4 0h1v14h-1V5zm3 0h2v14h-2V5z"/>',
        'copy'          => '<rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>',
        'tag'           => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
        'location'      => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>',
        'calendar-days' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/><circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>',
        'check-circle'  => '<path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'x-circle'      => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
        'xmark'         => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
    ];
    $d  = $icons[$name] ?? '<circle cx="12" cy="12" r="10"/>';
    $sw = in_array($name, ['calendar-days', 'barcode', 'tag', 'grid']) ? '1.5' : '1.8';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="' . $stroke . '" stroke-width="' . $sw . '">' . $d . '</svg>';
}
?>

<main class="main-area">

    <!-- Topbar -->
    <div class="topbar fade-up">
        <div>
            <div class="greeting-eyebrow">Resident Portal</div>
            <div class="greeting-name">Community <span style="color:var(--indigo)">Library</span></div>
            <div class="greeting-sub">Browse, search, and borrow books available to all residents</div>
        </div>
        <div class="topbar-right">
            <?= layout_dark_toggle() ?>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash-ok fade-up">
            <?= svgIcon('check-circle', 15, 'currentColor') ?>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash-err fade-up">
            <?= svgIcon('x-circle', 15, 'currentColor') ?>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- AI Smart Suggestion -->
    <div class="ai-card fade-up-1">
        <div class="ai-card-head">
            <div class="ai-icon">
                <?= svgIcon('bolt', 13, 'var(--indigo)') ?>
            </div>
            <div>
                <div class="ai-label">AI Smart Suggestion</div>
            </div>
        </div>
        <div class="ai-input-row">
            <input id="ragQuery" type="text" class="ai-input"
                placeholder="e.g. adventure for kids, Philippine history…"
                onkeydown="if(event.key==='Enter')doRag()">
            <button id="ragBtn" onclick="doRag()" class="find-btn">
                <?= svgIcon('search', 12, 'white') ?>
                Find for Me
            </button>
        </div>
        <div id="ragSkel" style="display:none;margin-top:.65rem;">
            <div class="shimmer" style="width:88%"></div>
            <div class="shimmer" style="width:68%"></div>
            <div class="shimmer" style="width:50%"></div>
        </div>
        <div id="ragErr" style="display:none;margin-top:8px;padding:10px 14px;background:#fee2e2;border:1px solid #fecaca;border-radius:var(--r-sm);font-size:.8rem;color:#991b1b;font-weight:500;"></div>
        <div class="ai-result-box" id="ragRes">
            <p style="font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.15em;color:var(--indigo);margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                <?= svgIcon('bolt', 10, 'var(--indigo)') ?> Librarian Suggestion
            </p>
            <p id="ragText" style="font-size:.82rem;color:#312e81;line-height:1.65;font-style:italic;font-weight:500;"></p>
            <p style="font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--text-sub);margin-top:10px;margin-bottom:6px;">Matching books</p>
            <div id="ragChips" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
        </div>
    </div>

    <!-- Controls -->
    <div class="controls-row fade-up-1">
        <div class="search-wrap">
            <span class="search-icon-pos"><?= svgIcon('search', 13, 'currentColor') ?></span>
            <input id="searchInput" type="text" class="search-input"
                placeholder="Search title or author…"
                oninput="filterBooks()">
        </div>
        <select id="genreFilter" class="genre-select" onchange="filterBooks()">
            <option value="">All Genres</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= esc($g) ?>"><?= esc($g) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="tab-group">
            <button id="tabBrowse" onclick="switchTab('browse')" class="tab-btn">
                <?= svgIcon('grid', 12, 'currentColor') ?> Browse
            </button>
            <button id="tabMine" onclick="switchTab('mine')" class="tab-btn">
                <?= svgIcon('history', 12, 'currentColor') ?> My Borrowings
            </button>
        </div>
    </div>

    <!-- Browse pane -->
    <div id="paneBrowse">
        <?php if (empty($books)): ?>
            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon"><?= svgIcon('book-open', 24, 'var(--text-faint)') ?></div>
                    <h3 style="font-size:.95rem;font-weight:700;margin-bottom:4px;">No books yet</h3>
                    <p style="font-size:.78rem;">The library is being stocked — check back soon!</p>
                </div>
            </div>
        <?php else: ?>
            <div class="books-grid" id="booksGrid">
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
                            <div class="cover-overlay">
                                <span class="cover-overlay-btn">
                                    <?= svgIcon('eye', 11, 'var(--indigo)') ?> View Details
                                </span>
                            </div>
                            <?php if (!empty($book['genre'])): ?>
                                <span class="cover-genre-badge"><?= esc($book['genre']) ?></span>
                            <?php endif; ?>
                            <span class="cover-avail-badge <?= $available ? 'avail-yes' : 'avail-no' ?>">
                                <?= $available ? 'Available' : 'Out' ?>
                            </span>
                        </div>

                        <div class="book-body">
                            <p class="book-title-txt"><?= esc($book['title']) ?></p>
                            <p class="book-author-txt">by <?= esc($book['author'] ?? 'Unknown') ?></p>
                            <div class="book-meta">
                                <span class="meta-pill"><?= (int)($book['available_copies'] ?? 0) ?>/<?= (int)($book['total_copies'] ?? 1) ?> copies</span>
                                <?php if (!empty($book['published_year'])): ?>
                                    <span class="meta-pill"><?= esc($book['published_year']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($book['call_number'])): ?>
                                    <span class="meta-pill-mono"><?= esc($book['call_number']) ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="tap-hint">Tap to view &amp; borrow</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- My Borrowings pane -->
    <div id="paneMine" style="display:none;">
        <?php if (empty($myBorrowings)): ?>
            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon"><?= svgIcon('history', 24, 'var(--text-faint)') ?></div>
                    <h3 style="font-size:.95rem;font-weight:700;margin-bottom:4px;">No borrowing history</h3>
                    <p style="font-size:.78rem;">Books you borrow will appear here.</p>
                </div>
            </div>
        <?php else: ?>
            <!-- Desktop table -->
            <div class="card" style="display:none;" id="borrowTableWrap">
                <div style="padding:16px 18px;border-bottom:1px solid var(--border-subtle);display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;background:var(--indigo-light);border-radius:9px;display:flex;align-items:center;justify-content:center;">
                        <?= svgIcon('history', 14, 'var(--indigo)') ?>
                    </div>
                    <div>
                        <div style="font-size:.88rem;font-weight:700;">My Borrowing History</div>
                        <div style="font-size:.68rem;color:var(--text-sub);"><?= count($myBorrowings) ?> record<?= count($myBorrowings) !== 1 ? 's' : '' ?></div>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="borrow-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Book</th>
                                <th>Borrowed On</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($myBorrowings as $i => $b):
                                $s = strtolower($b['status'] ?? 'pending');
                            ?>
                                <tr>
                                    <td style="color:var(--text-sub);font-weight:700;font-size:.78rem;"><?= $i + 1 ?></td>
                                    <td>
                                        <p style="font-weight:600;font-size:.85rem;"><?= esc($b['title']) ?></p>
                                        <p style="font-size:.72rem;color:var(--text-sub);margin-top:1px;"><?= esc($b['author'] ?? '') ?></p>
                                    </td>
                                    <td style="font-size:.82rem;color:var(--text-muted);font-weight:500;"><?= esc($b['borrowed_at'] ?? '—') ?></td>
                                    <td style="font-size:.82rem;color:var(--text-muted);font-weight:500;"><?= esc($b['due_date'] ?? '—') ?></td>
                                    <td><span class="tag tag-<?= $s ?>"><?= ucfirst($s) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Mobile cards -->
            <div id="borrowCardsWrap" style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:center;gap:9px;margin-bottom:2px;">
                    <div style="width:28px;height:28px;background:var(--indigo-light);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <?= svgIcon('history', 13, 'var(--indigo)') ?>
                    </div>
                    <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:var(--text-sub);">Borrowing History</p>
                </div>
                <?php foreach ($myBorrowings as $b):
                    $s = strtolower($b['status'] ?? 'pending');
                ?>
                    <div class="borrow-card">
                        <div class="borrow-card-top">
                            <div style="width:36px;height:36px;background:var(--indigo-light);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <?= svgIcon('book-open', 15, 'var(--indigo)') ?>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                                    <p style="font-weight:700;font-size:.85rem;line-height:1.3;"><?= esc($b['title']) ?></p>
                                    <span class="tag tag-<?= $s ?>" style="flex-shrink:0;margin-top:1px;"><?= ucfirst($s) ?></span>
                                </div>
                                <p style="font-size:.7rem;color:var(--text-sub);font-weight:500;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($b['author'] ?? '') ?></p>
                            </div>
                        </div>
                        <div class="borrow-card-dates">
                            <span style="display:flex;align-items:center;gap:4px;">
                                <?= svgIcon('calendar-days', 10, 'var(--text-faint)') ?>
                                Borrowed: <?= esc($b['borrowed_at'] ?? '—') ?>
                            </span>
                            <span style="display:flex;align-items:center;gap:4px;<?= $s === 'approved' ? 'color:#e11d48;font-weight:700;' : '' ?>">
                                <?= svgIcon('calendar-days', 10, 'var(--text-faint)') ?>
                                Due: <?= esc($b['due_date'] ?? '—') ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</main>

<!-- Book Detail Modal -->
<div class="modal-backdrop" id="bookDetailModal" onclick="onDetailBackdrop(event)">
    <div class="detail-card">
        <div class="sheet-handle"></div>
        <div class="detail-cover" id="detailCover">
            <span class="detail-cover-ph" id="detailCoverPh"></span>
        </div>
        <div class="detail-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px;">
                <div style="flex:1;min-width:0;">
                    <p id="detailGenrePill" style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.14em;color:var(--indigo);margin-bottom:3px;"></p>
                    <h3 id="detailTitle" style="font-size:1.15rem;font-weight:800;line-height:1.25;letter-spacing:-.02em;"></h3>
                    <p id="detailAuthor" style="font-size:.82rem;font-weight:600;margin-top:2px;"></p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0;">
                    <button onclick="closeDetailModal()" class="icon-btn" style="width:36px;height:36px;border-radius:9px;">
                        <?= svgIcon('xmark', 13, 'currentColor') ?>
                    </button>
                    <span id="detailAvailTag" class="tag"></span>
                </div>
            </div>

            <div id="detailPrefaceBox" class="hidden" style="margin-bottom:16px;padding:14px;background:var(--input-bg);border-radius:var(--r-md);border:1px solid var(--border);">
                <p style="font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--text-sub);margin-bottom:5px;">About this book</p>
                <p id="detailPreface" style="font-size:.82rem;line-height:1.65;font-style:italic;font-weight:500;"></p>
            </div>

            <div class="info-row">
                <div class="info-icon"><?= svgIcon('copy', 13, 'var(--indigo)') ?></div>
                <div>
                    <p class="info-label">Copies</p>
                    <p id="detailCopies" class="info-value"></p>
                </div>
            </div>
            <div class="info-row" id="detailYearRow">
                <div class="info-icon"><?= svgIcon('calendar-days', 13, 'var(--indigo)') ?></div>
                <div>
                    <p class="info-label">Published</p>
                    <p id="detailYear" class="info-value"></p>
                </div>
            </div>
            <div class="info-row" id="detailGenreRow">
                <div class="info-icon"><?= svgIcon('tag', 13, 'var(--indigo)') ?></div>
                <div>
                    <p class="info-label">Genre</p>
                    <p id="detailGenreVal" class="info-value"></p>
                </div>
            </div>
            <div class="info-row" id="detailCallRow">
                <div class="info-icon"><?= svgIcon('location', 13, 'var(--indigo)') ?></div>
                <div>
                    <p class="info-label">Call Number <span style="text-transform:none;font-weight:500;color:var(--text-faint);">(shelf location)</span></p>
                    <p id="detailCallVal" class="info-value"></p>
                </div>
            </div>
            <div class="info-row" id="detailIsbnRow">
                <div class="info-icon"><?= svgIcon('barcode', 13, 'var(--indigo)') ?></div>
                <div>
                    <p class="info-label">ISBN</p>
                    <p id="detailIsbnVal" class="info-value" style="font-family:var(--mono);"></p>
                </div>
            </div>

            <div id="detailActions" style="display:flex;gap:10px;margin-top:20px;"></div>
        </div>
    </div>
</div>

<!-- Borrow Confirm Modal -->
<div class="modal-backdrop" id="borrowModal" onclick="onBorrowBackdrop(event)">
    <div class="modal-card">
        <div class="sheet-handle"></div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <div>
                <h3 style="font-size:1rem;font-weight:800;letter-spacing:-.02em;">Confirm Borrow</h3>
                <p style="font-size:.72rem;font-weight:500;margin-top:2px;">14-day loan period</p>
            </div>
            <button onclick="closeBorrowModal()" class="icon-btn" style="width:36px;height:36px;border-radius:9px;">
                <?= svgIcon('xmark', 13, 'currentColor') ?>
            </button>
        </div>

        <div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--indigo-light);border-radius:var(--r-md);border:1px solid var(--indigo-border);margin-bottom:20px;">
            <div style="width:38px;height:38px;background:var(--card);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:var(--shadow-sm);">
                <?= svgIcon('book-open', 16, 'var(--indigo)') ?>
            </div>
            <div>
                <p style="font-weight:700;font-size:.88rem;" id="modalBookTitle">—</p>
                <p style="font-size:.7rem;margin-top:2px;">You'll be notified once your request is approved</p>
            </div>
        </div>

        <div style="display:flex;gap:10px;">
            <button onclick="closeBorrowModal()" style="flex:1;padding:11px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:600;color:var(--text-sub);border:1px solid var(--border);cursor:pointer;font-size:.85rem;font-family:var(--font);transition:background var(--ease);">Cancel</button>
            <form id="borrowForm" method="post" action="" style="flex:1;">
                <?= csrf_field() ?>
                <button type="submit" style="width:100%;padding:11px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;font-size:.85rem;border:none;cursor:pointer;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28);transition:background var(--ease);">Yes, Borrow</button>
            </form>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url() ?>";
    const BOOKS    = <?= $booksJson ?? '[]' ?>;
    const bookMap  = {};
    BOOKS.forEach(b => bookMap[b.id] = b);

    /* ── Responsive borrow view ── */
    function responsiveBorrowView() {
        const table = document.getElementById('borrowTableWrap');
        const cards = document.getElementById('borrowCardsWrap');
        if (!table || !cards) return;
        const isWide = window.innerWidth >= 768;
        table.style.display = isWide ? 'block' : 'none';
        cards.style.display = isWide ? 'none'  : 'flex';
    }
    window.addEventListener('resize', responsiveBorrowView);
    document.addEventListener('DOMContentLoaded', responsiveBorrowView);

    /* ── Tab switching ── */
    function switchTab(t) {
        document.getElementById('paneBrowse').style.display = t === 'browse' ? '' : 'none';
        document.getElementById('paneMine').style.display   = t === 'mine'   ? '' : 'none';
        document.getElementById('tabBrowse').className = 'tab-btn' + (t === 'browse' ? ' active' : '');
        document.getElementById('tabMine').className   = 'tab-btn' + (t === 'mine'   ? ' active' : '');
    }
    switchTab('browse');

    /* ── Filter ── */
    function filterBooks() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const g = document.getElementById('genreFilter').value;
        document.querySelectorAll('.book-card').forEach(c => {
            const mQ = c.dataset.title.includes(q) || c.dataset.author.includes(q);
            const mG = !g || c.dataset.genre === g;
            c.style.display = mQ && mG ? '' : 'none';
        });
    }

    /* ── Book detail modal ── */
    function openBookDetail(id) {
        const b = bookMap[id];
        if (!b) return;
        const avail = b.available_copies > 0;

        const coverEl = document.getElementById('detailCover');
        const phEl    = document.getElementById('detailCoverPh');
        const oldImg  = coverEl.querySelector('img');
        if (oldImg) oldImg.remove();
        if (b.cover_image) {
            phEl.style.display = 'none';
            const img = document.createElement('img');
            img.src = b.cover_image;
            img.alt = b.title;
            coverEl.appendChild(img);
        } else {
            phEl.style.display = '';
            phEl.textContent = b.title.charAt(0).toUpperCase();
        }

        document.getElementById('detailGenrePill').textContent = b.genre || '';
        document.getElementById('detailTitle').textContent     = b.title;
        document.getElementById('detailAuthor').textContent    = 'by ' + b.author;
        document.getElementById('detailCopies').textContent    = b.available_copies + ' available of ' + b.total_copies + ' total';

        const tag = document.getElementById('detailAvailTag');
        tag.textContent = avail ? 'Available' : 'Not Available';
        tag.className   = 'tag ' + (avail ? 'tag-available' : 'tag-out');

        const prefBox = document.getElementById('detailPrefaceBox');
        if (b.preface) {
            document.getElementById('detailPreface').textContent = b.preface;
            prefBox.classList.remove('hidden');
        } else {
            prefBox.classList.add('hidden');
        }

        const yr = document.getElementById('detailYearRow');
        if (b.published_year) { document.getElementById('detailYear').textContent = b.published_year; yr.style.display = ''; }
        else yr.style.display = 'none';

        const gr = document.getElementById('detailGenreRow');
        if (b.genre) { document.getElementById('detailGenreVal').textContent = b.genre; gr.style.display = ''; }
        else gr.style.display = 'none';

        const cr = document.getElementById('detailCallRow');
        if (b.call_number) { document.getElementById('detailCallVal').innerHTML = '<span class="call-number-badge">' + b.call_number + '</span>'; cr.style.display = ''; }
        else cr.style.display = 'none';

        const ir = document.getElementById('detailIsbnRow');
        if (b.isbn) { document.getElementById('detailIsbnVal').textContent = b.isbn; ir.style.display = ''; }
        else ir.style.display = 'none';

        const acts = document.getElementById('detailActions');
        if (avail) {
            acts.innerHTML = `
                <button
                    data-id="${b.id}"
                    data-title="${b.title.replace(/"/g,'&quot;')}"
                    onclick="closeDetailModal(); openBorrowModal(+this.dataset.id, this.dataset.title)"
                    style="flex:1;padding:12px;background:var(--indigo);color:white;border-radius:var(--r-sm);font-weight:700;font-size:.85rem;border:none;cursor:pointer;font-family:var(--font);box-shadow:0 4px 12px rgba(55,48,163,.28);display:flex;align-items:center;justify-content:center;gap:7px;transition:background var(--ease);"
                    onmouseover="this.style.background='#312e81'" onmouseout="this.style.background='var(--indigo)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Borrow This Book
                </button>
                <button onclick="closeDetailModal()"
                    style="padding:12px 18px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:600;font-size:.85rem;border:1px solid var(--border);cursor:pointer;color:var(--text-sub);font-family:var(--font);">
                    Close
                </button>`;
        } else {
            acts.innerHTML = `
                <button disabled
                    style="flex:1;padding:12px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:600;font-size:.85rem;border:1px solid var(--border);cursor:not-allowed;color:var(--text-sub);font-family:var(--font);display:flex;align-items:center;justify-content:center;gap:7px;">
                    Currently Unavailable
                </button>
                <button onclick="closeDetailModal()"
                    style="padding:12px 18px;background:var(--input-bg);border-radius:var(--r-sm);font-weight:600;font-size:.85rem;border:1px solid var(--border);cursor:pointer;color:var(--text-sub);font-family:var(--font);">
                    Close
                </button>`;
        }

        document.getElementById('bookDetailModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        document.getElementById('bookDetailModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    function onDetailBackdrop(e) {
        if (e.target === document.getElementById('bookDetailModal')) closeDetailModal();
    }

    /* ── Borrow modal ── */
    function openBorrowModal(id, title) {
        document.getElementById('modalBookTitle').textContent = title;
        document.getElementById('borrowForm').action = BASE_URL + 'books/borrow/' + id;
        document.getElementById('borrowModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeBorrowModal() {
        document.getElementById('borrowModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    function onBorrowBackdrop(e) {
        if (e.target === document.getElementById('borrowModal')) closeBorrowModal();
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeDetailModal(); closeBorrowModal(); }
    });

    /* ── RAG ── */
    async function doRag() {
        const query = document.getElementById('ragQuery').value.trim();
        if (query.length < 2) return;
        const skel = document.getElementById('ragSkel');
        const err  = document.getElementById('ragErr');
        const res  = document.getElementById('ragRes');
        const btn  = document.getElementById('ragBtn');
        res.classList.remove('show');
        err.style.display = 'none';
        skel.style.display = 'block';
        btn.disabled = true;
        document.querySelectorAll('.book-card').forEach(c => c.classList.remove('rag-hl'));
        try {
            const r = await fetch('/rag/suggest', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ query })
            });
            const d = await r.json();
            skel.style.display = 'none';
            btn.disabled = false;
            if (!d.suggestion) {
                err.textContent = d.error || d.message || 'No suggestion found.';
                err.style.display = 'block';
                return;
            }
            document.getElementById('ragText').textContent = d.suggestion;
            const chips = document.getElementById('ragChips');
            chips.innerHTML = '';
            (d.books || []).forEach(b => {
                const avail = (b.available_copies || 0) > 0;
                const chip  = document.createElement('button');
                chip.style.cssText = `display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:9px;font-size:.72rem;font-weight:600;border:1px solid;cursor:pointer;font-family:var(--font);transition:all .15s;${avail ? 'background:var(--card);border-color:var(--indigo-border);color:var(--indigo);' : 'background:var(--input-bg);border-color:var(--border);color:var(--text-sub);border-style:dashed;'}`;
                chip.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"/></svg>` +
                    b.title + (avail ? '' : ' <span style="opacity:.55">(out)</span>');
                chip.onclick = () => {
                    openBookDetail(b.id);
                    const card = document.getElementById('book-' + b.id);
                    if (card) { card.classList.add('rag-hl'); card.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
                };
                chips.appendChild(chip);
                const card = document.getElementById('book-' + b.id);
                if (card) card.classList.add('rag-hl');
            });
            res.classList.add('show');
        } catch (e) {
            skel.style.display = 'none';
            btn.disabled = false;
            err.textContent = 'Network error. Please try again.';
            err.style.display = 'block';
        }
    }
</script>

</body>
</html>