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
Route::middleware('auth:api')->post('/get_child_folders', 'API\FolderController@getChildFolders')->name('APIGetPublicFolders');
Route::middleware('auth:api')->post('/get_public_folder_files', 'API\FolderController@getPublicFiles')->name('APIGetPublicFolderFiles');

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
Route::middleware('auth:api')->post('/searchMemberName', 'API\MemberController@searchMemberName')->name('APISearchMemberName');

//[start](MEMBER CONTROL PANEL) 
Route::middleware('auth:api')->post('/sendMemberReply', 'API\MemberController@sendMemberReply')->name('APIsendMemberReply');
Route::middleware('auth:api')->post('/getUnreadTeacherMessages', 'API\MemberController@getUnreadTeacherMessages')->name('APIGetUnreadTeacherMessages');
Route::middleware('auth:api')->post('/getMemberMemoConversations', 'API\MemberController@getMemoConversations')->name('APIGetMemberMemoConversations');


//[notifier] or member inbox messages realtime notifier
Route::middleware('auth:api')->post('/getMemberInbox', 'API\MemberController@getMemberInbox')->name('APIGetMemberInbox');

//[start](CHAT SUPPORT)
Route::middleware('auth:api')->post('/getChathistory', 'API\ChatSupportController@getChathistory')->name('APIGetChathistory');
Route::middleware('auth:api')->post('/getRecentUserChatList', 'API\ChatSupportController@getRecentUserChatList')->name('APIGetRecentUserChatList');

Route::middleware('auth:api')->post('/getUnreadChatMessages', 'API\ChatSupportController@getUnreadChatMessages')->name('APIGetUnreadChatMessages');
Route::middleware('auth:api')->post('/markChatMessagesRead', 'API\ChatSupportController@markChatMessagesRead')->name('APIMarkChatMessagesRead');
Route::middleware('auth:api')->post('/saveCustomerSupportChat', 'API\ChatSupportController@saveCustomerSupportChat')->name('APISaveCustomerSupportChat');

Route::middleware('auth:api')->post('/getAdminChatHistory', 'API\ChatSupportController@getAdminChatHistory')->name('APIGetAdminChathistory');
Route::middleware('auth:api')->post('/getAdminUnreadChatMessages', 'API\ChatSupportController@getAdminUnreadChatMessages')->name('APIGetUnreadAdminChatMessages');
Route::middleware('auth:api')->post('/markAdminChatMessagesRead', 'API\ChatSupportController@markAdminChatMessagesRead')->name('APIMarkAdminChatMessagesRead');





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

//[start] member exam  score
Route::middleware('auth:api')->post('/getAllMemberExamScore', 'API\MemberExamController@getAllMemberExamScore')->name('APIGetMemberGetAllMemberExamScores');
Route::middleware('auth:api')->post('/getMemberLatestScore', 'API\MemberExamController@getMemberLatestScore')->name('APIGetMemberLatestScore');
Route::middleware('auth:api')->post('/addMemberExamScore', 'API\MemberExamController@addMemberExamScore')->name('APIAddMemberScore');




//Form Maker (update)
Route::middleware('auth:api')->post('/saveSimpleTextField', 'API\FormMakerController@saveSimpleTextField')->name('APISaveSimpleTextField');
Route::middleware('auth:api')->post('/saveDropDownSelect', 'API\FormMakerController@saveDropDownSelect')->name('APISaveDropDownSelect');
Route::middleware('auth:api')->post('/saveHTMLContent', 'API\FormMakerController@saveHTMLContent')->name('APISaveHTMLContent');
Route::middleware('auth:api')->post('/saveParagraphText', 'API\FormMakerController@saveParagraphText')->name('APISaveParagraphText');
Route::middleware('auth:api')->post('/copyField', 'API\FormMakerController@copyField')->name('APICopyField');
Route::middleware('auth:api')->post('/saveDropDownTeacherSelect', 'API\FormMakerController@saveDropDownTeacherSelect')->name('APISaveDropDownTeacherSelect');


