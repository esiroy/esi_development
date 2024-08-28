<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Gate, Auth, Config;

use App\Models\WritingEntries;
use App\Models\Member;


class WritingEntries extends Model
{
    public $table = "writing_entries";

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = true;


    function generateFileAnchorLink($filename) 
    {
        $basename = basename($filename);      
        $fileExtArray = explode(".",  $basename);

        $fileExtension = $fileExtArray[1];
        $fileURL = Storage::url($filename); 

        switch ($fileExtension) {
            case "jpg":              
            case "jpeg":
            case "png":
            case "gif":
                   
                echo "<img src='$fileURL' class='img-fluid'/>";
            break;
            default:
              echo "<a href='$fileURL' download='$fileURL'>$basename</a>";
        }
    }



    /*
        @words = determine how much points for deduction, 
        @return false if not within rangge
        @update: this is for 2021-2024
    function getWordPointDeduct_v1($words) 
    {          
        if  ($words >= 1 && $words <= 180)  {
            return 1;                        
        } else if ($words >= 181 && $words <= 500) {
            return 2;
        } else if ($words >= 501 && $words <= 800) {
            return 3;
        } else {
            return false;
        }
    }
    */

    /*
    @description: this is for 2021-2024    
    @words:  determine how much points for deduction, 
    @return false if not within range    
    */
    function getWordPointDeduct($words) 
    {          
        if  ($words >= 1 && $words <= 180)  {
            return 1;                        
        } else if ($words >= 181 && $words <= 350) {
            return 2;
        } else if ($words >= 351 && $words <= 500) {
            return 3;
        } else if ($words >= 501) {
            return 6;
        } else {
            return false;
        }
    }    


    function calculateAddtionalPoint($writingCredit, $totalWords) {    
        $pointsToDeduct     = $this->getWordPointDeduct($totalWords);
        $additionalPoints   = $pointsToDeduct - $writingCredit;
        return $additionalPoints;
    }


    function totalDeductedPoints($writingCredit, $totalWords, $appointed = false) { 

        $pointsToDeduct     = $this->getWordPointDeduct($totalWords);
        
        if ($appointed == true) {        
            $totalDeductedPoints = $pointsToDeduct * 2;
        } else {
            $totalDeductedPoints = $pointsToDeduct;
        }

        return $totalDeductedPoints;
    }


    function getAdditionalDeductionForAttachment($id, $data) {      
        $writingEntry = WritingEntries::find($id);      

        if ($writingEntry) 
        {

            $words =  $writingEntry->total_words;

            $member         = new Member();
            $writingEntries = new WritingEntries();

            $member = $member->where('user_id', $writingEntry->user_id)->first();


            if (isset($data->hasAttachment)) 
            {

                $hasAttachment = $data->hasAttachment;

                if ($hasAttachment == true) {                     

                    $words              = $data->words;
                    $writingCredit      = $writingEntry->total_points;                
                    $pointsToDeduct     = $writingEntries->getWordPointDeduct($words);
                    $additionalPoints   = $pointsToDeduct - $writingCredit;

                    //detect if user has assigned a teacher
                    if (isset($data->appointed_value)) {

                        if ($data->appointed_value == 'on') {           

                            $deductionTotal = $pointsToDeduct * 2;                            
                            //update total addtional points
                            $additionalPoints =  $deductionTotal - $writingCredit;
                            $totalDeductedPoints = $additionalPoints;



                        } else {

                            $totalDeductedPoints = $pointsToDeduct;
                        }

                    } else {
                        $totalDeductedPoints = $pointsToDeduct;
                    }      

                    $data = [
                                'total_points' => $totalDeductedPoints,
                                'total_words' =>  $words
                            ];
                    $writingEntry->update($data);
                    
           

                    //Update point balance since deduction of point credit for point balance is reading through agent Credit
                    if (isset($member->membership)) 
                    {

                        if ($member->membership == "Point Balance" || $member->membership == "Both" ) 
                        {
                            //add member transaction (agent subtract since we are deducting point)
                            if ($additionalPoints > 0) {    

                                $agentCredit = [
                                    'valid' => 1,
                                    'transaction_type' => 'AGENT_SUBTRACT',
                                    'agent_id' => $member->agent_id,
                                    'member_id' => $member->user_id,
                                    'lesson_shift_id' => $member->lesson_shift_id,
                                    //'created_by_id' => Auth::user()->id,
                                    'amount' => $additionalPoints,
                                    'price' => 1,
                                    'remarks' => "WRITING ENTRY - additional point for file attachment",
                                    //'credits_expiration' => $expiry_date,
                                    //'old_credits_expiration' => $old_credits_expiration,

                                    'writingCredit' => $writingCredit,
                                ];              

                                //ReportCardDate::create($reportCardData);                         

                                return $agentCredit;
                            } 


                        } else {
                        
                            echo "membership is monthly";

                        }


                    } else {
                    
                        return "no membership";
                    }

                } else {
                
                    return "no attachements added";
                }
            } else {
            
                 return "no attachements set";
            }
        } else {
        
             return "writing entry not found";
        }


    }
       

    function test($id, $data) {
    return 2;
    }
    
}
