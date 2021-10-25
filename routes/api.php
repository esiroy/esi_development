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
Route::middleware('auth:api')->post('/override_tutor_schedule', 'API\TutorScheduleController@overrideTutorSchedule')->name('APIOverrideTutorSchedule');

Route::middleware('auth:api')->post('/delete_tutor_schedule', 'API\TutorScheduleController@deleteTutorSchedule')->name('APIDeleteTutorSchedule');
Route::middleware('auth:api')->post('/get_schedules', 'API\TutorScheduleController@getSchedules')->name('APIGetSchedules');
Route::middleware('auth:api')->post('/get_members', 'API\TutorScheduleController@getMembers')->name('APIGetMemberList');
Route::middleware('auth:api')->post('/get_members_dropdown_options', 'API\TutorScheduleController@getMembersDropdownOptions')->name('APIGetMemberDropdownOptionList');

Route::middleware('auth:api')->post('/update_schedules', 'API\TutorScheduleController@updateSchedules')->name('APIUpdateSchedules');


//[start] favorite tutor
Route::middleware('auth:api')->post('/getFavoriteTutors', 'API\TutorController@getFavoriteTutors')->name('APIGetFavoriteTutors');
Route::middleware('auth:api')->post('/saveFavoriteTutor', 'API\TutorController@saveFavoriteTutor')->name('APISaveFavoriteTutor');
Route::middleware('auth:api')->post('/removeFavoriteTutor', 'API\TutorController@removeFavoriteTutor')->name('APIRemoveFavoriteTutor');

//[start] member ajax schedule
Route::middleware('auth:api')->post('/book', 'API\MemberController@bookSchedule')->name('APIBookSchedule');
Route::middleware('auth:api')->post('/cancelSchedule', 'API\MemberController@cancelSchedule')->name('APICancelSchedule');
//Route::middleware('auth:api')->post('/deleteSchedule', 'API\MemberController@deleteSchedule')->name('APIDeleteSchedule');
Route::middleware('auth:api')->post('/sendMemo', 'API\MemberController@sendMemo')->name('APISendMemo');
Route::middleware('auth:api')->post('/getMemo', 'API\MemberController@getMemo')->name('APIGetMemo');
Route::middleware('auth:api')->post('/postComment', 'API\MemberController@postComment')->name('APIPostComment');
Route::middleware('auth:api')->post('/viewComment', 'API\MemberController@viewComment')->name('APIViewComment');
Route::middleware('auth:api')->post('/getBookScheduledCount', 'API\MemberController@getBookScheduledCount')->name('APIgetBookScheduledCount');
Route::middleware('auth:api')->post('/getTotalMemberDailyReserved', 'API\MemberController@getTotalMemberDailyReserved')->name('APIGetTotalMemberDailyReserved');
Route::middleware('auth:api')->post('/getTotalTutorDailyReserved', 'API\MemberController@getTotalTutorDailyReserved')->name('APIGetTotalTutorDailyReserved');
Route::middleware('auth:api')->post('/getScheduleDetails', 'API\MemberController@getScheduleDetails')->name('APIGetScheduleDetails');

//[start](MEMBER CONTROL PANEL) 
Route::middleware('auth:api')->post('/sendMemberReply', 'API\MemberController@sendMemberReply')->name('APIsendMemberReply');
Route::middleware('auth:api')->post('/getUnreadTeacherMessages', 'API\MemberController@getUnreadTeacherMessages')->name('APIGetUnreadTeacherMessages');
Route::middleware('auth:api')->post('/getMemberMemoConversations', 'API\MemberController@getMemoConversations')->name('APIGetMemberMemoConversations');


//[notifier] or member inbox messages realtime notifier
Route::middleware('auth:api')->post('/getMemberInbox', 'API\MemberController@getMemberInbox')->name('APIGetMemberInbox');

//[start](CHAT SUPPORT)
Route::middleware('auth:api')->post('/getChathistory', 'API\ChatSupportController@getChathistory')->name('APIGetChathistory');
Route::middleware('auth:api')->post('/saveCustomerSupportChat', 'API\ChatSupportController@saveCustomerSupportChat')->name('APISaveCustomerSupportChat');


//[start](TUTOR CONTROL PANEL)  memo chat system 
Route::middleware('auth:api')->post('/getMemoConversations', 'API\TutorController@getMemoConversations')->name('APIGetMemoConversations');
Route::middleware('auth:api')->post('/sendMemoReply', 'API\TutorController@sendMemoReply')->name('APISendMemoReply');
Route::middleware('auth:api')->post('/getUnreadMemberMessages', 'API\TutorController@getUnreadMemberMessages')->name('APIgetUnreadMemberMessages');
Route::middleware('auth:api')->post('/uploadTutorFile', 'API\TutorController@uploadTutorFile')->name('APIuploadTutorFile');

//[notifier] TUTOR inbox messages realtime notifier
Route::middleware('auth:api')->post('/getTutorInbox', 'API\TutorController@getTutorInbox')->name('APIGetTutorInbox');

//(all memo)
Route::middleware('auth:api')->post('/getAllMemoConversations', 'API\TutorScheduleController@getAllMemoConversations')->name('APIGetAllMemoConversations');

//[start] Lesson Materials
Route::middleware('auth:api')->post('/sortLessonMaterials', 'API\LessonMaterialsController@sortLessonMaterials')->name('APISortLessonMaterials');


//Form Maker
Route::middleware('auth:api')->post('/saveSimpleTextField', 'API\FormMakerController@saveSimpleTextField')->name('APISaveSimpleTextField');
Route::middleware('auth:api')->post('/saveDropDownSelect', 'API\FormMakerController@saveDropDownSelect')->name('APISaveDropDownSelect');
Route::middleware('auth:api')->post('/saveHTMLContent', 'API\FormMakerController@saveHTMLContent')->name('APISaveSaveHTMLContent');

//Advance Fields
Route::middleware('auth:api')->post('/saveFirstNameField', 'API\FormMakerController@saveFirstNameField')->name('APISaveFirstNameField');
Route::middleware('auth:api')->post('/saveFirstEmailField', 'API\FormMakerController@saveFirstEmailField')->name('APISaveFirstEmailField');
Route::middleware('auth:api')->post('/saveLastNameField', 'API\FormMakerController@saveLastNameField')->name('APISaveLastNameField');
Route::middleware('auth:api')->post('/saveEmailField', 'API\FormMakerController@saveEmailField')->name('APISaveEmailField');
Route::middleware('auth:api')->post('/saveUploadField', 'API\FormMakerController@saveUploadField')->name('APISaveUploadField');


Route::middleware('auth:api')->post('/getHTMLFieldContent', 'API\FormMakerController@getHTMLFieldContent')->name('APISGetHTMLFieldContent');
Route::middleware('auth:api')->post('/removeField', 'API\FormMakerController@removeField')->name('APIRemoveField');

