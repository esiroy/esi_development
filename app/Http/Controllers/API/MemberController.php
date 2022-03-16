<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\LessonGoals;
use App\Models\Member;
use App\Models\MemberAttribute;
use App\Models\MemberDesiredSchedule;
use App\Models\Role;
use App\Models\ScheduleItem;
use App\Models\MemoReply;
use App\Models\Tutor;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Shift;
use App\Models\AgentTransaction;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;
use App\Models\LessonMailer;
use App\Models\Purpose;
use App\Models\MemberLevel;

use Auth, App;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class MemberController extends Controller
{

    public function getAgentName(Request $request)
    {

        $agentInfo = Agent::where('agent_id', $request['agent_id'])->first();

        if ($agentInfo) {
            return Response()->json([
                "success" => true,
                "firstname" => $agentInfo->user->firstname,
                "lastname" => $agentInfo->user->lastname,
                "message" => "test message",
            ]);
        } else {

            return Response()->json([
                "success" => false,
                "message" => "Agent ID not found",
            ]);
        }
    }

    /*
    Post the Questionnaire Comment
     */
    public function postComment(Request $request)
    {

        $id = $request->scheduleitemid;

        $questionnaire = Questionnaire::where('schedule_item_id', $id)->first();

        //@todo: Add to report card
        
        if (isset($questionnaire->id)) {
            $newQuestionnaire = $questionnaire->update([
                'schedule_item_id' => $id,
                'remarks' =>  $request->remarks,
                'tutor_id' => $request->tutor_id,
                'member_id' => Auth::user()->id,
                'valid' => true
            ]);

            $questionnaireID = $questionnaire->id;

        } else {
            $newQuestionnaire = Questionnaire::create([
                'schedule_item_id' => $id,
                'remarks' =>  $request->remarks,
                'tutor_id' => $request->tutor_id,
                'member_id' => Auth::user()->id,
                'valid' => true
            ]);         
            
            $questionnaireID = $newQuestionnaire->id;
        }




        if (isset($request->QUESTION_1grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id',  $questionnaireID)
                                ->where('QUESTION', "QUESTION_1")
                                ->first();

            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_1',
                    'grade' =>  $request->QUESTION_1grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_1',
                    'grade' =>  $request->QUESTION_1grade,
                    'valid' => true,
                ]);
            }

        }

        if (isset($request->QUESTION_2grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_2")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_2',
                    'grade' =>  $request->QUESTION_2grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_2',
                    'grade' =>  $request->QUESTION_2grade,
                    'valid' => true,
                ]);
            }
        }
        

        if (isset($request->QUESTION_3grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_3")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_3',
                    'grade' =>  $request->QUESTION_3grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_3',
                    'grade' =>  $request->QUESTION_3grade,
                    'valid' => true,
                ]);
            }
        }

        if (isset($request->QUESTION_4grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_4")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_4',
                    'grade' =>  $request->QUESTION_4grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'question' => 'QUESTION_4',
                    'grade' =>  $request->QUESTION_4grade,
                    'valid' => true,
                ]);
            }
        }        

       

        return Response()->json([
            "success" => true,
            "message" => "ご協力ありがとうございました。",
        ]);
       
    }

    public function viewComment(Request $request)
    {

        $id = $request->scheduleitemid;

        $scheduleItem = ScheduleItem::find($id);

        $questionnaire = Questionnaire::where('schedule_item_id', $id)->where('valid', true)->first();

        if ($questionnaire) {

            //Questionnaire id
            $questionnaireID = $questionnaire->id;

            //get the items
            $questionnaireItem1 = QuestionnaireItem::where('questionnaire_id', $questionnaireID)->where('QUESTION', "QUESTION_1")->first();
            $questionnaireItem2 = QuestionnaireItem::where('questionnaire_id', $questionnaireID)->where('QUESTION', "QUESTION_2")->first();
            $questionnaireItem3 = QuestionnaireItem::where('questionnaire_id', $questionnaireID)->where('QUESTION', "QUESTION_3")->first();
            $questionnaireItem4 = QuestionnaireItem::where('questionnaire_id', $questionnaireID)->where('QUESTION', "QUESTION_4")->first();            

            $grade_item1 = isset($questionnaireItem1->grade)? $questionnaireItem1->grade : ' - ';
            $grade_item2 = isset($questionnaireItem2->grade)? $questionnaireItem2->grade : " - ";
            $grade_item3 = isset($questionnaireItem3->grade)? $questionnaireItem3->grade : " - ";
            $grade_item4 = isset($questionnaireItem4->grade)? $questionnaireItem4->grade : " - ";

            $data = [
                'remarks' => $questionnaire->remarks,
                'questionnaireItem1' => getQuestionnnaireGradeTranslation($grade_item1),
                'questionnaireItem2' => getQuestionnnaireGradeTranslation($grade_item2),
                'questionnaireItem3' => getQuestionnnaireGradeTranslation($grade_item3),
                'questionnaireItem4' => getQuestionnnaireGradeTranslation($grade_item4),
            ];

            return Response()->json([
                "success" => true,
                "comment"  => $data,
                "message" => "Questionnaire comment found",
            ]);
        } else {
            return Response()->json([
                "success" => false,
                "schedule_item_id" => $id,
                "message" => "Questionnaire not found",
            ]);
        }
    }


    /* 
        Returns @totalScheduledItem - the total count of scheduled A and B 
    */
    public function getBookScheduledCount(Request $request, ScheduleItem $scheduleItem, Member $member) 
    {
        $memberID = $request->memberID;
        $memberInfo = $member->where('user_id', $memberID)->first();
        $totalScheduledItem = $scheduleItem->getTotalMemberReserved($memberInfo);
        return Response()->json([
            "success" => false,
            "totalScheduledItem" => $totalScheduledItem,
            "message" => "Member has " . $totalScheduledItem,
        ]);      
    }

    /* 
        Returns @totalScheduledItem - the total count of scheduled A and B for the day
    */
    public function getTotalMemberDailyReserved(Request $request, ScheduleItem $scheduleItem, Member $member) 
    {
        $date = $request->date;
        $memberID = $request->memberID;
        $memberInfo = $member->where('user_id', $memberID)->first();
        $totalDailyReserved = $scheduleItem->getTotalMemberDailyReserved($memberID, $date);

        return Response()->json([
            "success" => true,
            "totalDailyReserved" => $totalDailyReserved,
            "message" => "Member has " . $totalDailyReserved,
        ]);    
    }
    
    public function getTotalTutorDailyReserved(Request $request, ScheduleItem $scheduleItem, Member $member) 
    {
        $date = $request->date;
        $memberID = $request->memberID;
        $tutorID = $request->tutorID;        
        $memberInfo = $member->where('user_id', $memberID)->first();
        $totalTutorDailyReserved = $scheduleItem->getTotalTutorDailyReserved($memberID, $tutorID, $date);

        return Response()->json([
            "success" => true,
            "memberReservedActive" => env('MEMBER_RESERVE_LIMIT_ACTIVE', true),
            "totalTutorDailyReserved" => $totalTutorDailyReserved,
            "totalMemberReserved" => $scheduleItem->getTotalMemberReserved($memberInfo),
            "message" => "Tutor has " . $totalTutorDailyReserved ." for member " . $memberID,
        ]);           
    }

    /*
    Book a schedule for a member
     */
    public function bookSchedule(Request $request)
    {        
        $scheduleItem = new ScheduleItem;
        $scheduleID = $request->scheduleID;
        $tutorID = $request->tutorID;  
        $memberID = $request->memberID;
        $schedule_status = 'CLIENT_RESERVED';
        $schedule_status_b = 'CLIENT_RESERVED_B';

        
        $memberInfo = Member::where('user_id',  $memberID)->first();

        //find the schedule
        $schedule = $scheduleItem->find($scheduleID);        

        //total reservation for a day
        $dateOfResevation = date("Y-m-d", strtotime($schedule->lesson_time));
        //$totalDailyReserved = $scheduleItem->getTotalMemberDailyReserved($memberID, $dateOfResevation);

        $totalDailyTutorReserved = $scheduleItem->getTotalTutorDailyReserved($memberID, $tutorID, $dateOfResevation);

        //[UPDATE for MAY 15, 2022] 
        //LIMIT SCHEDULE ITEM (15 ITEMS)
        $totalScheduledItem = $scheduleItem->getTotalMemberReserved($memberInfo);
        if ($totalScheduledItem >= 15) {
            return Response()->json([
                "success" => false,
                "type"      => "msgbox",
                "message" => "予約数が上限に達したため予約できません",                
                "message_en" => "Cannot make a reservation because the number of reservations has reached the upper limit"
            ]);
        } 

        //check deactivated
        if ($memberInfo->user->is_activated == false) {
            return Response()->json([
                "success"   => false,
                "type"      => "msgbox",
                "message"   => "エラー：スケジュールを予約できません。ユーザーは現在非アクティブ化されています",                
                "message_en" => "Error: Unable to reserve schedule. User is currently deactivated"
            ]);
        }    



        if (!$schedule) {
            //schedule time  not found
            return Response()->json([
                "success"       => false,
                "type"          => "msgbox",
                "message"       => "スケジュールが見つからないか、もう存在しません",
                "message_en"    => "Schedule not found or no longer exists"
            ]);      
        } else if ($schedule->valid == false) {      

            return Response()->json([
                "success"       => false,
                "type"          => "msgbox",
                "message"       => "スケジュールが見つからないか、もう存在しません",
                "message_en"    => "Schedule is aleady invalid or archived"
            ]);  

        } else {
            //found check if tutor is still available in this time slot
            if ($schedule->schedule_status !== 'TUTOR_SCHEDULED') {
                return Response()->json([
                    "success" => false,
                    "type"      => "msgbox",
                    "message" => "ご予約できません。既に同じ時間にご予約があります。/ ページにアクセスしたときに撮影しました",
                    "message_en"    => "I cannot make a reservation. There is already a reservation at the same time / It was taken when you visited the page"
                ]);
            }
        }



        /*****************************************************
         *  [START] POINTS AND EXPIRATION CHECKER 
        ******************************************************/

        //1. CHECK IF MEMBER EXPIRED
        $agentCredts = new AgentTransaction();
        if ($memberInfo->isMemberCreditExpired($memberID)) {
            return Response()->json([
                "success"   => false,
                "type"      => "msgbox",
                "message"   => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                "message_en" => "You are out of points or your points have expired. (error code: 0001 - c.e)",    
            ]);                  
        }

        //2. CHECK IF MEMBER HAS ENOUGH POINTS
        if ($agentCredts->getCredits($memberID) <= 0) {
            return Response()->json([
                "success" => false,
                "type"      => "msgbox",           
                "message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                "message_en" => "You are out of points or your points have expired. (error code: 002 - insuffecient point)",   
            ]);              
        }  

        //3. CHECK IF LESSON TIME IS INVALID
        if (!$memberInfo->isReservedLessonValid($memberID, $schedule->lesson_time)) {            
            return Response()->json([
                "success"   => false,
                "type"      => "msgbox",
                "message"   => "ポイントが不足しているか、ポイントの有効期限が切れています。/ reserved schedule exceeds expiration date",
                "message_en" => "You are out of points or your points have expired. (error code: 003 - lesson time invalid)",       
            ]); 
        }        
        
        //4. check if 30 minutes is not reached, if reached disallow reservation and give message
        $date_now =  date("Y-m-d H:i:s");
        $valid_time = date("Y-m-d H:i:s", strtotime($date_now ." + 30 minutes"));
        $lessonTime = date("Y-m-d H:i:s", strtotime($schedule->lesson_time));
        if ($lessonTime >= $valid_time) {
            //valid time here.
        } else {
            //invalid time 
            return Response()->json([
                "success" => false,
                "type"      => "msgbox",
                "message" => "レッスン予約は開始30分前まで可能です",
                "message_en" => "Lesson reservations can be made up to 30 minutes before the start"
            ]);  
        }
     
        //5. compare current lesson limit and total month total reserved schedules (storing)
        $memberAttribute = new MemberAttribute();

        if (date("H", strtotime($schedule->lesson_time)) == "00" ) {
            $check_month_limit = date('M', strtotime($schedule->lesson_time ." - 1 day"));
        } else {
            $check_month_limit = date("M", strtotime($schedule->lesson_time));
        }
        

        $check_year_limit = date("Y", strtotime($schedule->lesson_time));
        $attribute = $memberAttribute->getLessonLimit($memberID, $check_month_limit, $check_year_limit);
        if ($attribute) {
            $limit = $attribute->lesson_limit;
            //check if there if it is not over the lesson limit capacity
            $month_to_reserve = date("m", strtotime($schedule->lesson_time));
            $year_to_reserve = date("Y", strtotime($schedule->lesson_time));
            $totalReserved = $scheduleItem->getTotalLessonReserved($memberID, $month_to_reserve, $year_to_reserve);

            if ($totalReserved >= $limit) 
            {                
                return Response()->json([
                    "success" => false,
                    "type"      => "msgbox",         
                    //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                    //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",
                    "message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                    "message_en" => "You are out of points or your points have expired. (member total monthly reserved limit)",
                ]);        
            }    
        } else {
            return Response()->json([
                "success" => false,
                "type"      => "msgbox",
                //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",
                "message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                "message_en" => "You are out of points or your points have expired. (member total monthly is Zero)",                     
            ]);
        }

        $lessonTime = date("Y-m-d H:i:s", strtotime($schedule->lesson_time));

        //check if duplicate schedule if exists
        $isLessonExists = ScheduleItem::where('lesson_time', $lessonTime)
            ->where('member_id', Auth::user()->id)
            ->where('schedule_status', "!=", 'TUTOR_CANCELLED')      
            ->where('valid', 1)
            ->exists();

        if ($isLessonExists) {
            return Response()->json([
                "success"   => false,
                "type"      => "msgbox",
                "message"   => "ご予約できません。　既に同じ時間にご予約があります。",
                "message_en"    => "I cannot make a reservation. There is already a reservation at the same time"
            ]);
        }

        /************************
         * SAVE SCHEDULE STATUS 
         * DESCRIPTION: WHEN SAVING, THE TOTAL DAILY RESERVED WILL BE SET TO RESERVED STATUS "B" 
         *              IF YOU WILL RESERVE 2 OR MORE IN A DAY 
         ************************/

        $MEMBER_RESERVE_LIMIT_ACTIVE = env('MEMBER_RESERVE_LIMIT_ACTIVE', true);

        if ($MEMBER_RESERVE_LIMIT_ACTIVE == true) 
        {
            if ($totalDailyTutorReserved >= 2) 
            {   
                $reservation_type = $schedule_status_b;      
                $data = [
                    'member_id' => $memberID,
                    'schedule_status' => $schedule_status_b,
                ];
                $schedule->update($data);
            } else {
                $reservation_type = $schedule_status;
                $data = [
                    'member_id' => $memberID,
                    'schedule_status' => $schedule_status,
                ];
                $schedule->update($data);
            }
        } else {

            //default to A
            $reservation_type = $schedule_status;
            $data = [
                'member_id' => $memberID,
                'schedule_status' => $schedule_status,
            ];
            $schedule->update($data);            
        }
       

        //** ADD MEMBER TRANSACTION */
        if ($memberID != null) 
        {
            //add lesson
            $shift = Shift::where('value', 25)->first();
            $transaction = [
                'schedule_item_id' => $scheduleID,
                'member_id' => Auth::user()->id,
                'created_by_id' => Auth::user()->id,
                'lesson_shift_id' => $shift->id,
                'transaction_type' => "LESSON",
                'reservation_type' => $reservation_type, 
                'amount' => 1,
                'valid' => true,
            ];
            AgentTransaction::create($transaction);
        }                    
        
        /*******************************************               
        *       [START] SEND MAIL (PRODUCTION ONLY)
        *******************************************/                    
        $scheduleItemObj = new scheduleItem();
        $selectedSchedule = $scheduleItemObj->find($scheduleID);

        if (App::environment(['prod', 'production'])) 
        {
            if ($selectedSchedule->schedule_status == 'CLIENT_RESERVED' || $selectedSchedule->schedule_status  == 'CLIENT_RESERVED_B') 
            {            
                $memberObj = new Member();
                $tutorObj = new Tutor();
                $memberInfo = $memberObj->where('user_id', $selectedSchedule->member_id )->first();
                $tutorInfo = $tutorObj->where('user_id', $selectedSchedule->tutor_id)->first();                                  
                //send Member Email
                $lessonMailer = new LessonMailer();
                $lessonMailer->sendMemberEmail($memberInfo, $tutorInfo, $selectedSchedule);    
                
            }             
        }
        /*******************************************               
        *       [END] SEND MAIL 
        *******************************************/    

        $credits = $agentCredts->getCredits($memberID);

       
        if ($MEMBER_RESERVE_LIMIT_ACTIVE == true) 
        {                
            if ($totalDailyTutorReserved >= 2) 
            {
                return Response()->json([
                    "success" => true,
                    "type"      => "msgbox",
                    "credits"  => "(". number_format($credits, 2) .")",
                    "message"   => "同日、同講師の予約上限2コマを超えています。",
                    "message_en" => "On the same day, the instructor's reservation limit of 2 frames has been exceeded.",
                    "status" => $selectedSchedule->schedule_status,
                    "userData" => $request['user'],
                    "lesson_time" => $lessonTime,
                    "tutor_id" => $schedule->tutor_id,
                    "member_id" => Auth::user()->id
                ]);

            } else { 

                return Response()->json([
                    "success" => true,
                    "type"      => "msgbox",
                    "credits"  => "(". number_format($credits, 2) .")",
                    "message" => "Member has been scheduled",
                    "message_en" => "Member has been scheduled.",
                    "userData" => $request['user'],
                    "lesson_time" => $lessonTime,
                    "tutor_id" => $schedule->tutor_id,
                    "member_id" => Auth::user()->id,
                ]);
            
            } 
        } else {

            //defauult
            return Response()->json([
                "success" => true,
                "type"      => "msgbox",
                "credits"  => "(". number_format($credits, 2) .")",
                "message" => "Member has been scheduled",
                "message_en" => "Member has been scheduled.",
                "userData" => $request['user'],
                "lesson_time" => $lessonTime,
                "tutor_id" => $schedule->tutor_id,
                "member_id" => Auth::user()->id,
            ]);            

        }




    }

    public function cancelSchedule(Request $request)
    {
        $scheduleID = $request->id;

        $agentCredts = new AgentTransaction();
        
        //@todo: check if the schedule is present
        $schedule = ScheduleItem::where('id', $scheduleID)->where('member_id', Auth::user()->id)->first();

        if ($schedule) {
           
            //IF SCHEDULE IS INVALID SHOW NOTIFY ERROR
            if ($schedule->valid == FALSE) {
                return Response()->json([
                    "success" => false,
                    "refresh" => true,
                    "message_jp"   => "スケジュールが見つかりません。すでに削除されている可能性があります",
                    "message" => "Schedule not found, it may have already been deleted",
                ]);                              
            }

            $reportCard = ReportCard::where('schedule_item_id', $scheduleID)->first();

            if ($reportCard) {
                $reportCard->delete();
            }       

            //@todo: (NEW) (cancellation is 3 hours grace period)
            $date_now =  date("Y-m-d H:i:s");
            $valid_time = date("Y-m-d H:i:s", strtotime($date_now ." + 3 hours"));
            $lessonTime = date("Y-m-d H:i:s", strtotime($schedule->lesson_time));

            //valid time here since it is greater that 3 hours)
            if ($valid_time <= $lessonTime) 
            {
                //refund points if status is [client reserved]
                if ($schedule->schedule_status == "CLIENT_RESERVED") {

                    $transaction = [
                        'schedule_item_id' => $scheduleID,
                        'member_id' => Auth::user()->id,
                        'created_by_id' => Auth::user()->id,                   
                        'transaction_type' => "CANCEL_LESSON", //<<--- this will refund the transaction
                        'reservation_type' => $schedule->schedule_status, //(update) June 10, 2021                        
                        'amount' => 1,
                        'valid' => true,
                    ];        
                    AgentTransaction::create($transaction); 

                    //cancel the transaction: 
                    //1. member id will be emptied  
                    //2. Update to back to tutor scheduled
                    $data = [
                        'member_id' => null,
                        'schedule_status' => 'TUTOR_SCHEDULED',
                        'memo'  => null
                    ];


                    $memoReplies = MemoReply::where('schedule_item_id', $scheduleID)->get();
                    foreach($memoReplies as $memoReply) {
                        $memoReply->delete();
                    }

                    /*******************************************               
                    *       [START] SEND MAIL - RESERVATION A
                    *******************************************/
                    if (App::environment(['prod', 'production'])) 
                    {                    
                        $scheduleItemObj = new scheduleItem();
                        $selectedSchedule = $scheduleItemObj->find($scheduleID);
                        $memberObj = new Member();
                        $tutorObj = new Tutor();
                        $memberInfo = $memberObj->where('user_id', $selectedSchedule->member_id)->first();
                        $tutorInfo = $tutorObj->where('user_id', $selectedSchedule->tutor_id)->first();  
                        
                        $lessonMailer = new LessonMailer();
                        $lessonMailer->sendMemberCancellationEmail($memberInfo, $tutorInfo, $selectedSchedule);
                    }  
                    /*******************************************               
                    *       [END] SEND MAIL - RESERVATION A
                    *******************************************/                                     
                
                } 
                else if ($schedule->schedule_status == "CLIENT_RESERVED_B") 
                {
                    //[client reserved b] - no refund 
                    $transaction = [
                        'schedule_item_id' => $scheduleID,
                        'member_id' => Auth::user()->id,
                        'created_by_id' => Auth::user()->id,                   
                        'transaction_type' => "CANCEL_LESSON_B", //<<--- this will NOT refund the transaction: NOTE: B TYPE CANCEL
                        'reservation_type' => $schedule->schedule_status, //(update) June 10, 2021
                        'amount' => 0,
                        'valid' => true,
                    ];        
                    AgentTransaction::create($transaction); 

                    //turn the the status to not available since it is B
                    $data = [
                        'schedule_status' => 'CLIENT_NOT_AVAILABLE',                     
                    ];

                    //remove
                    $memoReplies = MemoReply::where('schedule_item_id', $scheduleID)->get();
                    foreach($memoReplies as $memoReply) {
                        $memoReply->delete();
                    }

                    /*******************************************               
                    *       [START] SEND MAIL - RESERVATION B
                    *******************************************/
                    if (App::environment(['prod', 'production'])) 
                    {                        
                        $scheduleItemObj = new scheduleItem();
                        $selectedSchedule = $scheduleItemObj->find($scheduleID);
                        $memberObj = new Member();
                        $tutorObj = new Tutor();
                        $memberInfo = $memberObj->where('user_id', $selectedSchedule->member_id)->first();
                        $tutorInfo = $tutorObj->where('user_id', $selectedSchedule->tutor_id)->first();  
                        
                        $lessonMailer = new LessonMailer();
                        $lessonMailer->sendMemberAbsentEmail($memberInfo, $tutorInfo, $selectedSchedule);
                    }
                    /*******************************************               
                    *       [END] SEND MAIL - RESERVATION B
                    *******************************************/                      
                } else {
                    //schedule was updated and is now either deleted or completed or back to tutotr schedule, user must refresh
                    return Response()->json([
                        "success" => false,
                        "refresh" => true,
                        "message_jp"   => "スケジュールが見つかりません。すでに削除されている可能性があります",
                        "message" => "Schedule not found, it may have already been deleted",
                    ]);                       
                }
              
                
                //@todo: search delete questionnaire 
                $questionnaire = Questionnaire::where('schedule_item_id', $scheduleID)->where('valid', true)->first();
                if ($questionnaire) 
                {                    
                    $questionnaireItems = QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->get();
                    foreach ($questionnaireItems as $questionnaireItem) {
                        $questionnaireItem->delete();
                    }
                    $questionnaire->delete();
                }

                $credits = $agentCredts->getCredits(Auth::user()->id);

                //if client reserved B the booking link will not activate (this must be before update)
                if ($schedule->schedule_status == "CLIENT_RESERVED") {
                    $bookable = true;
                } else {
                    $bookable = false;
                }

                /*******************************************               
                    [START] UPDATE THE SCHEDULE
                *******************************************/   
                $schedule->update($data);

                return Response()->json([
                    "success" => true,
                    'bookable' => $bookable, //bookable(true) will add the link for booking
                    "credits"  => "(". number_format($credits, 2) .")",
                    'buttonText' => '予約', //link for reserve
                    "message" => "Member schedule has been cancelled  and refunded " . $lessonTime . " >= " .  $valid_time,
                    "userData" => $request['user'],
                ]);                       
               
            
            } else {
                //@this section gives no refund since it has 3 hours below:  
                //1. change the schedule status to client not available
                $schedule = ScheduleItem::find($scheduleID);            
                $data = [
                    'schedule_status' => 'CLIENT_NOT_AVAILABLE',
                    'memo'  => null
                ];
       

                //@todo: search delete questionnaire 
                $questionnaire = Questionnaire::where('schedule_item_id', $scheduleID)->where('valid', true)->first();

                if ($questionnaire) {
                    //@todo: search to delete questionnairre items
                    $questionnaireItems = QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->get();
                    foreach ($questionnaireItems as $questionnaireItem) {
                        $questionnaireItem->delete();
                    }
                    $questionnaire->delete();
                }


                /*******************************************               
                *       [START] SEND MAIL
                *******************************************/      
                //initialize member, tutor and schedule items    
                $scheduleItemObj = new scheduleItem();
                $selectedSchedule = $scheduleItemObj->find($scheduleID);
                $memberObj = new Member();
                $tutorObj = new Tutor();
                $memberInfo = $memberObj->where('user_id', $selectedSchedule->member_id)->first();
                $tutorInfo = $tutorObj->where('user_id', $selectedSchedule->tutor_id)->first();  
                
                $lessonMailer = new LessonMailer();
                $lessonMailer->sendMemberAbsentEmail($memberInfo, $tutorInfo, $selectedSchedule);    
               
                /*******************************************               
                *       [END] SEND MAIL 
                *******************************************/           

                $credits = $agentCredts->getCredits(Auth::user()->id);
                $schedule->update($data);  

                return Response()->json([
                    "success" => true,
                    'bookable' => false,
                    "credits"  => "(". number_format($credits, 2) .")",                   
                    'buttonText' => '済他', //done
                    "message" => "Member schedule has been cancelled and points consumed " . $lessonTime . " >= " .  $valid_time,
                    "userData" => $request['user'],
                ]);                     
            } 
        } else {

            return Response()->json([
                "success" => false,
                "message_jp"   => "スケジュールが見つかりません。すでに削除されている可能性があります",
                "message" => "Schedule not found, it may have already been deleted",
            ]);                 
        }
    }

    public function getScheduleDetails(Request $request) 
    {
        $scheduleID = $request->ScheduleItemID;
        $schedule = ScheduleItem::where('id', $scheduleID)->where('member_id', Auth::user()->id)->first();

        $date_now =  date("Y-m-d H:i:s");
        $valid_time = date("Y-m-d H:i:s", strtotime($date_now ." + 3 hours"));
        $lessonTime = date("Y-m-d H:i:s", strtotime($schedule->lesson_time));
        
        if ($valid_time <= $lessonTime) 
        {           

            if ($schedule->schedule_status == "CLIENT_RESERVED_B") 
            {                
                //valid time, check if schedule b           
                return Response()->json([
                    "success" => false,
                    "message_jp"    => "こちらの予約はキャンセルができませんがよろしいでしょうか？",
                    "message"       => "This reservation cannot be canceled, is that okay?",
                ]);   
            } else {
                //valid time, check if schedule a           
                return $schedule;
            }
        } else {
            return Response()->json([
                "success" => false,
                "message_jp"    => "このレッスンをキャンセル（欠席）されるとポイントは消化されます。キャンセル(欠席）しますか？",
                "message"       => "If you cancel (absent) this lesson, your points will be consumed. Do you want to cancel (absent)?",
            ]);             
        }        
    }



    //get member memo
    public function getMemo(Request $request)
    {
        $scheduleID = $request->scheduleID;        
        $schedule = ScheduleItem::find($scheduleID);  
        
        //get member Image
        $userImageObj = new UserImage;
        $memberImage = $userImageObj->getMemberPhotoByID($schedule->member_id); 


        if ($memberImage == null) {
            $memberOrignalImage = Storage::url('user_images/noimage.jpg');
        } else {
            $memberOrignalImage = Storage::url($memberImage->original);
        }
        
        
        //get teacher profile pic
        $userImageObj = new UserImage;
        $tutorImage = $userImageObj->getTutorPhotoByID($schedule->tutor_id);         

        if ($tutorImage == null) {
            $tutorOrignalImage = Storage::url('user_images/noimage.jpg');
        } else {
            $tutorOrignalImage = Storage::url($tutorImage->original);
        }

        if (date('H', strtotime($schedule->lesson_time)) == '00') {
            $lessonTime = date('Y年 m月 d日 24:i', strtotime($schedule->lesson_time ." - 1 day"))  ." - " .  date('24:i', strtotime($schedule->lesson_time." + 25 minutes "));
        } else {
            $lessonTime = date('Y年 m月 d日 H:i', strtotime($schedule->lesson_time)) ." - " . date('H:i', strtotime($schedule->lesson_time." + 25 minutes "));
        }
            
        
    

        if ($schedule) {
            return Response()->json([
                "success" => true,
                "memo" => $schedule->memo,
                "lesson_time" => $lessonTime,
                "message" => "Memo has been found",
                "memberImage" => $memberOrignalImage,
                "tutorImage" => $tutorOrignalImage,
                "schedule_status" => $schedule->schedule_status
            ]);
        } else {
            return Response()->json([
                "success" => false,
                "message" => "Error: Memo is not found.",
            ]);
        }
    }
    

    public function sendMemo(Request $request)
    {
        $scheduleID = $request->scheduleID;
        $message = $request->message;

        $data = [
            'memo' => $message,
        ];
        $schedule = ScheduleItem::find($scheduleID);
        $schedule->update($data);

        return Response()->json([
            "success" => true,
            "memo" => $message,
            "message" => "Memo has been saved",
        ]);
    }


    public function getMemoConversations(Request $request) 
    {
        $scheduleID = $request->scheduleID;
        $tutorID = $request->tutorID;
        $message = $request->message;

        //check if the schedule is available , if not send an error message
        $scheduleItem = ScheduleItem::find($scheduleID);
        
        $memoReply = new MemoReply();
        $conversations = $memoReply->where('schedule_item_id', $scheduleID)
                        ->orderBy("created_at", 'ASC')
                        ->get();

        if ($conversations) 
        {            

            $items = [];
            foreach($conversations as $item) {
                $items[] = [
                    "message"       => $item->message, 
                    "message_type"  => $item->message_type,
                    "created_at"    => ESIDateTimeSecondsFormat($item->created_at)
                ];
            }
            
           //$memoReply->where('schedule_item_id', $scheduleID)->update(array('is_read' => true));

           $memoReply->where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "TUTOR")->update(array('is_read' => true));

            return Response()->json([
                "success" => true,  
                "message"   => "conversations succesfully fetched",
                "conversations" => $items,            
            ]); 
        } else {
            return Response()->json([
                "success" => false,  
                "message"   => "no conversation found"                
            ]);             
        }
    }    

    public function sendMemberReply(Request $request)    
    {
        $scheduleID = $request->scheduleID;
        $memberID = $request->member_id;
        $message = $request->message;


        //check if the schedule is available , if not send an error message
        $scheduleItem = ScheduleItem::find($scheduleID);

        //update the schedule Memo if this is the first message from user, so it will become a thread starter
        if ($scheduleItem->memo == null)
        {            
            $data = [
                'memo' => $request->message,
            ];
            $scheduleItem->update($data);
        }

        //check if member schedule is book by me!!
        


        if ($scheduleItem) {

            $data = [
                'schedule_item_id' => $scheduleID,
                'sender_id' => $scheduleItem->member_id,
                'recipient_id' => $scheduleItem->tutor_id,
                'message_type' => "MEMBER",
                'message' => $message,
                'is_read' => false,
            ];

            $memoReply = new MemoReply();
            $memoResponse = $memoReply->create($data);            

            if ($memoResponse) 
            {
                $memo = $memoReply->find($memoResponse->id);

                return Response()->json([
                    "success"   => true,
                    "response"  => "message has been sent!",
                    "message"   => $message,            
                    "created_at"      => ESIDateTimeSecondsFormat($memo->created_at),
                ]);
            } else {
                return Response()->json([
                    "success"   => false,
                    "response"  => "Error has was not sent due to an error, please check back later.",
                    "created_at"      => date('m-d-y H:i:s'),
                ]);
            } 
        } else {
            return Response()->json([
                "success"   => false,
                "response"  => "Error schedule was not found, it may have been already removed.",
                "created_at"      => date('m-d-y H:i:s'),
            ]);
        }
       
    }

    public function getUnreadTeacherMessages(Request $request) 
    {
        $scheduleID = $request->scheduleID;

        $memoReply = new MemoReply();
        $conversations = $memoReply->where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "TUTOR")->get();   
        
        MemoReply::where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "TUTOR")->update(array('is_read' => true));

        $items = [];
        foreach($conversations as $item) {
            $items[] = [
                "message"       => $item->message,            
                "created_at"    => ESIDateTimeSecondsFormat($item->created_at)
            ];
        }

        return Response()->json([
            "success" => true,    
            "conversations" => $items,
            "message" => "Teacher memo replies has been fetched.",
        ]);
    }


    public function getMemberInbox(Request $request) 
    {

        $memberID = $request->memberID;
        $memberInfo = Member::where('user_id',  $memberID)->first();

        $scheduleItems = new ScheduleItem();
        $memoReply = new MemoReply();     

        $reservations = $scheduleItems->getMemberAllActiveLessons($memberInfo);

        $ctr = 0;
        $unread = 0;        
        $inbox = array();

        foreach($reservations as $reservation) 
        {   
            if (isset($reservation->id)) 
            {

                $ctr++;

                //$latestReply = $memoReply->where('schedule_item_id', $reservation->id)->where('is_read', false)->where('message_type', "TUTOR")->orderBy('created_at', 'DESC')->first();

                $latestReply = $memoReply->where('schedule_item_id', $reservation->id)->orderBy('updated_at', 'DESC')->first();
            
                if ($latestReply) 
                {
                    //GET THE MEMBER COUNT OF UNREAD REPLIES
                    $unreadTutorReplyCount = MemoReply::where('schedule_item_id', $reservation->id)->where('is_read', false)->where('message_type', "TUTOR")->count();

                    //TRACK TOTAL UNREAD
                    $unread = $unread + $unreadTutorReplyCount;
                    
                    //get teacher profile pic
                    $userImageObj = new UserImage;
                    $tutorImage = $userImageObj->getTutorPhotoByID($reservation->tutor_id);         

                    if ($tutorImage == null) {
                        $tutorOrignalImage = Storage::url('user_images/noimage.jpg');
                    } else {
                        $tutorOrignalImage = Storage::url($tutorImage->original);
                    }
    
                    if (date('H', strtotime($reservation->lesson_time)) == '00') {
                        $lessonTime = date('Y年 m月 d日 24:i', strtotime($reservation->lesson_time ." - 1 day")) ." - ".   date('24:i', strtotime($reservation->lesson_time." + 25 minutes "));
                    } else {  
                        $lessonTime = date('Y年 m月 d日 H:i', strtotime($reservation->lesson_time)) ." - ".  date('H:i', strtotime($reservation->lesson_time." + 25 minutes "));
                    }          

                    $inbox[] =  array(                       
                        "schedule_item_id" => $reservation->id,
                        "lessonTime" => $lessonTime,                        
                        "latestReply" => $latestReply->message,
                        "tutorOrignalImage" => $tutorOrignalImage,
                        "unreadMessageCount" => $unreadTutorReplyCount                        
                    );
                }
            }            
        }    

        return Response()->json([
            "success" => true,    
            "inbox" => $inbox,
            "inboxCount" => $ctr,
            "unread" => $unread,
            "message" => "Teacher memo replies has been fetched.",
        ]);
        

    }

    /**
     * API - create a new registered member .
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        //abort_if(Gate::denies('member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = json_decode($request['user']);
        $purposeList = json_decode($request['purposeList']);

        //disallow duplicate email and username
        $validator = Validator::make(
            [
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'japanese_firstname' => $data->japanese_firstname,
                'japanese_lastname' => $data->japanese_lastname,                 
                'email' => $data->email,
            ],
            [
                'first_name' => ['required', 'max:255'],
                'last_name' => ['required', 'max:255'],
                'japanese_firstname' => ['required', 'max:255'],
                'japanese_lastname' => ['required', 'max:255'],                 
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
                //'username' => ['required','max:190',Rule::unique('users')->whereNull('deleted_at')],
            ]);

        if ($validator->fails()) {

            return Response()->json([
                "success" => false,
                "message" => implode(", ", $validator->errors()->all()),
            ]);

        } else {

            DB::beginTransaction();

            try {

                $userData =
                    [
                    'user_type' => 'MEMBER',
                    "valid" => true,
                    'firstname' => $data->first_name,
                    'lastname' => $data->last_name,
                    'japanese_firstname' => $data->japanese_firstname,
                    'japanese_lastname' => $data->japanese_lastname,    
                    'email' => $data->email,
                    'username' => $data->email,
                    'password' => $data->password,
                    'api_token' => Hash('sha256', Str::random(80)),

                ];
                $user = User::create($userData);

                //Add Role
                $roles[] = Role::where('title', 'Member')->first()->id;
                $user->roles()->sync($roles);

                $tutorInfo = Tutor::find($data->maintutorid);

                //Agent
                $agent = Agent::where('agent_id', $data->agent_id)->first();
                if (isset($agent->user_id)) {
                    $agentID = $agent->user_id;
                } else {
                    $agentID = null;
                }

                //format birthday
                if ($data->birthday == null || $data->birthday == '') {
                    $birthday = null;
                    $age = null;
                } else {
                    $birthday = date('Y-m-d', strtotime($data->ubirthday));
                }

                if ($data->age == null || $data->age == '') {
                    $age = null;
                } else {
                    $age = $data->age;
                }                

                $memberInformation =
                    [
                    'user_id' => $user->id,
                    'tutor_id' => $tutorInfo->user_id,
                    'age' => $age,
                    'attribute' => strtoupper($data->attribute),
                    'birthday' => $birthday,
                    'member_since' => (isset($data->umember_since)) ? date('Y-m-d', strtotime($data->umember_since)) : date('Y-m-d'),
                    'nickname' => $data->nickname,
                    'gender' => $data->gender,
                    'agent_id' => $agentID,
                    'lesson_shift_id' => $data->lessonshiftid,
                    //course_category_id        => null,
                    //course_item_id            => null,
                    //english_level             => null,
                    'communication_app' => ucfirst(strtolower($data->communication_app)),
                    'skype_account' => (strtolower($data->communication_app) == 'skype') ? $data->communication_app_username : null,
                    'zoom_account' => (strtolower($data->communication_app) == 'zoom') ? $data->communication_app_username : null,
                    'membership' => $data->membership,

                    'is_report_card_visible_to_agent' => (boolean) $data->reportCard->agent,
                    'is_monthly_report_card_visible_to_agent' => (boolean) $data->monthlyReport->agent,

                    'is_report_card_visible' => (boolean) $data->reportCard->member,
                    'is_monthly_report_card_visible' => (boolean) $data->monthlyReport->member,

                    'point_purchase_type' => $data->pointPurchase,
                    'credits_expiration' => date('Y-m-d G:i:s', strtotime('+6 months')),

                ];
                $member = Member::create($memberInformation);
                $user->members()->sync([$member->id], false);

                /****************************
                Preferred Tutor
                 *****************************/
                //LessonGoals
                $lessonGoals = [];
                foreach ($data->preference->purpose as $key => $purpose) {
                    $goal = null;
                    $year_level = null;
                    $purposeExtraDetails = null;

                    if (isset($data->preference->purposeExtraDetails->$key)) {

                        if ($key === "CONVERSATION") {
                            $goal = $data->preference->purposeExtraDetails->$key;

                        } else if ($key === "ANTI_EXAM" || $key === "STUDY_ABROAD") {
                            $year_level = $data->preference->purposeExtraDetails->$key;
                        } else {
                            $purposeExtraDetails = $data->preference->purposeExtraDetails->$key;
                        }

                    } else {
                        $purposeExtraDetails = null;
                    }

                    array_push($lessonGoals, [
                        "member_id" => $user->id,
                        "purpose" => $key,
                        "goal" => $goal,
                        "year_level" => $year_level,
                        "extra_detail" => $purposeExtraDetails,
                        "valid" => true,
                    ]);
                }
                LessonGoals::where('member_id', $user->id)->delete();
                $purpose = LessonGoals::insert($lessonGoals);

                /********************************************
                            CREATE MEMBER PURPOSE (ADD)
                **********************************************/
                $purposeObject = new Purpose(); 
                $ObjectNameArray = array("IELTS", 
                            "TOEFL", "TOEFL_Junior", "TOEFL_Primary_Step_1", "TOEFL_Primary_Step_2", 
                            "TOEIC", 
                            "EIKEN", "TEAP", "BUSINESS", "BUSINESS_CAREERS", "DAILY_CONVERSATION", "OTHERS");

                foreach ($ObjectNameArray as $ObjectName) 
                {
                    if (isset($purposeList->{"$ObjectName"})) 
                    {
                        $purposeObject->saveMemberPurpose($user->id, $ObjectName, $purposeList); 
                        $purposeObject->saveTargetScores($user->id, $ObjectName, $purposeList);
                    }                
                }


                

                //Member Attribute (store)
                $lessonClasses = [];
                foreach ($data->preference->lessonClasses as $class) {
                    array_push($lessonClasses, [
                        "member_id" => $user->id,
                        "attribute" => $class->attribute,
                        "month" => $class->month,
                        "year" => $class->year,
                        "lesson_limit" => $class->grade, //@todo: change to lesson limit
                        "valid" => true,
                    ]);
                }
                MemberAttribute::where('member_id', $user->id)->delete();
                MemberAttribute::insert($lessonClasses);

                /****************************
                Desired Schedules
                 *****************************/
                $desiredSchedules = [];
                $ctr = 0;
                foreach ($data->desiredScheduleList as $schedule) {
                    $ctr = $ctr + 1;
                    array_push($desiredSchedules, [
                        "member_id" => $user->id,
                        "day" => $schedule->day,
                        "desired_time" => $schedule->time,
                        "sequence_number" => $ctr,
                        "valid" => true,
                    ]);
                }
                MemberDesiredSchedule::where('member_id', $user->id)->delete();
                MemberDesiredSchedule::insert($desiredSchedules);

                DB::commit();

                return Response()->json([
                    "success" => true,
                    "message" => "Member has been added",
                    "userData" => $request['user']                
                ]);
                
            } catch (\Exception $e) {

                DB::rollback();

                return Response()->json([
                    "success" => false,
                    "message" => "Exception Error Found (Member Store) : " . $e->getMessage() . " on Line : " . $e->getLine(),
                ]);
            }
        }

    }

    public function update(Request $request)
    {

        //abort_if(Gate::denies('member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = json_decode($request['user']);
        $purposeList = json_decode($request['purposeList']);



        //disallow duplicate email and username
        $validator = Validator::make(
            [
                'firstname' => $data->first_name,
                'lastname' => $data->last_name,
                'japanese_firstname' => $data->japanese_firstname,
                'japanese_lastname' => $data->japanese_lastname,                
                'email' => $data->email,
            ],
            [
                'firstname' => ['required', 'max:255'],
                'lastname' => ['required', 'max:255'],
                
                'japanese_firstname' => ['required', 'max:255'],
                'japanese_lastname' => ['required', 'max:255'], 

                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('users')->ignore($data->user_id)->whereNull('deleted_at'),
                ],
            ]);

        if ($validator->fails()) {

            return Response()->json([
                "success" => false,
                "message" => implode(", ", $validator->errors()->all()),
            ]);

        } else {

            DB::beginTransaction();

            try {

                $userData =
                    [
                    //'user_type' => 'MEMBER',
                    // "valid" => true,
                    'firstname' => $data->first_name,
                    'lastname' => $data->last_name,
                    'japanese_firstname' => $data->japanese_firstname,
                    'japanese_lastname' => $data->japanese_lastname,                 
                    'email' => $data->email,
                    'username' => $data->email,
                    //'password'      => $data->password,
                    //'api_token'     => Hash('sha256', Str::random(80)),
                ];
                $user = User::where('id', $data->user_id)->update($userData);

                //Tutor (required)
                $tutorID = null;
                if (isset($data->maintutorid)) {
                    $tutorInfo = Tutor::where('user_id', $data->maintutorid)->first();
                    if (isset($tutorInfo->user_id)) {
                        $tutorID = $tutorInfo->user_id;
                    }
                }

                //Agent (optional)
                $agentID = null;
                if (isset($data->agent_id)) {
                    $agent = Agent::where('agent_id', $data->agent_id)->first();
                    if (isset($agent->user_id)) {
                        $agentID = $agent->user_id;
                    }
                }

                //format birthday
                if ($data->birthday == null || $data->birthday == '') {
                    $birthday = null;
                    $age = null;
                } else {
                    $birthday = date('Y-m-d', strtotime($data->ubirthday));
                }

                if ($data->age == null || $data->age == '') {
                    $age = null;
                } else {
                    $age = $data->age;
                }

                $memberInformation = [
                    //'user_id'                 =>  $data->user_id,
                    'tutor_id' => $tutorID,
                    'age' => $age,
                    'attribute' => strtoupper($data->attribute),
                    'birthday' => $birthday,
                    'member_since' => (isset($data->umember_since)) ? date('Y-m-d', strtotime($data->umember_since)) : date('Y-m-d'),
                    'nickname' => $data->nickname,
                    'gender' => $data->gender,
                    'agent_id' => $agentID,
                    'lesson_shift_id' => $data->lessonshiftid,
                    //course_category_id        => null,
                    //course_item_id            => null,
                    //english_level             => null,
                    'communication_app' => ucfirst($data->communication_app),
                    'skype_account' => (strtolower($data->communication_app) == 'skype') ? $data->communication_app_username : null,
                    'zoom_account' => (strtolower($data->communication_app) == 'zoom') ? $data->communication_app_username : null,
                    'membership' => $data->membership,

                    'is_report_card_visible_to_agent' => (boolean) $data->reportCard->agent,
                    'is_monthly_report_card_visible_to_agent' => (boolean) $data->monthlyReport->agent,

                    'is_report_card_visible' => (boolean) $data->reportCard->member,
                    'is_monthly_report_card_visible' => (boolean) $data->monthlyReport->member,

                    'point_purchase_type' => $data->pointPurchase,

                    //@todo: WILL NOT update credit expiration since it will depend on creation?
                    //'credits_expiration'                =>  date('Y-m-d G:i:s', strtotime('+6 months'))

                ];
                $member = Member::where('user_id', $data->user_id)->update($memberInformation);

                /** MEMBER_USER PIVOT WILL NOT SYNC SINCE IT IS ALREADY ADDED ON CREATION ONLY
                 *
                 * $user->members()->sync([$member->id], false);
                 */

                /****************************
                Preferred Tutor
                 *****************************/
                //LessonGoals
                $lessonGoals = [];
                foreach ($data->preference->purpose as $key => $purpose) {

                    if ($purpose == true) {
                        
                        $goal = null;
                        $year_level = null;
                        $purposeExtraDetails = null;

                        if (isset($data->preference->purposeExtraDetails->$key)) {

                            if ($key === "CONVERSATION") {
                                $goal = $data->preference->purposeExtraDetails->$key;

                            } else if ($key === "ANTI_EXAM" || $key === "STUDY_ABROAD") {
                                $year_level = $data->preference->purposeExtraDetails->$key;
                            } else {
                                $purposeExtraDetails = $data->preference->purposeExtraDetails->$key;
                            }

                        } else {
                            $purposeExtraDetails = null;
                        }

                        array_push($lessonGoals, [
                            "member_id" => $data->user_id,
                            "purpose" => $key,
                            "goal" => $goal,
                            "year_level" => $year_level,
                            "extra_detail" => $purposeExtraDetails,
                            "valid" => true,
                        ]);
                                            
                    }

                }
                //delete old Lesson Goals and insert updated ones
                LessonGoals::where('member_id', $data->user_id)->delete();
                $purpose = LessonGoals::insert($lessonGoals);


                //Level Description
                $level_description = [                    
                    'C2'    => 'Mastery',
                    'C1'    => 'Expert',
                    'B2'    => 'Upper Intermediate',
                    'B1'    => 'Intermediate',
                    'A2'    => 'Elementary',
                    'A1'    => 'Starter',
                    'A 0'  => 'Beginner'
                ];

                if (isset($request->level)) {
                    //member Level
                    $memberlevelData = [
                                    "memberID"      => $data->user_id,
                                    "level"         => $request->level,
                                    "description"   => $level_description[$request->level]
                                ];

                    $memberLevel = new MemberLevel();
                    $memberLevel->saveLevel($memberlevelData);                
                } else {
                    
                    MemberLevel::where('member_id', $data->user_id)->delete();
                 
                }
                    

              

                /********************************************
                            UPDATE MEMBER PURPOSE [update]
                **********************************************/
                $purposeObject = new Purpose(); 
                $ObjectNameArray = array("IELTS", 
                            "TOEFL", "TOEFL_Junior", "TOEFL_Primary_Step_1", "TOEFL_Primary_Step_2", 
                            "TOEIC", 
                            "EIKEN", "TEAP", "BUSINESS", "BUSINESS_CAREERS", "DAILY_CONVERSATION", "OTHERS");

                foreach ($ObjectNameArray as $ObjectName) 
                {
                    if (isset($purposeList->{"$ObjectName"})) 
                    {
                        $purposeObject->saveMemberPurpose($data->user_id, $ObjectName, $purposeList); 
                    }                
                }

                
                
                foreach ($ObjectNameArray as $ObjectName)  
                {
                    $targetScore = null;

                    if (isset($purposeList->{"$ObjectName"})) 
                    {
                        //check if the option is checked to be used in_array
                        if (isset($purposeList->{"$ObjectName". "_option"})) {
                            $purpose_option_array = (array) $purposeList->{"$ObjectName". "_option"};
                        }
                       
                        if (isset($purposeList->{"$ObjectName". "_targetScore"})) 
                        {
                            foreach ($purposeList->{"$ObjectName". "_targetScore"} as $key => $item) {
                                if (isset($item)) {
                                    if ($item == true) {
                                        //check if the $key is on $purpose option
                                        if (in_array($key, $purpose_option_array)) {
                                            $targetScore[ strtolower(str_replace(" ", "_", $key))] = "". $item ."";                                        
                                        }                                        
                                    }                            
                                }
                            }

                            Purpose::where('purpose', str_replace("_", " ", $ObjectName))
                                    ->where('valid', 1)
                                    ->where('member_id', $data->user_id)
                                    ->update([
                                        'target_scores' => json_encode($targetScore, true)
                                    ]);
                    
                        }
                       
                    }
                }






                //Member Attribute (update)
                $lessonClasses = [];
                foreach ($data->preference->lessonClasses as $class) {
                    array_push($lessonClasses, [
                        "member_id" => $data->user_id,
                        "attribute" => $class->attribute,
                        "month" => $class->month,
                        "year" => $class->year,
                        "lesson_limit" => $class->lesson_limit,
                        "valid" => true,
                    ]);
                }
                //delete old attributes and insert updated ones
                MemberAttribute::where('member_id', $data->user_id)->delete();
                MemberAttribute::insert($lessonClasses);

                /****************************
                Desired Schedules
                 *****************************/
                $desiredSchedules = [];
                $ctr = 0;
                foreach ($data->desiredScheduleList as $schedule) {
                    $ctr = $ctr + 1;
                    array_push($desiredSchedules, [
                        "member_id" => $data->user_id,
                        "day" => $schedule->day,
                        "desired_time" => $schedule->desired_time,
                        "sequence_number" => $ctr,
                        "valid" => true,
                    ]);
                }
                MemberDesiredSchedule::where('member_id', $data->user_id)->delete();
                MemberDesiredSchedule::insert($desiredSchedules);

                DB::commit();

                return Response()->json([
                    "success" => true,
                    "message" => "Member " . $data->first_name . " " . $data->last_name . " has been updated",
                    "userData" => $request['user'],
                    "test" => $targetScore
                ]);
            } catch (\Exception $e) {
                return Response()->json([
                    "success" => false,
                    "message" => "Exception Error Found (Member Update) : " . $e->getMessage() . " on Line : " . $e->getLine(),
                ]);

                DB::rollback();
                // something went wrong
            }
        }

    }


    

}
