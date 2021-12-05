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
    public function saveMemberPurpose($memberID, $Object, $purposeList)  
    {         
        $option = [];

        if (isset($purposeList->{"$Object"})) 
        {
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


            if ($purposeList->{"$Object"} == true) {

                Purpose::create([          
                    'valid' => 1,
                    'purpose' => str_replace("_", " ", $Object),
                    'purpose_options' => $purpose_options,
                    'member_id' => $memberID
                ]);            
            }

        }        
    }

}
