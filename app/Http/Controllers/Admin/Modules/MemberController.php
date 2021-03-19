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


use Auth, Hash;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //request variables
        $member_id = request()->member_id;
        $name = $request->name;
        $email = $request->email;

        $attributes = createAttributes();
        $memberships = createMembership();

        $shifts = Shift::all();

        $memberQuery = Member::join('users', 'users.id', '=', 'members.user_id')                            
                            ->select("members.*", "users.id", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name"));

        if ($request->expired) {

            $today =   Carbon::now();
            $dateFrom = Carbon::now()->subDays(30); //expired for 30 days

            $memberQuery = $memberQuery->whereDate('members.credits_expiration', '<', $today->toDateString());  // all expired

            $memberQuery = $memberQuery->orderby('members.credits_expiration', 'DESC');
        
        }
        else if ($request->toexpire) 
        {

            //get expired members
            /*
            $dateFrom =   Carbon::now();
            $dateTo     = Carbon::now()->addDays(15); //expiring  15 days
            $memberQuery = $memberQuery->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));
            */

            $today =   Carbon::now();
            $memberQuery = $memberQuery->whereDate('members.credits_expiration', '>', $today->toDateString());  // all expired


            //Only Point Balance
            $memberQuery = $memberQuery->where('membership', "Point Balance");

            //agent transaction points.
            //$memberQuery = $memberQuery->leftJoin('agent_transaction', 'members.user_id', '=', 'agent_transaction.member_id');
            //$memberQuery = $memberQuery->where(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));


            $memberQuery = $memberQuery->orderby('members.credits_expiration', 'DESC');
         

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
                
            } else {

                //user did not search, check user if tutor, if user tutor then show only members that is he/she is tutoring... 
                if (strtolower(Auth::user()->user_type) == "tutor") 
                {
                    $memberQuery = $memberQuery->where('tutor_id', Auth::user()->id);
                }                
            } //[END] USER SEARCH

            $memberQuery = $memberQuery->orderby('users.id', 'ASC');
        }
        
        $members = $memberQuery->paginate(Auth::user()->items_per_page);

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
     * (v2)
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($memberID)
    {
        $memberInfo = Member::where('user_id', $memberID)->first();

        $agent = new Agent();   
        $agentInfo = $agent->getAgentInfo($memberInfo->agent_id);

        if (isset($memberInfo)) 
        {
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
                ->where('year', $thisYear)
                ->first();


            $schedules = $scheduleItem->getMemberScheduledLesson($memberID);

            
            $totalReserved = $scheduleItem->getMemberTotalReserved($memberID);


            return view('admin.modules.member.schedulelist', compact('schedules', 'totalReserved', 'member', 'memberInfo', 'agentInfo', 'tutorInfo', 'memberAttribute'));
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
        abort_if(Gate::denies('member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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


    public function resetPassword($id, Request $request)
    {
        abort_if(Gate::denies('member_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $member = Member::where('user_id', $id)->first();

        $userData = [
            'password' => Hash::make($request->password),
        ];

        $user = User::find($member->user_id);
        
        $user->update($userData);

        return redirect()->route('admin.member.edit', $id)->with('message', 'Member password has been updated successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($memberID)
    {

        abort_if(Gate::denies('member_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $memberInfo = Member::where('user_id', $memberID)->first();

        if (!$memberInfo) {
            //member is not found in member table
            abort(404);
        }

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
        

        if (isset($memberInfo->agent_id))
        {
            $agentInfo = Agent::where("user_id", $memberInfo->agent_id)->first();
            $agentArrayInfo = $agentInfo->toArray();
            $agentUserArrayInfo = $agentInfo->user->toArray();      
            $agentInfo = array_merge($agentArrayInfo, $agentUserArrayInfo);
        } else {
            $agentInfo = (object) [];
        }
      
        //$agentInfo = User::where('id', $memberInfo->agent_id)->first();

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


        if ($request->transaction_type !== 'CREDITS_EXPIRATION') {

            $validator = Validator::make($request->all(), [
                'transaction_type' => ['required'],
                'amount' => ['required'],
                'credits' => ['required'],
            ]);

            if ($validator->fails()) {
                //return redirect()->back()->withErrors($validator)->withInput();
                return redirect()->back()->withErrors($validator)->withInput()->with('error_message', 'Transaction amount and credits is required ');
            }

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

   

        //lesson_shift_id 

        if ($request->transaction_type == 'CREDITS_EXPIRATION') 
        {
            //Update expiry member
            $newExpiryDate = $request->expiry_date ." " . date("G:i:s");

            $expiry_date = date('Y-m-d G:i:s', strtotime($newExpiryDate));
            $old_credits_expiration = date('Y-m-d G:i:s', strtotime($member->credits_expiration));

            //agent transaction
            $member->update([
                'credits_expiration' => $expiry_date ,
            ]);
            
            //create Agent Transaction
            $agentCredit = [
                'valid' => 1,
                'transaction_type' => $request->transaction_type,
                'agent_id' => null,
                'member_id' => $member->user_id,
                'lesson_shift_id' => $member->lesson_shift_id,
                'created_by_id' => Auth::user()->id,
                'amount' => null, //amount is zero
                'price' => null, //amount is zero
                'remarks' => $request->remarks,
                'credits_expiration' => $expiry_date,
                'old_credits_expiration' => $old_credits_expiration,
            ];
            AgentTransaction::create($agentCredit);


        } else {

            //generate agent transaction expiration date
            $expiry_date = date('Y-m-d G:i:s', strtotime('+6 months'));
            $old_credits_expiration = date('Y-m-d G:i:s', strtotime($member->credits_expiration));

           
            //check the last member expiration if not greater than expiry date generated.
            if ($old_credits_expiration >= $expiry_date) {
               // $expiry_date = $old_credits_expiration;
            }

            //add member expiration
            $member->update([
                'credits_expiration' => $expiry_date,
            ]);            

            //create Agent Transaction
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
                'old_credits_expiration' => $old_credits_expiration,
            ];
            AgentTransaction::create($agentCredit);
        }

        return redirect()->route('admin.member.account', $id)->with('message', 'Member transaction has been added successfully!');
    }

    public function activate($id) {
        $user = User::find($id);
        $data = ['is_activated'=> true];
        $user->update($data);
        return redirect()->route('admin.member.edit', $id)->with('message', 'Member has been activated successfully!');
    }


    public function deactivate($id) 
    {
        $user = User::find($id);
        $data = ['is_activated'=> false];
        $user->update($data);
        return redirect()->route('admin.member.edit', $id)->with('message', 'Member has been deactivated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');


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
