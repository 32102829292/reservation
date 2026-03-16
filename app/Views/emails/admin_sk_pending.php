<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New SK Account Pending Approval</title>
</head>
<body style="margin:0;padding:0;background:#0f172a;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f172a;padding:40px 20px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#1e293b;border-radius:16px;overflow:hidden;max-width:600px;width:100%;">

        <!-- Header -->
        <tr>
          <td style="background:linear-gradient(135deg,#d97706,#b45309);padding:40px;text-align:center;">
            <div style="font-size:48px;margin-bottom:12px;">⏳</div>
            <h1 style="color:#ffffff;margin:0;font-size:26px;font-weight:700;">New SK Account Request</h1>
            <p style="color:#fde68a;margin:8px 0 0;font-size:15px;">Action Required — Barangay Chairman</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:40px;">
            <p style="color:#94a3b8;font-size:15px;margin:0 0 20px;line-height:1.6;">
              A new SK Officer account has been verified and is awaiting your approval:
            </p>

            <!-- SK Details Card -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f172a;border-radius:10px;margin-bottom:28px;">
              <tr>
                <td style="padding:24px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="padding:8px 0;border-bottom:1px solid #1e293b;">
                        <span style="color:#64748b;font-size:13px;">Name</span><br>
                        <span style="color:#e2e8f0;font-size:15px;font-weight:600;"><?= esc($skName) ?></span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:8px 0;border-bottom:1px solid #1e293b;">
                        <span style="color:#64748b;font-size:13px;">Email</span><br>
                        <span style="color:#60a5fa;font-size:15px;"><?= esc($skEmail) ?></span>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:8px 0;">
                        <span style="color:#64748b;font-size:13px;">Applied At</span><br>
                        <span style="color:#e2e8f0;font-size:15px;"><?= esc($appliedAt) ?></span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- CTA Button -->
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" style="padding:8px 0 28px;">
                  <a href="<?= esc($manageUrl) ?>" style="display:inline-block;background:linear-gradient(135deg,#d97706,#b45309);color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:16px;font-weight:600;">
                    👤 Review SK Account
                  </a>
                </td>
              </tr>
            </table>

            <p style="color:#64748b;font-size:13px;margin:0;line-height:1.6;">
              Log in to the admin panel to approve or reject this SK account application.
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