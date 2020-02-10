<?php

use Illuminate\Http\Request;

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
Route::group(['prefix' => 'V1',  'middleware' => 'cors'], function () {
    Route::get('/', function(){
        return;
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::resource('message', 'API\MessageController');
        Route::get('message/user/{id}', 'API\MessageController@listNotDeletedByUserId');
        Route::get('message/user/{id}/deleted', 'API\MessageController@listDeletedByUserId');

        Route::get('user/{id}', 'API\UserController@show');
    });

    Route::post('register', 'API\UserController@register');
    Route::post('login', 'API\UserController@login');
});
