<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentTransaction;
use App\Models\LessonGoals;
use App\Models\Member;
use App\Models\MemberAttribute;
use App\Models\MemberDesiredSchedule;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\ScheduleItem;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\User;
use App\Models\UserImage;
use App\Models\MemoReply;
use App\Models\ChatSupportHistory;
use App\Models\Purpose;
use App\Models\MemberExamScore;
use App\Models\Homework;
use App\Models\MemberLevel;
use App\Models\MergedAccount;
   
use App\Models\MemberMiniTestSetting;
use App\Models\MemberSetting;
use App\Models\MemberMonthlyTerm;

use App\Models\MemberFeedback;
use App\Models\MemberFeedbackDetails;


use App\Models\Folder;

use Auth, Hash, Storage;
use Carbon\Carbon;
use DB;

//use App\Models\MemberPointPurchaseHistory;
//use App\Models\Membership;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class MemberController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');

        $this->middleware(function ($request, $next) {
            //authenticated by has no "admin_access" in his role attached
            //@do: redirect to home (authenticated member will be his view)
            if (Gate::denies('admin_access')) {
                return redirect(route('home'));
            }
            return $next($request);           
        });        
    }


    /**
     * (v2)
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //request variables
        $member_id = request()->member_id;
        $name = $request->name;
        $email = $request->email;

        $attributes = createAttributes();
        $memberships = createMembership();

        $shifts = Shift::all();

        $memberQuery = Member::join('users', 'users.id', '=', 'members.user_id')                            
                            ->select("members.*", "users.is_activated", "users.id", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name"));

        if ($request->expired) {

            $today =   Carbon::now();
            $dateFrom = Carbon::now()->subDays(30); //expired for 30 days

            $memberQuery = $memberQuery->whereDate('members.credits_expiration', '<', $today->toDateString());  // all expired

            $memberQuery = $memberQuery->orderby('members.credits_expiration', 'DESC');
        
        }
        else if ($request->toexpire) 
        {

            //get expired members
            /*
            $dateFrom =   Carbon::now();
            $dateTo     = Carbon::now()->addDays(15); //expiring  15 days
            $memberQuery = $memberQuery->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));
            */

            $today =   Carbon::now();
            $memberQuery = $memberQuery->whereDate('members.credits_expiration', '>', $today->toDateString());  // all expired


            //Only Point Balance
            $memberQuery = $memberQuery->where('membership', "Point Balance");

            //agent transaction points.
            //$memberQuery = $memberQuery->leftJoin('agent_transaction', 'members.user_id', '=', 'agent_transaction.member_id');
            //$memberQuery = $memberQuery->where(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));


            $memberQuery = $memberQuery->orderby('members.credits_expiration', 'DESC');
         

        } else {

            //@[START] USER SEARCH - if user search for a member
            if (isset($member_id) || isset($name) || isset($email)) {
                if (isset($member_id)) {
                    $memberQuery = $memberQuery->where('members.user_id', $member_id);
                }

                if (isset($name)) {
                    $memberQuery = $memberQuery->orWhereRaw("CONCAT(users.firstname,' ',users.lastname) like '%" . $name . "%'")->orWhereRaw("CONCAT(users.lastname,' ',users.firstname) like '%" . $name . "%'");                
                }                

                if (isset($email)) {
                    $memberQuery = $memberQuery->orWhere('users.email', $email);
                }
                
            } else {

                //user did not search, check user if tutor, if user tutor then show only members that is he/she is tutoring... 
                if (strtolower(Auth::user()->user_type) == "tutor") 
                {
                    $memberQuery = $memberQuery->where('tutor_id', Auth::user()->id);
                }                
            } //[END] USER SEARCH

            $memberQuery = $memberQuery->orderby('users.id', 'ASC');
        }
        
        $members = $memberQuery->paginate(Auth::user()->items_per_page);

        //Tutor Query
        //$tutorQuery = User::whereHas('roles', function($q) { $q->where('title', 'Tutor'); })->get();
        $tutorQuery = User::where('user_type', "TUTOR")->get();
        $tutors = json_encode($tutorQuery);

        //MEMBER ACCESS CONTROL
        $can_member_access = (Gate::denies('member_acesss')) ? 'false' : 'true';
        $can_member_create = (Gate::denies('can_member_create')) ? 'false' : 'true';

        $can_member_delete = (Gate::denies('member_delete')) ? 'false' : 'true';
        $can_member_edit = (Gate::denies('member_edit')) ? 'false' : 'true';
        $can_member_view = (Gate::denies('member_view')) ? 'false' : 'true';

        return view('admin.modules.member.index', compact('memberships', 'shifts', 'attributes', 'tutors', 'members',
            'can_member_access', 'can_member_create', 'can_member_edit',
            'can_member_view', 'can_member_delete'));
    }    

    /**
     * (v2)
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($memberID)
    {

        $folder = new Folder();
        //get recent history for member
        $recentLessonHistory   = $folder->getAllRecentLessonHistory($memberID);

        //initialize member feedback
        $memberFeedbackModel = new MemberFeedback();
        $memberFeedbackDetailsModel = new MemberFeedbackDetails();

        //get recent history member feedback
        if (isset($recentLessonHistory->schedule_id)) 
        {
            $memberFeedback = $memberFeedbackModel->where('schedule_id', $recentLessonHistory->schedule_id)->first();

            if ($memberFeedback) {

                $memberFeedbackDetails = $memberFeedbackDetailsModel->getMemberFeedbackDetails($memberFeedback->id, 'student_performance_rating');
            }
        } else {

            $recentLessonHistory    = null;
            $memberFeedback         = null;
            $memberFeedbackDetails  = null;
        }
       


       
      

        $memberInfo = Member::where('user_id', $memberID)->first();

        $agent = new Agent();   
        $agentInfo = $agent->getAgentInfo($memberInfo->agent_id);

        if (isset($memberInfo)) 
        {

            //search if user has merged account
            $mergedAccount = MergedAccount::where('merged_member_id',  $memberID)->first();

            if ($mergedAccount) {

                //merged account
                $mergedMemberInfo   = User::find($mergedAccount->member_id)->memberInfo;
                $mergedType         = "secondary";

                $mergedAccounts  = null;


                //report cards (time manager and purpose and past exam and cefr score ra mo merge)
                $reportCard = new ReportCard();
                //$latestReportCard = $reportCard->getLatest($mergedAccount->member_id);
                $latestReportCard = $reportCard->getLatest($memberID);

                //member CEFR Level
                $memberLevel = new MemberLevel();      
                $currentMemberlevel = $memberLevel->getLevel($mergedAccount->member_id);
                

                                //writing report cards
                $reportCardDate = new ReportCardDate();
                $latestWritingReport = $reportCardDate->getLatest($mergedAccount->member_id);

            } else {


                //main account
                $mergedMemberInfo = $memberInfo;   
                $mergedType         = "main";

                $mergedAccounts = MergedAccount::select('users.id', 'users.email', 'merged_accounts.created_at as date')
                ->where('member_id', $memberID)
                ->leftJoin('users', 'users.id', '=', 'merged_accounts.merged_member_id')
                ->get();

                //report cards
                $reportCard = new ReportCard();
                $latestReportCard = $reportCard->getLatest($memberID);

                //member CEFR Level
                $memberLevel = new MemberLevel();      
                $currentMemberlevel = $memberLevel->getLevel($memberID);


                //writing report cards
                $reportCardDate = new ReportCardDate();
                $latestWritingReport = $reportCardDate->getLatest($memberID);

            }        


            if (isset($memberInfo->tutor_id)) {
                $tutorInfo = Tutor::where('user_id', $memberInfo->tutor_id)->first();
            } else {
                $tutorInfo = null;
            }

            if (isset($memberInfo->lesson_shift_id)) {
                $shift = new Shift();
                $duration = $shift->getDuration($memberInfo->lesson_shift_id);
            } else {
                $duration = null;
            }

            //get Lesson goals
            $goals = new LessonGoals();
            $lessonGoals = $goals->getLessonGoals($memberID);

  
           


            if (isset($latestReportCard->schedule_item_id))
            {
                $homework = Homework::where('schedule_item_id', $latestReportCard->schedule_item_id)->first();
            } else {
                $homework = null;
            }




            //get purpose (new)       
            $purposeModel = new Purpose();
            $purpose = $purposeModel->getMemberPurpose($memberID);

            $memberExamScoreModel = new MemberExamScore();
            $memberLatestExamScore = $memberExamScoreModel->getMemberLatestScore($memberID);

            return view('admin.modules.member.memberInfo', compact('memberInfo', 'tutorInfo', 'agentInfo', 'lessonGoals', 
                                                'latestReportCard', 'latestWritingReport', 'purpose', 'memberLatestExamScore', 
                                                'currentMemberlevel', 'homework', 'mergedMemberInfo', 'mergedAccount', 'mergedType', 'mergedAccounts', 
                                                'recentLessonHistory', 'memberFeedback', 'memberFeedbackDetails'));
        } else {

            abort(404, "Member Not Found");
        }

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function account($memberID)
    {
        $member = Member::where("user_id", $memberID)->first();

        if (!isset($member)) {
            abort(404);
        }

        $user = $member->user;
        $agent = $member->agent;

        $agentTransaction = new AgentTransaction();
        $credits = $agentTransaction->getCredits($member->user_id);
        $latestDateOfPurchase = $agentTransaction->getMemberLatestDateOfPurchase($member->user_id);

        $transactions = $agentTransaction->getMemberTransactions($member->user_id);

        $purchaseHistory = $agentTransaction->getAllPaymentHistory($member->user_id);

        return view('admin.modules.member.account', compact('member', 'credits', 'transactions', 'purchaseHistory', 'latestDateOfPurchase'));
    }

    /**
     * Display a listing of the payment history.
     * @param $memberID
     */
    public function paymenthistory($memberID)
    {
        $memberInfo = Member::where('user_id', $memberID)->first();

        if ($memberInfo) {

            $member = $memberInfo->user;

            //agent
            $agentInfo = Agent::where('user_id', $memberInfo->agent_id)->first();

            //tutor for
            $tutorInfo = Tutor::where('user_id', $memberInfo->tutor_id)->first();

            $agentTransaction = new AgentTransaction();
            $paymentHistory = $agentTransaction->getPaymentHistory($memberID);

            return view('admin.modules.member.paymenthistory', compact('member', 'memberInfo', 'agentInfo', 'tutorInfo', 'paymentHistory'));

        } else {

            abort(404);

        }

    }

    public function schedulelist_test($memberID, ScheduleItem $scheduleItem, MemberAttribute $memberAttribute)
    {

        $schedules = $scheduleItem->getMemberScheduledLesson($memberID);
        
        
        $lessonLimit = 0;
        $memberAttribute = $memberAttribute->getCurrentMonthLessonLimit($memberID);        
        if (isset($memberAttribute->lesson_limit)) {
            $lessonLimit = $memberAttribute->lesson_limit;
        }

        $totalReserved = $scheduleItem->getTotalLessonForCurrentMonth($memberID);        
        
        $memberLessonsRemaining = $lessonLimit - $totalReserved;

        echo $memberLessonsRemaining;

    }

    public function schedulelist($memberID, ScheduleItem $scheduleItem, MemberAttribute $memberAttribute)
    {
        $memberInfo = Member::where('user_id', $memberID)->first();

        if ($memberInfo) {
            $member = $memberInfo->user;

            //agent
            $agentInfo = Agent::where('user_id', $memberInfo->agent_id)->first();

            //tutor for
            $tutorInfo = Tutor::where('user_id', $memberInfo->tutor_id)->first();

            $thisMonth = strtoupper(date("M"));
            $thisYear = date("Y");
      
            $schedules = $scheduleItem->getMemberScheduledLesson($memberID);

            /* wrong fetch
            $memberAttribute = $memberAttribute->getCurrentMonthLessonLimit($memberID);
            $lessonLimit = $memberAttribute->lesson_limit;            
            $totalReserved = $scheduleItem->getTotalLessonForCurrentMonth($memberID);
            $memberLessonsRemaining = $lessonLimit - $totalReserved;
            */

            $lessonLimit = 0;
            $memberAttribute = $memberAttribute->getCurrentMonthLessonLimit($memberID);        
            if (isset($memberAttribute->lesson_limit)) {
                $lessonLimit = $memberAttribute->lesson_limit;
            }
    
            $totalReserved = $scheduleItem->getTotalLessonForCurrentMonth($memberID);
            
            //$memberLessonsRemaining = $lessonLimit - $totalReserved;    

            $memberLessonsRemaining  = $memberInfo->getMemberMonthlyLessonsLeft($memberID);

            return view('admin.modules.member.schedulelist', compact('schedules', 'lessonLimit', 'totalReserved', 'memberLessonsRemaining', 'member', 'memberInfo', 'agentInfo', 'tutorInfo', 'memberAttribute'));
            
        } else {
            abort(404);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.modules.member.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //api storing
    }


    public function resetPassword($id, Request $request)
    {
        abort_if(Gate::denies('member_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $member = Member::where('user_id', $id)->first();

        $userData = [
            'password' => Hash::make($request->password),
        ];

        $user = User::find($member->user_id);
        
        $user->update($userData);

        return redirect()->route('admin.member.edit', $id)->with('message', 'Member password has been updated successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($memberID)
    {



        abort_if(Gate::denies('member_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $memberInfo = Member::where('user_id', $memberID)->first();

        if (!$memberInfo) {
            //member is not found in member table
            abort(404);
        }

        //get photo
        $userImageObj = new UserImage();
        $userImage = $userImageObj->getMemberPhoto($memberInfo);

        //user Info
        $userInfo = User::where('id', $memberID)->select('id', 'firstname', 'lastname', 'email', 'japanese_firstname', 'japanese_lastname','user_type', 'is_activated')->first();
        $attributes = createAttributes();
        $memberships = createMembership();
        $shifts = Shift::all();
        

        //get latest report card
        $reportCardObject = new ReportCard();
        $latestReportCardValue = $reportCardObject->getLatest($memberID);
      
        
        if (isset($latestReportCardValue->schedule_item_id))
        {
            $homework = Homework::where('schedule_item_id', $latestReportCardValue->schedule_item_id)->first();

            if ($homework) {
            
                $homeworkdata = [
                            'url'           => url( Storage::url($homework->original) ),      
                            'instruction'   => $homework->instruction             
                            ];
                
            } else {
            
                 $homeworkdata = null;
            
            }

        }

     

        $latestReportCard = [
            'lesson_level' => isset($latestReportCardValue->lesson_level)? $latestReportCardValue->lesson_level : ' - ',
            'lesson_course' => isset($latestReportCardValue->lesson_course)? $latestReportCardValue->lesson_course : ' - ',
            'lesson_material' => isset($latestReportCardValue->lesson_material)? $latestReportCardValue->lesson_material : ' - ',
            'lesson_subject' => isset($latestReportCardValue->lesson_subject)? $latestReportCardValue->lesson_subject : ' - ',
            'lesson_grade' => isset($latestReportCardValue->grade)? formatGrade($latestReportCardValue->grade) : ' - ',
            'homework' => $homeworkdata ?? '',
        ];


        
        if (isset($memberInfo->agent_id))
        {
            $agentInfo = Agent::where("user_id", $memberInfo->agent_id)->first();
            $agentArrayInfo = $agentInfo->toArray();
            $agentUserArrayInfo = $agentInfo->user->toArray();      
            $agentInfo = array_merge($agentArrayInfo, $agentUserArrayInfo);
        } else {
            $agentInfo = (object) [];
        }
      
        //$agentInfo = User::where('id', $memberInfo->agent_id)->first();

        //get Lessongoals (purpose)
        $goals = new LessonGoals();
        $lessonGoals = $goals->getLessonGoals($memberID);


        //get purose (new)       
        $purposeModel = new Purpose();
        //$purpose = $purposeModel->getMemberPurpose($memberID);
        $purpose = $purposeModel->getMemberPurpose($memberID);


        
        //member CEFR Level
        $memberLevel = new MemberLevel();      
        $currentMemberlevel = $memberLevel->getLevel($memberID);
            
        $memberExamScoreModel = new MemberExamScore();
        $memberLatestExamScore = $memberExamScoreModel->getMemberLatestScore($memberID);


        //MemberAttribute - (lessonClasses)
        $memberAttribute = new MemberAttribute();
        
        $lessonClasses = $memberAttribute->getMemberAttribute($memberID);

        $memberDesiredSchedule = new MemberDesiredSchedule();
        $desiredSchedule = $memberDesiredSchedule->getMemberDesiredSchedule($memberID);


        //get User Mini Test 
        $memberMiniTestSetting = new MemberMiniTestSetting();

        $minitest['memberMiniTestHasOverride']    = $memberMiniTestSetting->hasOverride($memberID);
        $minitest['memberMiniTestLimit']          = $memberMiniTestSetting->getMiniTestLimit($memberID);
        $minitest['memberMiniTestDuration']       = $memberMiniTestSetting->getMiniTestDuration($memberID);

        //get User Tab Settings 
        $memberSettingsObj = new MemberSetting();
        $hideMemberTabs = $memberSettingsObj->getMemberSetting($memberID, 'hide_member_tabs');

    
        $memberMonthlyTermObj = new MemberMonthlyTerm();
        $memberMonthlyTerm = $memberMonthlyTermObj->isMonthlyNotificationEnabled($memberID);        

        $isMemberAgreedToTerms = $memberMonthlyTermObj->isMemberAgreedToMonthlyTerms($memberID); 
        

        //View all the stufff
        return view('admin.modules.member.edit', compact('agentInfo', 'memberships', 'shifts', 'attributes',
            'userInfo', 'memberInfo', 'userImage', 'latestReportCard',
            'lessonGoals', 'lessonClasses', 'desiredSchedule', 'purpose', 'memberLatestExamScore', 'currentMemberlevel',
            'minitest', 'hideMemberTabs',
            'memberMonthlyTerm', 'isMemberAgreedToTerms'
            ));

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

     

        if ($request->transaction_type !== 'CREDITS_EXPIRATION') {

            $validator = Validator::make($request->all(), [
                'transaction_type' => ['required'],
                'amount' => ['required'],
                'credits' => ['required'],
            ]);

            if ($validator->fails()) {
                //return redirect()->back()->withErrors($validator)->withInput();
                return redirect()->back()->withErrors($validator)->withInput()->with('error_message', 'Transaction amount and credits is required ');
            }

        }

        //variables
        $expiry_date = null;

        //AGENT_SUBTRACT
        //DISTRIBUTE, CREDITS_EXPIRATION, MANUAL_ADD, FREE_CREDITS
        $member = Member::where("user_id", $id)->first();

        if (!isset($member)) {
            abort(404);
        }

        $memberUserId = $member->user_id; 


        if ($request->transaction_type == 'CREDITS_EXPIRATION') 
        {
            //Update expiry member
            $newExpiryDate = $request->expiry_date ." " . date("G:i:s");

            $expiry_date = date('Y-m-d G:i:s', strtotime($newExpiryDate));
            $old_credits_expiration = date('Y-m-d G:i:s', strtotime($member->credits_expiration));

            //agent transaction
            $member->update([
                'credits_expiration' => $expiry_date ,
            ]);
            
            //create Agent Transaction
            $agentCredit = [
                'valid' => 1,
                'transaction_type' => $request->transaction_type,
                'agent_id' => null,
                'member_id' => $member->user_id,
                'lesson_shift_id' => $member->lesson_shift_id,
                'created_by_id' => Auth::user()->id,
                'amount' => null, //amount is zero
                'price' => null, //amount is zero
                'remarks' => $request->remarks,
                'credits_expiration' => $expiry_date,
                'old_credits_expiration' => $old_credits_expiration,
            ];
            AgentTransaction::create($agentCredit);


        } else {      

            //generate agent transaction expiration date
            $expiry_date = date('Y-m-d G:i:s', strtotime('+6 months'));

            $old_credits_expiration = date('Y-m-d G:i:s', strtotime($member->credits_expiration));

           



            if ($request->transaction_type == 'DISTRIBUTE') 
            {                
                //create Agent Transaction :  (updates) ONLY Distribute will change expiration date + 6months


                //add member expiration
                $member->update([
                    'credits_expiration' => $expiry_date,
                ]); 

                $agentCredit = [
                    'valid' => 1,
                    'transaction_type' => $request->transaction_type,
                    'agent_id' => null,
                    'member_id' => $member->user_id,
                    'lesson_shift_id' => $member->lesson_shift_id,
                    'created_by_id' => Auth::user()->id,
                    'amount' => $request->credits,
                    'price' => $request->amount,
                    'remarks' => $request->remarks,
                    'credits_expiration' => $expiry_date,
                    'old_credits_expiration' => $old_credits_expiration,
                ];

                AgentTransaction::create($agentCredit);


            } else {

                //create Agent Transaction
                $agentCredit = [
                    'valid' => 1,
                    'transaction_type' => $request->transaction_type,
                    'agent_id' => null,
                    'member_id' => $member->user_id,
                    'lesson_shift_id' => $member->lesson_shift_id,
                    'created_by_id' => Auth::user()->id,
                    'amount' => $request->credits,
                    'price' => $request->amount,
                    'remarks' => $request->remarks,
                    //'credits_expiration' => $expiry_date,
                    //'old_credits_expiration' => $old_credits_expiration,
                ];

                AgentTransaction::create($agentCredit);

            }



        }

        return redirect()->route('admin.member.account', $id)->with('message', 'Member transaction has been added successfully!');
    }

    public function activate($id) {
        $user = User::find($id);
        $data = ['is_activated'=> true];
        $user->update($data);
        return redirect()->route('admin.member.edit', $id)->with('message', 'Member has been activated successfully!');
    }


    public function deactivate($id) 
    {
        $user = User::find($id);
        $data = ['is_activated'=> false];
        $user->update($data);
        return redirect()->route('admin.member.edit', $id)->with('message', 'Member has been deactivated successfully!');
    }


    public function destroy($id)
    {        
        abort_if(Gate::denies('member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (Auth::user()->user_type == "ADMINISTRATOR") 
        {        
            $member = Member::where('user_id', $id)->first();

            if (!$member) {

                abort(404);
            }

            $user = User::find($member->user_id);

            if ($user) {

                LessonGoals::where('member_id', $user->id)->delete();
                MemberAttribute::where('member_id', $user->id)->delete();
                MemberDesiredSchedule::where('member_id', $user->id)->delete();
    
                //clear all chat support history
                ChatSupportHistory::where('sender_id', $user->id)->delete();
                ChatSupportHistory::where('recipient_id', $user->id)->delete();
    
                MemoReply::where('sender_id', $user->id)->delete();
                MemoReply::where('recipient_id', $user->id)->delete();
    
    
                MergedAccount::where('member_id', $user->id)->delete();
                MergedAccount::where('merged_member_id', $user->id)->delete();
    
                ChatSupportHistory::where('sender_id', $user->id)->delete();
                ChatSupportHistory::where('recipient_id', $user->id)->delete();
    
                MemberSetting::where('user_id', $user->id)->delete();                

            }


            $member->delete();
            $user->forceDelete();

            return redirect()->route('admin.member.index')->with('message', 'Member has been deleted!');    

        } else {

            return redirect()->back()->with('error_message', 'Delete is not allowed for your user type, please contact the administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /*
    public function destroy($id)
    {
        abort_if(Gate::denies('member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if (Auth::user()->user_type == "ADMINISTRATOR") 
        {
        
            $member = Member::where('user_id', $id)->first();
            $user = User::find($member->user_id);

            LessonGoals::where('member_id', $user->id)->delete();
            MemberAttribute::where('member_id', $user->id)->delete();
            MemberDesiredSchedule::where('member_id', $user->id)->delete();

            //clear all chat support history
            ChatSupportHistory::where('sender_id', $user->id)->delete();
            ChatSupportHistory::where('recipient_id', $user->id)->delete();

            MemoReply::where('sender_id', $user->id)->delete();
            MemoReply::where('recipient_id', $user->id)->delete();
            Purpose::where('member_id', $user->id)->delete();
            $member->delete();
            $user->forceDelete();

            return redirect()->route('admin.member.index')->with('message', 'Member has been added deleted!');        

        } else {        

            return redirect()->route('admin.member.edit', $id)->with('error_message', 'Current user has no administrator privilidge!');

        }



    }

    

    /*
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Tutor::whereIn('user_id', request('ids'))->delete();
        User::whereIn('user_id', request('ids'))->forceDelete();

        //clear all chat support history
        ChatSupportHistory::whereIn('sender_id',request('ids'))->forceDelete();
        ChatSupportHistory::whereIn('recipient_id',request('ids'))->forceDelete();

        MemoReplies::whereIn('sender_id', request('ids'))->delete();
        MemoReplies::whereIn('recipient_id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    */


}