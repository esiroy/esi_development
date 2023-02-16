<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MemberFeedback;
use App\Models\MemberFeedbackDetails;
use App\Models\LessonHistory;

class MemberFeedbackController extends Controller
{
    //

    function postMemberFeedback(Request $request) 
    {

        //reservation schedule id
        $scheduleID  = $request->reservation['schedule_id'];

        //User Details        
        $tutorID        = $request->reservation['tutor_id'];
        $memberID       = $request->reservation['member_id'];

        //content, feeback texts
        $feeback = $request->feeback;
        $message = $request->message;

        //Stars Ratings  
        $studentPerformanceRating   =   $request->studentPerformanceRating;

        //get the status "INCOMPLETE" , "COMPLETE"

        $memberFeedback = MemberFeedback::where('schedule_id', $scheduleID)->first();

        if (!$memberFeedback) {

            $created = MemberFeedback::create([
                'schedule_id'       => $scheduleID,
                'member_user_id'    => $memberID,
                'tutor_user_id'     => $tutorID,
                'feedback'          => $feeback,
                'message'           => $message,
                'is_active'         => true,
            ]);

            //Add the ratings
            $rating2 = MemberFeedbackDetails::create([
                'member_feedback_id'  => $created->id,
                'name'              => 'student_performance_rating',
                'description'       => "Student Performance Rating",
                'value'             => $studentPerformanceRating,
                'order_id'          => 2,
                'is_active'         => true,
            ]);


            /** @todo: UPDATE "COMPLETE"  the "INCOMPLETE" IF USER SELECTED "INCOMPLETE" since user was not complete */
            $lessonStatus =  $request->lessonStatus;

            if ($lessonStatus == "INCOMPLETE") {

                $lessonHistory = LessonHistory::where('member_id', $memberID)
                                            ->where('schedule_id', $scheduleID)
                                            ->where('status', "COMPLETED")->first();

                if ($lessonHistory) {

                    $lessonHistory->update([                    
                        'status' => "INCOMPLETE"
                    ]);               
                
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
