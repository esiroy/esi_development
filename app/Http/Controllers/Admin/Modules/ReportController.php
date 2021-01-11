<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ScheduleItem;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $per_page = 50;

        
        $from   = date("Y年 m月 j日");
        $to   = null;

        if (isset($request->status)) {

            $status = str_replace(" ", "_", strtoupper($request->status));
            $schedules = ScheduleItem::where('schedule_status', $status )->orderBy('created_at', 'DESC')->paginate(30);


        } 
        else if (isset($request->date_from) && isset($request->date_to)) 
        {
            $from = date($request->date_from);
            $to = date($request->date_to);      

            $schedules = ScheduleItem::whereBetween('created_at', [$from, $to])
                    ->orderBy('created_at', 'DESC')
                    ->paginate($per_page);
            
        } 
        else if (isset($request->date_from) && isset($request->date_to) && isset($request->status)) 
        {
            $from = date($request->date_from);
            $to = date($request->date_to);  

            $schedules = ScheduleItem::whereBetween('created_at', [$from, $to])
            ->where('schedule_status', $request->status)
            ->orderBy('created_at', 'DESC')
            ->paginate($per_page);

        } else {
            $schedules = ScheduleItem::paginate($per_page);
        }

       
        return view('admin.modules.report.index', compact('schedules', 'date', 'from', 'to'));
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
