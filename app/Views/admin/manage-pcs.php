<?php
$page      = 'manage-pcs';
$user_name = $user_name ?? session()->get('name') ?? 'Administrator';

$totalPcs       = count($pcs ?? []);
$availableCount = count(array_filter($pcs ?? [], fn($p) => $p['status'] === 'available'));
$maintenCount   = $totalPcs - $availableCount;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Manage PCs | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ── PC grid cards ── */
        .pc-card {
            background: var(--card);
            border-radius: var(--r-lg);
            padding: 20px;
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            transition: all var(--ease);
            display: flex;
            flex-direction: column;
        }

        .pc-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--indigo-border);
        }

        .pc-card.available-card {
            border-top: 3px solid #22c55e;
        }

        .pc-card.maintenance-card {
            border-top: 3px solid #f59e0b;
        }

        body.dark .pc-card {
            background: var(--card);
            border-color: rgba(99, 102, 241, .1);
        }

        /* ── PC list cards (mobile) ── */
        .pc-list-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .08);
            border-radius: var(--r-lg);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
        }

        .pc-list-card.available-card {
            border-left: 4px solid #22c55e;
        }

        .pc-list-card.maintenance-card {
            border-left: 4px solid #f59e0b;
        }

        body.dark .pc-list-card {
            background: var(--card);
            border-color: rgba(99, 102, 241, .1);
        }

        /* ── Toggle buttons ── */
        .btn-toggle-to-maint {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: .5rem .85rem;
            border-radius: 9px;
            font-size: .72rem;
            font-weight: 700;
            background: #fef3c7;
            color: #92400e;
            border: 1.5px solid #fde68a;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            flex: 1;
        }

        .btn-toggle-to-maint:hover {
            background: #fde68a;
        }

        .btn-toggle-to-avail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: .5rem .85rem;
            border-radius: 9px;
            font-size: .72rem;
            font-weight: 700;
            background: #dcfce7;
            color: #166534;
            border: 1.5px solid #86efac;
            cursor: pointer;
            font-family: var(--font);
            transition: all var(--ease);
            flex: 1;
        }

        .btn-toggle-to-avail:hover {
            background: #bbf7d0;
        }

        .btn-delete-sm {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: #fef2f2;
            color: #ef4444;
            border: 1.5px solid #fecaca;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--ease);
            flex-shrink: 0;
        }

        .btn-delete-sm:hover {
            background: #fee2e2;
            border-color: #f87171;
        }

        /* ── Responsive grid/list ── */
        @media(min-width:640px) {
            #pcListMobile {
                display: none !important
            }

            #pcGrid {
                display: block !important
            }
        }

        @media(max-width:639px) {
            #pcGrid {
                display: none !important
            }
        }

        /* ── Form fields in modal ── */
        .field-input {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid rgba(99, 102, 241, .12);
            border-radius: var(--r-md);
            padding: 12px 14px 12px 40px;
            font-size: .9rem;
            font-family: var(--font);
            color: var(--text);
            transition: all .2s;
            outline: none;
        }

        .field-input:focus {
            border-color: #818cf8;
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .field-input-plain {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid rgba(99, 102, 241, .12);
            border-radius: var(--r-md);
            padding: 12px 14px;
            font-size: .9rem;
            font-family: var(--font);
            color: var(--text);
            transition: all .2s;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
        }

        .field-input-plain:focus {
            border-color: #818cf8;
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .field-label {
            display: block;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--text-sub);
            margin-bottom: 6px;
        }

        body.dark .field-input,
        body.dark .field-input-plain {
            background: var(--input-bg);
            border-color: rgba(99, 102, 241, .18);
            color: var(--text);
        }
    </style>
</head>

<body>

    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- ════ ADD PC MODAL ════ -->
    <div id="addModal" class="modal-back" onclick="if(event.target===this)closeModal('addModal')">
        <div class="modal-card">
            <div class="sheet-handle"></div>
            <div class="modal-head">
                <div>
                    <div class="modal-title-lbl">Asset Management</div>
                    <h3 class="modal-title">Add New Station</h3>
                    <p style="font-size:.75rem;color:var(--text-sub);margin-top:3px;">Register a PC to the asset pool.</p>
                </div>
                <button onclick="closeModal('addModal')" class="modal-close"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button>
            </div>
            <form action="/admin/add-pc" method="POST" style="display:flex;flex-direction:column;gap:14px;">
                <?= csrf_field() ?>
                <div>
                    <label class="field-label">PC Number / Station Name</label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-desktop" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--text-sub);font-size:.8rem;pointer-events:none;"></i>
                        <input type="text" name="pc_number" required placeholder="e.g. PC-01 or Lab Station 3" value="<?= old('pc_number') ?>" class="field-input">
                    </div>
                </div>
                <div>
                    <label class="field-label">Initial Status</label>
                    <div style="position:relative;">
                        <i class="fa-solid fa-circle-dot" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--text-sub);font-size:.8rem;pointer-events:none;"></i>
                        <select name="status" class="field-input-plain" style="padding-left:38px;">
                            <option value="available" <?= old('status', 'available') == 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="maintenance" <?= old('status') == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;padding-top:4px;">
                    <button type="button" onclick="closeModal('addModal')" class="modal-cancel" style="flex:1;">Cancel</button>
                    <button type="submit" class="action-btn" style="flex:1;justify-content:center;">
                        <i class="fa-solid fa-floppy-disk" style="font-size:.8rem;"></i> Save Station
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ════ DELETE CONFIRM MODAL ════ -->
    <div id="deleteModal" class="modal-back" onclick="if(event.target===this)closeModal('deleteModal')">
        <div class="modal-card" style="max-width:360px;">
            <div class="sheet-handle"></div>
            <div style="text-align:center;padding:8px 0 16px;">
                <div style="width:52px;height:52px;background:#fee2e2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.5rem;">
                    <i class="fa-solid fa-trash-can" style="color:#dc2626;"></i>
                </div>
                <h3 style="font-size:1.1rem;font-weight:800;color:var(--text);">Delete Station?</h3>
                <p style="font-size:.8rem;color:var(--text-sub);margin-top:4px;font-weight:500;">This action cannot be undone.</p>
                <p id="deleteStationName" style="font-size:.85rem;font-weight:700;margin-top:10px;"></p>
            </div>
            <div style="display:flex;gap:10px;">
                <button onclick="closeModal('deleteModal')" class="modal-cancel" style="flex:1;">Cancel</button>
                <a id="deleteLink" href="#" class="modal-danger" style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;">
                    <i class="fa-solid fa-trash-can" style="font-size:.75rem;"></i> Delete
                </a>
            </div>
        </div>
    </div>

    <!-- ════ MAIN ════ -->
    <main class="main-area">

        <div class="topbar fade-up">
            <div>
                <div class="page-eyebrow">Asset Management</div>
                <div class="page-title">Manage PCs</div>
                <div class="page-sub">Monitor and manage station availability</div>
            </div>
            <div class="topbar-right">
                <div class="icon-btn" onclick="adminToggleDark()" title="Toggle dark mode">
                    <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem;"></i></span>
                </div>
                <button onclick="openModal('addModal')" class="action-btn">
                    <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> Add New Station
                </button>
            </div>
        </div>

        <?php if (session()->has('success')): ?>
            <div class="flash-ok fade-up"><i class="fa-solid fa-circle-check"></i><?= session('success') ?><button onclick="this.closest('.flash-ok').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button></div>
        <?php endif; ?>
        <?php if (session()->has('error')): ?>
            <div class="flash-err fade-up"><i class="fa-solid fa-circle-exclamation"></i><?= session('error') ?><button onclick="this.closest('.flash-err').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;"><i class="fa-solid fa-xmark" style="font-size:.75rem;"></i></button></div>
        <?php endif; ?>

        <p class="section-label fade-up-1">Overview</p>
        <div class="stats-grid fade-up-1" style="grid-template-columns:repeat(3,1fr);">
            <div class="stat-card" style="border-left-color:var(--indigo);" onclick="setFilter('all')">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div class="stat-lbl">Total Stations</div><i class="fa-solid fa-layer-group" style="color:var(--indigo);font-size:.85rem;"></i>
                </div>
                <div class="stat-num" style="color:var(--indigo);"><?= $totalPcs ?></div>
            </div>
            <div class="stat-card" style="border-left-color:#16a34a;" onclick="setFilter('available')">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div class="stat-lbl">Available</div><i class="fa-solid fa-circle-check" style="color:#16a34a;font-size:.85rem;"></i>
                </div>
                <div class="stat-num" style="color:#16a34a;"><?= $availableCount ?></div>
            </div>
            <div class="stat-card" style="border-left-color:#d97706;" onclick="setFilter('maintenance')">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div class="stat-lbl">Maintenance</div><i class="fa-solid fa-wrench" style="color:#d97706;font-size:.85rem;"></i>
                </div>
                <div class="stat-num" style="color:#d97706;"><?= $maintenCount ?></div>
            </div>
        </div>

        <div class="card fade-up-1" style="padding:16px 20px;margin-bottom:16px;">
            <div style="display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap;">
                <div class="search-wrap" style="flex:1;min-width:180px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search stations…" oninput="applyFilters()">
                </div>
                <button class="reset-btn" onclick="clearFilters()"><i class="fa-solid fa-rotate-left" style="font-size:.7rem;"></i> Reset</button>
            </div>
            <div style="display:flex;gap:8px;overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:2px;">
                <button class="qtab active" data-tab="all" onclick="setFilter('all')"><i class="fa-solid fa-layer-group" style="font-size:.7rem;"></i> All <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $totalPcs ?></span></button>
                <button class="qtab" data-tab="available" onclick="setFilter('available')"><i class="fa-solid fa-circle-check" style="font-size:.7rem;"></i> Available <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $availableCount ?></span></button>
                <button class="qtab" data-tab="maintenance" onclick="setFilter('maintenance')"><i class="fa-solid fa-wrench" style="font-size:.7rem;"></i> Maintenance <span style="font-size:.6rem;opacity:.7;font-family:var(--mono);"><?= $maintenCount ?></span></button>
            </div>
            <p id="resultCount" style="font-size:.65rem;font-weight:700;color:var(--text-sub);margin-top:10px;"></p>
        </div>

        <?php if (empty($pcs)): ?>
            <div class="empty-state" style="text-align:center;padding:48px 20px;">
                <i class="fa-solid fa-desktop" style="font-size:2.5rem;color:#e2e8f0;display:block;margin-bottom:12px;"></i>
                <p style="font-weight:800;color:var(--text-sub);font-size:1rem;">No stations yet</p>
                <p style="color:var(--text-faint);font-size:.82rem;margin-top:4px;margin-bottom:16px;">Add your first PC to get started.</p>
                <button onclick="openModal('addModal')" class="action-btn" style="display:inline-flex;">
                    <i class="fa-solid fa-plus" style="font-size:.8rem;"></i> Add Station
                </button>
            </div>
        <?php else: ?>

            <!-- Desktop grid -->
            <div id="pcGrid" style="display:none;" class="fade-up-1">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;">
                    <?php foreach ($pcs as $pc):
                        $isAvail = $pc['status'] === 'available';
                        $assetId = str_pad($pc['id'], 4, '0', STR_PAD_LEFT);
                        $pcNum   = htmlspecialchars($pc['pc_number']);
                    ?>
                        <div class="pc-card <?= $isAvail ? 'available-card' : 'maintenance-card' ?>"
                            data-status="<?= $pc['status'] ?>"
                            data-search="<?= strtolower($pcNum) ?>">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px;">
                                <div style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;<?= $isAvail ? 'background:var(--indigo-light);color:var(--indigo);' : 'background:#fef3c7;color:#d97706;' ?>">
                                    <i class="fa-solid fa-desktop" style="font-size:1.1rem;"></i>
                                </div>
                                <span class="tag <?= $isAvail ? 'tag-available' : 'tag-maintenance' ?>">
                                    <i class="fa-solid <?= $isAvail ? 'fa-circle-check' : 'fa-wrench' ?>" style="font-size:.55rem;"></i>
                                    <?= ucfirst($pc['status']) ?>
                                </span>
                            </div>
                            <h3 style="font-size:1rem;font-weight:800;margin-bottom:2px;">Station <?= $pcNum ?></h3>
                            <p style="font-size:.68rem;color:var(--text-sub);font-family:var(--mono);margin-bottom:12px;">Asset #<?= $assetId ?></p>
                            <div style="margin-bottom:14px;">
                                <p style="font-size:.65rem;font-weight:700;color:<?= $isAvail ? '#16a34a' : '#d97706' ?>;margin-bottom:5px;"><?= $isAvail ? 'Ready for booking' : 'Under maintenance' ?></p>
                                <div class="prog-bar">
                                    <div class="prog-fill" style="width:<?= $isAvail ? '100%' : '40%' ?>;background:<?= $isAvail ? '#16a34a' : '#d97706' ?>;"></div>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;margin-top:auto;">
                                <form action="/admin/update-pc-status" method="POST" style="flex:1;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                    <input type="hidden" name="status" value="<?= $isAvail ? 'maintenance' : 'available' ?>">
                                    <button type="submit" class="<?= $isAvail ? 'btn-toggle-to-maint' : 'btn-toggle-to-avail' ?>" style="width:100%;">
                                        <i class="fa-solid <?= $isAvail ? 'fa-wrench' : 'fa-circle-check' ?>" style="font-size:.65rem;"></i>
                                        <?= $isAvail ? 'Set Maintenance' : 'Set Available' ?>
                                    </button>
                                </form>
                                <button onclick="confirmDelete(<?= $pc['id'] ?>, '<?= addslashes($pcNum) ?>')" class="btn-delete-sm">
                                    <i class="fa-solid fa-trash-can" style="font-size:.75rem;"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Mobile list -->
            <div id="pcListMobile" style="display:flex;flex-direction:column;gap:10px;" class="fade-up-1">
                <?php foreach ($pcs as $pc):
                    $isAvail = $pc['status'] === 'available';
                    $assetId = str_pad($pc['id'], 4, '0', STR_PAD_LEFT);
                    $pcNum   = htmlspecialchars($pc['pc_number']);
                ?>
                    <div class="pc-list-card <?= $isAvail ? 'available-card' : 'maintenance-card' ?>"
                        data-status="<?= $pc['status'] ?>"
                        data-search="<?= strtolower($pcNum) ?>">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">
                            <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                                <div style="width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;<?= $isAvail ? 'background:var(--indigo-light);color:var(--indigo);' : 'background:#fef3c7;color:#d97706;' ?>">
                                    <i class="fa-solid fa-desktop" style="font-size:.95rem;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <p style="font-weight:800;font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Station <?= $pcNum ?></p>
                                    <p style="font-size:.68rem;color:var(--text-sub);font-family:var(--mono);">Asset #<?= $assetId ?></p>
                                </div>
                            </div>
                            <span class="tag <?= $isAvail ? 'tag-available' : 'tag-maintenance' ?>" style="flex-shrink:0;">
                                <i class="fa-solid <?= $isAvail ? 'fa-circle-check' : 'fa-wrench' ?>" style="font-size:.55rem;"></i>
                                <?= ucfirst($pc['status']) ?>
                            </span>
                        </div>
                        <div style="margin-bottom:10px;">
                            <div class="prog-bar">
                                <div class="prog-fill" style="width:<?= $isAvail ? '100%' : '40%' ?>;background:<?= $isAvail ? '#16a34a' : '#d97706' ?>;"></div>
                            </div>
                            <p style="font-size:.68rem;font-weight:600;color:<?= $isAvail ? '#16a34a' : '#d97706' ?>;margin-top:5px;"><?= $isAvail ? 'Ready for booking' : 'Under maintenance' ?></p>
                        </div>
                        <div style="display:flex;gap:8px;">
                            <form action="/admin/update-pc-status" method="POST" style="flex:1;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                <input type="hidden" name="status" value="<?= $isAvail ? 'maintenance' : 'available' ?>">
                                <button type="submit" class="<?= $isAvail ? 'btn-toggle-to-maint' : 'btn-toggle-to-avail' ?>" style="width:100%;padding:.6rem .75rem;">
                                    <i class="fa-solid <?= $isAvail ? 'fa-wrench' : 'fa-circle-check' ?>" style="font-size:.65rem;"></i>
                                    <?= $isAvail ? 'Set Maintenance' : 'Set Available' ?>
                                </button>
                            </form>
                            <button onclick="confirmDelete(<?= $pc['id'] ?>, '<?= addslashes($pcNum) ?>')" class="btn-delete-sm">
                                <i class="fa-solid fa-trash-can" style="font-size:.75rem;"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="noResultsMsg" class="hidden empty-state" style="margin-top:16px;">
                <i class="fa-solid fa-filter-circle-xmark" style="font-size:1.8rem;color:#e2e8f0;display:block;margin-bottom:8px;"></i>
                <p style="font-size:.85rem;font-weight:700;color:var(--text-sub);">No stations match your search.</p>
            </div>
        <?php endif; ?>

    </main>

    <script>
        let curFilter = 'all';

        function openModal(id) {
            document.getElementById(id).classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeModal('addModal');
                closeModal('deleteModal');
            }
        });

        function confirmDelete(id, name) {
            document.getElementById('deleteStationName').textContent = `"Station ${name}"`;
            document.getElementById('deleteLink').href = `/admin/delete-pc/${id}`;
            openModal('deleteModal');
        }

        function setFilter(f) {
            curFilter = f;
            document.querySelectorAll('.qtab').forEach(t => t.classList.toggle('active', t.dataset.tab === f));
            applyFilters();
        }

        function applyFilters() {
            const q = document.getElementById('searchInput').value.toLowerCase().trim();
            let n = 0;
            const allCards = document.querySelectorAll('[data-status][data-search]');
            allCards.forEach(c => {
                const matchS = curFilter === 'all' || c.dataset.status === curFilter;
                const matchQ = !q || c.dataset.search.includes(q);
                const show = matchS && matchQ;
                c.style.display = show ? '' : 'none';
                if (show) n++;
            });
            const total = allCards.length;
            document.getElementById('resultCount').textContent = `Showing ${n} of ${total} station${total !== 1 ? 's' : ''}`;
            const noMsg = document.getElementById('noResultsMsg');
            if (noMsg) noMsg.classList.toggle('hidden', n > 0);
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            setFilter('all');
        }

        applyFilters();
    </script>
</body>

</html>