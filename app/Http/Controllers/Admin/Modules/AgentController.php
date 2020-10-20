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
use App\Models\Grade;
use App\Models\Industry;

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
		
        $agents = User::whereHas('roles', function($q) { $q->where('title', 'Agent'); })->get();         

     

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
            'id'          		=> ['required',  Rule::unique('users')],
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
        //
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
