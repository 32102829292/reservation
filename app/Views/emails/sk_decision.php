<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SK Account Decision</title>
</head>
<body style="margin:0;padding:0;background:#f9f9f8;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f8;padding:48px 16px;">
  <tr>
    <td align="center">
      <table width="520" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;border:1px solid #e5e5e3;max-width:520px;width:100%;">

        <!-- Header -->
        <tr>
          <td style="padding:36px 40px 24px;text-align:center;">
            <img src="<?= base_url('images/logo.png') ?>" alt="SK Brgy. F. De Jesus" width="80" height="80"
              style="border-radius:50%;display:block;margin:0 auto 14px;box-shadow:0 4px 16px rgba(0,0,0,0.12),0 1px 4px rgba(0,0,0,0.08);object-fit:contain;background:#fff;">
            <p style="margin:0 0 2px;font-size:14px;font-weight:700;color:#1a1a1a;">Sangguniang Kabataan</p>
            <p style="margin:0;font-size:12px;color:#8e8e8e;">Brgy. F. De Jesus, Unisan, Quezon</p>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="padding:0 40px;"><div style="height:1px;background:#e5e5e3;"></div></td></tr>

        <!-- Status label -->
        <tr>
          <td style="padding:24px 40px 0;">
            <?php if ($decision === 'approved'): ?>
            <span style="display:inline-block;background:#dcfce7;color:#166534;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;padding:4px 10px;border-radius:4px;">Approved</span>
            <?php else: ?>
            <span style="display:inline-block;background:#fee2e2;color:#991b1b;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;padding:4px 10px;border-radius:4px;">Not Approved</span>
            <?php endif; ?>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:16px 40px 28px;">
            <?php if ($decision === 'approved'): ?>
            <h2 style="margin:0 0 16px;font-size:20px;font-weight:600;color:#1a1a1a;letter-spacing:-0.3px;">Your account has been approved</h2>
            <p style="margin:0 0 12px;font-size:15px;line-height:1.65;color:#3d3d3a;">Hi <?= esc($name) ?>,</p>
            <p style="margin:0 0 28px;font-size:15px;line-height:1.65;color:#3d3d3a;">
              The Barangay Chairman has approved your SK Officer account. You now have full access to the SK dashboard to manage reservations and user requests.
            </p>
            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="border-radius:8px;background:#1a1a1a;">
                  <a href="<?= esc($loginUrl) ?>" style="display:inline-block;padding:13px 28px;font-size:14px;font-weight:500;color:#ffffff;text-decoration:none;border-radius:8px;">
                    Log in to your account
                  </a>
                </td>
              </tr>
            </table>
            <?php else: ?>
            <h2 style="margin:0 0 16px;font-size:20px;font-weight:600;color:#1a1a1a;letter-spacing:-0.3px;">Application not approved</h2>
            <p style="margin:0 0 12px;font-size:15px;line-height:1.65;color:#3d3d3a;">Hi <?= esc($name) ?>,</p>
            <p style="margin:0 0 16px;font-size:15px;line-height:1.65;color:#3d3d3a;">
              After careful review, the Barangay Chairman has not approved your SK Officer account application at this time.
            </p>
            <p style="margin:0 0 28px;font-size:15px;line-height:1.65;color:#3d3d3a;">
              If you believe this is a mistake or require further information, please contact the Barangay F. De Jesus office directly in Unisan, Quezon.
            </p>
            <?php endif; ?>

            <p style="margin:0;font-size:13px;line-height:1.6;color:#8e8e8e;">This is an automated notification. Please do not reply directly to this email.</p>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="padding:0 40px;"><div style="height:1px;background:#e5e5e3;"></div></td></tr>

        <!-- Footer -->
        <tr>
          <td style="padding:20px 40px 28px;">
            <p style="margin:0;font-size:12px;color:#b5b3ad;">&copy; <?= date('Y') ?> E-Learning Resource Reservation System &middot; Brgy. F. De Jesus, Unisan, Quezon</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>