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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/user/register', 'API\AuthController@register');
    $router->post('/user/sign-in', 'API\AuthController@signIn');

    $router->post('/user/recover-password', 'API\AuthController@passwordRecoverGenerateToken');
    $router->patch('/user/recover-password', [
        'as' => 'password.reset',
        'uses' => 'API\AuthController@passwordRecover'
    ]);

    $router->group(['middleware' => 'auth'], function() use ($router) {
        $router->get('/user/companies', 'API\CompanyController@getCompanies');
        $router->post('/user/companies', 'API\CompanyController@createCompany');
    });
});
