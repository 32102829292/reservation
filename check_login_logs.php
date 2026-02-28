<?php
require 'app/Config/Database.php';
$db = \Config\Database::connect();
$result = $db->query('DESCRIBE login_logs');
$fields = $result->getResultArray();
echo 'login_logs table structure:' . PHP_EOL;
foreach ($fields as $field) {
    echo $field['Field'] . ' - ' . $field['Type'] . ' - ' . ($field['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . ' - ' . $field['Default'] . PHP_EOL;
}
?>
