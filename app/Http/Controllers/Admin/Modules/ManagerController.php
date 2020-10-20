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
use App\Models\Manager;
use App\Models\Grade;
use App\Models\Permission;
use Validator;
use Auth;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $managers = User::whereHas('roles', function($q) { $q->where('title', 'Manager'); })->get(); 
        return view('admin.modules.manager.index', compact( 'managers'));
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
                'email'         => $request['email'],
                'first_name'    => '',
                'last_name'     => '',
                'username'      => $request['email'],
                'password'      => $request['password'],
                'api_token'     => Hash('sha256', Str::random(80))                
            ];

            $user = User::create($userData); 
            
            //Add Role
            $roles[] = Role::where('title', 'Manager')->first()->id;
            $user->roles()->sync($roles);             

            //Add Manager Info
            $managerData = [           
                'user_id'               => $user->id,                           
                'name_en'               => $request['name_en'],
                'name_jp'               => $request['name_jp'],
                'is_japanese'           => (boolean) $request['is_japanese']
            ];
            
            $manager = Manager::create($managerData);
            $user->managers()->sync([$manager->id], false);      
            
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
