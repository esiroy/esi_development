<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use App\Models\Folder;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    $data = $request->user();
    return Response::json($data);
});
*/

Route::middleware('auth:api')->post('/create_member', 'API\MemberController@store')->name('APICreateMember');
Route::middleware('auth:api')->post('/update_member', 'API\MemberController@update')->name('APIUpdateMember');
Route::middleware('auth:api')->post('/get_agent_name', 'API\MemberController@getAgentName')->name('APIGetAgentName');


//[start] File Manager
Route::middleware('auth:api')->post('/get_folders', 'API\FolderController@folders')->name('APIGetFolders');
Route::middleware('auth:api')->post('/get_folder_files', 'API\FolderController@files')->name('APIGetFolderFiles');
Route::middleware('auth:api')->post('/create_folder', 'API\FolderController@store')->name('APICreateFolder');
Route::middleware('auth:api')->post('/update_folder', 'API\FolderController@update')->name('APIUpdateFolder');
Route::middleware('auth:api')->post('/share_folder', 'API\FolderController@shareFolder')->name('APIShareFolder');
Route::middleware('auth:api')->post('/share_file', 'API\FolderController@shareFile')->name('APIShareFile');
Route::middleware('auth:api')->post('/move_into_parent', 'API\FolderController@moveIntoParent')->name('APIMoveIntoParentFolder');
Route::middleware('auth:api')->post('/reorder_items', 'API\FolderController@reorderSiblingFolders')->name('APIReorderSiblingFolders');
Route::middleware('auth:api')->post('/delete_folder', 'API\FolderController@deleteFolder')->name('APIDeleteFolders');
Route::post('/get_child_folders', 'API\FolderController@getChildFolders')->name('APIGetPublicFolders');
Route::post('/get_public_folder_files', 'API\FolderController@getPublicFiles')->name('APIGetPublicFolderFiles');

//[start] My Page Scheduler
Route::middleware('auth:api')->post('/get_tutors', 'API\TutorController@getTutors')->name('APIGetTutors');
Route::middleware('auth:api')->post('/create_tutor_schedule', 'API\TutorScheduleController@store')->name('APICreateTutorSchedule');
Route::middleware('auth:api')->post('/update_tutor_schedule', 'API\TutorScheduleController@update')->name('APIUpdateTutorSchedule');
Route::middleware('auth:api')->post('/delete_tutor_schedule', 'API\TutorScheduleController@deleteTutorSchedule')->name('APIDeleteTutorSchedule');
Route::middleware('auth:api')->post('/get_schedules', 'API\TutorScheduleController@getSchedules')->name('APIGetSchedules');
Route::middleware('auth:api')->post('/get_members', 'API\TutorScheduleController@getMembers')->name('APIGetMemberList');


//[start] member ajax schedule
Route::middleware('auth:api')->post('/book', 'API\MemberController@bookSchedule')->name('APIBookSchedule');
Route::middleware('auth:api')->post('/cancelSchedule', 'API\MemberController@cancelSchedule')->name('APICancelSchedule');
Route::middleware('auth:api')->post('/sendMemo', 'API\MemberController@sendMemo')->name('APISendMemo');
Route::middleware('auth:api')->post('/getMemo', 'API\MemberController@getMemo')->name('APIGetMemo');
Route::middleware('auth:api')->post('/postComment', 'API\MemberController@postComment')->name('APIPostComment');








