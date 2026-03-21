<?php

namespace Config;

use Config\Services;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();
$routes->get('test', 'Test::index');
$routes->get('fix-admin', 'FixController::fixAdminRole');

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Auth routes
$routes->get('login', 'AuthController::login');
$routes->get('auth/login', 'AuthController::login');
$routes->get('login-action', 'AuthController::login');        // prevent 404 on Render wakeup
$routes->post('login-action', 'AuthController::loginAction');
$routes->post('auth/login-action', 'AuthController::loginAction');
$routes->get('register', 'AuthController::register');
$routes->get('auth/register', 'AuthController::register');
$routes->get('register-action', 'AuthController::register');  // prevent 404 on Render wakeup
$routes->post('register-action', 'AuthController::registerAction');
$routes->post('auth/register-action', 'AuthController::registerAction');
$routes->get('logout', 'AuthController::logout');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('verify-email/(:segment)', 'AuthController::verifyEmail/$1');
$routes->get('/', 'AuthController::redirectDashboard');

// Forgot Password routes
$routes->post('forgot-password/send-otp',       'ForgotPasswordController::sendOtp');
$routes->post('forgot-password/verify-otp',     'ForgotPasswordController::verifyOtp');
$routes->post('forgot-password/reset-password', 'ForgotPasswordController::resetPassword');

// RAG route
$routes->post('rag/suggest', 'RagController::suggest');

// Resident routes
$routes->group('', ['filter' => 'role:user'], function($routes) {
    $routes->get('dashboard', 'UserController::dashboard');
    $routes->get('reservation', 'UserController::reservation');
    $routes->post('reservation/create', 'UserController::createReservation');
    $routes->get('reservation-list', 'UserController::reservationList');
    $routes->post('reservation/cancel/(:num)', 'UserController::cancelReservation/$1');
    $routes->post('reservation/check-availability', 'UserController::checkAvailability');
    $routes->get('reservation/success/(:num)', 'UserController::reservationSuccess/$1');
    $routes->get('profile', 'UserController::profile');
    $routes->post('profile/update', 'UserController::updateProfile');
    $routes->get('e-ticket', 'UserController::eTicket');
    $routes->get('ticket', 'UserController::ticket');
    $routes->get('books', 'BookController::index');
    $routes->post('books/borrow/(:num)', 'BookController::borrow/$1');
    $routes->get('books/my-borrowings', 'BookController::myBorrowings');
});

// SK routes
$routes->group('sk', ['filter' => 'role:sk'], function($routes) {
    $routes->get('dashboard',            'SkController::dashboard');
    $routes->get('reservations',         'SkController::reservations');
    $routes->get('my-reservations',      'SkController::myReservations');
    $routes->get('new-reservation',      'SkController::newReservation');
    $routes->post('create-reservation',  'SkController::createReservation');
    $routes->post('approve',             'SkController::approve');
    $routes->post('decline',             'SkController::decline');
    $routes->post('reservations/approve','SkController::approve');
    $routes->post('reservations/decline','SkController::decline');
    $routes->post('validateETicket',     'SkController::validateETicket');
    $routes->post('validate-eticket',    'SkController::validateETicket');
    $routes->get('reservations/download','SkController::downloadCsv');
    $routes->get('activity-logs',        'SkController::activityLogs');
    $routes->get('profile',              'SkController::profile');
    $routes->post('profile/update',      'SkController::updateProfile');
    $routes->get('scanner',              'SkController::scanner');
    $routes->post('log-print',           'SkController::logPrint');

    // User requests
    $routes->get('user-requests',            'SkController::userRequests');
    $routes->post('check-new-user-requests', 'SkController::checkNewUserRequests');
    $routes->get('get-pending-count',        'SkController::getPendingCount');
    $routes->post('check-new-reservations',  'SkController::checkNewReservations');

    // Claimed reservations
    $routes->get('claimed-reservations', 'SkController::claimedReservations');
    $routes->get('export-claimed-excel', 'SkController::exportClaimedToExcel');

    // Books — SK manage
    $routes->get('books',                        'BookController::manage');
    $routes->get('books/create',                 'BookController::create');
    $routes->post('books/store',                 'BookController::store');
    $routes->post('books/extract-pdf',           'SkController::extractPdfWithAI');
    $routes->get('books/edit/(:num)',            'BookController::edit/$1');
    $routes->post('books/update/(:num)',         'BookController::update/$1');
    $routes->post('books/delete/(:num)',         'BookController::delete/$1');
    $routes->post('books/update-copies/(:num)', 'BookController::updateCopies/$1');  // ★ inline copies

    // Borrowings — SK manage
    $routes->get('borrowings',                'BookController::borrowings');
    $routes->post('borrowings/approve/(:num)','BookController::approveBorrowing/$1');
    $routes->post('borrowings/return/(:num)', 'BookController::returnBook/$1');
    $routes->post('borrowings/reject/(:num)', 'BookController::rejectBorrowing/$1');
    $routes->get('books/debug-pdf',           'SkController::debugPdf');
});

