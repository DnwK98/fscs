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
Route::namespace('Auth')->group(function () {
    Route::post('/token', 'ApiTokenController@token')->name('api.token');
    Route::get('/me', 'ApiTokenController@me')->name('api.me');
});

Route::prefix('v1')->group(function () {

    Route::get('/user', 'UserResourceController@getList')->name('api.user.list');
    Route::get('/user/{id}', 'UserResourceController@get')->name('api.user.single');

});

