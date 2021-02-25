<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ScheduleItem;
use App\Models\Tutor;
use Carbon\Carbon;

class SalaryReportController extends Controller
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

        $query = new ScheduleItem();

        if (isset($request->date_from) && isset($request->date_to)) 
        {
            $from = date($request->date_from);
            $to = date($request->date_to);    
            $query = $query->whereBetween('created_at', [$from, $to]);                   
            
        } 
        
        if (isset($request->status)) 
        {            
            $status = str_replace(" ", "_", strtoupper($request->status));
            $query->where('schedule_status', $status);
        }        
      
        
        $schedules = $query->where('valid', 1)->orderBy('created_at', 'DESC')->paginate($per_page);
       
        return view('admin.modules.salary.index', compact('schedules', 'from', 'to'));        
        
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
