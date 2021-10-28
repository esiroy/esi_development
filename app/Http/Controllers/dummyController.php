<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use App\Models\Member;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\MemberAttribute;
use App\Models\ScheduleItem;
use App\Mail\SendEmailDemo;
use App\Models\MemoReply;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;
use App;
use Gate;
use DB;
use Auth;
use Config;
use Mail;
use App\Models\LessonMailer;
use App\Mail\CustomerSupport as CustomerSupportMail;
use App\Models\PhpSpreadsheetFontStyle as Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class dummyController extends Controller
{

    public function __construct()
    {
    }

    public function index(ScheduleItem $scheduleItemObj) 
    {
        $memberID = 4;


        //TOTAL RESERVED
        
        //$totalReserved =  $scheduleItemObj->getTotalLessonReserved($memberID, '11', '01'); 


        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date("2021-11-01 09:00:00")));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";
        
        
        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")  
                    ->get();
                    
        echo "<Br>";
        echo $startDate . " - " . $endDate;
        echo "<Br>";                    
        echo "<pre>";
        foreach ($reserved as $item) {
            echo $item->lesson_time;
            echo "<BR>";
        }        
        echo "</pre>";

       
        
        echo $scheduleItemObj->getTotalLessonForCurrentMonth($memberID); 

        echo " - ";

        echo $scheduleItemObj->getTotalReservedForCurrentMonth($memberID);


        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date('Y-m-01 09:00:00')));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";

        echo "<Br>";
        echo $startDate . " - " . $endDate;
        echo "<Br>";

        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")
                    ->get();

        echo "<pre>";
        foreach ($reserved as $item) {
            echo $item->lesson_time;
            echo "<BR>";
        }        
        echo "</pre>";


        /* test old code (buggy)*/
        $currentYear = date('Y');
        $currentMonth = date('m');
                
        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "CLIENT_RESERVED")                       
                    //->where('valid', 1)
                    ->get();


            echo "<pre>";
            foreach ($reserved as $item) {
                echo $item->lesson_time;
                echo "<BR>";
            }        
            echo "</pre>";                    

    }
    
    public function mailDateFormat(ScheduleItem $scheduleItem) {

        $scheduleItem = $scheduleItem->find(912532);

        echo ESIMailDateTimeFormat($scheduleItem->lesson_time);

        //return view("dummy/index", ['title'=> "TEST"]);

    }

    public function dropzone() {
        return view("dummy/dropzoneSimple", ['title'=> "TEST"]);
    }

    public function simpleuploader() {
        return view("dummy/index", ['title'=> "TEST"]);
    }

    public function updateQuestionnaire() {
        $questions = Questionnaire::where('created_at', '>=', date('2021-05-10'))->get();
        foreach ($questions as $q) 
        {
            echo "===============================<BR>";
            echo $q->id .  " - " . $q->created_at . "<BR>";
            echo "===============================<BR>";
            
            $items = QuestionnaireItem::where('questionnaire_id', $q->id)->where('question', "")->where('valid', true)->get();
            $ctr = 1;

            foreach ($items as $item) {

                echo $item->id ."<BR>";

                $data = [
                            'question' => 'QUESTION_'.$ctr
                ];
                

                $item->update($data);               

                $ctr ++;
               
            }
        }
        
    }

    public function memoReply() 
    {

        $date = date('Y-m-d H:i:s');

        $schedules = MemoReply::where('schedule_item.tutor_id', 21809)
        ->join('schedule_item', 'schedule_item.id', '=', 'memo_replies.schedule_item_id')
        ->groupBy('memo_replies.schedule_item_id')
        ->where(function ($q) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })         
        ->select('memo_replies.id', 'memo_replies.schedule_item_id', 'memo_replies.updated_at', 'memo_replies.message')
        ->where('schedule_item.lesson_time', ">=", $date)
        ->get();

        print_r ($schedules);
        
        foreach ($schedules as $schedule) 
        {
           $latestReply = MemoReply::where('schedule_item_id', $schedule->id)->where('is_read', false)->where('message_type', "MEMBER")->orderBy('updated_at', 'DESC')->first();           
            
           $results[] = array(
                            'schedule_id' => $latestReply->schedule_item_id, 
                            'message' => $latestReply->message,
                            'updated_at' => $latestReply->updated_at ,
                        );
        }               
        usort($results, sortByDate('updated_at'));

        echo "<pre>";

        $object = (object) $results;

        print_R ($object);
        
        
    }
  
    public function index2() {
        $date = date('Y-m-d H:i:s');
        
       //get schedule unique
       /*
        $schedules = MemoReply::where('schedule_item.tutor_id', 21809)
                ->join('schedule_item', 'schedule_item.id', '=', 'memo_replies.schedule_item_id')
                ->groupBy('memo_replies.schedule_item_id')
                ->where(function ($q) {                
                    $q->orWhere('schedule_status', 'CLIENT_RESERVED')
                    ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
                })         
                ->select('memo_replies.id', 'memo_replies.schedule_item_id', 'memo_replies.updated_at', 'memo_replies.message')
                ->where('schedule_item.lesson_time', ">=", $date)
                ->get();

        */

        $schedules = ScheduleItem::where('tutor_id', 21809)->where('valid', 1)->where(function ($q) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();        

                
        foreach ($schedules as $schedule) 
        {
           $latestReply = MemoReply::where('schedule_item_id', $schedule->id)->where('is_read', false)->where('message_type', "MEMBER")->orderBy('updated_at', 'DESC')->first();           
            
           $results[] = array(
                            'schedule_id' => $latestReply->schedule_item_id, 
                            'message' => $latestReply->message,
                            'updated_at' => $latestReply->updated_at ,
                        );
        }               
        usort($results, sortByDate('updated_at'));

        echo "<pre>";

        $object = (object) $results;

        print_R ($object);
    }

    public function uploader(){
        
       
        return view("dummy/index", ['title'=> "TEST"]);

    }
    

    public function sendTestMail() {

        //$user['email'] = 'emailroy2002@yahoo.com';
        //Mail::to($user['email'])->send(new SendEmailDemo());        
        
        Mail::to("bhadz.trex@gmail.com")
        ->cc(["emailroy2002@yahoo.com", "abellana@gmail.com"])
        //->bcc($evenMoreUsers)
        //->from('support@mytutor.co.jp', 'マイチューター')        
        ->send(new SendEmailDemo());
    }

    public function testDispatch() {

        $details['to'] = 'abellana@gmail.com';
        $details['name'] = 'Roy this is a test dispatch';
        $details['subject'] = 'Hello roy i am testing this';
        $details['message'] = 'Here goes all message body.';

        SendMailJob::dispatch($details);

        return response('Email sent successfully');

    }

    public function testMailReservedB(LessonMailer $lessonMailer) {
        
        //*** TEMPLATE ***/
        $details['template'] = "emails.client.reserved";        

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスン予約のご案内'; //reserved
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        $lessonMailer->sendMemberEmail($member, $tutor, $scheduleItem);

        return view($details['template'], compact('member','tutor', 'scheduleItem'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function testMailReserved()
    {         
        /*
        if (App::environment(['local', 'staging'])) {
            echo "1";
        } else {
            echo "2";
        }*/



        //*** TEMPLATE ***/
        $details['template'] = "emails.tutor.tutorNotifyCancelled";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスン予約のご案内'; //reserved
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        return view('emails.tutor.tutorNotifyCancelled', compact('member','tutor', 'scheduleItem'));



        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);
        dispatch($job);        


        dd('Send Email Successfully');
        //return view('emails.lesson.reserved', compact('member','tutor', 'scheduleItem'));
    }


    public function testMailCancelled()
    {        
        
        //*** TEMPLATE ***/
        //$details['template'] = "emails.lesson.memberNotifyCancelled";


        $details['template'] = "emails.manager.clientReserved";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスンキャンセルのご案内'; //cancelled
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        //$job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);
        //dispatch($job);        
        //dispatch(new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem));        


        $lessonMailer = new LessonMailer();
        $lessonMailer->send($member, $tutor, $scheduleItem);      
        
        
        dd('Send Email Successfully');        
    }    


    public function clientNotAvailable() 
    {  
        $details['template'] = "emails.lesson.cancelled";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスン欠席のご案内'; //client not available
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);

        dispatch($job);        
        dd('Send Email Successfully');         
    }


    public function tutorNotAvailable() 
    {    
        //マイチューター：レッスンキャンセルのご案内 (CHECKED)


        $details['template'] = "emails.lesson.cancelled";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスンキャンセルのご案内'; //tutor not available
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);

        dispatch($job);        
        dd('Send Email Successfully');          

    }



    function lessonLimitTest() 
    {      
        $memberID = Auth::user()->id;
        $memberAttribute = new MemberAttribute();
        $scheduleItem = new ScheduleItem();
        $attribute = $memberAttribute->getLessonLimit($memberID);

        //current lesson limit
        $limit = $attribute->lesson_limit;
        //total schedule added (active)
        $currentMonthTotalReserves = $scheduleItem->getTotalLessonForCurrentMonth($memberID);

        echo $currentMonthTotalReserves;

        if ($currentMonthTotalReserves >= $limit) {
            echo "over the total";
        } else {
            echo "okay";
        }

    }

    public function test() {
        return view('admin.test.index');

    }

    public function testUserPoints($memberID) {
        //18153 - Kobayashi, Ryusei  

    }

    public function testExpiry(Member $member) 
    {
        $user_id =  Auth::user()->id;        
    
        $memberInfo = $member->where('user_id', 20372)->first();


        $today = date("Y-m-d, H:i");
        $expiry = date("Y-m-d, 00:30", strtotime($memberInfo->credits_expiration ." + 1 day"));;

        echo $today ." > ". $expiry;


        
        echo "<bR>";

        
        if ($today > $expiry) {
            echo "hala expired<BR>";
        } else {
            echo "hala dili<BR>";
        }

        echo "<bR>";

        if ($member->isMemberCreditExpired(20372)) {
            echo "<p>expired</p>";
        } else {
            echo "not expired";
        }


    }


    public function testGetMembers(Request $request) 
    {
        $today = Carbon::now();
        $dateFrom = $request->get('from');
        $dateTo = $request->get('to');

        //get query with expiration null
        $memberQuery = Member::join('agent_transaction', 'agent_transaction.member_id', '=', 'members.user_id');
        $memberQuery = $memberQuery->whereBetween('agent_transaction.created_at', array($dateFrom, $dateTo));
        $memberQuery = $memberQuery->where('agent_transaction.transaction_type', "LIKE", "EXPIRED");
        $memberQuery = $memberQuery->where('members.membership', "Point Balance");
        $memberQuery = $memberQuery->where('members.credits_expiration', null);  //expired
        $memberQuery = $memberQuery->groupby('members.user_id')->get()->toArray();

        

        $memberQueryOne = Member::join('users', 'users.id', '=', 'members.user_id');
        $memberQueryOne = $memberQueryOne->select("members.*", "users.id", "users.email", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as fullname"));
        $memberQueryOne = $memberQueryOne->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));
        $memberQueryOne = $memberQueryOne->where('members.credits_expiration', ">=", $dateFrom);
        $memberQueryOne = $memberQueryOne->whereDate('members.credits_expiration', '<=', $dateTo);
        $memberQueryOne = $memberQueryOne->where('membership', "Point Balance");        
        $memberQueryOne = $memberQueryOne->orderby('members.credits_expiration', 'ASC')->get()->toArray();

        $memberQuery = array_merge($memberQuery, $memberQueryOne);



        //get query with expiration null
        $memberQueryThree = Member::join('agent_transaction', 'agent_transaction.member_id', '=', 'members.user_id');
        $memberQueryThree = $memberQueryThree->whereBetween('members.credits_expiration', array($dateFrom, $dateTo));
        $memberQueryThree = $memberQueryThree->where('members.membership', "Point Balance");
        $memberQueryThree = $memberQueryThree->groupby('members.user_id')->get()->toArray();

        $memberQueryAll = array_merge($memberQuery, $memberQueryThree);

        $memberQueryAll = unique_multidim_array($memberQueryAll, 'user_id');


        foreach ($memberQueryAll as $memberItem) {
            $member = Member::where('user_id', $memberItem['user_id'])->first();
            echo $member->user->id ." " .$member->user->firstname . " " . $member->user->lastname . "  Status: " .  $member->transaction_type . " | expiry:  " . $member->credits_expiration
             ." | Expired Added :  ". date('M-d-y', strtotime($memberItem['created_at']));
            echo "<BR>";
        }
        
        
        /*
        $members =  DB::table('members')->join('users', 'users.id', '=', 'members.user_id')
        ->select('members.user_id', 'members.nickname')
        ->where('users.valid', 1)
        ->get();

        return view('admin.test.index');
        */

    }
    
    

    function excelExportTest() 
    {
        $dateToday = date("m/d/Y");
        $filename =  "MyPageSortedMemberList.xlsx";
               
        $spreadsheet = Style::init();
        $sheet = $spreadsheet->getActiveSheet(); 

        //Set Header Text
        $sheet->setCellValue('B1', "Sorted Member List as of $dateToday");     
       
        //Secondary Field Headers (h2)
        $sheet->setCellValue('B2', "I.D");
        $sheet->setCellValue('C2', "First Name");
        $sheet->setCellValue('D2', "Last Name");
        $sheet->setCellValue('E2', "E-Mail");
        $sheet->setCellValue('F2', "Credits");
        $sheet->setCellValue('G2', "Expiration Date");
        
        //style for field headers h2
        $styleArrayH2 = Style::setHeader('FFFFFF','669999', 20);
        $spreadsheet->getActiveSheet()->getStyle('B2:G2')->applyFromArray($styleArrayH2);        

        //SET COLOR MANUAL
        //$spreadsheet->getActiveSheet()->getStyle('B2:G2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);


        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);


        if(file_exists($filename)) {
            header('Content-Description: File Transfer');
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            flush(); // Flush system output buffer
            readfile($filename);
            die();
        } else {
            http_response_code(404);
	        die();
        }        

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $memberQuery = Member::join('users', 'users.id', '=', 'members.user_id');
        $memberQuery = $memberQuery->select("members.*", "users.id", "users.email", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as fullname"));
        $memberQuery = $memberQuery->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
