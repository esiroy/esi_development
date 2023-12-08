<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ScheduleItem;
use App\Models\Folder;

class lessonSlideSchedule extends Controller
{
    public function getConsecutiveLessons(Request $request) {

        $scheduleID = $request->scheduleID;
        $schedules  = $scheduleItem->find_remaining_lessons($scheduleID);

        return $scheduleItem->find_consecutive_lessons($schedules);     
    }
}