//Advance Fields 
Route::middleware('auth:api')->post('/saveFirstNameField', 'API\FormMakerController@saveFirstNameField')->name('APISaveFirstNameField');
Route::middleware('auth:api')->post('/saveFirstEmailField', 'API\FormMakerController@saveFirstEmailField')->name('APISaveFirstEmailField');
Route::middleware('auth:api')->post('/saveLastNameField', 'API\FormMakerController@saveLastNameField')->name('APISaveLastNameField');
Route::middleware('auth:api')->post('/saveEmailField', 'API\FormMakerController@saveEmailField')->name('APISaveEmailField');
Route::middleware('auth:api')->post('/saveUploadField', 'API\FormMakerController@saveUploadField')->name('APISaveUploadField');
Route::middleware('auth:api')->post('/getHTMLFieldContent', 'API\FormMakerController@getHTMLFieldContent')->name('APISGetHTMLFieldContent');
Route::middleware('auth:api')->post('/removeField', 'API\FormMakerController@removeField')->name('APIRemoveField');

//Form Field HTML 
Route::middleware('auth:api')->post('/writing/editFormField', 'API\FormMakerController@editFormField')->name('APIEditFormField');
Route::middleware('auth:api')->post('/writing/updateFormField', 'API\FormMakerController@updateFormField')->name('APIupdateFormField');


//writing api
Route::middleware('auth:api')->post('/writing/updateWritingFields', 'API\FormMakerController@updateWritingFields')->name('APIWritingFormFieldsUpdate');
Route::middleware('auth:api')->post('/writing/getWritingImages', 'API\FormMakerController@getWritingImages')->name('APIGetWritingImages');
Route::middleware('auth:api')->post('/writing/upload', 'API\FormMakerController@upload')->name('APIWritingUpload');
Route::middleware('auth:api')->post('/writing/assignTutor', 'API\FormMakerController@assignTutor')->name('APIWritingAssignTutor');
Route::middleware('auth:api')->post('/writing/getSubmittedWritingPoints', 'API\FormMakerController@getSubmittedWritingPoints')->name('APIGetSubmittedWritingPoints');

Route::middleware('auth:api')->post('/writing/getDropDownOptions', 'API\FormMakerController@getDropDownOptions')->name('APIWritingGetDropDownOptions');
Route::middleware('auth:api')->post('/writing/sortWritingFields', 'API\FormMakerController@sortWritingFields')->name('APIWritingSortWritingFields');


//Writing Entry Checker
Route::middleware('auth:api')->post('/writing/checkCredits', 'API\WritingEntryController@checkCredits')->name('APICheckCredits');
Route::middleware('auth:api')->post('/writing/checkMemberCredits', 'API\WritingEntryController@checkMemberCredits')->name('APICheckMemberCredits');
Route::middleware('auth:api')->post('/writing/sendReloadEmail', 'API\WritingEntryController@sendReloadEmail')->name('APISendReloadEmail');



//[start] member exam  score
Route::middleware('auth:api')->post('/getMemberExamScoreByType', 'API\MemberExamController@getMemberExamScoreByType')->name('APIGetMemberExamScoreByType');
Route::middleware('auth:api')->post('/getMemberExamScoreByPage', 'API\MemberExamController@getMemberExamScoreByPage')->name('APIGetMemberExamScoreByPage');
Route::middleware('auth:api')->post('/getMemberScoreHistory', 'API\MemberExamController@getMemberScoreHistory')->name('APIGetMemberScoreHistory');

Route::middleware('auth:api')->post('/updateMemberExamScore', 'API\MemberExamController@updateMemberExamScore')->name('APIUpdateMemberExamScore');
Route::middleware('auth:api')->post('/deleteMemberExamScore', 'API\MemberExamController@deleteMemberExamScore')->name('APIDeleteMemberExamScore');


//Lastest Exam scores
Route::middleware('auth:api')->post('/getMemberLatestScore', 'API\MemberExamController@getMemberLatestScore')->name('APIGetMemberLatestScore');
Route::middleware('auth:api')->post('/addMemberExamScore', 'API\MemberExamController@addMemberExamScore')->name('APIAddMemberScore');


