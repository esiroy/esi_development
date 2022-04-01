<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeManagerProgress;

class TimeManagerProgressAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTimeManagerProgressGraph(Request $request, TimeManagerProgress $timeManagerProgress)
    {
        $entries = $timeManagerProgress->select('date','total_minutes', 'total_hours')
                    ->where('time_manager_id', $request['timeManagerID'])
                    ->where('member_id', $request['memberID'])
                    ->orderBy('date', 'ASC')->get();  

        if ($entries) {
        
            return Response()->json([
                "success"   => true,                
                "entries"      => $entries,
                "message"   => "time manager progress has been successfully fetched"
            ]);    

        } else {
        
            return Response()->json([
                "success"   => false,
                "message"   => "entry has been successfully saved",
            ]);   
        }

    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TimeManagerProgress $timeManagerProgress)
    {
        $response = $timeManagerProgress->add($request['data']);

        if ($response) {
        
            return Response()->json([
                "success"   => true,
                "message"   => "entry has been successfully saved"
            ]);    

        } else {
        
            return Response()->json([
                "success"   => false,
                "message"   => "error found, entry was not saved",
            ]);   
        }
        
        
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
