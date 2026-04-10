<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New SK Account Pending Approval</title>
</head>
<body style="margin:0;padding:0;background:#060e1e;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#060e1e;padding:40px 16px;">
  <tr><td align="center">
    <table width="520" cellpadding="0" cellspacing="0" style="max-width:520px;width:100%;">

      <!-- Header brand bar -->
      <tr><td style="padding:0 0 20px;">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="vertical-align:middle;padding-right:10px;">
              <!-- SK logo pill -->
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

        <!-- Amber top stripe -->
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="background:linear-gradient(90deg,#d97706,#f59e0b);height:3px;font-size:0;line-height:0;">&nbsp;</td></tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0">
          <tr><td style="padding:36px 40px 32px;">

            <!-- Tag -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="background:rgba(217,119,6,.15);border:1px solid rgba(217,119,6,.3);border-radius:999px;padding:4px 14px;">
                <span style="font-size:10px;font-weight:700;color:#fbbf24;letter-spacing:.1em;text-transform:uppercase;">&#9679; Action Required</span>
              </td></tr>
            </table>

            <h1 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#e2eaf8;letter-spacing:-.04em;line-height:1.25;">New SK account request</h1>
            <p style="margin:0 0 28px;font-size:12px;color:#4a6fa5;font-weight:600;letter-spacing:.04em;">SANGGUNIANG KABATAAN &mdash; BRGY. F. DE JESUS, UNISAN, QUEZON</p>

            <!-- Divider -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="border-top:1px solid rgba(99,102,241,.12);font-size:0;">&nbsp;</td></tr>
            </table>

            <p style="margin:0 0 24px;font-size:14px;color:#7fb3e8;line-height:1.7;">
              A new Sangguniang Kabataan Officer account has been verified and is awaiting your approval. Please review the details below.
            </p>

            <!-- Details card -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 28px;border-radius:10px;border:1px solid rgba(99,102,241,.15);overflow:hidden;">
              <tr><td style="padding:14px 18px;border-bottom:1px solid rgba(99,102,241,.1);background:rgba(55,48,163,.08);">
                <p style="margin:0 0 3px;font-size:10px;font-weight:700;color:#4a6fa5;letter-spacing:.1em;text-transform:uppercase;">Full Name</p>
                <p style="margin:0;font-size:15px;font-weight:700;color:#e2eaf8;"><?= esc($skName) ?></p>
              </td></tr>
              <tr><td style="padding:14px 18px;border-bottom:1px solid rgba(99,102,241,.1);">
                <p style="margin:0 0 3px;font-size:10px;font-weight:700;color:#4a6fa5;letter-spacing:.1em;text-transform:uppercase;">Email Address</p>
                <p style="margin:0;font-family:'Courier New',Courier,monospace;font-size:13px;color:#818cf8;"><?= esc($skEmail) ?></p>
              </td></tr>
              <tr><td style="padding:14px 18px;background:rgba(55,48,163,.08);">
                <p style="margin:0 0 3px;font-size:10px;font-weight:700;color:#4a6fa5;letter-spacing:.1em;text-transform:uppercase;">Date Applied</p>
                <p style="margin:0;font-size:14px;color:#c7d2fe;"><?= esc($appliedAt) ?></p>
              </td></tr>
            </table>

            <!-- CTA Button -->
            <table cellpadding="0" cellspacing="0" style="margin:0 0 24px;">
              <tr><td style="border-radius:10px;background:#3730a3;box-shadow:0 6px 24px rgba(55,48,163,.45);">
                <a href="<?= esc($manageUrl) ?>" style="display:inline-block;padding:13px 28px;font-size:14px;font-weight:800;color:#ffffff;text-decoration:none;border-radius:10px;letter-spacing:-.01em;">Review application &rarr;</a>
              </td></tr>
            </table>

            <p style="margin:0;font-size:12px;color:#1e3a5f;line-height:1.7;">The applicant will be notified of your decision via email once you approve or reject their account.</p>

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