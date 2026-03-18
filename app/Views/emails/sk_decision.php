<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SK Account Decision</title>
</head>
<body style="margin:0;padding:0;background:#0f1117;font-family:Georgia,'Times New Roman',serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f1117;padding:48px 16px;">
  <tr>
    <td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;">

        <!-- Hero Header -->
        <tr>
          <td style="background:linear-gradient(145deg,#1a0a2e 0%,#16103a 40%,#0d1f4a 100%);border-radius:16px 16px 0 0;padding:0 48px 36px;text-align:center;border:1px solid #2a2060;border-bottom:none;">
            <div style="height:4px;background:linear-gradient(90deg,#c8102e 0%,#f5c518 50%,#1a3a8a 100%);border-radius:4px 4px 0 0;margin:0 -48px 36px;"></div>

            <img src="<?= base_url('images/logo.png') ?>" alt="SK Brgy. F. De Jesus" width="96" height="96"
              style="display:block;margin:0 auto 20px;border-radius:50%;box-shadow:0 0 0 4px rgba(245,197,24,0.3),0 0 0 8px rgba(245,197,24,0.1),0 8px 32px rgba(0,0,0,0.5);object-fit:contain;background:#fff;">

            <p style="margin:0 0 4px;font-size:18px;font-weight:700;color:#ffffff;letter-spacing:0.06em;font-family:Georgia,'Times New Roman',serif;">SANGGUNIANG KABATAAN</p>
            <p style="margin:0;font-size:11px;color:#9b9ec4;letter-spacing:0.14em;text-transform:uppercase;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Brgy. F. De Jesus &nbsp;&bull;&nbsp; Unisan, Quezon</p>
          </td>
        </tr>

        <!-- Gradient divider bar — changes by decision -->
        <?php if ($decision === 'approved'): ?>
        <tr><td style="background:linear-gradient(90deg,#16a34a,#4ade80,#16a34a);height:3px;border-left:1px solid #2a2060;border-right:1px solid #2a2060;"></td></tr>
        <?php else: ?>
        <tr><td style="background:linear-gradient(90deg,#c8102e,#f87171,#c8102e);height:3px;border-left:1px solid #2a2060;border-right:1px solid #2a2060;"></td></tr>
        <?php endif; ?>

        <!-- Body Card -->
        <tr>
          <td style="background:#ffffff;padding:44px 48px 36px;border:1px solid #e8e8e6;border-top:none;border-bottom:none;">

            <?php if ($decision === 'approved'): ?>

            <!-- Approved label chip -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr>
                <td style="background:#f0fdf4;border:1px solid #86efac;border-radius:20px;padding:5px 14px;">
                  <span style="font-size:11px;font-weight:600;color:#166534;letter-spacing:0.1em;text-transform:uppercase;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">&#10003;&nbsp; Approved</span>
                </td>
              </tr>
            </table>

            <h2 style="margin:0 0 14px;font-size:26px;font-weight:700;color:#0f1117;letter-spacing:-0.5px;line-height:1.25;font-family:Georgia,'Times New Roman',serif;">Your account has been approved</h2>

            <p style="margin:0 0 10px;font-size:15px;line-height:1.7;color:#374151;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Hi <strong style="color:#0f1117;"><?= esc($name) ?></strong>,</p>
            <p style="margin:0 0 28px;font-size:15px;line-height:1.7;color:#374151;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
              The Barangay Chairman has approved your SK Officer account. You now have full access to the SK dashboard to manage reservations and user requests.
            </p>

            <!-- Welcome banner -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 32px;">
              <tr>
                <td style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #86efac;border-left:4px solid #16a34a;border-radius:0 8px 8px 0;padding:16px 20px;">
                  <p style="margin:0;font-size:14px;color:#166534;line-height:1.6;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">&#127881; &nbsp;Welcome to the team! You can now log in and start using the SK system.</p>
                </td>
              </tr>
            </table>

            <!-- CTA Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="border-radius:10px;background:linear-gradient(135deg,#16a34a 0%,#15803d 100%);box-shadow:0 4px 16px rgba(22,163,74,0.35);">
                  <a href="<?= esc($loginUrl) ?>" style="display:inline-block;padding:15px 36px;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;letter-spacing:0.02em;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
                    Log in to your account &rarr;
                  </a>
                </td>
              </tr>
            </table>

            <?php else: ?>

            <!-- Not Approved label chip -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr>
                <td style="background:#fff1f2;border:1px solid #fca5a5;border-radius:20px;padding:5px 14px;">
                  <span style="font-size:11px;font-weight:600;color:#991b1b;letter-spacing:0.1em;text-transform:uppercase;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">&#10005;&nbsp; Not Approved</span>
                </td>
              </tr>
            </table>

            <h2 style="margin:0 0 14px;font-size:26px;font-weight:700;color:#0f1117;letter-spacing:-0.5px;line-height:1.25;font-family:Georgia,'Times New Roman',serif;">Application not approved</h2>

            <p style="margin:0 0 10px;font-size:15px;line-height:1.7;color:#374151;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Hi <strong style="color:#0f1117;"><?= esc($name) ?></strong>,</p>
            <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:#374151;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
              After careful review, the Barangay Chairman has not approved your SK Officer account application at this time.
            </p>

            <!-- Info box -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="background:#fef9f0;border:1px solid #fcd34d;border-left:4px solid #f5c518;border-radius:0 8px 8px 0;padding:16px 20px;">
                  <p style="margin:0 0 4px;font-size:13px;font-weight:600;color:#92400e;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Have questions?</p>
                  <p style="margin:0;font-size:13px;line-height:1.6;color:#78350f;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">If you believe this is a mistake or require further information, please contact the Barangay F. De Jesus office directly in Unisan, Quezon.</p>
                </td>
              </tr>
            </table>

            <?php endif; ?>

            <p style="margin:0;font-size:13px;line-height:1.6;color:#9ca3af;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">This is an automated notification. Please do not reply directly to this email.</p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#16103a;border-radius:0 0 16px 16px;padding:20px 48px 24px;border:1px solid #2a2060;border-top:none;">
            <p style="margin:0;font-size:12px;color:#6b6f9c;text-align:center;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">&copy; <?= date('Y') ?> E-Learning Resource Reservation System &nbsp;&bull;&nbsp; Brgy. F. De Jesus, Unisan, Quezon</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>