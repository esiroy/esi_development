<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeManager;
use App\Models\TimeManagerProgress;

class TimeManagerProgressAPIController extends Controller
{


    public function getTimeManagerProgressList(Request $request, TimeManagerProgress $timeManagerProgress) 
    {
       
        $memberID           = $request['memberID'];
        $timeManagerID       = $request['timeManagerID'];
        

        $timeManager    = TimeManager::where('id', $timeManagerID)
                            ->where('member_id', $memberID)                           
                            ->where('valid', true)->first();
                            
        if ($timeManager) {
        
            $timeManagerProgress = TimeManagerProgress::where('member_id', $memberID)
                        ->where('time_manager_id', $timeManagerID)
                        ->orderBy('date', 'ASC')
                        ->where('time_manager_id', $timeManager->id)->paginate(10);

            if ($timeManagerProgress->total() >= 1) 
            {
                //pagination details
                $currentPage = $timeManagerProgress->currentPage();
                $perPage = $timeManagerProgress->perPage();
                $rows = $timeManagerProgress->total();

                //Format
                foreach ($timeManagerProgress as $progress) 
                {
                    $items[] = [
                            'id'            => $progress->id,
                            'date'          => JapaneseDateFormat($progress->date),
                            'udate'         => $progress->date,
                            'minutes'       => json_decode($progress->minutes),
                            'total_minutes' => $progress->total_minutes,
                            'total_hours'   => HoursToTime($progress->total_hours)
                        ];
                }

                return Response()->json([
                    "success"   => true,                    
                    'progress'  => $items,
                    "rows"      => $rows,
                    "currentPage" => $currentPage,
                    'perPage'   => $perPage,
                    "message"   => "time manager progress has been successfully fetched"
                ]);

            } else {
            
                return Response()->json([
                    "success"           => false,
                    "rows"              => $timeManagerProgress->total(),
                    "message"           => "fetching time manager progress failed "
                ]);
            
            
            }
        }

    }
    
    
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
                "message"   => "fetching entry failed",
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
                "message"   => "graph failed",
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
        $response = $timeManagerProgress->addEntry($request['data']);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeManagerProgress $timeManagerProgress)
    {
        $id = $request['updateID'];
        $response = $timeManagerProgress->updateEntry($id, $request['data']);

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
         $timeManager = TimeManagerProgress::where('member_id', $request->get('memberID'))
                        ->where('id',  $request->get('itemID'))->delete();

        if ($timeManager) {

            return Response()->json([
                "success"           => true,
                "message"           => "entry has been successfully removed",
            ]);

        } else {
        
            return Response()->json([
                "success"           => false,
                "message"           => "Error can't remove entry",
            ]);
        
        }                        
    }
}
