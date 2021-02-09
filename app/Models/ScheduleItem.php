<?php

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class ScheduleItem extends Model
{
    public $table = 'schedule_item';

    protected $guarded = array('created_at', 'updated_at');

    private $limit = 30;

    /**
     * PLOT RESERVATIONS FOR MEMBERS
     * @param  $dateFrom
     * @return lessons

     */
    public function getMemberLessons($member) 
    {
        $reserves = ScheduleItem::where('member_id', $member->user_id)->where('valid', 1)->where(function ($q) use ($member) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->orderby('created_at', 'DESC')->get();

        return $reserves;
    }


    /*
    public function getReservationsold($date, $lesson_shift_id)
    {
        $lessons = [];
        $lessonItems = ScheduleItem::whereDate('lesson_time', $date)->where('lesson_shift_id', $lesson_shift_id)->get();

        foreach ($lessonItems as $lessonItem) {
            $lessons[$lessonItem->tutor_id][$lessonItem->lesson_time] = $lessonItem;
        }

        return $lessons;
    }*/

    /** @v2
     * @param tutorID - ID FROM tutor admin panel
     */
    public function getTutorLessons($tutorID, $dateFrom, $dateTo)
    {
        $lessons = [];
        $tutor = Tutor::find($tutorID);

        /*
        $lessonItems = ScheduleItem::where('tutor_id', $tutor->user_id)
        ->where('lesson_time', '>=', $dateFrom)
        ->where('lesson_time', '<=', $dateTo)
        ->get();
         */
        
        $dateToExtended = date('Y-m-d', strtotime($dateTo ." + 1 day"));   
        
        $lessonItems = ScheduleItem::whereBetween(DB::raw('DATE(lesson_time)'), array($dateFrom, $dateToExtended))->where('tutor_id', $tutor->user_id)->where('valid', 1)->get();

        foreach ($lessonItems as $item) {
            //find nickname
            $nickname = "";            
            $firstname = "";
            $lastname = "";
            $japanese_firstname = "";

            if (isset($item->member_id)) {
                $member = Member::where('user_id', $item->member_id)->first();
                //$user       = User::find($item->member_id);

                if (isset($member->nickname)) {
                    $nickname = $member->nickname;
                }

                if (isset($member->user->firstname)) {
                    $firstname = $member->user->firstname;
                }

                if (isset($member->user->lastname)) {
                    $lastname = $member->user->lastname;
                }

                if (isset($member->user->japanses_firstname)) {
                    $japanese_firstname = $member->user->japanese_firstname;
                }
            }

            $dateKey = date('m/d/Y', strtotime($item->lesson_time));

            $lessons[$dateKey][date("H:i", strtotime($item->lesson_time . " -1 hour"))] = [
                'id' => $item->id,
                'status' => $item->schedule_status,
                'startTime' => date("H:i", strtotime($item->lesson_time . " -1 hour")),
                'endTime' => date("H:i", strtotime($item->lesson_time)),
                'scheduled_at' => date('Y/m/d', strtotime($item->lesson_time)),
                'email_type' => $item->email_type,
                'duration' => $item->duration,
                'tutor_id' => $item->tutor_id,
                'tutor_name_en' => $tutor->name_en,
                'tutor_name_jp' => $tutor->name_jp,
                'creator_id' => $item->creator_id,
                //member info
                'member_id' => $item->member_id,
                'nickname' => $nickname,
                'firstname' => preg_replace('/[^A-Za-z0-9]/', ' ', $firstname),
                'lastname' => preg_replace('/[^A-Za-z0-9]/', ' ', $lastname)
            ];
        }

        return $lessons;
    }

    /**
     * ADMIN PANEL - FROM PLOTTING USER SCHEDULES
     *@param date
     *@param duration
     */
    public function getSchedulesOld($date, $duration)
    {

        $nextDay = date("Y-m-d", strtotime($date . " + 1 day"));

        $tutors = Tutor::select('tutors.id', 'tutors.user_id', 'tutors.is_terminated', 'users.firstname', 'users.lastname', 'users.valid')
            ->join('users', 'users.id', '=', 'tutors.user_id')
            ->where('users.valid', 1)
            ->where('tutors.is_terminated', 0)
            ->orderBy('sort', 'ASC')->get();

        //get the lessons
        $schedules = [];

        $schedules['tutors'] = $tutors;

        foreach ($tutors as $tutor) 
        {
            $scheduleItems = ScheduleItem::whereBetween(DB::raw('DATE(lesson_time)'), array($date, $nextDay))
                ->where('tutor_id', $tutor->user_id)
                ->where('valid', 1)
                ->get();

            foreach ($scheduleItems as $item) {
                //$member     = Member::find($item->member_id);
                //$user       = User::find($member->user_id);

                //@todo: v2 - check member
                $member = Member::where('user_id', $item->member_id)->first();
                //$user = User::find($item->member_id);

                $nickname = "";
                $firstname = "";
                $lastname = "";
                $japanese_firstname = "";

                if (isset($member->nickname)) {
                    $nickname = $member->nickname;
                }

                if (isset($member->user->firstname)) {
                    $firstname = $member->user->firstname;
                }

                if (isset($member->user->lastname)) {
                    $lastname = $member->user->lastname;
                }

                if (isset($member->user->japanses_firstname)) {
                    $japanese_firstname = $member->user->japanese_firstname;
                }

                //tutorid , scheduled_at, startTime

                $schedules[$tutor->id][date('Y-m-d', strtotime($item->lesson_time))][date("H:i", strtotime($item->lesson_time . " -1 hour"))] = [
                    'id' => $item->id,
                    'status' => $item->schedule_status,
                    'startTime' => date("H:i", strtotime($item->lesson_time . " -1 hour")),
                    'endTime' => date("H:i", strtotime($item->lesson_time)),
                    'scheduled_at' => date('Y-m-d', strtotime($item->lesson_time)),
                    'email_type' => $item->email_type,
                    'duration' => $item->duration,
                    //tutor info
                    'tutor_id' => $item->tutor_id,
                    'tutor_name_en' => $tutor->name_en,
                    'tutor_name_jp' => $tutor->name_jp,
                    //member info
                    'member_id' => $item->member_id,
                    'nickname' => $nickname,

                    'firstname' => preg_replace('/[^A-Za-z0-9]/', ' ', $firstname),
                    'lastname' => preg_replace('/[^A-Za-z0-9]/', ' ', $lastname),

                    //'cleaned_firstname'     => preg_replace('/[^A-Za-z0-9]/', ' ', $firstname),
                    //'japanese_firstname'    => $japanese_firstname
                ];
            }

        }
        return $schedules;
    }

    public function getSchedules($date, $duration)
    {
        $nextDay = date("Y-m-d", strtotime($date . " + 1 day"));

        $scheduleItems = ScheduleItem::whereBetween(DB::raw('DATE(lesson_time)'), array($date, $nextDay))
        //->where('tutor_id', $tutor->user_id)
        ->where('valid', 1)
        ->get();

        /*
        $scheduleItems = ScheduleItem::join('tutors', 'schedule_item.tutor_id', '=', 'tutors.user_id')
            ->select('schedule_item.*', 'tutors.id as tutorID')
            ->whereBetween(DB::raw('DATE(lesson_time)'), array($date, $nextDay))
            //->where('tutor_id', $tutor->user_id)
            ->where('valid', 1)
            ->get();
        */
        $schedules = [];

        foreach ($scheduleItems as $item) {
            $schedules[$item->tutor_id][date('Y-m-d', strtotime($item->lesson_time))][date("H:i", strtotime($item->lesson_time . " -1 hour"))] = [
                'id' => $item->id,
                'status' => $item->schedule_status,
                'startTime' => date("H:i", strtotime($item->lesson_time . " -1 hour")),
                'endTime' => date("H:i", strtotime($item->lesson_time)),
                'scheduled_at' => date('Y-m-d', strtotime($item->lesson_time)),
                'email_type' => $item->email_type,
                'duration' => $item->duration,
                
                'member_id' => $item->member_id,
            ];
        }

        return $schedules;
    }

    //List ALL specific Member schedules
    public function getMemberScheduledLesson($memberID)
    {
        $lessons = ScheduleItem::select('schedule_item.*', 'users.firstname', 'users.lastname')
            ->join('tutors', 'tutors.user_id', '=', 'schedule_item.tutor_id')
            ->join('users', 'users.id', '=', 'schedule_item.tutor_id')
            ->where('member_id', $memberID)
            ->orderBy('lesson_time', 'desc')
            ->paginate(Auth::user()->items_per_page);
        return $lessons;
    }

    public function getMemberTotalReserved($memberID, $month = null, $year = null)
    {

        if ($month == null) {
            $month = strtoupper(date("m"));
        }

        if ($year == null) {
            $year = date("Y");
        }

        $lessonItems = ScheduleItem::where('member_id', $memberID)
            ->whereMonth('lesson_time', '=', $month)
            ->whereYear('lesson_time', '=', $year);

        return $lessonItems->count();
    }

}
