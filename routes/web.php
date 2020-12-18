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
Route::get('/home', 'MemberDashboard@index')->name('home');

//Route::get('/', 'HomeController@index')->name('welcome');
//Route::get('/home', 'FolderCreatorController@index')->name('home');
//Route::get('/', 'ScheduleController@index')->name('welcome');

//Login Form
Route::get('/', 'Auth\LoginController@showLoginForm')->name('welcomeLogin');



//User Reservation
Route::resource('/reservation', 'Members\ReservationController');
Route::get('/memberschedule', 'Members\ReservationController@create');





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


/*Customer Support */
Route::resource('customersupport', 'CustomerSupportController');


/* Admin Panel */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {

    /* Admin Auth */
    Route::get('login', 'AuthController@showLoginForm')->name('showLoginForm');
    Route::post('login', 'AuthController@login')->name('AdminLogin');
    Route::post('logout', 'AuthController@logout')->name('AdminLogout');

    Route::group(['middleware' => 'admin.auth'], function()
    {
      
        Route::resource('/', 'Modules\LessonController');

        /*This module alias */
        Route::resource('/dashboard', 'Modules\ScheduleItemController');
        Route::resource('/lesson', 'Modules\ScheduleItemController');

        
        
        //Route::resource('/dashboard', 'DashboardController');
          //Route::resource('/', 'DashboardController');
        

        /* 
        //File Manager as a homepage
        Route::resource('/', 'Modules\FileManagerController');
        Route::resource('/dashboard', 'Modules\FileManagerController');
        */

      

        //Member
        Route::delete('/member/destroy', 'Modules\MemberController@massDestroy')->name('member.massDestroy');
        Route::resource('/member', 'Modules\MemberController');
        
        Route::get('/member/paymenthistory/{id}', 'Modules\MemberController@paymenthistory')->name('member.paymenthistory');
        Route::get('/member/account/{id}', 'Modules\MemberController@account')->name('member.account');
        Route::get('/member/schedulelist/{id}', 'Modules\MemberController@schedulelist')->name('member.schedulelist');


        //tutor
        Route::delete('/tutor/destroy', 'Modules\TutorController@massDestroy')->name('tutor.massDestroy');
        Route::post('/tutor/resetPassword/{id}', 'Modules\TutorController@resetPassword')->name('tutor.resetPassword');
        Route::resource('/tutor', 'Modules\TutorController');

        Route::delete('/manager/destroy', 'Modules\ManagerController@massDestroy')->name('manager.massDestroy');
        Route::post('/manager/resetPassword', 'Modules\ManagerController@resetPassword')->name('manager.resetPassword');        
        Route::resource('/manager', 'Modules\ManagerController');

        //Agent
        Route::get('/agent/account/{id}', 'Modules\AgentController@account')->name('agent.account');
        Route::post('/agent/updateAccount', 'Modules\AgentController@updateAccount')->name('agent.updateAccount');

        Route::delete('/agent/destroy', 'Modules\AgentController@massDestroy')->name('agent.massDestroy');
        Route::post('/agent/resetPassword/{id}', 'Modules\AgentController@resetPassword')->name('agent.resetPassword');        
        Route::resource('/agent', 'Modules\AgentController');

        //Manage
        Route::resource('/questionnaires', 'Modules\QuestionnaireController');
        Route::resource('/announcement', 'Modules\AnnouncementController');

        Route::get('/course/sort', 'Modules\CourseController@sort');
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
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');