<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SK Account Decision</title>
</head>
<body style="margin:0;padding:0;background:#f9f9f8;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f8;padding:40px 16px;">
  <tr><td align="center">
    <table width="480" cellpadding="0" cellspacing="0" style="max-width:480px;width:100%;">

      <tr><td style="background:#ffffff;border-radius:12px;border:1px solid #e5e5e3;overflow:hidden;">

        <!-- Top stripe: green or red -->
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:<?= $decision === 'approved' ? '#16a34a' : '#dc2626' ?>;height:3px;font-size:0;line-height:0;">&nbsp;</td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px 40px 32px;">

            <!-- Tag -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <?php if ($decision === 'approved'): ?>
              <tr><td style="background:#dcfce7;border-radius:6px;padding:4px 12px;">
                <span style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#166534;letter-spacing:0.06em;text-transform:uppercase;">Approved</span>
              </td></tr>
              <?php else: ?>
              <tr><td style="background:#fee2e2;border-radius:6px;padding:4px 12px;">
                <span style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#991b1b;letter-spacing:0.06em;text-transform:uppercase;">Not Approved</span>
              </td></tr>
              <?php endif; ?>
            </table>

            <?php if ($decision === 'approved'): ?>
            <h1 style="margin:0 0 8px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:22px;font-weight:700;color:#0f0f0e;letter-spacing:-0.4px;line-height:1.3;">Your account has been approved</h1>
            <?php else: ?>
            <h1 style="margin:0 0 8px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:22px;font-weight:700;color:#0f0f0e;letter-spacing:-0.4px;line-height:1.3;">Application not approved</h1>
            <?php endif; ?>

            <p style="margin:0 0 24px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:13px;color:#8e8e8e;">Sangguniang Kabataan &mdash; Brgy. F. De Jesus, Unisan, Quezon</p>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="border-top:1px solid #f0f0ee;font-size:0;">&nbsp;</td></tr>
            </table>

            <p style="margin:0 0 8px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;color:#3d3d3a;line-height:1.65;">Hi <strong style="color:#0f0f0e;"><?= esc($name) ?></strong>,</p>

            <?php if ($decision === 'approved'): ?>
            <p style="margin:0 0 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;color:#3d3d3a;line-height:1.65;">
              The Barangay Chairman has approved your SK Officer account. You now have full access to the SK portal to manage reservations and user requests.
            </p>
            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr><td style="border-radius:8px;background:#0f0f0e;">
                <a href="<?= esc($loginUrl) ?>" style="display:inline-block;padding:13px 26px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:8px;">Log in to your account &rarr;</a>
              </td></tr>
            </table>
            <?php else: ?>
            <p style="margin:0 0 16px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;color:#3d3d3a;line-height:1.65;">
              After careful review, the Barangay Chairman has not approved your SK Officer account application at this time.
            </p>
            <p style="margin:0 0 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;color:#3d3d3a;line-height:1.65;">
              For further information, please contact the Barangay F. De Jesus office directly in Unisan, Quezon.
            </p>
            <?php endif; ?>

            <p style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:13px;color:#b5b3ad;line-height:1.6;">This is an automated notification. Please do not reply directly to this email.</p>

          </td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="border-top:1px solid #f0f0ee;padding:16px 40px 20px;">
            <p style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:12px;color:#b5b3ad;">&copy; <?= date('Y') ?> E-Learning Resource Reservation System &middot; Brgy. F. De Jesus, Unisan, Quezon</p>
          </td></tr>
        </table>

      </td></tr>
    </table>
  </td></tr>
</table>
</body>
</html>