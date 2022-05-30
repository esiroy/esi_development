<?php

namespace App\Models;

use App\Models\GeneralSetting;
use Illuminate\Database\Eloquent\Model;
use  DB;

class MiniTestSetting extends Model
{


    public $table = 'settings';

    public $timestamps = false;
    
    protected $guarded = array('created_at', 'updated_at');


    /* defaults to 2 if not set */
    public function getMiniTestLimit()
    {
        $limit = GeneralSetting::select('name', 'value')
            ->where('name', 'minitest_limit')
            ->where('valid', true)
            ->first();

        if (isset($limit)) {
            return $limit->value;
        } else {
            return 2;
        }
    }

    /* defaults to 7 if not set */
    public function getMiniTestDuration()
    {
        $duration = GeneralSetting::select('name', 'value')
            ->where('name', 'minitest_duration')
            ->where('valid', true)
            ->first();

        if (isset($duration)) {
            return $duration->value;
        } else {
            return 7;
        }
    }



    //Update if exists or create if does not
    public function createOrUpdateLimit($limit) {
    
        $setting = GeneralSetting::where('name', 'minitest_limit')->where('valid', true)->first();

        if (isset($setting)) {
          
            $setting->update([
                'value' => $limit
            ]);

        } else {
           //not exists, then create it.
            GeneralSetting::create([
                'name'  => "minitest_limit",
                'description' => "Maximum Limit of Free Mini Test",
                'value' => $limit,
                'valid' => true,
            ]);

         
        }
    
    }


    public function createOrUpdateDuration($duration) {
    
        $setting = GeneralSetting::where('name', 'minitest_duration')->where('valid', true)->first();

        if (isset($setting)) {
          
            $setting->update([
                'value' => $duration
            ]);

        } else {
           //not exists, then create it.
            GeneralSetting::create([
                'name'  => "minitest_duration",
                'description' => "Duration of minitest to refresh free credits",
                'value' => $duration,
                'valid' => true,
            ]);
        }
    
    }
    
}
