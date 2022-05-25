<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GeneralSetting;

class MiniTestSetting extends Model
{
    public function getMiniTestLimit() 
    {
        return GeneralSetting::select('name','value')
                                ->where('name', 'minitest')
                                ->where('valid', true)
                                ->first();
    }    
}
