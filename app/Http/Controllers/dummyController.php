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

    public function testGetMembers() 
    {
        $members =  DB::table('members')->join('users', 'users.id', '=', 'members.user_id')
        ->select('members.id', 'members.user_id', 'members.nickname', 'users.firstname', 'users.lastname', 'users.valid')
        ->where('users.valid', 1)
        ->get();

        $members = json_encode($members);


        echo "<pre>";
        print_r ($members);
        echo "</pre>";



        //echo (microtime(true));

        list($usec, $sec) = explode(' ', microtime()); //split the microtime on space
        //with two tokens $usec and $sec

        $usec = str_replace("0.", ".", $usec);     //remove the leading '0.' from usec

        print date('H:i:s', $sec) . $usec;       //appends the decimal portion of seconds        

    }

    public function index( LessonMailer $lessonMailer){
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $memberInfo = $memberObj->where('user_id',20372)->first();
        $tutorInfo = $tutorObj->where('user_id', 21809)->first();
        $scheduleItem = $scheduleItem->find(879884);
        
        
        $lessonMailer->send($memberInfo, $tutorInfo, $scheduleItem);

        dd('Send Email Successfully');

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
        $details['template'] = "emails.lesson.reserved";

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

        return view('emails.lesson.tutorNotifyReserved', compact('member','tutor', 'scheduleItem'));



        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);
        dispatch($job);        


        dd('Send Email Successfully');
        //return view('emails.lesson.reserved', compact('member','tutor', 'scheduleItem'));
    }


    public function testMailCancelled()
    {        //*** TEMPLATE ***/
        $details['template'] = "emails.lesson.memberNotifyCancelled";

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

        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);

        dispatch($job);        
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
