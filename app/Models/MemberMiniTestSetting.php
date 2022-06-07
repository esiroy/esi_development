<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberMiniTestSetting extends Model
{
    public $table = 'member_settings';

    public $timestamps = false;
    
    protected $guarded = array('created_at', 'updated_at');


    /* defaults to 2 if not set */
    public function hasOverride($userID)
    {
        $override = MemberMiniTestSetting::select('name', 'value')
            ->where('name', 'minitest_override')
            ->where('user_id', $userID)
            ->where('valid', true)
            ->first();

        if (isset($override)) 
        {
            return $override->value;
        } else {
            return false;
        }
    }

    /* defaults to 2 if not set */
    public function getMiniTestLimit($userID)
    {
        $limit = MemberMiniTestSetting::select('name', 'value')
            ->where('name', 'minitest_limit')
            ->where('user_id', $userID)
            ->where('valid', true)            
            ->first();

        if (isset($limit)) {
            return $limit->value;
        } else {
            return 2;
        }
    }





    /* defaults to 7 if not set */
    public function getMiniTestDuration($userID)
    {
        $duration = MemberMiniTestSetting::select('name', 'value')
            ->where('name', 'minitest_duration')
            ->where('user_id', $userID)
            ->where('valid', true)
            ->first();

        if (isset($duration)) {
            return $duration->value;
        } else {
            return 7;
        }
    }



    public function createOrUpdateOverride($userID, $value)
    {
        $override = MemberMiniTestSetting::where('name', 'minitest_override')
                                ->where('user_id', $userID)
                                ->where('valid', true)
                                ->first();

        if (isset($override)) 
        {
            $override->update([
                'value' => $value
            ]);

        } else {

            MemberMiniTestSetting::create([
                'name'          => "minitest_override",
                'user_id'       => $userID,
                'description'   => "Override Setting of the General Free Mini Test Setting",
                'value'         => $value,
                'valid'         => true,
            ]);
        }
    }    



    //Update if exists or create if does not
    public function createOrUpdateLimit($userID, $limit) {
    
        $setting = MemberMiniTestSetting::where('name', 'minitest_limit')
                        ->where('user_id', $userID)
                        ->where('valid', true)
                        ->first();

        if (isset($setting)) {
          
            $setting->update([
                'value' => $limit
            ]);

        } else {
           //not exists, then create it.
            MemberMiniTestSetting::create([
                'name'          => "minitest_limit",
                'user_id'       => $userID,
                'description'   => "Maximum Limit of Free Mini Test",
                'value'         => $limit,
                'valid'         => true,
            ]);

         
        }
    
    }


    public function createOrUpdateDuration($userID, $duration) {
    
        $setting = MemberMiniTestSetting::where('name', 'minitest_duration')
                        ->where('user_id', $userID)
                        ->where('valid', true)->first();

        if (isset($setting)) {
          
            $setting->update([               
                'value'     => $duration
            ]);

        } else {
           //not exists, then create it.
            MemberMiniTestSetting::create([
                'name'          => "minitest_duration",
                'user_id'       => $userID,
                'description'   => "Duration of minitest to refresh free credits",
                'value'         => $duration,
                'valid'         => true,
            ]);
        }
    
    }    
}
