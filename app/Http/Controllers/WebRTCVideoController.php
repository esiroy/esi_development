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
use App\Models\lessonHistory;

use App\Models\SatisfactionSurvey;
use App\Models\MemberFeedback;


class WebRTCVideoController extends Controller
{
    public function index(Request $request) 
    {
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

            $userInfo =  Auth::user();

            //get the selected material chosen by users
            $material = MemberSelectedLessonSlideMaterial::where('schedule_id', $reserve->id)->where('user_id', $reserve->member_id)->first();

            if (!$material) 
            {
                //@todo: message no selected material
                //@todo: get previous if current lesson is finished?
                echo "User has not selected a folder";
                exit();

            } else {  

                //@desc: get the folder materials
                $folderID       = $material->folder_id;
                $folderSegments = Folder::getURLSegments( $material->folder_id, " > ");
                $folderURLArray = Folder::getURLArray( $material->folder_id);
                $files          = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get();            

            }


            $lessonHistory = lessonHistory::where('schedule_id', $reserve->id)->first();

                    
            //check if it has a survey
            if ($lessonHistory) {


                if ($userInfo->user_type == "MEMBER") {       

                    //@todo: check if the member is done with the satisfaction survey
                    $lessonSurvey = SatisfactionSurvey::where('schedule_id', $reserve->id)->first();
                    
                    if ($lessonSurvey) {
                        return Response::view('modules.webRTC.lessonFinished');                
                    } 

                } else if ($userInfo->user_type == "TUTOR") {       

                    //@todo: check if the tutor filled up the member feedback form
                    $memberFeedback = MemberFeedback::where('schedule_id', $reserve->id)->first();

                    if ($memberFeedback) {
                        return Response::view('modules.webRTC.lessonFinished');                
                    } 


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
                        'title'     => 'Recipient not found',
                        'message'   => 'Recipient not found',
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
                        'title'     => 'Recipient not found',
                        'message'   => 'Recipient not found',
                        'code'      => '500'
                    ]);
                }
            }

            return Response::view('modules.webRTC.index', compact('lessonHistory', 'roomID', 'folderID', 'userInfo', 'recipientInfo', 'reservationData', 'isBroadcaster'))->header('Accept-Ranges', 'bytes');

        } else {

            return view('errors.500',[
                'title'     => 'Schedule not found',
                'message'   => 'Schedule not found',
                'code'      => '500'
            ]);
        }
       
    }
}
