<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

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

            if (Auth::user()->user_type == "MEMBER") {

                return ESIDateTimeFormat($date);

                
            } else {


                return Carbon::parse($date)->setTimezone('Asia/Singapore')->format('d F G:i:s A');
            }

            
        } 
    }


}
