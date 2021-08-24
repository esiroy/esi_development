<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AgentTransaction;
use App\Models\LessonMailer;
use App\Models\Member;
use App\Models\UserImage;
use App\Models\MemberAttribute;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;
use App\Models\ReportCard;
use App\Models\ScheduleItem;
use App\Models\MemoReply;
use App\Models\Shift;
use App\Models\Tutor;
use DB, Storage, App;

class TutorScheduleController extends Controller
{

    public function getMembers(Request $request)
    {
        try {

            //DB::table('members')->
            $members = DB::table('members')->join('users', 'users.id', '=', 'members.user_id')
                ->select('members.user_id as uid', 'members.nickname as nn')
                ->where('users.valid', 1)
                ->get();

            return Response()->json([
                "success" => true,
                "members" => $members,
            ]);

        } catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Exception Error Found (Get Members) : " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);
        }
    }

    public function getMembersDropdownOptions(Request $request)
    {
        try {

            //DB::table('members')->
            $members = DB::table('members')->join('users', 'users.id', '=', 'members.user_id')
                ->select('members.user_id as uid', 'users.firstname as fn', 'users.lastname as ln')
                ->where('users.valid', 1)
                ->get();

            return Response()->json([
                "success" => true,
                "members" => $members,
            ]);

        } catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Exception Error Found (Get Members) : " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);
        }
    }

    public function getSchedules(Request $request)
    {

        $scheduled_at = $request['scheduled_at'];
        $duration = $request['shiftDuration'];

        $scheduleItem = new ScheduleItem();
        $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);

        return Response()->json([
            "success" => true,
            "tutorLessonsData" => $tutorLessonsData,
        ]);
    }

    
    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
 
        $lessonData = null;
        $scheduled_at = $request['scheduled_at'];
        $duration = $request['shiftDuration'];
        
        $scheduledItemData = $request['scheduledItemData'];
        
        //find schedule to update
        $scheduleItem = ScheduleItem::find($scheduledItemData['id']);
        $reservationType = $scheduleItem->schedule_status; //previous reservation type

        //added JUN 18, 2021
        if ($request['status'] == 'CLIENT_NOT_AVAILABLE') 
        {            
            $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);

            if ($scheduleItem->schedule_status == 'CLIENT_RESERVED' || $scheduleItem->schedule_status == 'CLIENT_RESERVED_B' || $scheduleItem->schedule_status == 'SUPPRESSED_SCHEDULE' || $scheduleItem->schedule_status == 'COMPLETED' ) {

            } else {
            
                return Response()->json([
                    "success" => false,
                    "refresh" => true,
                    "schedulestatus" => $scheduleItem->schedule_status,
                    'tutorLessonsData' => $tutorLessonsData,
                    "message" => "ERROR: cannot process request due to outdated schedule or schedule is not valid client reservation, press okay to refresh schedules",
                ]);    
            }
        }

        try
        {
            DB::beginTransaction();


            $tutor = $request['tutorData'];
            $member = $request['memberData'];
            $tutorInfo = Tutor::find($tutor['tutorID']);
            $lessonTime = date("Y-m-d H:i:s", strtotime($scheduled_at . " " . $tutor['startTime'] . " + 1 hour")); //JAPANESE TIMIE (1 HOUR ADVANCE)



            if (isset($member['id'])) {
                $memberID = $member['id'];
            } else {
                $memberID = null;
            }

            $memberInfo = Member::where('user_id', $member['id'])->first();
            if ($memberInfo) {
                $memberData = [
                    'id' => $memberInfo->user_id,
                    'nickname' => $memberInfo->nickname,
                    'firstname' => preg_replace('/[^A-Za-z0-9]/', ' ', $memberInfo->user->firstname),
                    'lastname' => preg_replace('/[^A-Za-z0-9]/', ' ', $memberInfo->user->lastname),
                ];
            } else {
                $memberData = [
                    'id' => "",
                    'nickname' => "",
                    'firstname' => "",
                    'lastname' => "",
                ];
            }


            /****************************************************
             * UPDATE (NEW) 
             * DATE: JUNE 2021
             * DESCRIPTION: CHECK IF LESSON EXIST
             * NOTE: THIS WILL ONLY CHECK FOR CLIENT RESERVED A OR B AND SUPPRESSED SCHEDULE
             ****************************************************/
            if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') 
            {
                //WHEN CLIENT IS RESERVED IT WILL NOT LET IT BOOK, IT WILL PROMPT TO REFRESH
                $isLessonExists = ScheduleItem::where('lesson_time', $lessonTime)->where('tutor_id', $tutorInfo->user_id)->where('schedule_status', '!=', 'TUTOR_SCHEDULED')->where('valid', 1)->exists();            
                if ($isLessonExists) {                
                    $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);
                    return Response()->json([
                        "success" => false,
                        "refresh" => true,
                        'tutorLessonsData' => $tutorLessonsData,
                        "message" => "The Schedule $lessonTime is already booked or taken, press okay to refresh schedules.",
                    ]);                    
                }
            } else if ($request['status'] == 'TUTOR_SCHEDULED' ||  $request['status'] == 'SUPPRESSED_SCHEDULE' || $request['status'] == 'CLIENT_NOT_AVAILABLE' || $request['status'] == 'COMPLETED') {

                $isLessonExists = ScheduleItem::where('lesson_time', $lessonTime)->where('tutor_id', $tutorInfo->user_id)->where('member_id', '!=', $memberID)->where('valid', 1)->exists();            
                if ($isLessonExists) {                
                    $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);
                    return Response()->json([
                        "success" => false,
                        "refresh" => true,
                        'tutorLessonsData' => $tutorLessonsData,
                        "message" => "The Schedule $lessonTime is already booked or was updated after the page load, press okay to refresh schedules.",
                    ]);                    
                }
            }
            
                
            /****************************************************
             *  START UPDATING             
             ****************************************************/
            if ($request['status'] == 'TUTOR_SCHEDULED') {

                $emailType = null;
                $lessonData = [
                    'member_id' => null,
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];

            } else if ($request['status'] == 'TUTOR_CANCELLED') {

                $emailType = $request['cancelationType'];
                $lessonData = [
                    'member_id' => $memberID,
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];

            } else if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') {

                //check deactivated
                if ($memberInfo->user->is_activated == false) {
                    return Response()->json([
                        "success" => false,
                        "message" => "Error: Can not reserve schedule, the user is currently deactivated",
                    ]);
                }

                $emailType = $request['reservationType'];

                $lessonData = [
                    'member_id' => $memberID,
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];


                //CHECK IF LESSON EXIST ON SAME TIME AND SAME DATE, FLASH MESSAGE IF EXISTED ALREADY
                /*
                $isLessonExists = ScheduleItem::where('lesson_time', $lessonTime)->where('tutor_id', $tutorInfo->user_id)->where('valid', 1)->exists();
                if ($isLessonExists) 
                {
                    $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);
                    return Response()->json([
                        "success" => false,
                        "refresh" => true,
                        'tutorLessonsData' => $tutorLessonsData,
                        "message" => "The Schedule $lessonTime is already booked, press okay to refresh schedules."
                    ]);
                }*/                                

                /****************************************************
                 *    DUPLICATE MEMBER LESSON TIME CHECKER (UPDATE)
                 *****************************************************/
                $isTutorReserved = ScheduleItem::where('id', '!=', $scheduleItem->id)->where('lesson_time', $lessonTime)->where('member_id', $memberID)->where('schedule_status', 'CLIENT_RESERVED')->where('valid', 1)->exists();
                $isTutorReserved_b = ScheduleItem::where('id', '!=', $scheduleItem->id)->where('lesson_time', $lessonTime)->where('member_id', $memberID)->where('schedule_status', 'CLIENT_RESERVED_B')->where('valid', 1)->exists();
                if ($isTutorReserved || $isTutorReserved_b) {
                    return Response()->json([
                        "success" => false,
                        "refresh" => true,
                        "message" => "Member already have a booked schedule for this  $lessonTime  time slot, try booking other time slot for this member.",
                    ]);
                }

                /****************************************************
                 *       NEW MEMBER POINTS AND ATTRIBUTES CHECKER
                 *****************************************************/                
                //1. CHECK IF POINTS IS EXPIRED
                if ($memberInfo->isMemberCreditExpired($memberID)) {
                    return Response()->json([
                        "success" => false,
                        //"message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                        //"message_en" => "You are out of points or your points have expired.", 
                        "message" => "Member credits expired",
                        "message_en" => "member credits expired"
                    ]);
                }

                //2. CHECK IF MEMBER HAS ENOUGH POINTS TO BOOK A SCHEDULE
                $agentCredts = new AgentTransaction();
                if ($agentCredts->getCredits($memberID) <= 0) {
                    return Response()->json([
                        "success" => false,                        
                        //"message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                        //"message_en" => "You are out of points or your points have expired.",

                        "message" => "Member out of credits",
                        "message_en" => "out of credits"
                    ]);
                } //END CREDIT CHECKER                

                //3. CHECK IF LESSON TIME IS INVALID
                if (!$memberInfo->isReservedLessonValid($memberID, $lessonTime)) {            
                    return Response()->json([
                        "success" => false,
                        "message" => "you selected a schedule is past member's expiration date",
                        "message_en" => "you selected a schedule is past member's expiration date.",       
                    ]); 
                } 

                //compare current lesson limit and total month total reserved schedules
                $memberAttribute = new MemberAttribute();
                $check_month_limit = date("M", strtotime($request['scheduled_at']));
                $check_year_limit = date("Y", strtotime($request['scheduled_at']));
                $attribute = $memberAttribute->getLessonLimit($memberID, $check_month_limit, $check_year_limit);

                if ($attribute) {
                    //USER HAS ATTRIBUTE BUT EXCEEDED THE LIMIT
                    $limit = $attribute->lesson_limit;
                    //$currentMonthTotalReserves = $scheduleItem->getTotalLessonForCurrentMonth($memberID);

                    //check if there if it is not over the lesson limit capacity
                    $month_to_reserve = date("m", strtotime($request['scheduled_at']));
                    $year_to_reserve = date("Y", strtotime($request['scheduled_at']));
                    $totalReserved = $scheduleItem->getTotalLessonReserved($memberID, $month_to_reserve, $year_to_reserve);
                    if ($totalReserved >= $limit) {
                        return Response()->json([
                            "success" => false,
                            //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                            //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",

                            //"message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                            //"message_en" => "No points / monthly limit or Credit Balance",

                            "message" => "Member Monthly Reservation limit Reached",
                            "message_en" => "Member Monthly Reservation limit Reached"
                        ]);
                    }
                } else {

                    //USER HAS NOT ADDED ANY ATTRIBUTE OR MONTHLY LIMIT YET!
                    return Response()->json([
                        "success" => false,
                        //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                        //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",
                        "message" => "Member Monthly Reservation limit Reached",
                        "message_en" => "Member Monthly Reservation limit Reached",
                    ]);
                } //END MEMBER ATTRIBUTE CHECKER

            } else {
                $emailType = null;
                $lessonData = [
                    'schedule_status' => $request['status'],
                    'member_id' => $memberID,                    
                    'email_type' => $emailType,
                    'valid' => 1,
                ];
            }

            //@todo: update only when exists?
            $scheduleItem->update($lessonData);
            DB::commit();

            //** ADD MEMBER TRANSACTION */          
            if ($memberID != null) {
                $memberTransactionData = [
                     //'scheduleItem'      => $scheduleItem,
                    'scheduleItemID'      => $scheduledItemData['id'],
                    'memberID' => $memberID,
                    'shiftDuration' => $request['shiftDuration'],
                    'reservation_type' => $reservationType,
                    'status' => $request['status'],
                ];
                $transactionObj = new AgentTransaction();
                $transaction = $transactionObj->addMemberTransactions($memberTransactionData);
            }

            //get lessons
            $scheduleItem = new ScheduleItem();
            $scheduled_at = $request['scheduled_at'];
            $duration = $request['shiftDuration'];

            $selectedSchedule = $scheduleItem->find($scheduledItemData['id']);

            /****************************************************
             *      [START] SEND MAIL TO MEMBER AND TUTOR
             *****************************************************/
            //if (App::environment(['prod', 'production'])) 
            //{                
                $lessonMailer = new LessonMailer();
                $lessonMailer->send($memberInfo, $tutorInfo, $selectedSchedule);
            //}
            /****************************************************
             *      [END] SEND MAIL TO MEMBER AND TUTOR
             *****************************************************/            

            if ($selectedSchedule->memo == null) {
                $hasMemo = null;
            } else {
                $hasMemo = true;
            }

            return Response()->json([
                "success" => true,
                "memberID" => $memberID,
                "message" => "Lesson has been updated",
                "scheduleItemID" => $scheduledItemData['id'],
                "member_memo" => $hasMemo,
                "tutorData" => $tutor,
                "memberData" => $memberData,
                //'tutorLessonsData' => $tutorLessonsData,
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            return Response()->json([
                "success" => false,
                "message" => "Exception Error Found in (Update Tutor Schedule) : " . $e->getMessage() . " on Line " . $e->getLine(),
            ]);
        }

    }

    /**
     * Store a newly created schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ScheduleItem $scheduleItem)
    {
        $lessonData = null;
        $scheduled_at = $request['scheduled_at'];
        $duration = $request['shiftDuration'];
        $tutor = $request['tutorData'];
        $member = $request['memberData'];

        if (isset($member['id'])) {
            $memberID = $member['id'];
        } else {
            $memberID = null;
        }

        try {

            DB::beginTransaction();

            if ($request['status'] == 'TUTOR_CANCELLED') 
            {

                $emailType = $request['cancelationType'];

            } else if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') {

                $emailType = $request['reservationType'];

            } else {

                $emailType = null;
            }

            //Member Info
            $memberInfo = Member::where('user_id', $member['id'])->first();
            //check memberInfo
            if ($memberInfo) {
                $memberData = [
                    'id' => $memberInfo->user_id,
                    'nickname' => $memberInfo->nickname,
                    'firstname' => preg_replace('/[^A-Za-z0-9]/', ' ', $memberInfo->user->firstname),
                    'lastname' => preg_replace('/[^A-Za-z0-9]/', ' ', $memberInfo->user->lastname),
                ];
            } else {
                $memberData = [
                    'id' => "",
                    'nickname' => "",
                    'firstname' => "",
                    'lastname' => "",
                ];
            }

            $tutorInfo = Tutor::find($tutor['tutorID']);
            $lessonTime = date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] . " + 1 hour")); //JAPANESE TIMIE (1 HOUR ADVANCE)
            $schedule_status = str_replace(' ', '_', $request['status']);
            $shift = Shift::where('value', $request['shiftDuration'])->first();

            //STORE TO SCHEDULE ITEM TABLE
            $lessonData = [
                'lesson_time' => $lessonTime, //save the japanese time
                'duration' => $request['shiftDuration'],
                'email_type' => $emailType,
                'tutor_id' => $tutorInfo->user_id,
                'member_id' => $member['id'],
                'schedule_status' => $schedule_status,
                'duration' => $request['shiftDuration'],
                'lesson_shift_id' => $shift->id,
                'valid' => 1,
                'memo' => "",
            ];

            /****************************************************
             * STORE (NEW) 
             * DATE: JUNE 2021
             * DESCRIPTION: CHECK IF LESSON EXIST ON SAME TIME AND SAME DATE, FLASH MESSAGE IF EXISTED ALREADY             
             ****************************************************/
            $isLessonExists = ScheduleItem::where('lesson_time', $lessonTime)->where('tutor_id', $tutorInfo->user_id)->where('valid', 1)->exists();
            if ($isLessonExists) {
                $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);
                return Response()->json([
                    "success" => false,
                    "refresh" => true,
                    'tutorLessonsData' => $tutorLessonsData,
                    "message" => "The Schedule $lessonTime is already booked, press okay to refresh schedules.",
                ]);
            }
            
            //CHECK IF MEMBER HAS OTHER BOOKED SCHEDULE ON THIS TIME SLOT?
            if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') 
            {
                //CHECK IF USER IS DEACTIVATED
                if ($memberInfo->user->is_activated == false) {
                    return Response()->json([
                        "success" => false,
                        "message" => "Error: Can not reserve schedule, the user is currently deactivated",
                    ]);
                }

                /****************************************************
                 *       DUPLICATE MEMBER LESSON TIME CHECKER
                 *****************************************************/
                $isTutorReserved = ScheduleItem::where('lesson_time', $lessonTime)->where('member_id', $memberID)->where('schedule_status', 'CLIENT_RESERVED')->where('valid', 1)->exists();
                $isTutorReserved_b = ScheduleItem::where('lesson_time', $lessonTime)->where('member_id', $memberID)->where('schedule_status', 'CLIENT_RESERVED_B')->where('valid', 1)->exists();
                if ($isTutorReserved || $isTutorReserved_b) {
                    return Response()->json([
                        "success" => false,
                        "refresh" => false,
                        "message" => "Member already have a booked schedule for this  $lessonTime  time slot, try booking other time slot for this member.",
                    ]);
                }

                /****************************************************
                 *       NEW MEMBER POINTS AND ATTRIBUTES CHECKER
                 *****************************************************/
                $agentCredts = new AgentTransaction();

                //1. CHECK IF POINTS IS EXPIRED
                if ($memberInfo->isMemberCreditExpired($memberID)) {
                    return Response()->json([
                        "success" => false,
                        //"message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                        //"message_en" => "You are out of points or your points have expired.",
                        "message" => "member credits expired",
                        "message_en" => "member credits expired"
                    ]);
                }

                //2. CHECK IF MEMBER HAS ENOUGH POINTS TO BOOK A SCHEDULE
                $agentCredts = new AgentTransaction();
                if ($agentCredts->getCredits($memberID) <= 0) {
                    return Response()->json([
                        "success" => false,                        
                        //"message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                        //"message_en" => "You are out of points or your points have expired.",

                        "message" => "out of credits",
                        "message_en" => "out of credits"
                    ]);
                } //END CREDIT CHECKER

                //3. CHECK IF LESSON TIME IS INVALID
                if (!$memberInfo->isReservedLessonValid($memberID, $lessonTime)) {            
                    return Response()->json([
                        "success" => false,
                        "message" => "you selected a schedule is past member's expiration date",
                        "message_en" => "you selected a schedule is past member's expiration date.",       
                    ]); 
                }                 

                //compare current lesson limit and total month total reserved schedules (storing)
                $memberAttribute = new MemberAttribute();

                $check_month_limit = date("M", strtotime($request['scheduled_at']));
                $check_year_limit = date("Y", strtotime($request['scheduled_at']));
                $attribute = $memberAttribute->getLessonLimit($memberID, $check_month_limit, $check_year_limit);

                if ($attribute) {

                    $limit = $attribute->lesson_limit;
                    //$currentMonthTotalReserves = $scheduleItem->getTotalLessonForCurrentMonth($memberID);

                    //check if there if it is not over the lesson limit capacity
                    $month_to_reserve = date("m", strtotime($request['scheduled_at']));
                    $year_to_reserve = date("Y", strtotime($request['scheduled_at']));

                    $totalReserved = $scheduleItem->getTotalLessonReserved($memberID, $month_to_reserve, $year_to_reserve);

                    if ($totalReserved >= $limit) {
                        return Response()->json([
                            "success" => false,
                            //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                            //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",

                            //"message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                            //"message_en" => "No points / monthly limit or Credit Balance",


                            "message" => "Member Monthly Reservation limit Reached",
                            "message_en" => "Member Monthly Reservation limit Reached"                            
                        ]);
                    }
                } else {
                    return Response()->json([
                        "success" => false,
                        //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                        //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",
                        //"message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                        //"message_en" => "No points / monthly limit or Credit Balance",
                        "message" => "Member Monthly Reservation limit Reached",
                        "message_en" => "Member Monthly Reservation limit Reached"                        
                    ]);
                } //END MEMBER ATTRIBUTE CHECKER
            }

            $scheduleItem = ScheduleItem::create($lessonData);

            DB::commit();

            //** ADD MEMBER TRANSACTION */
            if ($memberID != null) {
                $memberTransactionData = [
                    //'scheduleItem'      => $scheduleItem,
                    'scheduleItemID'      => $scheduleItem->id,
                    'memberID' => $memberID,
                    'reservation_type' => $request['status'],
                    'shiftDuration' => $request['shiftDuration'],
                    'status' => $request['status'],
                ];

                $transactionObj = new AgentTransaction();
                $transaction = $transactionObj->addMemberTransactions($memberTransactionData);
            }

            $selectedSchedule = ScheduleItem::find($scheduleItem->id);

            /*******************************************
             *  [START] SEND E-MAIL (JOB) RESERVED
             *******************************************/
            if (App::environment(['prod', 'production'])) 
            {                
                if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') {
                    $lessonMailer = new LessonMailer();
                    $lessonMailer->send($memberInfo, $tutorInfo, $selectedSchedule);
                }
            }
            /*******************************************
             *       [END] SEND MAIL (JOB) RESERVED
             *******************************************/

            //$tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);            

            return Response()->json([
                "success" => true,
                "message" => "Lesson has been added",
                "scheduleItemID" => $scheduleItem->id,
                "tutorData" => $request['tutorData'],
                "memberData" => $memberData,
                //'tutorLessonsData' => $tutorLessonsData, //@todo: replace this MEMOMY HUGGER WITH ID JS
            ]);

        } catch (\Exception $e) {
            
            DB::rollback();

            return Response()->json([
                "success" => false,
                "message" => "Exception Error Found (Create Tutor Schedule) : " . $e->getMessage() . " on Line " . $e->getLine(),
            ]);
        }

    }

    public function deleteTutorSchedule(Request $request)
    {
        $data = $request['scheduleData'];
        $tutorID = $data['tutorID'];
        $startTime = $data['startTime'];
        $endTime = $data['endTime'];
        $scheduled_at = $request['scheduled_at'];
        $duration = $request['shiftDuration'];

        //change to schedule item for max compatibility
        $lessonTime = date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $startTime . " + 1 hour"));

        $tutor = Tutor::find($tutorID);

        $schedule = ScheduleItem::where('tutor_id', $tutor->user_id)->where('duration', $duration)->where('lesson_time', $lessonTime)->first();

        if ($schedule) {
            //set the deleted schedule id
            $scheduleID = $schedule->id;

            //remove report card
            $reportCard = ReportCard::where('schedule_item_id', $scheduleID)->first();
            if ($reportCard) {
                $reportCard->delete();
            }

            //search delete questionnaire
            $questionnaire = Questionnaire::where('schedule_item_id', $scheduleID)->where('valid', true)->first();

            if ($questionnaire) {
                //@todo: search to delete questionnairre items
                $questionnaireItems = QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->get();
                foreach ($questionnaireItems as $questionnaireItem) {
                    $questionnaireItem->delete();
                }
                $questionnaire->delete();
            }

            //[updated - July 1] remove replies when removing a schedule
            $memoReplies =  MemoReply::where('schedule_item_id', $scheduleID)->get();
            foreach ($memoReplies as $memoReply) {
                $memoReply->delete();
            }

            $schedule->delete();

           

            //refetch schedule items
            $scheduleItem = new ScheduleItem();
            $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);
            return Response()->json([
                "success" => true,
                "message" => "lesson deleted",
                "tutorData" => $data,
                "tutorLessonsData" => $tutorLessonsData,
            ]);

        } else {
            return Response()->json([
                "success" => false,
                "message" => "lesson has been already deleted",
            ]);
        }

    }


    public function getAllMemoConversations(Request $request, UserImage $userImageObj) 
    {
        $scheduleID = $request->scheduleID;

        //check if the schedule is available , if not send an error message
        $scheduleItem = ScheduleItem::find($scheduleID);
        
        $memoReply = new MemoReply();
        $conversations = $memoReply->where('schedule_item_id', $scheduleID)->orderBy("created_at", 'ASC')->get();        
     
        //get teacher profile pic
        $memberImage = $userImageObj->getMemberPhotoByID($scheduleItem->member_id);         

        if ($memberImage == null) {
            $memberOrignalImage = Storage::url('user_images/noimage.jpg');
        } else {
            $memberOrignalImage = Storage::url($memberImage->original);
        }

        //get teacher profile pic       
        $tutorImage = $userImageObj->getTutorPhotoByID($scheduleItem->tutor_id);         

        if ($tutorImage == null) {
            $tutorOrignalImage = Storage::url('user_images/noimage.jpg');
        } else {
            $tutorOrignalImage = Storage::url($tutorImage->original);
        }

        if (isset($scheduleItem->memo)) {
            $memo = $scheduleItem->memo;
        } else {
            $memo = null;
        }

        if ($conversations) 
        {

            if (date('H', strtotime($scheduleItem->lesson_time)) == '00') {
                $lessonTime = date('Y年 m月 d日 24:i', strtotime($scheduleItem->lesson_time ." - 1 day")) ." - ".   date('24:i', strtotime($scheduleItem->lesson_time." + 25 minutes "));
            } else {  
                $lessonTime = date('Y年 m月 d日 H:i', strtotime($scheduleItem->lesson_time)) ." - ".  date('H:i', strtotime($scheduleItem->lesson_time." + 25 minutes "));
            }
            

            return Response()->json([
                "success"       => true,  
                "message"       => "conversations succesfully fetched",
                "lessonTime"    => $lessonTime,
                "memberPhoto"   => $memberOrignalImage,
                "tutorPhoto"    => $tutorOrignalImage,
                "memo"          => $memo,
                "conversations" => $conversations,            
            ]); 
        } else {
            return Response()->json([
                "success" => false,  
                "message"   => "no conversation found"                
            ]);             
        }
    }
}
