<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use Gate;
use Validator;
use Input;
use Auth;
use DB;

use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\MemberLesson;
use App\Models\Purpose;
use App\Models\MemberDesiredSchedule;
use App\Models\Lesson;
use App\Models\ScheduleItem;


class MemberController extends Controller
{

    /*  
     Book a schedule
    */
    public function bookSchedule(Request $request) 
    {
       $id = $request->id;

       //@todo: check if the schedule is unique


       //@todo: check if 3 hours and have attribute



       //@todo: save to database       
       $schedule = ScheduleItem::find($id);
       $data = ['schedule_status'=> 'CLIENT_RESERVED'];
       $schedule->update($data);
       

       return Response()->json([
           "success"       => true,
           "message"       => "Member has been scheduled",
           "userData"      => $request['user']
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
            'member_id'         => null,
            'schedule_status'   => 'CLIENT_NOT_AVAILABLE'
        ];
       $schedule->update($data);
       

       return Response()->json([
           "success"       => true,
           "message"       => "Member has been cancelled scheduled",
           "userData"      => $request['user']
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
            'first_name'    => $data->first_name,
            'last_name'     => $data->last_name,            
            'email'         => $data->email
        ], 
        [
            'first_name'    => ['required', 'max:255'],
            'last_name'     => ['required', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            //'username' => ['required','max:190',Rule::unique('users')->whereNull('deleted_at')],            
        ]);

        if ($validator->fails()) {

            return Response()->json([
                "success" => false,
                "message"   => implode(", ", $validator->errors()->all()) 
            ]);

        } else {

            DB::beginTransaction();

            try { 

                $userData =
                [                
                    'first_name'    => $data->first_name,
                    'last_name'     => $data->last_name,
                    'email'         => $data->email,
                    //'username'      => $data->username,
                    'username'      => $data->email,
                    'password'      => $data->password,
                    'api_token'     => Hash('sha256', Str::random(80))
                ];
                $user = User::create($userData);
            
                //Add Role
                $roles[] = Role::where('title', 'Member')->first()->id;
                $user->roles()->sync($roles); 
                
            
                $memberInformation =
                [
                    'user_id'                   =>  $user->id,
                    'member_attribute_id'       =>  $data->attribute->id,
                    'nickname'                  =>  $data->username,
                    'agent_id'                  =>  $data->agent_id,
                    'gender'                    =>  $data->gender,
                    'birthdate'                 =>  date('Y-m-d', strtotime($data->birthday)),
                    'age'                       =>  $data->age,
                    'communication_app_name'    =>  $data->communication_app,
                    'communication_app_username' => $data->communication_app_username,
                    'membership_id'             =>  $data->membership,                    
                    'exam_record_id'            =>  1, //@todo: remove exam or nullify
                    'member_since'              =>  (isset($data->member_since))? date('Y-m-d', strtotime($data->member_since)) : date('Y-m-d'),
                    'lesson_time_id'            =>  $data->lessonshiftid,
                    'main_tutor_id'             =>  $data->maintutorid,
                    'agent_report_card'         =>  (boolean) $data->reportCard->agent,
                    'agent_monthly_report'      =>  (boolean)  $data->monthlyReport->agent,

                    'member_report_card'        =>  (boolean) $data->reportCard->member,
                    'member_monthly_report'     =>  (boolean) $data->monthlyReport->member,     

                    'point_purchase'            =>  $data->pointPurchase,
                    //'desired_schedules'         =>  $data->desired_schedules,
                ];
                $member = Member::create($memberInformation);
                $user->members()->sync([$member->id], false);  


                /****************************
                    Preferred Tutor                
                *****************************/
                //Purpose Data 
                $purposeData = [];
                foreach($data->preference->purpose as $key => $purpose) 
                {   
                    if (isset($data->preference->purposeExtraDetails->$key)) {
                        $purposeExtraDetails = $data->preference->purposeExtraDetails->$key;
                    } else {
                        $purposeExtraDetails = null;
                    }
        
                    array_push($purposeData, [
                                "user_id"   => $user->id,
                                "name"       => $key, 
                                "purpose"    => $purposeExtraDetails ]);
                }
                Purpose::where('user_id', $user->id)->delete();
                $purpose = Purpose::insert($purposeData);


                //Lesson Classes
                $lessonClasses = [];
                foreach ($data->preference->lessonClasses as $class) {
                    array_push($lessonClasses, [
                                            "user_id"   => $user->id,
                                            "attribute" => $class->attribute->name, 
                                            "grade"     => $class->grade,
                                            "month"     => $class->month,
                                            "year"      => $class->year 
                                        ]);
                }
                MemberLesson::where('user_id', $user->id)->delete();
                MemberLesson::insert($lessonClasses);



                //Desired Schedule
                $desiredSchedules = [];
                foreach ($data->desiredScheduleList as $schedule) {
                    array_push($desiredSchedules, [
                        "user_id"   => $user->id,
                        "day"       => $schedule->day,
                        "time"      => $schedule->time,
                    ]);
                }
                MemberDesiredSchedule::where('user_id', $user->id)->delete();
                MemberDesiredSchedule::insert($desiredSchedules);
        
                DB::commit();

                return Response()->json([
                    "success"       => true,
                    "message"       => "Member has been added",
                    "userData"      => $request['user']
                ]);
            } 
            catch (\Exception $e) 
            {
                return Response()->json([
                    "success"   => false,
                    "message"   => "Exception Error Found (Member) : " . $e->getMessage()
                ]);

                DB::rollback();
                // something went wrong
            }
        }
       

    }


    public function update(Request $request)
    {
        //abort_if(Gate::denies('member_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = json_decode($request['user']);

        //disallow duplicate email and username
        $validator = Validator::make(
        [
            'first_name'    => $data->first_name,
            'last_name'     => $data->last_name,            
            'email'         => $data->email
        ], 
        [
            'first_name'    => ['required', 'max:255'],
            'last_name'     => ['required', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            //'username' => ['required','max:190',Rule::unique('users')->whereNull('deleted_at')],            
        ]);

        if ($validator->fails()) {

            return Response()->json([
                "success" => false,
                "message"   => implode(", ", $validator->errors()->all()) 
            ]);

        } else {

            DB::beginTransaction();

            try { 

                $userData =
                [                
                    'first_name'    => $data->first_name,
                    'last_name'     => $data->last_name,
                    'email'         => $data->email,
                    //'username'      => $data->username,
                    'username'      => $data->email,
                    //'password'      => $data->password,
                    'api_token'     => Hash('sha256', Str::random(80))
                ];
                $user = User::create($userData);
            
                //Add Role
                $roles[] = Role::where('title', 'Member')->first()->id;
                $user->roles()->sync($roles); 
                
            
                $memberInformation =
                [
                    'user_id'                   =>  $user->id,
                    'member_attribute_id'       =>  $data->attribute->id,
                    'nickname'                  =>  $data->username,
                    'agent_id'                  =>  $data->agent_id,
                    'gender'                    =>  $data->gender,
                    'birthdate'                 =>  date('Y-m-d', strtotime($data->birthday)),
                    'age'                       =>  $data->age,
                    'communication_app_name'    =>  $data->communication_app,
                    'communication_app_username' => $data->communication_app_username,
                    'membership_id'             =>  $data->membership,                    
                    'exam_record_id'            =>  1, //@todo: remove exam or nullify
                    'member_since'              =>  (isset($data->member_since))? date('Y-m-d', strtotime($data->member_since)) : date('Y-m-d'),
                    'lesson_time_id'            =>  $data->lessonshiftid,
                    'main_tutor_id'             =>  $data->maintutorid,
                    'agent_report_card'         =>  (boolean) $data->reportCard->agent,
                    'agent_monthly_report'      =>  (boolean)  $data->monthlyReport->agent,

                    'member_report_card'        =>  (boolean) $data->reportCard->member,
                    'member_monthly_report'     =>  (boolean) $data->monthlyReport->member,     

                    'point_purchase'            =>  $data->pointPurchase,
                    //'desired_schedules'         =>  $data->desired_schedules,
                ];
                $member = Member::create($memberInformation);
                $user->members()->sync([$member->id], false);  


                /****************************
                    Preferred Tutor                
                *****************************/
                //Purpose Data 
                $purposeData = [];
                foreach($data->preference->purpose as $key => $purpose) 
                {   
                    if (isset($data->preference->purposeExtraDetails->$key)) {
                        $purposeExtraDetails = $data->preference->purposeExtraDetails->$key;
                    } else {
                        $purposeExtraDetails = null;
                    }
        
                    array_push($purposeData, [
                                "user_id"   => $user->id,
                                "name"       => $key, 
                                "purpose"    => $purposeExtraDetails ]);
                }
                Purpose::where('user_id', $user->id)->delete();
                $purpose = Purpose::insert($purposeData);


                //Lesson Classes
                $lessonClasses = [];
                foreach ($data->preference->lessonClasses as $class) {
                    array_push($lessonClasses, [
                                            "user_id"   => $user->id,
                                            "attribute" => $class->attribute->name, 
                                            "grade"     => $class->grade,
                                            "month"     => $class->month,
                                            "year"      => $class->year 
                                        ]);
                }
                MemberLesson::where('user_id', $user->id)->delete();
                MemberLesson::insert($lessonClasses);



                //Desired Schedule
                $desiredSchedules = [];
                foreach ($data->desiredScheduleList as $schedule) {
                    array_push($desiredSchedules, [
                        "user_id"   => $user->id,
                        "day"       => $schedule->day,
                        "time"      => $schedule->time,
                    ]);
                }
                MemberDesiredSchedule::where('user_id', $user->id)->delete();
                MemberDesiredSchedule::insert($desiredSchedules);
        
                DB::commit();

                return Response()->json([
                    "success"       => true,
                    "message"       => "Member has been added",
                    "userData"      => $request['user']
                ]);
            } 
            catch (\Exception $e) 
            {
                return Response()->json([
                    "success"   => false,
                    "message"   => "Exception Error Found (Member) : " . $e->getMessage()
                ]);

                DB::rollback();
                // something went wrong
            }
        }
       

    }
   
}
