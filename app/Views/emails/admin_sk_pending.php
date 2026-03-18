<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New SK Account Pending Approval</title>
</head>
<body style="margin:0;padding:0;background:#0f1117;font-family:Georgia,'Times New Roman',serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f1117;padding:48px 16px;">
  <tr>
    <td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;">

        <!-- Hero Header -->
        <tr>
          <td style="background:linear-gradient(145deg,#1a0a2e 0%,#16103a 40%,#0d1f4a 100%);border-radius:16px 16px 0 0;padding:0 48px 36px;text-align:center;border:1px solid #2a2060;border-bottom:none;">
            <div style="height:4px;background:linear-gradient(90deg,#c8102e 0%,#f5c518 50%,#1a3a8a 100%);border-radius:4px 4px 0 0;margin:0 -48px 36px;"></div>

            <img src="<?= base_url('images/logo.png') ?>" alt="SK Brgy. F. De Jesus" width="96" height="96"
              style="display:block;margin:0 auto 20px;border-radius:50%;box-shadow:0 0 0 4px rgba(245,197,24,0.3),0 0 0 8px rgba(245,197,24,0.1),0 8px 32px rgba(0,0,0,0.5);object-fit:contain;background:#fff;">

            <p style="margin:0 0 4px;font-size:18px;font-weight:700;color:#ffffff;letter-spacing:0.06em;font-family:Georgia,'Times New Roman',serif;">SANGGUNIANG KABATAAN</p>
            <p style="margin:0;font-size:11px;color:#9b9ec4;letter-spacing:0.14em;text-transform:uppercase;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Brgy. F. De Jesus &nbsp;&bull;&nbsp; Unisan, Quezon</p>
          </td>
        </tr>

        <!-- Gradient divider bar -->
        <tr><td style="background:linear-gradient(90deg,#c8102e,#f5c518,#1a3a8a);height:3px;border-left:1px solid #2a2060;border-right:1px solid #2a2060;"></td></tr>

        <!-- Body Card -->
        <tr>
          <td style="background:#ffffff;padding:44px 48px 36px;border:1px solid #e8e8e6;border-top:none;border-bottom:none;">

            <!-- Label chip -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr>
                <td style="background:#fffbeb;border:1px solid #fcd34d;border-radius:20px;padding:5px 14px;">
                  <span style="font-size:11px;font-weight:600;color:#92400e;letter-spacing:0.1em;text-transform:uppercase;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">&#9888;&nbsp; Action Required</span>
                </td>
              </tr>
            </table>

            <h2 style="margin:0 0 14px;font-size:26px;font-weight:700;color:#0f1117;letter-spacing:-0.5px;line-height:1.25;font-family:Georgia,'Times New Roman',serif;">New SK account request</h2>
            <p style="margin:0 0 28px;font-size:15px;line-height:1.7;color:#374151;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
              A new Sangguniang Kabataan Officer account has been verified and is awaiting your approval. Please review the applicant's details below.
            </p>

            <!-- Details card -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8f8f7;border-radius:10px;border:1px solid #e5e5e3;margin:0 0 32px;overflow:hidden;">
              <!-- Card header -->
              <tr>
                <td style="background:linear-gradient(135deg,#16103a,#1a3a8a);padding:12px 20px;">
                  <p style="margin:0;font-size:11px;font-weight:600;color:#c7d2fe;letter-spacing:0.12em;text-transform:uppercase;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Applicant Details</p>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #e5e5e3;">
                  <p style="margin:0 0 3px;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;font-weight:500;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Full Name</p>
                  <p style="margin:0;font-size:16px;color:#0f1117;font-weight:600;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;"><?= esc($skName) ?></p>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #e5e5e3;">
                  <p style="margin:0 0 3px;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;font-weight:500;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Email Address</p>
                  <p style="margin:0;font-size:14px;color:#1a3a8a;font-family:'SF Mono',Monaco,'Courier New',monospace;"><?= esc($skEmail) ?></p>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;">
                  <p style="margin:0 0 3px;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;font-weight:500;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">Applied</p>
                  <p style="margin:0;font-size:14px;color:#374151;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;"><?= esc($appliedAt) ?></p>
                </td>
              </tr>
            </table>

            <!-- CTA Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
              <tr>
                <td style="border-radius:10px;background:linear-gradient(135deg,#c8102e 0%,#9b0a22 100%);box-shadow:0 4px 16px rgba(200,16,46,0.35);">
                  <a href="<?= esc($manageUrl) ?>" style="display:inline-block;padding:15px 36px;font-size:14px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;letter-spacing:0.02em;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">
                    Review application &rarr;
                  </a>
                </td>
              </tr>
            </table>

            <p style="margin:0;font-size:13px;line-height:1.6;color:#9ca3af;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">The applicant will be notified of your decision via email once you approve or reject their account.</p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#16103a;border-radius:0 0 16px 16px;padding:20px 48px 24px;border:1px solid #2a2060;border-top:none;">
            <p style="margin:0;font-size:12px;color:#6b6f9c;text-align:center;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">&copy; <?= date('Y') ?> E-Learning Resource Reservation System &nbsp;&bull;&nbsp; Brgy. F. De Jesus, Unisan, Quezon</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>