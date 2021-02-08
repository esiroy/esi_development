<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use App\Models\Agent;
use App\Models\Shift;
use App\Models\AgentTransaction;
use App\Models\MemberAttribute;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Mail\SendUserSignUpConfirmation as SendUserSignUpConfirmationMail;

use Session;
use Validator;
use Mail;

class SignUpController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showSignUpForm()
    {
        return view('auth.signup');
    }

    public function activation($activation_code) 
    {

        $user = User::where('activation_code', $activation_code)->first();        
        if ($user) 
        {          
            $user->update([
                'activation_code' => "",
                'valid' => 1,                
                'is_activated' => 1
            ]);

            //@todo: add attribute (2 months) from current month and (2 lesson)
            $memberAttribute = new MemberAttribute();

            $dataThisMonth = [
                "valid" => true,
                'attribute'     => "TRIAL",
                'lesson_limit'  => 2,
                'member_id' => $user->id,
                'month' => strtoupper(date('M')),
                'year'  => strtoupper(date('Y'))
            ];            
            $memberAttribute->create($dataThisMonth);
            

            $dataNextMonth = [
                "valid" => true,
                'attribute'     => "TRIAL",
                'lesson_limit'  => 2,
                'member_id' => $user->id,
                'month' => strtoupper(date('M', strtotime('first day of +1 month'))),
                'year'  => strtoupper(date('Y', strtotime('first day of +1 month')))
            ];
            $memberAttribute->create($dataNextMonth);
            
            //@todo: add 2 points
             $dataAgentTransaction = [
                'member_id' => $user->id,
                'lesson_shift_id' => null,
                'created_by_id' => null,
                'transaction_type' => "FREE_CREDITS",
                'amount' => 2,
                'valid' => true,
            ];

            AgentTransaction::create($dataAgentTransaction);             

            return view('auth.activation', compact('message', 'user'));

        } else {
            abort(404);
        }
    }

    public function validateSignUpForm(Request $request)
    {
        $validator = Validator::make(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'first_name_jp' => $request->first_name_jp,
                'last_name_jp' => $request->last_name_jp,
                'email' => $request->email,
                'password' => $request->password,
                'confirm_password' => $request->confirm_password,
                'communication_app_username' => $request->communication_app_username,
            ],
            [
                'first_name' => ['required', 'max:255'],
                'last_name' => ['required', 'max:255'],
                'first_name_jp' => ['required', 'max:255'],
                'last_name_jp' => ['required', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],

                'password' => 'required|min:8|same:confirm_password',
                'confirm_password' => 'required',

                'communication_app_username' => ['required', 'max:255'],
                //'username' => ['required','max:190',Rule::unique('users')->whereNull('deleted_at')],
            ]);

        if ($validator->fails()) {
            return redirect()->route('signup')->withErrors($validator)->withInput();

        } else {
            return redirect()->route('signUpConfirmation')->withInput();
        }
    }

    public function showConfirmationForm(Request $request)
    {
        Session::reflash();
        return view('auth.confirmation');
    }

    public function store(Request $request)
    {

        //Session::reflash();
        DB::beginTransaction();

        try {

            //create activation code
            $activation_code = md5(Str::random(8));

            $userData =
                [
                'user_type' => "MEMBER",
                'firstname' => $request['first_name'],
                'lastname' => $request['last_name'],
                'japanese_firstname' => $request['first_name_jp'],
                'japanese_lastname' => $request['last_name_jp'],
                'username' => $request['email'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'activation_code' => $activation_code,
                'api_token' => Hash('sha256', Str::random(80)),
                'valid' => 1,
            ];
            $user = User::create($userData);

            //Add Role
            $roles[] = Role::where('title', 'Member')->first()->id;
            $user->roles()->sync($roles);

            $lessonShift = Shift::where('value', 25)->first();
            
            //communication app
            $commApp = ucfirst($request['commApp']);

            $skype_account = null;
            $zoom_account = null;

            if ($commApp == "Zoom") {
                $zoom_account = $request['communication_app_username'];
            } else if ($commApp == "Skype") {    
                $skype_account = $request['communication_app_username'];
            }            

            $memberInformation =
                [
                'user_id' => $user->id,
                'nickname' => $request['nickname'],
                'communication_app' => ucfirst($request['commApp']),
                'skype_account' => $skype_account,
                'zoom_account' => $zoom_account,
                'membership' => "Point Balance",
                'member_since' => date('Y-m-d'),

                //attribute (auto filled)
                'attribute' => 'TRIAL',
                'lesson_shift_id' => $lessonShift->id,
            ];


            $member = Member::create($memberInformation);
            $user->members()->sync([$member->id], false);

            DB::commit();

            //@todo: send verification mail.

            $mailData = [
                'firstname' => $request['first_name'],
                'lastname' => $request['last_name'],                
                'name' =>  $request['first_name'] . " " . $request['last_name'],
                'nickname' =>$request['nickname'],
                'email' =>  $request['email'],
                'activation_code' => $activation_code,
            ];

         
            Mail::send(new SendUserSignUpConfirmationMail($mailData));

            //redirect to step 3
            $name = $request['first_name'] . " " . $request['last_name'];


            $message = "<p>登録ありがとうございます。 $name </p>
            <br/>
                        <p>アカウントを有効にするには、メールを確認してください。</p>";

            return redirect()->route('step3')->with('message', $message);

        } catch (\Exception $e) {
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
        $message = session('message');

        return view('auth.step3', compact('message'));

    }

}
