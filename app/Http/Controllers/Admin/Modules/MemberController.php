<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Member;
use App\Models\Lesson;
use App\Models\MemberCredits;
use App\Models\Attribute;
use App\Models\Membership;
use App\Models\Shift;
use App\Models\MemberPointPurchaseHistory;
use App\Models\Agent;
use App\Models\Tutor;

use DB, Auth, Validator;

class MemberController extends Controller
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

        //@todo: search


        //@todo:attributes
        $attributes = Attribute::all();
        $memberships = Membership::all();        
        $shifts = Shift::all();

        /*
        $members = User::select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))
                   ->whereHas('roles', function($q) { $q->where('title', 'Member'); })->get();   
        */
        $members = Member::join('users', 'users.id', '=', 'members.user_id')
                    ->leftJoin('attributes', 'attributes.id', '=', 'members.member_attribute_id')
                    ->leftJoin('agents', 'agents.id', '=', 'members.agent_id')
                    ->leftJoin('tutors', 'tutors.id', '=', 'members.main_tutor_id')
                    ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name, 
                                            attributes.name as attribute, 
                                            members.id as id,
                                            tutors.name_en as main_tutor_name
                                        "))                    
                    ->get();

        
        $tutorQuery = User::whereHas('roles', function($q) { $q->where('title', 'Tutor'); })->get();         
        $tutors = json_encode($tutorQuery);

        return view('admin.modules.member.index', compact('memberships', 'shifts', 'attributes', 'tutors', 'members'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */  
    public function account($memberID)
    {
        $member         = Member::find($memberID);
        if (!isset($member)) {
            abort(404);
        }

        $user           = $member->user;
        $agent          = $member->agent;        

        //Member Transactions
        $transactions  = MemberCredits::join('users', 'users.id', '=', 'member_credits.user_id')
                        ->where('user_id', $member->user_id)->get();

        $purchaseHistory    = MemberPointPurchaseHistory::join('users', 'users.id', '=', 'member_point_purchase_history.user_id')
                            ->where('user_id', $member->user_id)->get();

       return view('admin.modules.member.account', compact('member', 'transactions', 'purchaseHistory'));
    }
    
    public function schedulelist($memberID, Lesson $lesson) 
    {
        $memberInfo         = Member::find($memberID);

        if ($memberInfo) {
            $member             = $memberInfo->user;        
            //agent     
            $agentInfo          = Agent::find($memberInfo->agent_id);
            $tutorInfo          = Tutor::find($memberInfo->main_tutor_id);
    
            if ($agentInfo) {
                $agent = $agentInfo->user;
            } else {        
                $agent = null;
            }
    
            $schedules  = $lesson->getMemberScheduledLesson($memberID);
            
            return view('admin.modules.member.schedulelist', compact('schedules', 'member', 'memberInfo', 'agent', 'agentInfo', 'tutorInfo'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
			'transaction_type'  => ['required'],
            'amount'            => ['required'],
            'credits'           => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();       
        }      

        //variables
        $expiry_date = null;

        //AGENT_SUBTRACT
        //DISTRIBUTE, CREDITS_EXPIRATION, MANUAL_ADD, FREE_CREDITS 
        $member = Member::find($id);

        if (!isset($member)) {
            abort(404);
        }
       
        $memberUserId = $member->user_id;

        if ($request->transaction_type == 'AGENT_SUBTRACT') 
        {            
            $newCredit      = -abs($request->credits);
            $totalCredits    = $member->credits - $request->credits;
        } else {
            $newCredit      = $request->credits;
            $totalCredits   = $member->credits + $newCredit;
        }

        $member->update([
            'credits'               => $totalCredits,
            'credits_expiration'    => date('Y-m-d G:i:s', strtotime('+6 months')), 
            'latest_purchase_date'  => date('Y-m-d G:i:s')            
        ]);
       
        if (isset($request->expiry_date)) {
            $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
        }
        
        //Update Member
        $MemberCredit = [
            'transaction_type'                  => $request->transaction_type,
            'user_id'                           => $memberUserId,
            'transaction_user_id'               => Auth::user()->id,
            'amount'                            => $request->amount,
            'credits'                           => $newCredit,
            'remarks'                           => $request->remarks,
            'original_credit_expiration_date'   => $expiry_date
        ];        
        MemberCredits::create($MemberCredit);

        //@Get time duration of member        
        $duration = Shift::find($member->lesson_time_id)->value;


        //Insert into MemberPointPurchaseHistory
        $pointHistory = [
            'user_id'                           => $memberUserId,
            'transaction_user_id'               => Auth::user()->id,
            'amount'                            => $request->amount,
            'credits'                           => $newCredit,	
            'lesson_time_duration'              => $duration
        ];
        MemberPointPurchaseHistory::create($pointHistory);


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
        //
    }
}
