<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lessonHistory extends Model
{
    public $table = 'lesson_history';

    public $timestamps = false;

    protected $guarded = array('created_at', 'updated_at');
}
