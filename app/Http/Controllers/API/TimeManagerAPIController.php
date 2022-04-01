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
        
        if ($timeManager) 
        {
            $timeManager->makeHidden(['created_at', 'updated_at']);

            $minutes = TimeManagerProgress::where('member_id', $memberID)->sum('total_minutes');
            $requiredMinutes = calculateHoursToMinutes($timeManager->required_hours);
            $minutesLeft = $requiredMinutes - $minutes;

            $totalTimeLeft = minutesFormatter($minutesLeft);  
            $percentageLeft = ($minutes / $requiredMinutes) * 100;
            $formatted_percentage= number_format($percentageLeft, 2, '.', '');

            return Response()->json([
                "success"           => true,
                "message"           => "entry has been successfully found",
                "requiredMinutes " => $requiredMinutes,
                "totalTimeLeft"     => $totalTimeLeft,
                "percentageLeft"    => $formatted_percentage,
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

        $startDate = ESIDate($inputData['startDate']);
        $endDate   = ESIDate($inputData['endDate']);

        $requiredDays = getRemainingDays($startDate, $endDate);       

        //calculated hours to days
        //$requiredHours = calculateDaysToHours($requiredDays);

        $requiredHours = $inputData['requiredHours'];
        
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
        $startDate = ESIDate($inputData['startDate']);
        $endDate   = ESIDate($inputData['endDate']);
        $requiredDays = getRemainingDays($startDate, $endDate);  

        //calculated hours to days
        //$requiredHours = calculateDaysToHours($requiredDays);
        $requiredHours = $inputData['requiredHours'];


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
