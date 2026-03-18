<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SK Account Decision</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Georgia',serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:48px 20px;">
  <tr>
    <td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;max-width:560px;width:100%;">

        <!-- Top accent bar -->
        <tr>
          <td style="background:<?= $decision === 'approved' ? '#166534' : '#991b1b' ?>;height:4px;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

        <!-- Header -->
        <tr>
          <td style="padding:48px 48px 32px;border-bottom:1px solid #e2e8f0;">
            <p style="margin:0 0 4px;font-family:'Georgia',serif;font-size:11px;letter-spacing:0.15em;text-transform:uppercase;color:#94a3b8;">
              <?= $decision === 'approved' ? 'Account Approved' : 'Application Update' ?>
            </p>
            <h1 style="margin:0;font-family:'Georgia',serif;font-size:28px;font-weight:normal;color:#0f172a;letter-spacing:-0.5px;">
              <?= $decision === 'approved' ? 'Your Account is Active' : 'Application Not Approved' ?>
            </h1>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:40px 48px;">
            <p style="margin:0 0 8px;font-size:13px;text-transform:uppercase;letter-spacing:0.1em;color:#94a3b8;font-family:'Georgia',serif;">Dear,</p>
            <p style="margin:0 0 28px;font-size:20px;color:#0f172a;font-family:'Georgia',serif;"><?= esc($name) ?></p>

            <?php if ($decision === 'approved'): ?>
            <p style="margin:0 0 20px;font-size:15px;line-height:1.8;color:#475569;font-family:'Georgia',serif;">
              We are pleased to inform you that the Barangay Chairman has reviewed and approved your Sangguniang Kabataan Officer account application.
            </p>
            <p style="margin:0 0 32px;font-size:15px;line-height:1.8;color:#475569;font-family:'Georgia',serif;">
              You now have full access to the SK dashboard where you can manage reservations, review user requests, and perform other SK Officer functions.
            </p>

            <!-- Status badge -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 32px;">
              <tr>
                <td style="background:#f0fdf4;border:1px solid #86efac;padding:12px 20px;">
                  <p style="margin:0;font-size:13px;color:#166534;font-family:'Georgia',serif;letter-spacing:0.05em;">
                    Account Status: <strong>Active</strong>
                  </p>
                </td>
              </tr>
            </table>

            <!-- CTA -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 36px;">
              <tr>
                <td style="background:#166534;">
                  <a href="<?= esc($loginUrl) ?>" style="display:inline-block;background:#166534;color:#ffffff;text-decoration:none;padding:14px 36px;font-family:'Georgia',serif;font-size:14px;letter-spacing:0.08em;text-transform:uppercase;">
                    Access Your Account
                  </a>
                </td>
              </tr>
            </table>

            <?php else: ?>
            <p style="margin:0 0 20px;font-size:15px;line-height:1.8;color:#475569;font-family:'Georgia',serif;">
              After careful review, the Barangay Chairman has determined that your SK Officer account application cannot be approved at this time.
            </p>

            <!-- Status badge -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="background:#fef2f2;border:1px solid #fca5a5;padding:12px 20px;">
                  <p style="margin:0;font-size:13px;color:#991b1b;font-family:'Georgia',serif;letter-spacing:0.05em;">
                    Account Status: <strong>Not Approved</strong>
                  </p>
                </td>
              </tr>
            </table>

            <p style="margin:0 0 28px;font-size:15px;line-height:1.8;color:#475569;font-family:'Georgia',serif;">
              If you would like to appeal this decision or require further clarification, please contact the Barangay F De Jesus office directly in Unisan, Quezon.
            </p>
            <?php endif; ?>

            <!-- Divider -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="border-top:1px solid #e2e8f0;font-size:0;">&nbsp;</td>
              </tr>
            </table>

            <p style="margin:0;font-size:13px;line-height:1.7;color:#94a3b8;font-family:'Georgia',serif;">
              This is an automated notification from the E-Learning Resource Reservation System. Please do not reply directly to this email.
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
          <td style="background:<?= $decision === 'approved' ? '#166534' : '#991b1b' ?>;height:4px;font-size:0;line-height:0;">&nbsp;</td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>