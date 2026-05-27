<?php
// Direct database update script
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/vendor/autoload.php';

use Config\Database;

$db = Database::connect();
$builder = $db->table('users');
$builder->whereIn('id', [1, 4])->update(['role' => 'admin']);

// Also set email for the first user if needed
$db->table('users')->where('id', 1)->update(['email' => 'chairman@example.com']);

echo "Database updated successfully!\n\n";
echo "Updated users:\n";
$result = $db->table('users')->whereIn('id', [1, 4])->get();
foreach ($result->getResult() as $row) {
    echo "ID: {$row->id}, Name: {$row->name}, Email: {$row->email}, Role: {$row->role}\n";
}
?>
