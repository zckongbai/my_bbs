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

$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->get('404', function () use ($app) {
    echo '404';
});

$app->get('403', function () use ($app) {
    echo '403';
});

/**
 * user
 */
$app->get('user', 'UserController@index');
$app->get('user/index', 'UserController@index');
$app->get('user/register', 'UserController@register');
$app->post('user/register', 'UserController@register');
$app->get('user/login', 'UserController@login');
$app->post('user/login', 'UserController@login');
$app->get('user/logout', 'UserController@logout');
$app->get('user/getReplies', 'UserController@getReplies');
$app->get('user/sendReplies', 'UserController@sendReplies');
$app->get('user/topics', 'UserController@topics');

$app->get('user/event', 'UserController@eventTest');

/**
 * home
 */
$app->get('home/index', 'HomeController@index');
$app->get('home', 'HomeController@index');

/**
 * permission
 */
$app->get('permission/index', ['uses'=>'PermissionController@index']);
$app->post('permission/add', ['uses'=>'PermissionController@add']);
$app->post('permission/update', ['uses'=>'PermissionController@update']);
$app->get('permission/delete/{id}', ['uses'=>'PermissionController@delete']);

$app->get('role/index', ['uses'=>'RoleController@index']);
$app->get('role/add', ['uses'=>'RoleController@add']);
$app->post('role/update', ['uses'=>'RoleController@update']);
$app->get('role/delete/{id}', ['uses'=>'RoleController@delete']);

$app->get('section/index', ['uses'=>'SectionController@index']);
$app->get('section/add', ['uses'=>'SectionController@add']);
$app->post('section/add', ['uses'=>'SectionController@add']);
$app->post('section/update', ['uses'=>'SectionController@update']);
$app->get('section/delete/{id}', ['uses'=>'SectionController@delete']);

/**
 * topic
 */
$app->get('topic/add', ['uses'=>'TopicController@add']);
$app->post('topic/add', ['uses'=>'TopicController@add']);
$app->post('topic/reply', ['middleware' => 'auth', 'uses' => 'TopicController@reply']);
$app->get('topic/{id}', ['middleware' => 'auth', 'uses' => 'TopicController@get']);

