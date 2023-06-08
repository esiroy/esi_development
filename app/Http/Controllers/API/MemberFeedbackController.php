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

class MemberFeedbackController extends Controller {

    function postMemberFeedback(Request $request, ReportCard $reportcard , Grade $grade, 
    LessonHistory $lessonHistory, Notes $notes, MemberFeedback $memberFeedback, 
    MemberFeedbackDetails $memberFeedbackDetails) 
    {

        //reservation schedule id
        $scheduleID     = $request->reservation['schedule_id'];
        $tutorID        = $request->reservation['tutor_id'];
        $memberID       = $request->reservation['member_id'];
        $rating         = $request->studentPerformanceRating;
        $material       = json_decode($request->material);
        $lessonStatus   =  $request->lessonStatus;
        $lessonGrade    = $grade->getGrade($rating);
        $feedback        = $request->feedback;  
        $memberNotes    = $request->notes;    


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

         $reportCardCreated      = $reportcard->saveReportCard($reportCardData);


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
