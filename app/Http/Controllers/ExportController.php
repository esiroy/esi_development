<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Models\AgentTransaction;
use App\Models\PhpSpreadsheetFontStyle as Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Response;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use DB;

class ExportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function exportExpiredXLS(Request $request) 
    {
        //Date Today
        $dateToday = date("m/d/Y");

        //EXPORT FILENAME
        $filename =  "MyPageSortedMemberList.xlsx";

        //SET STYLE
        $styleArrayH1 = Style::setHeader();
        $styleArrayH2 = Style::setHeader('FFFFFF','669999', 18);

        //Initialize
        $spreadsheet = Style::init();
        $sheet = $spreadsheet->getActiveSheet(); 

        //Set Header Text
        $sheet->setCellValue('B1', "Sorted Expired Member List as of $dateToday");     
       
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
        $spreadsheet->getActiveSheet()->getStyle('B2:G2')->getAlignment()->setHorizontal('center');

        //get to expired members        
        $today =   Carbon::now();
        $dateFrom = $request->get('from');
        $dateTo =   $request->get('to');       

        $memberQuery = Member::join('users', 'users.id', '=', 'members.user_id');
        $memberQuery = $memberQuery->select("members.*", "users.id", "users.email", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as fullname"));
        $memberQuery = $memberQuery->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));

        //$memberQuery = $memberQuery->whereDate('members.credits_expiration', '<', $today->toDateString());  //expired

        $memberQuery = $memberQuery->where('membership', "Point Balance");
        $memberQuery = $memberQuery->orderby('members.credits_expiration', 'ASC')->get();

        //Agent Credits Initialize
        $agenTransaction = new AgentTransaction;

        $ctr = 3;        
        foreach ($members as $member) 
        {            
            $credits = $agenTransaction->getCredits($member->user_id);
            if ($credits >= 1) {
                $spreadsheet->getActiveSheet()->getStyle('B'. $ctr.':G'.$ctr)->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('B'.$ctr, $member->user_id); //user id
                $sheet->setCellValue('C'.$ctr, $member->firstname);
                $sheet->setCellValue('D'.$ctr, $member->lastname);
                $sheet->setCellValue('E'.$ctr, $member->email);
                $sheet->setCellValue('F'.$ctr, $credits);
                $sheet->setCellValue('G'.$ctr, date("m-d-Y  h:i:s A", strtotime($member->credits_expiration)) );
                $ctr = $ctr + 1;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);        

        if(file_exists($filename)) {
            header('Content-Description: File Transfer');
            header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Content-Type: application/vnd.ms-excel');
            header('Expires: 0');
            header('Content-Length: ' . filesize($filename));
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush(); // Flush system output buffer
            readfile($filename);
            die();
        } else {
            http_response_code(404);
	        die();
        }
    }


    public function exportSoonToExpireXLS(Request $request) 
    {
        //Date Today
        $dateToday = date("m/d/Y");

        /*
        $dateFrom =   Carbon::now();
        $dateTo     = Carbon::now()->addDays(15); //expiring  15 days
        */


        //EXPORT FILENAME
        $filename =  "MyPageSortedMemberList.xlsx";

        //SET STYLE
        $styleArrayH1 = Style::setHeader();
        $styleArrayH2 = Style::setHeader('FFFFFF','669999', 18);

        //Initialize
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
        $spreadsheet->getActiveSheet()->getStyle('B2:G2')->getAlignment()->setHorizontal('center');

        //get to expired members        
        $today =   Carbon::now();
        $dateFrom = $request->get('from');
        $dateTo =   $request->get('to');       

        $memberQuery = Member::join('users', 'users.id', '=', 'members.user_id');
        $memberQuery = $memberQuery->select("members.*", "users.id", "users.email", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as fullname"));
        $memberQuery = $memberQuery->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));

        //$memberQuery = $memberQuery->whereDate('members.credits_expiration', '>', $today->toDateString());  //not expired

        $memberQuery = $memberQuery->where('membership', "Point Balance");

        //ORDERING
        $members = $memberQuery->orderby('members.credits_expiration', 'ASC')->get();

        //Agent Credits Initialize
        $agenTransaction = new AgentTransaction;

        $ctr = 3;        
        foreach ($members as $member) 
        {            
            //@get accumalted expired points
            $credits = $agenTransaction->getCredits($member->user_id);
            if ($credits >= 1) {
                $spreadsheet->getActiveSheet()->getStyle('B'. $ctr.':G'.$ctr)->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('B'.$ctr, $member->user_id); //user id
                $sheet->setCellValue('C'.$ctr, $member->firstname);
                $sheet->setCellValue('D'.$ctr, $member->lastname);
                $sheet->setCellValue('E'.$ctr, $member->email);
                $sheet->setCellValue('F'.$ctr, $credits);
                $sheet->setCellValue('G'.$ctr, date("m-d-Y  h:i:s A", strtotime($member->credits_expiration)) );
                $ctr = $ctr + 1;

                
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);        

        if(file_exists($filename)) {
            header('Content-Description: File Transfer');
            header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Content-Type: application/vnd.ms-excel');
            header('Expires: 0');
            header('Content-Length: ' . filesize($filename));
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush(); // Flush system output buffer
            readfile($filename);


            unlink($filename);
            
            die();
        } else {
            http_response_code(404);
	        die();
        }
        
        
    
    }


    
    public function exportSoonToExpireCSV($dateFrom, $dateTo) {

        $selected_array = array('I.D','Lastname','Firstname','Japanese','Email','Attribute','Age','Purpose');
        $Filename ='SortedMemberList.csv';

        $japanese_firstname = (isset($member->user->japanese_firstname)) ? $member->japanese_firstname : '';
        $japanese_lastname = (isset($member->user->japanese_lastname)) ? $member->japanese_lastname : '';

        $row['I.D'] = $member->user_id;
        $row['Lastname'] = (isset($member->user->lastname)) ? $member->user->lastname : '';
        $row['Firstname'] = (isset($member->user->firstname)) ? $member->user->firstname : '';
        $row['Japanese'] = $japanese_firstname . " " . $japanese_lastname;
        $row['Email'] = (isset($member->user->email)) ? $member->user->email : '';
        $row['Attribute'] = $member->attribute;
        $row['Age'] = (isset($member->age)) ? $member->age : '';
        $row['Purpose'] = (isset($member->purpose)) ? $member->purpose : '';

        $Array_data[] = $row;
                

        header('Content-Type: text/csv; charset=utf-8');
        Header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename='.$Filename.'');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fputcsv($output, $selected_array);
        foreach ($Array_data as $row){
            fputcsv($output, $row);
        }
        fclose($output);
    }

    public function exportCSV()
    {        
        $selected_array = array('I.D','Lastname','Firstname','Japanese','Email','Attribute','Age','Purpose');
        $members = Member::get();

        foreach ($members as $member) 
        {
            $japanese_firstname = (isset($member->user->japanese_firstname)) ? $member->japanese_firstname : '';
            $japanese_lastname = (isset($member->user->japanese_lastname)) ? $member->japanese_lastname : '';

            $row['I.D'] = $member->user_id;
            $row['Lastname'] = (isset($member->user->lastname)) ? $member->user->lastname : '';
            $row['Firstname'] = (isset($member->user->firstname)) ? $member->user->firstname : '';
            $row['Japanese'] = $japanese_firstname . " " . $japanese_lastname;
            $row['Email'] = (isset($member->user->email)) ? $member->user->email : '';
            $row['Attribute'] = $member->attribute;
            $row['Age'] = (isset($member->age)) ? $member->age : '';
            $row['Purpose'] = (isset($member->purpose)) ? $member->purpose : '';

            $Array_data[] = $row;
        }       

        $Filename ='memberlist.csv';

        header('Content-Type: text/csv; charset=utf-8');
        Header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename='.$Filename.'');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fputcsv($output, $selected_array);
        foreach ($Array_data as $row){
            fputcsv($output, $row);
        }
        fclose($output);
    }

   



}
