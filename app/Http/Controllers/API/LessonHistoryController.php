<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\lessonHistory;
use App\Models\Member;
use App\Models\User;
use Auth, Config;


class LessonHistoryController extends Controller
{

    public function postStartLessonHistory() {
    
    }

    
    //@description:  this will search for lesson in history and add a history if not found, or update if found
    public function postLessonHistory(Request $request, Member $member) {             

        $reservation = $request->reservation;
        $lessonHistory = lessonHistory::where('schedule_id', $reservation['schedule_id'])->first();

        if ($lessonHistory) 
        {    

            $lessonHistory->update([
                'current_slide'     => $request->currentSlide,
                'total_slides'      => $request->totalSlides,               
                'time_ended'        => Carbon::now(),
                
            ]);

            return Response()->json([
                "success" => true,             
                "reservation" => $request->reservation,
                "message" => "Lesson Materials posted successfully."
            ]); 

        
        } else {

            $lessonHistory = lessonHistory::create([
                'folder_id'         => $request->folder_id,
                'current_slide'     => $request->currentSlide,
                'total_slides'      => $request->totalSlides,
                'schedule_id'       => $reservation['schedule_id'],
                'tutor_id'          => $reservation['tutor_id'],
                'member_id'         => $reservation['member_id'],
                'time_started'      => Carbon::now(),
            ]);
                      

            return Response()->json([
                "success" => false,                
                "message" => "Member Lesson History was not found while ending session,"
            ]); 

        }


    
    }
}
