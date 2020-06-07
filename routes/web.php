<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::get('/home', 'HomeController@index')->name('home');



Route::get('/home', 'HomeController@index')->name('home');

/* Folder Controllers */
Route::resource('uploader', 'FolderCreatorController');

/*File Controller*/
Route::resource('file', 'FileController');

/*Upload Controller */
Route::post('uploader/fileUploader', 'FileUploadController@upload');

/* Public Folder View */
Route::get('folder/{id}', 'PublicFolderController@show');

/* Download Controller*/
Route::resource('download', 'DownloadController');


/* Admin Panel */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {

    Route::get('login', 'AuthController@showLoginForm')->name('showLoginForm');
    Route::post('login', 'AuthController@login')->name('login');
    Route::get('logout', 'AuthController@logout')->name('logout');
    Route::post('logout', 'AuthController@logout')->name('logout');

    Route::group(['middleware' => 'admin.auth'], function(){
        Route::resource('/', 'DashboardController');
        Route::resource('/dashboard', 'DashboardController');
    });
});


Auth::routes();
