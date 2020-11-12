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
use App\Models\Tutor;
use App\Models\Grade;
use App\Models\Shift;
use App\Models\Permission;
use Validator;
use Auth;

class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user, Tutor $tutor)
    {
        $tutors = User::whereHas('roles', function($q) { $q->where('title', 'tutor'); })->get();         
        $shifts = Shift::all();
        $grades = Grade::all();
        return view('admin.modules.tutor.index', compact('shifts', 'tutors', 'grades'));
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
        //abort_if(Gate::denies('tutor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


  
        $validator = Validator::make($request->all(), [
            //'first_name' => ['required', 'string', 'max:255'],
            //'last_name' => ['required', 'string', 'max:255'],
            //'username'      => ['required', 'string', 'max:16', Rule::unique('users')->whereNull('deleted_at')],
            //'password'      => ['required', 'string', 'min:8', 'confirmed'],
            //'birthdate'      => ['required'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'password'          => ['required', 'string', 'min:8'],            
            'sort'              => ['required', 'integer'],
            'salary_rate'       => ['integer'],
            'grade'             => ['required'],
            'skype_name'        => ['required'],
            'skype_id'          => ['required'],
            'name_en'           => ['required'],
            'name_jp'           => ['required'],
            'gender'            => ['required'],
            'japanese_fluency'  => ['required'],
            'shift'             => ['required']
        ]);


        if ($validator->fails()) {

            return redirect()->route('admin.tutor.index')->withErrors($validator)->withInput();       

        } else {

            $userData =
            [
                'email'         => $request['email'],
                'first_name'    => '',
                'last_name'     => '',
                'username'      => $request['email'],
                'password'      => $request['password'],
                'api_token'      => Hash('sha256', Str::random(80))
            ];
            $user = User::create($userData);          

            //Add Role
            $roles[] = Role::where('title', 'Tutor')->first()->id;
            $user->roles()->sync($roles); 
             
            $tutorData = [
                'sort'                  => $request['sort'],
                'user_id'               => $user->id,
                'salary_rate'           => $request['salary_rate'],
                'member_grade_id'       => $request['grade'],
                'skype_id'              => $request['skype_id'],
                'skype_name'            => $request['skype_name'],                
                'name_en'               => $request['name_en'],
                'name_jp'               => $request['name_jp'],
                'gender'                => $request['gender'], 
                'hobby'                 => $request['hobby'],
                'birthdate'             => $request['birthdate'],
                'major_in'              => $request['major_in'],
                'introduction'          => $request['introduction'],
                'japanese_fluency_id'   => $request['japanese_fluency'],
                'shift_id'              => $request['shift'],
                'is_default_main_tutor' => (boolean) $request['default_main_tutor'],
                'is_terminated'         => (boolean) $request['is_terminated']                 
            ];              

            $tutor = Tutor::create($tutorData);
            $user->tutors()->sync([$tutor->id], false);  

            return redirect()->route('admin.tutor.index')->with('message', 'Tutor has been added successfully!');
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
