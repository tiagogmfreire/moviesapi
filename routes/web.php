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

$router
->get('/', function () use ($router) {
    return $router->app->version();
})
//the endpoints of the API
->get('/movie/upcoming',['uses' => 'MovieController@upcoming'])
->get('/movie/search',['uses' => 'MovieController@search'])
->get('/movie/{id}',['uses' => 'MovieController@movie'])
;
