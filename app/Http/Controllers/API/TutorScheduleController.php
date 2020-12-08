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
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) 
    {      
        $lessonData = null;


        try {                        
            DB::beginTransaction();
            $tutor = $request['tutorData'];
            $member = $request['memberData'];


            if ( $request['status'] == 'Tutor Cancelled') 
            {
                $emailType =  $request['cancelationType'];
            } 
            else if ($request['status'] == 'Client Reserved' || $request['status'] == 'Client Reserved B') 
            {
                $emailType =  $request['reservationType'];
            } else {
                $emailType = null;
            }

            $lessonData = [
                'scheduled_at'  => $request['scheduled_at'], 
                'duration'      => $request['shiftDuration'],
                'email_type'    => $emailType,     
                'creator_id'    => (isset(Auth::user()->id)) ? Auth::user()->id : null,
                'tutor_id'      => $tutor['tutorID'],
                'start_time'     => $tutor['startTime'],
                'end_time'       => $tutor['endTime'],  
                'member_id'     => $member['id'],                
                'status'        => $request['status']
            ];

            //find lesson and update
            $lesson = Lesson::where('tutor_id', $tutor['tutorID'])                            
                            ->where('duration',  $request['shiftDuration'])
                            ->where('start_time', $tutor['startTime'])
                            ->where('end_time',  $tutor['endTime'])
                            ->where('scheduled_at', $request['scheduled_at'])
                            ->first();        

            $lesson->update($lessonData);      
            DB::commit();

            //get lessons
            $lesson = new Lesson();
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
     
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lessonData = null;

        try {                        
            DB::beginTransaction();
            $tutor = $request['tutorData'];
            $member = $request['memberData'];


            if ( $request['status'] == 'Tutor Cancelled') 
            {
                $emailType =  $request['cancelationType'];
            } 
            else if ($request['status'] == 'Client Reserved' || $request['status'] == 'Client Reserved B') 
            {
                $emailType =  $request['reservationType'];
            } else {
                $emailType = null;
            }

            $lessonData = [
                'scheduled_at'  => $request['scheduled_at'], 
                'duration'      => $request['shiftDuration'],
                'email_type'    => $emailType,     
                'creator_id'    => (isset(Auth::user()->id)) ? Auth::user()->id : null,
                'tutor_id'      => $tutor['tutorID'],
                'start_time'     => $tutor['startTime'],
                'end_time'       => $tutor['endTime'],  
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
        
        $deleted = Lesson::where('tutor_id', $tutorID)
            ->where('scheduled_at', $scheduled_at)
            ->where('duration', $duration)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)->delete();  

        //$tutor = Tutor::find($tutorID);

        if ($deleted) {
            $lesson             = new Lesson();
            $tutorLessonsData   = $lesson->getLessons($scheduled_at, $duration);

            return Response()->json([
                "success"           => true,
                "message"           => "lesson deleted",
                "tutorData"         => $data,
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
