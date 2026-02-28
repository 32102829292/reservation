<?php
require 'app/Config/Database.php';
require 'app/Models/UserModel.php';
require 'app/Models/LoginLog.php';

$db = \Config\Database::connect();

// Check if admin user exists
$userModel = new \App\Models\UserModel();
$admin = $userModel->where('email', 'admin@example.com')->first();

if (!$admin) {
    echo "Admin user not found. Running seed to create admin.\n";
    // Run the seed
    $seeder = new \App\Database\Seeds\CleanAndCreateAdmin();
    $seeder->run();
    $admin = $userModel->where('email', 'admin@example.com')->first();
}

if ($admin) {
    echo "Admin user found: " . $admin['email'] . "\n";

    $loginLog = new \App\Models\LoginLog();

    // Simulate first login
    $insertId1 = $loginLog->insert([
        'user_id' => $admin['id'],
        'role' => $admin['role'],
        'login_time' => date('Y-m-d H:i:s')
    ]);
    echo "First login inserted with ID: $insertId1\n";

    // Simulate second login without logging out first
    $insertId2 = $loginLog->insert([
        'user_id' => $admin['id'],
        'role' => $admin['role'],
        'login_time' => date('Y-m-d H:i:s')
    ]);
    echo "Second login inserted with ID: $insertId2\n";

    // Simulate logout for the latest active session (should update ID $insertId2)
    $latestLog = $loginLog->where('user_id', $admin['id'])
        ->where('logout_time', null)
        ->orderBy('id', 'DESC')
        ->first();
    if ($latestLog) {
        echo "Latest active log: " . json_encode($latestLog) . "\n";
        $loginLog->update($latestLog['id'], ['logout_time' => date('Y-m-d H:i:s')]);
        echo "Logout updated for ID: " . $latestLog['id'] . "\n";
    }

    // Check all logs for this user
    $allLogs = $loginLog->where('user_id', $admin['id'])->findAll();
    echo "All logs for user:\n";
    foreach ($allLogs as $log) {
        echo json_encode($log) . "\n";
    }
} else {
    echo "Failed to create or find admin user.\n";
}
?>
