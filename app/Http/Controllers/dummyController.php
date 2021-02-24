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

use Gate;
use DB;
use Auth;
use Config;

use Mail;
use App\Mail\CustomerSupport as CustomerSupportMail;
use App\Models\PhpSpreadsheetFontStyle as Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class dummyController extends Controller
{

    public function __construct()
    {
       //$this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        exit();

        date("Y-m-d H:i:s", strtotime($schedule->lesson_time ." - 30 minutes"));

        
        exit();
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
        
        
        echo "test";

        //@todo: get all member

        //@todo: get transaction that are expired for this member


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
