<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New SK Account Pending Approval</title>
</head>
<body style="margin:0;padding:0;background:#f9f9f8;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f8;padding:40px 16px;">
  <tr><td align="center">
    <table width="480" cellpadding="0" cellspacing="0" style="max-width:480px;width:100%;">

      <tr><td style="background:#ffffff;border-radius:12px;border:1px solid #e5e5e3;overflow:hidden;">

        <!-- Amber top stripe -->
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:#d97706;height:3px;font-size:0;line-height:0;">&nbsp;</td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px 40px 32px;">

            <!-- Tag -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="background:#fef3c7;border-radius:6px;padding:4px 12px;">
                <span style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#92400e;letter-spacing:0.06em;text-transform:uppercase;">Action Required</span>
              </td></tr>
            </table>

            <h1 style="margin:0 0 8px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:22px;font-weight:700;color:#0f0f0e;letter-spacing:-0.4px;line-height:1.3;">New SK account request</h1>
            <p style="margin:0 0 24px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:13px;color:#8e8e8e;">Sangguniang Kabataan &mdash; Brgy. F. De Jesus, Unisan, Quezon</p>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="border-top:1px solid #f0f0ee;font-size:0;">&nbsp;</td></tr>
            </table>

            <p style="margin:0 0 24px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;color:#3d3d3a;line-height:1.65;">
              A new Sangguniang Kabataan Officer account has been verified and is awaiting your approval. Please review the details below.
            </p>

            <!-- Details -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;border-radius:8px;border:1px solid #e5e5e3;overflow:hidden;">
              <tr><td style="padding:14px 18px;border-bottom:1px solid #f0f0ee;background:#f9f9f8;">
                <p style="margin:0 0 3px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#8e8e8e;letter-spacing:0.06em;text-transform:uppercase;">Full Name</p>
                <p style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;font-weight:600;color:#0f0f0e;"><?= esc($skName) ?></p>
              </td></tr>
              <tr><td style="padding:14px 18px;border-bottom:1px solid #f0f0ee;">
                <p style="margin:0 0 3px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#8e8e8e;letter-spacing:0.06em;text-transform:uppercase;">Email Address</p>
                <p style="margin:0;font-family:'SF Mono',Monaco,'Courier New',monospace;font-size:14px;color:#0f0f0e;"><?= esc($skEmail) ?></p>
              </td></tr>
              <tr><td style="padding:14px 18px;background:#f9f9f8;">
                <p style="margin:0 0 3px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#8e8e8e;letter-spacing:0.06em;text-transform:uppercase;">Date Applied</p>
                <p style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:14px;color:#0f0f0e;"><?= esc($appliedAt) ?></p>
              </td></tr>
            </table>

            <!-- Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="border-radius:8px;background:#0f0f0e;">
                <a href="<?= esc($manageUrl) ?>" style="display:inline-block;padding:13px 26px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:8px;">Review application &rarr;</a>
              </td></tr>
            </table>

            <p style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:13px;color:#b5b3ad;line-height:1.6;">The applicant will be notified of your decision via email once you approve or reject their account.</p>

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