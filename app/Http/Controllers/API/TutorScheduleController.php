<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Tutor;
use App\Models\ScheduleItem;
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
                ->select('members.id', 'members.user_id', 'users.firstname', 'users.lastname', 'users.valid')
                ->where('users.valid', 1)->get();        

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


            $tutor = $request['tutorData'];
            $member = $request['memberData'];


            $tutorInfo =  Tutor::find($tutor['tutorID']);


       
                        

            if ($request['status'] == 'TUTOR_CANCELLED') {
                $emailType = $request['cancelationType'];
                $lessonData = [
                    'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'])),
                    'duration' => $request['shiftDuration'],
                    'tutor_id' => $tutorInfo->user_id,
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];
            } else if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') {
                $emailType = $request['reservationType'];

                if (isset($member['id'])) {

                    //$memberID = $member['id'];

                    //v2 - change id to user_id
                    $memberInfo = Member::find($member['id']);

                    $lessonData = [
                        'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] . " +1 hour")),
                        'duration' => $request['shiftDuration'],
                        'tutor_id' => $tutorInfo->user_id,
                        'member_id' => $memberInfo->user_id,
                        'schedule_status' => $request['status'],
                        'email_type' => $emailType,
                        'valid' => 1,
                    ];
                } else {

                    $lessonData = [
                        'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] . " +1 hour")),
                        'duration' => $request['shiftDuration'],
                        'tutor_id' => $tutorInfo->user_id,
                        'schedule_status' => $request['status'],
                        'email_type' => $emailType,
                        'valid' => 1,
                    ];
                }

            } else {
                $emailType = null;
                $lessonData = [
                    'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] . " +1 hour" )),
                    'duration' => $request['shiftDuration'],
                    'tutor_id' => $tutorInfo->user_id,
                    'schedule_status' => $request['status'],
                    'email_type' => $emailType,
                    'valid' => 1,
                ];
            }

            //change to schedule item for max compatibility of data export and import of old system
            $lessonTime = date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] ." +1 hour"));

            $scheduleItem = ScheduleItem::where('tutor_id', $tutorInfo->user_id)
                ->where('duration', $request['shiftDuration'])
                ->where('lesson_time', $lessonTime)->first();

            $scheduleItem->update($lessonData);

            DB::commit();

            //get lessons
            $scheduleItem = new ScheduleItem();
            $scheduled_at = $request['scheduled_at'];
            $duration = $request['shiftDuration'];

            $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);

            return Response()->json([
                "success" => true,
                "message" => "Lesson has been updated",
                "tutorData" => $tutorInfo->user_id,
                "memberData" => $request['memberData'],
                'tutorLessonsData' => $tutorLessonsData,
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
    public function store(Request $request)
    {
        $lessonData = null;

        try {
            DB::beginTransaction();
            $tutor = $request['tutorData'];
            $member = $request['memberData'];

            if ($request['status'] == 'TUTOR_CANCELLED') {
                $emailType = $request['cancelationType'];
            } else if ($request['status'] == 'CLIENT_RESERVED' || $request['status'] == 'CLIENT_RESERVED_B') {
                $emailType = $request['reservationType'];

            } else {
                $emailType = null;
            }

            /*
            $lessonData = [
            'scheduled_at'  => $request['scheduled_at'],
            'duration'      => $request['shiftDuration'],
            'email_type'    => $emailType,
            'creator_id'    => (isset(Auth::user()->id)) ? Auth::user()->id : null,
            'tutor_id'      => $tutor['tutorID'],
            'start_time'     => $tutor['startTime'],
            'end_time'       => $tutor['endTime'],
            'member_id'     => $member['id'],
            'status'        => $request['status']
            ];*/

            

            if (isset($member['id'])) {
                $memberID = $member['id'];
            } else {
                $memberID = null;
            }

            //v2 - change id to user_id
            $memberInfo = Member::find($member['id']);
            $tutorInfo =  Tutor::find($tutor['tutorID']);

            $lessonData = [
                'lesson_time' => date("Y-m-d H:i:s", strtotime($request['scheduled_at'] . " " . $tutor['startTime'] ." + 1 hour")), //save the japanese time
                'duration' => $request['shiftDuration'],
                'email_type' => $emailType,
                'tutor_id' => $tutorInfo->user_id,
                'member_id' => $member['id'],
                'schedule_status' => str_replace(' ', '_', $request['status']),
                'duration' => $request['shiftDuration'],
                'lesson_shift_id' => 1, //25 minutes
                'valid' => 1,
                'memo' => "",
            ];

            $scheduleItem = ScheduleItem::create($lessonData);
            DB::commit();

            //SCHEDULES ARE NEW!
            //GET LESSON AND RENEW THE LESSON THAT HAS BEEN ADDED FOR TUTOR

            $scheduleItem = new ScheduleItem();
            $scheduled_at = $request['scheduled_at'];
            $duration = $request['shiftDuration'];

            $tutorLessonsData = $scheduleItem->getSchedules($scheduled_at, $duration);

            //@todo: email user
            return Response()->json([
                "success" => true,
                "message" => "Lesson has been added",
                "tutorData" => $request['tutorData'],
                "memberData" => $request['memberData'],
                'tutorLessonsData' => $tutorLessonsData,
            ]);

        } catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Exception Error Found (Create Tutor Schedule) : " . $e->getMessage(),
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

        $deleted = ScheduleItem::where('tutor_id', $tutor->user_id)
            ->where('duration', $duration)
            ->where('lesson_time', $lessonTime)->delete();

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
