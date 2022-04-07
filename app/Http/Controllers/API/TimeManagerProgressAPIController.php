<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeManager;
use App\Models\TimeManagerProgress;

class TimeManagerProgressAPIController extends Controller
{


    public function getTimeManagerProgressGraph(Request $request, TimeManagerProgress $timeManagerProgress)
    {          
       
        $today          = date("Y-m-d");
        $memberID       = $request['memberID'];
        $timeManager    = TimeManager::where('member_id', $memberID)->where('valid', true)->first();

        if ($timeManager) 
        {        
            if (isset($timeManager->start_date) && isset($timeManager->end_date)) 
            {
                //CONVERT REQUIRED DAYS FROM STARD AND END DATE (OPTION 1)
                $startDate  = ESIDate($timeManager->start_date);
                $endDate    = ESIDate($timeManager->end_date);
                $numberOfDays    = getRemainingDays($startDate, $endDate) + 1;    
                $ellapsedDays    = getRemainingDays($startDate, $today) -1;

            } else {

                $numberOfDays    = 0;    
                $ellapsedDays    = 0;
            }


            //requried minutes
            $timeManager->makeHidden(['created_at', 'updated_at']);
            $requiredMinutes    = calculateHoursToMinutes($timeManager->required_hours);
            $minutes            = TimeManagerProgress::where('member_id', $memberID)->where('time_manager_id', $timeManager->id)->sum('total_minutes');

            //hours consumed the student progressed
            $spentHours =  calculateMinutesToHours($minutes);
            $minutesLeft = $requiredMinutes - $minutes;

            //average hours
            $averageHoursPerDay = $timeManager->required_hours / $numberOfDays;


            //Expected hours
            if ($ellapsedDays >= 1) {
                $expected_hours =  $averageHoursPerDay * ($ellapsedDays + 1);
                $total_expected_hours = number_format($expected_hours, 2, '.', '');
               
            } else {
                $total_expected_hours = 0;
            }

            return Response()->json([
                "success"           => true,                
                "expectedHours"     => calculateDecimalHours($total_expected_hours),
                "spentHours"        => calculateDecimalHours($spentHours),
                "requiredHours"     => calculateDecimalHours($timeManager->required_hours),
                "message"           => "time manager progress has been successfully fetched"
            ]);

        } else {        
            return Response()->json([
                "success"   => false,
                "message"   => "entry has been successfully saved",
            ]);   
        }

    }



    public function getTimeManagerProgressGraphByDate(Request $request, TimeManagerProgress $timeManagerProgress)
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
