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
use App\Models\SatisfactionSurvey;


use Auth, Config, DateTime;


class LessonHistoryController extends Controller
{

    public function postLessonStartHistory(Request $request, Member $member) 
    {

        //Determine if lesson has been started by looking for the lesson history
        $lessonHistory      = LessonHistory::where('schedule_id', $request->reservation['schedule_id'])->first();
        $isLessonStarted    = ($lessonHistory) ? true : false;

        //$isLessonStarted    = TRUE; //TEST ONLY (TRUE FOR STARTED DEFAULT)
      
        //Booleans Status
        $startTimeInvalid           = false;
        $isUserAbsent               = false;
        $isLessonExpired            = false;
        $isLessonExceedGracePeriod  = false;

        $callWaitingLimit           = 999; //Call waiting Limit (Mark Absent Modal trigger) in Minutes (15 min default)
        $gracePerionInMinutes       = 999; //Grace Period Extion to End Time or Session Expiration (15 min default)

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

        $esiStartTime = ESIDateTimeFormat($startTime);
        $esiEndTime = ESIDateTimeFormat($endTime);
        $esiGracePeriodEndTime = ESIDateTimeFormat($gracePeriodEndTime);

        if ($startTime > $currentTime) {

            //Lesson should not start since the lesson start time is so advance
            $success                = false;  
            $startTimeInvalid       = true;
            $title                  = "Lesson time not started";
            $message                = "<div class='font-weight-bold'>Lesson time ". ($esiStartTime) ." has not started yet</div>";
            $message                .= "<div>please check back again later</div>";

        } else if ($isLessonStarted == false && $currentTime > $gracePeriodEndTime) {

            $success            = false;
            $isLessonExpired    = true;
            $title              = "Expired Lesson";
            $message            = "<div class='font-weight-bold text-left'><font-weight-bold>Lesson $esiStartTime - $esiEndTime has expired</strong></div>";           
            $message           .= "<div class='mt-2 font-weight-bold text-left'>Grace time  $esiGracePeriodEndTime was also exceeded.</div>";
           

        } else if ($isLessonStarted == false && $totalElapsedMinutes > $callWaitingLimit) {

            $success            = false;
            $isUserAbsent       = true;       
            $title              = "Student is absent";    
            $message            = "<div class='mt-2 font-weight-bold text-left'>Student was unable to connect or student is absent</div>";
            $message           .= "<div class='mt-2 font-weight-bold text-left'>Elapsed time is over $callWaitingLimit minutes.</div>";

        } else if ($isLessonStarted == true && $currentTime >= $endTime) { 

            //make this lesson started to "false" to nullify expiration
        
            if ($currentTime >= $gracePeriodEndTime) {
                
                $success                = false;  
                $startTimeInvalid       = true;
                $isLessonExpired        = true;
                $isUserAbsent           = false; //The user was not absent since  tutor confirm that it should started
                $title                  = "Expired Session";    
                $message                = "<div class='font-weight-bold mt-1'>Lesson time expired beyond grace time period, ending session is recommended</div>";
                $message               .= "<div class='mt-1 small'>Lesson Time: $startTime - $endTime </div>";
                $message               .= "<div class='mt-1 small'>Grace Period: ($gracePeriodEndTime)</div>";

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
            'title'                 => $title,
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
    * Starts a lesson for a member.
    *
    * @param Request $request The request object containing lesson data.
    * @param Member $member The member object associated with the lesson.
    * @return JsonResponse The JSON response indicating the success or failure of the operation.
    */    
    public function startLesson(Request $request, Member $member) {

        $reservation        = $request->reservation;
        $isTimerStarted     = $request->isTimerStarted;
        $slidesData         = $request->slidesData;

        $now              = Carbon::now();
        $currentTime      = $now->format('Y-m-d H:i:s');

        $lessonHistory = LessonHistory::where('schedule_id', $reservation['schedule_id'])->first();

        if ($lessonHistory) {

           

            return Response()->json([
                "success"       => true,
                "currentTime"   => $currentTime,
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


    public function postLessonAbsentHistory(Request $request) {    

    
        $scheduleID = $request->reservation['schedule_id'];
        $scheduleItem = ScheduleItem::where('id', $scheduleID)->first();

        if (!$scheduleItem) {

            $message = "<div class='font-weight-bold'>Sorry,  We found an error while updating student records</div>";

            return Response()->json([
                "success"       => false,
                "reservation"   => $request->reservation,
                "message"       => $message . "<div>We can't find lesson schedule and was unable to mark your student absent</div>",
            ]);           
        
        }

        if ($scheduleItem->schedule_status == 'CLIENT_RESERVED' || $scheduleItem->schedule_status == 'CLIENT_RESERVED_B') {       
        
            $lessonData = [
                'schedule_status' => 'CLIENT_NOT_AVAILABLE'
            ];

            $scheduleItem->update($lessonData);

            return Response()->json([
                "success"       => true,
                "reservation"   => $request->reservation,
                "message"       => "Student was successfully marked as absent",
            ]);

        } else {
        
            return Response()->json([
                "success"       => false,
                "reservation"   => $request->reservation,
                "message"       => $message . "<div>Error marking student absent, please try again later</div>",
            ]);        
        }
    }



    public function postLessonEndHistory(Request $request, Member $member) {             

        $reservation            = $request->reservation;
        $isTimerStarted         = $request->isTimerStarted;
        $slidesData             = $request->slidesData;
        $consecutiveSchedules   = $request->consecutiveSchedules;

        //THE FIRST LESSON
        $lessonHistory  = LessonHistory::where('schedule_id', $reservation['schedule_id'])->first();      
     
        if ($lessonHistory) {

               $newHistoryslideIDs = null;

            if (count($consecutiveSchedules) > 1) {


                foreach ($consecutiveSchedules['lessons'] as $key => $lesson) {

                    $consecutiveLessonHistories = LessonHistory::where('schedule_id', $lesson['id'])->where('status', 'NEW')->first();                 

                    if ($consecutiveLessonHistories) {

                        $consecutiveLessonHistories->update([
                            'status'            => 'COMPLETED',
                            'current_slide'     => $request->currentSlide,
                            'total_slides'      => $request->totalSlides,               
                            'time_ended'        => Carbon::now(),                
                        ]);

                    } else {
                    
                        //the consecutive lesson did not found any history we will duplicate it from the first lesson! and update the parent id
                        $newHistory = $lessonHistory->replicate();
                        $arrayReplicatedData = $newHistory->toArray();

                        //UPDATE THE (LESSON HISTORY) table (parent_lesson_id);
                        $arrayReplicatedData['parent_lesson_id'] = $lessonHistory->id;
                        $arrayReplicatedData['schedule_id']      = $lesson['id'];
                        $arrayReplicatedData['status']           = "CS_MERGED";

                        $arrayReplicatedData['additional_notes'] = "consecutive schedule #($key) - PARENT LESSON: " . $lessonHistory->id;                        	
                        $newCreatedModel = LessonHistory::create($arrayReplicatedData);

                        $newHistoryslideIDs[] = $newCreatedModel->id;
                    }

                    //UPDATE THE SCHEDULE TO COMPLETE
                    $scheduleItem = ScheduleItem::where('id', $lesson['id'])->first();

                    if ($scheduleItem) {
                        $scheduleItem->update(['schedule_status' => "COMPLETED"]);            
                    }
                }

            } else {
            
                $lessonHistory->update([
                    'status'            => 'COMPLETED',
                    'current_slide'     => $request->currentSlide,
                    'total_slides'      => $request->totalSlides,               
                    'time_ended'        => Carbon::now(),                
                ]);

                $scheduleItem = ScheduleItem::where('id', $reservation['schedule_id'])->first();
            
                if ($scheduleItem) {
                    $scheduleItem->update(['schedule_status' => "COMPLETED"]);
                }
            }

            return Response()->json([
                "success"               => true,
                "reservation"            => $request->reservation,
                "consecutiveSchedules"  => $consecutiveSchedules,
                "message"               => "Lesson Materials posted successfully."
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


                $scheduleItem = ScheduleItem::where('id', $reservation['schedule_id'])->first();

                if ($scheduleItem) {
                    $scheduleItem->update(['schedule_status' => "COMPLETED"]);            
                }


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

        $response = $lessonSlideHistory->saveSlideHistory($slideIndex, $totalSlides, $reservation, $canvasData, $imageData);

        if ($response->success == true) {
        
            return Response()->json([
                "success"       => $response->success,                
                "message"       => $response->message,              
            ]); 

        } else {
        
            return Response()->json([
                "success"       => false,
                "message"       => "Warning - Lesson has not started yet, Lesson Slide History not saved",
            ]);         
        }

    }     


    /**
    * Retrieves unrated lessons for a given lesson history.
    *
    * @param Request $request The request object containing additional parameters.
    * @param LessonHistory $lessonHistory The lesson history object.
    * @return JsonResponse The JSON response containing the unrated lessons.
    */

    public function getUnratedLessons(Request $request, LessonHistory $lessonHistory) 
    {    
        $userID = $request->userID;
        return $lessonHistory->getMemberLessonsWithNoRatings($userID);
    }

    public function setLessonRating(Request $request) {

        $scheduleID = $request->scheduleId;
        $rating = $request->rating;
        $userID = $request->userID;

        $survey = new SatisfactionSurvey();
        $response = $survey->setRating($scheduleID, $rating);

        return $response;
    
    }
}
