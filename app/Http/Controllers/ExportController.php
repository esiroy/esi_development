<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Response;

class ExportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function exportCSV()
    {
        
        $selected_array = array('I.D','Lastname','Firstname','Japanese','Email','Attribute','Age','Purpose');

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
