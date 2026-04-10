<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email</title>
</head>
<body style="margin:0;padding:0;background:#060e1e;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#060e1e;padding:40px 16px;">
  <tr><td align="center">
    <table width="520" cellpadding="0" cellspacing="0" style="max-width:520px;width:100%;">

      <!-- Header brand bar -->
      <tr><td style="padding:0 0 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="vertical-align:middle;">
              <table cellpadding="0" cellspacing="0">
                <tr>
                  <td style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#c0392b,#3730a3);text-align:center;vertical-align:middle;font-weight:800;font-size:.75rem;color:#ffffff;border:2px solid rgba(212,160,23,.4);">
                    SK
                  </td>
                  <td style="padding-left:10px;vertical-align:middle;">
                    <p style="margin:0;font-size:.95rem;font-weight:800;color:#e2eaf8;letter-spacing:-.03em;">my<span style="color:#818cf8;">Space.</span></p>
                    <p style="margin:0;font-size:.58rem;font-weight:600;color:#4a6fa5;letter-spacing:.06em;text-transform:uppercase;">Community Portal · Brgy. F. De Jesus</p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td></tr>

      <!-- Card -->
      <tr><td style="background:#0b1628;border-radius:16px;border:1px solid rgba(99,102,241,.15);overflow:hidden;">

        <!-- Top stripe — indigo gradient matching brand -->
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:linear-gradient(90deg,#3730a3,#818cf8);height:3px;font-size:0;line-height:0;">&nbsp;</td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px 40px 32px;">

            <!-- Tag -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="background:rgba(55,48,163,.18);border:1px solid rgba(99,102,241,.28);border-radius:999px;padding:4px 14px;">
                <span style="font-size:10px;font-weight:700;color:#a5b4fc;letter-spacing:.1em;text-transform:uppercase;">&#9679; Email Verification</span>
              </td></tr>
            </table>

            <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#e2eaf8;letter-spacing:-.04em;line-height:1.25;">Verify your email address</h1>
            <p style="margin:0 0 28px;font-size:12px;color:#4a6fa5;font-weight:600;letter-spacing:.04em;">SANGGUNIANG KABATAAN &mdash; BRGY. F. DE JESUS, UNISAN, QUEZON</p>

            <!-- Divider -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="border-top:1px solid rgba(99,102,241,.12);font-size:0;">&nbsp;</td></tr>
            </table>

            <p style="margin:0 0 8px;font-size:14px;color:#7fb3e8;line-height:1.7;">Hi <strong style="color:#e2eaf8;"><?= esc($name) ?></strong>,</p>
            <p style="margin:0 0 28px;font-size:14px;color:#7fb3e8;line-height:1.7;">
              Thank you for registering. Click the button below to verify your email address and activate your mySpace account.
            </p>

            <!-- CTA Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr><td style="border-radius:10px;background:#3730a3;box-shadow:0 6px 24px rgba(55,48,163,.45);">
                <a href="<?= esc($verifyUrl) ?>" style="display:inline-block;padding:13px 28px;font-size:14px;font-weight:800;color:#ffffff;text-decoration:none;border-radius:10px;letter-spacing:-.01em;">Verify email address &rarr;</a>
              </td></tr>
            </table>

            <!-- Link fallback -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;border-radius:10px;border:1px solid rgba(99,102,241,.15);background:rgba(55,48,163,.08);overflow:hidden;">
              <tr><td style="padding:14px 16px;">
                <p style="margin:0 0 6px;font-size:10px;font-weight:700;color:#4a6fa5;letter-spacing:.1em;text-transform:uppercase;">Or copy this link</p>
                <p style="margin:0;font-family:'Courier New',Courier,monospace;font-size:11px;color:#818cf8;word-break:break-all;"><?= esc($verifyUrl) ?></p>
              </td></tr>
            </table>

            <!-- Expiry note -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;border-radius:10px;border:1px solid rgba(212,160,23,.15);background:rgba(212,160,23,.06);overflow:hidden;">
              <tr><td style="padding:12px 16px;">
                <table cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="font-size:14px;padding-right:10px;vertical-align:middle;color:#fbbf24;">&#9201;</td>
                    <td style="font-size:12px;color:#1e3a5f;vertical-align:middle;">This verification link will expire in <strong style="color:#d4a017;">24 hours</strong>.</td>
                  </tr>
                </table>
              </td></tr>
            </table>

            <p style="margin:0;font-size:12px;color:#1e3a5f;line-height:1.7;">If you did not create an account, you can safely ignore this email.</p>

          </td></tr>
        </table>

        <!-- Footer -->
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="border-top:1px solid rgba(99,102,241,.1);padding:16px 40px 20px;background:rgba(6,14,30,.5);">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="font-size:11px;color:#1e3a5f;font-weight:600;">
                  &copy; <?= date('Y') ?> <span style="color:#4a6fa5;">mySpace &middot; SK Brgy. F. De Jesus</span> &middot; Unisan, Quezon
                </td>
                <td align="right" style="font-size:10px;">
                  <span style="display:inline-block;padding:2px 8px;border-radius:999px;background:rgba(55,48,163,.15);border:1px solid rgba(99,102,241,.2);color:#4a6fa5;font-weight:700;letter-spacing:.06em;">&#128274; SECURE</span>
                </td>
              </tr>
            </table>
          </td></tr>
        </table>

      </td></tr>
    </table>
  </td></tr>
</table>
</body>
</html>