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
Route::resource('dump', 'dummyController');
Route::get('test', 'dummyController@test');
Route::get('testGetMembers', 'dummyController@testGetMembers');
Route::get('sendTestMail', 'dummyController@sendTestMail');
Route::get('testDispatch', 'dummyController@testDispatch');
Route::get('testMailReserved', 'dummyController@testMailReserved');
Route::get('testMailCancelled', 'dummyController@testMailCancelled');
Route::get('testExpiry', 'dummyController@testExpiry');

Route::get('testMailReservedB', 'dummyController@testMailReservedB');


//Route::get('test/{id}', 'TableImporterController@test')->name('test');
//Route::get('testSchedule/{id}', 'TableImporterController@test')->name('test');

/* REVERSE TRANSACTION B*/


/*************** 
 * AGENT IMPORTER
 ****************/
Route::resource('importAgentTranscations', 'TableGetAgentTransactionController');
Route::get('importAgentTranscations/index/{per_item}', 'TableGetAgentTransactionController@index');
Route::get('importAgentTranscations/{id}/{per_item}', 'TableGetAgentTransactionController@show');
Route::get('importAgentTranscations/old/{id}/{per_item}', 'TableGetAgentTransactionController@showOld');


/*************** 
 * SCHEDULES IMPORTER
 ****************/
Route::get('importSchedules/compare', 'TableScheduleItemImporterController@compare');
Route::get('importSchedules/getnew', 'TableScheduleItemImporterController@getNewTransactions');

//import table
Route::get('importSchedules/update/{id}', 'TableScheduleItemImporterController@update');

//page chunk links
Route::get('importSchedules/index/{id}', 'TableScheduleItemImporterController@index');
Route::get('importSchedules/index/', 'TableScheduleItemImporterController@index');

//@param id - page id number for the chunk
Route::get('importSchedules/import/{id}', 'TableScheduleItemImporterController@importSchedules');
Route::get('importSchedules/import/{id}/{per_item}', 'TableScheduleItemImporterController@importSchedules');

/*************** 
 * SCHEDULES UPDATE BY DATE
 ****************/
Route::get('scheduleUpdater/index/{date}', 'TableSchedulerUpdaterController@index');
Route::resource('scheduleUpdater', 'TableSchedulerUpdaterController');



/*************** 
 *  IMPORT QUESTIONNAIRES 
 ***************/
Route::get('importQuestionnaire', 'TableQuestionnaireImporterController@index');
Route::get('importQuestionnaire/import/{id}', 'TableQuestionnaireImporterController@import');
Route::get('importQuestionnaire/import/{id}/{per_item}', 'TableQuestionnaireImporterController@import');


/*************** 
 *  IMPORT QUESTIONNAIRE ITEMS
 ***************/
Route::get('importQuestionnaireItem', 'TableQuestionnaireItemImporterController@index');
Route::get('importQuestionnaireItem/import/{id}', 'TableQuestionnaireItemImporterController@import');
Route::get('importQuestionnaireItem/import/{id}/{per_item}', 'TableQuestionnaireItemImporterController@import');


/*************** 
* USER IMAGE IMPORTER
***************/
Route::resource('importUserImage', 'TableUserImageImporterController');
Route::get('importUserImage/index/{per_item}', 'TableUserImageImporterController@index');
Route::get('importUserImage/{id}/{per_item}', 'TableUserImageImporterController@show');

/*************** 
 * USER IMPORTER
 ***************/
Route::resource('importUsers', 'TableUserImporterController');
Route::get('importUsers/index/{per_item}', 'TableUserImporterController@index');
Route::get('importUsers/{id}/{per_item}', 'TableUserImporterController@show');

/*************** 
 * TUTOR IMPORTER
 ***************/
Route::resource('importTutors', 'TableTutorImporterController');
Route::get('importTutors/index/{per_item}', 'TableTutorImporterController@index');
Route::get('importTutors/{id}/{per_item}', 'TableTutorImporterController@show');


/*************** 
 * MEMBER IMPORTER
 ***************/
Route::resource('importMembers', 'TableMemberImporterController');
Route::get('importMembers/index/{per_item}', 'TableMemberImporterController@index');
Route::get('importMembers/{id}/{per_item}', 'TableMemberImporterController@show');


/*************** 
 * MEMBER ATTRIBUTE IMPORTER
 ***************/
