<?php

namespace App\Http\Controllers;

use App\Models\AgentTransaction;
use App\Models\Member;
use App\Models\PhpSpreadsheetFontStyle as Style;
use App\Models\ScheduleItem;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\Agent;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //@note: Export Only with Point Balance (Membership)
    public function exportExpiredXLS(Request $request)
    {
        set_time_limit(0);
        
        //Date Today
        $dateToday = date("m/d/Y");

        //EXPORT FILENAME
        $filename = "MyPageSortedMemberList.xlsx";

        //SET STYLE
        $styleArrayH1 = Style::setHeader();
        $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 18);

        //Initialize
        $spreadsheet = Style::init();
        $sheet = $spreadsheet->getActiveSheet();

        //Set Header Text
        $sheet->setCellValue('B1', "Sorted Expired Member List as of $dateToday");

        //Secondary Field Headers (h2)
        $sheet->setCellValue('B2', "I.D");
        $sheet->setCellValue('C2', "First Name");
        $sheet->setCellValue('D2', "Last Name");
        $sheet->setCellValue('E2', "Japanese First Name");
        $sheet->setCellValue('F2', "Japanese Last Name");
        $sheet->setCellValue('G2', "E-Mail");
        $sheet->setCellValue('H2', "Attribute");
        $sheet->setCellValue('I2', "Credits");
        $sheet->setCellValue('J2', "Expiration Date");

        //style for field headers h2
        $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 20);
        $spreadsheet->getActiveSheet()->getStyle('B2:I2')->applyFromArray($styleArrayH2);
        $spreadsheet->getActiveSheet()->getStyle('B2:I2')->getAlignment()->setHorizontal('center');
      

        //get to expired members
        //$today = Carbon::now();
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

        //Agent Credits Initialize
        $agenTransaction = new AgentTransaction;

        $ctr = 3;
        foreach ($memberQueryAll as $memberItem) 
        {
            $member = Member::where('user_id', $memberItem['user_id'])->first();

            $credits = $agenTransaction->getExpiredCredits($memberItem['user_id']);
            if ($credits >= 1) {
                $spreadsheet->getActiveSheet()->getStyle('B' . $ctr . ':I' . $ctr)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('B' . $ctr, $member->user->id); //user id
                $sheet->setCellValue('C' . $ctr, $member->user->firstname);
                $sheet->setCellValue('D' . $ctr, $member->user->lastname);                
                $sheet->setCellValue('E' . $ctr, $member->user->japanese_firstname);
                $sheet->setCellValue('F' . $ctr, $member->user->japanese_lastname);
                $sheet->setCellValue('G' . $ctr, $member->user->email);
                $sheet->setCellValue('H' . $ctr, $member->attribute);
                $sheet->setCellValue('I' . $ctr, $credits);
                if (isset($member->credits_expiration)) {
                    $sheet->setCellValue('J' . $ctr, date("m-d-Y  h:i:s A", strtotime($member->credits_expiration)));
                } else {
                    $sheet->setCellValue('J' . $ctr, date("m-d-Y  h:i:s A", strtotime($memberItem['created_at'])));
                }
                
                $ctr = $ctr + 1;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
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

    public function exportSoonToExpireXLS(Request $request)
    {
        //Date Today
        $dateToday = date("m/d/Y");

        /*
        $dateFrom =   Carbon::now();
        $dateTo     = Carbon::now()->addDays(15); //expiring  15 days
         */

        //EXPORT FILENAME
        $filename = "MyPageSortedMemberList.xlsx";

        //SET STYLE
        $styleArrayH1 = Style::setHeader();
        $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 18);

        //Initialize
        $spreadsheet = Style::init();
        $sheet = $spreadsheet->getActiveSheet();

        //Set Header Text
        $sheet->setCellValue('B1', "Sorted Member List as of $dateToday");

        //Secondary Field Headers (h2)
        $sheet->setCellValue('B2', "I.D");
        $sheet->setCellValue('C2', "First Name");
        $sheet->setCellValue('D2', "Last Name");

        $sheet->setCellValue('E2', "Japanese First Name");
        $sheet->setCellValue('F2', "Japanese Last Name");

        $sheet->setCellValue('G2', "E-Mail");
        $sheet->setCellValue('H2', "Credits");
        $sheet->setCellValue('I2', "Expiration Date");

        //style for field headers h2
        $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 20);
        $spreadsheet->getActiveSheet()->getStyle('B2:I2')->applyFromArray($styleArrayH2);
        $spreadsheet->getActiveSheet()->getStyle('B2:I2')->getAlignment()->setHorizontal('center');

        //get to expired members
        $today = Carbon::now();
        $dateFrom = $request->get('from');
        $dateTo = $request->get('to');

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
        foreach ($members as $member) {
            //@get accumalted expired points
            $credits = $agenTransaction->getCredits($member->user_id);
            if ($credits >= 1) {
                $spreadsheet->getActiveSheet()->getStyle('B' . $ctr . ':I' . $ctr)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('B' . $ctr, $member->user_id); //user id
                $sheet->setCellValue('C' . $ctr, $member->firstname);
                $sheet->setCellValue('D' . $ctr, $member->lastname);

                $sheet->setCellValue('E' . $ctr, $member->user->japanese_firstname);
                $sheet->setCellValue('F' . $ctr, $member->user->japanese_lastname);

                $sheet->setCellValue('G' . $ctr, $member->email);
                $sheet->setCellValue('H' . $ctr, $credits);
                $sheet->setCellValue('I' . $ctr, date("m-d-Y  h:i:s A", strtotime($member->credits_expiration)));
                $ctr = $ctr + 1;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
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

    public function exportSoonToExpireCSV($dateFrom, $dateTo)
    {

        $selected_array = array('I.D', 'Lastname', 'Firstname', 'Japanese', 'Email', 'Attribute', 'Age', 'Purpose');
        $Filename = 'SortedMemberList.csv';

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
        header('Content-Disposition: attachment; filename=' . $Filename . '');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fputcsv($output, $selected_array);
        foreach ($Array_data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    }


    public function exportCSV()
    {            
        $filename = "memberlist.xlsx";
        //SET STYLE
        $styleArrayH1 = Style::setHeader();
        $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 18);
        //Initialize
        $spreadsheet = Style::init();
        $sheet = $spreadsheet->getActiveSheet();
        //Set Header Text
        $sheet->setCellValue('B1', "Member List");
        //Secondary Field Headers (h2)
        $sheet->setCellValue('B2', "I.D");
        $sheet->setCellValue('C2', "First Name");
        $sheet->setCellValue('D2', "Last Name");
        $sheet->setCellValue('E2', "Japanese First Name");
        $sheet->setCellValue('F2', "Japanese Last Name");
        $sheet->setCellValue('G2', "E-Mail");
        $sheet->setCellValue('H2', "Attribute");
        $sheet->setCellValue('I2', "Age");
        $sheet->setCellValue('J2', "Purpose");
        //style for field headers h2
        $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 20);
        $spreadsheet->getActiveSheet()->getStyle('B2:I2')->applyFromArray($styleArrayH2);
        $spreadsheet->getActiveSheet()->getStyle('B2:J2')->getAlignment()->setHorizontal('center');

        //GET MEMBER AND ORDERING
        $members = Member::orderby('members.user_id', 'ASC')->get();
        $ctr = 3;



        foreach ($members as $member) 
        {  

            $jp_firstname = isset($member->user->japanese_lastname)? $member->user->japanese_lastname : '';
            $jp_lastname = isset($member->user->japanese_firstname)? $member->user->japanese_firstname : '';

            $spreadsheet->getActiveSheet()->getStyle('B' . $ctr . ':J' . $ctr)->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('B' . $ctr, $member->user_id); //user id
            $sheet->setCellValue('C' . $ctr, isset($member->user->firstname)? $member->user->firstname : '');
            $sheet->setCellValue('D' . $ctr, isset($member->user->lastname)? $member->user->lastname : '');
            $sheet->setCellValue('E' . $ctr, $jp_firstname );
            $sheet->setCellValue('F' . $ctr, $jp_lastname);
            $sheet->setCellValue('G' . $ctr, isset($member->user->email)? $member->user->email : '');
            $sheet->setCellValue('H' . $ctr, isset($member->attribute)? $member->attribute : '');
            $sheet->setCellValue('I' . $ctr, isset($member->age)? $member->age : '');
            $sheet->setCellValue('J' . $ctr, (isset($member->purpose)) ? $member->purpose : '' );
            $ctr = $ctr + 1;            
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
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
    //Member Export To CSV
    /*
    public function exportCSVOLD()
    {
        $selected_array = array('I.D', 'Lastname', 'Firstname', 'Japanese', 'Email', 'Attribute', 'Age', 'Purpose');
        $members = Member::get();

        foreach ($members as $member) {
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

        $Filename = 'memberlist.csv';

        header('Content-Type: text/csv; charset=utf-8');
        Header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename=' . $Filename . '');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        fputcsv($output, $selected_array);
        foreach ($Array_data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    }*/

    public function downloadlessonReport(Request $request)
    {
        //initiatte schedule for reporting
        $schedules = new ScheduleItem();

        $status = $request->status;

        if (isset($request->dateFrom) && isset($request->dateTo)) 
        {
            $dateFrom = date('Y-m-d', strtotime($request['dateFrom']));
            $dateTo = date('Y-m-d', strtotime($request['dateTo']));
            $extendedTo = date('Y-m-d', strtotime($dateTo . " +1 day"));
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom . " 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");
        } else {
            //Current date
            $dateFrom = date("Y-m-d");            
            $dateTo = date('Y-m-d', strtotime($dateFrom . " +1 day"));
            $extendedTo = date('Y-m-d', strtotime($dateFrom . " +2 day"));
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom ." 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");                
        }

        if (isset($request->tutorid)) {
            $schedules = $schedules->where('tutor_id', $request->tutorid);
        }

        if (isset($request->status)) {
            $status = str_replace(' ', '_', strtoupper($request->status));
            $schedules = $schedules->where('schedule_status', $status);
        }

        //no request paramters
        if (!isset($request->dateFrom) && !isset($request->dateTo) && !isset($request->status) && (!isset($request->tutorid))) {
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom . " 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");
        }

        //valid only
        $schedules = $schedules->where('valid', true);
        $schedules = $schedules->orderBy('lesson_time', 'DESC')->orderBy('id', 'DESC');
        $schedules = $schedules->get();

        //Date Today
        $dateToday = date("m/d/Y");

        set_time_limit(0);

        //lesson report to PDF
        if (strtolower($request->type) == 'pdf') 
        {
            foreach ($schedules as $schedule) {

                $memberName = "-";
                $tutorName = "-";
                $shiftValue = "";
                $salary = "";
                $salaryRate = "";
                $agentName = "";

                $member = Member::where('user_id', $schedule->member_id)->first();
                if ($member) {
                    $memberName = $member->user->firstname ." ". $member->user->lastname;
                }

                $agent = new Agent();
                $agentInfo = $agent->getMemberAgent($schedule->member_id);

                if ($agentInfo) {
                    $agentName = $agentInfo->user->firstname ." ". $agentInfo->user->lastname;
                }
          
                $tutor = Tutor::where('user_id', $schedule->tutor_id)->first();
                if ($tutor) {
                    if (isset($tutor->user->firstname)) {
                        $tutorName = $tutor->user->firstname; //english name
                    } else { 
                        $tutorName = $tutor->user->japanese_firstname;      //fall back the japanese name
                    }
                } else {
                    $tutorName = "-";
                }

                $shift = Shift::where('id', $schedule->lesson_shift_id)->first();
                if ($shift) {
                    $shiftValue = $shift->name;
                }

                //date
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time . " -1 day"));
                } else {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time));
                }

                //Time
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleTime = date("24:i", strtotime($schedule->lesson_time)) . " - " . date("24:i", strtotime($schedule->lesson_time . " +25 minutes"));
                } else {
                    $scheduleTime = date("H:i", strtotime($schedule->lesson_time)) . " - " . date("H:i", strtotime($schedule->lesson_time . " +25 minutes"));
                }

                $status = ucwords(str_replace("_", " ", strtolower($schedule->schedule_status)));
                
                $schedulesData[] = [
                    'id' => $schedule->id,
                    'date' => $scheduleDate,
                    'time' => $scheduleTime,
                    'status'=> $status,
                    'shift'=> $shiftValue,
                    'agent' => $agentName,
                    'tutor'=> $tutorName,                    
                    'member'=> $memberName,                    
                ];
            }

            $dateToday = date("F j, Y", strtotime($dateToday));
            $dateFrom = date("F j, Y", strtotime($dateFrom));
            $dateTo = date("F j, Y", strtotime($dateTo));

            $pdf = PDF::loadView('pdf.lessonReport', compact('schedulesData', 'dateToday', 'dateFrom', 'dateTo'));
            // Finally, you can download the file using download function
            return $pdf->download('Lesson Report.pdf');
            exit();


        } elseif (strtolower($request->type) == 'excel') {
            
            //lesson report to PDF

            //EXPORT LESSON REPORT FILENAME
            $filename = "Lesson Report.xlsx";

            //SET STYLE
            $styleArrayH1 = Style::setHeader();
            $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 18);

            //Initialize
            $spreadsheet = Style::init_report();
            $sheet = $spreadsheet->getActiveSheet();

            //Set Header Text
            $sheet->setCellValue('A1', "Tutor Lesson Report from $request->dateFrom to $request->dateTo");

            //Secondary Field Headers (h2)
            $sheet->setCellValue('A2', "I.D");
            $sheet->setCellValue('B2', "Date");
            $sheet->setCellValue('C2', "Time");
            $sheet->setCellValue('D2', "Status");
            $sheet->setCellValue('E2', "Shift");
            $sheet->setCellValue('F2', "Agent");
            $sheet->setCellValue('G2', "Tutor");
            $sheet->setCellValue('H2', "Member");

            //style for field headers h2
            $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 20);
            $spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArrayH1);
            $spreadsheet->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleArrayH2);
            $spreadsheet->getActiveSheet()->getStyle('A1:G2')->getAlignment()->setHorizontal('center');

            //get to expired members
            $today = Carbon::now();
            $dateFrom = $request->get('dateFrom');
            $dateTo = $request->get('dateTo');

            //Agent Credits Initialize
            $agenTransaction = new AgentTransaction;

            $ctr = 3;
            foreach ($schedules as $schedule) {
                
                $memberName = "-";
                $tutorName = "-";
                $shiftValue = "";
                $salary = "";
                $salaryRate = "";
                $agentName = "";

                $member = Member::where('user_id', $schedule->member_id)->first();
                if ($member) {
                    $memberName = $member->user->firstname ." ". $member->user->lastname;
                }

                $agent = new Agent();
                $agentInfo = $agent->getMemberAgent($schedule->member_id);

                if ($agentInfo) {
                    $agentName = $agentInfo->user->firstname ." ". $agentInfo->user->lastname;
                }
          
                $tutor = Tutor::where('user_id', $schedule->tutor_id)->first();
                if ($tutor) {
                    if (isset($tutor->user->firstname)) {
                        $tutorName = $tutor->user->firstname; //english name
                    } else { 
                        $tutorName = $tutor->user->japanese_firstname;      //fall back the japanese name
                    }
                } else {
                    $tutorName = "-";
                }

                $shift = Shift::where('id', $schedule->lesson_shift_id)->first();
                if ($shift) {
                    $shiftValue = $shift->name;
                }

                //date
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time . " -1 day"));
                } else {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time));
                }

                //Time
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleTime = date("24:i", strtotime($schedule->lesson_time)) . " - " . date("24:i", strtotime($schedule->lesson_time . " +25 minutes"));
                } else {
                    $scheduleTime = date("H:i", strtotime($schedule->lesson_time)) . " - " . date("H:i", strtotime($schedule->lesson_time . " +25 minutes"));
                }

                $status = ucwords(str_replace("_", " ", strtolower($schedule->schedule_status)));

                $spreadsheet->getActiveSheet()->getStyle('A' . $ctr . ':I' . $ctr)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A' . $ctr, $schedule->id); //user id
                $sheet->setCellValue('B' . $ctr, $scheduleDate);  //tutorName
                $sheet->setCellValue('C' . $ctr, $scheduleTime);
                $sheet->setCellValue('D' . $ctr, $status);
                $sheet->setCellValue('E' . $ctr, $shiftValue);
                $sheet->setCellValue('F' . $ctr, $agentName);
                $sheet->setCellValue('G' . $ctr, $tutorName);
                $sheet->setCellValue('H' . $ctr, $memberName);
                $ctr = $ctr + 1;

            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);

            if (file_exists($filename)) {
                header('Content-Description: File Transfer');
                header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
                header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
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
    }    

    public function downloadSalaryReport(Request $request)
    {
        //initiatte schedule for reporting
        $schedules = new ScheduleItem();

        $status = $request->status;

        if (isset($request->dateFrom) && isset($request->dateTo)) {
            $dateFrom = date('Y-m-d', strtotime($request['dateFrom']));
            $dateTo = date('Y-m-d', strtotime($request['dateTo']));
            $extendedTo = date('Y-m-d', strtotime($dateTo . " +1 day"));
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom . " 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");
        } else {
            //Current date
            $dateFrom = date("Y-m-d");            
            $dateTo = date('Y-m-d', strtotime($dateFrom . " +1 day"));
            $extendedTo = date('Y-m-d', strtotime($dateFrom . " +2 day"));
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom ." 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");
        }

        if (isset($request->tutorid)) {
            $schedules = $schedules->where('tutor_id', $request->tutorid);
        }

        if (isset($request->status)) {
            $status = str_replace(' ', '_', strtoupper($request->status));
            $schedules = $schedules->where('schedule_status', $status);
        }

        //no request paramters
        if (!isset($request->dateFrom) && !isset($request->dateTo) && !isset($request->status) && (!isset($request->tutorid))) {
            $schedules = $schedules->where('lesson_time', '>=', $dateFrom . " 01:00:00")->where('lesson_time', '<=', $extendedTo . " 00:30:00");
        }

        //valid only
        $schedules = $schedules->where('valid', true);
        $schedules = $schedules->orderBy('lesson_time', 'DESC')->orderBy('id', 'DESC');
        $schedules = $schedules->get();

        //Date Today
        $dateToday = date("m/d/Y");

        set_time_limit(0);

        if (strtolower($request->type) == 'pdf') 
        {
            foreach ($schedules as $schedule) {

                $tutorName = "-";
                $shiftValue = "";
                $salary = "";
                $salaryRate = "";

                $tutor = Tutor::where('user_id', $schedule->tutor_id)->first();
                if ($tutor) {
                    if (isset($tutor->user->firstname)) {
                        $tutorName = $tutor->user->firstname;
                    } else {
                        $tutorName = $tutor->user->japanese_firstname;
                    }
                } else {
                    $tutorName = "-";
                }
    
                $shift = Shift::where('id', $schedule->lesson_shift_id)->first();
                if ($shift) {
                    $shiftValue = $shift->name;
                }            
    
                //date
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time . " -1 day"));
                } else {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time));
                }
    
                //Time
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleTime = date("24:i", strtotime($schedule->lesson_time)) . " - " . date("24:i", strtotime($schedule->lesson_time . " +25 minutes"));
                } else {
                    $scheduleTime = date("H:i", strtotime($schedule->lesson_time)) . " - " . date("H:i", strtotime($schedule->lesson_time . " +25 minutes"));
                }
    
                $status = ucwords(str_replace("_", " ", strtolower($schedule->schedule_status)));
    
                if (isset($tutor->salary_rate)) {
                    $salary = number_format($tutor->salary_rate, 1);
                }
    
                if (isset($tutor->salary_rate)) {
                    if ($schedule->schedule_status == "COMPLETED" || $schedule->schedule_status == "CLIENT_NOT_AVAILABLE") {
                        $cost = number_format($tutor->salary_rate, 1);
                    } elseif ($schedule->schedule_status == "SUPPRESSED_SCHEDULE") {
                        $newSalary = $tutor->salary_rate / 2;
                        $cost = number_format($newSalary, 1);
                    } else {
                        $cost = number_format(0, 1);
                    }
                } else {
                    $cost = number_format(0, 1);
                }
                
                
                $schedulesData[] = [
                    'id' => $schedule->id,
                    'tutor'=> $tutorName,
                    'shift'=> $shiftValue,
                    'date '=> $scheduleDate,
                    'time '=> $scheduleTime,
                    'status'=> $status,
                    'salary'=> $salary,
                    'cost '=> $cost
                ];
            }

            $dateToday = date("F j, Y", strtotime($dateToday));
            $dateFrom = date("F j, Y", strtotime($dateFrom));
            $dateTo = date("F j, Y", strtotime($dateTo));

            $pdf = PDF::loadView('pdf.salaryReport', compact('schedulesData', 'dateToday', 'dateFrom', 'dateTo'));
            // Finally, you can download the file using download function
            return $pdf->download('Salary Report.pdf');
            exit();


        } elseif (strtolower($request->type) == 'excel') {

            //EXPORT FILENAME
            $filename = "salaryReport.xlsx";

            //SET STYLE
            $styleArrayH1 = Style::setHeader();
            $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 18);

            //Initialize
            $spreadsheet = Style::init_report();
            $sheet = $spreadsheet->getActiveSheet();

            //Set Header Text
            $sheet->setCellValue('A1', "Tutor Salary Report from $request->dateFrom to $request->dateTo");

            //Secondary Field Headers (h2)
            $sheet->setCellValue('A2', "I.D");
            $sheet->setCellValue('B2', "Tutor");
            $sheet->setCellValue('C2', "Shift");
            $sheet->setCellValue('D2', "Date");
            $sheet->setCellValue('E2', "Time");
            $sheet->setCellValue('F2', "Status");
            $sheet->setCellValue('G2', "Salary");
            $sheet->setCellValue('H2', "Cost");

            //style for field headers h2
            $styleArrayH2 = Style::setHeader('FFFFFF', '669999', 20);
            $spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArrayH1);
            $spreadsheet->getActiveSheet()->getStyle('A2:H2')->applyFromArray($styleArrayH2);
            $spreadsheet->getActiveSheet()->getStyle('A1:G2')->getAlignment()->setHorizontal('center');

            //get to expired members
            $today = Carbon::now();
            $dateFrom = $request->get('dateFrom');
            $dateTo = $request->get('dateTo');

            //Agent Credits Initialize
            $agenTransaction = new AgentTransaction;

            $ctr = 3;
            foreach ($schedules as $schedule) {
                //TUTOR NAME
                $tutorName = "-";
                $shiftValue = "";
                $salary = "";
                $salaryRate = "";

                $tutor = Tutor::where('user_id', $schedule->tutor_id)->first();
                if ($tutor) {
                    if (isset($tutor->user->firstname)) {
                        $tutorName = $tutor->user->firstname;
                    } else {
                        $tutorName = $tutor->user->japanese_firstname;
                    }
                } else {
                    $tutorName = "-";
                }

                $shift = Shift::where('id', $schedule->lesson_shift_id)->first();
                if ($shift) {
                    $shiftValue = $shift->name;
                }

                //date
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time . " -1 day"));
                } else {
                    $scheduleDate = date("F j, Y", strtotime($schedule->lesson_time));
                }

                //Time
                if (date("H", strtotime($schedule->lesson_time)) == "00") {
                    $scheduleTime = date("24:i", strtotime($schedule->lesson_time)) . " - " . date("24:i", strtotime($schedule->lesson_time . " +25 minutes"));
                } else {
                    $scheduleTime = date("H:i", strtotime($schedule->lesson_time)) . " - " . date("H:i", strtotime($schedule->lesson_time . " +25 minutes"));
                }

                $status = ucwords(str_replace("_", " ", strtolower($schedule->schedule_status)));

                if (isset($tutor->salary_rate)) {
                    $salary = $tutor->salary_rate;
                }

                if (isset($tutor->salary_rate)) {
                    if ($schedule->schedule_status == "COMPLETED" || $schedule->schedule_status == "CLIENT_NOT_AVAILABLE") {
                        $cost = number_format($tutor->salary_rate, 1);
                    } elseif ($schedule->schedule_status == "SUPPRESSED_SCHEDULE") {
                        $newSalary = $tutor->salary_rate / 2;
                        $cost = number_format($newSalary, 1);
                    } else {
                        $cost = number_format(0, 1);
                    }
                } else {
                    $cost = number_format(0, 1);
                }

                $spreadsheet->getActiveSheet()->getStyle('A' . $ctr . ':I' . $ctr)->getAlignment()->setHorizontal('center');
                $sheet->setCellValue('A' . $ctr, $schedule->id); //user id
                $sheet->setCellValue('B' . $ctr, $tutorName);
                $sheet->setCellValue('C' . $ctr, $shiftValue);
                $sheet->setCellValue('D' . $ctr, $scheduleDate);
                $sheet->setCellValue('E' . $ctr, $scheduleTime);
                $sheet->setCellValue('F' . $ctr, $status);
                $sheet->setCellValue('G' . $ctr, $salary);
                $sheet->setCellValue('H' . $ctr, $cost);
                $ctr = $ctr + 1;

            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);

            if (file_exists($filename)) {
                header('Content-Description: File Transfer');
                header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
                header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
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
    }

}
