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
    Route::post('edit-profile', 'StudentController@editProfile');
    Route::post('upload-image', 'StudentController@upload');
});

Route::group(['prefix'=>'ustad'], function() {
    Route::post('register', 'UstadController@register');
    Route::post('login', 'UstadController@login');
    Route::post('recover', 'UstadController@recover');
    Route::post('verify-code', 'UstadController@verifyCode');
    Route::post('new-password', 'UstadController@newPassword');
    Route::post('send-code', 'UstadController@sendCode');
    Route::post('edit-profile', 'UstadController@editProfile');
    Route::post('upload-image', 'UstadController@upload');
});

Route::group(['prefix'=>'post'], function() {
    Route::post('make-post', 'PostController@makePost');
    Route::get('get-all-posts', 'PostController@getAllPosts');
    Route::post('like-post', 'PostController@likePost');
    Route::post('unlike-post', 'PostController@unlikePost');
    Route::post('comment-on-post', 'PostController@commentOnPost');
});