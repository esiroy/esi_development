<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class ScheduleItem extends Model
{
    public $table = 'schedule_item';

    protected $guarded = array('created_at', 'updated_at');
                
    /** 
    * ADMIN PANEL - FROM PLOTTING USER SCHEDULES
     *@param date
     *@param duration
    */
    public function getSchedules($date, $duration) 
    {
        $tutors = Tutor::all();
        //get the lessons
        $schedules = [];        
    
        foreach ($tutors as $tutor) 
        {                 
            $scheduleItems = ScheduleItem::whereDate('lesson_time', $date)->where('tutor_id', $tutor->id)->get();   
            
            foreach ($scheduleItems as $item) 
            {
                $member     = Member::find($item->member_id);

                $user       = User::find($member['user_id']);
                //@done: user my be empty if not reserved
                if (isset($user['first_name'])) {
                    $memberNameEN = $user['first_name'] . " " .  $user['last_name'];
                    $memberNameJP = $user['first_name_jp'] . " " . $user['last_name_jp'];
                } else {
                    $memberNameEN = "";
                    $memberNameJP = "";
                }

               
                $schedules[$tutor->id][] = [
                    'id'                => $item->id,
                    'status'            => $item->schedule_status,
                    //'lesson_time'       => $item->lesson_time, (date_time)
                    'startTime'         =>  date("H:i", strtotime($item->lesson_time)),
                    'endTime'           =>  date("H:i",  strtotime($item->lesson_time ."+1 hour")),
                    'scheduled_at'      =>  date('Y-m-d', strtotime($item->lesson_time)),
                    'email_type'        => $item->email_type,              
                    'duration'          => $item->duration,                    
                    'member_id'         => $item->member_id,   
                    'tutor_id'          => $item->tutor_id,
                    'tutor_name_en'     => $tutor->name_en,
                    'tutor_name_jp'     => $tutor->name_jp,                               
                    'member_name_en'    => $memberNameEN,
                    'member_name_jp'    => $memberNameJP,
                ];                
            }            
        }
        return $schedules;        
    }



    public function getTutorLessons($tutorID, $dateFrom, $dateTo) 
    {     
        $lessons = [];        
        $tutor = Tutor::find($tutorID);

        $lessonItems = ScheduleItem::where('tutor_id', $tutorID)
                                ->where('lesson_time', '>=', $dateFrom)
                                ->where('lesson_time', '<=', $dateTo)
                                ->get();

        foreach ($lessonItems as $lessonItem) 
        {
            $member     = Member::find($lessonItem->member_id);
            $user       = User::find($member['user_id']);
            //@done: user my be empty if not reserved
            if (isset($user['first_name'])) {
                $fname = $user['first_name'];
                $fname_jp = $user['first_name_jp'];
            } else {
                $fname = "";
                $fname_jp = "";
            }

            /*
            echo "<pre>";
            print_r ($lessonItem);
            echo "</pre>";
            */

            $dateKey = date('m/d/Y', strtotime($lessonItem->lesson_time));

            $lessons[$dateKey][date("H:i", strtotime($lessonItem->lesson_time))] = [
                'id'                => $lessonItem->id,
                //'startTime'         => $lessonItem->start_time,
                //'endTime'           => $lessonItem->end_time,
                //'scheduled_at'      => $lessonItem->scheduled_at,

                'startTime'         =>  date("H:i", strtotime($lessonItem->lesson_time)),
                'endTime'           =>  date("H:i",  strtotime($lessonItem->lesson_time ."+1 hour")),
                'scheduled_at'      =>  date('Y/m/d', strtotime($lessonItem->lesson_time)),

                'email_type'        => $lessonItem->email_type,                
                'duration'          => $lessonItem->duration,
                'tutor_id'          => $lessonItem->tutor_id,
                'tutor_name_en'     => $tutor->name_en,
                'tutor_name_jp'     => $tutor->name_jp,
                'creator_id'        => $lessonItem->creator_id,
                'member_id'         => $lessonItem->member_id,                    
                'member_name_en'    => $fname,
                'member_name_jp'    => $fname_jp,
                'status'            => $lessonItem->schedule_status,
               
            ];            
        }     
        
        return $lessons;
        
    }
        
    

}
