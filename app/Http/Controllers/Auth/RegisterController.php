<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Role;
use App\Models\Member;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $is_netenglish = config('app.netenglish');

        if ($is_netenglish == true) {
            echo "not allowed to register";
            exit();
        }
        
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:16', Rule::unique('users')->whereNull('deleted_at')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        
        //note: this is now transferred to Signup Controller
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'first_name_jp'  => $data['first_name_jp'],
            'last_name_jp'  => $data['last_name_jp'],
            'email' => $data['email'],
            'api_token' => Hash('sha256', Str::random(80)),
            'password' => Hash::make($data['password']),
        ]);

        $roles[] = Role::where('title', 'Member')->first()->id;
        $user->roles()->sync($roles);

        //note: this is now transferred to Signup Controller
        $memberInformation =
        [
            'user_id'                   =>  $user->id,
            'member_attribute_id'       =>  null,
            'nickname'                  =>  $data['username'],
            'agent_id'                  =>  null,
            'gender'                    =>  null,
            'birthdate'                 =>  null, //date('Y-m-d', strtotime($data->birthday)),
            'age'                       =>  null,
            'communication_app_name'    =>  $data['commApp'],
            'communication_app_username' => $data['communication_app_username'],
            'membership_id'             =>  1,                    
            'exam_record_id'            =>  1, //@todo: remove exam or nullify
            'member_since'              => date('Y-m-d'),
            'lesson_time_id'            => 1,
            'main_tutor_id'             => null,
            'agent_report_card'         => false,
            'agent_monthly_report'      => false,
            'member_report_card'        => false,
            'member_monthly_report'     => false,    
            'point_purchase'            =>  null,
            
        ];
        $member = Member::create($memberInformation);
        $user->members()->sync([$member->id], false);   
        return $user;
    }
}
