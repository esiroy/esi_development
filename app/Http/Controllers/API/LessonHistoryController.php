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

use Auth, Config, DateTime;


class LessonHistoryController extends Controller
{

    public function postLessonStartHistory(Request $request, Member $member) 
    {

        //Determine if lesson has been started by looking for the lesson history
        $lessonHistory      = LessonHistory::where('schedule_id', $request->reservation['schedule_id'])->first();
        $isLessonStarted    = ($lessonHistory) ? true : false;

        //$isLessonStarted    = true; //TEST ONLY
      
        //Booleans Status
        $startTimeInvalid           = false;
        $isUserAbsent               = false;
        $isLessonExpired            = false;
        $isLessonExceedGracePeriod  = false;

        $callWaitingLimit           = 30; //Call waiting Limit (Mark Absent Modal trigger) in Minutes (15 min default)
        $gracePerionInMinutes       = 30; //Grace Period Extion to End Time or Session Expiration (15 min default)

        //Calcuate duration in milliseconds
        $durationInMilliseconds = minutesToMilliseconds($request->duration);

        $startTime      = Carbon::createFromFormat('Y-m-d H:i:s', trim($request->startTime)); 
        $endTime        = Carbon::createFromFormat('Y-m-d H:i:s', trim($request->endTime));
        $currentTime    = Carbon::now(); 

        //Format Time
        $start      = $startTime->format('Y-m-d H:i:s');
        $end        = $endTime->format('Y-m-d H:i:s');       
        $current    = $currentTime->format('Y-m-d H:i:s');
        $interval   = $currentTime->diff($start);

        //adjust grace end period
        $absentStartTime       = Carbon::parse($start)->addMinutes($callWaitingLimit)->format('Y-m-d H:i:s');
        $gracePeriodEndTime    = Carbon::parse($end)->addMinutes($gracePerionInMinutes)->format('Y-m-d H:i:s');

        /* (theres is no need to format this)
        if ($startTime->format('H') === '00') {
            // Add a day when the hours is 0
            $newStarTime = $startTime->modify('+1 day');
            $start = $newStarTime->format('Y-m-d H:i:s');
        }

        if ($endTime->format('H') === '00') {
            // Add a day when the hours is 0
            $newEndTime = $endTime->modify('+1 day');
            $end = $newEndTime->format('Y-m-d H:i:s');
        }   
        */
        

        //Get Elapsed Time 
        $elapsedMilliseconds    = $interval->format("%f");
        $elapsedDays            = $interval->format("%a");
        $elapsedHours           = $interval->format("%h");
        $elapsedMinutes         = $interval->format("%i");
        $elapsedSeconds         = $interval->format("%s");

        //Get Total Elapsed Time in Minutes
        $totalElapsedMinutes = ($elapsedDays * 24 * 60) +($elapsedHours * 60) + $elapsedMinutes;
        $totalElapsedMilliseconds = minutesToMilliseconds($totalElapsedMinutes) + ($elapsedSeconds * 1000);

        //Remaining Time
        $remaningLessonDurationInMilliseconds   = calculateRemainingMilliseconds($durationInMilliseconds, $totalElapsedMilliseconds);
        $remaningLessonDurationInMinutes        = millisecondsToMinutes($remaningLessonDurationInMilliseconds);

        if ($startTime > $currentTime) {

            //Lesson should not start since the lesson start time is so advance
            $success                = false;  
            $startTimeInvalid       = true;
            $title                  = "Lesson time not started";
            $message                = "<div class='font-weight-bold'>Lesson time $startTime has not started yet</div>";
            $message                .= "<div>please check back again later</div>";

        } else if ($isLessonStarted == false && $currentTime >= $gracePeriodEndTime) {

            $success            = false;
            $isLessonExpired    = true;
            $title              = "Expired Lesson";
            $message            = "Expired Lesson, Grace period time  $gracePeriodEndTime(JST) was given and exceeded.";

        } else if ($isLessonStarted == false && $totalElapsedMinutes >= $callWaitingLimit) {

            $success            = false;
            $isUserAbsent       = true;       
            $title              = "Student is absent";    
            $message            = "Student is absent, Elapsed time is $callWaitingLimit minutes or over";

        } else if ($isLessonStarted == true && $currentTime >= $endTime) { 
            //make this lesson started to "false" to nullify expiration
        
            if ($currentTime >= $gracePeriodEndTime) {
                
                $success                = false;  
                $startTimeInvalid       = true;
                $isLessonExpired        = true;
                $isUserAbsent           = false; //The user was not absent since  tutor confirm that it should started
                $title                  = "Expired Session";    
                $message                = "<div>Lesson time has expired beyond grace time period</div>";
                 $message              .= "<div>Grace Time: ($gracePeriodEndTime)</div>";

            } else {
                            
                $success                = true;  
                $startTimeInvalid       = true;
                $isLessonExpired        = false; //Lesson is now give a grace period of  ($gracePerionInMinutes) / 15 minutes as default
                $isUserAbsent           = false; //The user was not absent since  tutor confirm that it should started

                //Grace time period period is set to true, this will now inform when refreshed
                $isLessonExceedGracePeriod = true;            
                $title                     = "Session About To Expire within $gracePerionInMinutes";
                $message                   = "Lesson time has been given $gracePerionInMinutes minute grace period to finish lesson";
            
            }

        } else {
        
            $success            = true;
            $isUserAbsent       = false;

            $title              = "Active Session, Start Lesson Now";
            $message            = "User is present, Elapsed time is less than $gracePerionInMinutes minutes";
            
        }       

        return Response()->json([                    
            'success'               => $success,
            'title'             => $title,
            'message'               => $message,

            //Determine User Status
            'isLessonStarted'       => $isLessonStarted,
            'isUserAbsent'          => $isUserAbsent,
            'isLessonExpired'       => $isLessonExpired,   
            'isStartTimeInvalid'    => $startTimeInvalid,

            

            //Lesson Time
            'currentDateTime'       => $current,
            'startTime'             => $start,
            'endTime'               => $end,

            //Absent Start Time (15 min after start time)
            'callWaitingLimit'      => $callWaitingLimit,
            'absentStartTime'       => $absentStartTime,
            

            //END TIME GRACE PERIOD
            'isLessonExceedGracePeriod' => $isLessonExceedGracePeriod,
            'graceEnd'                  => $gracePeriodEndTime,
            'gracePerionInMinutes'     => $gracePerionInMinutes,

            //Duration
            'durationInMin'         => $request->duration,
            'durationInMs'          => $durationInMilliseconds,

            //Get Elapsed Time
            'elapsed'               => [
                                        'milliseconds'   => $elapsedMilliseconds,
                                        'days'           => $elapsedDays,
                                        'hours'          => $elapsedHours,
                                        'minutes'        => $elapsedMinutes,
                                        'seconds'        => $elapsedSeconds,
                                    ],

            //Remaining Lesson Duration
            'remaningDurationInMilliseconds'    => $remaningLessonDurationInMilliseconds,
            'remainingDurationInMinutes'        => $remaningLessonDurationInMinutes,

            //Get total Ellapsed 
            'totalElapsedMinutes'        => $totalElapsedMinutes, 
            'totalElapsedMilliseconds'   => $totalElapsedMilliseconds,
        ]); 

       
    }

