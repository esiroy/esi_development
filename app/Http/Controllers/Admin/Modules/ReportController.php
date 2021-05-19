<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\ScheduleItem;
use Auth , DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = Auth::user()->items_per_page;
        
        //initiatte schedule for reporting
        $schedules = new ScheduleItem();

        $status = $request->status;

        if (isset($request->date_from) && isset($request->date_to)) 
        {
            $dateFrom = date('Y-m-d', strtotime($request['date_from']));
            $dateTo = date('Y-m-d', strtotime($request['date_to']));
            $extendedTo = date('Y-m-d', strtotime($dateTo . " +1 day"));
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom ." 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");          
        } else {
            //Current date
            $dateFrom = date("Y-m-d");            
            $dateTo = date('Y-m-d', strtotime($dateFrom . " +1 day"));
            $extendedTo = date('Y-m-d', strtotime($dateFrom . " +2 day"));
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom ." 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");             
        }

        if (isset($request->status)) {            
            $status = str_replace(' ', '_', strtoupper($request->status));
            $schedules = $schedules->where('schedule_status', $status);
        }

        //no request paramters
        if (!isset($request->date_from) && !isset($request->date_to) && !isset($request->status)) 
        {
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom ." 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");                    
        }        
        
        //valid only
        $schedules = $schedules->where('valid', true);
        //add additional ordering
        $schedules = $schedules->orderBy('lesson_time', 'DESC')->orderBy('id', 'DESC');
        //1k only (@todo: ask if there is pagination?)
        $schedules = $schedules->paginate(1000);
        //$schedules = $schedules->paginate($per_page);      

        return view('admin.modules.report.index', compact('schedules', 'dateFrom', 'dateTo'));
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
