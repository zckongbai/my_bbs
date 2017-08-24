<?php

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

// $app->get('/', function () use ($app) {
//     return $app->welcome();
// });

$app->get('404', function () use ($app) {
    echo '404';
});

$app->get('403', function () use ($app) {
    echo '403';
});

$app->group(['namespace' => 'App\Http\Controllers', ['middleware' => 'auth']], function ($app){

    /**
     * user login
     */
    $app->get('user/login', 'UserController@login');
    $app->post('user/login', 'UserController@login');

    /**
     * home
     */
    $app->get('home/index', 'HomeController@index');
    $app->get('home', 'HomeController@index');
    $app->get('/', 'HomeController@index');

    /**
     * admin login
     */
    $app->get('admin/login', 'AdminController@login');
    $app->post('admin/login', 'AdminController@login');

});

$app->group(['middleware'=>'auth', 'namespace' => 'App\Http\Controllers'],function ($app){

    /**
     * user
     */
    $app->get('user', 'UserController@index');
    $app->get('user/index', 'UserController@index');
    $app->get('user/register', 'UserController@register');
    $app->post('user/register', 'UserController@register');
    $app->get('user/logout', 'UserController@logout');
    $app->get('user/getReplies', 'UserController@getReplies');
    $app->get('user/sendReplies', 'UserController@sendReplies');
    $app->get('user/topics', 'UserController@topics');

    $app->get('user/event', 'UserController@eventTest');


    /**
     * section
     */
    $app->get('section/index', ['uses'=>'SectionController@index']);
    $app->get('section/add', ['uses'=>'SectionController@add']);
    $app->post('section/add', ['uses'=>'SectionController@add']);
    $app->post('section/update', ['uses'=>'SectionController@update']);
    $app->get('section/{id}/delete', ['uses'=>'SectionController@delete', 'as'=>'section/{id}/delete']);

    /**
     * topic
     */
    $app->get('topic/add', ['uses'=>'TopicController@add']);
    $app->post('topic/add', ['uses'=>'TopicController@add']);
    $app->post('topic/reply', ['uses' => 'TopicController@reply']);
    $app->get('topic/{id}', ['uses' => 'TopicController@get', 'as'=>'topic/{id}']);
    $app->get('topic/{id}/delete', ['uses' => 'TopicController@delete', 'as'=>'topic/{id}/delete']);

});

$app->group(['middleware' => 'admin', 'namespace' => 'App\Http\Controllers'], function ($app){
    /**
     * Routes for resource admin
     */
    $app->get('admin', 'AdminController@index');
    $app->get('admin/login', 'AdminController@login');
    $app->post('admin/add', 'AdminController@add');
    $app->post('admin/{id}', 'AdminController@update');
    $app->delete('admin/{id}', 'AdminController@update');

    /**
     * permission
     */
    $app->get('permission/index', ['uses'=>'PermissionController@index']);
    $app->post('permission/add', ['uses'=>'PermissionController@add']);
    $app->post('permission/update', ['uses'=>'PermissionController@update']);
    $app->get('permission/{id}/delete', ['uses'=>'PermissionController@delete']);

    $app->get('role/index', ['uses'=>'RoleController@index']);
    $app->get('role/add', ['uses'=>'RoleController@add']);
    $app->post('role/add', ['uses'=>'RoleController@add']);
    $app->post('role/update', ['uses'=>'RoleController@update']);
    $app->get('role/{id}/delete', ['uses'=>'RoleController@delete']);
});

// /**
//  * Routes for resource topic
//  */
// $app->get('topic', 'TopicsController@all');
// $app->get('topic/{id}', 'TopicsController@get');
// $app->post('topic', 'TopicsController@add');
// $app->put('topic/{id}', 'TopicsController@put');
// $app->delete('topic/{id}', 'TopicsController@remove');
