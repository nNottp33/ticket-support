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
    // profile page
    $routes->get('/profile', 'Profile::index');
    $routes->post('/profile/send/otp', 'Profile::sendEmailCode');
    $routes->post('/profile/change/password', 'Profile::changePassword');
    $routes->get('/profile/show', 'Profile::getProfile');
    $routes->post('/profile/update', 'Profile::updateProfile');
    
    // dashboard
    $routes->get('/', 'Home::index', ['filter' => 'role_admin']);

    // role_admin
    $routes->group('admin', ['filter' => 'role_admin'], function ($routes) {
        // other
        $routes->get('get/list', 'Catagory::getAdminList');

        // dashboard
        $routes->get('/', 'Home::index');
        $routes->get('dashboard/ticket/often/list', 'Home::getTicketOften');

        // ticket page
        $routes->get('ticket/list', 'Ticket::index');
        $routes->get('ticket/show/list', 'Ticket::getTicketAdmin');
        $routes->get('ticket/count', 'Ticket::countTicket');
        $routes->post('ticket/user/detail', 'Ticket::getUserByEmail');
        $routes->post('ticket/update/status', 'Ticket::updateTicket');
        $routes->get('ticket/owner/change/get', 'Ticket::getTicketOwner');
        $routes->post('ticket/reject/change', 'Ticket::changeTicket');
        $routes->get('ticket/show/list/by/status', 'Ticket::getTicketAdminByStatus');
        $routes->post('ticket/more/detail', 'Ticket::getMoreTicketDetail');
        
        // user page
        $routes->get('users', 'User::index');
        $routes->get('users/list', 'User::userList');
        $routes->post('users/byId', 'User::getUserById');
        $routes->get('users/list/byStatus', 'User::getUserByStatus');
        $routes->post('users/new', 'User::insertUser');
        $routes->post('users/update', 'User::updateUser');
        $routes->post('users/delete', 'User::deleteUser');
        $routes->post('users/reset/password', 'User::resetPassword');
        $routes->get('users/count', 'User::countUsers');
        $routes->post('users/update/status', 'User::updateStatusUser');
        $routes->get('department/list', 'User::getDepartmentList');
        $routes->post('position/list', 'User::getPositionList');

        // catagory page
        $routes->get('catagories', 'Catagory::index');
        $routes->get('catagories/list', 'Catagory::getCategories');
        $routes->get('catagories/owner', 'Catagory::getCatagoryOwner');
        $routes->post('catagories/owner/save', 'Catagory::saveCatagoryOwner');
        $routes->post('catagories/owner/delete', 'Catagory::deleteCatagoryOwner');
        $routes->post('catagories/update/status', 'Catagory::updateStatusCat');
        $routes->post('catagories/get/edit', 'Catagory::getCatEdit');
        $routes->post('catagories/update', 'Catagory::updateCatagory');
        $routes->post('catagories/delete', 'Catagory::deleteCatagory');
        $routes->post('catagories/add', 'Catagory::insertCatagory');
        $routes->get('catagories/sub', 'Catagory::getSubCatagory');
        $routes->post('catagories/sub/delete', 'Catagory::deleteSubCatagory');
        $routes->post('catagories/sub/update/status', 'Catagory::updateStatusSubCat');
        $routes->post('catagories/sub/insert', 'Catagory::insertSubCat');
        $routes->post('catagories/sub/get/edit', 'Catagory::getUpdateSubCat');
        $routes->post('catagories/sub/update', 'Catagory::updateSubCat');
    });

    // role user
    $routes->group('user', ['filter' => 'role_user'], function ($routes) {
        $routes->get('home', 'UserTicket::index');

        // ticket page
        $routes->get('catagories/list', 'Catagory::getCategories');
        $routes->get('catagories/sub', 'Catagory::getSubCatagory');
        $routes->post('ticket/save', 'UserTicket::saveTicket');
        $routes->get('ticket/list', 'UserTicket::getTicketByUser');
        // $routes->match(['get', 'post'], 'ticket/detail', 'UserTicket::getTicketDetail');
        $routes->post('ticket/detail', 'UserTicket::getTicketDetail');
        $routes->post('ticket/update/status', 'UserTicket::updateTicket');
        $routes->get('ticket/update/status', 'UserTicket::updateTicket');
        $routes->post('ticket/return', 'UserTicket::returnTicket');

        // history ticket page
        $routes->get('history/ticket', 'HistoryTicket::index');
        $routes->get('history/ticket/search', 'HistoryTicket::searchHistory');

        // report page
        $routes->get('report/ticket/all', 'UserReport::ticketAll_page');
        $routes->get('report/ticket/status', 'UserReport::ticketStatus_page');
        $routes->get('report/ticket/all/display', 'UserReport::index');
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
