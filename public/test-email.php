<?php
// Place this file at: public/test-email.php
// Access it at: http://localhost:8080/test-email.php
// DELETE THIS FILE after testing!

// Boot CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../vendor/autoload.php';
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootWeb($paths);

// ── Test email ──────────────────────────────────────────────
$email = \Config\Services::email();

$email->setFrom('noreply@elearning.edu.ph', 'E-Learning System');
$email->setTo('izeiayiviesca@gmail.com'); // change to your email
$email->setSubject('✅ Test Email from E-Learning System');
$email->setMessage('<h2 style="color:#2563eb">It works!</h2><p>Your email config is working correctly.</p>');
$email->setMailType('html');

$result = $email->send();

echo '<pre style="font-family:monospace;font-size:14px;padding:20px">';
echo '<strong>Result:</strong> ' . ($result ? '✅ SUCCESS — check your inbox!' : '❌ FAILED') . "\n\n";
echo '<strong>Debug info:</strong>' . "\n";
echo $email->printDebugger(['headers', 'subject', 'body']);
echo '</pre>';