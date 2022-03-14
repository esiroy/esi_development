<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    public $table = 'member_purpose';
    
    protected $guarded = array('created_at', 'updated_at');


    public function getMemberPurpose($memberID) 
    {
        $purpose = Purpose::where('member_id', $memberID)->where('valid', 1)->orderBy('id', 'ASC')->get();       
        return $purpose;    
    }


    //This will save API posted purpose
    /* 
        $Object = "Type of all the lesson";
    */
    public function saveMemberPurpose($memberID, $Object, $purposeList)  
    {         
        $option = [];
        $targetScore = [];

        //add this to delete non usable targets (no more use sitewide delete)
        Purpose::where('purpose', "TOEFL Primary")->delete();

        if (isset($purposeList->{"$Object"})) 
        {
            //Purpose Options
            if (isset($purposeList->{"$Object". "_option"})) 
            {
                foreach ($purposeList->{"$Object". "_option"} as $key => $item) {
                    if (isset($item)) {
                        if ($item == true) {
                            $option[] =  str_replace("_", " ", $key);
                        }                            
                    }
                }
            }

            if ( strtolower($purposeList->{"$Object"}) == "others"   ||   strtolower($Object) == "others" ) {        
                $purpose_options = $purposeList->{"OTHERS_value"};

            } else {
                $purpose_options = (is_array($option))? json_encode($option) : [];
            }
            
            if ($purposeList->{"$Object"} == true) 
            {
                $purposeValue = str_replace("_", " ", $Object);
                $purposeFound = Purpose::where('purpose', $purposeValue)->first();

                
                if ( $purposeFound ) 
                {                
                    $purposeFound->update([          
                        'valid' => 1,
                        'purpose' => $purposeValue,
                        'purpose_options' => $purpose_options,
                        'member_id' => $memberID
                    ]);                  
                } else {
                    Purpose::create([          
                        'valid' => 1,
                        'purpose' => $purposeValue,
                        'purpose_options' => $purpose_options,
                        'member_id' => $memberID
                    ]); 
                }
          
            } else {
            
                $purposeValue = str_replace("_", " ", $Object);
                $purposeFound = Purpose::where('purpose', $purposeValue)->first();
                if ( $purposeFound ) 
                { 
                    $purposeFound->update([          
                        'valid' => 0,
                    ]);
                } 

            }
        }
    }


    public function saveTargetScores($memberID, $Object, $purposeList) 
    {
        if (isset($purposeList->{"$Object"}))        
        {
            $targetScore = null;

            if (isset($purposeList->{"$Object"})) 
            {
                //check if the option is checked to be used in_array
                if (isset($purposeList->{"$Object". "_option"})) {
                    $purpose_option_array = (array) $purposeList->{"$Object". "_option"};
                }
                
                if (isset($purposeList->{"$Object". "_targetScore"})) 
                {
                    foreach ($purposeList->{"$Object". "_targetScore"} as $key => $item) {
                        if (isset($item)) {
                            if ($item == true) {
                                //check if the $key is on $purpose option
                                if (in_array($key, $purpose_option_array)) {
                                    $targetScore[ strtolower(str_replace(" ", "_", $key))] = "". $item ."";                                        
                                }                                        
                            }                            
                        }
                    }

                    Purpose::where('purpose', str_replace("_", " ", $Object))
                            ->where('valid', 1)
                            ->where('member_id', $memberID)
                            ->update([
                                'target_scores' => json_encode($targetScore, true)
                            ]);
            
                }
                
            }
        }
    }

}
