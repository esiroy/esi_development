<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Response;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Member;
use App\Models\ScheduleItem;


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


            //get the tutor info if the logged in user is tutor

            if ($userInfo->user_type == "TUTOR") {       

                $userInfo = (Auth::user()->tutorInfo);                
                $isBroadcaster = 'true';

                //get the recipient based on the reservation data (member is now the recipient)
                $user = User::find($reserve->member_id);

                if ($user) {
                    
                    $recipient = [
                        'userid'=> $user->id,
                        'username'=> $user->username,
                        'nickname'=> $user->user_id,            
                        'type'=> 'TUTOR', 
                        'broadcaster'=>  $isBroadcaster
                    ];

                }



                /*
                if ($recipient){
                    echo "Member recipient found";
                } else {
                    echo "Member recipient not found :" .$reserve->member_id ;
                }*/

            } else if ($userInfo->user_type == "MEMBER") {      

                $userInfo = (Auth::user()->memberInfo);

                
                $isBroadcaster = 'false';

                //get the recipient based on the reservation
                $tutor = Tutor::find($reserve->tutor_id);

                echo $tutor->id;

                if ($tutor) {

                    $recipient = json_encode([
                                    'userid'=> $tutor->user_id,
                                    'username'=> $tutor->username,
                                    'nickname'=> $tutor->user,            
                                    'type'=> 'TUTOR', 
                                    'broadcaster'=>  $isBroadcaster
                                ]);

                } else {
                    //show error
                }
               
            }



             return Response::view('modules.webRTC.index', compact('roomID', 'userInfo', 'recipient', 'reservationData', 'isBroadcaster'))->header('Accept-Ranges', 'bytes');


        } else {
            return view('errors.500',[
                'title'     => 'Schedule not found',
                'message'   => 'Schedule not found',
                'code'      => '500'
            ]);
        }
       
    }
}
