<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lesson;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Shift;
use App\Models\Member;
use App\Models\Status;
use App\Models\Questionnaire;
use App\Models\ScheduleItem;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\Homework;
use App\Models\MiniTestResult;

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
    public function index(Request $request, ReportCard $reportcards) 
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
            
            $datereportcards = ReportCardDate::where('member_id', $member->user_id)->orderBy('created_at', 'DESC')->paginate(Auth::user()->items_per_page,['*'], 'datereportcards');    
            $latestReportCard = $reportcards->getLatest($member->user_id);

            
        

            


            if ($request->display == 'none') 
            {
                //@todo: (update to model)
                $reportcards = Questionnaire::where('member_id',  $member->user_id)
                                ->orderBy('created_at', 'DESC')
                                ->where('valid', true)
                                ->paginate(Auth::user()->items_per_page,['*'], 'reportcards');
                
                //@todo: (update to model)
                $scheduleItems = ScheduleItem::where('member_id',  $member->user_id)
                            ->where('schedule_status', '!=', 'TUTOR_CANCELLED')
                            //->where('valid', true)
                            ->orderBy('lesson_time', 'DESC')
                            ->paginate(Auth::user()->items_per_page, ['*'], 'reportcards');

                return view('modules.questionnaire.index', compact('member', 'data', 'scheduleItems', 'reportcards', 'datereportcards', 'latestReportCard'));

            } else {

                $reportcards = ReportCard::where('member_id',  $member->user_id)->orderBy('created_at', 'DESC')->paginate(Auth::user()->items_per_page,['*'], 'reportcards');

                $scheduleItems = ScheduleItem::where('member_id',  $member->user_id)
                                ->where('schedule_status', '!=', 'TUTOR_CANCELLED')
                                //->where('valid', true)
                                ->orderBy('lesson_time', 'DESC')                                
                                ->paginate(Auth::user()->items_per_page, ['*'], 'reportcards');   

                $miniTestResults = MiniTestResult::select('question_categories.name', 'member_test_results.*')
                                    ->leftJoin('question_categories', 'question_categories.id', '=', 'member_test_results.question_category_id')                        
                                    ->where('member_test_results.user_id', $member->user_id)
                                    ->where('member_test_results.valid', true)
                                    ->orderBy('member_test_results.time_started', 'DESC')             
                                    ->paginate(Auth::user()->items_per_page, ['*'], 'minitest');

                return view('modules.lessonrecord.index', compact('member', 'data', 'reportcards', 'scheduleItems', 'datereportcards', 'latestReportCard', 'miniTestResults'));
            }
        } else {
            abort(404);
        }    
    }


    
    public function reportcarddatelist($reportcardid, Request $request, ReportCard $reportcards) 
    {
        $user = Auth::user();        
        $member = Member::where('user_id', $user->id)->first();
        
        $reportcards = ReportCardDate::where('id', $reportcardid)
                        ->where('valid', true)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(30);

        $latestReportCard = $reportcards->getLatest($member->user_id);

        return view('modules.lessonrecord.reportcarddatelist', compact('reportcards', 'latestReportCard'));
    }



    public function reportcard($reportcardid, ReportCard $reportcards) 
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

        if ($reportcard) 
        {
           
            $latestReportCard = $reportcards->getLatest($member->user_id);           

            //(get  list of home work )
            $homework = Homework::where('schedule_item_id', $reportcard->schedule_item_id)->first();

            return view('modules.lessonrecord.reportcard', compact('reportcard', 'member', 'data', 'latestReportCard', 'homework'));
        } else {
            abort(404);
        }
    }

 

    public function userreportcarddate($reportcardid, ReportCard $reportcards, Request $request) {

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

            $latestReportCard = $reportcards->getLatest($member->user_id);

        }

        $userreportcard = ReportCardDate::where('id', $reportcardid)->first();

        if ($userreportcard) {
            return view('modules.lessonrecord.userreportcarddate', compact('userreportcard', 'member', 'latestReportCard', 'data'));
        } else {
            abort(404);
        }
        
    }


}
