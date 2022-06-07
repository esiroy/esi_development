<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{

    public $timestamps = false;  

    protected $table = 'settings';

    protected $guarded = array('created_at', 'updated_at');
}
