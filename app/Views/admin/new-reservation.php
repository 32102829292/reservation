<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | Admin</title>
    <link rel="stylesheet" href="<?= base_url('css/admin_app.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <script>
        (function() {
            if (localStorage.getItem('admin_theme') === 'dark') document.documentElement.classList.add('dark-pre')
        })();
    </script>
    <style>
        /* ── Form Styles ── */
        .form-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid rgba(99, 102, 241, .08);
            box-shadow: var(--shadow-sm);
            padding: 32px;
            max-width: 760px;
            margin: 0 auto
        }

        @media(max-width:639px) {
            .form-card {
                padding: 20px 16px
            }
        }

        .section-divider {
            border: none;
            border-top: 1px solid rgba(99, 102, 241, .08);
            margin: 1.75rem 0
        }

        /* ── Type Toggle ── */
        .type-toggle {
            display: flex;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 14px;
            gap: 4px
        }

        .type-btn {
            flex: 1;
            text-align: center;
            padding: .7rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: .82rem;
            transition: all .18s;
            color: #64748b;
            border: none;
            background: transparent;
            font-family: var(--font)
        }

        .type-btn.active {
            background: var(--indigo);
            color: white;
            box-shadow: 0 4px 14px rgba(55, 48, 163, .3)
        }

        /* ── Autocomplete ── */
        .autocomplete-wrap {
            position: relative
        }

        .autocomplete-list {
            position: absolute;
            z-index: 50;
            background: white;
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--r-md);
            box-shadow: var(--shadow-md);
            max-height: 220px;
            overflow-y: auto;
            width: 100%;
            top: calc(100% + 4px);
            left: 0
        }

        .autocomplete-item {
            padding: 11px 14px;
            cursor: pointer;
            font-size: .85rem;
            transition: background .12s
        }

        .autocomplete-item:hover {
            background: var(--indigo-light);
            color: var(--indigo)
        }

        .autocomplete-item .sub {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: 2px
        }

        /* ── PC Section ── */
        .pc-section {
            background: var(--indigo-light);
            border: 1px solid var(--indigo-border);
            border-radius: var(--r-md);
            padding: 16px
        }

        /* ── Button ── */
        .btn-primary {
            background: var(--indigo);
            color: white;
            border: none;
            padding: .875rem 1.75rem;
            border-radius: var(--r-md);
            font-weight: 800;
            font-size: .85rem;
            letter-spacing: .04em;
            cursor: pointer;
            transition: all .2s;
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(55, 48, 163, .28)
        }

        .btn-primary:hover {
            background: var(--indigo-mid);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(55, 48, 163, .35)
        }

        .btn-primary:active {
            transform: translateY(0)
        }

        /* ── Flash ── */
        .flash-err {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 13px 18px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-weight: 700;
            border-radius: var(--r-md);
            font-size: .875rem
        }

        /* ── Icon helper ── */
        .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--indigo-light);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        /* ── Modal ── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(7px);
            z-index: 200;
            padding: 1.5rem;
            overflow-y: auto;
            align-items: center;
            justify-content: center
        }

        .modal-backdrop.show {
            display: flex;
            animation: fadeIn .15s ease
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        .modal-box {
            background: white;
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 460px;
            padding: 28px;
            margin: auto;
            animation: slideUp .2s ease;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg)
        }

        @keyframes slideUp {
            from {
                transform: translateY(14px);
                opacity: 0
            }

            to {
                transform: none;
                opacity: 1
            }
        }

        .mrow {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: .55rem 0;
            border-bottom: 1px solid rgba(99, 102, 241, .07);
            gap: 1rem
        }

        .mrow:last-child {
            border-bottom: none
        }

        .mrow-label {
            font-size: .6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #94a3b8;
            flex-shrink: 0
        }

        .mrow-value {
            font-weight: 700;
            color: #0f172a;
            font-size: .83rem;
            text-align: right
        }

        .sheet-handle {
            display: none;
            width: 36px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 999px;
            margin: 0 auto 16px
        }

        @media(max-width:639px) {
            .modal-backdrop {
                padding: 0;
                align-items: flex-end !important
            }

            .modal-box {
                border-radius: var(--r-xl) var(--r-xl) 0 0;
                max-width: 100%;
                animation: sheetUp .25s cubic-bezier(.34, 1.2, .64, 1) both
            }

            .sheet-handle {
                display: block
            }
        }

        @keyframes sheetUp {
            from {
                opacity: 0;
                transform: translateY(60px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        /* ── Dark Mode extras ── */
        body.dark .form-card {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .field-input {
            background: #101e35;
            border-color: rgba(99, 102, 241, .18);
            color: #e2eaf8
        }

        body.dark .field-input:focus {
            background: #0b1628;
            border-color: var(--indigo)
        }

        body.dark .field-input[readonly] {
            background: #060e1e;
            color: #4a6fa5
        }

        body.dark .type-toggle {
            background: #101e35
        }

        body.dark .type-btn {
            color: #7fb3e8
        }

        body.dark .section-divider {
            border-color: rgba(99, 102, 241, .1)
        }

        body.dark .section-icon {
            background: rgba(55, 48, 163, .2)
        }

        body.dark .pc-section {
            background: rgba(55, 48, 163, .1);
            border-color: rgba(99, 102, 241, .2)
        }

        body.dark .flash-err {
            background: rgba(220, 38, 38, .1);
            border-color: rgba(248, 113, 113, .3);
            color: #f87171
        }

        body.dark .autocomplete-list {
            background: #0b1628;
            border-color: rgba(99, 102, 241, .18)
        }

        body.dark .autocomplete-item:hover {
            background: rgba(99, 102, 241, .12);
            color: #a5b4fc
        }

        body.dark .autocomplete-item .sub {
            color: #4a6fa5
        }

        body.dark .modal-box {
            background: #0b1628;
            color: #e2eaf8
        }

        body.dark .mrow-label {
            color: #4a6fa5
        }

        body.dark .mrow-value {
            color: #e2eaf8
        }

        body.dark .mrow {
            border-color: rgba(99, 102, 241, .08)
        }

        body.dark .sheet-handle {
            background: #1e3a5f
        }

        body.dark .modal-box .bg-slate-50 {
            background: #101e35 !important;
            border-color: rgba(99, 102, 241, .1) !important
        }

        body.dark .field-input::placeholder {
            color: #4a6fa5
        }

        body.dark select.field-input option {
            background: #0b1628;
            color: #e2eaf8
        }
    </style>
</head>

<body>
    
    <?php include APPPATH . 'Views/partials/admin_layout.php'; ?>

    <!-- ── Main ── -->
    <main class="main-area">
        <!-- Header -->
        <header style="display:flex;flex-direction:column;gap:4px;margin-bottom:28px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
                <div>
                    <p style="font-size:.62rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#94a3b8;margin-bottom:4px">Administration</p>
                    <h2 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1.1">New Reservation</h2>
                    <p style="font-size:.78rem;color:#94a3b8;font-weight:500;margin-top:4px">Register a manual entry into the system.</p>
                </div>
                <div style="display:flex;align-items:center;gap:10px;margin-top:4px">
                    <div onclick="adminToggleDark()" title="Toggle dark mode" style="width:44px;height:44px;background:white;border:1px solid rgba(99,102,241,.12);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;color:#64748b;cursor:pointer;transition:all var(--ease);box-shadow:var(--shadow-sm)">
                        <span id="darkIcon"><i class="fa-regular fa-sun" style="font-size:.85rem"></i></span>
                    </div>
                    <a href="/admin/manage-reservations" style="display:flex;align-items:center;gap:7px;padding:10px 18px;background:white;border:1px solid rgba(99,102,241,.15);border-radius:var(--r-sm);font-size:.85rem;font-weight:700;color:#475569;text-decoration:none;transition:all var(--ease);box-shadow:var(--shadow-sm)">
                        <i class="fa-solid fa-chevron-left" style="font-size:.75rem"></i> Back
                    </a>
                </div>
            </div>
        </header>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-err"><i class="fa-solid fa-circle-exclamation"></i><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form id="reservationForm" method="POST" action="<?= base_url('admin/create-reservation') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="visitor_name" id="finalVisitorName">
                <input type="hidden" name="user_email" id="finalUserEmail">
                <input type="hidden" name="user_id" id="finalUserId">
                <input type="hidden" name="visitor_type" id="finalVisitorType" value="User">
                <input type="hidden" name="purpose" id="finalPurpose">

                <!-- ① Visitor Type -->
                <div style="margin-bottom:24px">
                    <span class="field-label" style="margin-bottom:10px;display:block">Visitor Classification</span>
                    <div class="type-toggle">
                        <button type="button" class="type-btn active" id="btnUser" onclick="setType('User')">
                            <i class="fa-solid fa-user" style="margin-right:7px;font-size:.8rem"></i>Registered User
                        </button>
                        <button type="button" class="type-btn" id="btnVisitor" onclick="setType('Visitor')">
                            <i class="fa-solid fa-person-walking" style="margin-right:7px;font-size:.8rem"></i>Walk-in Visitor
                        </button>
                    </div>
                </div>

                <hr class="section-divider">

                <!-- ② Personal Details -->
                <div style="margin-bottom:24px">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
                        <div class="section-icon"><i class="fa-solid fa-id-card" style="color:var(--indigo);font-size:.85rem"></i></div>
                        <div>
                            <p style="font-weight:800;color:#0f172a;font-size:.9rem">Personal Details</p>
                            <p style="font-size:.7rem;color:#94a3b8;margin-top:2px">Identify the visitor</p>
                        </div>
                    </div>

                    <div id="userFields" style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                        <div>
                            <label class="field-label">Full Name</label>
                            <div class="autocomplete-wrap">
                                <input type="text" id="userNameInput" class="field-input" placeholder="Type to search users…" autocomplete="off">
                                <ul id="autocompleteList" class="autocomplete-list hidden"></ul>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="userEmailDisplay" class="field-input" placeholder="Auto-filled on selection" readonly>
                            <p style="font-size:.65rem;color:#94a3b8;margin-top:4px">Fills automatically when a user is selected</p>
                        </div>
                    </div>

                    <div id="visitorFields" style="display:none;grid-template-columns:1fr 1fr;gap:16px">
                        <div>
                            <label class="field-label">Full Name</label>
                            <input type="text" id="visitorNameInput" class="field-input" placeholder="Enter visitor's full name">
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="visitorEmailInput" class="field-input" placeholder="Enter email (optional)">
                        </div>
                    </div>
                </div>

                <hr class="section-divider">

                <!-- ③ Resource & Schedule -->
                <div style="margin-bottom:24px">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px">
                        <div class="section-icon"><i class="fa-solid fa-calendar-days" style="color:var(--indigo);font-size:.85rem"></i></div>
                        <div>
                            <p style="font-weight:800;color:#0f172a;font-size:.9rem">Resource & Schedule</p>
                            <p style="font-size:.7rem;color:#94a3b8;margin-top:2px">What, where, and when</p>
                        </div>
                    </div>

                    <div style="margin-bottom:16px">
                        <label class="field-label">Select Asset / Resource</label>
                        <select id="resourceSelect" name="resource_id" class="field-input" required>
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources as $res): ?>
                                <option value="<?= $res['id'] ?>" data-name="<?= htmlspecialchars($res['name']) ?>">
                                    <?= htmlspecialchars($res['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="pcSection" style="display:none;margin-bottom:16px" class="pc-section">
                        <label class="field-label" style="color:var(--indigo)">Assign Workstation</label>
                        <select id="pcSelect" name="pc_number" class="field-input" style="margin-top:6px">
                            <option value="">— No specific station —</option>
                            <?php foreach ($pcs as $pc): ?>
                                <option value="<?= htmlspecialchars($pc['pc_number']) ?>">Station <?= htmlspecialchars($pc['pc_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px">
                        <div>
                            <label class="field-label">Date</label>
                            <input type="date" name="reservation_date" id="resDate" class="field-input" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div>
                            <label class="field-label">Start Time</label>
                            <input type="time" name="start_time" id="startTime" class="field-input" required>
                        </div>
                        <div>
                            <label class="field-label">End Time</label>
                            <input type="time" name="end_time" id="endTime" class="field-input" required>
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Purpose of Visit</label>
                        <select id="purposeSelect" class="field-input" required>
                            <option value="">— Select purpose —</option>
                            <option value="Work">Work</option>
                            <option value="Personal">Personal</option>
                            <option value="Study">Study</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div id="purposeOtherWrap" style="display:none;margin-top:12px">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" class="field-input" placeholder="Describe the purpose…">
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;padding-top:8px;border-top:1px solid rgba(99,102,241,.08)">
                    <button type="button" onclick="previewReservation()" class="btn-primary" style="width:100%;max-width:none">
                        <i class="fa-solid fa-eye" style="font-size:.85rem"></i> Preview & Confirm
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- ── Confirmation Modal ── -->
    <div id="confirmModal" class="modal-backdrop" onclick="handleBackdrop(event)">
        <div class="modal-box">
            <div class="sheet-handle"></div>
            <div style="text-align:center;margin-bottom:20px">
                <div style="width:52px;height:52px;background:var(--indigo);color:white;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.1rem;box-shadow:0 8px 20px rgba(55,48,163,.3)">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <h3 style="font-size:1.05rem;font-weight:800;color:#0f172a;letter-spacing:-.02em">Confirm Reservation</h3>
                <p style="font-size:.75rem;color:#94a3b8;margin-top:3px">Review details before saving.</p>
            </div>

            <div style="background:#f8fafc;border-radius:var(--r-md);padding:16px;border:1px solid rgba(99,102,241,.08);margin-bottom:16px">
                <div class="mrow"><span class="mrow-label">Type</span><span class="mrow-value" id="mType"></span></div>
                <div class="mrow"><span class="mrow-label">Name</span><span class="mrow-value" id="mName"></span></div>
                <div class="mrow"><span class="mrow-label">Email</span><span class="mrow-value" id="mEmail"></span></div>
                <div class="mrow"><span class="mrow-label">Asset</span><span class="mrow-value" id="mAsset"></span></div>
                <div class="mrow"><span class="mrow-label">Station</span><span class="mrow-value" id="mStation"></span></div>
                <div class="mrow"><span class="mrow-label">Date</span><span class="mrow-value" id="mDate"></span></div>
                <div class="mrow"><span class="mrow-label">Time</span><span class="mrow-value" id="mTime"></span></div>
                <div class="mrow"><span class="mrow-label">Purpose</span><span class="mrow-value" id="mPurpose"></span></div>
            </div>

            <div id="qrWrap" style="display:none;background:white;border:2px dashed var(--indigo-border);border-radius:var(--r-md);padding:20px;flex-direction:column;align-items:center;margin-bottom:16px">
                <p style="font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.15em;color:#94a3b8;margin-bottom:12px">E-Ticket Preview</p>
                <canvas id="qrCanvas" style="border-radius:10px;margin:0 auto"></canvas>
                <p id="qrText" style="font-size:.7rem;color:#94a3b8;font-family:var(--mono);margin-top:8px;text-align:center;word-break:break-all"></p>
                <button type="button" onclick="downloadQR()" style="margin-top:12px;display:flex;align-items:center;gap:6px;padding:8px 16px;background:var(--indigo);color:white;border-radius:9px;font-weight:700;font-size:.75rem;border:none;cursor:pointer;font-family:var(--font)">
                    <i class="fa-solid fa-download"></i> Download E-Ticket
                </button>
            </div>

            <div style="display:flex;gap:10px">
                <button type="button" onclick="closeModal()" style="flex:1;padding:13px;background:#f8fafc;border-radius:var(--r-md);font-weight:700;color:#475569;border:1px solid rgba(99,102,241,.1);cursor:pointer;font-family:var(--font);font-size:.82rem;transition:background .15s" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">Cancel</button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" style="flex:2;padding:13px;background:var(--indigo);color:white;border-radius:var(--r-md);font-weight:700;border:none;cursor:pointer;font-family:var(--font);font-size:.82rem;display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 4px 14px rgba(55,48,163,.3);transition:background .15s">
                    <i class="fa-solid fa-check" style="font-size:.75rem"></i> Confirm & Save
                </button>
            </div>
        </div>
    </div>

    <script>
        const allUsers = <?= json_encode($users ?? []) ?>;
        let currentType = 'User';
        let selectedUser = null;

        /* Responsive user fields grid */
        function setFieldGridCols() {
            const g1 = document.getElementById('userFields'),
                g2 = document.getElementById('visitorFields');
            const cols = window.innerWidth < 640 ? '1fr' : '1fr 1fr';
            g1.style.gridTemplateColumns = cols;
            g2.style.gridTemplateColumns = cols;
        }
        setFieldGridCols();
        window.addEventListener('resize', setFieldGridCols);

        function setType(type) {
            currentType = type;
            document.getElementById('finalVisitorType').value = type;
            const isUser = type === 'User';
            document.getElementById('btnUser').classList.toggle('active', isUser);
            document.getElementById('btnVisitor').classList.toggle('active', !isUser);
            document.getElementById('userFields').style.display = isUser ? 'grid' : 'none';
            document.getElementById('visitorFields').style.display = isUser ? 'none' : 'grid';
            selectedUser = null;
            document.getElementById('userNameInput').value = '';
            document.getElementById('userEmailDisplay').value = '';
            document.getElementById('visitorNameInput').value = '';
            document.getElementById('visitorEmailInput').value = '';
            document.getElementById('finalUserId').value = '';
        }

        const userNameInput = document.getElementById('userNameInput');
        const autocompleteList = document.getElementById('autocompleteList');

        userNameInput.addEventListener('input', () => {
            const q = userNameInput.value.toLowerCase().trim();
            autocompleteList.innerHTML = '';
            selectedUser = null;
            if (!q) {
                autocompleteList.classList.add('hidden');
                return;
            }
            const matches = allUsers.filter(u =>
                (u.name && u.name.toLowerCase().includes(q)) ||
                (u.full_name && u.full_name.toLowerCase().includes(q)) ||
                (u.email && u.email.toLowerCase().includes(q))
            ).slice(0, 8);
            if (!matches.length) {
                autocompleteList.classList.add('hidden');
                return;
            }
            matches.forEach(u => {
                const displayName = u.full_name || u.name || '';
                const li = document.createElement('li');
                li.className = 'autocomplete-item';
                li.innerHTML = `<div style="font-weight:700;font-size:.85rem">${displayName}</div><div class="sub">${u.email}</div>`;
                li.addEventListener('mousedown', () => {
                    selectedUser = u;
                    userNameInput.value = displayName;
                    document.getElementById('userEmailDisplay').value = u.email;
                    document.getElementById('finalUserId').value = u.id;
                    autocompleteList.classList.add('hidden');
                });
                autocompleteList.appendChild(li);
            });
            autocompleteList.classList.remove('hidden');
        });
        userNameInput.addEventListener('blur', () => setTimeout(() => autocompleteList.classList.add('hidden'), 150));

        document.getElementById('resourceSelect').addEventListener('change', function() {
            const name = this.options[this.selectedIndex].dataset.name || '';
            const isComputer = name.toLowerCase().includes('computer') || name.toLowerCase().includes('pc');
            document.getElementById('pcSection').style.display = isComputer ? 'block' : 'none';
        });

        document.getElementById('purposeSelect').addEventListener('change', function() {
            document.getElementById('purposeOtherWrap').style.display = this.value === 'Others' ? 'block' : 'none';
        });

        function previewReservation() {
            const isUser = currentType === 'User';
            const name = isUser ? userNameInput.value.trim() : document.getElementById('visitorNameInput').value.trim();
            const email = isUser ? document.getElementById('userEmailDisplay').value.trim() : document.getElementById('visitorEmailInput').value.trim();
            const resourceEl = document.getElementById('resourceSelect');
            const resourceId = resourceEl.value;
            const resourceName = resourceEl.options[resourceEl.selectedIndex]?.text || '—';
            const pcVal = document.getElementById('pcSelect').value;
            const date = document.getElementById('resDate').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const purposeVal = document.getElementById('purposeSelect').value;
            const purposeOther = document.getElementById('purposeOther').value.trim();
            const purposeFinal = purposeVal === 'Others' && purposeOther ? `Others — ${purposeOther}` : purposeVal;

            if (!name) {
                alert('Please enter a name.');
                return;
            }
            if (!resourceId) {
                alert('Please select a resource.');
                return;
            }
            if (!date) {
                alert('Please select a date.');
                return;
            }
            if (!startTime) {
                alert('Please enter a start time.');
                return;
            }
            if (!endTime) {
                alert('Please enter an end time.');
                return;
            }
            if (!purposeVal) {
                alert('Please select a purpose.');
                return;
            }
            if (isUser && !selectedUser && !document.getElementById('finalUserId').value) {
                alert('Please select a registered user from the dropdown.');
                return;
            }

            document.getElementById('finalVisitorName').value = name;
            document.getElementById('finalUserEmail').value = email;
            document.getElementById('finalPurpose').value = purposeFinal;

            document.getElementById('mType').textContent = isUser ? 'Registered User' : 'Walk-in Visitor';
            document.getElementById('mName').textContent = name || '—';
            document.getElementById('mEmail').textContent = email || '—';
            document.getElementById('mAsset').textContent = resourceName;
            document.getElementById('mStation').textContent = pcVal ? `Station ${pcVal}` : '—';
            document.getElementById('mDate').textContent = date;
            document.getElementById('mTime').textContent = `${startTime} – ${endTime}`;
            document.getElementById('mPurpose').textContent = purposeFinal || '—';

            document.getElementById('qrWrap').style.display = 'none';
            document.getElementById('confirmBtn').style.display = '';
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
            const code = `ACCESS-${Date.now()}`;
            document.getElementById('qrText').textContent = code;
            QRCode.toCanvas(document.getElementById('qrCanvas'), code, {
                width: 150,
                margin: 1,
                color: {
                    dark: '#0f172a',
                    light: '#ffffff'
                }
            }, () => {
                document.getElementById('qrWrap').style.display = 'flex';
                btn.style.display = 'none';
                setTimeout(() => document.getElementById('reservationForm').submit(), 800);
            });
        }

        function downloadQR() {
            const canvas = document.getElementById('qrCanvas');
            const code = document.getElementById('qrText').textContent;
            const link = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        function openModal() {
            document.getElementById('confirmModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('show');
            document.body.style.overflow = '';
            const btn = document.getElementById('confirmBtn');
            btn.disabled = false;
            btn.style.display = '';
            btn.innerHTML = '<i class="fa-solid fa-check" style="font-size:.75rem"></i> Confirm & Save';
        }

        function handleBackdrop(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>