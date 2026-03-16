<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | SK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#16a34a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SK Reserve">
    <link rel="apple-touch-icon" href="/assets/img/icon-192.png">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .sidebar-card {
            background: white;
            border-radius: 32px;
            border: 1px solid #e2e8f0;
            height: calc(100vh - 48px);
            position: sticky;
            top: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-header {
            flex-shrink: 0;
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .sidebar-item {
            transition: all 0.2s;
        }

        .sidebar-item.active {
            background: #16a34a;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(22, 163, 74, 0.3);
        }

        .mobile-nav-pill {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 92%;
            max-width: 600px;
            background: rgba(20, 83, 45, 0.98);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 6px;
            z-index: 100;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .mobile-scroll-container {
            display: flex;
            gap: 4px;
            overflow-x: auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }

        .mobile-scroll-container::-webkit-scrollbar {
            display: none;
        }

        .form-card {
            background: white;
            border-radius: 28px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02);
        }

        .section-divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 2rem 0;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e2e8f0;
            font-size: 0.92rem;
            transition: all 0.2s;
            background: #fcfdfe;
            border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #16a34a;
            background: white;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.08);
        }

        input[readonly] {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }

        .type-btn {
            flex: 1;
            text-align: center;
            padding: 0.75rem 1rem;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.2s;
            color: #64748b;
            border: none;
            background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .type-btn.active {
            background: #16a34a;
            color: white;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
        }

        .autocomplete-wrap {
            position: relative;
        }

        .autocomplete-list {
            position: absolute;
            z-index: 50;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            max-height: 220px;
            overflow-y: auto;
            width: 100%;
            top: calc(100% + 4px);
            left: 0;
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            font-size: 0.88rem;
            transition: background 0.15s;
        }

        .autocomplete-item:hover {
            background: #f0fdf4;
            color: #16a34a;
        }

        .autocomplete-item .sub {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        .btn-primary {
            background: #16a34a;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 16px;
            font-weight: 800;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: all 0.25s;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(22, 163, 74, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(6px);
            z-index: 200;
            padding: 1.5rem;
            overflow-y: auto;
            align-items: center;
            justify-content: center;
        }

        .modal-backdrop.show {
            display: flex;
            animation: fadeIn 0.15s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-box {
            background: white;
            border-radius: 32px;
            width: 100%;
            max-width: 460px;
            padding: 2.5rem;
            margin: auto;
            animation: slideUp 0.2s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes slideUp {
            from {
                transform: translateY(16px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .mrow {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f1f5f9;
            gap: 1rem;
        }

        .mrow:last-child {
            border-bottom: none;
        }

        .mrow-label {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .mrow-value {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.85rem;
            text-align: right;
        }

        .field-label {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            display: block;
            margin-bottom: 0.4rem;
        }

        .pc-section {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 20px;
            padding: 1.5rem;
        }
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
    ?>

    <!-- ── Sidebar ── -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Youth Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">SK<span class="text-green-600">.</span></h1>
            </div>

            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = (isset($page) && $page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
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
                $isActive = (isset($page) && $page == $item['key']);
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
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

    <!-- ── Main ── -->
    <main class="flex-1 p-6 lg:p-12 pb-32">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">New Reservation</h2>
                <p class="text-slate-500 font-medium">Register a manual entry into the system.</p>
            </div>
            <a href="/sk/my-reservations" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-600 hover:bg-slate-50 transition flex items-center gap-2">
                <i class="fa-solid fa-chevron-left text-sm"></i> Back
            </a>
        </header>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 px-6 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="form-card max-w-3xl mx-auto p-8 lg:p-10">
            <form id="reservationForm" method="POST" action="<?= base_url('sk/create-reservation') ?>">
                <?= csrf_field() ?>

                <input type="hidden" name="visitor_name" id="finalVisitorName">
                <input type="hidden" name="user_email" id="finalUserEmail">
                <input type="hidden" name="user_id" id="finalUserId">
                <input type="hidden" name="visitor_type" id="finalVisitorType" value="User">
                <input type="hidden" name="purpose" id="finalPurpose">
                <input type="hidden" name="pcs" id="finalPcs" value="[]">

                <!-- ① Visitor Type Toggle -->
                <div class="mb-8">
                    <span class="field-label mb-3 block">Visitor Classification</span>
                    <div class="flex bg-slate-100 p-1.5 rounded-[18px]">
                        <button type="button" class="type-btn active" id="btnUser" onclick="setType('User')">
                            <i class="fa-solid fa-user mr-2"></i>Registered User
                        </button>
                        <button type="button" class="type-btn" id="btnVisitor" onclick="setType('Visitor')">
                            <i class="fa-solid fa-person-walking mr-2"></i>Walk-in Visitor
                        </button>
                    </div>
                </div>

                <hr class="section-divider">

                <!-- ② Personal Details -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-sm">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 tracking-tight">Personal Details</h3>
                    </div>

                    <div id="userFields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Full Name</label>
                            <div class="autocomplete-wrap">
                                <input type="text" id="userNameInput" placeholder="Type to search users…" autocomplete="off">
                                <ul id="autocompleteList" class="autocomplete-list hidden"></ul>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="userEmailDisplay" placeholder="Auto-filled on selection" readonly>
                            <p class="text-[10px] text-slate-400 mt-1">Fills automatically when a user is selected</p>
                        </div>
                    </div>

                    <div id="visitorFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Full Name</label>
                            <input type="text" id="visitorNameInput" placeholder="Enter visitor's full name">
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" id="visitorEmailInput" placeholder="Enter email (optional)">
                        </div>
                    </div>
                </div>

                <hr class="section-divider">

                <!-- ③ Resource & Schedule -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-sm">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 tracking-tight">Resource & Schedule</h3>
                    </div>

                    <div class="mb-5">
                        <label class="field-label">Select Asset / Resource</label>
                        <select id="resourceSelect" name="resource_id" required>
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources as $res): ?>
                                <option value="<?= $res['id'] ?>" data-name="<?= htmlspecialchars($res['name']) ?>">
                                    <?= htmlspecialchars($res['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="pcSection" class="hidden pc-section mb-5">
                        <label class="field-label text-green-700 mb-3 block">Assign Workstation(s)</label>
                        <div id="pcGrid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                            <?php foreach ($pcs as $pc): ?>
                                <?php $num = htmlspecialchars($pc['pc_number']); ?>
                                <button type="button"
                                    onclick="togglePc('<?= $num ?>', this)"
                                    data-pc="<?= $num ?>"
                                    class="pc-btn py-2.5 px-3 rounded-xl text-xs font-bold border border-green-200 bg-white text-slate-600 hover:border-green-400 hover:text-green-700 transition">
                                    <?= $num ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <p id="pcNoneMsg" class="text-xs text-slate-400 mt-2 hidden">No PCs available for this resource.</p>
                        <p class="text-[10px] text-green-700 font-semibold mt-3">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Selected: <span id="pcSelectedLabel">None</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        <div>
                            <label class="field-label">Date</label>
                            <input type="date" name="reservation_date" id="resDate" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div>
                            <label class="field-label">Start Time</label>
                            <input type="time" name="start_time" id="startTime" required>
                        </div>
                        <div>
                            <label class="field-label">End Time</label>
                            <input type="time" name="end_time" id="endTime" required>
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Purpose of Visit</label>
                        <select id="purposeSelect" required>
                            <option value="">— Select purpose —</option>
                            <option value="Work">Work</option>
                            <option value="Personal">Personal</option>
                            <option value="Study">Study</option>
                            <option value="SK Activity">SK Activity</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div id="purposeOtherWrap" class="hidden mt-3">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" placeholder="Describe the purpose…">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" onclick="previewReservation()" class="btn-primary w-full md:w-auto">
                        <i class="fa-solid fa-eye"></i> Preview & Confirm
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- ── Confirmation Modal ── -->
    <div id="confirmModal" class="modal-backdrop" onclick="handleBackdrop(event)">
        <div class="modal-box">
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-green-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl shadow-lg shadow-green-200">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900">Confirm Reservation</h3>
                <p class="text-slate-400 text-sm font-medium mt-1">Review details before saving.</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-5">
                <div class="mrow"><span class="mrow-label">Type</span> <span class="mrow-value" id="mType"></span></div>
                <div class="mrow"><span class="mrow-label">Name</span> <span class="mrow-value" id="mName"></span></div>
                <div class="mrow"><span class="mrow-label">Email</span> <span class="mrow-value" id="mEmail"></span></div>
                <div class="mrow"><span class="mrow-label">Asset</span> <span class="mrow-value" id="mAsset"></span></div>
                <div class="mrow"><span class="mrow-label">Stations</span><span class="mrow-value" id="mStation"></span></div>
                <div class="mrow"><span class="mrow-label">Date</span> <span class="mrow-value" id="mDate"></span></div>
                <div class="mrow"><span class="mrow-label">Time</span> <span class="mrow-value" id="mTime"></span></div>
                <div class="mrow"><span class="mrow-label">Purpose</span> <span class="mrow-value" id="mPurpose"></span></div>
            </div>

            <div id="qrWrap" class="hidden bg-white border-2 border-dashed border-green-100 rounded-2xl p-5 flex flex-col items-center mb-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">E-Ticket Preview</p>
                <canvas id="qrCanvas" class="rounded-xl mx-auto"></canvas>
                <p id="qrText" class="text-xs text-slate-400 font-mono mt-2 text-center break-all"></p>
                <button type="button" onclick="downloadQR()" class="mt-3 flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl font-bold text-xs hover:bg-green-700 transition">
                    <i class="fa-solid fa-download"></i> Download E-Ticket
                </button>
            </div>

            <div class="flex gap-3" id="modalActions">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">
                    Cancel
                </button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" class="flex-1 py-4 bg-green-600 text-white rounded-2xl font-bold hover:bg-green-700 transition text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> Confirm & Save
                </button>
            </div>
        </div>
    </div>

    <script>
        const allUsers = <?= json_encode($users ?? []) ?>;
        let currentType = 'User';
        let selectedUser = null;
        let selectedPcs = [];

        function setType(type) {
            currentType = type;
            document.getElementById('finalVisitorType').value = type;
            const isUser = type === 'User';
            document.getElementById('btnUser').classList.toggle('active', isUser);
            document.getElementById('btnVisitor').classList.toggle('active', !isUser);
            document.getElementById('userFields').classList.toggle('hidden', !isUser);
            document.getElementById('visitorFields').classList.toggle('hidden', isUser);
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
                li.innerHTML = `<div class="font-semibold">${displayName}</div><div class="sub">${u.email}</div>`;
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

        userNameInput.addEventListener('blur', () => {
            setTimeout(() => autocompleteList.classList.add('hidden'), 150);
        });

        document.getElementById('resourceSelect').addEventListener('change', function() {
            const name = (this.options[this.selectedIndex].dataset.name || '').toLowerCase();
            const showPcs = name.includes('computer') || name.includes('pc') || name.includes('lab');
            document.getElementById('pcSection').classList.toggle('hidden', !showPcs);
            selectedPcs = [];
            updatePcHidden();
            document.querySelectorAll('.pc-btn').forEach(b => b.classList.remove('selected-pc'));
        });

        function togglePc(num, btn) {
            const idx = selectedPcs.indexOf(num);
            if (idx === -1) {
                selectedPcs.push(num);
                btn.classList.add('selected-pc', 'bg-green-600', 'text-white', 'border-green-600');
                btn.classList.remove('bg-white', 'text-slate-600', 'border-green-200');
            } else {
                selectedPcs.splice(idx, 1);
                btn.classList.remove('selected-pc', 'bg-green-600', 'text-white', 'border-green-600');
                btn.classList.add('bg-white', 'text-slate-600', 'border-green-200');
            }
            updatePcHidden();
        }

        function updatePcHidden() {
            document.getElementById('finalPcs').value = JSON.stringify(selectedPcs);
            document.getElementById('pcSelectedLabel').textContent = selectedPcs.length ?
                selectedPcs.join(', ') :
                'None';
        }

        document.getElementById('purposeSelect').addEventListener('change', function() {
            document.getElementById('purposeOtherWrap').classList.toggle('hidden', this.value !== 'Others');
        });

        function previewReservation() {
            const isUser = currentType === 'User';
            const name = isUser ?
                userNameInput.value.trim() :
                document.getElementById('visitorNameInput').value.trim();
            const email = isUser ?
                document.getElementById('userEmailDisplay').value.trim() :
                document.getElementById('visitorEmailInput').value.trim();
            const resourceEl = document.getElementById('resourceSelect');
            const resourceId = resourceEl.value;
            const resourceName = resourceEl.options[resourceEl.selectedIndex]?.text || '—';
            const pcSection = !document.getElementById('pcSection').classList.contains('hidden');
            const date = document.getElementById('resDate').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const purposeVal = document.getElementById('purposeSelect').value;
            const purposeOther = document.getElementById('purposeOther').value.trim();
            const purposeFinal = purposeVal === 'Others' && purposeOther ?
                `Others — ${purposeOther}` :
                purposeVal;

            if (!name) {
                alert('Please enter a name.');
                return;
            }
            if (!resourceId) {
                alert('Please select a resource.');
                return;
            }
            if (pcSection && selectedPcs.length === 0) {
                alert('Please select at least one workstation.');
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
            document.getElementById('mStation').textContent = selectedPcs.length ? selectedPcs.join(', ') : '—';
            document.getElementById('mDate').textContent = date;
            document.getElementById('mTime').textContent = `${startTime} – ${endTime}`;
            document.getElementById('mPurpose').textContent = purposeFinal || '—';

            document.getElementById('qrWrap').classList.add('hidden');
            document.getElementById('confirmBtn').style.display = '';
            openModal();
        }

        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
            const code = `SK-${Date.now()}`;
            document.getElementById('qrText').textContent = code;
            QRCode.toCanvas(document.getElementById('qrCanvas'), code, {
                width: 160,
                margin: 1,
                color: {
                    dark: '#1e293b',
                    light: '#ffffff'
                }
            }, () => {
                document.getElementById('qrWrap').classList.remove('hidden');
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
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Confirm & Save';
        }

        function handleBackdrop(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
    </script>

    <!-- ── PWA ── -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js', {
                        scope: '/'
                    })
                    .then(reg => {
                        reg.addEventListener('updatefound', () => {
                            const newWorker = reg.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    showUpdateBanner();
                                }
                            });
                        });
                    })
                    .catch(err => console.warn('[PWA] SW registration failed:', err));

                navigator.serviceWorker.addEventListener('message', event => {
                    if (!event.data) return;
                    switch (event.data.type) {
                        case 'SAVE_PENDING_RESERVATION': {
                            const pending = JSON.parse(localStorage.getItem('pending_reservations') || '[]');
                            pending.push(event.data.payload);
                            localStorage.setItem('pending_reservations', JSON.stringify(pending));
                            showOfflineSavedToast();
                            break;
                        }
                        case 'FLUSH_PENDING_RESERVATIONS': {
                            flushPendingReservations();
                            break;
                        }
                        case 'RESERVATIONS_SYNCED': {
                            console.log('[PWA] Synced');
                            break;
                        }
                    }
                });
            });
        }

        async function flushPendingReservations() {
            const pending = JSON.parse(localStorage.getItem('pending_reservations') || '[]');
            if (!pending.length) return;
            const remaining = [];
            for (const item of pending) {
                try {
                    const formData = new FormData();
                    Object.entries(item.data).forEach(([key, value]) => formData.append(key, value));
                    const response = await fetch(item.url, {
                        method: 'POST',
                        body: formData
                    });
                    if (!response.ok) remaining.push(item);
                } catch (e) {
                    remaining.push(item);
                }
            }
            localStorage.setItem('pending_reservations', JSON.stringify(remaining));
            if (navigator.serviceWorker.controller)
                navigator.serviceWorker.controller.postMessage({
                    type: 'RESERVATIONS_SYNCED'
                });
            if (pending.length !== remaining.length)
                showSyncedToast(pending.length - remaining.length);
        }

        window.addEventListener('online', async () => {
            removeBanner('pwa-offline-banner');
            createBanner('pwa-online-banner', `<i class="fa-solid fa-circle-check" style="color:#fff"></i> Back online!`, '#16a34a', '#fff');
            setTimeout(() => removeBanner('pwa-online-banner'), 3000);
            if ('serviceWorker' in navigator && 'SyncManager' in window) {
                try {
                    const reg = await navigator.serviceWorker.ready;
                    await reg.sync.register('sync-reservations');
                } catch {
                    flushPendingReservations();
                }
            } else {
                flushPendingReservations();
            }
        });

        window.addEventListener('offline', () => {
            createBanner('pwa-offline-banner', `<i class="fa-solid fa-wifi" style="color:#fff;opacity:0.7"></i> You're offline — cached pages still work`, '#1e293b', '#fff');
        });

        function createBanner(id, html, bg, color) {
            if (document.getElementById(id)) return;
            const el = document.createElement('div');
            el.id = id;
            el.style.cssText = `position:fixed;top:0;left:0;right:0;z-index:9999;padding:10px 20px;text-align:center;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;transform:translateY(-100%);transition:transform 0.3s ease;display:flex;align-items:center;justify-content:center;gap:8px;background:${bg};color:${color};`;
            el.innerHTML = html;
            document.body.prepend(el);
            requestAnimationFrame(() => {
                el.style.transform = 'translateY(0)';
            });
        }

        function removeBanner(id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.transform = 'translateY(-100%)';
                setTimeout(() => el.remove(), 350);
            }
        }

        function showUpdateBanner() {
            createBanner('pwa-update-banner', `<i class="fa-solid fa-rotate" style="color:#fff"></i> New version available &nbsp;<button onclick="applyUpdate()" style="background:white;color:#2563eb;border:none;padding:3px 12px;border-radius:8px;cursor:pointer;font-weight:800;font-size:12px;font-family:inherit;">Update now</button>`, '#2563eb', '#fff');
        }

        function applyUpdate() {
            if (navigator.serviceWorker.controller)
                navigator.serviceWorker.controller.postMessage({
                    type: 'SKIP_WAITING'
                });
            location.reload();
        }

        function showToast(icon, iconColor, title, message, duration = 5000) {
            let container = document.getElementById('pwa-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'pwa-toast-container';
                container.style.cssText = 'position:fixed;bottom:90px;right:16px;z-index:8888;display:flex;flex-direction:column;gap:8px;pointer-events:none;';
                document.body.appendChild(container);
            }
            const id = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = id;
            toast.style.cssText = `background:white;border-radius:16px;padding:12px 16px;box-shadow:0 10px 25px rgba(0,0,0,0.12);border-left:4px solid ${iconColor};display:flex;align-items:center;gap:10px;pointer-events:auto;font-family:'Plus Jakarta Sans',sans-serif;min-width:260px;max-width:340px;animation:toastIn 0.3s ease;`;
            toast.innerHTML = `<style>@keyframes toastIn{from{transform:translateX(110%);opacity:0}to{transform:translateX(0);opacity:1}}</style><div style="width:32px;height:32px;border-radius:10px;background:${iconColor}20;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="${icon}" style="color:${iconColor};font-size:14px;"></i></div><div style="flex:1"><p style="font-weight:800;font-size:13px;color:#1e293b;margin:0;">${title}</p><p style="font-size:11px;color:#64748b;margin:2px 0 0;">${message}</p></div><button onclick="document.getElementById('${id}').remove()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:14px;padding:2px;">✕</button>`;
            container.appendChild(toast);
            setTimeout(() => {
                const t = document.getElementById(id);
                if (t) t.remove();
            }, duration);
        }

        function showOfflineSavedToast() {
            showToast('fa-solid fa-floppy-disk', '#f59e0b', 'Saved offline', "Your reservation will sync when you're back online.");
        }

        function showSyncedToast(count) {
            showToast('fa-solid fa-circle-check', '#16a34a', `${count} reservation${count > 1 ? 's' : ''} synced!`, 'Your pending reservations were submitted successfully.');
        }

        let deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', e => {
            e.preventDefault();
            deferredPrompt = e;
            setTimeout(() => {
                if (deferredPrompt && !window.matchMedia('(display-mode: standalone)').matches) showInstallChip();
            }, 10000);
        });

        function showInstallChip() {
            if (document.getElementById('pwa-install-chip')) return;
            const chip = document.createElement('div');
            chip.id = 'pwa-install-chip';
            chip.style.cssText = "position:fixed;bottom:90px;right:16px;z-index:999;background:white;border:1px solid #e2e8f0;border-radius:20px;padding:10px 16px;display:flex;align-items:center;gap:10px;box-shadow:0 10px 25px rgba(0,0,0,0.12);font-family:'Plus Jakarta Sans',sans-serif;animation:toastIn 0.3s ease;cursor:pointer;";
            chip.innerHTML = `<div style="width:32px;height:32px;background:#f0fdf4;border-radius:10px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-download" style="color:#16a34a;font-size:14px;"></i></div><div><p style="font-weight:800;font-size:13px;color:#1e293b;margin:0;">Install SK Reserve</p><p style="font-size:11px;color:#94a3b8;margin:0;">Works offline, no App Store needed</p></div><button onclick="event.stopPropagation();document.getElementById('pwa-install-chip').remove()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:14px;margin-left:4px;">✕</button>`;
            chip.addEventListener('click', async e => {
                if (e.target.tagName === 'BUTTON') return;
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const {
                        outcome
                    } = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    chip.remove();
                }
            });
            document.body.appendChild(chip);
            setTimeout(() => {
                const c = document.getElementById('pwa-install-chip');
                if (c) c.remove();
            }, 12000);
        }

        window.addEventListener('appinstalled', () => {
            deferredPrompt = null;
        });
    </script>

</body>

</html>