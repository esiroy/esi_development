<?php

namespace App\Http\Controllers;

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


class WebRTCVideoController extends Controller
{

    public function index(Request $request,  Folder $folder) 
    {

        if (Auth::user()->roles->contains('title', 'Admin')) {

            return redirect(route('admin.dashboard.index'));

        } else if (Auth::user()->roles->contains('title', 'Tutor')) {

            return redirect(route('admin.webRTC.index'));
        }

        $roomID = $request->get('roomid');
        $reserve = ScheduleItem::where('id', $roomID)->first();

        
        if ($reserve) 
        {

            $reservationData = [
                'schedule_id'       => $reserve->id,
                'tutor_id'          => $reserve->tutor_id,
                'member_id'         => $reserve->member_id,
                'duration'          => $reserve->duration,
                'lesson_time'       => $reserve->lesson_time,
                'lessonTimeRage'    => LessonTimeRange($reserve->lesson_time),
                'schedule_status'   => $reserve->schedule_status
            ];         


            $isLessonFinished = LessonHistory::where('schedule_id', $reserve->id)->where('status', "COMPLETED")->first();

            if ($isLessonFinished) {
            
                $lessonCompleted = true;

              // return Response::view('modules.webRTC.lessonFinished');                
            
            } else {
            
                $lessonCompleted = false;

            }

            
            $userInfo =  Auth::user();

            //get the selected material chosen by users
            $material = MemberSelectedLessonSlideMaterial::where('schedule_id', $reserve->id)->where('user_id', $reserve->member_id)->first();

            if (!$material) {                
                //@note: Auto Folder Selected is false;
                $isFolderSelected   = false;
                $folderID           = $folder->getNextFolderID($reserve->member_id);

                //folder URI
                $folderSegments     = "";
                $folderURLArray     = [];
                $files              = []; 

            } else{
                        
                $isFolderSelected   = true;               
                $folderID           = $material->folder_id;

                //folder URI
                $folderSegments     = Folder::getURLSegments( $material->folder_id, " > ");
                $folderURLArray     = Folder::getURLArray( $material->folder_id);
                $files              = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get(); 

            }

        

            //@note: Lesson history to check surveys, 
            //@note: Blade view will detect if be (MEMBER OR TUTOR) based on the session

            $incompleteLessonHistory = LessonHistory::where('member_id', $reserve->member_id)->where('schedule_id',$reserve->id)->where('status', "INCOMPLETE")->first();

            if ($incompleteLessonHistory) {
            
                $lessonHistory = $incompleteLessonHistory;

            } else {
            
                $lessonHistory = LessonHistory::where('member_id', $reserve->member_id)->where('schedule_id', $reserve->id)->where('status', "NEW")->first();

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
                $lessonSurvey = SatisfactionSurvey::where('schedule_id', $reserve->id)->first();
                
                if ($lessonSurvey) {

                    $feedbackCompleted = true;

                    return Response::view('modules.webRTC.lessonFinished');
                }  

            } else if ($userInfo->user_type == "TUTOR") {       

                //@note:    check if the tutor filled up the member feedback form
                //@note(2): show lesson finished if member submitted member survey
                $memberFeedback = MemberFeedback::where('schedule_id', $reserve->id)->first();

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
                        'broadcaster'=>  $isBroadcaster
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
                                    'broadcaster'=>  $isBroadcaster
                                ];

                } else {

                    return view('errors.500',[
                        'title'     => 'Sorry, Tutor was not found, please contact administrator',
                        'message'   => 'Sorry, Tutor was not found, please contact administrator',
                        'code'      => '500'
                    ]);
                }
            }

            return Response::view('modules.webRTC.index', compact('lessonCompleted', 'feedbackCompleted', 'isFolderSelected', 'lessonHistory', 'roomID', 
                                                        'folderID', 'userInfo', 'recipientInfo', 'reservationData', 'isBroadcaster'))->header('Accept-Ranges', 'bytes');

        } else {

            return view('errors.500',[
                'title'     => 'Schedule not found',
                'message'   => 'Schedule not found',
                'code'      => '500'
            ]);
        }
       
    }
}
