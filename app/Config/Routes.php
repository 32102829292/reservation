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
$routes->post('login-action', 'AuthController::loginAction');
$routes->post('auth/login-action', 'AuthController::loginAction');
$routes->get('register', 'AuthController::register');
$routes->get('auth/register', 'AuthController::register');
$routes->post('register-action', 'AuthController::registerAction');
$routes->post('auth/register-action', 'AuthController::registerAction');
$routes->get('logout', 'AuthController::logout');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('/', 'AuthController::redirectDashboard');

// Resident routes
$routes->group('', ['filter' => 'role:resident'], function($routes){
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
});

// SK routes
$routes->group('sk', function($routes) {
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
    $routes->get('reservations/download','SkController::downloadCsv');
    $routes->get('activity-logs',        'SkController::activityLogs');
    $routes->get('profile',              'SkController::profile');
    $routes->post('profile/update',      'SkController::updateProfile');
    $routes->get('scanner',              'SkController::scanner');
    
    // Routes for user requests approval
    $routes->get('user-requests',           'SkController::userRequests');
    $routes->post('check-new-user-requests','SkController::checkNewUserRequests');
    $routes->get('get-pending-count',       'SkController::getPendingCount');
    
    // Routes for claimed reservations
    $routes->get('claimed-reservations',      'SkController::claimedReservations');
    $routes->get('export-claimed-excel',      'SkController::exportClaimedToExcel');
});

// Admin routes
$routes->group('admin', ['filter' => 'role:chairman'], function($routes){
    $routes->get('dashboard',            'AdminController::dashboard');
    $routes->get('manage-reservations',  'AdminController::manageReservations');
    $routes->get('new-reservation',      'AdminController::newReservation');
    $routes->post('create-reservation',  'AdminController::createReservation');
    $routes->post('approve',             'AdminController::approve');
    $routes->post('decline',             'AdminController::decline');
    $routes->get('manage-sk',            'AdminController::manageSK');
    $routes->post('approve-sk',          'AdminController::approveSK');
    $routes->post('reject-sk',           'AdminController::rejectSK');
    $routes->get('scanner',              'AdminController::scanner');
    $routes->get('login-logs',           'AdminController::loginLogs');
    $routes->get('activity-logs',        'AdminController::activityLogs');
    $routes->get('profile',              'AdminController::profile');
    $routes->post('profile/update',      'AdminController::profileUpdate');
    $routes->get('manage-pcs',           'AdminController::managePCs');
    $routes->post('add-pc',              'AdminController::addPC');
    $routes->post('update-pc-status',    'AdminController::updatePCStatus');
    $routes->get('delete-pc/(:num)',     'AdminController::deletePC/$1');
    $routes->get('fix-missing-claims',   'AdminController::fixMissingClaims');
    $routes->get('clean-empty-actions',  'AdminController::cleanEmptyActions');
    $routes->post('update-empty-actions', 'AdminController::updateEmptyActions');
    $routes->post('delete-empty-actions', 'AdminController::deleteEmptyActions');
    $routes->get('debug-actions',         'AdminController::debugActions');
    $routes->get('check-claim-log/(:num)','AdminController::checkClaimLog/$1');
});