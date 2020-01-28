<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/token', 'TokenController@token')->name('api.token');

Route::prefix('v1')->group(function () {

    Route::get('/user', 'UserController@getList')->name('api.user.list');
    Route::get('/user/{id}', 'UserController@get')->name('api.user.single');

    Route::options('/server', 'ServerController@options')->name('api.server.options');
    Route::get('/server/{id}', 'ServerController@get')->name('api.server.single');
    Route::get('/server', 'ServerController@getList')->name('api.server.list');
    Route::post('/server', 'ServerController@post')->name('api.server.create');
    Route::post('/server/{id}/restart', 'ServerController@restart')->name('api.server.restart');

    Route::get('/event', 'EventController@getList')->name('api.event.list');

});

