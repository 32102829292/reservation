<?php
$conn = mysqli_connect("localhost", "root", "", "reservation_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations");
$row = mysqli_fetch_assoc($result);
echo "Number of reservations: " . $row['count'] . "\n";

if ($row['count'] > 0) {
    echo "Sample reservations:\n";
    $result = mysqli_query($conn, "SELECT id, user_id, resource_id, reservation_date FROM reservations LIMIT 5");
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: {$row['id']}, User: {$row['user_id']}, Resource: {$row['resource_id']}, Date: {$row['reservation_date']}\n";
    }
}

mysqli_close($conn);
