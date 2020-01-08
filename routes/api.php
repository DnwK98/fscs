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

});

