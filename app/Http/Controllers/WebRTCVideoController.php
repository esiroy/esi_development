<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleItem;
use Auth, Response;

class WebRTCVideoController extends Controller
{
    public function index(Request $request) 
    {
      

        

        $roomID = $request->get('roomid');

        $reserve = ScheduleItem::where('id', $roomID)->first();
        
        if ($reserve) {
        
        
            $reservationData = [
                'schedule_id'       => $reserve->id,
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

            } else if ($userInfo->user_type == "MEMBER") {
                
                $userInfo = (Auth::user()->memberInfo);
                $isBroadcaster = 'false';
              

            }
        }

        

        return Response::view('modules.webRTC.index', compact('roomID', 'userInfo', 'reservationData', 'isBroadcaster'))->header('Accept-Ranges', 'bytes');

        //return view('modules.webRTC.index', compact('roomID', 'userInfo', 'reservationData', 'isBroadcaster'));
    }
}
