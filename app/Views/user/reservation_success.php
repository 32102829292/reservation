<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Submitted | E-Learning Resource Reservation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-y: auto;
        }

        /* Animated background shapes */
        .shape {
            position: fixed;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            top: -200px;
            left: -200px;
            animation: float 20s infinite ease-in-out;
        }

        .shape-2 {
            width: 600px;
            height: 600px;
            bottom: -300px;
            right: -300px;
            animation: float 25s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(40px, -40px) rotate(120deg); }
            66% { transform: translate(-30px, 30px) rotate(240deg); }
        }

        .success-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 600px;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #fbbf24, #d97706);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.5);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .success-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 1.2rem;
            padding: 1rem;
            position: relative;
            overflow: hidden;
            width: 100%;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: white;
            color: #4b5563;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 1.2rem;
            padding: 1rem;
            width: 100%;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-secondary:hover {
            border-color: #667eea;
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .detail-value {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .status-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 0.25rem 1rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-block;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 1.2rem;
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: center;
        }

        .info-box i {
            font-size: 2rem;
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }

        .info-box h4 {
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 0.5rem;
        }

        .info-box p {
            color: #3b82f6;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Animated Background Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <!-- Success Card -->
    <div class="success-card">
        <!-- Success Icon - Using amber color for pending -->
        <div class="success-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <i class="fa-regular fa-clock"></i>
        </div>

        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-black text-slate-900 mb-1">Reservation Submitted!</h1>
            <p class="text-slate-500">Your reservation is pending approval.</p>
        </div>

        <!-- Reservation Details -->
        <div class="bg-slate-50 rounded-2xl p-6 mb-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fa-regular fa-receipt text-amber-600"></i>
                Reservation Details
            </h3>
            
            <div class="space-y-2">
                <div class="detail-row">
                    <span class="detail-label">Reservation ID</span>
                    <span class="detail-value font-mono">#<?= $reservation['id'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Resource</span>
                    <span class="detail-value"><?= esc($reservation['resource_name'] ?? 'Resource') ?></span>
                </div>
                <?php if (!empty($reservation['pc_number'])): ?>
                <div class="detail-row">
                    <span class="detail-label">Workstation</span>
                    <span class="detail-value"><?= esc($reservation['pc_number']) ?></span>
                </div>
                <?php endif; ?>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value"><?= date('F j, Y', strtotime($reservation['reservation_date'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Time</span>
                    <span class="detail-value"><?= date('g:i A', strtotime($reservation['start_time'])) ?> - <?= date('g:i A', strtotime($reservation['end_time'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Purpose</span>
                    <span class="detail-value"><?= esc($reservation['purpose'] ?? '—') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-badge">PENDING</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Important Information Box -->
        <div class="info-box">
            <i class="fa-regular fa-hourglass-half"></i>
            <h4>Awaiting Approval</h4>
            <p>Your reservation is currently pending review by an SK officer or admin. You will receive your e-ticket once approved.</p>
            <p class="text-xs mt-3 text-blue-400">This usually takes 1-2 hours during office hours.</p>
        </div>

        <!-- What's Next Section -->
        <div class="bg-white rounded-2xl p-4 mb-6 border border-slate-100">
            <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                <i class="fa-regular fa-circle-check text-green-600"></i>
                What's Next?
            </h4>
            <ul class="space-y-2 text-sm text-slate-600">
                <li class="flex items-start gap-2">
                    <i class="fa-regular fa-bell text-amber-500 mt-1 text-xs"></i>
                    <span>You'll receive a notification once your reservation is approved</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-regular fa-envelope text-amber-500 mt-1 text-xs"></i>
                    <span>Check your email for updates and your e-ticket</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-regular fa-calendar-check text-amber-500 mt-1 text-xs"></i>
                    <span>You can view the status anytime in "My Reservations"</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6">
            <a href="<?= base_url('/reservation-list') ?>" class="btn-secondary">
                <i class="fa-regular fa-calendar"></i>
                My Reservations
            </a>
            <a href="<?= base_url('/dashboard') ?>" class="btn-primary">
                <i class="fa-regular fa-compass"></i>
                Go to Dashboard
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="flex justify-center gap-4 mt-6 text-xs text-slate-400">
            <a href="<?= base_url('/reservation') ?>" class="hover:text-green-600 transition flex items-center gap-1">
                <i class="fa-regular fa-plus"></i> New Reservation
            </a>
            <span>•</span>
            <a href="<?= base_url('/reservation-list') ?>" class="hover:text-green-600 transition flex items-center gap-1">
                <i class="fa-regular fa-clock"></i> Check Status
            </a>
        </div>
    </div>

    <script>
        // Auto-hide any flash messages after 5 seconds
        setTimeout(function() {
            const flashMsg = document.querySelector('.flash-message');
            if (flashMsg) {
                flashMsg.style.transition = 'opacity 0.5s ease';
                flashMsg.style.opacity = '0';
                setTimeout(() => flashMsg.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>