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
use Illuminate\Support\Facades\Hash;

use Auth;
use Gate;
use Validator;
use Input;

class TutorController extends Controller
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
    public function index(User $user, Tutor $tutor)
    {
        $shifts = Shift::all();
        $grades = Grade::all();

        $tutors = Tutor::join('users', 'users.id', '=', 'tutors.user_id');


        //@[START] USER SEARCH - if user search for a member
        if(isset($member_id) || isset($name) || isset($email)) {        
            if (isset($member_id)) {    
                $tutors = $tutors->where('tutors.id', $member_id);
            }
            if (isset($name)) {     
                $tutors = $tutors->orWhere('tutors.name_en', 'like', '%' .  $name . '%')->orWhere('tutors.name_en', 'like', '%' .  $name . '%'); 
            }

            if (isset($email)) {
                $tutors = $tutors->orWhere('users.email', $email);
           }
        } //[END] USER SEARCH


        $tutors = $tutors->get();
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
                'is_default_main_tutor' => (boolean) $request['is_default_main_tutor'],
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
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tutor $tutor)
    {
        //abort_if(Gate::denies('tutor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shifts = Shift::all();
        $grades = Grade::all();

        return view('admin.modules.tutor.edit', compact('tutor', 'shifts', 'grades'));
    }
    
    public function resetPassword($id, Request $request) 
    {
        $tutor = Tutor::find($id);
        $userData = [
            'password' => Hash::make($request->password)
        ];      
        $user = User::find($tutor->user_id);
        $user->update($userData);

        return redirect()->route('admin.tutor.edit', $id)->with('message', 'Tutor password has been updated successfully!');
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tutor $tutor)
    {
        $validator = Validator::make($request->all(), 
        [
            'email'             => ['required', 'string', 'email', 'max:255', 
                                    Rule::unique('users')->ignore($tutor->user_id)->whereNull('deleted_at')
                                   ],
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
                'username'      => $request['email'],                
                'api_token'      => Hash('sha256', Str::random(80))
            ];

            $user = User::find($tutor->user_id);
            $user->update($userData);

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
                'is_default_main_tutor' => (boolean) $request['is_default_main_tutor'],
                'is_terminated'         => (boolean) $request['is_terminated']                 
            ];              

            $tutor = Tutor::find($tutor->id);
            $tutor->update($tutorData);
            //$user->tutors()->sync([$tutor->id], false);  

            return redirect()->route('admin.tutor.index')->with('message', 'Tutor has been updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tutor $tutor)
    {
        //abort_if(Gate::denies('tutor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::find($tutor->user_id);
        $user->delete();

        //delete tutor if there is still added
        $tutor->delete();

        return back()->with('message', 'Tutor has been deleted successfully!');
    }


    public function massDestroy(Request $request)
    {
        //abort_if(Gate::denies('tutor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Tutor::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

 
}