Route::resource('importMemberAttribute', 'TableMemberAttributeImporterController');
Route::get('importMemberAttribute/index/{per_item}', 'TableMemberAttributeImporterController@index');
Route::get('importMemberAttribute/{id}/{per_item}', 'TableMemberAttributeImporterController@show');


/*************** 
 * AGENT IMPORTER
 ***************/
Route::resource('importAgents', 'TableAgentImporterController');
Route::get('importAgents/index/{per_item}', 'TableAgentImporterController@index');
Route::get('importAgents/{id}/{per_item}', 'TableAgentImporterController@show');

/*************** 
 * REPORTCARD IMPORTER
 ***************/
Route::get('importReportCards/compare', 'TableReportCardImporterController@compare');
Route::resource('importReportCards', 'TableReportCardImporterController');
Route::get('importReportCards/index/{per_item}', 'TableReportCardImporterController@index');
Route::get('importReportCards/{id}/{per_item}', 'TableReportCardImporterController@show');

/*************** 
 * REPORTCARD DATE IMPORTER
 ***************/
Route::resource('importReportCardsDate', 'TableReportCardDateImporterController');
Route::get('importReportCardsDate/index/{per_item}', 'TableReportCardDateImporterController@index');
Route::get('importReportCardsDate/{id}/{per_item}', 'TableReportCardDateImporterController@show');

/*************** 
 * CUSTOMER SUPPORT IMPORTER
 ***************/
Route::resource('importCustomerSupport', 'TableCustomerSupportImporterController');
Route::get('importCustomerSupport/index/{per_item}', 'TableCustomerSupportImporterController@index');
Route::get('importCustomerSupport/{id}/{per_item}', 'TableCustomerSupportImporterController@show');

/*************** 
 *  DESIRED SCHEDULE IMPORTER
 ***************/
Route::resource('importDesiredSchedule', 'TableDesiredScheduleImporterController');
Route::get('importDesiredSchedule/index/{per_item}', 'TableDesiredScheduleImporterController@index');
Route::get('importDesiredSchedule/{id}/{per_item}', 'TableDesiredScheduleImporterController@show');

/*************** 
 *  COURSE CATEGORY IMPORTER
 ***************/
Route::resource('importCourseCategory', 'TableCourseCategoryImporterController');
Route::get('importCourseCategory/index/{per_item}', 'TableCourseCategoryImporterController@index');
Route::get('importCourseCategory/{id}/{per_item}', 'TableCourseCategoryImporterController@show');

 /*************** 
 *  COURSE CATEGORY IMPORTER IMAGE
 ***************/
Route::resource('importCourseCategoryImage', 'TableCourseCategoryImageImporterController');
Route::get('importCourseCategoryImage/index/{per_item}', 'TableCourseCategoryImageImporterController@index');
Route::get('importCourseCategoryImage/{id}/{per_item}', 'TableCourseCategoryImageImporterController@show');

Route::resource('updateMemberExpirty', 'TableUserExpiryUpdaterController');

/*************** 
 *  ANNOUNCEMENTS
 ***************/

/*************** 
 *  COMPANY
 ***************/


/*** MEMBERS */
Route::get('/home', 'MemberDashboard@index')->name('home');


/*STATIC PAGES */
Route::get('/stagelevel', 'PageController@stageLevel')->name('stagelevel');
Route::get('/user', 'MemberDashboard@index')->name('user');
Route::get('/faq', 'MemberDashboard@faq')->name('faq');

/** TUTOR */
Route::resource('viewtutor', 'TutorProfileController');

//Settings
Route::resource('/settings', 'Members\MemberSettingController');
Route::put('settings', 'Members\MemberSettingController@updatePassword')->name('settings.updatePassword');

//Image upload
Route::resource('image-upload', 'Admin\imageUploadController');

//User Reservation
Route::resource('/reservation', 'Members\ReservationController');
Route::get('/memberschedule', 'Members\ReservationController@create');


//Route::get('/', 'HomeController@index')->name('welcome');
//Route::get('/home', 'FolderCreatorController@index')->name('home');
//Route::get('/', 'ScheduleController@index')->name('welcome');

//Login Form
Route::get('/', 'Auth\LoginController@showLoginForm')->name('welcomeLogin');

Route::get('/lessonrecord', 'LessonRecordController@index')->name('lessonrecord');
Route::get('/reportcard/{id}', 'LessonRecordController@reportcard')->name('reportcard');
Route::get('/userreportcarddate/{id}', 'LessonRecordController@userreportcarddate')->name('reportcarddate');

