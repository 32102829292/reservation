<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>New Reservation</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eff6ff;
            overflow-x: hidden;
        }

        main {
            padding-bottom: env(safe-area-inset-bottom, 5.5rem);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 9999px;
            background-color: #3b82f6;
            color: white;
            font-weight: 500;
        }

        .logout-btn:hover {
            background-color: #2563eb;
        }

        .form-container {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="time"],
        select,
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .radio-group,
        .checkbox-group {
            display: flex;
            gap: 2rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .radio-item,
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .radio-item input,
        .checkbox-item input {
            width: auto;
        }

        .pc-list {
            background: #f9fafb;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .pc-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .pc-item:last-child {
            margin-bottom: 0;
        }

        .pc-info {
            flex: 1;
        }

        .pc-info p {
            margin: 0.25rem 0;
            font-size: 0.9rem;
            color: #4b5563;
        }

        .pc-info strong {
            color: #1f2937;
        }

        .pc-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-warning {
            background-color: #f59e0b;
            color: white;
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
        }

        .btn-warning:hover {
            background-color: #d97706;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .maintenance-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .success-message {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            display: none;
        }

        .success-message.show {
            display: block;
        }

        .add-pc-section {
            background: #eff6ff;
            border: 2px dashed #3b82f6;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .add-pc-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0.75rem;
            align-items: flex-end;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .add-pc-form {
                grid-template-columns: 1fr;
            }

            .radio-group,
            .checkbox-group {
                gap: 1rem;
            }
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">
    <?php $page = $page ?? 'new-reservation'; ?>

<!-- Hamburger Menu Button for Small Screens -->
<button id="hamburgerBtn" class="fixed top-4 right-4 z-50 bg-blue-600 text-white p-2 rounded-lg shadow-lg md:hidden">
    <i class="fa-solid fa-bars"></i>
</button>

<!-- Mobile Drawer for Small Screens -->
<div id="mobileDrawer" class="fixed inset-0 z-40 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" id="drawerBackdrop"></div>
    <div class="absolute left-0 top-0 h-full w-64 bg-blue-600 text-white shadow-xl transform -translate-x-full transition-transform duration-300" id="drawerContent">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="/admin/new-reservation" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition bg-blue-700 font-semibold">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
                <a href="/admin/manage-reservations" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-calendar"></i> Manage Reservations
                </a>
                <a href="/admin/manage-sk" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-user-shield"></i> Manage SK Accounts
                </a>
                <a href="/admin/login-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-clock"></i> Login Logs
                </a>
                <a href="/admin/scanner" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-qrcode"></i> Scanner
                </a>
                <a href="/admin/activity-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-solid fa-list"></i> Activity Logs
                </a>
                <a href="/admin/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition">
                    <i class="fa-regular fa-user"></i> Profile
                </a>
            </nav>
        </div>
    </div>
</div>

    <div class="flex flex-1 flex-col lg:flex-row">

        <aside class="hidden lg:flex flex-col w-64 bg-blue-600 text-white shadow-xl rounded-tr-3xl rounded-br-3xl p-6">
            <h1 class="text-2xl font-bold mb-10">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'dashboard') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="/admin/new-reservation" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'new-reservation') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-plus"></i> New Reservation
                </a>
                <a href="/admin/manage-reservations" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'manage-reservations') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-calendar"></i> Manage Reservations
                </a>
                <a href="/admin/manage-sk" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'manage-sk') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-user-shield"></i> Manage SK Accounts
                </a>
                <a href="/admin/login-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'login-logs') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-clock"></i> Login Logs
                </a>
                <a href="/admin/scanner" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'scanner') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-qrcode"></i> Scanner
                </a>
                <a href="/admin/activity-logs" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'activity-logs') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-solid fa-list"></i> Activity Logs
                </a>
                <a href="/admin/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-500 transition <?= ($page == 'profile') ? 'bg-blue-700 font-semibold' : 'bg-blue-600' ?>">
                    <i class="fa-regular fa-user"></i> Profile
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-4 lg:p-6 overflow-auto">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div class="flex items-center gap-3">
                    <a href="/admin/manage-reservations" class="logout-btn" style="background-color: #6b7280; gap: 0.5rem;">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                    <h2 class="text-2xl font-semibold text-blue-900">New Reservation</h2>
                </div>
                <a href="/logout" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>

            <div class="form-container">
                <div class="success-message" id="successMessage">
                    <i class="fa-solid fa-check-circle"></i> Reservation created successfully!
                </div>

                <form id="reservationForm" method="POST" action="/admin/create-reservation">

                    <!-- Visitor Type Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fa-solid fa-user-check"></i> Visitor Information
                        </div>

                        <div class="form-group">
                            <label>Visitor Type <span style="color: #ef4444;">*</span></label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" id="resident" name="visitor_type" value="resident" required>
                                    <label for="resident" style="margin: 0; cursor: pointer;">Resident</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="visitor" name="visitor_type" value="visitor">
                                    <label for="visitor" style="margin: 0; cursor: pointer;">Visitor</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="userEmail">Email/User ID <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="userEmail" name="user_email" placeholder="Enter email or user ID" required>
                            </div>
                            <div class="form-group">
                                <label for="visitorName">Full Name <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="visitorName" name="visitor_name" placeholder="Enter full name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="visitorEmail">Contact Email</label>
                            <input type="email" id="visitorEmail" name="visitor_contact_email" placeholder="Enter contact email">
                        </div>
                    </div>

                    <!-- Reservation Details Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fa-solid fa-calendar-check"></i> Reservation Details
                        </div>

                        <div class="form-group">
                            <label for="resourceSelect">Resource <span style="color: #ef4444;">*</span></label>
                            <select id="resourceSelect" name="resource_id" class="w-full border rounded-lg p-2" required>
                                <option value="">Select Resource</option>
                                <?php foreach ($resources ?? [] as $resource): ?>
                                    <option value="<?= $resource['id'] ?>"><?= $resource['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="resDate">Reservation Date <span style="color: #ef4444;">*</span></label>
                                <input type="date" id="resDate" name="reservation_date" required>
                            </div>
                            <div class="form-group">
                                <label for="resType">Reservation Type <span style="color: #ef4444;">*</span></label>
                                <select id="resType" name="reservation_type" required>
                                    <option value="">Select Type</option>
                                    <option value="hourly">Hourly</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="startTime">Start Time <span style="color: #ef4444;">*</span></label>
                                <input type="time" id="startTime" name="start_time" required>
                            </div>
                            <div class="form-group">
                                <label for="endTime">End Time <span style="color: #ef4444;">*</span></label>
                                <input type="time" id="endTime" name="end_time" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="purpose">Purpose of Reservation</label>
                            <textarea id="purpose" name="purpose" rows="3" placeholder="Enter purpose of reservation"></textarea>
                        </div>
                    </div>

                    <!-- PC Selection Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fa-solid fa-desktop"></i> PC Assignment
                        </div>

                        <div class="form-group">
                            <label>Selected PCs <span style="color: #ef4444;">*</span></label>
                            <div class="pc-list" id="pcList">
                                <p class="text-gray-500 text-sm">No PCs selected yet</p>
                            </div>
                        </div>

                        <div class="form-group" id="pcDropdownGroup" style="display: none;">
                            <label for="pcDropdown">Select PC <span style="color: #ef4444;">*</span></label>
                            <select id="pcDropdown" class="pc-dropdown">
                                <option value="">Select a PC</option>
                            </select>
                        </div>

                        <div class="add-pc-section">
                            <div class="section-title" style="margin-bottom: 1rem;">
                                <i class="fa-solid fa-plus"></i> Add PC
                            </div>
                            <div class="add-pc-form">
                                <div>
                                    <label for="pcNumber" style="margin-bottom: 0.5rem;">PC Number</label>
                                    <input type="text" id="pcNumber" placeholder="e.g., PC-001" style="margin-bottom: 0;">
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="maintenanceCheck">
                                    <label for="maintenanceCheck" style="margin: 0; cursor: pointer;">Under Maintenance</label>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="addPC()">
                                    <i class="fa-solid fa-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-section" style="border-bottom: none;">
                        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                            <a href="/admin/manage-reservations" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fa-solid fa-redo"></i> Clear
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Create Reservation
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </main>
    </div>

    <nav class="fixed bottom-0 left-0 right-0 bg-blue-600 text-white shadow-xl lg:hidden z-50">
        <div class="flex overflow-x-auto gap-1 p-2">
            <a href="/admin/dashboard" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'dashboard') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Dashboard</span>
            </a>
            <a href="/admin/new-reservation" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'new-reservation') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-plus text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">New Reservation</span>
            </a>
            <a href="/admin/manage-reservations" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'manage-reservations') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-calendar text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Reservations</span>
            </a>
            <a href="/admin/manage-sk" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'manage-sk') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-user-shield text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Manage SK</span>
            </a>
            <a href="/admin/login-logs" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'login-logs') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-clock text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Login Logs</span>
            </a>
            <a href="/admin/scanner" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'scanner') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-qrcode text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Scanner</span>
            </a>
            <a href="/admin/activity-logs" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'activity-logs') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-solid fa-list text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Activity Logs</span>
            </a>
            <a href="/admin/profile" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 <?= ($page == 'profile') ? 'bg-blue-700 font-semibold' : 'hover:bg-blue-500' ?>">
                <i class="fa-regular fa-user text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Profile</span>
            </a>
            <a href="/logout" class="flex flex-col items-center justify-center py-2 rounded-lg transition flex-shrink-0 hover:bg-blue-500">
                <i class="fa-solid fa-sign-out-alt text-lg"></i>
                <span class="text-[11px] mt-1 text-center leading-tight">Logout</span>
            </a>
        </div>
    </nav>

    <script>
    let selectedPCs = [];

    // Handle resource selection
    document.getElementById('resourceSelect').addEventListener('change', function() {
        const selectedResourceId = this.value;
        const pcSection = document.getElementById('pcSection');

        if (selectedResourceId === '1') { // Computer resource
            pcSection.style.display = 'block';
        } else {
            pcSection.style.display = 'none';
        }
    });

        function addPC() {
            const pcNumber = document.getElementById('pcNumber').value.trim();
            const isMaintenance = document.getElementById('maintenanceCheck').checked;

            if (!pcNumber) {
                alert('Please enter a PC number');
                return;
            }

            // Check if PC already added
            if (selectedPCs.some(pc => pc.number === pcNumber)) {
                alert('This PC is already added');
                return;
            }

            selectedPCs.push({
                number: pcNumber,
                maintenance: isMaintenance
            });

            document.getElementById('pcNumber').value = '';
            document.getElementById('maintenanceCheck').checked = false;

            updatePCList();
        }

        function removePC(index) {
            selectedPCs.splice(index, 1);
            updatePCList();
        }

        function toggleMaintenance(index) {
            selectedPCs[index].maintenance = !selectedPCs[index].maintenance;
            updatePCList();
        }

        function updatePCList() {
            const pcList = document.getElementById('pcList');

            if (selectedPCs.length === 0) {
                pcList.innerHTML = '<p class="text-gray-500 text-sm">No PCs selected yet</p>';
                return;
            }

            pcList.innerHTML = selectedPCs.map((pc, index) => `
                <div class="pc-item">
                    <div class="pc-info">
                        <p><strong>PC Number:</strong> ${pc.number}</p>
                        <p><strong>Status:</strong> ${pc.maintenance ? '<span class="maintenance-badge"><i class="fa-solid fa-tools"></i> Under Maintenance</span>' : '<span style="color: #10b981;">Available</span>'}</p>
                    </div>
                    <div class="pc-actions">
                        <button type="button" class="btn btn-warning" onclick="toggleMaintenance(${index})">
                            <i class="fa-solid fa-tools"></i> ${pc.maintenance ? 'Available' : 'Maintenance'}
                        </button>
                        <button type="button" class="btn btn-danger" onclick="removePC(${index})">
                            <i class="fa-solid fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            `).join('');

            // Update hidden input for form submission
            document.getElementById('reservationForm').insertAdjacentHTML('beforeend', 
                '<input type="hidden" name="pcs" value="' + JSON.stringify(selectedPCs) + '" id="pcsInput">'
            );
            const oldInput = document.getElementById('reservationForm').querySelector('input[name="pcs"]:not(#pcsInput)');
            if (oldInput && oldInput.id !== 'pcsInput') {
                oldInput.remove();
            }
        }

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('resDate').min = today;

        // Form submission
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            if (selectedPCs.length === 0) {
                e.preventDefault();
                alert('Please add at least one PC to the reservation');
                return;
            }
        });

        // Allow adding PC by pressing Enter
        document.getElementById('pcNumber').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addPC();
            }
        });

        // Handle reservation type change
        document.getElementById('resType').addEventListener('change', function() {
            const selectedType = this.value;
            const pcDropdownGroup = document.getElementById('pcDropdownGroup');
            const addPcSection = document.querySelector('.add-pc-section');

            if (selectedType === 'computer') {
                pcDropdownGroup.style.display = 'block';
                addPcSection.style.display = 'none';
                populatePCDropdown();
                // Clear manual PCs when switching to computer
                selectedPCs = [];
                updatePCList();
            } else {
                pcDropdownGroup.style.display = 'none';
                addPcSection.style.display = 'block';
            }
        });

        // Populate PC dropdown
        function populatePCDropdown() {
            const dropdown = document.getElementById('pcDropdown');
            dropdown.innerHTML = '<option value="">Select a PC</option>';

            predefinedPCs.forEach(pc => {
                const option = document.createElement('option');
                option.value = pc.number;
                option.textContent = pc.number;
                if (pc.maintenance) {
                    option.disabled = true;
                    option.textContent += ' (Under Maintenance)';
                }
                dropdown.appendChild(option);
            });
        }

        // Handle PC dropdown selection
        document.getElementById('pcDropdown').addEventListener('change', function() {
            const selectedPC = this.value;
            if (!selectedPC) return;

            const pc = predefinedPCs.find(p => p.number === selectedPC);
            if (pc && !pc.maintenance) {
                // Check if PC already selected
                if (selectedPCs.some(p => p.number === selectedPC)) {
                    alert('This PC is already selected');
                    this.value = '';
                    return;
                }

                selectedPCs.push({
                    number: selectedPC,
                    maintenance: false
                });

                updatePCList();
                this.value = ''; // Reset dropdown
            }
        });
    </script>

</body>

</html>
