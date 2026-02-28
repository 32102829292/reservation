<?php
$conn = mysqli_connect("localhost", "root", "", "reservation_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$result = mysqli_query($conn, "SHOW TABLES");
echo "Tables in reservation_system:\n";
while ($row = mysqli_fetch_row($result)) {
    echo $row[0] . "\n";
}

mysqli_close($conn);
