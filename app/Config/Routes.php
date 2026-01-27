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

// Public routes (no role filter)
$routes->get('login', 'AuthController::login');
$routes->post('login-action', 'AuthController::loginAction');
$routes->get('register', 'AuthController::register');
$routes->post('register-action', 'AuthController::registerAction');
$routes->get('logout', 'AuthController::logout');
$routes->get('/', 'AuthController::redirectDashboard');


// User routes - only for users with 'resident' role
$routes->group('', ['filter' => 'role:resident'], function($routes){
    $routes->get('dashboard','UserController::dashboard');
    $routes->get('reservation','Reservation::index');
    $routes->post('reservation/reserve','Reservation::reserve');
    $routes->get('reservation-list','Reservation::reservationList');
    $routes->get('e-ticket','UserController::eTicket');
    $routes->get('ticket','UserController::ticket');
    $routes->get('profile','UserController::profile');
    $routes->post('profile/update', 'UserController::updateProfile');
});


$routes->group('sk', function($routes) {
    $routes->get('dashboard', 'SkController::dashboard');
    $routes->get('reservations', 'SkController::reservations');
    $routes->get('new-reservation', 'SkController::newReservation');
    $routes->post('create-reservation', 'SkController::createReservation');
    $routes->get('profile', 'SkController::profile');
    $routes->get('scanner', 'SkController::scanner');

    $routes->post('reservations/approve', 'SkController::approve');
    $routes->post('reservations/decline', 'SkController::decline');

    $routes->get('reservations/download', 'SkController::downloadCsv');
});

$routes->group('admin', ['filter' => 'role:chairman'], function($routes){
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('manage-reservations', 'AdminController::manageReservations');
    $routes->get('manage-sk', 'AdminController::manageSK');
    $routes->get('set-reservation', 'AdminController::setReservation');
    $routes->get('new-reservation', 'AdminController::newReservation');
    $routes->post('create-reservation', 'AdminController::createReservation');
    $routes->post('approve-sk', 'AdminController::approveSK');
    $routes->post('reject-sk', 'AdminController::rejectSK');
    $routes->post('approve', 'AdminController::approve');
    $routes->post('decline', 'AdminController::decline');
    $routes->get('scanner', 'AdminController::scanner');
    $routes->get('login-logs', 'AdminController::loginLogs');
    $routes->get('activity-logs', 'AdminController::activityLogs');
    $routes->get('profile', 'AdminController::profile');
});
