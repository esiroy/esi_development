<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentTransaction;
use App\Models\LessonGoals;
use App\Models\Member;
use App\Models\MemberAttribute;
use App\Models\MemberDesiredSchedule;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\ScheduleItem;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\User;
use App\Models\UserImage;
use Auth;
use Carbon\Carbon;
use DB;

//use App\Models\MemberPointPurchaseHistory;
//use App\Models\Membership;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class MemberController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');

        $this->middleware(function ($request, $next) {
            //authenticated by has no "admin_access" in his role attached
            //@do: redirect to home (authenticated member will be his view)
            if (Gate::denies('admin_access')) {
                return redirect(route('home'));
            }
            return $next($request);           
        });

        
    }

    /**
     * (v2)
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($memberID)
    {
        $agent = new Agent();
        $agentInfo = $agent->getMemberAgent($memberID);
        $memberInfo = Member::where('user_id', $memberID)->first();

        if (isset($memberInfo)) {
            if (isset($memberInfo->tutor_id)) {
                $tutorInfo = Tutor::where('user_id', $memberInfo->tutor_id)->first();
            } else {
                $tutorInfo = null;
            }

            if (isset($memberInfo->lesson_shift_id)) {
                $shift = new Shift();
                $duration = $shift->getDuration($memberInfo->lesson_shift_id);
            } else {
                $duration = null;
            }

            //get Lesson goals
            $goals = new LessonGoals();
            $lessonGoals = $goals->getLessonGoals($memberID);

            //report cards
            $reportCard = new ReportCard();
            $latestReportCard = $reportCard->getLatest($memberID);

            //writing report cards
            $reportCardDate = new ReportCardDate();
            $latestWritingReport = $reportCardDate->getLatest($memberID);

            return view('admin.modules.member.memberInfo', compact('memberInfo', 'tutorInfo', 'agentInfo', 'lessonGoals', 'latestReportCard', 'latestWritingReport'));
        } else {

            abort(404, "Member Not Found");
        }

    }

    /*
    public function details($memberID)
    {
    $member = Member::join('users', 'users.id', '=', 'members.user_id')
    //->leftJoin('attributes', 'attributes.id', '=', 'members.member_attribute_id')
    ->leftJoin('agents', 'agents.id', '=', 'members.agent_id')
    ->leftJoin('tutors', 'tutors.id', '=', 'members.main_tutor_id')
    ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name,
    attributes.name as attribute,
    members.id as id,
    agents.id as agent_id,
    tutors.name_en as main_tutor_name,
    members.credits as credits
    "))->where('members.id', $memberID)->first();

    return view('admin.modules.member.details', compact('member'));
    }
     */

    /**
     * (v2)
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //request variables
        $member_id = $request->member_id;
        $name = $request->name;
        $email = $request->email;

        $attributes = createAttributes();
        $memberships = createMembership();

        $shifts = Shift::all();

        $memberQuery = Member::join('users', 'users.id', '=', 'members.user_id')
            ->leftJoin('agents', 'agents.id', '=', 'members.agent_id')
            ->leftJoin('tutors', 'tutors.id', '=', 'members.tutor_id')
            ->select("*", DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name,
                                        users.id as id,
                                        agents.id as agent_id
                                    "));

        if ($request->expired) {

            $now = Carbon::now();
            $memberQuery = $memberQuery->whereDate('members.credits_expiration', '<', $now->toDateString());
        
        } else if ($request->toexpire) {
            $now = Carbon::now()->subDays(30);
            $memberQuery = $memberQuery->whereDate('members.credits_expiration', '<', $now->toDateString());                

        } else {

            //@[START] USER SEARCH - if user search for a member
            if (isset($member_id) || isset($name) || isset($email)) {
                if (isset($member_id)) {
                    $memberQuery = $memberQuery->where('members.user_id', $member_id);
                }

                if (isset($name)) {
                    $memberQuery = $memberQuery->orWhereRaw("CONCAT(users.firstname,' ',users.lastname) like '%" . $name . "%'")->orWhereRaw("CONCAT(users.lastname,' ',users.firstname) like '%" . $name . "%'");                
                }                

                if (isset($email)) {
                    $memberQuery = $memberQuery->orWhere('users.email', $email);
                }
                
            } //[END] USER SEARCH

        }

        $members = $memberQuery->orderby('users.id', 'ASC')->paginate(Auth::user()->items_per_page);

        //Tutor Query
        //$tutorQuery = User::whereHas('roles', function($q) { $q->where('title', 'Tutor'); })->get();
        $tutorQuery = User::where('user_type', "TUTOR")->get();
        $tutors = json_encode($tutorQuery);

        //MEMBER ACCESS CONTROL
        $can_member_access = (Gate::denies('member_acesss')) ? 'false' : 'true';
        $can_member_create = (Gate::denies('can_member_create')) ? 'false' : 'true';

        $can_member_delete = (Gate::denies('member_delete')) ? 'false' : 'true';
        $can_member_edit = (Gate::denies('member_edit')) ? 'false' : 'true';
        $can_member_view = (Gate::denies('member_view')) ? 'false' : 'true';

        return view('admin.modules.member.index', compact('memberships', 'shifts', 'attributes', 'tutors', 'members',
            'can_member_access', 'can_member_create', 'can_member_edit',
            'can_member_view', 'can_member_delete'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function account($memberID)
    {
        $member = Member::where("user_id", $memberID)->first();

        if (!isset($member)) {
            abort(404);
        }

        $user = $member->user;
        $agent = $member->agent;

        $agentTransaction = new AgentTransaction();
        $credits = $agentTransaction->getCredits($member->user_id);
        $latestDateOfPurchase = $agentTransaction->getMemberLatestDateOfPurchase($member->user_id);

        $transactions = $agentTransaction->getMemberTransactions($member->user_id);
        $purchaseHistory = $agentTransaction->getAllPaymentHistory($member->user_id);

        return view('admin.modules.member.account', compact('member', 'credits', 'transactions', 'purchaseHistory', 'latestDateOfPurchase'));
    }

    /**
     * Display a listing of the payment history.
     * @param $memberID
     */
    public function paymenthistory($memberID)
    {
        $memberInfo = Member::where('user_id', $memberID)->first();

        if ($memberInfo) {

            $member = $memberInfo->user;

            //agent
            $agentInfo = Agent::where('user_id', $memberInfo->agent_id)->first();

            //tutor for
            $tutorInfo = Tutor::where('user_id', $memberInfo->tutor_id)->first();

            $agentTransaction = new AgentTransaction();
            $paymentHistory = $agentTransaction->getPaymentHistory($memberID);

            return view('admin.modules.member.paymenthistory', compact('member', 'memberInfo', 'agentInfo', 'tutorInfo', 'paymentHistory'));

        } else {

            abort(404);

        }

    }

    public function schedulelist($memberID, ScheduleItem $scheduleItem)
    {
        $memberInfo = Member::where('user_id', $memberID)->first();

        if ($memberInfo) {
            $member = $memberInfo->user;

            //agent
            $agentInfo = Agent::where('user_id', $memberInfo->agent_id)->first();

            //tutor for
            $tutorInfo = Tutor::where('user_id', $memberInfo->tutor_id)->first();

            $thisMonth = strtoupper(date("M"));
            $thisYear = date("Y");

            //lesson limit (attribute)
            $memberAttribute = MemberAttribute::where('member_id', $memberID)
                ->where('month', $thisMonth)
                ->where('month', $thisYear)
                ->first();


            $schedules = $scheduleItem->getMemberScheduledLesson($memberID);

            return view('admin.modules.member.schedulelist', compact('schedules', 'member', 'memberInfo', 'agentInfo', 'tutorInfo', 'memberAttribute'));
        } else {
            abort(404);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.modules.member.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //api storing
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($memberID)
    {
        $memberInfo = Member::where('user_id', $memberID)->first();

        //get photo
        $userImageObj = new UserImage();
        $userImage = $userImageObj->getMemberPhoto($memberInfo);

        //user Info
        $userInfo = User::where('id', $memberID)->select('id', 'firstname', 'lastname', 'email',
            'japanese_firstname', 'japanese_lastname',
            'user_type', 'is_activated')->first();

        $attributes = createAttributes();
        $memberships = createMembership();
        $shifts = Shift::all();

        //agent info
        $agent = new Agent();
        $agentInfo = $agent->getMemberAgent($memberID);

        //get Lessongoals (purpose)
        $goals = new LessonGoals();
        $lessonGoals = $goals->getLessonGoals($memberID);

        //MemberAttribute - (lessonClasses)
        $memberAttribute = new MemberAttribute();
        $lessonClasses = $memberAttribute->getMemberAttribute($memberID);

        $memberDesiredSchedule = new MemberDesiredSchedule();
        $desiredSchedule = $memberDesiredSchedule->getMemberDesiredSchedule($memberID);

        //View all the stufff
        return view('admin.modules.member.edit', compact('agentInfo', 'memberships', 'shifts', 'attributes',
            'userInfo', 'memberInfo', 'userImage',
            'lessonGoals', 'lessonClasses', 'desiredSchedule'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => ['required'],
            'amount' => ['required'],
            'credits' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //variables
        $expiry_date = null;

        //AGENT_SUBTRACT
        //DISTRIBUTE, CREDITS_EXPIRATION, MANUAL_ADD, FREE_CREDITS
        $member = Member::where("user_id", $id)->first();

        if (!isset($member)) {
            abort(404);
        }

        $memberUserId = $member->user_id;

        /*
        if ($request->transaction_type == 'AGENT_SUBTRACT') {
        $newCredit = -abs($request->credits);
        $totalCredits = $member->credits - $request->credits;
        } else {
        $newCredit = $request->credits;
        $totalCredits = $member->credits + $newCredit;
        }*/

        $member->update([
            'credits_expiration' => date('Y-m-d G:i:s', strtotime('+6 months')),
        ]);

        if (isset($request->expiry_date)) {
            $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
        } else {
            $expiry_date = date('Y-m-d G:i:s', strtotime('+6 months'));
        }       

        //lesson_shift_id 

        //Update Agent Transaction Table
        $agentCredit = [
            'valid' => 1,
            'transaction_type' => $request->transaction_type,
            'agent_id' => null,
            'member_id' => $member->user_id,
            'lesson_shift_id' => $member->lesson_shift_id,
            'created_by_id' => Auth::user()->id,
            'amount' => $request->credits,
            'price' => $request->amount,
            'remarks' => $request->remarks,
            'credits_expiration' => $expiry_date,
        ];
        AgentTransaction::create($agentCredit);

        return redirect()->route('admin.member.account', $id)->with('message', 'Member transaction has been added successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::where('user_id', $id)->first();
        $user = User::find($member->user_id);

        LessonGoals::where('member_id', $user->id)->delete();
        MemberAttribute::where('member_id', $user->id)->delete();
        MemberDesiredSchedule::where('member_id', $user->id)->delete();

        $member->delete();
        $user->forceDelete();

        return redirect()->route('admin.member.index')->with('message', 'Member has been added deleted!');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Tutor::whereIn('user_id', request('ids'))->delete();
        User::whereIn('user_id', request('ids'))->forceDelete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
