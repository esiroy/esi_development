<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TimeManager;
use App\Models\TimeManagerProgress;
use App\Models\MemberNotifier;


class TimeManagerAPIController extends Controller
{
   

    public function get(request $request) 
    {
        $memberID = $request->get('memberID');        
        $timeManager = TimeManager::where('member_id', $memberID)->where('valid', true)->first();

        if ($timeManager) 
        {
            $timeManager->makeHidden(['created_at', 'updated_at']);
         
            //Get Number of days
            $today = date("Y-m-d");

            if (isset($timeManager->start_date) && isset($timeManager->end_date)) {

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
            $requiredMinutes    = calculateHoursToMinutes($timeManager->required_hours);
            $minutes            = TimeManagerProgress::where('member_id', $memberID)->where('time_manager_id', $timeManager->id)->sum('total_minutes');

             //hours consumed the student progressed
            $spentHours =  calculateMinutesToHours($minutes);
            $minutesLeft = $requiredMinutes - $minutes;


            //COUNT HOW MANY UPDATES CURRENT DAY
            $currentDayProgressCounter = TimeManagerProgress::where('member_id', $memberID)
                                            ->where('time_manager_id', $timeManager->id)
                                            ->whereDate('created_at', $today)
                                            ->count();
        

     

            //remaining time in hours
            $remainingHours = calculateMinutesToHours($minutesLeft);
            if ($remainingHours < 0) {
                $remainingHours = 0;
            }
     

            //percentage time left
            if ($requiredMinutes >= 1) {
                $percentageLeft = ($minutes / $requiredMinutes) * 100;              
            } else {                
                $percentageLeft = 0;
            }

            $formatted_percentage = number_format($percentageLeft, 2, '.', '');

            //average hours
            $averageHoursPerDay = $timeManager->required_hours / $numberOfDays;

            //Expected hours
            if ($ellapsedDays >= 1) {
                $expected_hours =  $averageHoursPerDay * ($ellapsedDays + 1);
                $total_expected_hours = number_format($expected_hours, 2, '.', '');

                //Get the current progress percentage on exprected chours
                $spentHourPercentage =  ($spentHours / $expected_hours) * 100 ;

            } else {
                $total_expected_hours = 0;
                $spentHourPercentage = 0;
            }





            //check if notified
            $notifications = new MemberNotifier();
            $is_time_manager_notified = $notifications->is_time_manager_notified($memberID);
            $time_manager_no_progress_notification = "警告！ 学習時間が大変遅れています。";

            return Response()->json([
                "success"                   => true,
                "message"                   => "entry has been successfully found",
                "today"                     => $today,
                "content"                   => $timeManager, 
                "requiredMinutes"           => $requiredMinutes,              
                "numberOfdays"              => $numberOfDays,
                "ellapsedDays"              => $ellapsedDays,
                "currentDayProgressCounter" => $currentDayProgressCounter,               
                "percentageLeft"            => $formatted_percentage,

                "spentHourPercentage"       => $spentHourPercentage,

                "requiredHours"             => HoursToTime($timeManager->required_hours),
                "expectedHours"             => HoursToTime($total_expected_hours),
                "averageHoursPerDay"        => HoursToTime($averageHoursPerDay),
                "spentHours"                => HoursToTime($spentHours),
                "remainingHours"            => HoursToTime($remainingHours),                              
                
                "is_time_manager_notified"  => $is_time_manager_notified,
                "time_manager_no_progress_notification" => $time_manager_no_progress_notification,               
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