    /** 
        @description: when user starts a lesson lesson will be MAREKD STARTED
                         OR  the lesson history will be updated TO MARKED TIME ENDED

        @param: $request->reservation       - reservation details
        @param: $request->totalSlides       - total number of slides
        @param: $request->currentSlide      - Current slide
    */
    public function startLesson(Request $request, Member $member) {

        $reservation        = $request->reservation;
        $isTimerStarted     = $request->isTimerStarted;
        $slidesData         = $request->slidesData;

        $lessonHistory = LessonHistory::where('schedule_id', $reservation['schedule_id'])->first();

        if ($lessonHistory) {

            return Response()->json([
                "success"       => true,
                "reservation"   => $request->reservation,
                "message"       => "Lesson History already added."
            ]); 

        
        } else if (!$lessonHistory) {

        
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

            $lessonHistory = lessonHistory::create([
                'folder_id'         => $folderID,
                'current_slide'     => $request->currentSlide,
                'total_slides'      => $request->totalSlides,
                'schedule_id'       => $reservation['schedule_id'],
                'tutor_id'          => $reservation['tutor_id'],
                'member_id'         => $reservation['member_id'],
                'time_started'      => Carbon::now(),

                //lesson history for new 
                'status'            => "NEW"               
            ]);
                      
            if ($lessonHistory) {
                
                foreach($slidesData as $slide) {
                    $created = LessonSlideHistory::create([
                        'slide_index'       => $slide['slideIndex'],
                        'lesson_history_id' => $lessonHistory->id,            
                        'content'           => json_encode($slide['canvasData']),
                        'data'              => json_encode($slide['imageData'])
                    ]); 
                }

            }

            return Response()->json([
                "success" => true,                
                "message" => "Member Lesson History updated"
            ]); 

        } else {

            return Response()->json([
                "success" => false,                
                "message" => "We can't create lesson history"
            ]);         
        }
    }


    public function postLessonEndHistory(Request $request, Member $member) {             

        $reservation        = $request->reservation;
        $isTimerStarted     = $request->isTimerStarted;
        $slidesData         = $request->slidesData;

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

            $lessonHistory = lessonHistory::create([
                'folder_id'         => $folderID,
                'current_slide'     => $request->currentSlide,
                'total_slides'      => $request->totalSlides,
                'schedule_id'       => $reservation['schedule_id'],
                'tutor_id'          => $reservation['tutor_id'],
                'member_id'         => $reservation['member_id'],
                'time_started'      => Carbon::now(),

                //lesson history for new 
                'status'            => "NEW"               
            ]);
                      
            if ($lessonHistory) {
                
                foreach($slidesData as $slide) {

                    
                        
                    $created = LessonSlideHistory::create([
                        'slide_index'       => $slide['slideIndex'],
                        'lesson_history_id' => $lessonHistory->id,            
                        'content'           => json_encode($slide['canvasData']),
                        'data'              => json_encode($slide['imageData'])
                    ]); 

                }

            }

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
