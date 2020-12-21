<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lesson;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Shift;
use App\Models\Member;
use App\Models\Status;
use App\Models\ReportCard;
use App\Models\ReportCardDate;

use Gate;
use Validator;
use Input;
use DB;
use Auth;

class LessonRecordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //lessonRecords landing page
    public function index() 
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        if (isset($member)) 
        {
            $memberData = Member::find($member->id);
            $skypeID    = $memberData->communication_app_username; 
            $tutorData = Tutor::find($member->main_tutor_id);

            $lecturer   = (isset($tutorData->name_en))? $tutorData->name_en : '';

            $data = [
                'lecturer'  => $lecturer,
                'skypeID'   => $skypeID,            
            ];  
            
        }

        $reportcards = ReportCard::orderBy('created_at', 'DESC')->paginate(30,['*'], 'reportcards');
        //$reportcards->setPageName('reportcards');
  
        
        $datereportcards = ReportCardDate::orderBy('created_at', 'DESC')->paginate(1,['*'], 'datereportcards');
        //$datereportcards->setPageName('ReportCardDate');

        

        $latestReportCard = ReportCard::OrderBy('created_at', 'DESC')->first();



        
        return view('modules.lessonrecord.index', compact('member', 'data', 'reportcards', 'datereportcards', 'latestReportCard'));
    }



    public function reportcarddatelist($reportcardid, Request $request) {

        //$member = Member::find( $member_id);

        $reportcards = ReportCardDate::where('id', $reportcardid)->orderBy('created_at', 'DESC')->paginate(30);

        $latestReportCard = ReportCard::OrderBy('created_at', 'DESC')->first();


        return view('modules.lessonrecord.reportcarddatelist', compact('reportcards', 'latestReportCard'));
    }



    public function reportcard($reportcardid) 
    {
        

        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        if (isset($member)) 
        {
            $memberData = Member::find($member->id);
            $skypeID    = $memberData->communication_app_username; 
            $tutorData = Tutor::find($member->main_tutor_id);

            $lecturer   = (isset($tutorData->name_en))? $tutorData->name_en : '';

            $data = [
                'lecturer'  => $lecturer,
                'skypeID'   => $skypeID,            
            ];  
            
        }


        $reportcard = ReportCard::where('id', $reportcardid)->first();

        $latestReportCard = ReportCard::OrderBy('created_at', 'DESC')->first();


        return view('modules.lessonrecord.reportcard', compact('reportcard', 'member', 'data', 'latestReportCard'));

    }

 

    public function userreportcarddate($reportcardid, Request $request) {

        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        if (isset($member)) 
        {
            $memberData = Member::find($member->id);
            $skypeID    = $memberData->communication_app_username; 
            $tutorData = Tutor::find($member->main_tutor_id);

            $lecturer   = (isset($tutorData->name_en))? $tutorData->name_en : '';

            $data = [
                'lecturer'  => $lecturer,
                'skypeID'   => $skypeID,            
            ];  
            
        }


        $userreportcard = ReportCardDate::where('id', $reportcardid)->first();

        return view('modules.lessonrecord.userreportcarddate', compact('userreportcard', 'member', 'data'));
    }


}
