<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    public $table = 'homeworks';

    protected $guarded = array('created_at', 'updated_at');

    public function replicateHomework($scheduleID, $consecutiveSchedules) {
    
        
        $homeWork = Homework::where('schedule_item_id', $scheduleID)->first();

        if ($homeWork) {

            foreach ($consecutiveSchedules['lessons'] as $key => $lesson) 
            {
                $isNewHomeWorkAdded = Homework::where('schedule_item_id',  $lesson['id'])->first();

                if (!$isNewHomeWorkAdded) {
                    $newHomeWork =  $homeWork->replicate();
                    
                    $newHomeWork->schedule_item_id = $lesson['id'];
                    $newHomeWork->valid = true;
                    $newHomeWork->save();
                }
            }
            
            return true;

        } else {
        
            return false;
        }

   

    }
}
