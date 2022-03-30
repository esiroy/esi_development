<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeManagerProgress extends Model
{
    public $table = 'time_manager_progress_details';

    protected $guarded = array('created_at', 'updated_at');


    public function add($data) 
    {
        $entry = TimeManagerProgress::create([
            'time_manager_id' => $data['id'],
            'course'        => $data['course'],
            'member_id'     => $data['memberID'],            
            'date'          => mysql_format_date($data['date']),
            'minutes'       => json_encode($data['minutes']),
            'total_minutes' => $data['minutes']['total'],
        ]);

        if ($entry) {
            return true;
        } else {
            return false;
        }
    }
}