//[start] member purpose
Route::middleware('auth:api')->post('/getMemberPurposeList', 'API\MemberPurposeController@getMemberPurposeList')->name('APIGetMemberPurposeList');
Route::middleware('auth:api')->post('/updateMemberPurpose', 'API\MemberPurposeController@updateMemberPurpose')->name('APIUpdateMemberPurpose');
Route::middleware('auth:api')->post('/getMemberPurpose', 'API\MemberPurposeController@getMemberPurpose')->name('APIGetMemberPurpose');

//[start] member notes
Route::middleware('auth:api')->post('/getMemberNotes', 'API\MemberNotesController@getMemberNotes')->name('APIGetMemberNotes');
Route::middleware('auth:api')->post('/saveNote', 'API\MemberNotesController@saveNote')->name('APISaveMemberNote');
Route::middleware('auth:api')->post('/updateNote', 'API\MemberNotesController@updateNote')->name('APIUpdateMemberNote');
Route::middleware('auth:api')->post('/deleteNote', 'API\MemberNotesController@deleteNote')->name('APIDeleteMemberNote');

Route::middleware('auth:api')->post('/getMemberLevel', 'API\MemberLevelController@getMemberLevel')->name('APIGetMemberLevel');
Route::middleware('auth:api')->post('/saveMemberLevel', 'API\MemberLevelController@saveMemberLevel')->name('APISaveMemberLevel');



Route::middleware('auth:api')->post('/getTimeManager', 'API\TimeManagerAPIController@get')->name('APIGetTimeManager');
Route::middleware('auth:api')->post('/createTimeManager', 'API\TimeManagerAPIController@create')->name('APICreateTimeManager');
Route::middleware('auth:api')->post('/updateTimeManager', 'API\TimeManagerAPIController@update')->name('APIUpdateTimeManager');
Route::middleware('auth:api')->post('/deleteTimeManager', 'API\TimeManagerAPIController@destroy')->name('APIDeleteTimeManager');



Route::middleware('auth:api')->post('/getTimeManagerProgress', 'API\TimeManagerProgressAPIController@get')->name('APIgetTimeManagerProgress');
Route::middleware('auth:api')->post('/createTimeManagerProgress', 'API\TimeManagerProgressAPIController@store')->name('APICreateTimeManagerProgress');
Route::middleware('auth:api')->post('/updateTimeManagerProgress', 'API\TimeManagerProgressAPIController@update')->name('APIUpdateTimeManagerProgress');
Route::middleware('auth:api')->post('/deleteTimeManagerProgress', 'API\TimeManagerProgressAPIController@destroy')->name('APIDeleteTimeManagerProgress');

Route::middleware('auth:api')->post('/getTimeManagerProgressGraph', 'API\TimeManagerProgressAPIController@getTimeManagerProgressGraph')->name('APIGetTimeManagerProgressGraph');
Route::middleware('auth:api')->post('/getTimeManagerProgressList', 'API\TimeManagerProgressAPIController@getTimeManagerProgressList')->name('APIGetTimeManagerProgressList');



Route::middleware('auth:api')->post('/getQuestions', 'API\QuestionsAPIController@get')->name('APIGetQuesions');
Route::middleware('auth:api')->post('/createQuestions', 'API\QuestionsAPIController@store')->name('APICreateQuesions');
Route::middleware('auth:api')->post('/updateQuestions', 'API\QuestionsAPIController@update')->name('APIUpdateQuesions');
Route::middleware('auth:api')->post('/deleteQuestions', 'API\QuestionsAPIController@destroy')->name('APIDeleteQuesions');

Route::middleware('auth:api')->post('/getAnswers', 'API\AnswersAPIController@get')->name('APIGetAnswers');
Route::middleware('auth:api')->post('/addAnswerStartTime', 'API\AnswersAPIController@addAnswerStartTime')->name('APIAddAnswerStartTime');
Route::middleware('auth:api')->post('/createAnswers', 'API\AnswersAPIController@store')->name('APICreateAnswers');
Route::middleware('auth:api')->post('/postAnswers', 'API\AnswersAPIController@post')->name('APIPostAnswers');
Route::middleware('auth:api')->post('/updateAnswers', 'API\AnswersAPIController@update')->name('APIUpdateAnswers');
Route::middleware('auth:api')->post('/deleteAnswers', 'API\AnswersAPIController@destroy')->name('APIDeleteAnswers');


