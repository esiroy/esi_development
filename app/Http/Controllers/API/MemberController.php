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
use App\Models\Tutor;
use App\Models\User;
use App\Models\Shift;
use App\Models\AgentTransaction;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;
use App\Models\LessonMailer;
use Auth;
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
                    'QUESTION' => 'QUESTION_1',
                    'grade' =>  $request->QUESTION_1grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_1',
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
                    'QUESTION' => 'QUESTION_2',
                    'grade' =>  $request->QUESTION_2grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_2',
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
                    'QUESTION' => 'QUESTION_3',
                    'grade' =>  $request->QUESTION_3grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_3',
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
                    'QUESTION' => 'QUESTION_4',
                    'grade' =>  $request->QUESTION_4grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_4',
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

            $data = [
                'remarks' => $questionnaire->remarks,
                'questionnaireItem1' => getQuestionnnaireGradeTranslation($questionnaireItem1->grade),
                'questionnaireItem2' => getQuestionnnaireGradeTranslation($questionnaireItem2->grade),
                'questionnaireItem3' => getQuestionnnaireGradeTranslation($questionnaireItem3->grade),
                'questionnaireItem4' => getQuestionnnaireGradeTranslation($questionnaireItem4->grade),
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
    Book a schedule
     */
    public function bookSchedule(Request $request)
    {
        $scheduleItem = new ScheduleItem;

        $scheduleID = $request->scheduleID;
        $memberID = $request->memberID;
        $schedule_status = 'CLIENT_RESERVED';

        //find the schedule
        $schedule = $scheduleItem->find($scheduleID);

        if (!$schedule) {
            //schedule time  not found
            return Response()->json([
                "success" => false,           
                "message" => "スケジュールが見つからないか、もう存在しません",
                "message_en" => "Schedule not found or no longer exists"
            ]);            
        }

        //check if 30 minutes is not reached, if reached disallow reservation and give message
        $date_now =  date("Y-m-d H:i:s");
        $valid_time = date("Y-m-d H:i:s", strtotime($date_now ." + 30 minutes"));
        $lessonTime = date("Y-m-d H:i:s", strtotime($schedule->lesson_time));

        if ($lessonTime >= $valid_time) {
            //valid time here.
        } else {
            //invalid time 
            return Response()->json([
                "success" => false,           
                "message" => "レッスン予約は開始30分前まで可能です",
            ]);  
        }


        //check if member has enough points
        $agentCredts = new AgentTransaction();
        if ($agentCredts->getCredits($memberID) <= 0) {
            return Response()->json([
                "success" => false,           
                "message" => "十分なポイントがないか、ポイントが期限切れになった",
            ]);              
        }
        
        //attribute check for this month
        //compare current lesson limit and total month total reserved schedules
        $memberAttribute = new MemberAttribute();
        $attribute = $memberAttribute->getLessonLimit($memberID);     
        if ($attribute) {
            $limit = $attribute->lesson_limit;        
            $currentMonthTotalReserves = $scheduleItem->getTotalLessonForCurrentMonth($memberID);
            if ($currentMonthTotalReserves >= $limit) 
            {
                return Response()->json([
                    "success" => false,           
                    //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                    //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",

                    "message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                    "message_en" => "You are out of points or your points have expired.",                    

                    
                ]);        
            }    
        } else {
            return Response()->json([
                "success" => false,           
                //"message" => "月間設定受講回数を超えているか、ポイントが足りないためレッスンの予約ができません",
                //"message_en" => "I cannot book a lesson because I have exceeded the monthly set number of lessons or I do not have enough points",

                "message" => "ポイントが不足しているか、ポイントの有効期限が切れています。",
                "message_en" => "You are out of points or your points have expired.",                     
            ]);
        }

        //@todo: check if the schedule not having same time with other schedules
        $lessonTime = date("Y-m-d H:i:s", strtotime($schedule->lesson_time));

        //@todo: check schedule if exists
        $isLessonExists = ScheduleItem::where('lesson_time', $lessonTime)
            ->where('member_id', Auth::user()->id)
            //->where('tutor_id', $schedule->tutor_id)
            //->where('schedule_status', $schedule_status)
            //->where('duration', $request['shiftDuration'])
            //->where('lesson_shift_id', $shift->id)
            ->where('valid', 1)
            ->exists();

        if ($isLessonExists) {
            return Response()->json([
                "success" => false,
                "message" => "ご予約できません。　既に同じ時間にご予約があります。",
            ]);
        }

        //@todo: save to database
        $data = [
            'member_id' => $memberID,
            'schedule_status' => $schedule_status,
        ];
        $schedule->update($data);


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
                'amount' => 1,
                'valid' => true,
            ];
            AgentTransaction::create($transaction);
        }                    
        
        /*******************************************               
        *       [START] SEND MAIL
        *******************************************/      
        //initialize member, tutor and schedule items    
        $scheduleItemObj = new scheduleItem();
        $selectedSchedule = $scheduleItemObj->find($scheduleID);
        if ($selectedSchedule->schedule_status == 'CLIENT_RESERVED' || $selectedSchedule->schedule_status  == 'CLIENT_RESERVED_B') 
        {            
            $memberObj = new Member();
            $tutorObj = new Tutor();
            $memberInfo = $memberObj->where('user_id', $selectedSchedule->member_id )->first();
            $tutorInfo = $tutorObj->where('user_id', $selectedSchedule->tutor_id)->first();  
            
            $lessonMailer = new LessonMailer();
            $lessonMailer->send($memberInfo, $tutorInfo, $selectedSchedule);    
        } 
        /*******************************************               
        *       [END] SEND MAIL 
        *******************************************/    

        $credits = $agentCredts->getCredits($memberID);

        return Response()->json([
            "success" => true,
            "credits"  => "(". number_format($credits, 2) .")",
            "message" => "Member has been scheduled",
            "userData" => $request['user'],
            "lesson_time" => $lessonTime,
            "tutor_id" => $schedule->tutor_id,
            "member_id" => Auth::user()->id,
        ]);
    }

    public function cancelSchedule(Request $request)
    {
        $scheduleID = $request->id;

        $agentCredts = new AgentTransaction();
        
        //@todo: check if the schedule is present
        $schedule = ScheduleItem::find($scheduleID);

        if ($schedule) {
            //@todo: remove report card
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
                    $schedule->update($data);
                    
                
                } else if ($schedule->schedule_status == "CLIENT_RESERVED_B") { 

                    //[client reserved b] - no refund

                    //turn the the status to not available since it is B
                    $data = [
                        'schedule_status' => 'CLIENT_NOT_AVAILABLE',                     
                    ];
                    $schedule->update($data);

                }     

          
                
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
                $scheduleItemObj = new scheduleItem();
                $selectedSchedule = $scheduleItemObj->find($scheduleID);
                $memberObj = new Member();
                $tutorObj = new Tutor();
                $memberInfo = $memberObj->where('user_id', $selectedSchedule->member_id)->first();
                $tutorInfo = $tutorObj->where('user_id', $selectedSchedule->tutor_id)->first();  
                
                $lessonMailer = new LessonMailer();
                $lessonMailer->send($memberInfo, $tutorInfo, $selectedSchedule);                
                /*******************************************               
                *       [END] SEND MAIL 
                *******************************************/   

                $credits = $agentCredts->getCredits(Auth::user()->id );
                return Response()->json([
                    "success" => true,
                    'bookable' => true, //bookable(true) will add the link for booking
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
                $schedule->update($data);   

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
                $lessonMailer->send($memberInfo, $tutorInfo, $selectedSchedule);    
               
                /*******************************************               
                *       [END] SEND MAIL 
                *******************************************/           

                $credits = $agentCredts->getCredits(Auth::user()->id );
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


    //get member memo
    public function getMemo(Request $request)
    {
        $scheduleID = $request->scheduleID;
        $schedule = ScheduleItem::find($scheduleID);

        if ($schedule) {
            return Response()->json([
                "success" => true,
                "memo" => $schedule->memo,
                "message" => "Memo has been found",
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        //abort_if(Gate::denies('member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = json_decode($request['user']);

        //disallow duplicate email and username
        $validator = Validator::make(
            [
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'email' => $data->email,
            ],
            [
                'first_name' => ['required', 'max:255'],
                'last_name' => ['required', 'max:255'],
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

                if ($data->age == null || $date->age == '') {
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

                //Member Attribute
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
                    "userData" => $request['user'],
                ]);
            } catch (\Exception $e) {
                return Response()->json([
                    "success" => false,
                    "message" => "Exception Error Found (Member Store) : " . $e->getMessage() . " on Line : " . $e->getLine(),
                ]);

                DB::rollback();
                // something went wrong
            }
        }

    }

    public function update(Request $request)
    {

        //abort_if(Gate::denies('member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = json_decode($request['user']);

        //disallow duplicate email and username
        $validator = Validator::make(
            [
                'firstname' => $data->first_name,
                'lastname' => $data->last_name,
                'email' => $data->email,
            ],
            [
                'firstname' => ['required', 'max:255'],
                'lastname' => ['required', 'max:255'],
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
                //delete old Lesson Goals and insert updated ones
                LessonGoals::where('member_id', $data->user_id)->delete();
                $purpose = LessonGoals::insert($lessonGoals);

                /*
                //data sample
                [id] => 123153
                [valid] => 1
                [attribute] => Member
                [lesson_limit] => 1
                [month] => JUN
                [year] => 2021
                [member_id] => 19208
                [created_at] =>
                [updated_at] =>
                 */

                //Member Attribute
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
