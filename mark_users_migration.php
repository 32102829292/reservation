<?php
$conn = mysqli_connect("localhost", "root", "", "reservation_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO migrations (version, class, group, namespace, time, batch) VALUES ('2026-01-15-000000', 'CreateUsers', 'default', 'App', UNIX_TIMESTAMP(), 1)";
if (mysqli_query($conn, $sql)) {
    echo "Users migration marked as completed.\n";
} else {
    echo "Error: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
