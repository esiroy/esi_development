<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Member;
use App\Models\Role;
use App\Models\Tutor;
use App\Models\Grade;
use App\Models\Shift;
use App\Models\Permission;
use Gate;
use Validator;
use Input;
use Auth;
use DB;
use Session;


class SignUpController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    
    public function showSignUpForm() {
        return view('auth.signup');
    }


    public function validateSignUpForm(Request $request) 
    {
        $validator = Validator::make(
            [
                'first_name'                     => $request->first_name,
                'last_name'                     => $request->last_name,
                'first_name_jp'                 => $request->first_name_jp,
                'last_name_jp'                  => $request->last_name_jp,
                'email'                         => $request->email,

                'password'                      => $request->password,              
                'confirm_password'              => $request->confirm_password,
                'communication_app_username'    => $request->communication_app_username
            ], 
            [
                'first_name'        => ['required', 'max:255'],
                'last_name'         => ['required', 'max:255'],
                'first_name_jp'     => ['required', 'max:255'],
                'last_name_jp'      => ['required', 'max:255'],
                'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],

                'password'          => 'required|min:8|same:confirm_password',
                'confirm_password'  => 'required',

                'communication_app_username'    => ['required', 'max:255']
                //'username' => ['required','max:190',Rule::unique('users')->whereNull('deleted_at')],            
            ]);


        if ($validator->fails()) 
        { 
            return redirect()->route('signup')->withErrors($validator)->withInput();       

        } else {
            return redirect()->route('signUpConfirmation')->withInput();
        }
    }


    public function showConfirmationForm(Request $request) {
        Session::reflash();
        return view('auth.confirmation');
    }

    public function store(Request $request) 
    {

        //Session::reflash();        
        DB::beginTransaction();

        try { 
            $userData =
            [                
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'first_name_jp'     => $request['first_name_jp'],
                'last_name_jp'      => $request['last_name_jp'],
                'username'          => $request['email'],
                'email'             => $request['email'],                
                'password'          => $request['password'],
                'api_token'         => Hash('sha256', Str::random(80))
            ];
            $user = User::create($userData);
        
            //Add Role
            $roles[] = Role::where('title', 'Member')->first()->id;
            $user->roles()->sync($roles); 

                
            $memberInformation =
            [
                'user_id'                   =>  $user->id,
                'member_attribute_id'       =>  null,
                'nickname'                  =>  $request['nickname'],
                'agent_id'                  =>  null,
                'gender'                    =>  null,
                'birthdate'                 =>  null, //date('Y-m-d', strtotime($request['birthday'])),
                'age'                       =>  null,
                'communication_app_name'    =>  $request['commApp'],
                'communication_app_username' => $request['communication_app_username'],
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

            DB::commit();
            //@todo: send verification mail.

            //redirect to step 3
            $name = $request['first_name'] . " " . $request['last_name'];
            $message = "<p>登録ありがとうございます。 $name </p>
            <br/>
                        <p>アカウントを有効にするには、メールを確認してください。</p>";
    
            return redirect()->route('step3')->with('message', $message); 

        }
        catch (\Exception $e) 
        {
            /*
            return Response()->json([
                "success"   => false,
                "message"   => "Exception Error Found (Member) : " . $e->getMessage()
            ]);
            */

            return redirect()->route('signup')->with('message', $e->getMessage()); 

            DB::rollback();
        }

        
    }

    //awating for verification
    public function step3(Request $request) 
    {

        Session::reflash();
        $message =  session('message') ;
        

        return view('auth.step3', compact('message'));

    }



}
