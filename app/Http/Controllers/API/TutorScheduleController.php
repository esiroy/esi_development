<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\Lesson;

use Auth;
use DB;


class TutorScheduleController extends Controller
{
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       // print_r ($request['tutorData']);
        //print_r ($request['memberData']);

        $lessonData = null;

        try {
                        
            DB::beginTransaction();

            $tutor = $request['tutorData'];
            $member = $request['memberData'];

            if ($request['emailType'] == null) {
                $emailType = 'standard'; //@todo (change this to submitter one from $request) $request['emailType'], 
            } else {
                $emailType = $request['emailType'];
            }

            $lessonData = [
                'scheduled_at'  => $request['scheduled_at'], 
                'duration'      => $request['shiftDuration'],
                'email_type'    => $emailType,     
                'creator_id'    => (isset(Auth::user()->id)) ? Auth::user()->id : null,
                'tutor_id'      => $tutor['tutorID'],
                'startTime'     => $tutor['startTime'],
                'endTime'       => $tutor['endTime'],  
                'member_id'     => $member['id'],                
                'status'        => $request['status']
            ];

            $lesson = Lesson::create($lessonData);      
            DB::commit();

            //get lessons
            $scheduled_at = $request['scheduled_at'];
            $duration = $request['shiftDuration'];

            $tutorLessonsData = $lesson->getLessons($scheduled_at, $duration);
    
            return Response()->json([
                "success"               => true,  
                "message"               => "Lesson has been added",
                "tutorData"             => $request['tutorData'],
                "memberData"            => $request['memberData'], 
                'tutorLessonsData'      => $tutorLessonsData
            ]);

        } catch (\Exception $e) {

            return Response()->json([
                "success"   => false,
                "message"   => "Exception Error Found (Tutor Schedule) : " . $e->getMessage()
            ]);

            DB::rollback();            
        }        

    }
 
    public function deleteTutorSchedule(Request $request)
    {       

        $data = $request['scheduleData'];

        $tutorID         = $data['tutorID'];      
        $startTime       = $data['startTime'];
        $endTime         = $data['endTime'];
        $scheduled_at    = $request['scheduled_at'];
        $duration        = $request['shiftDuration'];           

        $tutor = Tutor::find($tutorID);


        $deleted = Lesson::where('tutor_id', $tutorID)
            ->where('scheduled_at', $scheduled_at)
            ->where('duration', $duration)
            ->where('startTime', $startTime)
            ->where('endTime', $endTime)->delete();

        if ($deleted) {
            $lesson = new Lesson();

            //$shiftDuration  = 25;
            //$dateToday      =  date('Y-m-d');  

            //get lessons
         

            $tutorLessonsData = $lesson->getLessons($scheduled_at, $duration);
    
            return Response()->json([
                "success"       => true,
                "message"       => "lesson deleted",
                "tutorData"     => $data,
                "tutorLessonsData"  => $tutorLessonsData
            ]);
        } else {
            return Response()->json([
                "success"       => false,
                "message"       => "lesson has been alreay deleted"                
            ]);       
        }




    }
}
