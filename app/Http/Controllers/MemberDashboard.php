<?php

namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\ReportCard;
use App\Models\MemberDesiredSchedule;
use App\Models\ScheduleItem;
use App\Models\User;
use App\Models\Announcement;
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
    public function index(ScheduleItem $scheduleItems, ReportCard $reportcards, MemberDesiredSchedule $memberDesiredSchedules,  Announcement $announcements) 
    {
        $member = Member::where('user_id', Auth::user()->id)->first();

        if (isset($member)) {

            $reserves = $scheduleItems->getMemberActiveLessons($member);

            $latestReportCard = $reportcards->getLatest($member->user_id);

            $desiredSchedules = $memberDesiredSchedules->getMemberDesiredSchedule($member->user_id);            

            $today = date('Y-m-d');

            $announcement = $announcements->where('valid', true)
                            ->where('is_hidden', false)
                            ->orderBy('created_at', 'DESC')
                            ->whereDate('date_from','<=', $today)
                            ->whereDate('date_to','>=', $today)
                            ->first();

            return view('modules/member/index', compact('member', 'reserves', 'latestReportCard',  'desiredSchedules', 'announcement'));

        } else {
            
            if (Auth::user()->roles->contains('title', 'Admin')) {
                return redirect(route('admin.dashboard.index'));
            } else {            
                abort(403, 'Unauthorized action, you are not allowed to view this page');
            }
        }
    }
}