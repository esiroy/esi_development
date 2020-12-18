<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


use App\Models\Lesson;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Shift;
use App\Models\Member;
use App\Models\Status;


use Gate;
use Validator;
use Input;
use DB;
use Auth;

class LessonController extends Controller
{
    
    public $timeSlots;

    public function __construct() 
    {
        $this->middleware('auth'); //->except();

        $this->timeSlots = array(
            ['id'=> 1, 'startTime'=> '10:00', 'endTime'=> '11:00'],
            ['id'=> 2, 'startTime'=> '10:30', 'endTime'=> '11:30'],
            ['id'=> 3, 'startTime'=> '11:00', 'endTime'=> '12:00'],
            ['id'=> 4, 'startTime'=> '11:30', 'endTime'=> '12:30'],
            ['id'=> 5, 'startTime'=> '12:00', 'endTime'=> '13:00'],
            ['id'=> 6, 'startTime'=> '12:30', 'endTime'=> '13:30'],
            ['id'=> 7, 'startTime'=> '13:00', 'endTime'=> '14:00'],
            ['id'=> 8, 'startTime'=> '13:30', 'endTime'=> '14:30'],
            ['id'=> 9, 'startTime'=> '14:00', 'endTime'=> '15:00'],
            ['id'=> 10, 'startTime'=> '14:30', 'endTime'=> '15:30'],
            ['id'=> 11, 'startTime'=> '15:00', 'endTime'=> '16:00'],
            ['id'=> 12, 'startTime'=> '15:30', 'endTime'=> '16:30'],
            ['id'=> 13, 'startTime'=> '16:00', 'endTime'=> '17:00'],
            ['id'=> 14, 'startTime'=> '16:30', 'endTime'=> '17:30'],
            ['id'=> 15, 'startTime'=> '17:00', 'endTime'=> '18:00'],
            ['id'=> 16, 'startTime'=> '17:30', 'endTime'=> '18:30'],
            ['id'=> 17, 'startTime'=> '18:00', 'endTime'=> '19:00'],
            ['id'=> 18, 'startTime'=> '18:30', 'endTime'=> '19:30'],
            ['id'=> 19, 'startTime'=> '19:00', 'endTime'=> '20:00'],
            ['id'=> 20, 'startTime'=> '19:30', 'endTime'=> '20:30'],
            ['id'=> 21, 'startTime'=> '20:00', 'endTime'=> '21:00'],
            ['id'=> 22, 'startTime'=> '20:30', 'endTime'=> '21:30'],
            ['id'=> 23, 'startTime'=> '21:00', 'endTime'=> '22:00'],
            ['id'=> 24, 'startTime'=> '21:30', 'endTime'=> '22:30'],
            ['id'=> 25 , 'startTime'=> '22:00', 'endTime'=> '23:00'],
            ['id'=> 26 , 'startTime'=> '22:30', 'endTime'=> '23:30'],
            ['id'=> 27 , 'startTime'=> '23:00', 'endTime'=> '24:00'],
            ['id'=> 28 , 'startTime'=> '23:30', 'endTime'=> '24:30']
        );                 
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lesson $lesson, Request $request)
    {
        if(Gate::allows('admin_lesson_scheduler_access')) 
        {
            //$status = Status::all();      
            
            //default on load without any parameters
            if (isset($request['inputDate'])) {
                $dateToday = date('Y-m-d', strtotime($request['inputDate']));
                $year           = date('Y', strtotime($request['inputDate']));
                $month           = date('m', strtotime($request['inputDate']));
                $day           = date('d', strtotime($request['inputDate']));
            } else {           
                $dateToday      =  date('Y-m-d');
                $year           = date('Y');
                $month           = date('m');
                $day           = date('d');
            }      
    
            if (isset($request['shift_duration'])) {
                $shiftDuration  = $request['shift_duration'];
            } else {
                $shiftDuration  = 25;
            }
          

    
            //get the members
            /*
            $members = Member::join('users', 'users.id', '=', 'members.user_id')
            ->join('attributes', 'attributes.id', '=', 'members.member_attribute_id')
            ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name, attributes.name as attribute"))
            ->get();
            */
            //search the ID
            $shift  = Shift::where("value", $shiftDuration)->first();    
            //get tutors for this shift id
            $tutors = Tutor::where('shift_id', $shift->id)->get();   
            $members = Member::join('users', 'users.id', '=', 'members.user_id')
                ->select('members.*','users.first_name', 'users.last_name')
                ->get();
           
    
          
            $lessons = $lesson->getLessons($dateToday, $shiftDuration);    
            
            if (count($lessons) == 0) {
                $lessons = (object) ['0' => null];
            }
    
            return view('admin.modules.lesson.index', compact('dateToday', 'year', 'month', 'day', 'shiftDuration', 'tutors', 'members', 'lessons'));

        } 
        else if (Gate::allows('tutor_lesson_scheduler_access'))
        {
            //time slots
            $timeSlots = $this->timeSlots;

            //date of the query
            if (isset($request['dateFrom']) || isset($request['dateTo'])) 
            {                
                $dateFrom = date('Y-m-d', strtotime($request['dateFrom']));
                $dateTo = date('Y-m-d', strtotime($request['dateTo']));
            }
            else
            {

                //$dateFrom = "2020-12-01";
                //$dateTo   = "2020-12-25";

                $dateFrom = date('Y-m-d');
                $dateTo   = date('Y-m-d', strtotime($dateFrom ." + 5 day"));                
            }


            $from = strtotime($dateFrom);
            $to = strtotime($dateTo);

            //differencial computations
            $datediff = $to - $from;

            //actual number of days (integer)
            $lessonDays = round($datediff / (60 * 60 * 24));

            //@todo: Tutor - get the lessons of the current user only since this is tutor  
            $tutor = Tutor::where('user_id', Auth::user()->id)->first();
            
            $lessons = $lesson->getTutorLessons($tutor->id, $dateFrom, $dateTo);

            return view('admin.modules.tutor.lessons', compact('tutorLessons',  'dateFrom', 'dateTo', 'lessonDays', 'timeSlots', 'lessons'));
            
        } else {
            echo "admin_lesson_scheduler_access or tutor_lesson_scheduler_access is disallowed";
        }

      
    }

  
}
