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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lesson $lesson, Request $request)
    {
        $status = Status::all();
      
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

        //echo $dateToday . " " . $shiftDuration;

        //search the ID
        $shift  = Shift::where("value", $shiftDuration)->first();

        //get tutors for this shift id
        $tutors = Tutor::where('shift_id', $shift->id)->get();

        //get the members
        /*
        $members = Member::join('users', 'users.id', '=', 'members.user_id')
        ->join('attributes', 'attributes.id', '=', 'members.member_attribute_id')
        ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name, attributes.name as attribute"))
        ->get();
        */

        $members = Member::join('users', 'users.id', '=', 'members.user_id')
            ->select('members.*','users.first_name', 'users.last_name')
            ->get();

      
        $lessons = $lesson->getLessons($dateToday, $shiftDuration);    
        
        if (count($lessons) == 0) {
            $lessons = (object) ['0' => null];
        }

        return view('admin.modules.lesson.index', compact('dateToday', 'year', 'month', 'day', 'shiftDuration', 'tutors', 'members', 'lessons'));
    }

  
}
