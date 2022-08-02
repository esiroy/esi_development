<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberSetting extends Model
{
    public $table = 'member_settings';

    protected $guarded = array('created_at', 'updated_at');


    public function getMemberSetting($userID, $settingName)  {

        
        $setting = MemberSetting::where('user_id', $userID)
                                ->where('name', $settingName)                                
                                ->where('valid', true)
                                ->first();

        if ($setting) {        
            return $setting;
        } else {
            return null;
        }
        

    }

    public function createOrUpdateSetting($userID, $settingName, $value)  {
    
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
                'description'   => "Setting for tabs to hide ( FAQ/Lesson Fee/Lesson Course )",
                'value'         => $value ?? false,
                'valid'         => true,
            ]);
        }

    }

  


}
