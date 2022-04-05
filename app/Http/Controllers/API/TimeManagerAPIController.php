<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TimeManager;
use App\Models\TimeManagerProgress;

class TimeManagerAPIController extends Controller
{
   

    public function get(request $request) 
    {
        $memberID = $request->get('memberID');        
        $timeManager = TimeManager::where('member_id', $memberID)->where('valid', true)->first();

         
        //Get Number of days
        $today = date("Y-m-d");

        if (isset($timeManager->start_date) && isset($timeManager->end_date)) {

            //CONVERT REQUIRED DAYS FROM STARD AND END DATE (OPTION 1)
            $startDate  = ESIDate($timeManager->start_date);
            $endDate    = ESIDate($timeManager->end_date);

            $numberOfDays    = getRemainingDays($startDate, $endDate) + 1;    
            $ellapsedDays    = getRemainingDays($startDate, $today) - 1; //-1 disregard the day currently have

        } else {
            $numberOfDays    = 0;    
            $ellapsedDays    = 0;
        }
        

        if ($timeManager) 
        {
            $timeManager->makeHidden(['created_at', 'updated_at']);

            $minutes = TimeManagerProgress::where('member_id', $memberID)->where('time_manager_id', $timeManager->id)->sum('total_minutes');

            //look for current day updates
            $currentDayProgressCounter = TimeManagerProgress::where('member_id', $memberID)
                                            ->where('time_manager_id', $timeManager->id)
                                            ->whereDate('created_at', $today)
                                            ->count();

            $minutesProgress = calculateHoursToMinutes($currentDayProgressCounter);
            $requiredMinutes = calculateHoursToMinutes($timeManager->required_hours);


            $minutesLeft = $requiredMinutes - $minutes;

            //hours consumed the student progressed
            $spentHours =  calculateMinutesToHours($minutes);

            //remaining time in hours
            $remainingHours = calculateMinutesToHours($minutesLeft);
     

            //percentage time left
            $percentageLeft = ($minutes / $requiredMinutes) * 100;

            $formatted_percentage = number_format($percentageLeft, 2, '.', '');

            //average hours
            $averageHoursPerDay = $timeManager->required_hours / $numberOfDays;

            //Expected hours
            if ($ellapsedDays >= 1) {
                $expected_hours =  $averageHoursPerDay * $ellapsedDays;
                $formatted_expected_hours = number_format($expected_hours, 2, '.', '');
                $expected_hours_with_decimals = calculateDecimalHours($formatted_expected_hours);
            } else {
                $expected_hours_with_decimals = 0;
            }


           


            return Response()->json([
                "success"           => true,
                "message"           => "entry has been successfully found",
                "requiredMinutes"   => $requiredMinutes,
              
                "numberOfdays"      => $numberOfDays,
                "ellapsedDays"      => $ellapsedDays,
                "today"             => $today,
                "percentageLeft"    => $formatted_percentage,

                "averageHoursPerDay"    => calculateDecimalHours($averageHoursPerDay),
                "spentHours"            => calculateDecimalHours($spentHours),
                "remainingHours"        => calculateDecimalHours($remainingHours),
                "expectedHours"         => $expected_hours_with_decimals,
                "currentDayProgressCounter" => $currentDayProgressCounter,
                "requiredHours"     =>  calculateDecimalHours($timeManager->required_hours),

                "content"           => $timeManager,                
            ]);
        } else {        
            return Response()->json([
                "success"           => false,
                "message"           => "entry was not found",     
            ]);
        }    
    }

    public function create(Request $request)
    {
        
        $inputData = $request->get('data');


    

        //CONVERT REQUIRED HOURS TO DAYS (OPTION 2)
        $requiredHours = $inputData['requiredHours'];
        $requiredDays = calculateHoursToDays($requiredHours);
        
        $data = [
            'valid'             => true, 
            'member_id'         => $request->get('memberID'),
            'course'            => $inputData['course'],
            'grade_level'        => $inputData['gradeLevel'] ?? "", //OPTIONAL GRADE LEVE
            'start_date'        => $inputData['startDate'],
            'end_date'          => $inputData['endDate'],
            'current_score'     => $inputData['currentScore'],
            'target_score'      => $inputData['targetScore'],            
            'required_hours'    => $requiredHours,
            'required_days'     => $requiredDays,
            'has_materials'     => isset($inputData['material_checkbox']) ? true : false,
            'materials'         => json_encode($inputData['materials']),
            'time_achievement'  => 0, //set to zero
            'remaining_days'    => $requiredDays,
        ];

    

        $result = TimeManager::create($data);

        if ($result) {
        
            return Response()->json([
                "success"           => true,
                "message"           => "entry has been successfully saved",
                "content"              => $data,                
            ]); 

        } else {
        
            return Response()->json([
                "success" => false,
                "message" => "saving did not succeed " 
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
    public function update(Request $request)
    {
        $memberID = $request->get('memberID');
        $inputData = $request->get('data');
       
        //CONVERT REQUIRED DAYS FROM STARD AND END DATE (OPTION 1)
        $startDate = ESIDate($inputData['startDate']);
        $endDate   = ESIDate($inputData['endDate']);        
        //$requiredDays = getRemainingDays($startDate, $endDate);       

        //CONVERT REQUIRED HOURS TO DAYS (OPTION 2)
        $requiredHours = $inputData['requiredHours'];
        $requiredDays = calculateHoursToDays($requiredHours);        


        $timeManager = TimeManager::where('member_id', $memberID)->where('valid', true)->first();

        if ($timeManager) 
        {

            $data = [
                'valid'             => true, 
                'member_id'         => $request->get('memberID'),
                'course'            => $inputData['course'],
                'grade_level'        => $inputData['gradeLevel'] ?? "", //OPTIONAL GRADE LEVE
                'start_date'        => $inputData['startDate'],
                'end_date'          => $inputData['endDate'],
                'current_score'     => $inputData['currentScore'],
                'target_score'      => $inputData['targetScore'],            
                'required_hours'    => $requiredHours,
                'required_days'     => $requiredDays,
                'has_materials'     => isset($inputData['material_checkbox']) ? true : false,
                'materials'         => json_encode($inputData['materials']),
                'time_achievement'  => 0, //set to zero
                'remaining_days'    => $requiredDays,
            ];


            $result =  $timeManager->update($data);

            if ($result) {
                
                return Response()->json([
                    "success"           => true,
                    "message"           => "entry has been successfully found",
                    "content"           => $data,                
                ]);
            } else {
            
                return Response()->json([
                    "success"           => false,
                    "message"           => "update error for time manager",     
                ]);
                

            }


        } else {        
            return Response()->json([
                "success"           => false,
                "message"           => "entry was not found",     
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

        $timeManager = TimeManager::where('member_id', $request->get('memberID'))->where('valid', true)->first();

        if ($timeManager) {
            $timeManager->update([
                'valid'=> false
            ]);        

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
