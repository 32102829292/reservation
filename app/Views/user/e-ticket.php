<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>My Approved E-Tickets</h2>
    <a href="/dashboard" class="btn btn-secondary mb-3">Back</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Reservation</th>
                <th>Date</th>
                <th>Time</th>
                <th>E-Ticket</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($reservations as $res): ?>
            <tr>
                <td><?= $res['resource_id'] ?></td>
                <td><?= $res['reservation_date'] ?></td>
                <td><?= $res['start_time'] ?> - <?= $res['end_time'] ?></td>
                <td>
                    <?= $res['e_ticket_code'] ?>
                    <br>
                    <?php if(isset($res['qr_base64'])): ?>
                        <img src="<?= $res['qr_base64'] ?>" style="width:150px;height:150px;">
                    <?php endif; ?>
                    <br>
                    <a href="/user/downloadTicket/<?= $res['id'] ?>" class="btn btn-primary btn-sm mt-2">Download QR</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
