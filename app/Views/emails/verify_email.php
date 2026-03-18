<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email</title>
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
            <p style="margin:0 0 2px;font-size:14px;font-weight:700;color:#1a1a1a;letter-spacing:-0.01em;">Sangguniang Kabataan</p>
            <p style="margin:0;font-size:12px;color:#8e8e8e;font-weight:400;">Brgy. F. De Jesus, Unisan, Quezon</p>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="padding:0 40px;"><div style="height:1px;background:#e5e5e3;"></div></td></tr>

        <!-- Body -->
        <tr>
          <td style="padding:32px 40px 28px;">
            <h2 style="margin:0 0 20px;font-size:20px;font-weight:600;color:#1a1a1a;letter-spacing:-0.3px;">Verify your email address</h2>
            <p style="margin:0 0 12px;font-size:15px;line-height:1.65;color:#3d3d3a;">Hi <?= esc($name) ?>,</p>
            <p style="margin:0 0 28px;font-size:15px;line-height:1.65;color:#3d3d3a;">
              Thank you for registering. Please verify your email address to activate your account.
            </p>

            <!-- Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="border-radius:8px;background:#1a1a1a;">
                  <a href="<?= esc($verifyUrl) ?>" style="display:inline-block;padding:13px 28px;font-size:14px;font-weight:500;color:#ffffff;text-decoration:none;border-radius:8px;">
                    Verify email address
                  </a>
                </td>
              </tr>
            </table>

            <p style="margin:0 0 8px;font-size:13px;color:#8e8e8e;">Or copy and paste this link into your browser:</p>
            <p style="margin:0 0 28px;font-size:12px;color:#6b7280;word-break:break-all;background:#f9f9f8;padding:10px 14px;border-radius:6px;font-family:'SF Mono',Monaco,'Courier New',monospace;"><?= esc($verifyUrl) ?></p>

            <p style="margin:0;font-size:13px;line-height:1.6;color:#8e8e8e;">If you did not create an account, you can safely ignore this email.</p>
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