<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Tutor;
use App\Models\ScheduleItem;
use App\Models\AgentTransaction;
use App\Models\Shift;
use Auth;

//use App\Models\Lesson;

use DB;
use Illuminate\Http\Request;

class TutorScheduleController extends Controller
{

    public function getMembers(Request $request)
    {
        try {        
            
            $members = Member::join('users', 'users.id', '=', 'members.user_id')
                ->select('members.id', 'members.user_id', 'members.nickname', 'users.firstname', 'users.lastname', 'users.valid')
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

        try {
            DB::beginTransaction();

            $scheduledItemData = $request['scheduledItemData'];

            $tutor = $request['tutorData'];
            $member = $request['memberData'];
            $tutorInfo =  Tutor::find($tutor['tutorID']);

            if (isset($member['id'])) {
                $memberID = $member['id'];
            } else {
                $memberID = null;
            }            


            //v2 - change id to user_id
            $memberInfo = Member::where('user_id', $member['id'])->first();

            if ($memberInfo) {
                $memberData = [
                    'id'        => $memberInfo->user_id,
                    'nickname'  => $memberInfo->nickname,
                    'firstname' => preg_replace('/[^A-Za-z0-9]/', ' ',  $memberInfo->user->firstname),
                    'lastname'  => preg_replace('/[^A-Za-z0-9]/', ' ',  $memberInfo->user->lastname),                    
                ];
            } else {
                $memberData = [
                    'id'        => "",
                    'nickname'  => "",
                    'firstname' => "",
                    'lastname'  => ""
                ];          
            }
            
            

            if ($request['status'] == 'TUTOR_CANCELLED') {

                $emailType = $request['cancelationType'];

                $lessonData = [
                    //'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] . " +1 hour")),
                    //'duration' => $request['shiftDuration'],
                    //'tutor_id' => $tutorInfo->user_id,                    
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];


            } else if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') {


                $emailType = $request['reservationType'];

                /*
                if (isset($member['id'])) {                    
                    $memberInfo = Member::where("user_id", $member['id'])->first();
                    if (isset($memberInfo->user_id)) {
                        $member_id = null;
                    } else {
                        $member_id = $memberInfo->user_id;
                    }
                } else {
                    $member_id = null;
                }
                */

                $lessonData = [
                    //'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] . " +1 hour")),
                    //'duration' => $request['shiftDuration'],
                    //'tutor_id' => $tutorInfo->user_id,
                    'member_id' => $memberID,
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];
               

            } else {
                $emailType = null;
                $lessonData = [
                    //'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] . " +1 hour" )),
                    //'duration' => $request['shiftDuration'],
                    //'tutor_id' => $tutorInfo->user_id,
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];
            }

            //change to schedule item for max compatibility of data export and import of old system
            /*
            $lessonTime = date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] ." +1 hour"));
            $scheduleItem = ScheduleItem::where('tutor_id', $tutorInfo->user_id)
                ->where('duration', $request['shiftDuration'])
                ->where('lesson_time', $lessonTime)->first();
            */


            $scheduleItem = ScheduleItem::find($scheduledItemData['id']);

            //@todo: update only when exists?
            $scheduleItem->update($lessonData);

            DB::commit();



            //** ADD MEMBER TRANSACTION */
            /**@todo: add transaction only when it exits? */
            if ($memberID != null) {                
                $memberTransactionData = [
                    //'scheduleItem'      => $scheduleItem,
                    'memberID'          => $memberID,
                    'shiftDuration'      => $request['shiftDuration'],
                    'status'            => $request['status']
                ];
                $transactionObj = new AgentTransaction();
                $transaction = $transactionObj->addMemberTransactions($memberTransactionData);
            }


            //get lessons
            $scheduleItem = new ScheduleItem();
            $scheduled_at = $request['scheduled_at'];
            $duration = $request['shiftDuration'];

            //get all schedules (this is now ommited since it wil be fetch from ajax on the component)
            //$tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);

            return Response()->json([
                "success" => true,
                "memberID" => $memberID,
                "message" => "Lesson has been updated",
                "scheduleItemID"        => $scheduledItemData['id'],
                "tutorData" => $tutor,
                "memberData" => $memberData,
                //'tutorLessonsData' => $tutorLessonsData,
            ]);

        } catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Exception Error Found in (Update Tutor Schedule) : " . $e->getMessage() ." on Line " . $e->getLine(),
            ]);

            DB::rollback();
        }

    }

    /**
     * Store a newly created schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ScheduleItem $scheduleItem) {

        $lessonData = null;
        $scheduled_at = $request['scheduled_at'];
        $duration = $request['shiftDuration'];        

        try {
            DB::beginTransaction();
            $tutor = $request['tutorData'];
            $member = $request['memberData'];

            if (isset($member['id'])) {
                $memberID = $member['id'];
            } else {
                $memberID = null;
            }

            if ($request['status'] == 'TUTOR_CANCELLED') 
            {
                $emailType = $request['cancelationType'];

            } else if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') {
                $emailType = $request['reservationType'];
            } else {
                $emailType = null;
            }


            //v2 - change id to user_id
            $memberInfo = Member::where('user_id', $member['id'])->first();

            if ($memberInfo) {
                $memberData = [
                    'id'        => $memberInfo->user_id,
                    'nickname'  => $memberInfo->nickname,
                    'firstname' => preg_replace('/[^A-Za-z0-9]/', ' ',  $memberInfo->user->firstname),
                    'lastname'  => preg_replace('/[^A-Za-z0-9]/', ' ',  $memberInfo->user->lastname),    
                ];
            } else {
                $memberData = [
                    'id'        => "",
                    'nickname'  => "",
                    'firstname' => "",
                    'lastname'  => ""
                ];          
            }

            $tutorInfo =  Tutor::find($tutor['tutorID']);
            $lessonTime = date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] ." + 1 hour"));
            $schedule_status = str_replace(' ', '_', $request['status']);

            $shift = Shift::where('value', $request['shiftDuration'])->first();

            //STORE TO scheduleItem TABLE
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

            //@todo: check if not exists?
            $isLessonExists = ScheduleItem::where('lesson_time', $lessonTime)
                        ->where('tutor_id',$tutorInfo->user_id)
                        //->where('member_id', $member['id'])
                        //->where('schedule_status', $schedule_status)
                        //->where('duration', $request['shiftDuration'])
                        //->where('lesson_shift_id', $shift->id)
                        ->where('valid', 1)
                        ->exists();
            
            if ($isLessonExists) 
            {               
                $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);

                return Response()->json([
                    "success" => false,
                    "refresh"  => true,
                    'tutorLessonsData' => $tutorLessonsData,
                    "message" => "The Schedule $lessonTime is already booked, press okay to refresh schedules.",
                ]);
            } 


            //@todo: check if member has other booked schedule on this time slot?
            //@todo: check if not exists?
            if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') 
            {

                $isTutorReserved = ScheduleItem::where('lesson_time', $lessonTime)
                            //->where('tutor_id',$tutorInfo->user_id)
                            ->where('member_id', $member['id'])
                            ->where('schedule_status', $request['status'])
                            ->where('valid', 1)
                            ->exists();

                $isTutorReserved_b = ScheduleItem::where('lesson_time', $lessonTime)
                            //->where('tutor_id',$tutorInfo->user_id)
                            ->where('member_id', $member['id'])
                            ->where('schedule_status', $request['status'])
                            ->where('valid', 1)
                            ->exists();                            

                if ($isTutorReserved || $isTutorReserved_b) 
                {   
                    return Response()->json([
                        "success" => false,
                        "refresh"  => false,                        
                        "message" => "Member already have a booked schedule for this  $lessonTime  time slot, try booking other time slot for this member.",
                    ]);
                } 
                

            }


            $scheduleItem = ScheduleItem::create($lessonData);
            DB::commit();

            //** ADD MEMBER TRANSACTION */
            if ($memberID != null) {

                $memberTransactionData = [
                    //'scheduleItem'      => $scheduleItem,
                    'memberID'          => $memberID,
                    'shiftDuration'     => $request['shiftDuration'],
                    'status'            => $request['status']
                ];   

                $transactionObj = new AgentTransaction();
                $transaction = $transactionObj->addMemberTransactions($memberTransactionData);
            }
            
            //$tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);
            //@todo: email user
            return Response()->json([
                "success" => true,
                "message" => "Lesson has been added",
                "scheduleItemID"  => $scheduleItem->id,
                "tutorData" => $request['tutorData'],
                "memberData" => $memberData,                
                //'tutorLessonsData' => $tutorLessonsData, //@todo: replace this MEMOMY HUGGER WITH ID JS
            ]);

        } catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Exception Error Found (Create Tutor Schedule) : " . $e->getMessage() ." on Line " . $e->getLine(),
            ]);

            DB::rollback();
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

        $deleted = ScheduleItem::where('tutor_id', $tutor->user_id)->where('duration', $duration)->where('lesson_time', $lessonTime)->delete();

        if ($deleted) {
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
}
