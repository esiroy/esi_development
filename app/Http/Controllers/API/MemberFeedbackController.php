<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MemberFeedback;
use App\Models\MemberFeedbackDetails;

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
        $generalCourseRating                    = $request->generalCourseRating;
        $studentPerformanceRating            =   $request->studentPerformanceRating;
        $teacherSelfPerformanceRating        = $request->teacherSelfPerformanceRating;

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
            /*
            $rating1 = MemberFeedbackDetails::create([
                'member_feedback_id'  => $created->id,
                'name'              => 'general_course_ratings',
                'description'       => "General Course Ratings",
                'value'             => $generalCourseRating,
                'order_id'          => 1,
                'is_active'         => true,
            ]);
           */

            	
            $rating2 = MemberFeedbackDetails::create([
                'member_feedback_id'  => $created->id,
                'name'              => 'student_performance_rating',
                'description'       => "Student Performance Rating",
                'value'             => $studentPerformanceRating,
                'order_id'          => 2,
                'is_active'         => true,
            ]);


            /*
            $rating3 = MemberFeedbackDetails::create([
                'member_feedback_id'  => $created->id,
                'name'              => 'teacher_self_performace_rating',
                'description'       => "Teacher Self Performace Rating",
                'value'             => $teacherSelfPerformanceRating,
                'order_id'          => 3,
                'is_active'         => true,
            ]);  
            */


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
