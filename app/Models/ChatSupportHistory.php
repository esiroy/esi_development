<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class ChatSupportHistory extends Model
{
    public $table = 'chatsupport_history';

    protected $guarded = array( 'updated_at');    


    public function getCreatedAtAttribute($date)
    {
        if (is_null($date)) {
            return null;
        } else {
            return Carbon::parse($date)->setTimezone('Asia/Singapore')->format('l d F G:i:s A');
        } 
    }
}
