<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SK Account Decision</title>
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f172a;padding:40px 20px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#1e293b;border-radius:16px;overflow:hidden;max-width:600px;width:100%;">

        <!-- Header — green if approved, red if rejected -->
        <?php if ($decision === 'approved'): ?>
        <tr>
          <td style="background:linear-gradient(135deg,#16a34a,#15803d);padding:40px;text-align:center;">
            <div style="font-size:48px;margin-bottom:12px;">🎉</div>
            <h1 style="color:#ffffff;margin:0;font-size:26px;font-weight:700;">Account Approved!</h1>
            <p style="color:#bbf7d0;margin:8px 0 0;font-size:15px;">Your SK Officer account is now active</p>
          </td>
        </tr>
        <?php else: ?>
        <tr>
          <td style="background:linear-gradient(135deg,#dc2626,#b91c1c);padding:40px;text-align:center;">
            <div style="font-size:48px;margin-bottom:12px;">❌</div>
            <h1 style="color:#ffffff;margin:0;font-size:26px;font-weight:700;">Application Not Approved</h1>
            <p style="color:#fecaca;margin:8px 0 0;font-size:15px;">Your SK Officer account application</p>
          </td>
        </tr>
        <?php endif; ?>

        <!-- Body -->
        <tr>
          <td style="padding:40px;">
            <p style="color:#94a3b8;font-size:15px;margin:0 0 16px;">Hello, <strong style="color:#e2e8f0;"><?= esc($name) ?></strong>!</p>

            <?php if ($decision === 'approved'): ?>
            <p style="color:#94a3b8;font-size:15px;margin:0 0 24px;line-height:1.6;">
              Great news! The Barangay Chairman has <strong style="color:#4ade80;">approved</strong> your SK Officer account. 
              You can now log in and access the SK dashboard to manage reservations.
            </p>
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" style="padding:8px 0 28px;">
                  <a href="<?= esc($loginUrl) ?>" style="display:inline-block;background:linear-gradient(135deg,#16a34a,#15803d);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:16px;font-weight:600;">
                    🚀 Log In Now
                  </a>
                </td>
              </tr>
            </table>
            <?php else: ?>
            <p style="color:#94a3b8;font-size:15px;margin:0 0 24px;line-height:1.6;">
              We regret to inform you that the Barangay Chairman has <strong style="color:#f87171;">not approved</strong> your SK Officer account application at this time.
            </p>
            <p style="color:#94a3b8;font-size:15px;margin:0 0 24px;line-height:1.6;">
              If you believe this is a mistake or would like more information, please contact the Barangay office directly.
            </p>
            <?php endif; ?>

            <p style="color:#64748b;font-size:13px;margin:0;line-height:1.6;">
              If you have any questions, please contact the Barangay F De Jesus office in Unisan, Quezon.
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