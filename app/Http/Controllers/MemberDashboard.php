<?php

namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\ReportCard;
use App\Models\MemberDesiredSchedule;
use App\Models\ScheduleItem;
use App\Models\User;
use Auth;

class MemberDashboard extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ScheduleItem $scheduleItem, ReportCard $reportcard, MemberDesiredSchedule $memberDesiredSchedules )
    {
        $member = Member::where('user_id', Auth::user()->id)->first();

        if (isset($member)) {

            $reserves = $scheduleItem->getMemberLessons($member);

            $latestReportCard = $reportcard->getLatest($member->user_id);

            $desiredSchedules = $memberDesiredSchedules->getMemberDesiredSchedule($member->user_id);            

            return view('modules/member/index', compact('member', 'reserves', 'latestReportCard',  'desiredSchedules'));

        } else {
            
            if (Auth::user()->roles->contains('title', 'Admin')) {
                return redirect(route('admin.dashboard.index'));
            } else {            
                abort(403, 'Unauthorized action, you are not allowed to view this page');
            }
        }
    }
}