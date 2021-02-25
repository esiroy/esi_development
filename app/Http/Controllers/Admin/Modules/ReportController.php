<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\ScheduleItem;
use Auth;
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

        //$from   = date("Y年 m月 j日");
        $from = date("Y m j");
        $to = null;

        if (isset($request->status)) 
        {
            $status = str_replace(" ", "_", strtoupper($request->status));
            $schedules = ScheduleItem::where('schedule_status', $status)->orderBy('created_at', 'DESC')->paginate(30);

        } else if (isset($request->date_from) && isset($request->date_to)) {

            $dateFrom = date('Y-m-d', strtotime($request['date_from']));
            $dateTo = date('Y-m-d', strtotime($request['date_to']));

            $schedules = ScheduleItem::whereBetween('lesson_time', [$dateFrom, $dateTo])->orderBy('created_at', 'DESC')->where('schedule_status', $request->status)->paginate($per_page);
            
        } else if (isset($request->date_from) && isset($request->date_to) && isset($request->status)) {


            $dateFrom = date('Y-m-d', strtotime($request['date_from']));
            $dateTo = date('Y-m-d', strtotime($request['date_to']));

            $schedules = ScheduleItem::whereBetween('lesson_time', [$dateFrom, $dateTo])->where('schedule_status', $request->status)->orderBy('created_at', 'DESC')->paginate($per_page);

        } else {

            $schedules = ScheduleItem::orderBy('lesson_time', 'DESC')->paginate($per_page);
        }

        return view('admin.modules.report.index', compact('schedules', 'from', 'to'));
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
