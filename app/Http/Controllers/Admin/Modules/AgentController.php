<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentTransaction;
use App\Models\Role;
use App\Models\Tutor;
use App\Models\UserImage;


//use App\Models\AgentCredits;
//use App\Models\AgentPointPurchaseHistory;

use App\Models\User;
use App\Models\Member;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Hash;
use Validator, Gate;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Agent $agents, Request $request)
    {

        abort_if(Gate::denies('agent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        //$agents = User::whereHas('roles', function($q) { $q->where('title', 'Agent'); })->get();
        //Industry::all();
        $industries = createIndustries();

        //request variables
        $username = $request->username;
        $name = $request->name;
        $email = $request->email;

        $agentQuery = Agent::join('users', 'users.id', '=', 'agents.user_id')
            ->select('agents.*', 'users.username', 'users.firstname', 'users.lastname');

        //@[START] USER SEARCH - if user search for a member
        if (isset($username) || isset($name) || isset($email)) {

            if (isset($username)) {
                $agentQuery = $agentQuery->where('users.username', $username);
            }
            if (isset($name)) {
                $agentQuery = $agentQuery->orWhere('users.firstname', 'like', '%' . $name . '%')->orWhere('users.lastname', 'like', '%' . $name . '%');
            }

            if (isset($email)) {
                $agentQuery = $agentQuery->orWhere('users.email', $email);
            }
        }

        $agents = $agentQuery->orderby('users.firstname', 'ASC')->paginate(Auth::user()->items_per_page);



        return view('admin.modules.agent.index', compact('agents', 'industries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function memberlist($memberID) 
    {
        $members = Member::join('users', 'users.id', '=', 'members.user_id')
            ->where('agent_id', $memberID)
            ->select('users.valid', 'users.firstname', 'users.lastname', 'members.*')
            ->where('valid', 1)->paginate(Auth::user()->items_per_page);

        if (isset($members)) {
            return view('admin.modules.agent.memberlist', compact('members'));
        } else {
            abort(404);
        }

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Agent Form
        $validator = Validator::make($request->all(), [
            'industry_type' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8'],
            'name_en' => ['required'],
            'name_jp' => ['required'],
            'id' => ['required', Rule::unique('agents')],
            'representative' => ['required'],
            'contract_date' => ['required'],
        ]);

        if ($validator->fails()) {

            return redirect()->route('admin.agent.index')->withErrors($validator)->withInput()->with('error_message')->with("Message", 'Error found in form, please check and try again');

        } else {

            $userData =
                [
                'valid' => 1,
                'user_type' => "AGENT",
                'id' => $request['agent_id'],
                'firstname' => $request['name_en'],
                'lastname' => $request['name_jp'],
                'email' => $request['email'],
                'username' => $request['email'],
                'password' => $request['password'],
                'api_token' => Hash('sha256', Str::random(80)),
            ];
            $user = User::create($userData);

            //Add Role
            $roles[] = Role::where('title', 'Agent')->first()->id;
            $user->roles()->sync($roles);

            //Create Agent Information
            $agentData = [
                'industry_type' => $request['industry_type'],
                'user_id' => $user->id,
                'agent_id' => $request['id'],
                'representative' => $request['representative'],
                'hiragana' => $request['hiragana'],
                'address' => $request['address'],
                'inclination' => $request['inclination'],
                'contract_date' => $request['contract_date'],
                'remark' => $request['agent_remark'],
            ];

            $agent = Agent::create($agentData);

            $user->agents()->sync([$agent->id], false);
            return redirect()->route('admin.agent.index')->with('message', 'Agent has been added successfully!');
        }
    }

    /**
     * Display a listing of the payment history.
     * @param $id
     */
    public function paymenthistory($agentID)
    {
        $agentInfo = Agent::where('user_id', $agentID)->first();

        if ($agentInfo) {

            $member = $agentInfo->user;

            //agent
            $agentInfo = Agent::where('user_id', $agentInfo->agent_id)->first();

            //tutor for
            if (isset($agentInfo->tutor_id)) {
                $tutorInfo = Tutor::where('user_id', $agentInfo->tutor_id)->first();
            } else {
                $tutorInfo = null;
            }

            $agentTransaction = new AgentTransaction();
            $paymentHistory = $agentTransaction->getAgentPaymentHistory($agentID);

            return view('admin.modules.agent.agentpaymenthistory', compact('member',  'agentInfo', 'tutorInfo', 'paymentHistory'));

        } else {
            abort(404);
        }
    }

    public function account($agentID)
    {

        
        $agent = Agent::where('user_id', $agentID)->first();

       


        if (!isset($agent)) {
            //abort(404);
            echo "agent not found";
        } else {

            $agentTransaction = new AgentTransaction();
            $credits = $agentTransaction->getAgentCredits($agentID);
            $latestDateOfPurchase = $agentTransaction->getAgentLatestDateOfPurchase($agentID);    

            //list
            $transactions  = $agentTransaction->getAgentTransactions($agentID);
            $purchaseHistory = $agentTransaction->getAgentAllPaymentHistory($agentID);           

            return view('admin.modules.agent.account', compact('agent', 'credits', 'latestDateOfPurchase', 'transactions', 'purchaseHistory'));

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agent = Agent::where('user_id', $id)->first();

        //get photo
        $userImageObj = new UserImage();
        $userImage = $userImageObj->getMemberPhoto($agent);        

        $industries = createIndustries();

        if (isset($agent->id)) {
            return view('admin.modules.agent.edit', compact('agent', 'industries', 'userImage'));
        } else {
            abort(404);
        }
        
    }




    public function resetPassword($id, Request $request)
    {
        //abort_if(Gate::denies('agent_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $tutor = Agent::where('user_id', $id)->first();

        $userData = [
            'password' => Hash::make($request->password),
        ];

        $user = User::find($tutor->user_id);
        $user->update($userData);

        
        return redirect()->route('admin.agent.edit', $id)->with('message', 'Tutor password has been updated successfully!');
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
        $agent = Agent::where('user_id', $id)->first();

        if (!isset($agent)) {
            abort(404);
        }

        $agentUserId = $agent->user_id;

        /*
        if ($request->transaction_type == 'AGENT_SUBTRACT') 
        {
            $newCredit = -abs($request->credits);            
            $totalCredits = $agent->credits - $request->credits;
            $purchasedAmount = $agent->purchased_amount - $request->amount;            
        } else {
            $newCredit = $request->credits;
            $totalCredits = $agent->credits + $newCredit;
            $purchasedAmount = $agent->purchased_amount + $request->amount;            
        }
        */

        //update agent expiration to 6 months
        $agent->update([
            'credits_expiration' => date('Y-m-d G:i:s', strtotime('+6 months'))
        ]);

        //add the expiration to transaction or empty add 6 months
        if (isset($request->expiry_date)) {
            $expiry_date = date('Y-m-d G:i:s', strtotime($request->expiry_date));
        } else {
            $expiry_date =  date('Y-m-d G:i:s', strtotime('+6 months'));        
        }

        //Update Agent Transaction Table
        $agentCredit = [
            'valid'         => 1,
            'transaction_type' => $request->transaction_type,
            'agent_id' => $agent->user_id,
            'created_by_id' => Auth::user()->id,
            'amount' => $request->credits,            
            'price' => $request->amount,
            'remarks' => $request->remarks,
            'credits_expiration' => $expiry_date,
        ];
        AgentTransaction::create($agentCredit);

        return redirect()->route('admin.agent.account', $agent->user_id)->with('message', 'Agent transaction has been added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (Auth::user()->user_type == "ADMINISTRATOR") 
        {       
            $user   = User::find($id);
            $agent  = Agent::where('user_id', $id)->first();
            
            $agent->delete();
            $user->forceDelete();

            return back()->with('message', 'Agent has been deleted successfully!');

        } else {

            return redirect()->back()->with('error_message', 'Delete is not allowed for your user type, please contact the administrator.');
        }        
    }

    /*
    public function massDestroy(Request $request)
    {
        //abort_if(Gate::denies('tutor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Tutor::whereIn('user_id', request('ids'))->delete();
        User::whereIn('user_id', request('ids'))->forceDelete();

        return response(null, Response::HTTP_NO_CONTENT);
    }*/



}
