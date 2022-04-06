<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberNotifier extends Model
{
    public $table = 'member_notifier';

    public $timestamps = false;

    protected $fillable = array('id', 'date', 'type', 'member_id');

    public function is_time_manager_notified($memberID) 
    {    
        $today  = date("Y-m-d");
        $type   = "time_manager";

        //create a member notifier date log if current date is not found,
        //If current date is found return is_notified = true, return (is_notified =false) if not
        $memberNotifier = MemberNotifier::where('member_id', $memberID)
                            ->where('type', 'time_manager')
                            ->whereDate('date', $today)                            
                            ->first();
                            
        if ($memberNotifier) {

            $is_notified = true;

        } else {

            $is_notified = false;

            $notify = MemberNotifier::where('member_id', $memberID)->first();
            if ($notify) 
            {        
                $notify->update([
                    'member_id'     => $memberID,
                    'date'          => $today,
                    'type'          => 'time_manager'
                ]);

            } else {            
                //create a notifier
                MemberNotifier::create([
                    'member_id'     => $memberID,
                    'date'          => $today,
                    'type'          => 'time_manager'
                ]);
            }

        }

        return $is_notified;
    }
}