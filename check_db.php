<?php
// Direct database query to check users table schema and data
$conn = mysqli_connect("localhost", "root", "", "reservation_system");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check table structure
echo "=== Users Table Structure ===\n";
$result = mysqli_query($conn, "DESCRIBE users");
while ($row = mysqli_fetch_assoc($result)) {
    print_r($row);
}

echo "\n=== Users Data (role column) ===\n";
$result = mysqli_query($conn, "SELECT id, name, email, role FROM users");
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: {$row['id']}, Name: {$row['name']}, Email: {$row['email']}, Role: '{$row['role']}'\n";
}

mysqli_close($conn);
