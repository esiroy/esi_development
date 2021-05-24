<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Role;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

use Gate, Auth, Validator, DB;

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
    public function index(User $user, Tutor $tutor, Request $request)
    {

        abort_if(Gate::denies('tutor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //request variables
        $tutor_id = $request->tutor_id;
        $name = $request->name;
        $email = $request->email;

        $shifts = Shift::all();
        //$grades = Grade::all();

        $grades = createGrades();

        $tutors = Tutor::join('users', 'users.id', '=', 'tutors.user_id')->select('tutors.*', 'users.firstname', 'users.lastname', 'users.email', 'users.valid');

        //@[START] USER SEARCH - if user search for a member
        if (isset($tutor_id) || isset($name) || isset($email)) {
            if (isset($tutor_id)) {
                $tutors = $tutors->where('tutors.user_id', $tutor_id);
            }
            if (isset($name)) {
                //$tutors = $tutors->orWhere('tutors.name_en', 'like', '%' . $name . '%')->orWhere('tutors.name_en', 'like', '%' . $name . '%');

                $tutors = $tutors->orWhereRaw("CONCAT(users.firstname,' ',users.lastname) like '%" . $name . "%'")
                                 ->orWhereRaw("CONCAT(users.lastname,' ',users.firstname) like '%" . $name . "%'");                


                $tutors = $tutors->orWhereRaw("users.firstname like '%" . $name . "%'")
                                 ->orWhereRaw("users.japanese_firstname like '%" . $name . "%'");              

            }

            if (isset($email)) {
                $tutors = $tutors->orWhere('users.email', $email);
            }
        } //[END] USER SEARCH

        $tutors = $tutors->paginate(Auth::user()->items_per_page);


        return view('admin.modules.tutor.index', compact('shifts', 'tutors', 'grades'));
    }

    public function maintutor($id)
    {
        $members = Member::join('users', 'users.id', '=', 'members.user_id')
            ->join('tutors', 'tutors.user_id', 'members.tutor_id')
            ->select('users.firstname', 'users.lastname', 'users.valid', 'members.*', 'tutors.is_default_main_tutor')
            ->where('members.tutor_id', $id)
            ->where('users.valid', 1)
        //->where('tutors.is_default_main_tutor', 1)
            ->paginate(Auth::user()->items_per_page);

        return view('admin.modules.tutor.maintutor', compact('members'));
    }

    public function supporttutor($id)
    {
        $members = Member::join('users', 'users.id', '=', 'members.user_id')
            ->join('tutors', 'tutors.user_id', 'members.tutor_id')
            ->select('users.firstname', 'users.lastname', 'users.valid', 'members.*', 'tutors.is_default_support_tutor')
            ->where('members.tutor_id', $id)
            ->where('users.valid', 1)
            ->where('tutors.is_default_support_tutor', 1)
            ->paginate(Auth::user()->items_per_page);

        return view('admin.modules.tutor.supporttutor', compact('members'));
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
            //'birthday'      => ['required'],

            'name_en' => ['required', 'string', 'max:255'],
            'name_jp' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8'],
            'sort' => ['required', 'integer'],
            'salary_rate' => ['integer'],
            'grade' => ['required'],
            'skype_name' => ['required'],
            'skype_id' => ['required'],
            'gender' => ['required'],
            'japanese_fluency' => ['required'],
            'shift' => ['required'],
        ]);

        if ($validator->fails()) {

            return redirect()->route('admin.tutor.index')->with('error_message', 'Error in adding tutor, please check the form below!')->withErrors($validator)->withInput();

        } else {

            try
            {
                DB::beginTransaction();
                                
                $userData =
                    [
                    'firstname' => $request['name_en'],
                    'lastname' => '',
                    'japanese_firstname' => $request['name_jp'],
                    'japanese_lastname' => '',
                    'email' => $request['email'],
                    'username' => $request['email'],
                    'password' => Hash::make($request['password']),
                    'api_token' => Hash('sha256', Str::random(80)),
                    'user_type' => "TUTOR",
                    'valid' => ! ((boolean) $request['is_terminated']),
                ];
                $user = User::create($userData);

                //Add Role
                $roles[] = Role::where('title', 'Tutor')->first()->id;
                $user->roles()->sync($roles);

                //check if main tutor?
                $isMainTutor = (boolean) $request['is_default_main_tutor'];

                if ($isMainTutor === true) {
                    $mainTutor = true;
                    $supportTutor = false;
                } else {
                    $mainTutor = false;
                    $supportTutor = true;
                }

                $tutorData = [
                    'sort' => $request['sort'],
                    'user_id' => $user->id,
                    'salary_rate' => $request['salary_rate'],
                    'grade' => $request['grade'],
                    'skype_id' => $request['skype_id'],
                    'skype_name' => $request['skype_name'],
                    'skype_password' => '',
                    'gender' => $request['gender'],
                    'hobby' => $request['hobby'],
                    'birthday' => date('Y-m-d', strtotime($request['birthdate'])),
                    'major' => $request['major_in'],
                    'introduction' => $request['introduction'],
                    'fluency' => $request['japanese_fluency'],
                    'lesson_shift_id' => $request['shift'],
                    'is_default_main_tutor' => $mainTutor,
                    'is_default_support_tutor' => $supportTutor,
                    'is_terminated' => (boolean) $request['is_terminated'],
                ];

                $tutor = Tutor::create($tutorData);
                $user->tutors()->sync([$tutor->id], false);

                DB::commit();

                return redirect()->route('admin.tutor.index')->with('message', 'Tutor has been added successfully!');

            } catch (\Exception $e) {
                
                DB::rollback();

                return redirect()->route('admin.tutor.index')->with('message', 'Error, Tutor was not updated due to an error please check back later. <p> ' . $e->getMessage() . " on Line " . $e->getLine());

            }
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
    public function edit($id, Request $request)
    {
        abort_if(Gate::denies('tutor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tutor = Tutor::join('users', 'users.id', '=', 'tutors.user_id')->where('user_id', $id)->first();

        //get photo
        $userImageObj = new UserImage();
        $userImage = $userImageObj->getMemberPhoto($tutor);

        if (isset($tutor)) {
            $shifts = Shift::all();
            $grades = createGrades();

            return view('admin.modules.tutor.edit', compact('tutor', 'userImage', 'shifts', 'grades'));
        }
    }

    public function resetPassword($id, Request $request)
    {
        abort_if(Gate::denies('tutor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $tutor = Tutor::where('user_id', $id)->first();

        $userData = [
            'password' => Hash::make($request->password),
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
    public function update($id, Request $request)
    {
        $tutor = Tutor::where('user_id', $id)->first();

        $validator = Validator::make($request->all(),
            [
                'email' => ['required', 'string', 'email', 'max:255',
                    Rule::unique('users')->ignore($tutor->user_id)->whereNull('deleted_at'),
                ],
                'sort' => ['required', 'integer'],
                'salary_rate' => ['integer'],
                'grade' => ['required'],
                'skype_name' => ['required'],
                'skype_id' => ['required'],
                'name_en' => ['required'],
                'name_jp' => ['required'],
                'gender' => ['required'],
                'japanese_fluency' => ['required'],
                'shift' => ['required'],
            ]);

        if ($validator->fails()) {
            return redirect()->route('admin.tutor.index')->withErrors($validator)->withInput();
        } else {
            
            try
            {
                DB::beginTransaction();

                $userData =
                    [
                    'firstname' => $request['name_en'],
                    //'lastname' => '',
                    'japanese_firstname' => $request['name_jp'],
                    //'japanese_lastname' => '',
                    'email' => $request['email'],
                    'username' => $request['email'],
                    //'password' => $request['password'],
                    'api_token' => Hash('sha256', Str::random(80)),
                    //'user_type' => "TUTOR",
                    'valid' => ! ((boolean) $request['is_terminated']),
                ];
                $user = User::find($id);
                $user->update($userData);

                //Add Role
                $roles[] = Role::where('title', 'Tutor')->first()->id;
                $user->roles()->sync($roles);

                //check if main tutor?
                $isMainTutor = (boolean) $request['is_default_main_tutor'];

                if ($isMainTutor === true) {
                    $mainTutor = true;
                    $supportTutor = false;
                } else {
                    $mainTutor = false;
                    $supportTutor = true;
                }

                $tutorData = [
                    'sort' => $request['sort'],
                    'user_id' => $user->id,
                    'salary_rate' => $request['salary_rate'],
                    'grade' => $request['grade'],
                    'skype_id' => $request['skype_id'],
                    'skype_name' => $request['skype_name'],
                    'skype_password' => '',
                    'gender' => $request['gender'],
                    'hobby' => $request['hobby'],
                    'birthday' => date('Y-m-d', strtotime($request['birthdate'])),
                    'major' => $request['major_in'],
                    'introduction' => $request['introduction'],
                    'fluency' => $request['japanese_fluency'],
                    'lesson_shift_id' => $request['shift'],
                    'is_default_main_tutor' => $mainTutor,
                    'is_default_support_tutor' => $supportTutor,
                    'is_terminated' => (boolean) $request['is_terminated'],
                ];

                $tutor = Tutor::where("user_id", $id)->first();
                $tutor->update($tutorData);
                //$user->tutors()->sync([$tutor->id], false);
                DB::commit();

                return redirect()->route('admin.tutor.index')->with('message', 'Tutor has been updated successfully!');

            } catch (\Exception $e) {

                DB::rollback();

                return redirect()->route('admin.tutor.index')->with('error_message', 'Error, Tutor was not updated due to an error please check back later. <p> ' . $e->getMessage() . " on Line " . $e->getLine());
            }                
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
        abort_if(Gate::denies('tutor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user   = User::find($id);
        $tutor  = Tutor::where('user_id', $id)->first();

        //delete tutor if there is still added
        $tutor->delete();
        $user->forceDelete();

        return back()->with('message', 'Tutor has been deleted successfully!');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('tutor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Tutor::whereIn('user_id', request('ids'))->delete();
        User::whereIn('user_id', request('ids'))->forceDelete();


        return response(null, Response::HTTP_NO_CONTENT);
    }

}
