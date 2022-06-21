<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.s


$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/profile', 'Profile::index');
    $routes->get('/', 'Home::index', ['filter' => 'role_admin']);
    $routes->group('admin', ['filter' => 'role_admin'], function ($routes) {
        $routes->get('/', 'Home::index', );
        $routes->get('ticket/list', 'Ticket::index');
        $routes->get('users', 'User::index');
        $routes->get('users/list', 'User::userList');
        $routes->post('users/byId', 'User::getUserById');
        $routes->get('users/list/byStatus', 'User::getUserByStatus');
        $routes->post('users/new', 'User::insertUser');
        $routes->post('users/update', 'User::updateUser');
        $routes->post('users/delete', 'User::deleteUser');
        $routes->post('users/reset/password', 'User::resetPassword');
        $routes->get('users/count', 'User::countUsers');
        $routes->get('ticket/catagories', 'Ticket::catList');
        $routes->get('department/list', 'User::getDepartmentList');
        $routes->post('position/list', 'User::getPositionList');
    });

    $routes->group('user', ['filter' => 'role_user'], function ($routes) {
        $routes->get('home', 'UserTicket::index');
    });
});

 


// checked loggedin
$routes->group('', ['filter' => 'loggedin'], function ($routes) {
    $routes->get('/auth', 'Auth::index');
    $routes->post('/auth/login', 'Auth::login');
});



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
