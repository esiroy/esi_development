<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LessonChat extends Model
{

    public $table = 'lesson_chat_history';


    protected $guarded = array('created_at', 'updated_at');


    public function getCreatedAtAttribute($date)
    {
        if (is_null($date)) {
            return null;
        } else {
            //return Carbon::parse($date)->setTimezone('Asia/Singapore')->format('l d F G:i:s A');

            return Carbon::parse($date)->setTimezone('Asia/Singapore')->format('d F G:i:s A');
        } 
    }


}
