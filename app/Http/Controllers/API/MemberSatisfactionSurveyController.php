<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SatisfactionSurvey;
use App\Models\SatisfactionSurveyDetails;

class MemberSatisfactionSurveyController extends Controller
{
    function postSatisfactionSurvey(Request $request) 
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
        //$generalCourseRating            = $request->generalCourseRating;
        //$studentSelfPerformanceRating   = $request->studentSelfPerformanceRating;
        $teacherPerformanceRating        = $request->teacherPerformanceRating;

        $lessonSurvey = SatisfactionSurvey::where('schedule_id', $scheduleID)->first();

        if (!$lessonSurvey) {

            $created = SatisfactionSurvey::create([
                'schedule_id'       => $scheduleID,
                'member_user_id'    => $memberID,
                'tutor_user_id'     => $tutorID,
                'feedback'          => $feeback,
                'message'           => $message,
                'is_active'         => true,
            ]);

            /*
            //Add the ratings
            $rating1 = SatisfactionSurveyDetails::create([
                'lesson_survey_id'  => $created->id,
                'name'              => 'general_course_ratings',
                'description'       => "General Course Ratings",
                'value'             => $generalCourseRating,
                'order_id'          => 1,
                'is_active'         => true,
            ]);

            	
            $rating2 = SatisfactionSurveyDetails::create([
                'lesson_survey_id'  => $created->id,
                'name'              => 'student_self_performance_rating',
                'description'       => "Student Self Performance Rating",
                'value'             => $studentSelfPerformanceRating,
                'order_id'          => 2,
                'is_active'         => true,
            ]);
            */

            $rating3 = SatisfactionSurveyDetails::create([
                'lesson_survey_id'  => $created->id,
                'name'              => 'teacher_performace_rating',
                'description'       => "Teacher Performace Rating",
                'value'             => $teacherPerformanceRating,
                'order_id'          => 3,
                'is_active'         => true,
            ]);  
            	


            return Response()->json([
                "success"       => true,
                "message"       => "Member posted a survey successfully",
                "created"       => $created
            ]);

        } else {

            return Response()->json([
                "success"       => false,
                "message"       => "You already posted a survey for this lesson"
            ]);        
        }




    }
}
