<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberMonthlyTerm extends Model
{
    public $table = 'member_settings';

    public $timestamps = false;
    
    protected $guarded = array('created_at', 'updated_at');

    public function isMonthlyNotificationEnabled($userID) 
    {
        $result = $this->where('user_id', $userID)->where('name', 'show_monthly_member_notification')->first();

        if ($result) {

            if ($result->value == true && $result->valid == true)
                return true;
            else 
                return false;
        } else {
            return false;
        }        
    }


    public function isMemberAgreedToMonthlyTerms($userID) 
    {
        $result = $this->where('user_id', $userID)->where('name', 'member_agreed_monthly_terms')->first();

        if ($result) {

            if ($result->value == true && $result->valid == true)
                return true;
            else 
                return false;
        } else {
            return false;
        }        
    }    
    

    public function agreeMonthlyTerm($userID)
    {
       $result = $this->where('user_id', $userID)->where('name', 'member_agreed_monthly_terms')->first();

       if ($result) {

            return $result->update(['value' => true]);

       } else {

            return $this->create([
                'user_id' => $userID,
                'name'    => 'member_agreed_monthly_terms',
                'description' => 'shows member notificaton popup on the front end side',
                'value' => true,
                'valid' => true,
                'created_at'   => now(),  // Add created_at timestamp
                'updated_at'   => now(),  // Add updated_at timestamp                
            ]);
       }
    }  
    
    
    public function createOrUpdateSetting($userID, $settingName, $value, $description = '')  {
    
        $setting = MemberSetting::where('user_id', $userID)
                                ->where('name', $settingName)                                
                                ->where('valid', true)
                                ->first();

        if (isset($setting)) {
          
            $setting->update([
                'value' => $value
            ]);

        } else {

             //not exists, then create it.
            MemberSetting::create([
                'user_id'       => $userID,
                'name'          => $settingName,                
                'description'   => $description,
                'value'         => $value ?? false,
                'valid'         => true,
            ]);
        }

    }    
}
