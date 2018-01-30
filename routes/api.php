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
Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function() {

    Route::get('config', 'ManagerController@getConfig');
    Route::get('extra-register-form', 'RegisterController@getExtraRegisterForms');
    Route::post('participant-registration', 'RegisterController@registerParticipantForEvent');
    Route::post('participant-preregistration', 'RegisterController@processPreregisterForEvent');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('live-event-detail', 'EventController@getLiveEventDetail');
        Route::post('complete-game', 'EventController@markGameComplete');
        Route::get('get-participant-score', 'EventController@retrieveParticipantScore');

        Route::group(['prefix' => 'profile'], function () {
            Route::get('get', 'ProfileController@getProfile');
            Route::post('update', 'ProfileController@updateProfile');
        });
    });
});