Route::middleware('auth:api')->post('/getMergedAccounts', 'API\MergeAccountAPIController@get')->name('APIGetMergeAccounts');
Route::middleware('auth:api')->post('/getMergedAccountType', 'API\MergeAccountAPIController@getType')->name('APIGetMergedAccountType');
Route::middleware('auth:api')->post('/createMergedAccount', 'API\MergeAccountAPIController@store')->name('APIAddMergeAccount');
Route::middleware('auth:api')->post('/updateMergedAccount', 'API\MergeAccountAPIController@update')->name('APIUpdateMergeAccount');
Route::middleware('auth:api')->post('/deleteMergedAccount', 'API\MergeAccountAPIController@destroy')->name('APIDeleteMergeAccount');

//merge secondary to main
Route::middleware('auth:api')->post('/mergeSecondaryToMain', 'API\MergeAccountAPIController@mergeSecondaryToMain')->name('APIMergeSecondaryToMain');

//Admin Merging
Route::middleware('auth:api')->post('/createAdminMergedAccount', 'API\MergeAccountAPIController@adminMergedAccount')->name('APIAdminAddMergeAccount');

//miniTest Results
Route::middleware('auth:api')->post('/getMemberMiniTestResult', 'API\MemberMiniTestResultController@get')->name('APIGetMemberMiniTestResults');
Route::middleware('auth:api')->post('/deleteMemberMiniTestResult/{id}', 'API\MemberMiniTestResultController@destroy')->name('APIDeleteMemberMiniTestResults');

//Lesson Slide Pre-selected
Route::middleware('auth:api')->post('/getMemberLessonSelected', 'API\LessonSlideMaterials@getMemberLessonSelected')->name('APIGetLessonMaterialSelected');
Route::middleware('auth:api')->post('/updateSelectedLesson', 'API\LessonSlideMaterials@updateSelectedLesson')->name('APIUpdateSelectedSlidesFolder');

//Lesson Selected 
Route::middleware('auth:api')->post('/saveSelectedLessonSlideMaterial', 'API\LessonSlideMaterials@saveSelectedLessonSlideMaterial')->name('APISaveSelectedLessonSlideMaterial');


//lesson slide materials
Route::middleware('auth:api')->post('/getLessonMaterialSlides', 'API\LessonSlideMaterials@getLessonMaterialSlides')->name('APIGetLessonMaterialSlides');
Route::middleware('auth:api')->post('/getLessonSlideMaterialList', 'API\LessonSlideMaterials@getLessonSlideMaterialList')->name('APIGetLessonSlideMaterialList'); //Folder Listings

//Save Lesson Materials
Route::middleware('auth:api')->post('/saveEmptyCustomSlide', 'API\LessonSlideMaterials@saveEmptyCustomSlide')->name('APIsaveEmptyCustomSlide');

//lesson slide preview (NEW)
Route::middleware('auth:api')->post('/getLessonSelectedPreview', 'API\LessonSlideMaterials@getLessonSelectedPreview')->name('APIGetLessonSelectedPreview');


//Lesson History Controller
Route::middleware('auth:api')->post('/postLessonHistory', 'API\LessonHistoryController@postLessonHistory')->name('APIPostLessonHistory');

//lesson Slide History
Route::middleware('auth:api')->post('/saveLessonSlideHistory', 'API\LessonHistoryController@saveLessonSlideHistory')->name('APISaveLessonHistory');


//File Manager
Route::middleware('auth:api')->post('saveFileNotes', 'API\FileManagerController@saveFileNotes');

//File Upload
Route::middleware('auth:api')->delete('deleteAudio', 'FileUploadController@deleteAudio');

//File Ordering
Route::middleware('auth:api')->post('/saveFileOrder', 'API\FileController@saveFileOrder')->name('saveFileOrder');


//Member Satisfaction Survey
Route::middleware('auth:api')->post('/postSatisfactionSurvey', 'API\MemberSatisfactionSurveyController@postSatisfactionSurvey')->name('APIPostSatisfactionSurvey');
Route::middleware('auth:api')->post('/postMemberFeedback', 'API\MemberFeedbackController@postMemberFeedback')->name('APIMemberFeedback');


