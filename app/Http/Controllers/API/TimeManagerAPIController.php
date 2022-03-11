<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TimeManager;

class TimeManagerAPIController extends Controller
{
   

    public function get(request $request) 
    {
        $memberID = $request->get('memberID');
        
        $timeManager = TimeManager::where('member_id', $memberID)->where('valid', true)->first();
        if ($timeManager) 
        {
            $timeManager->makeHidden(['created_at', 'updated_at']);

            return Response()->json([
                "success"           => true,
                "message"           => "entry has been successfully found",
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
            'start_date'        => ESIDate($inputData['startDate']),
            'end_date'          => ESIDate($inputData['endDate']),
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
    public function update(Request $request, $id)
    {
        $memberID = $request->get('memberID');
        $requiredHours = $inputData['requiredHours'];

        $timeManager = TimeManager::where('member_id', $memberID)->where('valid', true)->first();

        if ($timeManager) 
        {

            $data = [
                'valid'             => true, 
                'member_id'         => $request->get('memberID'),
                'course'            => $inputData['course'],
                'start_date'        => ESIDate($inputData['startDate']),
                'end_date'          => ESIDate($inputData['endDate']),
                'current_score'     => $inputData['currentScore'],
                'target_score'      => $inputData['targetScore'],            
                'required_hours'    => $requiredHours,
                'required_days'     => $requiredDays,
                'has_materials'     => isset($inputData['material_checkbox']) ? true : false,
                'materials'         => json_encode($inputData['materials']),
                'time_achievement'  => 0, //set to zero
                //'remaining_days'    => $requiredDays,
            ];


            $result = TimeManager::updae($data);

            if ($result) {
                
                return Response()->json([
                    "success"           => true,
                    "message"           => "entry has been successfully found",
                    "content"           => $timeManager,                
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


    public function view()
    {
        //
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
