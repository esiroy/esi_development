<?php

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\MemoReply;

use App\Models\MiniTestResult;
use App\Models\Member;

class ScheduleItem extends Model
{
    public $table = 'schedule_item';

    protected $guarded = array('created_at', 'updated_at');

    private $limit = 30;


    public function reportCard()
    {
        return $this->hasOne(ReportCard::class, 'schedule_item_id');
    }
    
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
        })->orderby('created_at', 'ASC')->get();

        return $reserves;
    }


  
    public function getMemberAllActiveLessons($member) 
    {
        $date = date('Y-m-d H:i:s');

        $schedules = ScheduleItem::where('member_id', $member->user_id)->where('valid', 1)->where(function ($q) use ($member) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();

        $results = array();

        foreach ($schedules as $schedule) 
        {
           $latestReply = MemoReply::where('schedule_item_id', $schedule->id)->orderBy('updated_at', 'DESC')->first();           
            
           if ($latestReply) {
                $results[] = array(
                    'id' => $schedule->id,
                    "memo" => $schedule->memo,
                    "lesson_time" => $schedule->lesson_time,
                    'message' => $latestReply->message,
                    "member_id" => $schedule->member_id,
                    "tutor_id" => $schedule->tutor_id,
                    'updated_at' => $latestReply->updated_at ,
                );
           }

        }               

        usort($results, sortByDate('updated_at'));
        $results = (object) $results;
        $object = json_decode(json_encode($results));
        return $object;
    }


    public function getMemberAllActiveLessons_standard($member) 
    {
        $date = date('Y-m-d H:i:s');

        $reserves = ScheduleItem::where('member_id', $member->user_id)->where('valid', 1)->where(function ($q) use ($member) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();

        return $reserves;
    }
    
    public function getMemberAllActiveLessons_AutoFormat($member) 
    {

        $date = date('Y-m-d H:i:s');
        $minutes = date('i', strtotime($date));

        // If minutes is more than 00 and less than 30, set them to 0
        if ($minutes > 0 && $minutes < 30) {
            $date = date('Y-m-d H:00:00', strtotime($date));
        }
        
        // If equal or more than 30, set them to 30
        if ($minutes >= 30) {
            $date = date('Y-m-d H:30:00', strtotime($date));
        }

        $reserves = ScheduleItem::where('member_id', $member->user_id)->where('valid', 1)->where(function ($q) use ($member) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();

        return $reserves;        
    }

    public function getTutorAllActiveLessons($tutor) 
    {
        $date = date('Y-m-d H:i:s');

        $schedules = ScheduleItem::where('tutor_id', $tutor->user_id)->where('valid', 1)->where(function ($q) use ($tutor) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();

        $results = array();

        foreach ($schedules as $schedule) 
        {
           $latestReply = MemoReply::where('schedule_item_id', $schedule->id)->orderBy('updated_at', 'DESC')->first();           
            
           if ($latestReply) {
                $results[] = array(
                    'id' => $schedule->id,
                    "memo" => $schedule->memo,
                    "lesson_time" => $schedule->lesson_time,
                    'message' => $latestReply->message,
                    "member_id" => $schedule->member_id,
                    "tutor_id" => $schedule->tutor_id,
                    'updated_at' => $latestReply->updated_at ,
                );
           }

        }               

        usort($results, sortByDate('updated_at'));
        $results = (object) $results;
        $object = json_decode(json_encode($results));
        return $object;
    }  


    public function getTutorAllActiveLessons_standard($tutor) 
    {
        $date = date('Y-m-d H:i:s');

        $reserves = ScheduleItem::where('tutor_id', $tutor->user_id)->where('valid', 1)->where(function ($q) use ($tutor) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();
        
        return $reserves;
    }    
    
     public function getTutorAllActiveLessons_AutoFormat($tutor) 
    {
        $date = date('Y-m-d H:i:s');
        $minutes = date('i', strtotime($date));

        // If minutes is more than 00 and less than 30, set them to 0
        if ($minutes > 0 && $minutes < 30) {
            $date = date('Y-m-d H:00:00', strtotime($date));
        }
        
        // If equal or more than 30, set them to 30
        if ($minutes >= 30) {
            $date = date('Y-m-d H:30:00', strtotime($date));
        }        
        $reserves = ScheduleItem::where('tutor_id', $tutor->user_id)->where('valid', 1)->where(function ($q) use ($tutor) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();
        
        return $reserves;
    } 

    
    public function getMemberActiveLessons($member) 
    {
        $date_now = date('Y-m-d H:i:s');
        //
        $date_30minutes_validity = date("Y-m-d H:i:s", strtotime($date_now ." - 30 minutes"));

        $reserves = ScheduleItem::where('member_id', $member->user_id)->where('valid', 1)->where(function ($q) use ($member) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date_30minutes_validity)
        ->orderby('lesson_time', 'ASC')
        ->paginate(5);

        return $reserves;
    }
    

    /* @Added: MAY 21, 2021
     * @Desc:  Returns total number count of Reserved (A & B ONLY), this is for the member schedule list limiter to 
     * @Params: @MembeID - User ID of Member
     * Returns: @total (site wide number of reserved A and B)
    */
    public function getTotalMemberReserved($member) 
    {
        $dateOfReservation = date('Y-m-d H:i:s');
                     
        $total = ScheduleItem::where('member_id', $member->user_id)
        ->where('valid', 1)
        ->where(function ($q) use ($member) 
        {
            $q
                ->orWhere('schedule_status', 'CLIENT_RESERVED')
                ->orWhere('schedule_status', 'CLIENT_RESERVED_B');

        })
        ->where('lesson_time', ">=", $dateOfReservation)
        ->count();


        return $total;
    }

    public function getTotalMemberMultiAccountReserved($userID, $multiAccountID) 
    {
        $dateOfReservation = date('Y-m-d H:i:s');
                     
        $total = ScheduleItem::where('member_id', $userID)
        ->where('valid', 1)
        ->where('member_multi_account_id', $multiAccountID)
        ->where(function ($q) use ($userID) 
        {
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
              ->orWhere('schedule_status', 'CLIENT_RESERVED_B');

        })
        ->where('lesson_time', ">=", $dateOfReservation)
        ->count();


        return $total;
    }


    /**
     * @Added: JUNE 3, 2021
     * @Desc: Returns total number of reserve A and B for a particular day for a particular member
     * @Params: @membeID - User ID of Member
     * @Params: @date (format Y, M, D)
     * Returns: @total (number of reserved A and B in a particular day for a particular member)
    */
    public function getTotalMemberDailyReserved($memberID, $date) 
    {

        $nextDay = date("Y-m-d", strtotime($date . " + 1 day"));

        $total = ScheduleItem::where('member_id', $memberID)                            
                            ->where(function ($q) use ($memberID) {       
                                $q->orWhere('schedule_status', 'CLIENT_RESERVED')
                                  ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
                            })->whereDate('lesson_time', '=', $date)                            
                            ->orWhereDate('lesson_time', '=',  $nextDay . " 00:30:00")
                            ->orWhereDate('lesson_time', '=',  $nextDay . " 00:00:00")                            
                            ->where('valid', 1)                            
                            ->count();

        return $total;
    }

    /**
     * @Added: JUNE 7, 2021
     * @Desc: Returns total number of reserve A and B for a particular day for a particular Tutor         
     * @Params: @membeID - User ID of Member
     * @Params: @tutor - Tutot ID of Teacher
     * @Params: @date (format Y, M, D)
     * Returns: @total (number of reserved A and B in a particular day)
    */    
    public function getTotalTutorDailyReserved($memberID, $tutorID, $date) 
    {      

        $nextDay = date("Y-m-d", strtotime($date . " + 1 day"));

        $reserved = ScheduleItem::whereRaw("(lesson_time >= ? AND lesson_time <= ?)", [$date, $nextDay." 00:30:00"])
                        ->where('member_id', $memberID)
                        ->where('tutor_id', $tutorID)  
                        ->where('valid', 1)          
                        ->where('schedule_status', 'CLIENT_RESERVED')
                        ->count();                           

        $reserved_b = ScheduleItem::whereRaw("(lesson_time >= ? AND lesson_time <= ?)", [$date, $nextDay." 00:30:00"])
                        ->where('member_id', $memberID)
                        ->where('tutor_id', $tutorID)  
                        ->where('valid', 1)          
                        ->where('schedule_status', 'CLIENT_RESERVED_B')                                                    
                        ->count();                           
                    
              
              
        $total = $reserved + $reserved_b;

        return $total;
    }


    /*  @description : Get the time interval of the current time 
        @returns     : Returns current time lesson interval
                        Lesson is null - Returns current time lesson interval
        @foramt      :  date('Y-m-d H:i:s')
    */

    function getCurrentTimeDuration($lessonTime = null) 
    {
        $currenTime = date('Y-m-d H:i:s');

        if ($lessonTime == null) {        
            $lessonDate = $this->adjustToJapaneseHours($currenTime);    
        } else {        
            $lessonDate =  $this->adjustToJapaneseHours($lessonTime);
        }

        return $this->calculateLessonStartTime($lessonDate);
    }


    /* 
        @DESCRIPTION: Base on the date, it will get the time in within30 minute 

        @note:  A LESSON THAT FALLS ON 00:00:00 till 00:30:00 will be considered a day before 24:00:00 
    */
    public function adjustToJapaneseHours($lessonTime) 
    {
        if (date('H', strtotime($lessonTime)) == '00') {
            return date('Y-m-d H:i:s', strtotime($lessonTime ." - 1 day"));            
        } else {    
            return date('Y-m-d H:i:s', strtotime($lessonTime)); 
        }
    }

    /* 
        @DESCRIPTION: Base on the date, it will get the time in within30 minute 
    */

    public function calculateLessonStartTime($date) 
    {

        $day        =  date('Y-m-d', strtotime($date));
        $hour       = date('H', strtotime($date));
        $min        = date('i', strtotime($date));
        $seconds    = "00";    

        if ($min >= 0 && $min <= 25) {
            $minute = "00";
        } else if ($min > 25 && $min <= 59) {
            $minute = "30";        
        }  else {
            $minute = "00";
        }

        return ($day ." ". $hour.":".$minute.":".$seconds);
    }


    /* Returns the Schedules based on Lesson Time, month, year */
    /*
    public function getTotalLessonReserved_version1($memberID, $month, $year) 
    {
        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $year)
                    ->whereMonth('lesson_time','=', $month)
                    ->where('schedule_status', '=', "CLIENT_RESERVED")      
                    //->where('valid', 1)
                    ->count();

        $reserved_b = ScheduleItem::where('member_id', $memberID)
            ->whereYear('lesson_time', '=', $year)
            ->whereMonth('lesson_time','=', $month)
            ->where('schedule_status', '=', "CLIENT_RESERVED_B")                       
            //->where('valid', 1)
            ->count();                       

        $completed = ScheduleItem::where('member_id', $memberID)
            ->whereYear('lesson_time', '=', $year)
            ->whereMonth('lesson_time','=', $month)
            ->where('schedule_status', '=', "COMPLETED")                       
            ->where('valid', 1)
            ->count();

        $not_available = ScheduleItem::where('member_id', $memberID)
            ->whereYear('lesson_time', '=', $year)
            ->whereMonth('lesson_time','=', $month)
            ->where('schedule_status', '=', "CLIENT_NOT_AVAILABLE")                       
            //->where('valid', 1)
            ->count();            

        $reserveCount = $reserved + $reserved_b + $completed + $not_available;

        return $reserveCount;        
    }
    */

    /* Returns the Schedules based on Lesson Time for current month*/
    /*
    public function getTotalLessonForCurrentMonth_version1($memberID) 
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "CLIENT_RESERVED")                       
                    //->where('valid', 1)
                    ->count();
        
        $reserved_b = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "CLIENT_RESERVED_B")                       
                    //->where('valid', 1)
                    ->count();                    
                    
        $completed = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "COMPLETED")                       
                    //->where('valid', 1)
                    ->count();

        $not_available = ScheduleItem::where('member_id', $memberID)
                        ->whereYear('lesson_time', '=', $currentYear)
                        ->whereMonth('lesson_time','=', $currentMonth)
                        ->where('schedule_status', '=', "CLIENT_NOT_AVAILABLE")                       
                        //->where('valid', 1)
                        ->count();
                        
        $reserveCount = $reserved + $reserved_b + $completed + $not_available;

        return $reserveCount;
    }
    */

    /* Returns the Total Schedule Count for the current month based on lesson Time (active or inactive will) */
    /*
    public function getTotalReservedForCurrentMonth_version1($memberID) 
    {
        //CLient rserve / Client reserve B / Completed /Client not available
        $currentYear = date('Y');
        $currentMonth = date('m');

        
        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "CLIENT_RESERVED")                       
                    //->where('valid', 1)
                    ->count();

        
        $reserved_b = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "CLIENT_RESERVED_B")                       
                    //->where('valid', 1)
                    ->count();                    
                    
        $completed = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "COMPLETED")                       
                    //->where('valid', 1)
                    ->count();

        $not_available = ScheduleItem::where('member_id', $memberID)
                        ->whereYear('lesson_time', '=', $currentYear)
                        ->whereMonth('lesson_time','=', $currentMonth)
                        ->where('schedule_status', '=', "CLIENT_NOT_AVAILABLE")                       
                        //->where('valid', 1)
                        ->count();

        $reserveCount = $reserved + $reserved_b + $completed + $not_available;   
                    
        return $reserveCount;
    }
    */


    /*
        Description: This will check how many reserved for a specific month and year for a client
        Parameters:
            @month - the month of the schedule
            @year  - the year of the schedule
            @memberID - member ID of user        
    */
    public function getTotalLessonReserved($memberID, $month, $year) 
    {

        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date("$year-$month-01 09:00:00")));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";
        
        
        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")      
                    //->where('valid', 1)
                    ->count();

        $reserved_b = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED_B")                       
                    //->where('valid', 1)
                    ->count();                       

        $completed = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "COMPLETED")                       
                    ->where('valid', 1)
                    ->count();

        $not_available = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                        ->where('schedule_status', '=', "CLIENT_NOT_AVAILABLE")                       
                        //->where('valid', 1)
                        ->count();            

        $reserveCount = $reserved + $reserved_b + $completed + $not_available;

        return $reserveCount;        
    }    

    public function getTotalLessonForCurrentMonth($memberID) 
    {
        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date('Y-m-01 09:00:00')));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";

        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")                       
                    //->where('valid', 1)
                    ->count();
        
        $reserved_b = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED_B")                       
                    //->where('valid', 1)
                    ->count();                    
                    
        $completed = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "COMPLETED")                       
                    //->where('valid', 1)
                    ->count();

        $not_available = ScheduleItem::where('member_id', $memberID)
                        ->whereBetween('lesson_time', [$startDate, $endDate])
                        ->where('schedule_status', '=', "CLIENT_NOT_AVAILABLE")                       
                        //->where('valid', 1)
                        ->count();
                        

        $writingPoints = WritingEntries::where('user_id', $memberID)
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->where('type', 'Monthly')
                            ->sum('total_points');


        $miniTestCount =  MiniTestResult::where('user_id', $memberID)
                            ->whereBetween('time_started', [$startDate, $endDate])
                            ->where('type', 'Monthly')->count();

        

        $reserveCount = $reserved + $reserved_b + $completed + $not_available + $writingPoints + $miniTestCount;

        return $reserveCount;
    }
    
    
    public function getTotalReservedForCurrentMonth($memberID) 
    {
        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date('Y-m-1 01:00:00')));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";

        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")
                    //->where('valid', 1)
                    ->count();                    

        
        $reserved_b = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED_B")                       
                    //->where('valid', 1)
                    ->count();                    
                    
        $completed = ScheduleItem::where('member_id', $memberID)
                        ->whereBetween('lesson_time', [$startDate, $endDate])
                        ->where('schedule_status', '=', "COMPLETED")                       
                        //->where('valid', 1)
                        ->count();

        $not_available = ScheduleItem::where('member_id', $memberID)
                        ->whereBetween('lesson_time', [$startDate, $endDate])
                        ->where('schedule_status', '=', "CLIENT_NOT_AVAILABLE")                       
                        //->where('valid', 1)
                        ->count();

      
        $writingPoints = WritingEntries::where('user_id', $memberID)
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->where('type', 'Monthly')
                                ->sum('total_points');


        $miniTestCount =  MiniTestResult::where('user_id', $memberID)
                            ->whereBetween('time_started', [$startDate, $endDate])
                            ->where('type', 'Monthly')
                            ->count();

      

        $reserveCount = $reserved + $reserved_b + $completed + $not_available + $writingPoints + $miniTestCount ;
                    
        return $reserveCount;
    }
    


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
                'memo' => $item->memo,
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
                'lastname' => preg_replace('/[^A-Za-z0-9]/', ' ', $lastname),
                'maid' => $item->member_multi_account_id,
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

    /* live version*/
    public function getSchedules_version1($date, $duration)
    {
        $nextDay = date("Y-m-d", strtotime($date . " + 1 day"));

        //$scheduleItems = ScheduleItem::whereBetween(DB::raw('DATE(lesson_time)'), array($date, $nextDay))->where('valid', 1)->get();

        $scheduleItems = ScheduleItem::whereDate('lesson_time', '=', $date)
                            ->where('valid', 1)
                            ->orWhereDate('lesson_time', '=',  $nextDay . " 00:30:00")
                            ->orWhereDate('lesson_time', '=',  $nextDay . " 00:00:00")                            
                            ->get();

        $reportCard = new ReportCard();

        $schedules = [];
        foreach ($scheduleItems as $item) 
        {

            //add questionnair marker
            $questionnaire = Questionnaire::where('schedule_item_id', $item->id)->first();
            if ($questionnaire) {
                $hasQuestionnaire = true;
            } else {
                $hasQuestionnaire = false;
            }


            //add report card marker
            $memberReportCard = $reportCard->getReportbyScheduleItemID($item->id);
            if ($memberReportCard) {
                $hasReportCard = true;
            } else {
                $hasReportCard = false;
            }

            if ($item->valid === 1 || $item->valid === '1') 
            {                
                $schedules[$item->tutor_id][date('Y-m-d', strtotime($item->lesson_time))][date("H:i", strtotime($item->lesson_time . " -1 hour"))] = [
                    'valid' => $item->valid,
                    'id' => $item->id,
                    'status' => $item->schedule_status,
                    'startTime' => date("H:i", strtotime($item->lesson_time . " -1 hour")),
                    'endTime' => date("H:i", strtotime($item->lesson_time)),
                    'scheduled_at' => date('Y-m-d', strtotime($item->lesson_time)),
                    'email_type' => $item->email_type,
                    'duration' => $item->duration,                
                    'member_id' => $item->member_id,
                    'member_memo' => $item->memo,
                    'hasReportCard' => $hasReportCard,
                    'hasQuestionnaire' => $hasQuestionnaire
                    //'questionnaire' => $questionnaire,
                ];
            }


        }

        return $schedules;
    }

    public function getSchedules($date, $duration)
    {
        $nextDay = date("Y-m-d", strtotime($date . " + 1 day"));

        //$scheduleItems = ScheduleItem::whereBetween(DB::raw('DATE(lesson_time)'), array($date, $nextDay))->where('valid', 1)->get();

        $scheduleItems = ScheduleItem::whereDate('lesson_time', '=', $date)
                            ->where('valid', 1)
                            ->orWhereDate('lesson_time', '=',  $nextDay . " 00:30:00")
                            ->orWhereDate('lesson_time', '=',  $nextDay . " 00:00:00")                            
                            ->get();

        $reportCard = new ReportCard();
        $member = new Member();


        $schedules = [];
        foreach ($scheduleItems as $item) 
        {
           

            //add questionnair marker
            $questionnaire = Questionnaire::where('schedule_item_id', $item->id)->first();
            if ($questionnaire) {
                $hasQuestionnaire = true;
            } else {
                $hasQuestionnaire = false;
            }


            //add report card marker
            $memberReportCard = $reportCard->getReportbyScheduleItemID($item->id);
            
            if ($memberReportCard) {
                $hasReportCard = true;
            } else {
                $hasReportCard = false;
            }

           

            if ($item->valid === 1 || $item->valid === '1') 
            {                
                $userMemoValid = null;

                //the memo from schedule is null, letst take a look at the memo replies if there are sent by teacher
                if ($item->memo) {
                    $userMemoValid = true;
                } else {
                    $memoReplies = MemoReply::where('schedule_item_id', $item->id)->count();
                    if ($memoReplies >= 1) {
                        $userMemoValid = true;
                    } 
                }

                $schedules[$item->tutor_id][date('Y-m-d', strtotime($item->lesson_time))][date("H:i", strtotime($item->lesson_time . " -1 hour"))] = [
                    'valid' => $item->valid,
                    'id' => $item->id,
                    'status' => $item->schedule_status,
                    'startTime' => date("H:i", strtotime($item->lesson_time . " -1 hour")),
                    'endTime' => date("H:i", strtotime($item->lesson_time)),
                    'scheduled_at' => date('Y-m-d', strtotime($item->lesson_time)),
                    'email_type' => $item->email_type,
                    'duration' => $item->duration,                
                    'member_id' => $item->member_id,
                    "nickname" => $member->getNickname($item->member_id),
                    'member_memo' => $userMemoValid,
                    'hasReportCard' => $hasReportCard,
                    'hasQuestionnaire' => $hasQuestionnaire,
                    "maid" => $item->member_multi_account_id , //multi accountid
                ];
            }


        }

        return $schedules;
    }

    public function testSchedulOptimize($date, $duration) {

        $nextDay = date("Y-m-d", strtotime($date . " + 1 day"));


        $scheduleItems = ScheduleItem::select('*', 
            DB::raw('(SELECT COUNT(*) FROM  questionnaire WHERE  questionnaire.schedule_item_id = schedule_item.id) AS has_questionnaire'),
            DB::raw('(SELECT COUNT(*) FROM report_card WHERE report_card.schedule_item_id = schedule_item.id) AS has_reportcard'),
            DB::raw('(SELECT COUNT(*) FROM memo_replies WHERE memo_replies.schedule_item_id = schedule_item.id) AS has_memoreplies'),
        )
        ->whereDate('lesson_time', '=', $date)
        ->where('valid', 1)
        ->orWhere(function ($subQuery) use ($nextDay) {
            $subQuery->whereDate('lesson_time', '=', $nextDay . " 00:30:00")
                    ->orWhereDate('lesson_time', '=', $nextDay . " 00:00:00");
        })
        ->get();
     
        
        $schedules = [];

        foreach ($scheduleItems as $item) {
        
            $hasQuestionnaire = $item->has_questionnaire > 0;
            $hasReportCard = $item->has_reportcard > 0;
            $hasMemoReplies = $item->has_memoreplies > 0;

            $userMemoValid = null;
            
            if ($item->valid === 1 || $item->valid === '1') {
                $userMemoValid = $item->memo || $hasMemoReplies;
            }
        
            $schedules[$item->tutor_id][date('Y-m-d', strtotime($item->lesson_time))][date("H:i", strtotime($item->lesson_time . " -1 hour"))] = [
                'valid' => $item->valid,
                'id' => $item->id,
                'status' => $item->schedule_status,
                'startTime' => date("H:i", strtotime($item->lesson_time . " -1 hour")),
                'endTime' => date("H:i", strtotime($item->lesson_time)),
                'scheduled_at' => date('Y-m-d', strtotime($item->lesson_time)),
                'email_type' => $item->email_type,
                'duration' => $item->duration,
                'member_id' => $item->member_id,
                'member_memo' => $userMemoValid,
                'hasReportCard' => $hasReportCard,
                'hasQuestionnaire' => $hasQuestionnaire
            ];
        }
        
        return $schedules;        
    }

    //List ALL specific Member schedules
    public function getMemberScheduledLesson($memberID)
    {
        /*
        $lessons = ScheduleItem::select('schedule_item.*', 'users.firstname', 'users.lastname')
            //->join('tutors', 'tutors.user_id', '=', 'schedule_item.tutor_id')
            ->join('users', 'users.id', '=', 'schedule_item.tutor_id')
            ->where('member_id', $memberID)
            //->where('schedule_item.valid', 1)
            ->orderBy('lesson_time', 'desc')
            ->paginate(Auth::user()->items_per_page);
        */

             $lessons = ScheduleItem::where('member_id', $memberID)
            //->where('schedule_item.valid', 1)
            //->orderBy('created_at', 'desc')
            ->orderBy('lesson_time', 'desc')
            ->paginate(Auth::user()->items_per_page);


        return $lessons;
    }

    //List ALL specific Member schedules without minitest
    public function getMemberScheduledLessonExcludingMiniTest($memberID)
    {
        $lessons = ScheduleItem::where('member_id', $memberID)
        //->where('schedule_item.valid', 1)    
        ->where('schedule_item.schedule_status', '<>', 'minitest')       
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

    public function multiAccount() 
    {
        return $this->hasOne(MemberMultiAccountAlias::class, 'member_multi_account_id', 'member_multi_account_id');       
    }
}
