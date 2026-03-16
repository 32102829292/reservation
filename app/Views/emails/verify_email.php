<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email</title>
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f172a;padding:40px 20px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#1e293b;border-radius:16px;overflow:hidden;max-width:600px;width:100%;">
        
        <!-- Header -->
        <tr>
          <td style="background:linear-gradient(135deg,#2563eb,#1d4ed8);padding:40px;text-align:center;">
            <div style="font-size:48px;margin-bottom:12px;">📧</div>
            <h1 style="color:#ffffff;margin:0;font-size:26px;font-weight:700;">Verify Your Email</h1>
            <p style="color:#bfdbfe;margin:8px 0 0;font-size:15px;">E-Learning Resource Reservation System</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:40px;">
            <p style="color:#94a3b8;font-size:15px;margin:0 0 16px;">Hello, <strong style="color:#e2e8f0;"><?= esc($name) ?></strong>!</p>
            <p style="color:#94a3b8;font-size:15px;margin:0 0 24px;line-height:1.6;">
              Thank you for registering. Please click the button below to verify your email address and activate your account.
            </p>

            <!-- CTA Button -->
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" style="padding:8px 0 32px;">
                  <a href="<?= esc($verifyUrl) ?>" style="display:inline-block;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:16px;font-weight:600;letter-spacing:0.3px;">
                    ✅ Verify My Email
                  </a>
                </td>
              </tr>
            </table>

            <p style="color:#64748b;font-size:13px;margin:0 0 8px;">Or copy and paste this link into your browser:</p>
            <p style="background:#0f172a;border-radius:6px;padding:12px;word-break:break-all;margin:0 0 24px;">
              <a href="<?= esc($verifyUrl) ?>" style="color:#60a5fa;font-size:13px;text-decoration:none;"><?= esc($verifyUrl) ?></a>
            </p>

            <p style="color:#64748b;font-size:13px;margin:0;line-height:1.6;">
              If you did not create an account, you can safely ignore this email.
            </p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#0f172a;padding:24px;text-align:center;border-top:1px solid #334155;">
            <p style="color:#475569;font-size:12px;margin:0;">
              © <?= date('Y') ?> E-Learning Resource Reservation System — Brgy. F De Jesus, Unisan Quezon
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>