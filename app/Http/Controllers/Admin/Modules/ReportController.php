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

        //Current date
        $from = date("Y-m-d");
        $to = date('Y-m-d', strtotime($from . " 1 day"));      

        if (isset($request->date_from) && isset($request->date_to) && !isset($request->status)) {

            $dateFrom = date('Y-m-d', strtotime($request['date_from']));
            $dateTo = date('Y-m-d', strtotime($request['date_to']));

            $schedules = ScheduleItem::whereDate('lesson_time', '>=', $dateFrom)
                                        ->whereDate('lesson_time', '<=', $dateTo);
            $schedules = $schedules->orderBy('schedule_status', 'DESC');
            $schedules = $schedules->paginate($per_page);

        } else if (isset($request->date_from) && isset($request->date_to) && isset($request->status)) {

            $dateFrom = date('Y-m-d', strtotime($request['date_from']));
            $dateTo = date('Y-m-d', strtotime($request['date_to']));
            $status = str_replace(' ', '_', strtoupper($request->status));

            $schedules = ScheduleItem::whereDate('lesson_time', '>=', $dateFrom)
                                        ->whereDate('lesson_time', '<=', $dateTo);

            $schedules = $schedules->where('schedule_status', $status);

            $schedules = $schedules->orderBy('schedule_status', 'DESC');
            $schedules = $schedules->paginate($per_page);

        } else if (isset($request->status)) {

            $status = str_replace(' ', '_', strtoupper($request->status));
            $status = str_replace(" ", "_", strtoupper($status));

            $schedules = ScheduleItem::where('schedule_status', $status);
            $schedules = $schedules->orderBy('schedule_status', 'DESC');
            $schedules = $schedules->paginate($per_page);

        } else {

            // $schedules = ScheduleItem::orderBy('lesson_time', 'DESC')->paginate($per_page);

            $schedules = ScheduleItem::whereDate('lesson_time', '>=', $from)
                                        ->whereDate('lesson_time', '<=', $to);
            $schedules = $schedules->paginate($per_page);                                        

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
