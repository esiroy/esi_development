<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\User;
use App\Models\MemberSelectedLessonSlideMaterial;
use App\Models\LessonHistory;
use App\Models\LessonSlideHistory;
use App\Models\ScheduleItem;

use Auth, Config;


class LessonHistoryController extends Controller
{
    /** 
        @description: when user starts a lesson lesson will be MAREKD STARTED
                         OR  the lesson history will be updated TO MARKED TIME ENDED

        @param: $request->reservation       - reservation details
        @param: $request->totalSlides       - total number of slides
        @param: $request->currentSlide      - Current slide
    */
    public function postLessonHistory(Request $request, Member $member) {             

        $reservation        = $request->reservation;
        $isTimerStarted     = $request->isTimerStarted;


        $lessonHistory = LessonHistory::where('schedule_id', $reservation['schedule_id'])->first();

        if ($lessonHistory) {

            $lessonHistory->update([
                'status'            => 'COMPLETED',
                'current_slide'     => $request->currentSlide,
                'total_slides'      => $request->totalSlides,               
                'time_ended'        => Carbon::now(),                
            ]);


            $scheduleItem = ScheduleItem::where('id', $reservation['schedule_id'])->first();

            if ($scheduleItem) {            
                $scheduleItem->update([
                    'schedule_status' => "COMPLETED"
                ]);
            }
            


            return Response()->json([
                "success"       => true,
                "reservation"   => $request->reservation,
                "message"       => "Lesson Materials posted successfully."
            ]); 

        
        } else {

        
            $folderID = null; //reset to null;

            $selectedLesson = MemberSelectedLessonSlideMaterial::where('schedule_id', $reservation['schedule_id'])->first();

            if ($selectedLesson) {
            
                $folderID = $selectedLesson->folder_id;

            } else {
            
                if (isset($request->folder_id)) {                
                    $folderID = $request->folder_id;
                } else {
                    $folderID = null;
                }
            }

  
            /**
                @done: add the status (new, [done], skipped)
                @todo: add note, if created a empty slide
            */                


            $lessonHistory = lessonHistory::create([
                'folder_id'         => $folderID,
                'current_slide'     => $request->currentSlide,
                'total_slides'      => $request->totalSlides,
                'schedule_id'       => $reservation['schedule_id'],
                'tutor_id'          => $reservation['tutor_id'],
                'member_id'         => $reservation['member_id'],
                'time_started'      => Carbon::now(),

                //lesson history new
                'status'            => "NEW"
               
            ]);
                      

            return Response()->json([
                "success" => false,                
                "message" => "Member Lesson History updated"
            ]); 

        }
    }

    public function saveLessonSlideHistory(Request $request, LessonSlideHistory $lessonSlideHistory) 
    {

        $totalSlides        = $request->totalSlides;
        $slideIndex         = $request->slideIndex;
        $reservation        = $request->reservation;
        $canvasData         = json_encode($request->canvasData);
        $imageData          = json_encode($request->imageData);

        $res = $lessonSlideHistory->saveSlideHistory($slideIndex, $totalSlides, $reservation, $canvasData, $imageData);

        if ($res->success == true) {
        
            return Response()->json([
                "success"       => $res->success,                
                "message"       => $res->message,              
            ]); 

        } else {
        
            return Response()->json([
                "success"       => false,
                "message"       => "Warning - Lesson has not started yet, Lesson Slide History not saved",
            ]);         
        }




    }        
}
