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
     * user
     */
    $app->get('user/login', 'UserController@login');
    $app->post('user/doLogin', 'UserController@doLogin');
    $app->get('user/register', 'UserController@register');
    $app->post('user/doRegister', 'UserController@doRegister');

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
    $app->post('admin/doLogin', 'AdminController@doLogin');

    /**
     * section
     */
    $app->get('section', ['uses'=>'SectionController@index']);
    $app->get('section/{id}/topics', ['uses'=>'SectionController@topics', 'as'=>'section/topics']);


});

$app->group(['middleware'=>'user', 'namespace' => 'App\Http\Controllers'],function ($app){

    /**
     * user
     */
    $app->get('user', 'UserController@index');
    $app->get('user/index', 'UserController@index');
    $app->get('user/logout', 'UserController@logout');
    $app->get('user/getReplies', 'UserController@getReplies');
    $app->get('user/sendReplies', 'UserController@sendReplies');
    $app->get('user/topics', 'UserController@topics');

    /**
     * topic
     */
    $app->get('topic/add', ['uses'=>'TopicController@add']);
    $app->post('topic/doAdd', ['uses'=>'TopicController@doAdd']);
    $app->post('topic/reply', ['uses' => 'TopicController@reply']);
    $app->get('topic/{id}', ['uses' => 'TopicController@get', 'as'=>'topic']);
    $app->get('topic/{id}/delete', ['uses' => 'TopicController@delete', 'as'=>'topic/delete']);

});

$app->group(['middleware' => 'admin', 'namespace' => 'App\Http\Controllers'], function ($app){
    /**
     * permission
     */
    $app->get('permission', ['uses'=>'PermissionController@index']);
    $app->post('permission/add', ['uses'=>'PermissionController@add']);
    $app->post('permission/update', ['uses'=>'PermissionController@update']);
    $app->get('permission/{id}/delete', ['uses'=>'PermissionController@delete']);

    /**
     * role
     */
    $app->get('role', ['uses'=>'RoleController@index']);
    $app->post('role/add', ['uses'=>'RoleController@add']);
    $app->post('role/update', ['uses'=>'RoleController@update']);
    $app->get('role/{id}/delete', ['uses'=>'RoleController@delete']);

    /**
     * section
     */
    $app->get('section/add', ['uses'=>'SectionController@add']);
    $app->post('section/doAdd', ['uses'=>'SectionController@doAdd']);
    $app->post('section/update', ['uses'=>'SectionController@update', 'as'=>'section/update']);
    $app->get('section/{id}/delete', ['uses'=>'SectionController@delete', 'as'=>'section/delete']);
    $app->get('section/{id}', ['uses'=>'SectionController@get']);
});