Route::resource('/questionnaire', 'Members\QuestionnaireController');


/* Public Folder View */
//Route::get("folder/{path}", "PublicFolderController@show")->where('path', '.+');

/*File Controller*/
//Route::resource('file', 'FileController');


/* Folder Manager Controllers */
//Route::resource('uploader', 'FolderCreatorController');

/*Upload Controller, Customer Support Upload Controller   */
Route::post('uploader/fileUploader', 'FileUploadController@upload');

Route::post('upload/tutor/file', 'FileUploadController@tutorUpload');


/* Download Controller*/
//Route::resource('download', 'DownloadController');
Route::get('download/{path}', 'DownloadController@downloadLessonMaterial');


/*Customer Support */
Route::resource('customersupport', 'CustomerSupportController');
Route::resource('faq', 'FAQController');

/*Chat support */
Route::resource('customerchatsupport', 'CustomerChatSupportController');


/*Lesson Support */
Route::resource('lessonmaterials', 'LessonMaterialsController');


//export file (public)
Route::get('exportCSV', 'ExportController@exportCSV')->name('exportMemberCSV');
Route::get('exportExpiredXLS', 'ExportController@exportExpiredXLS')->name('exportExpiredXLS');
Route::get('exportSoonToExpireXLS', 'ExportController@exportSoonToExpireXLS')->name('exportSoonToExpireXLS');

//reports
Route::get('downloadlessonReport', 'ExportController@downloadlessonReport')->name('downloadlessonReport');
Route::get('downloadSalaryReport', 'ExportController@downloadSalaryReport')->name('downloadSalaryReport');

Route::get('writing/ielts', 'Writing\WritingController@ielts')->name('ielts');

Route::get('writing', 'Writing\WritingController@index')->name('writing');




