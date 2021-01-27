<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use App\Models\Shift;
use App\Models\Tutor;

use Gate;
use DB;
use Auth;

use App\Models\ScheduleItem;

class dummyController extends Controller
{

    public function __construct()
    {
       //$this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $date = '2021-01-14';
        $duration = 25;
        $nextDay = date("Y-m-d", strtotime($date ." + 1 day"));

        /*
        $scheduleItems = ScheduleItem::whereBetween(DB::raw('DATE(lesson_time)'), array($date, $nextDay))->get();

        foreach ($scheduleItems as $items) {
            echo $items->id . " - ". $items->lesson_time . " " . $items->schedule_status . "<BR>";
        }
        */

        $schedules = new ScheduleItem();

        $items = $schedules->getTestSchedules($date, $duration);

        echo "<pre>";
        print_r ($items);
        echo "</pre>";
        echo "==========";

        /*
        $scheduleItems = ScheduleItem::where('tutor_id', 20041)
        ->whereDate('lesson_time', '>=', $date)
        ->whereDate('lesson_time', '<=', $nextDay)
        ->where('valid', 1)
        ->get();
        */
        //echo "<pre>";
        //print_r ($scheduleItems);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
