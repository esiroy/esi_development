<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tutor;
use App\Models\Member;

class Lesson extends Model
{    
    public $table = 'lessons';

    private $limit = 1;

    protected $guarded = array('created_at', 'updated_at');


    public function getMemberScheduledLesson($memberID) 
    {
        $member = Member::find($memberID);        
        $lessons = Lesson::select('lessons.*', 'tutors.name_en as tutor_name', 'tutors.name_jp as tutor_name_jp')
                    ->join('tutors', 'tutors.id', '=',  'lessons.tutor_id')
                    ->where('member_id', $member['id'])->paginate($this->limit);
                    
        return $lessons;
    }
    

    public function getTutorScheduledLesson($tutorID) 
    {
        $tutor = Tutor::find($tutorID);
        $lessons = Lesson::where('tutor_id', $tutor['user_id'])->get();
        return $lessons;
    }


    public function getTutorLessons($tutorID, $dateFrom, $dateTo) 
    {
        $lessons = [];
        
        $tutor = Tutor::find($tutorID);
        
        $lessonItems = Lesson::where('tutor_id', $tutorID)
                                ->where('scheduled_at', '>=', $dateFrom)
                                ->where('scheduled_at', '<=', $dateTo)
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

            $dateKey = date('m/d/Y', strtotime($lessonItem->scheduled_at));

            $lessons[$dateKey][$lessonItem->start_time] = [
                'id'                => $lessonItem->id,
                'startTime'         => $lessonItem->start_time,
                'endTime'           => $lessonItem->end_time,
                'email_type'        => $lessonItem->email_type,
                'scheduled_at'      => $lessonItem->scheduled_at,
                'duration'          => $lessonItem->duration,                    
                'status'            => $lessonItem->status,
                'tutor_id'          => $lessonItem->tutor_id,
                'tutor_name_en'     => $tutor->name_en,
                'tutor_name_jp'     => $tutor->name_jp,
                'creator_id'        => $lessonItem->creator_id,
                'member_id'         => $lessonItem->member_id,                    
                'member_name_en'    => $fname,
                'member_name_jp'    => $fname_jp
            ];            
        }     
        
        return $lessons;
        
    }
        

    public function getLessons($date, $duration) 
    {  
        $tutors = Tutor::all();

        //get the lessons
        $lessons = [];

        foreach ($tutors as $tutor) 
        {             
            $lessonItems = Lesson::where('scheduled_at', $date)->where('tutor_id', $tutor->id)->get();
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

                $lessons[$tutor->id][] = [
                    'id'                => $lessonItem->id,
                    'startTime'         => $lessonItem->start_time,
                    'endTime'           => $lessonItem->end_time,
                    'email_type'        => $lessonItem->email_type,
                    'scheduled_at'      => $lessonItem->scheduled_at,
                    'duration'          => $lessonItem->duration,                    
                    'status'            => $lessonItem->status,
                    'tutor_id'          => $lessonItem->tutor_id,
                    'tutor_name_en'     => $tutor->name_en,
                    'tutor_name_jp'     => $tutor->name_jp,
                    'creator_id'        => $lessonItem->creator_id,
                    'member_id'         => $lessonItem->member_id,                    
                    'member_name_en'    => $fname,
                    'member_name_jp'    => $fname_jp
                ];                
            }            
        }      

        
        return $lessons;
    }



    
}