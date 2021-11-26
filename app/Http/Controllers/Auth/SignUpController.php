<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendUserSignUpConfirmation as SendUserSignUpConfirmationMail;
use App\Models\Tutor;
use App\Models\Agent;
use App\Models\AgentTransaction;
use App\Models\Member;
use App\Models\MemberAttribute;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use App\Models\Purpose;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Mail;
use Session;
use Validator;

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
        //echo $tracking_code ." this is the tracking code";

        $user = User::where('activation_code', $activation_code)->first();

        if ($user) {

            if ($user->is_activated == true) {

                //already activated 
                //@todo: show activated
                return view('auth.activation_is_activated', compact('user'));

            } else {

                //activate user

                $user->update([
                    //'activation_code' => "",
                    'valid' => 1,
                    'is_activated' => 1,
                ]);

                //@todo: add attribute (2 months) from current month and (2 lesson)
                $memberAttribute = new MemberAttribute();

                $dataThisMonth = [
                    "valid" => true,
                    'attribute' => "TRIAL",
                    'lesson_limit' => 2,
                    'member_id' => $user->id,
                    'month' => strtoupper(date('M')),
                    'year' => strtoupper(date('Y')),
                ];
                $memberAttribute->create($dataThisMonth);

                $dataNextMonth = [
                    "valid" => true,
                    'attribute' => "TRIAL",
                    'lesson_limit' => 2,
                    'member_id' => $user->id,
                    'month' => strtoupper(date('M', strtotime('first day of +1 month'))),
                    'year' => strtoupper(date('Y', strtotime('first day of +1 month'))),
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

                return view('auth.activation', compact('user'));
            }

        } else {

            //activation code invalid or not found
            return view('auth.activation_not_found', compact('user'));           
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

                'password' => 'required|min:4|max:32|same:confirm_password',
                'confirm_password' => 'required',

                'communication_app_username' => ['required', 'max:255'],
                //'username' => ['required','max:190',Rule::unique('users')->whereNull('deleted_at')],
            ]);

        if ($validator->fails()) {
            return redirect()->route('signup', ['a8' => $request->a8])->withErrors($validator)->withInput();

        } else {
            return redirect()->route('signUpConfirmation', ['a8' => $request->a8])->withInput();
        }
    }

    public function showConfirmationForm(Request $request)
    {
        Session::reflash();
        return view('auth.confirmation');
    }

    public function store(Request $request)
    {

        $checkUser = User::where('email', $request['email'])->first();

        if ($checkUser) 
        {
            $name = $request['first_name'] . " " . $request['last_name'];          
            $message = "<p>登録ありがとうございます。 $name </p><br/><p>アカウントを有効にするには、メールを確認してください。</p>";
            return redirect()->route('step3')->with('message', $message);
            exit();
        }

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

            //lesson shift
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

            /** defaults */
            //@todo: mt100 - default tutor for new users
            $defaultAgent = Agent::where('agent_id', 'mt100')->first();

            //@todo: 127 - default tutor id
            $defaultTutor = Tutor::where('user_id', 127)->first();
            
            $expiry_date = date('Y-m-d G:i:s', strtotime('+6 months'));

            
            $memberInformation =
                [
                'user_id' => $user->id,
                'nickname' => $request['nickname'],
                'communication_app' => ucfirst($request['commApp']),
                'skype_account' => $skype_account,
                'zoom_account' => $zoom_account,
                'membership' => "Point Balance",
                'member_since' => date('Y-m-d'),
                "gender" => "null",
                'credits_expiration' =>  $expiry_date,

                //"birthday" => "null",
                //default agent (auto filled)
                
                'agent_id' => $defaultAgent->user_id,
                //default tutor (auto filled)
                'tutor_id' => $defaultTutor->user_id,


                //attribute (auto filled)
                'attribute' => 'TRIAL',
                'lesson_shift_id' => $lessonShift->id,
            ];

            $member = Member::create($memberInformation);
            $user->members()->sync([$member->id], false);

            /********************************************
                        CREATE MEMBER PURPOSE
            **********************************************/

            //IELTS
            if (isset($request['IELTS'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['IELTS'],
                    'purpose_options' => json_encode($request['IELTS_option']),   
                    'member_id' => $user->id

                ]);
            }
 
            //TOEFL
            if (isset($request['TOEFL'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['TOEFL'],
                    'purpose_options' => json_encode($request['TOEFL_option']),
                    'member_id' => $user->id
                ]);
            }

            //TOEFL_Primary
            if (isset($request['TOEFL_Primary'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['TOEFL_Primary'],
                    //'purpose_options' => json_encode($request['TOEFL_Primary_option']),
                    'member_id' => $user->id
                ]);
            }

            //TOEIC
            if (isset($request['TOEIC'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['TOEIC'],
                    'purpose_options' => json_encode($request['TOEIC_option']),
                    'member_id' => $user->id
                ]);
            }


            //EIKEN
            if (isset($request['EIKEN'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['EIKEN'],
                    'purpose_options' => json_encode($request['EIKEN_option']),
                    'member_id' => $user->id
                ]);
            }            


            //TEAP
            if (isset($request['TEAP'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['TEAP'],
                    'purpose_options' => json_encode($request['TEAP_option']),
                    'member_id' => $user->id
                ]);
            }                 
              

            //BUSINESS
            if (isset($request['BUSINESS'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['BUSINESS'],
                    'purpose_options' => json_encode($request['BUSINESS_option']),
                    'member_id' => $user->id
                ]);
            }                 


            //BUSINESS_CAREERS
            if (isset($request['BUSINESS_CAREERS'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['BUSINESS_CAREERS'],
                    'purpose_options' => json_encode($request['BUSINESS_CAREERS_option']),
                    'member_id' => $user->id
                ]);
            }         


            //DAILY_CONVERSATION
            if (isset($request['DAILY_CONVERSATION'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['DAILY_CONVERSATION'],
                    'purpose_options' => json_encode($request['DAILY_CONVERSATION_option']),
                    'member_id' => $user->id
                ]);
            }

            //DAILY_CONVERSATION
            if (isset($request['OTHERS'])) {
                Purpose::create([          
                    'valid' => 1,
                    'purpose' => $request['OTHERS'],
                    'purpose_options' => $request['OTHERS_value'],
                    'member_id' => $user->id
                ]);
            }              


            DB::commit();
            
            //@todo: send verification mail.
            $mailData = [
                'firstname' => $request['first_name'],
                'lastname' => $request['last_name'],
                'name' => $request['first_name'] . " " . $request['last_name'],
                'nickname' => $request['nickname'],
                'email' => $request['email'],
                'a8' => $request['a8'],
                'activation_code' => $activation_code,
            ];
            Mail::send(new SendUserSignUpConfirmationMail($mailData));

            //redirect to step 3
            $name = $request['first_name'] . " " . $request['last_name'];

            $message = "<p>登録ありがとうございます。 $name </p><br/><p>アカウントを有効にするには、メールを確認してください。</p>";

            return redirect()->route('step3')->with('message', $message);

        } catch (\Exception $e) {

            DB::rollback();
            
            return redirect()->route('signup')->with('message', $e->getMessage() . " on Line Number: " . $e->getLine());
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
