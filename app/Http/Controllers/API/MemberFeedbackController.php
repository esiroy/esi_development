<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\Models\MemberFeedback;
use App\Models\MemberFeedbackDetails;
use App\Models\LessonHistory;
use App\Models\ReportCard;
use App\Models\Grade;
use App\Models\Notes;
use App\Models\Homework;

class MemberFeedbackController extends Controller {

    function postMemberFeedback(Request $request, ReportCard $reportcard , Grade $grade, 
    LessonHistory $lessonHistory, Notes $notes, MemberFeedback $memberFeedback, 
    MemberFeedbackDetails $memberFeedbackDetails, Homework $homeWork) 
    {

        //reservation schedule id
        $scheduleID     = $request->reservation['schedule_id'];
        $tutorID        = $request->reservation['tutor_id'];
        $memberID       = $request->reservation['member_id'];
        $rating         = $request->studentPerformanceRating;
        $material       = json_decode($request->material);
        $lessonStatus   = $request->lessonStatus;
        $lessonGrade    = $grade->getGrade($rating);
        $feedback       = $request->feedback;  
        $memberNotes    = $request->notes;    
        $consecutiveSchedules = $request->consecutiveSchedules;



        $isReplicated = $homeWork->replicateHomework($scheduleID, $consecutiveSchedules);

        $notesCreated = $notes->saveMemberNote($memberID, $tutorID, $memberNotes); 

        if (isset($consecutiveSchedules['lessons'])) {
       
            foreach ($consecutiveSchedules['lessons'] as $key => $lesson) 
            {
                $nextScheduleID = $lesson['id'];

                $reportCardData = [
                    'schedule_item_id'  => $nextScheduleID,          
                    'member_id'         => $memberID,        
                    'comment'           => $feedback,
                    'grade'             => $lessonGrade,
                    //Materials
                    'lesson_course'     => $material->course ?? "",
                    'lesson_material'   => $material->material ?? "",
                    'lesson_subject'    => $material->subject ?? "",
                    //Course 
                    'course_category_id'    => null,
                    'course_item_id'        => null,                
                    'lesson_level'          => null,
                    //Validity
                    'valid'             =>  true,            
                ];
                $reportCardCreated      = $reportcard->saveReportCard($reportCardData);        

                $feedbackData = [
                    'schedule_id'       => $nextScheduleID,
                    'member_user_id'    => $memberID,
                    'tutor_user_id'     => $tutorID,
                    'feedback'          => $feedback,
                    'is_active'         => true,        
                ];
        
                $memberFeedbackCreated  = $memberFeedback->saveFeedback($feedbackData);

                $feedbackDetailData = [
                    'name'                  => 'student_performance_rating',
                    'description'           => "Student Performance Rating",
                    'value'                 => $rating,
                    'order_id'              => 1,
                    'is_active'             => true,        
                ];

                        
                $historyUpdated         = $lessonHistory->updateLessonStatus($lessonStatus, $nextScheduleID, $memberID);                       
                $memberFeedbackDetailsCreated = $memberFeedbackDetails->saveMemberFeedbackDetails($memberFeedbackCreated->id, $feedbackDetailData);

            } //end foreach

         

            return Response()->json([
                "success"       => true,
                "consecutiveSchedules" =>  $consecutiveSchedules,
                "message"       => "Member posted a survey for consecutive lessons successfully",
            ]);    

        } else {

            $reportCardData = [
                'schedule_item_id'  => $scheduleID,          
                'member_id'         => $memberID,        
                'comment'           => $feedback,
                'grade'             => $lessonGrade,
                //Materials
                'lesson_course'     => $material->course ?? "",
                'lesson_material'   => $material->material ?? "",
                'lesson_subject'    => $material->subject ?? "",
                //Course 
                'course_category_id'    => null,
                'course_item_id'        => null,                
                'lesson_level'          => null,
                //Validity
                'valid'             =>  true,            
            ];

            $feedbackData = [
                'schedule_id'       => $scheduleID,
                'member_user_id'    => $memberID,
                'tutor_user_id'     => $tutorID,
                'feedback'          => $feedback,
                'is_active'         => true,        
            ];
    
            $feedbackDetailData = [
                'name'                  => 'student_performance_rating',
                'description'           => "Student Performance Rating",
                'value'                 => $rating,
                'order_id'              => 1,
                'is_active'             => true,        
            ];
    
            $feedback = $memberFeedback->where('schedule_id', $scheduleID)->first();
    
            if ($feedback) {
    
                return Response()->json([
                    "success"       => false,
                    "message"       => "You already posted a feedback for this lesson"
                ]);
            } 
    
            DB::beginTransaction();
    
            try {            
    
                $reportCardCreated      = $reportcard->saveReportCard($reportCardData);
                $notesCreated           = $notes->saveMemberNote($memberID, $tutorID, $memberNotes);
                $historyUpdated         = $lessonHistory->updateLessonStatus($lessonStatus, $scheduleID, $memberID);
    
                $memberFeedbackCreated  = $memberFeedback->saveFeedback($feedbackData);
                $memberFeedbackDetailsCreated = $memberFeedbackDetails->saveMemberFeedbackDetails($memberFeedbackCreated->id, $feedbackDetailData);
    
                DB::commit();
    
                return Response()->json([
                    "success"       => true,
                    "message"       => "Member posted a survey successfully",
                ]);
    
            } catch (\Exception $e) {
    
                DB::rollBack();
    
    
                return Response()->json([
                    "success"       => false,
                    "message"       => $e->getMessage(),
                    "errorLine"     => $e->getLine()
                ]);                    
            }

        }
    }


    function testConsecutiveLesson(Request $request, Homework $homeWork) {
    
        $scheduleID     = $request->reservation['schedule_id'];

        $consecutiveSchedules = $request->consecutiveSchedules;

        $isReplicated = $homeWork->replicateHomework($scheduleID, $consecutiveSchedules);

        if ($isReplicated ) {
            
            return Response()->json([
                "success"       => true,
                "consecutiveSchedules"       => $consecutiveSchedules,
            ]);  

        } else {
        
            return Response()->json([
                "success"       => false,
                "consecutiveSchedules"       => $consecutiveSchedules,
            ]); 

        }


     

    }

}
