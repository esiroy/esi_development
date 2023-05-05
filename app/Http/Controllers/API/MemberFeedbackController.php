<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MemberFeedback;
use App\Models\MemberFeedbackDetails;
use App\Models\LessonHistory;
use App\Models\ReportCard;
use App\Models\Grade;
use App\Models\Notes;

class MemberFeedbackController extends Controller
{
    //

    function postMemberFeedback(Request $request, Grade $grade, Notes $notes) 
    {
        //reservation schedule id
        $scheduleID     = $request->reservation['schedule_id'];
        $material       = json_decode($request->material);
        //User Details        
        $tutorID        = $request->reservation['tutor_id'];
        $memberID       = $request->reservation['member_id'];
        //star ratings
        $rating         = $request->studentPerformanceRating;
        $lessonGrade    = $grade->getGrade($rating);
        //Feeback Comment
        $feeback            = $request->feedback;        
        //teacher notes
        $memberNotes             = $request->notes;   

        //Save the report card
        $reportCard = ReportCard::create([ 
            'schedule_item_id'  => $scheduleID,          
            'member_id'         => $memberID,        
            'comment'           => $feeback,
            'grade'             => $lessonGrade,
            //Materials
            'lesson_course'     => $material->course,
            'lesson_material'   => $material->material,
            'lesson_subject'    => $material->subject,            
            //Course 
            'course_category_id'    => null,
            'course_item_id'        => null,                
            'lesson_level'          => null,
            //Validity
            'valid'             =>  true,
        ]);

        //Create Notes
        $notesCreated = $notes->saveMemberNote($memberID, $tutorID, $memberNotes);

 
        //get the status "INCOMPLETE" , "COMPLETE"
        $memberFeedback = MemberFeedback::where('schedule_id', $scheduleID)->first();

        if (!$memberFeedback) {

            $feedbackCreated = MemberFeedback::create([
                'schedule_id'       => $scheduleID,
                'member_user_id'    => $memberID,
                'tutor_user_id'     => $tutorID,
                'feedback'          => $feeback,
                //'message'           => $message,
                'is_active'         => true,
            ]);

            //Add the ratings
            $feedbackDetails = MemberFeedbackDetails::create([
                'member_feedback_id'    => $created->id,
                'name'                  => 'student_performance_rating',
                'description'           => "Student Performance Rating",
                'value'                 => $rating,
                'order_id'              => 1,
                'is_active'             => true,
            ]);


            if (!$feedbackCreated) {
                return Response()->json([
                    "success"       => false,
                    "message"       => "Can't create feedback for this lesson, please try again"
                ]);
            }


            /** @todo: UPDATE LESSON HISTORY IF USER SELECTED INCOMPLETE"*/
            $lessonStatus =  $request->lessonStatus;

            if ($lessonStatus == "INCOMPLETE") 
            {
                $lessonHistory = LessonHistory::where('member_id', $memberID)->where('schedule_id', $scheduleID)->where('status', "COMPLETED")->first();

                if ($lessonHistory) {
                    $lessonHistory->update(['status' => "INCOMPLETE"]);
                }
            }



            return Response()->json([
                "success"       => true,
                "message"       => "Member posted a survey successfully",
                "created"       => $created
            ]);

        } else {
        
            return Response()->json([
                "success"       => false,
                "message"       => "You already posted a feedback for this lesson"
            ]);     

        }
     
    }


}
