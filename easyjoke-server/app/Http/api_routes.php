<?php

/**
 * @var $api \Dingo\Api\Routing\Router
 */
$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {

    // Set our namespace for the underlying routes
    $api->group(['namespace' => 'App\Api\V1\Controllers', 'middleware' => '\Barryvdh\Cors\HandleCors::class'], function ($api) {
        /** @var \Dingo\Api\Routing\Router $api */

        $api->post('auth/signup', 'AuthController@signup');
        $api->post('auth/login', 'AuthController@login');
        $api->post('auth/recovery', 'AuthController@recovery');
        $api->post('auth/reset', 'AuthController@reset');

        // categories
        $api->get('categories', 'CategoryController@index');
        $api->post('categories', 'CategoryController@store');
        $api->get('categories/{categories}', 'CategoryController@show');
        $api->get('categories/{categories}/jokes', 'CategoryController@jokes');
        $api->put('categories/{categories}', 'CategoryController@update');
        $api->delete('categories/{categories}', 'CategoryController@destroy');

        // jokes
        $api->get('jokes', 'JokeController@index');
        $api->post('jokes', 'JokeController@store');
        $api->get('jokes/{jokes}', 'JokeController@show');
        $api->put('jokes/{jokes}', 'JokeController@update');
        $api->delete('jokes/{jokes}', 'JokeController@destroy');
    });
});