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
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class MemberController extends Controller
{

    public function getAgent(Request $request)
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
    Book a schedule
     */
    public function bookSchedule(Request $request)
    {
        $scheduleID = $request->scheduleID;
        $memberID = $request->memberID;
        //@todo: check if the schedule is unique

        //@todo: check if 3 hours and have attribute

        //@todo: save to database
        $schedule = ScheduleItem::find($scheduleID);
        $data = [
            'member_id' => $memberID,
            'schedule_status' => 'CLIENT_RESERVED',
        ];
        $schedule->update($data);

        return Response()->json([
            "success" => true,
            "message" => "Member has been scheduled",
            "userData" => $request['user'],
        ]);

    }

    public function cancelSchedule(Request $request)
    {
        $id = $request->id;

        //@todo: check if the schedule is present

        //@todo: refund points if status is [client reserved]
        //@todo : no refund points if status if [client reserved b]
        //@todo: (cancellation is 3 hours grace period)

        //@todo: save to database
        $schedule = ScheduleItem::find($id);
        $data = [
            'member_id' => null,
            'schedule_status' => 'CLIENT_NOT_AVAILABLE',
        ];
        $schedule->update($data);

        return Response()->json([
            "success" => true,
            "message" => "Member has been cancelled scheduled",
            "userData" => $request['user'],
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

                $agent = Agent::where('agent_id', $data->agent_id)->first();

                $memberInformation =
                    [
                    'user_id' => $user->id,
                    'tutor_id' => $tutorInfo->user_id,
                    'age' => $data->age,
                    'attribute' => strtoupper($data->attribute),
                    'birthday' => date('Y-m-d', strtotime($data->ubirthday)),
                    'member_since' => (isset($data->umember_since)) ? date('Y-m-d', strtotime($data->umember_since)) : date('Y-m-d'),
                    'nickname' => $data->nickname,
                    'gender' => $data->gender,
                    'agent_id' => $agent->user_id,
                    'lesson_shift_id' => $data->lessonshiftid,
                    //course_category_id        => null,
                    //course_item_id            => null,
                    //english_level             => null,
                    'communication_app' => ucfirst(strtolower($data->communication_app)),
                    'skype_account' => ($data->communication_app == 'skype') ? $data->communication_app_username : null,
                    'zoom_account' => ($data->communication_app == 'zoom') ? $data->communication_app_username : null,
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

        /*
        foreach($data->preference->purpose as $key => $purpose)
        {
        echo $key ." <BR> ";
        }

        echo "<pre>";
        print_r ($data);
        exit();
         */

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

                /******
                 * UPDATE USER INFORMATION
                 */
                $userData =
                    [
                    'user_type' => 'MEMBER',
                    "valid" => true,
                    'firstname' => $data->first_name,
                    'lastname' => $data->last_name,
                    'email' => $data->email,
                    'username' => $data->email,
                    //'password'      => $data->password,
                    //'api_token'     => Hash('sha256', Str::random(80)),

                ];
                $user = User::where('id', $data->user_id)->update($userData);

                /**
                 * NO ROLE WILL BE ADDED SINCE THIS IS UPDATE
                 * $roles[] = Role::where('title', 'Member')->first()->id;
                 * $user->roles()->sync($roles);
                 */

                $tutorInfo = Tutor::where('user_id', $data->maintutorid)->first();

                
                $agent = Agent::where('agent_id', $data->agent_id)->first();

                $memberInformation =
                    [
                    //'user_id'                 =>  $data->user_id,
                    'tutor_id' => $tutorInfo->user_id,
                    'age' => $data->age,
                    'attribute' => strtoupper($data->attribute),
                    'birthday' => date('Y-m-d', strtotime($data->ubirthday)),
                    'member_since' => (isset($data->umember_since)) ? date('Y-m-d', strtotime($data->umember_since)) : date('Y-m-d'),
                    'nickname' => $data->nickname,
                    'gender' => $data->gender,
                    'agent_id' => $agent->user_id,
                    'lesson_shift_id' => $data->lessonshiftid,
                    //course_category_id        => null,
                    //course_item_id            => null,
                    //english_level             => null,
                    'communication_app' => ucfirst(strtolower($data->communication_app)),
                    'skype_account' => ($data->communication_app == 'skype') ? $data->communication_app_username : null,
                    'zoom_account' => ($data->communication_app == 'zoom') ? $data->communication_app_username : null,
                    'membership' => $data->membership,

                    'is_report_card_visible_to_agent' => (boolean) $data->reportCard->agent,
                    'is_monthly_report_card_visible_to_agent' => (boolean) $data->monthlyReport->agent,

                    'is_report_card_visible' => (boolean) $data->reportCard->member,
                    'is_monthly_report_card_visible' => (boolean) $data->monthlyReport->member,

                    'point_purchase_type' => $data->pointPurchase,

                    //@todo: WILL NOT update credit expiration since it will depend on creation?
                    //'credits_expiration'                =>  date('Y-m-d G:i:s', strtotime('+6 months'))

                ];
                $member = Member::where('id', $data->user_id)->update($memberInformation);

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
                print_r($data->preference->lessonClasses);

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
                        "lesson_limit" => $class->lesson_limit, //@todo: change to lesson limit
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
                    "message" => "Member has been added",
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
