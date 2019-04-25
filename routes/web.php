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
Route::get('/', 'HomeController@index');
Auth::routes(['register' => false]);


Route::middleware(['auth'])->group(function () {
    //FileUploader
    Route::get('/uploader', 'UploaderController@index');
    Route::match(['get', 'post'], '/uploader/add', 'UploaderController@add');
    Route::match(['get', 'post'], '/uploader/remove', 'UploaderController@remove');
});

Route::group(['namespace' => 'Site', 'prefix' => 'admin'], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
