<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


use App\Models\ScheduleItem;
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
use Carbon\Carbon;



class ScheduleItemController extends Controller
{
    
    public $timeSlots;

 
    public function __construct(Request $request) {

        //$this->middleware('auth');

        $this->middleware(function ($request, $next) {
            //authenticated by has no "admin_access" in his role attached
            //@do: redirect to home (authenticated member will be his view)
            if (Gate::denies('admin_access')) {
                return redirect(route('home'));
            }
            return $next($request);           
        });


        //$this->middleware('auth'); //->except();
        $this->setTimeSlots();
    }
    
    
    public function setTimeSlots() 
    {
        
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
            ['id'=> 28 , 'startTime'=> '23:30', 'endTime'=> '24:30'],

           
        );                 
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if(Gate::allows('admin_lesson_scheduler_access')) 
        {
            $nextDay      = date('Y-m-d', strtotime($request['inputDate'] ." + 1 day"));  

            //default on load without any parameters
            if (isset($request['inputDate'])) {
                $dateToday      = date('Y-m-d', strtotime($request['inputDate']));                
                $year           = date('Y', strtotime($request['inputDate']));
                $month          = date('m', strtotime($request['inputDate']));
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

            //get tutors for this shift id
            $shift  = Shift::where("value", $shiftDuration)->first();   

            
            $tutors = Tutor::where('lesson_shift_id', $shift->id)
                        ->where('is_terminated', 0)
                        //->orWhere('is_terminated', '=', null) //@todo: confirm null is not terminated
                        ->join('users', 'users.id', '=', 'tutors.user_id')
                        //->orderBy('firstname', 'ASC')
                        ->orderBy('sort', 'ASC')
                        ->select('tutors.*', 'users.firstname', 'users.lastname', 'users.valid')
                        ->where('valid', 1)
                        ->get();

            $schedulesObj = new ScheduleItem();
            $scheduleItems = $schedulesObj->getSchedules($dateToday, $shiftDuration); 

            //@todo: load via ajax!     (set member, and schedules to null                                
            $members = new Member();
            
            //$scheduleItems = (object) ['0' => null];
            //if (count($scheduleItems) == 0) {
                //$scheduleItems = new ScheduleItem();
                //$scheduleItems = (object) ['0' => null];                
           // }          

            return view('admin.modules.scheduleItem.index', compact('dateToday', 'nextDay', 'year', 'month', 'day', 'shiftDuration', 'tutors', 'members', 'scheduleItems'));

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


            //@todo: ajax load lessons and members (NOT CHECKED)
            $scheduleItem =  new ScheduleItem();
            
            $lessons = $scheduleItem->getTutorLessons($tutor->id, $dateFrom, $dateTo);
          
            return view('admin.modules.tutor.lessons', compact('dateFrom', 'dateTo', 'lessonDays', 'timeSlots', 'lessons'));            
            
        } else {
            //echo "admin_lesson_scheduler_access or tutor_lesson_scheduler_access is disallowed..";
        }

      
    }

  
}
