<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Index redirect to login
Route::get('/', 'LoginController@index');

//Login
Route::get('/login', 'LoginController@index');
Route::post('/login/checklogin', 'LoginController@checklogin');
Route::get('/login/successlogin', 'LoginController@successlogin');
Route::get('/login/logout', 'LoginController@logout');

//FileUploader
Route::get('/uploader', 'UploaderController@index');
Route::match(['get', 'post'], '/uploader/add', 'UploaderController@add');
Route::match(['get', 'post'], '/uploader/remove', 'UploaderController@remove');

// Email related routes
Route::get('mail/send', 'NotificationMailController@send');