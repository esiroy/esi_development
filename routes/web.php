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
Route::resource('test', 'dummyController');

Route::get('/home', 'ScheduleController@index')->name('home');

Route::get('/', 'Auth\LoginController@showLoginForm')->name('welcomeLogin');

//Route::get('/', 'HomeController@index')->name('welcome');
//Route::get('/home', 'FolderCreatorController@index')->name('home');

//Route::get('/', 'ScheduleController@index')->name('welcome');


/* Public Folder View */
Route::get("folder/{path}", "PublicFolderController@show")->where('path', '.+');

/*File Controller*/
Route::resource('file', 'FileController');


/* Folder Manager Controllers */
Route::resource('uploader', 'FolderCreatorController');

/*Upload Controller */
Route::post('uploader/fileUploader', 'FileUploadController@upload');

/* Download Controller*/
Route::resource('download', 'DownloadController');

/* Admin Panel */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {

    /* Admin Auth */
    Route::get('login', 'AuthController@showLoginForm')->name('showLoginForm');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout')->name('logout');

    Route::group(['middleware' => 'admin.auth'], function()
    {
        /*
        Route::resource('/', 'DashboardController');
        Route::resource('/dashboard', 'DashboardController');
        */

        /* 
        //File Manager as a homepage
        Route::resource('/', 'Modules\FileManagerController');
        Route::resource('/dashboard', 'Modules\FileManagerController');
        */

        Route::resource('/', 'Modules\LessonController');

        /*This module alias */
        Route::resource('/dashboard', 'Modules\LessonController');
        Route::resource('/lesson', 'Modules\LessonController');

        //Member
        Route::resource('/member', 'Modules\MemberController');
        Route::resource('/tutor', 'Modules\TutorController');
        Route::resource('/manager', 'Modules\ManagerController');
        Route::resource('/agent', 'Modules\AgentController');

        //Manage
        Route::resource('/questionnaires', 'Modules\QuestionnaireController');
        Route::resource('/announcement', 'Modules\AnnouncementController');
        Route::resource('/course', 'Modules\CourseController');
        Route::resource('/accounts', 'Modules\AccountController');
        Route::resource('/company', 'Modules\CompanyController');

        //Report
        Route::resource('/lessons', 'Modules\ReportController');
        Route::resource('/salary', 'Modules\SalaryReportController');

        /* Administrator Module Lists */
        Route::group(['prefix' => 'module', 'namespace' => 'Modules', 'as' => 'module.'], function() 
        {
            Route::resource('formbuilder', 'FormBuilderController');

            Route::delete('/filemanager/destroy', 'FileManagerController@massDestroy')->name('filemanager.massDestroy');
            Route::resource('filemanager', 'FileManagerController');

            /* @todo : Start Dynamic Module Routes 
            **
            **
            */
        });
           
        //User Management - Admin Area
        Route::group(['prefix' => 'user-management'], function() {
            
            Route::delete('/users/destroy', 'UserController@massDestroy')->name('users.massDestroy');
            Route::resource('/users', 'UserController');

            Route::delete('/roles/destroy', 'RoleController@massDestroy')->name('roles.massDestroy');
            Route::resource('/roles', 'RoleController');
           
            Route::delete('/permissions/destroy', 'PermissionController@massDestroy')->name('permissions.massDestroy');
            Route::resource('/permissions', 'PermissionController');
        });

    }); //End [Admin Area]

});

Route::get('/signup', 'Auth\SignUpController@showSignUpForm')->name('signup');
Route::post('/validateSignUpForm', 'Auth\SignUpController@validateSignUpForm')->name('validateSignUp');
Route::get('/confirmation', 'Auth\SignUpController@showConfirmationForm')->name('signUpConfirmation');
Route::post('/createMember','Auth\SignUpController@store')->name('createMember');

//step 3 (user has been emailed and waiting for verification)
Route::get('/saveuser','Auth\SignUpController@step3')->name('step3');



Auth::routes();