<?php

namespace Myth\Auth\Config;

use CodeIgniter\Router\RouteCollection;
use Myth\Auth\Config\Auth as AuthConfig;

/** @var RouteCollection $routes */

// Myth:Auth routes file.
$routes->group('/', static function ($routes) {
    // Load the reserved routes from Auth.php
    $config         = config(AuthConfig::class);
    $reservedRoutes = $config->reservedRoutes;

    // Installation
    $routes->get('info', 'Home::information');
    $routes->get('installation', 'Home::installation');
    $routes->post('installation', 'Home::attempinstallation');
    $routes->get('update-finance', 'Home::addfinance');
    $routes->get('update-admin', 'Home::updateadmin');

    // Developer Access Only
    $routes->get('trial', 'Home::trial');

    // Login/out
    $routes->get($reservedRoutes['login'], 'Auth::login', ['as' => 'login']);
    $routes->post($reservedRoutes['login'], 'Auth::attemptLogin');
    $routes->get($reservedRoutes['logout'], 'Auth::logout', ['as' => 'logout']);

    // Registration
    $routes->get($reservedRoutes['register'], 'Auth::register', ['as' => 'register']);
    $routes->post($reservedRoutes['register'], 'Auth::attemptRegister');

    // Activation
    $routes->get($reservedRoutes['activate-account'], 'Auth::activateAccount', ['as' => 'activate-account']);
    $routes->get($reservedRoutes['resend-activate-account'], 'Auth::resendActivateAccount', ['as' => 'resend-activate-account']);

    // Forgot/Resets
    $routes->get($reservedRoutes['forgot'], 'Auth::forgotPassword', ['as' => 'forgot']);
    $routes->post($reservedRoutes['forgot'], 'Auth::attemptForgot');
    $routes->get($reservedRoutes['reset-password'], 'Auth::resetPassword', ['as' => 'reset-password']);
    $routes->post($reservedRoutes['reset-password'], 'Auth::attemptReset');
});

// Upload Routes
$routes->group('upload', ['filter' => 'login'], function($routes) {
    $routes->post('designcreate', 'Upload::designcreate');
    $routes->post('removedesigncreate', 'Upload::removedesigncreate');
    $routes->post('spk', 'Upload::spk');
    $routes->post('savespk/(:num)', 'Upload::savespk/$1');
    $routes->post('removespk', 'Upload::removespk');
    $routes->post('mdl/(:num)', 'Upload::mdl/$1');
    $routes->post('layout', 'Upload::layout');
    $routes->post('removelayout', 'Upload::removelayout');
    $routes->post('photomdl', 'Upload::photomdl');
    $routes->post('removephotomdl', 'Upload::removephotomdl');
    $routes->post('sertrim/(:num)', 'Upload::sertrim/$1');
    $routes->post('removesertrim/(:num)', 'Upload::removesertrim/$1');
    $routes->post('bast/(:num)', 'Upload::bast/$1');
    $routes->post('removebast/(:num)', 'Upload::removebast/$1');
    $routes->post('buktipembayaran', 'Upload::buktipembayaran');
    $routes->post('removebuktipembayaran', 'Upload::removebuktipembayaran');
    $routes->post('buktipengiriman', 'Upload::buktipengiriman');
    $routes->post('removebuktipengiriman', 'Upload::removebuktipengiriman');
});

$routes->group('clientreg', static function ($routes) {
    $routes->get('', 'ClientRegister::index');
    $routes->post('', 'ClientRegister::submit');
});

$routes->group('/',['filter' => 'login'], function ($routes) {
    $routes->get('', 'Home::index');
    $routes->get('dashboard/(:num)', 'Home::clientdashboard/$1');
    $routes->get('logedin', 'Home::logedin');
});


// Revisi & Acc Design
$routes->group('home', ['filter' => 'login'], function($routes) {
    $routes->post('revisi', 'Home::revisi');
    $routes->post('removerevisi', 'Home::removerevisi');
    $routes->post('saverevisi/(:num)', 'Home::saverevisi/$1');
    $routes->post('acc/(:num)', 'Home::acc/$1');
    // $routes->get('accres/(:num)', 'Home::accres/$1');
    $routes->post('buktipembayaran/(:num)', 'Home::buktipembayaran/$1');
});

