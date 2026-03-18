<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Georgia',serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:48px 20px;">
  <tr>
    <td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;max-width:560px;width:100%;">

        <!-- Top accent bar -->
        <tr>
          <td style="background:#1e3a5f;height:4px;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

        <!-- Header -->
        <tr>
          <td style="padding:48px 48px 32px;border-bottom:1px solid #e2e8f0;">
            <p style="margin:0 0 4px;font-family:'Georgia',serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;color:#94a3b8;">E-Learning Resource Reservation System</p>
            <h1 style="margin:0;font-family:'Georgia',serif;font-size:28px;font-weight:normal;color:#0f172a;letter-spacing:-0.5px;">Email Verification</h1>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:40px 48px;">
            <p style="margin:0 0 8px;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;color:#94a3b8;font-family:'Georgia',serif;">Dear,</p>
            <p style="margin:0 0 28px;font-size:20px;color:#0f172a;font-family:'Georgia',serif;"><?= esc($name) ?></p>

            <p style="margin:0 0 28px;font-size:15px;line-height:1.8;color:#475569;font-family:'Georgia',serif;">
              Thank you for registering with the Brgy. F De Jesus E-Learning Resource Reservation System. To complete your registration and activate your account, please verify your email address by clicking the button below.
            </p>

            <!-- CTA -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 36px;">
              <tr>
                <td style="background:#1e3a5f;">
                  <a href="<?= esc($verifyUrl) ?>" style="display:inline-block;background:#1e3a5f;color:#ffffff;text-decoration:none;padding:14px 36px;font-family:'Georgia',serif;font-size:14px;letter-spacing:0.08em;text-transform:uppercase;">
                    Verify Email Address
                  </a>
                </td>
              </tr>
            </table>

            <!-- Divider -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="border-top:1px solid #e2e8f0;font-size:0;">&nbsp;</td>
              </tr>
            </table>

            <p style="margin:0 0 10px;font-size:12px;color:#94a3b8;font-family:'Georgia',serif;letter-spacing:0.05em;">Or copy this link into your browser:</p>
            <p style="margin:0 0 28px;font-size:12px;color:#1e3a5f;word-break:break-all;font-family:'Courier New',monospace;background:#f8fafc;padding:12px 14px;border-left:3px solid #1e3a5f;">
              <?= esc($verifyUrl) ?>
            </p>

            <p style="margin:0;font-size:13px;line-height:1.7;color:#94a3b8;font-family:'Georgia',serif;">
              If you did not initiate this registration, please disregard this message. No action is required on your part.
            </p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="padding:24px 48px;background:#f8fafc;border-top:1px solid #e2e8f0;">
            <p style="margin:0 0 4px;font-size:12px;color:#64748b;font-family:'Georgia',serif;">Brgy. F De Jesus, Unisan, Quezon</p>
            <p style="margin:0;font-size:11px;color:#94a3b8;font-family:'Georgia',serif;">&copy; <?= date('Y') ?> E-Learning Resource Reservation System. All rights reserved.</p>
          </td>
        </tr>

        <!-- Bottom accent bar -->
        <tr>
          <td style="background:#1e3a5f;height:4px;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>