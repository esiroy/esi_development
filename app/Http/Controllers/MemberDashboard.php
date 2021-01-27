<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\ReportCard;
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
    public function index()
    {
        $user = Auth::user();
        $memberInfo = Member::where('user_id', $user->id)->first();

        if (isset($memberInfo)) {
            $skypeID = $memberInfo->communication_app_username;

            $reserves = ScheduleItem::where('member_id', $user->id)->where('valid', 1)->where(function ($q) use ($user) {$q->orWhere('schedule_status', 'CLIENT_RESERVED')->orWhere('schedule_status', 'CLIENT_RESERVED_B');

            })->orderby('created_at', 'DESC')->get();

            $latestReportCard = ReportCard::OrderBy('created_at', 'DESC')->first();

            return view('modules/member/index', compact('memberInfo', 'reserves', 'latestReportCard'));

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

}
