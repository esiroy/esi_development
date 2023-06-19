<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Response;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Member;
use App\Models\ScheduleItem;
use App\Models\Folder;
use App\Models\File;
use App\Models\MemberSelectedLessonSlideMaterial;
use App\Models\LessonHistory;

use App\Models\SatisfactionSurvey;
use App\Models\MemberFeedback;



class TutorWebRTCVideoController extends Controller
{
    public function index(Request $request,  Folder $folder, ScheduleItem $scheduleItem) 
    {


        $roomID = $request->get('roomid');
        $reserve = ScheduleItem::where('id', $roomID)->first();

        if ($reserve) {

            $scheduleID =  $reserve->id;

            $userInfo =  Auth::user();

            $reservationData = [
                'schedule_id'       => $scheduleID,
                'tutor_id'          => $reserve->tutor_id,
                'member_id'         => $reserve->member_id,
                'duration'          => $reserve->duration,
                'lesson_time'       => $reserve->lesson_time,
                'lessonTimeRage'    => LessonTimeRange($reserve->lesson_time),
                'schedule_status'   => $reserve->schedule_status
            ];         

            

 

            //detect if merged
            $merged = LessonHistory::where('member_id', $reserve->member_id)
                                ->where('tutor_id',$reserve->tutor_id)
                                ->where('schedule_id',$scheduleID)
                                ->where('status', "CS_MERGED")
                                ->orderBy('id', 'DESC')
                                ->first();

            if ($merged) {
            
                //merged is set as the lesson
                $startedLesson = $merged;

                //get the parent
                $parentHistoryID  = $merged->parent_lesson_id;
                $parentLessonhistory = LessonHistory::where('id', $parentHistoryID)->first();

                if ($parentLessonhistory) {                

                    //NOTE:!!! ASSIGN THE NEW LESSON SCHUDULE ID for the child
                    $scheduleID = $parentLessonhistory->schedule_id;

                    
                    $reserve = ScheduleItem::where('id', $scheduleID)->first();
                    if ($reserve->schedule_status == "COMPLETED") {
                        $isLessonCompleted = true;                
                    } else {            
                        $isLessonCompleted = false;
                    }
                }
            

            } else {
            
                //detect if lesson has started.
                $startedLesson = LessonHistory::where('member_id', $reserve->member_id)
                        ->where('tutor_id',$reserve->tutor_id)
                        ->where('schedule_id',$scheduleID)
                        ->where('status', "NEW")
                        ->orderBy('id', 'DESC')
                        ->first();

                    

                if ($reserve->schedule_status == "COMPLETED") {
                    $isLessonCompleted = true;                
                } else {            
                    $isLessonCompleted = false;
                }
                                        
            }
    
            if ($startedLesson) {              
                $isLessonStarted = true;
            } else {                
                $isLessonStarted = false;
            }

           
            



            //get the selected material chosen by users
            $material = MemberSelectedLessonSlideMaterial::where('schedule_id', $scheduleID)->where('user_id', $reserve->member_id)->first();

            if (!$material) {     

                //No Materials Selected
                $isFolderSelected   = false;
                $folderID           = $folder->getNextFolderID($reserve->member_id);

                if ($folderID) {

                    $folderSegments     = Folder::getURLSegments($folderID, " > ");
                    $folderURLArray     = Folder::getURLArray($folderID);
                    $files              = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get();   

                } else {

                    //folder URI
                    $folderSegments     = "";
                    $folderURLArray     = [];
                    $files              = []; 

                }


            } else{

               // echo "materials has been selected";
                        
                $isFolderSelected   = true;               
                $folderID           = $material->folder_id;

                //folder URI
                $folderSegments     = Folder::getURLSegments( $material->folder_id, " > ");
                $folderURLArray     = Folder::getURLArray( $material->folder_id);
                $files              = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get(); 

               
            }


            



            //@note: Lesson history to check surveys, 
            //@note: Blade view will detect if be (MEMBER OR TUTOR) based on the session

			$lessonCompleted = LessonHistory::where('member_id', $reserve->member_id)->where('schedule_id',$scheduleID)->where('status', "COMPLETED")->first();

            $incompleteLessonHistory = LessonHistory::where('member_id', $reserve->member_id)->where('schedule_id',$scheduleID)->where('status', "INCOMPLETE")->first();

            if ($incompleteLessonHistory) {            
                $lessonHistory = $incompleteLessonHistory;
            } else {            
                $lessonHistory = LessonHistory::where('member_id', $reserve->member_id)->where('schedule_id', $scheduleID)->where('status', "NEW")->first();
                if (!$lessonHistory) {
                    $lessonHistory = null;
                }
            }





            //@note: check if session of a user has a survey
            //@note: show lesson finshed View if user has finshed the survey, or the page will persist the survey modal via (Vue Modal)
                $feedbackCompleted = false;


            if ($userInfo->user_type == "MEMBER") {       

                //@note(1): check if the member is done with the satisfaction survey, 
                //@note(2): show lesson finished if member submitted a satisfaction survey
                $lessonSurvey = SatisfactionSurvey::where('schedule_id', $scheduleID)->first();
                
                if ($lessonSurvey) {

                    $feedbackCompleted = true;

                    return Response::view('modules.webRTC.lessonFinished');
                }  

            } else if ($userInfo->user_type == "TUTOR") {       

                //@note:    check if the tutor filled up the member feedback form
                //@note(2): show lesson finished if member submitted member survey
                $memberFeedback = MemberFeedback::where('schedule_id', $scheduleID)->first();

                if ($memberFeedback) {

                    $feedbackCompleted = true;

                    return Response::view('modules.webRTC.lessonFinished');                
                }
            }            
            


            //get the tutor info if the logged in user is tutor
            if ($userInfo->user_type == "TUTOR") {       

                $userInfo = (Auth::user()->tutorInfo);                
                $isBroadcaster = 'true';

                //get the recipient based on the reservation data (member is now the recipient)
                $user = User::find($reserve->member_id);

                if ($user) {

                    $recipientInfo = [
                        'userid'=> $user->id,
                        'username'=> $user->username,
                        'nickname'=> $user->memberInfo->nickname,            
                        'type'=> $user->user_type, 
                        'broadcaster'=>  $isBroadcaster,
                        'image'=>  $user->image(),
                    ];

                } else {
                
                    return view('errors.500',[
                        'title'     => 'Member was not found on our records',
                        'message'   => 'Member was not found on our records',
                        'code'      => '500'
                    ]);                
                
                }

            } else if ($userInfo->user_type == "MEMBER") {      

                $userInfo = (Auth::user()->memberInfo);
                
                $isBroadcaster = 'false';

                //get the recipient based on the reservation
                $tutor = Tutor::where('user_id', $reserve->tutor_id)->first();

                if ($tutor) {

                    $recipientInfo = [
                                    'userid'=> $tutor->user_id,
                                    'username'=> $tutor->user->username,
                                    'nickname'=> $tutor->user->firstname,            
                                    'type'=> $tutor->user->user_type, 
                                    'broadcaster'=>  $isBroadcaster,
                                    'image'=>  $tutor->image(),
                                ];

                } else {

                    return view('errors.500',[
                        'title'     => 'Sorry, Tutor was not found, please contact administrator',
                        'message'   => 'Sorry, Tutor was not found, please contact administrator',
                        'code'      => '500'
                    ]);
                }
            }
            
           // $currentDateTime = date("Y-m-d H:i:s");
            //echo "Server Time: " .$currentDateTime;

  

            //Get Consecutive Lessons
            $lessons             = $scheduleItem->getConsecutiveLessons($scheduleID);
            $consecutiveDuration = $scheduleItem->getConsecutiveLessonDuration($lessons);

            

            $consecutiveSchedules     = [                                        
                                        'lessons'   => $lessons,
                                        'duration'  => $consecutiveDuration
                                    ];


            return Response::view('modules.webRTC.index', compact('roomID', 'lessonCompleted', 'isLessonStarted', 'isLessonCompleted', 
                                                            'consecutiveSchedules', 'feedbackCompleted', 'isFolderSelected', 'lessonHistory', 
                                                            'folderID', 'folderURLArray',
                                                            'userInfo', 'recipientInfo', 'reservationData', 'isBroadcaster')
                                                        )->header('Accept-Ranges', 'bytes');

        } else {

            return view('errors.500',[
                'title'     => 'Schedule not found',
                'message'   => 'Schedule not found',
                'code'      => '500'
            ]);
        }
       
    }
}
