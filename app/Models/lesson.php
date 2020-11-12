<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tutor;


class Lesson extends Model
{    
    public $table = 'lessons';

    protected $guarded = array('created_at', 'updated_at');

    public function getLessons($date, $duration) 
    {  
        $tutors = Tutor::all();

        //get the lessons
        $lessons = [];

        foreach ($tutors as $tutor) 
        {  
            $lessonItems = Lesson::where('scheduled_at', $date)->get();

            //where('tutor_id', $tutor->id)->where('duration', $duration)

            foreach ($lessonItems as $lessonItem) 
            {
                $lessons[$tutor->id][] = [
                    'id'            => $lessonItem->id,                    
                    'creator_id'    => $lessonItem->creator_id,
                    'tutor_id'      => $lessonItem->tutor_id,
                    
                    
                    //@todo get member name
                    'member_id'         => $lessonItem->member_id,
                    //'member_name_en'     =>

                    'startTime'     => $lessonItem->startTime,
                    'endTime'       => $lessonItem->endTime,
                    'email_type'    => $lessonItem->email_type,
                    'scheduled_at'  => $lessonItem->scheduled_at,
                    'duration'      => $lessonItem->duration,
                    
                    'status'        => $lessonItem->status,
                    //tutor
                    'name_en'    => $tutor->name_en,
                    'name_jp'    => $tutor->name_jp
                ];                
            }            
        }      

        
        return $lessons;
    }



    
}