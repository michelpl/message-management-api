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
Route::prefix('/V1')->middleware('cors')->group(function () {
    Route::get('/', function(){
        return;
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::resource('message', 'API\MessageController');
        Route::get('message/user/{id}', 'API\MessageController@listByUserId');

        Route::get('user/{id}', function (Request $request){
            return $request->user();
        });
    });

    Route::post('register', 'API\UserController@register');
    Route::post('login', 'API\UserController@login');
});