// Admin routes
$routes->group('admin', ['filter' => 'role:chairman'], function($routes) {
    $routes->get('dashboard',             'AdminController::dashboard');
    $routes->get('manage-reservations',   'AdminController::manageReservations');
    $routes->get('new-reservation',       'AdminController::newReservation');
    $routes->post('create-reservation',   'AdminController::createReservation');
    $routes->post('approve',              'AdminController::approve');
    $routes->post('decline',              'AdminController::decline');
    $routes->post('log-print',            'AdminController::logPrint');
    $routes->get('manage-sk',             'AdminController::manageSK');
    $routes->post('approve-sk',           'AdminController::approveSK');
    $routes->post('reject-sk',            'AdminController::rejectSK');
    $routes->get('scanner',               'AdminController::scanner');
    $routes->post('validateETicket',      'AdminController::validateETicket');
    $routes->post('validate-eticket',     'AdminController::validateETicket');
    $routes->get('login-logs',            'AdminController::loginLogs');
    $routes->get('activity-logs',         'AdminController::activityLogs');
    $routes->get('profile',               'AdminController::profile');
    $routes->post('profile/update',       'AdminController::profileUpdate');
    $routes->get('manage-pcs',            'AdminController::managePCs');
    $routes->post('add-pc',               'AdminController::addPC');
    $routes->post('update-pc-status',     'AdminController::updatePCStatus');
    $routes->get('delete-pc/(:num)',      'AdminController::deletePC/$1');
    $routes->get('fix-missing-claims',    'AdminController::fixMissingClaims');
    $routes->get('clean-empty-actions',   'AdminController::cleanEmptyActions');
    $routes->post('update-empty-actions', 'AdminController::updateEmptyActions');
    $routes->post('delete-empty-actions', 'AdminController::deleteEmptyActions');
    $routes->get('debug-actions',         'AdminController::debugActions');
    $routes->get('check-claim-log/(:num)','AdminController::checkClaimLog/$1');
    $routes->post('books/extract-pdf',    'AdminController::extractPdfWithAI');

    // Books — Admin manage
    $routes->get('books',                        'BookController::manage');
    $routes->get('books/create',                 'BookController::create');
    $routes->post('books/store',                 'BookController::store');
    $routes->get('books/edit/(:num)',            'BookController::edit/$1');
    $routes->post('books/update/(:num)',         'BookController::update/$1');
    $routes->post('books/delete/(:num)',         'BookController::delete/$1');
    $routes->post('books/update-copies/(:num)', 'BookController::updateCopies/$1');  // ★ inline copies

    // Borrowings — Admin manage
    $routes->get('borrowings',                'BookController::borrowings');
    $routes->post('borrowings/approve/(:num)','BookController::approveBorrowing/$1');
    $routes->post('borrowings/return/(:num)', 'BookController::returnBook/$1');
    $routes->post('borrowings/reject/(:num)', 'BookController::rejectBorrowing/$1');
});