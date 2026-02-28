<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation | <?= esc($user_name ?? 'User') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
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

        .availability-indicator {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 999px;
        }

        .available {
            background: #dcfce7;
            color: #166534;
        }

        .limited {
            background: #fef9c3;
            color: #854d0e;
        }

        .unavailable {
            background: #fee2e2;
            color: #991b1b;
        }

        .pc-btn.selected-pc {
            background: #16a34a !important;
            color: white !important;
            border-color: #16a34a !important;
        }

        /* Notification Styles */
        .notification-bell {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 150;
            cursor: pointer;
            transition: transform 0.2s ease;
            background: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .notification-bell:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.2rem 0.4rem;
            border-radius: 999px;
            min-width: 1.2rem;
            text-align: center;
            animation: pulse 2s infinite;
            border: 2px solid white;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .notification-dropdown {
            position: fixed;
            top: 80px;
            right: 24px;
            width: 360px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            border: 1px solid #e2e8f0;
            z-index: 1000;
            display: none;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
            cursor: pointer;
        }

        .notification-item:hover {
            background: #f8fafc;
        }

        .notification-item.unread {
            background: #f0fdf4;
            border-left: 3px solid #16a34a;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-time {
            font-size: 0.65rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }

        .notification-empty {
            padding: 3rem 2rem;
            text-align: center;
            color: #94a3b8;
        }

        .notification-empty i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        /* Mobile-optimized notifications */
        @media (max-width: 640px) {
            .notification-bell {
                top: 16px;
                right: 16px;
                width: 44px;
                height: 44px;
            }

            .notification-dropdown {
                position: fixed;
                top: 70px;
                right: 10px;
                left: 10px;
                width: auto;
                max-width: none;
            }
        }

        .toast-container {
            position: fixed;
            top: 80px;
            right: 24px;
            left: 24px;
            z-index: 2000;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        @media (min-width: 640px) {
            .toast-container {
                left: auto;
                width: 380px;
            }
        }

        .toast-message {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #10b981;
            margin-bottom: 0.75rem;
            pointer-events: auto;
            width: 100%;
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="flex">

    <?php
    $navItems = [
        ['url' => '/dashboard',       'icon' => 'fa-house',           'label' => 'Dashboard',       'key' => 'dashboard'],
        ['url' => '/reservation',     'icon' => 'fa-plus',            'label' => 'New Reservation', 'key' => 'reservation'],
        ['url' => '/reservation-list', 'icon' => 'fa-calendar',        'label' => 'My Reservations', 'key' => 'reservation-list'],
        ['url' => '/profile',         'icon' => 'fa-regular fa-user', 'label' => 'Profile',         'key' => 'profile'],
    ];
    ?>

    <!-- Notification Bell -->
    <div class="notification-bell" onclick="toggleNotifications()">
        <i class="fa-regular fa-bell text-xl text-slate-600"></i>
        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
    </div>

    <!-- Notification Dropdown -->
    <div id="notificationDropdown" class="notification-dropdown">
        <div class="notification-header">
            <span>Notifications</span>
            <button onclick="markAllAsRead()" class="text-xs bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-bold transition">
                Mark all read
            </button>
        </div>
        <div id="notificationList" class="notification-list">
            <!-- Notifications will be populated here -->
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container">
        <!-- Toasts will appear here -->
    </div>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-80 p-6">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <span class="text-xs font-black tracking-[0.2em] text-green-600 uppercase">Resident Portal</span>
                <h1 class="text-2xl font-extrabold text-slate-800">my<span class="text-green-600">Space.</span></h1>
            </div>

            <nav class="sidebar-nav space-y-1">
                <?php foreach ($navItems as $item):
                    $active = ($page == $item['key']) ? 'active' : 'text-slate-500 hover:bg-slate-50 hover:text-green-600';
                ?>
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-item flex items-center gap-4 px-5 py-3.5 rounded-2xl font-semibold text-sm <?= $active ?>">
                        <i class="fa-solid <?= $item['icon'] ?> w-5 text-center text-lg"></i>
                        <?= $item['label'] ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= base_url('/logout') ?>" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-red-500 font-bold hover:bg-red-50 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </a>
            </div>
        </div>
    </aside>

    <!-- Mobile Nav -->
    <nav class="lg:hidden mobile-nav-pill">
        <div class="mobile-scroll-container text-white px-2">
            <?php foreach ($navItems as $item):
                $isActive = ($page == $item['key']);
                $btnClass = $isActive ? 'bg-green-700 font-semibold' : 'hover:bg-green-500/30';
            ?>
                <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 <?= $btnClass ?>">
                    <i class="fa-solid <?= $item['icon'] ?> text-lg"></i>
                    <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
            <a href="<?= base_url('/logout') ?>" class="flex flex-col items-center justify-center py-2 px-3 min-w-[75px] rounded-xl transition flex-shrink-0 hover:bg-red-500/30 text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                <span class="text-[10px] mt-1 text-center leading-tight whitespace-nowrap">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-12 pb-32">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">New Reservation</h2>
                <p class="text-slate-500 font-medium">Book a resource for your upcoming visit.</p>
            </div>
            <a href="<?= base_url('/reservation-list') ?>" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-600 hover:bg-slate-50 transition flex items-center gap-2">
                <i class="fa-solid fa-chevron-left text-sm"></i> My Reservations
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

        <!-- Fairness Warning -->
        <?php if (isset($remainingReservations) && $remainingReservations > 0): ?>
            <div class="mb-6 px-6 py-4 bg-blue-50 border border-blue-200 text-blue-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-info-circle"></i>
                You have <?= $remainingReservations ?> reservation<?= $remainingReservations != 1 ? 's' : '' ?> remaining this period (max 3 per 2 weeks).
            </div>
        <?php endif; ?>

        <?php if (isset($isBlocked) && $isBlocked): ?>
            <div class="mb-6 px-6 py-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-ban"></i>
                You are temporarily blocked from making reservations until <?= date('F j, Y', strtotime($isBlocked['blocked_until'])) ?>.
            </div>
        <?php endif; ?>

        <div class="form-card max-w-3xl mx-auto p-8 lg:p-10">
            <form id="reservationForm" method="POST" action="<?= base_url('reservation/create') ?>">
                <?= csrf_field() ?>

                <!-- Hidden fields - auto-filled with current user -->
                <input type="hidden" name="user_id" id="finalUserId" value="<?= $user['id'] ?? '' ?>">
                <input type="hidden" name="visitor_name" id="finalVisitorName" value="<?= esc($user['name'] ?? '') ?>">
                <input type="hidden" name="user_email" id="finalUserEmail" value="<?= esc($user['email'] ?? '') ?>">
                <input type="hidden" name="visitor_type" id="finalVisitorType" value="User">
                <input type="hidden" name="purpose" id="finalPurpose">
                <input type="hidden" name="pcs" id="finalPcs" value="">
                <!-- IMPORTANT: Add this hidden field for e-ticket code -->
                <input type="hidden" name="e_ticket_code" id="finalETicketCode" value="">

                <!-- Personal Details - Pre-filled with logged-in user -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-sm">
                            <i class="fa-solid fa-user-circle"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 tracking-tight">Your Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="field-label">Full Name</label>
                            <input type="text" value="<?= esc($user['name'] ?? '') ?>" readonly class="bg-slate-50">
                        </div>
                        <div>
                            <label class="field-label">Email Address</label>
                            <input type="email" value="<?= esc($user['email'] ?? '') ?>" readonly class="bg-slate-50">
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                        <i class="fa-solid fa-check-circle"></i>
                        Booking as yourself
                    </p>
                </div>

                <hr class="section-divider">

                <!-- Resource & Schedule -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-sm">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 tracking-tight">Resource & Schedule</h3>
                    </div>

                    <!-- Resource picker from database -->
                    <div class="mb-5">
                        <label class="field-label">Select Resource</label>
                        <select id="resourceSelect" name="resource_id" required>
                            <option value="">— Choose a resource —</option>
                            <?php foreach ($resources as $res): ?>
                                <option value="<?= $res['id'] ?>"
                                    data-name="<?= esc($res['name']) ?>"
                                    data-type="<?= $res['type'] ?? '' ?>"
                                    data-has-pcs="<?= (strpos(strtolower($res['name']), 'computer') !== false ||
                                                        strpos(strtolower($res['name']), 'pc') !== false ||
                                                        strpos(strtolower($res['name']), 'lab') !== false) ? '1' : '0' ?>">
                                    <?= esc($res['name']) ?>
                                    <?php if (!empty($res['capacity'])): ?>
                                        (Capacity: <?= $res['capacity'] ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- PC multi-select (shown for computer resources) -->
                    <div id="pcSection" class="hidden pc-section mb-5">
                        <label class="field-label text-green-700 mb-3 block">Select Workstation(s)</label>
                        <div id="pcGrid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
                            <?php foreach ($pcs ?? [] as $pc): ?>
                                <?php $num = esc($pc['pc_number'] ?? $pc['name'] ?? ''); ?>
                                <?php if (!empty($num)): ?>
                                    <button type="button"
                                        onclick="togglePc('<?= $num ?>', this)"
                                        data-pc="<?= $num ?>"
                                        class="pc-btn py-2.5 px-3 rounded-xl text-xs font-bold border border-green-200 bg-white text-slate-600 hover:border-green-400 hover:text-green-700 transition">
                                        <?= $num ?>
                                    </button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-[10px] text-green-700 font-semibold mt-3">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Selected: <span id="pcSelectedLabel">None</span>
                        </p>
                    </div>

                    <!-- Date & Time with availability check -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        <div>
                            <label class="field-label">Date</label>
                            <input type="date" name="reservation_date" id="resDate"
                                value="<?= date('Y-m-d') ?>"
                                min="<?= date('Y-m-d') ?>"
                                onchange="checkAvailability()"
                                required>
                        </div>
                        <div>
                            <label class="field-label">Start Time</label>
                            <input type="time" name="start_time" id="startTime"
                                onchange="checkAvailability()"
                                required>
                        </div>
                        <div>
                            <label class="field-label">End Time</label>
                            <input type="time" name="end_time" id="endTime"
                                onchange="checkAvailability()"
                                required>
                        </div>
                    </div>

                    <!-- Availability indicator -->
                    <div id="availabilityMsg" class="hidden mb-4 p-3 rounded-xl text-sm font-medium">
                    </div>

                    <!-- Purpose from database -->
                    <div>
                        <label class="field-label">Purpose of Visit</label>
                        <select id="purposeSelect" name="purpose" required>
                            <option value="">— Select purpose —</option>
                            <?php foreach ($purposes ?? ['Work', 'Personal', 'Study', 'SK Activity', 'Others'] as $purpose): ?>
                                <option value="<?= esc($purpose) ?>"><?= esc($purpose) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="purposeOtherWrap" class="hidden mt-3">
                        <label class="field-label">Please Specify</label>
                        <input type="text" id="purposeOther" placeholder="Describe your purpose...">
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

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal-backdrop" onclick="handleBackdrop(event)">
        <div class="modal-box">
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-amber-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl shadow-lg shadow-amber-200">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900">Confirm Reservation</h3>
                <p class="text-slate-400 text-sm font-medium mt-1">Review your booking details</p>
                <p class="text-xs text-amber-600 font-bold mt-2">Your reservation will be pending approval</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-5">
                <div class="mrow"><span class="mrow-label">Name</span> <span class="mrow-value" id="mName"><?= esc($user['name'] ?? '') ?></span></div>
                <div class="mrow"><span class="mrow-label">Email</span> <span class="mrow-value" id="mEmail"><?= esc($user['email'] ?? '') ?></span></div>
                <div class="mrow"><span class="mrow-label">Resource</span><span class="mrow-value" id="mAsset"></span></div>
                <div class="mrow"><span class="mrow-label">Workstation</span><span class="mrow-value" id="mStation"></span></div>
                <div class="mrow"><span class="mrow-label">Date</span> <span class="mrow-value" id="mDate"></span></div>
                <div class="mrow"><span class="mrow-label">Time</span> <span class="mrow-value" id="mTime"></span></div>
                <div class="mrow"><span class="mrow-label">Purpose</span> <span class="mrow-value" id="mPurpose"></span></div>
            </div>

            <!-- QR Code / E-Ticket Preview -->
            <div id="qrWrap" class="hidden bg-white border-2 border-dashed border-green-100 rounded-2xl p-5 flex flex-col items-center mb-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Your E-Ticket</p>
                <canvas id="qrCanvas" class="rounded-xl mx-auto"></canvas>
                <p id="qrText" class="text-xs text-slate-400 font-mono mt-2 text-center break-all"></p>
                <button type="button" onclick="downloadQR()" class="mt-3 flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl font-bold text-xs hover:bg-green-700 transition">
                    <i class="fa-solid fa-download"></i> Download Ticket
                </button>
            </div>

            <!-- Note about approval -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-5 text-center">
                <i class="fa-regular fa-bell text-blue-500 mb-2 text-xl"></i>
                <p class="text-xs text-blue-700 font-medium">
                    You will receive a notification on this device once your reservation is approved.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-slate-600 hover:bg-slate-200 transition text-sm">
                    Cancel
                </button>
                <button type="button" id="confirmBtn" onclick="submitReservation()" class="flex-1 py-4 bg-green-600 text-white rounded-2xl font-bold hover:bg-green-700 transition text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> Submit Request
                </button>
            </div>
        </div>
    </div>

    <script>
        // User data from session
        const currentUser = {
            id: <?= $user['id'] ?? 'null' ?>,
            name: "<?= esc($user['name'] ?? '', 'js') ?>",
            email: "<?= esc($user['email'] ?? '', 'js') ?>"
        };

        let selectedPcs = [];
        let selectedResource = null;

        // Notification System
        let notifications = [
            <?php if (!empty($recentApprovals)): ?>
                <?php foreach ($recentApprovals as $approval): ?> {
                        id: <?= $approval['id'] ?>,
                        title: 'Reservation Approved! 🎉',
                        message: 'Your reservation for <?= esc($approval['resource_name']) ?> on <?= date('M j, Y', strtotime($approval['reservation_date'])) ?> has been approved.',
                        time: '<?= $approval['approved_at'] ?? date('Y-m-d H:i:s') ?>',
                        read: false,
                        resource: '<?= esc($approval['resource_name']) ?>',
                        date: '<?= $approval['reservation_date'] ?>'
                    },
                <?php endforeach; ?>
            <?php endif; ?>
        ];

        let unreadCount = notifications.filter(n => !n.read).length;
        let checkInterval;
        let lastCheckTime = new Date().toISOString();

        // Request notification permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            if ('Notification' in window) {
                Notification.requestPermission();
            }

            // Load notifications
            renderNotifications();
            updateNotificationBadge();

            // Check for new approvals every 30 seconds
            checkInterval = setInterval(checkForNewApprovals, 30000);

            // Set up event listener for when user returns to tab
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    checkForNewApprovals();
                }
            });

            // Show toast for unread notifications
            notifications.forEach(notif => {
                if (!notif.read) {
                    showToast(notif);
                }
            });
        });

        // Check for new approvals
        function checkForNewApprovals() {
            fetch('<?= base_url("reservation/check-new-approvals") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        user_id: currentUser.id,
                        last_check: lastCheckTime
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.new_approvals && data.new_approvals.length > 0) {
                        data.new_approvals.forEach(approval => {
                            const notification = {
                                id: approval.id,
                                title: 'Reservation Approved! 🎉',
                                message: `Your reservation for ${approval.resource_name} on ${new Date(approval.date).toLocaleDateString()} has been approved.`,
                                time: new Date().toISOString(),
                                read: false,
                                resource: approval.resource_name,
                                date: approval.date
                            };

                            addNotification(notification);
                            showPushNotification(notification);
                            showToast(notification);
                        });

                        lastCheckTime = new Date().toISOString();
                    }
                })
                .catch(error => console.error('Error checking approvals:', error));
        }

        // Add notification to list
        function addNotification(notification) {
            notifications.unshift(notification);
            unreadCount++;
            updateNotificationBadge();
            renderNotifications();
        }

        // Show push notification
        function showPushNotification(notification) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(notification.title, {
                    body: notification.message,
                    icon: '/favicon.ico',
                    badge: '/favicon.ico',
                    tag: 'reservation-approval',
                    renotify: true,
                    vibrate: [200, 100, 200] // Vibration pattern for mobile
                });
            }
        }

        // Show toast notification
        function showToast(notification) {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now() + Math.random();

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = 'toast-message';
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm text-slate-800">${notification.title}</p>
                        <p class="text-xs text-slate-600 mt-1">${notification.message}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Just now</p>
                    </div>
                    <button onclick="document.getElementById('${toastId}').remove()" class="text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                const toastEl = document.getElementById(toastId);
                if (toastEl) {
                    toastEl.remove();
                }
            }, 5000);
        }

        // Toggle notification dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

        // Mark all notifications as read
        function markAllAsRead() {
            notifications.forEach(notif => notif.read = true);
            unreadCount = 0;
            updateNotificationBadge();
            renderNotifications();
        }

        // Mark single notification as read
        function markAsRead(id) {
            const notif = notifications.find(n => n.id === id);
            if (notif && !notif.read) {
                notif.read = true;
                unreadCount = Math.max(0, unreadCount - 1);
                updateNotificationBadge();
                renderNotifications();
            }
        }

        // Update notification badge
        function updateNotificationBadge() {
            const badge = document.getElementById('notificationBadge');
            if (unreadCount > 0) {
                badge.style.display = 'block';
                badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
            } else {
                badge.style.display = 'none';
            }
        }

        // Render notifications in dropdown
        function renderNotifications() {
            const list = document.getElementById('notificationList');

            if (notifications.length === 0) {
                list.innerHTML = `
                    <div class="notification-empty">
                        <i class="fa-regular fa-bell-slash"></i>
                        <p class="text-sm">No notifications</p>
                    </div>
                `;
                return;
            }

            list.innerHTML = notifications
                .sort((a, b) => new Date(b.time) - new Date(a.time))
                .map(notif => `
                    <div class="notification-item ${!notif.read ? 'unread' : ''}" onclick="markAsRead(${notif.id})">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-check text-green-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-sm text-slate-800">${notif.title}</p>
                                <p class="text-xs text-slate-600 mt-1">${notif.message}</p>
                                <p class="notification-time">${formatTimeAgo(notif.time)}</p>
                            </div>
                            ${!notif.read ? '<span class="w-2 h-2 bg-green-500 rounded-full mt-1"></span>' : ''}
                        </div>
                    </div>
                `).join('');
        }

        // Format time ago
        function formatTimeAgo(time) {
            const seconds = Math.floor((new Date() - new Date(time)) / 1000);

            if (seconds < 60) return 'Just now';
            if (seconds < 3600) return `${Math.floor(seconds / 60)} minutes ago`;
            if (seconds < 86400) return `${Math.floor(seconds / 3600)} hours ago`;
            return `${Math.floor(seconds / 86400)} days ago`;
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const bell = document.querySelector('.notification-bell');

            if (!bell.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Auto-fill hidden fields on load
        document.getElementById('finalUserId').value = currentUser.id;
        document.getElementById('finalVisitorName').value = currentUser.name;
        document.getElementById('finalUserEmail').value = currentUser.email;

        // Resource change handler
        document.getElementById('resourceSelect').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const hasPcs = option.dataset.hasPcs === '1';
            const resourceName = option.dataset.name || '';

            selectedResource = {
                id: this.value,
                name: resourceName,
                hasPcs: hasPcs
            };

            document.getElementById('pcSection').classList.toggle('hidden', !hasPcs);

            // Reset PC selections
            selectedPcs = [];
            updatePcHidden();
            document.querySelectorAll('.pc-btn').forEach(btn => {
                btn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
                btn.classList.add('bg-white', 'text-slate-600', 'border-green-200');
            });

            checkAvailability();
        });

        // PC multi-select
        function togglePc(num, btn) {
            const idx = selectedPcs.indexOf(num);
            if (idx === -1) {
                selectedPcs.push(num);
                btn.classList.add('bg-green-600', 'text-white', 'border-green-600');
                btn.classList.remove('bg-white', 'text-slate-600', 'border-green-200');
            } else {
                selectedPcs.splice(idx, 1);
                btn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
                btn.classList.add('bg-white', 'text-slate-600', 'border-green-200');
            }
            updatePcHidden();
        }

        function updatePcHidden() {
            // Join selected PCs with comma for storage in pc_number field
            document.getElementById('finalPcs').value = selectedPcs.join(', ');
            document.getElementById('pcSelectedLabel').textContent =
                selectedPcs.length ? selectedPcs.join(', ') : 'None';
        }

        // Purpose "Others" handler
        document.getElementById('purposeSelect').addEventListener('change', function() {
            const isOther = this.value === 'Others';
            document.getElementById('purposeOtherWrap').classList.toggle('hidden', !isOther);
            if (!isOther) {
                document.getElementById('purposeOther').value = '';
            }
        });

        // Check availability via AJAX
        function checkAvailability() {
            const resourceId = document.getElementById('resourceSelect').value;
            const date = document.getElementById('resDate').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;

            if (!resourceId || !date || !startTime || !endTime) {
                document.getElementById('availabilityMsg').classList.add('hidden');
                return;
            }

            const msgEl = document.getElementById('availabilityMsg');
            msgEl.classList.remove('hidden', 'available', 'unavailable');
            msgEl.textContent = 'Checking availability...';
            msgEl.classList.add('bg-slate-100', 'text-slate-600');

            // Get CSRF token
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';

            fetch('<?= base_url("reservation/check-availability") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({
                        resource_id: resourceId,
                        date: date,
                        start_time: startTime,
                        end_time: endTime,
                        [csrfName]: csrfHash
                    })
                })
                .then(response => response.json())
                .then(data => {
                    msgEl.classList.remove('bg-slate-100', 'text-slate-600');

                    if (data.available) {
                        msgEl.textContent = data.message;
                        msgEl.classList.add('available');
                    } else {
                        msgEl.textContent = data.message;
                        msgEl.classList.add('unavailable');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    msgEl.classList.remove('bg-slate-100', 'text-slate-600');
                    msgEl.textContent = 'Error checking availability';
                    msgEl.classList.add('unavailable');
                });
        }

        // Preview reservation
        function previewReservation() {
            const resourceEl = document.getElementById('resourceSelect');
            const resourceId = resourceEl.value;
            const resourceName = resourceEl.options[resourceEl.selectedIndex]?.text || '—';
            const date = document.getElementById('resDate').value;
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            const purposeVal = document.getElementById('purposeSelect').value;
            const purposeOther = document.getElementById('purposeOther').value.trim();
            const purposeFinal = purposeVal === 'Others' && purposeOther ?
                `Others - ${purposeOther}` :
                purposeVal;
            const hasPcSection = !document.getElementById('pcSection').classList.contains('hidden');

            // Validation
            if (!resourceId) {
                alert('Please select a resource');
                return;
            }
            if (hasPcSection && selectedPcs.length === 0) {
                alert('Please select at least one workstation');
                return;
            }
            if (!date) {
                alert('Please select a date');
                return;
            }
            if (!startTime) {
                alert('Please enter start time');
                return;
            }
            if (!endTime) {
                alert('Please enter end time');
                return;
            }
            if (!purposeVal) {
                alert('Please select a purpose');
                return;
            }

            // Set final values
            document.getElementById('finalPurpose').value = purposeFinal;

            // Update modal
            document.getElementById('mName').textContent = currentUser.name;
            document.getElementById('mEmail').textContent = currentUser.email;
            document.getElementById('mAsset').textContent = resourceName;
            document.getElementById('mStation').textContent = selectedPcs.length ? selectedPcs.join(', ') : 'None';
            document.getElementById('mDate').textContent = date;
            document.getElementById('mTime').textContent = `${startTime} – ${endTime}`;
            document.getElementById('mPurpose').textContent = purposeFinal;

            // Show QR code in modal
            const qrWrap = document.getElementById('qrWrap');
            qrWrap.classList.remove('hidden');
            
            // Generate QR code with e-ticket format (for preview only)
            const previewCode = `SK-${currentUser.id}-PREVIEW`;
            document.getElementById('qrText').textContent = previewCode;
            
            QRCode.toCanvas(document.getElementById('qrCanvas'), previewCode, {
                width: 140,
                margin: 1,
                color: { dark: '#1e293b', light: '#ffffff' }
            }, () => {});

            openModal();
        }

        // Submit reservation
        function submitReservation() {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';

            // Generate unique e-ticket code with user ID and timestamp
            const eTicketCode = `SK-${currentUser.id}-${Date.now()}`;
            
            // Set the e-ticket code in the hidden field
            document.getElementById('finalETicketCode').value = eTicketCode;

            // Update QR code with the real e-ticket code
            document.getElementById('qrText').textContent = eTicketCode;
            
            QRCode.toCanvas(document.getElementById('qrCanvas'), eTicketCode, {
                width: 160,
                margin: 1,
                color: { dark: '#1e293b', light: '#ffffff' }
            }, () => {
                // Submit form after QR generation
                document.getElementById('reservationForm').submit();
            });
        }

        // Download QR code
        function downloadQR() {
            const canvas = document.getElementById('qrCanvas');
            const code = document.getElementById('qrText').textContent;
            const link = document.createElement('a');
            link.download = `E-Ticket-${code}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // Modal functions
        function openModal() {
            document.getElementById('confirmModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('show');
            document.body.style.overflow = '';
            const btn = document.getElementById('confirmBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Submit Request';
        }

        function handleBackdrop(e) {
            if (e.target === document.getElementById('confirmModal')) closeModal();
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('resDate').setAttribute('min', today);

        // Clean up interval on page unload
        window.addEventListener('beforeunload', function() {
            if (checkInterval) {
                clearInterval(checkInterval);
            }
        });
    </script>
</body>

</html>