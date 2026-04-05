<?php $page = 'reservation'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <title>Reservation Submitted | SK Reserve</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#3730a3">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <?php include(APPPATH . 'Views/partials/head_meta.php'); ?>

    <script>
    (function () {
        try { if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark-pre'); } catch(e) {}
    })();
    </script>

    <style>
        /* ── Layout shell (sidebar left, main scrolls) ── */
        body { display: flex; min-height: 100vh; }
        html.dark-pre body { background: #060e1e; }

        /* ── Main area overrides for this centred layout ── */
        .main-area {
            display: flex !important;
            align-items: flex-start;
            justify-content: center;
            padding: 28px 28px 40px !important;
        }
        @media(max-width:639px) { .main-area { padding: 16px 14px 0 !important; } }

        /* ── Success card ── */
        .success-card {
            background: var(--card);
            border-radius: var(--r-xl);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 28px;
            width: 100%;
            max-width: 560px;
            animation: l-slide-up .4s ease;
        }

        /* ── Pending icon (pulsing) ── */
        .pending-icon {
            width: 72px; height: 72px;
            background: #fef9c3; border: 2px solid #fde047;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            animation: pulse-ring 2.5s infinite ease-in-out;
        }
        @keyframes pulse-ring {
            0%,100% { box-shadow: 0 0 0 0 rgba(234,179,8,.25); }
            50%      { box-shadow: 0 0 0 10px rgba(234,179,8,0); }
        }

        /* ── Detail rows ── */
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: .55rem 0; border-bottom: 1px solid var(--border-subtle); gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .12em; color: var(--text-sub); flex-shrink: 0; }
        .detail-value { font-weight: 600; color: var(--text); font-size: .84rem; text-align: right; }

        /* ── Buttons ── */
        .btn-primary { background: var(--indigo); color: white; border: none; padding: .8rem 1.5rem; border-radius: var(--r-md); font-weight: 700; font-size: .85rem; cursor: pointer; transition: all var(--ease); font-family: var(--font); display: inline-flex; align-items: center; justify-content: center; gap: 7px; text-decoration: none; box-shadow: 0 4px 12px rgba(55,48,163,.28); }
        .btn-primary:hover { background: #312e81; transform: translateY(-1px); }
        .btn-secondary { background: var(--card); color: var(--text-muted); border: 1px solid var(--border); padding: .8rem 1.5rem; border-radius: var(--r-md); font-weight: 700; font-size: .85rem; cursor: pointer; transition: all var(--ease); font-family: var(--font); display: inline-flex; align-items: center; justify-content: center; gap: 7px; text-decoration: none; box-shadow: var(--shadow-sm); }
        .btn-secondary:hover { border-color: var(--indigo-border); background: var(--indigo-light); color: var(--indigo); }

        /* ── Step items ── */
        .step-item { display: flex; align-items: flex-start; gap: 10px; padding: .55rem 0; border-bottom: 1px solid var(--border-subtle); }
        .step-item:last-child { border-bottom: none; }
        .step-num { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .68rem; font-weight: 800; }
    </style>
</head>
<body>

<?php
$page = 'reservation';
include(APPPATH . 'Views/partials/layout.php');
?>

<main class="main-area">
    <div class="success-card">
        <div class="pending-icon">
            <svg width="29" height="29" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="1.8">
                <path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 00-.586-1.414L12 12m5-10v4.172a2 2 0 01-.586 1.414L12 12m0 0L7.586 16.586A2 2 0 007 18v4m5-10L7.586 7.414A2 2 0 017 6V2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <div style="text-align:center;margin-bottom:24px;">
            <h1 style="font-size:1.4rem;font-weight:800;color:var(--text);letter-spacing:-.03em;margin-bottom:6px;">Reservation Submitted!</h1>
            <p style="font-size:.82rem;color:var(--text-sub);font-weight:500;">Your request is pending approval from an SK officer.</p>
        </div>

        <!-- Details box -->
        <div style="background:var(--input-bg);border-radius:var(--r-md);padding:16px;border:1px solid var(--border);margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                <div style="width:28px;height:28px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <span style="font-size:.78rem;font-weight:700;color:var(--text);">Reservation Details</span>
                <span style="margin-left:auto;font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:999px;">Pending</span>
            </div>
            <div class="detail-row"><span class="detail-label">Reservation ID</span><span class="detail-value" style="font-family:var(--mono);color:var(--text-sub);">#<?= $reservation['id'] ?></span></div>
            <div class="detail-row"><span class="detail-label">Resource</span><span class="detail-value"><?= esc($reservation['resource_name'] ?? 'Resource') ?></span></div>
            <?php if (!empty($reservation['pc_number'])): ?>
                <div class="detail-row"><span class="detail-label">Workstation</span><span class="detail-value"><?= esc($reservation['pc_number']) ?></span></div>
            <?php endif; ?>
            <div class="detail-row"><span class="detail-label">Date</span><span class="detail-value"><?= date('F j, Y', strtotime($reservation['reservation_date'])) ?></span></div>
            <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value"><?= date('g:i A', strtotime($reservation['start_time'])) ?> – <?= date('g:i A', strtotime($reservation['end_time'])) ?></span></div>
            <div class="detail-row"><span class="detail-label">Purpose</span><span class="detail-value"><?= esc($reservation['purpose'] ?? '—') ?></span></div>
        </div>

        <!-- What's next -->
        <div style="background:var(--card);border-radius:var(--r-md);padding:16px;border:1px solid var(--border);margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                <div style="width:28px;height:28px;background:var(--indigo-light);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="var(--indigo)" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <span style="font-size:.78rem;font-weight:700;color:var(--text);">What happens next?</span>
            </div>
            <div class="step-item">
                <div class="step-num" style="background:#fef3c7;color:#92400e;">1</div>
                <div>
                    <p style="font-weight:700;font-size:.82rem;color:var(--text);">Waiting for review</p>
                    <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;">An SK officer will review your request</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num" style="background:var(--input-bg);color:var(--text-sub);">2</div>
                <div>
                    <p style="font-weight:700;font-size:.82rem;color:var(--text-sub);">Approval notification</p>
                    <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;">You'll get a notification once approved</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num" style="background:var(--input-bg);color:var(--text-sub);">3</div>
                <div>
                    <p style="font-weight:700;font-size:.82rem;color:var(--text-sub);">E-ticket released</p>
                    <p style="font-size:.72rem;color:var(--text-sub);margin-top:2px;">Your QR e-ticket will be available after approval</p>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
            <a href="<?= base_url('/reservation-list') ?>" class="btn-secondary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                My Reservations
            </a>
            <a href="<?= base_url('/dashboard') ?>" class="btn-primary">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Dashboard
            </a>
        </div>

        <div style="display:flex;justify-content:center;gap:20px;padding-top:12px;border-top:1px solid var(--border-subtle);">
            <a href="<?= base_url('/reservation') ?>" style="font-size:.72rem;font-weight:700;color:var(--indigo);text-decoration:none;display:flex;align-items:center;gap:4px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg> New Reservation
            </a>
            <span style="color:var(--text-faint);">|</span>
            <a href="<?= base_url('/reservation-list') ?>" style="font-size:.72rem;font-weight:700;color:var(--indigo);text-decoration:none;display:flex;align-items:center;gap:4px;">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Check Status
            </a>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.documentElement.classList.remove('dark-pre');
});
</script>
</body>
</html>