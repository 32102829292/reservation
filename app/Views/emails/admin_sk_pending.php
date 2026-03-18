<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New SK Account Pending Approval</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Georgia',serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:48px 20px;">
  <tr>
    <td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;max-width:560px;width:100%;">

        <!-- Top accent bar -->
        <tr>
          <td style="background:#92400e;height:4px;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

        <!-- Header -->
        <tr>
          <td style="padding:48px 48px 32px;border-bottom:1px solid #e2e8f0;">
            <p style="margin:0 0 4px;font-family:'Georgia',serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;color:#94a3b8;">Action Required — Barangay Chairman</p>
            <h1 style="margin:0;font-family:'Georgia',serif;font-size:28px;font-weight:normal;color:#0f172a;letter-spacing:-0.5px;">New SK Account Request</h1>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:40px 48px;">
            <p style="margin:0 0 28px;font-size:15px;line-height:1.8;color:#475569;font-family:'Georgia',serif;">
              A new Sangguniang Kabataan Officer account has been verified and is awaiting your review and approval. The details of the applicant are as follows:
            </p>

            <!-- Details block -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 36px;border:1px solid #e2e8f0;">
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                  <p style="margin:0 0 3px;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#94a3b8;font-family:'Georgia',serif;">Full Name</p>
                  <p style="margin:0;font-size:16px;color:#0f172a;font-family:'Georgia',serif;"><?= esc($skName) ?></p>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #e2e8f0;">
                  <p style="margin:0 0 3px;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#94a3b8;font-family:'Georgia',serif;">Email Address</p>
                  <p style="margin:0;font-size:15px;color:#1e3a5f;font-family:'Courier New',monospace;"><?= esc($skEmail) ?></p>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;background:#f8fafc;">
                  <p style="margin:0 0 3px;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#94a3b8;font-family:'Georgia',serif;">Date Applied</p>
                  <p style="margin:0;font-size:15px;color:#0f172a;font-family:'Georgia',serif;"><?= esc($appliedAt) ?></p>
                </td>
              </tr>
            </table>

            <!-- CTA -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 36px;">
              <tr>
                <td style="background:#92400e;">
                  <a href="<?= esc($manageUrl) ?>" style="display:inline-block;background:#92400e;color:#ffffff;text-decoration:none;padding:14px 36px;font-family:'Georgia',serif;font-size:14px;letter-spacing:0.08em;text-transform:uppercase;">
                    Review Account Application
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

            <p style="margin:0;font-size:13px;line-height:1.7;color:#94a3b8;font-family:'Georgia',serif;">
              Please log in to the administration panel to approve or reject this application. The applicant will be notified of your decision via email.
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
          <td style="background:#92400e;height:4px;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>