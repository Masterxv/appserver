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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'student'], function() {
    Route::post('register', 'StudentController@register');
    Route::post('login', 'StudentController@login');
    Route::post('recover', 'StudentController@recover');
    Route::post('verify-code', 'StudentController@verifyCode');
    Route::post('new-password', 'StudentController@newPassword');
    Route::post('send-code', 'StudentController@sendCode');
});

Route::group(['prefix'=>'ustad'], function() {
//    Route::post('register', 'StudentController@register');
//    Route::post('login', 'StudentController@login');
//    Route::post('recover', 'StudentController@recover');
});