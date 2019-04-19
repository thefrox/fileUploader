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

Route::get('/', function () {
    return view('login');
});

//Login
Route::get('/login', 'LoginController@index');
Route::post('/login/checklogin', 'LoginController@checklogin');
Route::get('/login/successlogin', 'LoginController@successlogin');
Route::get('/login/logout', 'LoginController@logout');

//FileUploader
Route::get('/uploader', 'UploaderController@index');
Route::get('/uploader/add', 'UploaderController@add');
Route::get('/uploader/edit', 'UploaderController@edit');
Route::get('/uploader/list', 'UploaderController@list');