<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New SK Account Pending Approval</title>
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
            <p style="margin:0 0 2px;font-size:14px;font-weight:700;color:#1a1a1a;">Sangguniang Kabataan</p>
            <p style="margin:0;font-size:12px;color:#8e8e8e;">Brgy. F. De Jesus, Unisan, Quezon</p>
          </td>
        </tr>

        <!-- Divider -->
        <tr><td style="padding:0 40px;"><div style="height:1px;background:#e5e5e3;"></div></td></tr>

        <!-- Label -->
        <tr>
          <td style="padding:24px 40px 0;">
            <span style="display:inline-block;background:#fef3c7;color:#92400e;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;padding:4px 10px;border-radius:4px;">Action Required</span>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:16px 40px 28px;">
            <h2 style="margin:0 0 16px;font-size:20px;font-weight:600;color:#1a1a1a;letter-spacing:-0.3px;">New SK account request</h2>
            <p style="margin:0 0 24px;font-size:15px;line-height:1.65;color:#3d3d3a;">
              A new Sangguniang Kabataan Officer account has been verified and is awaiting your approval.
            </p>

            <!-- Details card -->
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f8;border-radius:8px;border:1px solid #e5e5e3;margin:0 0 28px;">
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #e5e5e3;">
                  <p style="margin:0 0 2px;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#8e8e8e;font-weight:500;">Full Name</p>
                  <p style="margin:0;font-size:15px;color:#1a1a1a;font-weight:500;"><?= esc($skName) ?></p>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #e5e5e3;">
                  <p style="margin:0 0 2px;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#8e8e8e;font-weight:500;">Email Address</p>
                  <p style="margin:0;font-size:14px;color:#1a1a1a;font-family:'SF Mono',Monaco,'Courier New',monospace;"><?= esc($skEmail) ?></p>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px;">
                  <p style="margin:0 0 2px;font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:#8e8e8e;font-weight:500;">Applied</p>
                  <p style="margin:0;font-size:14px;color:#1a1a1a;"><?= esc($appliedAt) ?></p>
                </td>
              </tr>
            </table>

            <!-- Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr>
                <td style="border-radius:8px;background:#1a1a1a;">
                  <a href="<?= esc($manageUrl) ?>" style="display:inline-block;padding:13px 28px;font-size:14px;font-weight:500;color:#ffffff;text-decoration:none;border-radius:8px;">
                    Review application
                  </a>
                </td>
              </tr>
            </table>

            <p style="margin:0;font-size:13px;line-height:1.6;color:#8e8e8e;">The applicant will be notified of your decision via email once you approve or reject their account.</p>
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