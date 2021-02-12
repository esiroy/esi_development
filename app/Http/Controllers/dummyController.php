<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use App\Models\Member;
use App\Models\Shift;
use App\Models\Tutor;

use Gate;
use DB;
use Auth;
use Config;

use Mail;
use App\Mail\CustomerSupport as CustomerSupportMail;
use App\Models\ScheduleItem;
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
        echo "test";
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
