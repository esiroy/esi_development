<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;
use App\Models\Manager;
use App\Models\Grade;
use App\Models\Permission;
use App\Models\Tutor;
use App\Models\Member;

use Auth;
use Gate;
use Validator;
use Input;


class ManagerController extends Controller
{
    /**
     * Display a listing of  managers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('manager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');        

        //request variables
        $username = $request->username;
        $name = $request->name;
        $email = $request->email;

        $managerQuery = new User(); 
        
        
        //@[START] USER SEARCH - if user search for a member
        if (isset($username) || isset($name) || isset($email)) {

            if (isset($username)) {
                $managerQuery = $managerQuery->where('username', $username);
            }
            if (isset($name)) {                
                $managerQuery = $managerQuery->orWhere('firstname', 'like', '%' . $name . '%')->orWhere('users.lastname', 'like', '%' . $name . '%');
            }

            if (isset($email)) {
                $managerQuery = $managerQuery->orWhere('email', $email);
            }
        }

        $managers = $managerQuery->where('user_type', 'MANAGER')->paginate(Auth::user()->items_per_page);
        return view('admin.modules.manager.index', compact('managers'));
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
        $validator = Validator::make($request->all(), [
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'password'      => ['required', 'string', 'min:8'],
            'name_en'       => ['required'],
            'name_jp'       => ['required']
        ]);

        if ($validator->fails()) {

            return redirect()->route('admin.manager.index')->withErrors($validator)->withInput();       

        } else {

            //Add User
            $userData =
            [
                'user_type'             => "MANAGER",
                'email'                 => $request['email'],
                'username'              => $request['email'],
                'firstname'             => $request['name_en'],
                'japanese_firstname'    => $request['name_jp'],  
                'password'              => Hash::make($request['password']),
                'api_token'             => Hash('sha256', Str::random(80)),
                'valid'                 => 1          
            ];

            $user = User::create($userData); 
            
            //Add Role
            $roles[] = Role::where('title', 'Manager')->first()->id;
            $user->roles()->sync($roles);             

            /*
            //Add Manager Info
            $managerData = [           
                'user_id'               => $user->id,                           
                'name_en'               => $request['name_en'],
                'name_jp'               => $request['name_jp'],
                'is_japanese'           => (boolean) $request['is_japanese']
            ];
            
            $manager = Manager::create($managerData);
            $user->managers()->sync([$manager->id], false);
            */
            
            return redirect()->route('admin.manager.index')->with('message', 'Manager has been added successfully!');

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
     * @param  int  $manager
     * @return \Illuminate\Http\Response
     */    
    public function resetPassword($id, Request $request)
    {
        abort_if(Gate::denies('manager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userData = [
            'password' => Hash::make($request->password),
        ];

        $user = User::find($id);
        $user->update($userData);

        return redirect()->route('admin.manager.edit', $id)->with('message', 'Manager password has been updated successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  object  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        abort_if(Gate::denies('manager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manager = User::find($id);
        
        return view('admin.modules.manager.edit', compact('manager'));        
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

        $manager = User::find($id);

        $validator = Validator::make($request->all(), [
            'email'             => ['required', 'string', 'email', 'max:255', 
                                    Rule::unique('users')->ignore($manager->id)->whereNull('deleted_at')
                                   ],            
            //'password'      => ['required', 'string', 'min:8'],
            'name_en'       => ['required'],
            'name_jp'       => ['required']
        ]);

        if ($validator->fails()) {

            return redirect()->route('admin.manager.edit', $manager->id)->withErrors($validator)->withInput();       

        } else {

            //Update User
            $userData =
            [
                'email'                 => $request['email'],
                'username'              => $request['email'],
                'firstname'             => $request['name_en'],
                'japanese_firstname'    => $request['name_jp'],  
                //'password'              => Hash::make($request['password']),
                'is_japanese'           => (boolean) $request['is_japanese'],
                'api_token'             => Hash('sha256', Str::random(80)),
                'valid'                 => 1         
            ];

            $user = User::find($manager->id);
            $user->update($userData);        
            
            return redirect()->route('admin.manager.index')->with('message', 'Manager has been updated successfully!');

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        abort_if(Gate::denies('manager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $member = Member::where('user_id', $id)->first();
        $member->forceDelete();

        $user   = User::find($id);
        $user->forceDelete();

        return back()->with('message', 'Manager has been deleted successfully!');
    }
}
