<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\ReportCard;
use App\Models\ScheduleItem;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\User;
use Auth;
use Gate;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        /*JP [default] */
        $this->lessonSlots = array(
            ['id' => 1, 'startTime' => '11:00', 'endTime' => '11:25'],
            ['id' => 2, 'startTime' => '11:30', 'endTime' => '11:55'],
            ['id' => 3, 'startTime' => '12:00', 'endTime' => '12:25'],
            ['id' => 4, 'startTime' => '12:30', 'endTime' => '12:55'],
            ['id' => 5, 'startTime' => '13:00', 'endTime' => '13:25'],
            ['id' => 6, 'startTime' => '13:30', 'endTime' => '13:55'],
            ['id' => 7, 'startTime' => '14:00', 'endTime' => '14:25'],
            ['id' => 8, 'startTime' => '14:30', 'endTime' => '14:55'],
            ['id' => 9, 'startTime' => '15:00', 'endTime' => '15:25'],
            ['id' => 10, 'startTime' => '15:30', 'endTime' => '15:55'],
            ['id' => 11, 'startTime' => '16:00', 'endTime' => '16:25'],
            ['id' => 12, 'startTime' => '16:30', 'endTime' => '16:55'],
            ['id' => 13, 'startTime' => '17:00', 'endTime' => '18:25'],
            ['id' => 14, 'startTime' => '17:30', 'endTime' => '18:55'],
            ['id' => 15, 'startTime' => '18:00', 'endTime' => '18:25'],
            ['id' => 16, 'startTime' => '18:30', 'endTime' => '18:55'],
            ['id' => 17, 'startTime' => '19:00', 'endTime' => '19:25'],
            ['id' => 18, 'startTime' => '19:30', 'endTime' => '19:55'],
            ['id' => 19, 'startTime' => '20:00', 'endTime' => '21:25'],
            ['id' => 20, 'startTime' => '20:30', 'endTime' => '21:55'],
            ['id' => 21, 'startTime' => '21:00', 'endTime' => '22:25'],
            ['id' => 22, 'startTime' => '21:30', 'endTime' => '22:55'],
            ['id' => 23, 'startTime' => '22:00', 'endTime' => '23:25'],
            ['id' => 24, 'startTime' => '22:30', 'endTime' => '23:55'],
            ['id' => 25, 'startTime' => '23:00', 'endTime' => '23:25'],
            ['id' => 26, 'startTime' => '23:30', 'endTime' => '23:55'],
            ['id' => 27, 'startTime' => '24:00', 'endTime' => '24:25'],
            ['id' => 28, 'startTime' => '24:30', 'endTime' => '24:55'],
        );

    }

    /**
     * MAIN PAGE SHOWING BUTTONS FOR RESERVATIONS.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        if (isset($member)) {
            $memberData = Member::find($member->id);
            $skypeID = $memberData->communication_app_username;

            $tutorData = Tutor::find($member->main_tutor_id);
            $lecturer = (isset($tutorData->name_en)) ? $tutorData->name_en : '';

            $data = [
                'lecturer' => $lecturer,
                'skypeID' => $skypeID,
            ];

            $latestReportCard = ReportCard::OrderBy('created_at', 'DESC')->first();

            return view('modules/member/reservations', compact('member', 'data', 'latestReportCard'));
        } else {

            $roles = Auth::user()->roles;
            if (!$roles->contains('title', 'Member')) {
                return redirect(route('admin.dashboard.index'));
            } else {
                /**
                 * @todo: make a proper message here to your users that
                 * @todo: other roles tried to view this page, abort the page.
                 */
                abort(403, 'Unauthorized action, you are not allowed to view this page');
            }
        }
    }

    /**
     * Member SEARCH FOR RESERVATIONS Reservations.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ScheduleItem $scheduleItem, Request $request)
    {

        if (Gate::allows('member_lesson_scheduler_access')) {

            //default on load without any parameters
            if (isset($request['dateToday'])) {
                $dateToday = date('Y-m-d', strtotime($request['dateToday']));
                $year = date('Y', strtotime($request['dateToday']));
                $month = date('m', strtotime($request['dateToday']));
                $day = date('d', strtotime($request['dateToday']));

            } else {
                $dateToday = date('Y-m-d');
                $year = date('Y');
                $month = date('m');
                $day = date('d');
            }

            if (isset($request['shift_duration'])) {
                $shiftDuration = $request['shift_duration'];
            } else {
                $shiftDuration = 25;
            }

            //search the ID
            $shift = Shift::where("value", $shiftDuration)->first();


            //GET LESSONS FROM DATE TODAY ONLY
            $schedules = $scheduleItem->getReservations($dateToday, $shift->id);

            $ctr = 0;
            
            /*
            [id] => 858218
            [lesson_time] => 2021-01-13 16:00:00
            [tutor_id] => 12289
            [member_id] => 
            [schedule_status] => TUTOR_SCHEDULED
            [duration] => 25
            [lesson_shift_id] => 4
            [memo] => 
            [valid] => 1
            [email_type] => 
            [created_at] => 2021-01-02 18:17:06
            [updated_at] => 2021-01-02 18:17:06
            */

   

            //get tutors for this shift id
            $tutors = Tutor::where('lesson_shift_id', $shift->id)->where('is_terminated', false)->get();
            //$member = Member::where('user_id', Auth::user()->id)->first();

            //LESSON SLOTS
            $lessonSlots = $this->lessonSlots;

      

            /*
            foreach($lessonSlots as $lessonSlot) {

            $startTimePH = date('h:i', strtotime($lessonSlot['startTime'] ." - 1 hour "));
            foreach ($schedules as $schedule) {

            if(isset($schedule[$startTimePH])) {
                echo "scheduler link";
            }
            }
            }*/

            $latestReportCard = ReportCard::OrderBy('created_at', 'DESC')->limit(1)->first();

            //return view('/modules/member/scheduler', compact('schedules', 'dateToday', 'year', 'month', 'day', 'shiftDuration', 'tutors', 'members', 'schedules', 'lessonSlots', 'latestReportCard'));

        } else {

            echo "You have no access for scheduler, please contact administrator";
        }
    }

}
