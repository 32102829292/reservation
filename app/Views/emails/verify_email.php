<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email</title>
</head>
<body style="margin:0;padding:0;background:#f9f9f8;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f8;padding:40px 16px;">
  <tr><td align="center">
    <table width="480" cellpadding="0" cellspacing="0" style="max-width:480px;width:100%;">

      <!-- Card -->
      <tr><td style="background:#ffffff;border-radius:12px;border:1px solid #e5e5e3;overflow:hidden;">

        <!-- Top stripe -->
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:#1a1a1a;height:3px;font-size:0;line-height:0;">&nbsp;</td></tr>
        </table>

        <!-- Body -->
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px 40px 32px;">

            <!-- Tag -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="background:#f0f0ee;border-radius:6px;padding:4px 12px;">
                <span style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#6b6b6b;letter-spacing:0.06em;text-transform:uppercase;">Email Verification</span>
              </td></tr>
            </table>

            <h1 style="margin:0 0 8px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:22px;font-weight:700;color:#0f0f0e;letter-spacing:-0.4px;line-height:1.3;">Verify your email address</h1>
            <p style="margin:0 0 24px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:13px;color:#8e8e8e;line-height:1.5;">Sangguniang Kabataan &mdash; Brgy. F. De Jesus, Unisan, Quezon</p>

            <!-- Divider -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="border-top:1px solid #f0f0ee;font-size:0;">&nbsp;</td></tr>
            </table>

            <p style="margin:0 0 8px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;color:#3d3d3a;line-height:1.65;">Hi <strong style="color:#0f0f0e;"><?= esc($name) ?></strong>,</p>
            <p style="margin:0 0 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:15px;color:#3d3d3a;line-height:1.65;">
              Thank you for registering. Click the button below to verify your email address and activate your account.
            </p>

            <!-- Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 32px;">
              <tr><td style="border-radius:8px;background:#0f0f0e;">
                <a href="<?= esc($verifyUrl) ?>" style="display:inline-block;padding:13px 26px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:8px;letter-spacing:-0.1px;">Verify email address &rarr;</a>
              </td></tr>
            </table>

            <!-- Link fallback -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="background:#f9f9f8;border-radius:8px;border:1px solid #e5e5e3;padding:14px 16px;">
                <p style="margin:0 0 5px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:11px;font-weight:600;color:#8e8e8e;letter-spacing:0.06em;text-transform:uppercase;">Or copy this link</p>
                <p style="margin:0;font-family:'SF Mono',Monaco,'Courier New',monospace;font-size:12px;color:#374151;word-break:break-all;"><?= esc($verifyUrl) ?></p>
              </td></tr>
            </table>

            <p style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:13px;color:#b5b3ad;line-height:1.6;">If you did not create an account, you can safely ignore this email.</p>

          </td></tr>
        </table>

        <!-- Footer -->
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