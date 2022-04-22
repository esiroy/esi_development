<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeManager extends Model
{
    public $table = 'time_manager';

    protected $guarded = array('created_at', 'updated_at');
}
