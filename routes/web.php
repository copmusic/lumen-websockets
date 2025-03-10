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

use App\Events\NotificationEvent;

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/websockets/test', function () {
    try {
        event(new NotificationEvent(['test' => 'success']));

        return response("event were sent");
    } catch (Exception $exception) {
        return response($exception->getMessage() . PHP_EOL);
    }
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');
        $router->post('register', 'AuthController@register');

        $router->group(['middleware' => 'auth:api'], function () use ($router) {
            $router->post('logout', 'AuthController@logout');
            $router->post('refresh', 'AuthController@refresh');
            $router->post('me', 'AuthController@me');
        });
    });
});
