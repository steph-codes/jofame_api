<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix'=>'api'], function($router){
    // $router->post('visitor/create_visitor', 'VisitorController@createVisitor');
    // $router->post('visitor/create_visit', 'VisitorController@createVisit');
    // $router->post('visitor/login', 'VisitorController@login');
    // $router->post('visitor/book', 'VisitorController@Book');
    // $router->get('visitor/get_visitor_id', 'VisitorController@oneVisitor');
    $router->get('visitor/get_user', 'VisitorController@getUser');
    // $router->get('visitor/get_all_users', 'VisitorController@getUser');
    // $router->get('visitor/get_user_visits', 'VisitorController@userVisits');
    // $router->get('visitor/get_pending_visit_count', 'VisitorController@userPendingVisitCount');
    // $router->get('visitor/get_account_info', 'VisitorController@getAccountInfo');
    // $router->get('visitor/cancel_visit', 'VisitorController@cancelVisit');
    // $router->get('visitor/reset_password', 'VisitorController@resetPassword');
    // $router->get('visitor/change_email', 'VisitorController@changeEmail');
    // $router->get('visitor/change_phone', 'VisitorController@changePhone');
    // $router->get('visitor/create_complaint', 'VisitorController@createComplaint');
    // $router->get('visitor/get_complaint', 'VisitorController@getComplaint');
    // $router->get('visitor/announcements', 'VisitorController@Announcements');
    // $router->get('visitor/get_visitor_record', 'VisitorController@visitorRecord');
    // $router->post('visitor/update_visitor_record', 'VisitorController@updateVisitor');
    // $router->delete('articles/delete/{id}', 'VisitorController@delete');
});
