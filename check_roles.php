<?php
require_once __DIR__ . '/vendor/autoload.php';

$db = \Config\Database::connect();
$users = $db->table('users')->select('id, name, role')->get()->getResultArray();

echo "Users:\n";
foreach($users as $user) {
  echo "ID: {$user['id']}, Name: {$user['name']}, Role: '{$user['role']}'\n";
}