// Users
$routes->group('users',['filter' => 'login'], function ($routes){
    $routes->get('','User::index');
    // Users
    $routes->post('create','User::create');
    $routes->post('update/(:num)','User::update/$1');
    $routes->post('delete/(:num)','User::delete/$1');
    $routes->get('delete/(:num)','User::delete/$1');
    // Access Control
    $routes->get('access-control','User::accesscontrol');
    $routes->post('create/access-control','User::createaccess');
    $routes->post('update/access/(:num)','User::updateaccess/$1');
    $routes->get('delete/access-control/(:num)','User::deleteaccess/$1');
    // Log
    $routes->get('log','User::log');

    // Client
    // $routes->get('client','User::client');
    // $routes->get('deleteclient/(:num)','User::deleteclient/$1');
});

// Client
$routes->group('client',['filter' => 'login'], function ($routes){
    $routes->get('','Client::index');
    $routes->post('create','Client::create');
    $routes->post('update/(:num)','Client::update/$1');
    $routes->get('delete/(:num)','Client::delete/$1');
});

// Project
$routes->group('project',['filter' => 'login'], function ($routes) {
    $routes->get('', 'Project::index');
    $routes->post('mdl', 'Project::mdl');
    $routes->post('create', 'Project::create');
    $routes->post('update/(:num)', 'Project::update/$1');
    $routes->get('delete/(:num)', 'Project::delete/$1');
    $routes->get('sphprint/(:num)', 'Project::sphprint/$1');
    $routes->get('sphview/(:num)', 'Project::sphview/$1');
    $routes->get('invoice/(:num)', 'Project::invoice/$1');
    $routes->get('invoiceexcel/(:num)', 'Project::invoiceexcel/$1');
    $routes->get('invoiceview/(:num)', 'Project::invoiceview/$1');
    $routes->post('removesertrim/(:num)', 'Project::removesertrim/$1');
    // $routes->post('inv4/(:num)', 'Project::inv4/$1');

    // Project Temp Routes
    // $routes->get('', 'ProjectTemp::index');
    // $routes->post('create', 'ProjectTemp::create');
    // $routes->post('update/(:num)', 'ProjectTemp::update/$1');
    // $routes->post('delete/(:num)', 'ProjectTemp::delete/$1');
    // $routes->get('delete/(:num)', 'ProjectTemp::delete/$1');

});

// rab
$routes->group('rab',['filter' => 'login'], function ($routes) {
    $routes->get('', 'Rab::index');
    $routes->post('create', 'Rab::create');
    $routes->post('update/(:num)', 'Rab::update/$1');
    $routes->post('delete/(:num)', 'Rab::delete/$1');
    $routes->get('delete/(:num)', 'Rab::delete/$1');
});

// Paket
$routes->group('paket',['filter' => 'login'], function ($routes) {
    $routes->post('create', 'Mdl::create');
    $routes->post('update/(:num)', 'Mdl::update/$1');
    $routes->get('delete/(:num)', 'Mdl::delete/$1');
    $routes->get('deleteallmdl/(:num)', 'Mdl::deleteallmdl/$1');
});

// MDL
$routes->group('mdl',['filter' => 'login'], function ($routes) {
    $routes->get('', 'Mdl::index');
    $routes->post('create/(:num)', 'Mdl::createmdl/$1');
    $routes->post('update/(:num)', 'Mdl::updatemdl/$1');
    $routes->post('delete/(:num)', 'Mdl::deletemdl/$1');
    $routes->get('deleteuncate/(:num)', 'Mdl::deletemdluncategories/$1');
    $routes->get('datapaket', 'Mdl::datapaket');
    $routes->post('submitcat', 'Mdl::submitcat');
    $routes->get('orderingpaket', 'Mdl::orderingpaket');
    $routes->post('reorderingparent', 'Mdl::reorderingparent');
    $routes->post('reorderingpaket', 'Mdl::reorderingpaket');
    $routes->post('reorderingmdl', 'Mdl::reorderingmdl');
});

// Account
$routes->group('account',['filter' => 'login'], function ($routes) {
    $routes->get('', 'Account::index');
    $routes->post('update', 'Account::updateaccount');
});

// General Setting
$routes->group('setting',['filter' => 'login'], function ($routes) {
    $routes->get('', 'Setting::index');
    $routes->post('gconfig', 'Setting::gconfig');
    $routes->post('createreferensi', 'Setting::createreferensi');
    $routes->post('updatereferensi/(:num)', 'Setting::updatereferensi/$1');
    $routes->get('deletereferensi/(:num)', 'Setting::deletereferensi/$1');
});


// Log
// $routes->group('log',['filter' => 'login'], function ($routes) {
//     $routes->get('', 'Log::index');
    // $routes->post('create', 'Bar::create');
    // $routes->get('update/(:num)', 'Bar::index');
    // $routes->post('update/(:num)', 'Bar::update/$1');
// });