/* Admin Panel */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {

    /* Admin Auth */
    Route::get('login.do', 'AuthController@showLoginForm')->name('showLoginFormDo');

    Route::get('login', 'AuthController@showLoginForm')->name('showLoginForm');
    Route::post('login', 'AuthController@login')->name('AdminLogin');
    Route::post('logout', 'AuthController@logout')->name('AdminLogout');

    //settings
    Route::resource('settings', 'SettingsController');
    Route::put('settings', 'SettingsController@updatePassword')->name('settings.updatePassword');
    //Route::get('settings', 'SettingsController@index')->name('settings');

    Route::group(['middleware' => 'admin.auth'], function()
    {

        Route::resource('/writing', 'Modules\WritingController');

        //upload photo
        Route::resource('image-upload', 'imageUploadController');

        //ADMIN HOME
        Route::resource('/', 'Modules\ScheduleItemController');
        Route::resource('/dashboard', 'Modules\ScheduleItemController');
        Route::resource('/lesson', 'Modules\ScheduleItemController');

        //Route::resource('/dashboard', 'DashboardController');
        //Route::resource('/', 'DashboardController');
        /* 
        //File Manager as a homepage
        Route::resource('/', 'Modules\FileManagerController');
        Route::resource('/dashboard', 'Modules\FileManagerController');
        */

        Route::resource('/reportcard', 'Modules\ReportCardController');
        Route::get('/reportcardlist/{id}', 'Modules\ReportCardController@reportcardlist');

        Route::resource('/reportcarddate', 'Modules\ReportCardDateController');
        Route::get('/reportcarddatelist/{id}', 'Modules\ReportCardController@reportcarddatelist');

        //Member Chat Support
        Route::resource('customerchatsupport', 'Modules\CustomerChatSupportController');        

        //Member
        Route::delete('/member/destroy', 'Modules\MemberController@massDestroy')->name('member.massDestroy');
        Route::post('/member/resetPassword/{id}', 'Modules\MemberController@resetPassword')->name('member.resetPassword');        
        Route::post('/member/activate/{id}', 'Modules\MemberController@activate')->name('member.activate');
        Route::post('/member/deactivate/{id}', 'Modules\MemberController@deactivate')->name('member.deactivate');
        Route::resource('/member', 'Modules\MemberController');        
        Route::get('/member/paymenthistory/{id}', 'Modules\MemberController@paymenthistory')->name('member.paymenthistory');
        Route::get('/member/account/{id}', 'Modules\MemberController@account')->name('member.account');

        Route::get('/member/schedulelist_test/{id}', 'Modules\MemberController@schedulelist_test')->name('member.schedulelist_test');

        Route::get('/member/schedulelist/{id}', 'Modules\MemberController@schedulelist')->name('member.schedulelist');        
        Route::get('/member/{id}', 'Modules\MemberController@show')->name('member.details');

        //tutor
        Route::delete('/tutor/destroy', 'Modules\TutorController@massDestroy')->name('tutor.massDestroy');
        Route::post('/tutor/resetPassword/{id}', 'Modules\TutorController@resetPassword')->name('tutor.resetPassword');        
        Route::resource('/tutor', 'Modules\TutorController');
        Route::get('/maintutor/{id}', 'Modules\TutorController@maintutor');
        Route::get('/supporttutor/{id}', 'Modules\TutorController@supporttutor');
        

        Route::delete('/manager/destroy', 'Modules\ManagerController@massDestroy')->name('manager.massDestroy');
        Route::post('/manager/resetPassword/{id}', 'Modules\ManagerController@resetPassword')->name('manager.resetPassword');        
        Route::resource('/manager', 'Modules\ManagerController');

        //Agent
        Route::get('/agent/account/{id}', 'Modules\AgentController@account')->name('agent.account');
        Route::post('/agent/updateAccount', 'Modules\AgentController@updateAccount')->name('agent.updateAccount');
        Route::get('/agent/paymenthistory/{id}', 'Modules\AgentController@paymenthistory')->name('agent.paymenthistory');
        Route::get('/agent/memberlist/{id}', 'Modules\AgentController@memberlist')->name('agent.memberlist');

        Route::delete('/agent/destroy', 'Modules\AgentController@massDestroy')->name('agent.massDestroy');
        Route::post('/agent/resetPassword/{id}', 'Modules\AgentController@resetPassword')->name('agent.resetPassword');        
        Route::resource('/agent', 'Modules\AgentController');

        

        //Manage
        Route::resource('questionnaires', 'Modules\QuestionnaireController');
        Route::get('questionnaires_test/{id}', 'Modules\QuestionnaireController@questionnaire_test');


        Route::resource('/announcement', 'Modules\AnnouncementController');

        Route::get('/course/sortcategory', 'Modules\CourseController@sortcategory')->name('course.sortcategory');
        Route::get('/course/sortcategory/{id}', 'Modules\CourseController@sortcategory')->name('course.sortsubcategory');

        Route::post('/course/savesortedcategory', 'Modules\CourseController@savesortedcategory')->name('course.savesortedcategory');

        Route::delete('/course/destroyLessonMaterial', 'Modules\CourseController@destroyLessonMaterial')->name('course.destroyLessonMaterial');
        Route::resource('/course', 'Modules\CourseController');
        //upload course
        Route::post('/course/uploadCourseImage', 'Modules\CourseController@uploadCourseImage')->name('course.uploadCourseImage');
        Route::post('/course/uploadlessonmaterial', 'Modules\CourseController@uploadlessonmaterial')->name('course.uploadlessonmaterial');


        Route::resource('/accounts', 'Modules\AccountController');
        Route::resource('/company', 'Modules\CompanyController');

        //Report
        Route::resource('/lessons', 'Modules\ReportController');
        Route::resource('/salary', 'Modules\SalaryReportController');

        /* Writing (ADMIN BACKEND) */
        Route::get('/writing', 'Modules\WritingController@index')->name('writing.index');
        Route::post('/writing/{id}', 'Modules\WritingController@update')->name('writing.update');
      

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
Route::get('/signup.do', 'Auth\SignUpController@showSignUpForm')->name('signup_do');

Route::post('/validateSignUpForm', 'Auth\SignUpController@validateSignUpForm')->name('validateSignUp');
Route::get('/confirmation', 'Auth\SignUpController@showConfirmationForm')->name('signUpConfirmation');
Route::post('/createMember','Auth\SignUpController@store')->name('createMember');
Route::get('/activation/{code}', 'Auth\SignUpController@activation')->name('activation');

//step 3 (user has been emailed and waiting for verification)
Route::get('/saveuser','Auth\SignUpController@step3')->name('step3');

Auth::routes();
Route::post('login', 'Auth\LoginController@login')->name('login_member');
Route::post('logout', 'Auth\LoginController@logout')->name('logout_member');

Route::get('login.do', 'Auth\LoginController@showLoginForm')->name('login_member_do');
