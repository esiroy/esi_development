<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Role;
use App\Models\Agent;
use App\Models\Shift;
use App\Models\AgentCredits;
use App\Models\AgentPointPurchaseHistory;
use App\Models\Grade;
use App\Models\Industry;
use App\Models\Manager;
use App\Models\Permission;

use Validator;
use Auth;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Agent $agents)
    {	
        //$agents = User::whereHas('roles', function($q) { $q->where('title', 'Agent'); })->get();
        $agents = Agent::get();

		$industries = Industry::all();
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
			'industry_type'		=> ['required'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'password'          => ['required', 'string', 'min:8'],            
            'name_en'           => ['required'],
            'name_jp'           => ['required'],
            'id'          		=> ['required',  Rule::unique('agents')],
            'representative'    => ['required'],            
            'contract_date'     => ['required']
        ]);

        if ($validator->fails()) {

            return redirect()->route('admin.agent.index')->withErrors($validator)->withInput();       

        } else {

            $userData =
            [
				'id'            => $request['agent_id'],
                'first_name'    => '',
                'last_name'     => '',				
                'email'         => $request['email'],
                'username'      => $request['email'],
				'password'      => $request['password'],
				'api_token'      => Hash('sha256', Str::random(80))
            ];
            $user = User::create($userData); 

			//Add Role
			$roles[] = Role::where('title', 'Agent')->first()->id;
			$user->roles()->sync($roles); 

			//Create Agent Information
            $agentData = [           
                'industry_type_id'		=> $request['industry_type'],
                'user_id'               => $user->id,                              
                'name_en'               => $request['name_en'],
                'name_jp'               => $request['name_jp'],
                'representative'        => $request['representative'],
                'hiragana'              => $request['hiragana'],
                'address'               => $request['address'],
                'inclination'           => $request['inclination'],
                'contract_date'         => $request['contract_date'],
                'agent_remark'          => $request['agent_remark'],             
            ];              

        
            $agent = Agent::create($agentData);
			$user->agents()->sync([$agent->id], false);
            return redirect()->route('admin.agent.index')->with('message', 'Agent has been added successfully!');
        }
    }


    public function account($agentID) 
    {
        $agent         = Agent::find($agentID);
        if (!isset($agent)) {
            abort(404);
        }
        $user           = $agent->user;
        //Member Transactions
        $transactions  = AgentCredits::where('agent_id', $agentID)->get();

        $purchaseHistory    = AgentPointPurchaseHistory::join('users', 'users.id', '=', 'agent_point_purchase_history.user_id')
                            ->where('user_id', $agent->user_id)->get();
                            
       return view('admin.modules.agent.account', compact('agent', 'transactions', 'purchaseHistory'));
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
        $agent = Agent::find($id);

        if (!isset($agent)) {
            abort(404);
        }
       
        $agentUserId = $agent->user_id;

        if ($request->transaction_type == 'AGENT_SUBTRACT') 
        {            
            $newCredit      = -abs($request->credits);
            $totalCredits    = $agent->credits - $request->credits;
            $purchasedAmount = $agent->purchased_amount - $request->amount;
        } else {
            $newCredit      = $request->credits;
            $totalCredits   = $agent->credits + $newCredit;
            $purchasedAmount = $agent->purchased_amount + $request->amount;            
        }

        $agent->update([
            'initial_date_of_purchase' =>  date('Y-m-d G:i:s'),
            'purchased_amount'      =>  $purchasedAmount,
            'credits'               => $totalCredits,
            'credits_expiration'    => date('Y-m-d G:i:s', strtotime('+6 months')), 
            'latest_purchase_date'  => date('Y-m-d G:i:s')
        ]);
       
        if (isset($request->expiry_date)) {
            $expiry_date = date('Y-m-d', strtotime($request->expiry_date));
        }
        
        //Update Agent
        $agentCredit = [
            'transaction_type'                  => $request->transaction_type,
            'agent_id'                          => $agent->id,
            'transaction_user_id'               => Auth::user()->id,
            'amount'                            => $request->amount,
            'credits'                           => $newCredit,
            'remarks'                           => $request->remarks,
            'original_credit_expiration_date'   => $expiry_date
        ];        
        AgentCredits::create($agentCredit);
        //Insert into AgentPointPurchaseHistory
        $pointHistory = [
            'user_id'                           => $agentUserId,
            'transaction_user_id'               => Auth::user()->id,
            'amount'                            => $request->amount,
            'credits'                           => $newCredit,	
            //'lesson_time_duration'              => ""
        ];
        AgentPointPurchaseHistory::create($pointHistory);


        return redirect()->route('admin.agent.account', $agent)->with('message', 'Agent transaction has been added successfully!');
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
