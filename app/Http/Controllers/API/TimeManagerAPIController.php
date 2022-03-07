<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TimeManager;

class TimeManagerAPIController extends Controller
{
   

    public function create(Request $request)
    {
        
        $data = $request->get('data');

        $startDate = ESIDate($data['startDate']);
        $endDate   = ESIDate($data['endDate']);

        $remainingDays = getRemainingDays($startDate, $endDate);       

        //calculated hours to days
        $requiredDays = calculateHoursToDays($data["requiredHours"]);
        
        $content = [
            'valid'             => true, 
            'member_id'         => $request->get('memberID'),
            'course'            => $data['course'],
            'start_date'        => ESIDate($data['startDate']),
            'end_date'          => ESIDate($data['endDate']),
            'current_score'     => $data['currentScore'],
            'target_score'      => $data['targetScore'],            
            'required_hours'    => $data['requiredHours'],
            'required_days'     => number_format($requiredDays, 4, '.', ''),
            'has_materials'     => isset($data['material_checkbox']) ? true : false,
            'materials'         => json_encode($data['materials']),
            'time_achievement'  => 0, //set to zero
            'remaining_days'    => $remainingDays,
        ];

        $result = TimeManager::create($content);

        if ($result) {
        
            return Response()->json([
                "success"           => true,
                "message"           => "entry has been successfully saved",
                "content"              => $content,                
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
        //
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
    public function destroy($id)
    {
        //
    }


}
