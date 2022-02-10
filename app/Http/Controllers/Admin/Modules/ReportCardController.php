<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ScheduleItem;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\Member;
use App\Models\UserImage;
use App\Models\Agent;
use App\Models\Tutor;

class ReportCardController extends Controller
{   

    /*
     Description: a link will show the report card in the tutor lesson plan  and will let the tutor report
    */
    public function index(Request $request) 
    {     

        $scheduleitemid = $request->scheduleitemid;

        $scheduleItem = ScheduleItem::find($scheduleitemid);
        
        //get member details        
        $memberInfo = Member::where('user_id',$scheduleItem->member_id)->first();       
        

        //get photo
        $userImageObj = new UserImage();
        $userImage = $userImageObj->getMemberPhoto($memberInfo);


        if (isset($scheduleItem->tutor_id)) {
            $tutorInfo = Tutor::where('user_id',  $scheduleItem->tutor_id)->first();
        } else {
            $tutorInfo = null;
        }

        $reportCard = ReportCard::where('schedule_item_id', $scheduleitemid)->first();


        //get latest report cards for faster input (requested by manager)
        $latestReportCard = null;
                
        if ($scheduleItem->member_id) {
            $latestReportCard = ReportCard::where('member_id', $scheduleItem->member_id)->orderBy('created_at', 'DESC')->first();            
        }



        return view('admin.modules.member.reportcard', compact('scheduleitemid', 'userImage', 'scheduleItem', 'reportCard', 'latestReportCard', 'memberInfo', 'tutorInfo'));
    }



    public function reportcardlist($memberID, Request $request) 
    {

        $memberInfo         = Member::where('user_id',$memberID)->first();

        if ($memberInfo) {
            $member             = $memberInfo->user;   
            //agent  
            $agentInfo          = Agent::where('user_id', $memberInfo->agent_id)->first();
            //tutor 
            $tutorInfo       = Tutor::where('user_id', $memberInfo->tutor_id)->first(); 

            //report cards
            $reportcards = ReportCard::where('member_id', $member->id)->orderBy('created_at', 'DESC')->paginate(30);

            return view('admin.modules.member.reportcardlist', compact('reportcards', 'agentInfo' ,'tutorInfo', 'member', 'memberInfo'));

        } else {

            abort(404);

        }
        

      
    }


    public function reportcarddatelist($memberID, Request $request) 
    {
        $memberInfo         = Member::where('user_id',$memberID)->first();
        
        if ($memberInfo) {
            $member             = $memberInfo->user;   
            //agent  
            $agentInfo          = Agent::where('user_id', $memberInfo->agent_id)->first();
            //tutor 
            $tutorInfo       = Tutor::where('user_id', $memberInfo->tutor_id)->first(); 

            $reportcards = ReportCardDate::where('member_id', $member->id)->orderBy('created_at', 'DESC')->paginate(30);

            return view('admin.modules.member.reportcarddatelist', compact('reportcards', 'member',  'agentInfo' ,'tutorInfo'));
        } else {

            abort(404);

        }
    }



    public function store(Request $request) 
    {

        $scheduleItem = ScheduleItem::find($request->scheduleitemid);

        $reportCard = ReportCard::where('schedule_item_id', $scheduleItem['id'])->first();
        
        if (isset($reportCard->schedule_item_id)) 
        {

            //update schedule status
            $scheduleData = [
                    'schedule_status' => 'COMPLETED'
            ];
            $scheduleItem->update($scheduleData);  

            
            //update report card
            $reportData = [
                'valid'                 => 1,
                'comment'               => $request->comment,
                'grade'                 => $request->grade,
                'schedule_item_id'      => $scheduleItem['id'],
                'course_category_id'    => null,
                'course_item_id'        => null,
                'lesson_course'         => $request->lessonCourse,
                'lesson_material'       => $request->lessonMaterial,                        
                'lesson_subject'        => $request->lessonSubject,
                'member_id'             => $scheduleItem['member_id'],
                'lesson_level'          => $request->lessonLevel,                        
            ];
            $reportCard->update($reportData);            

        } else {


            $scheduleData = [
                'schedule_status' => 'COMPLETED'
            ];
            $scheduleItem->update($scheduleData);  

            $reportData = [
                'valid'                 => 1,
                'comment'               => $request->comment,
                'grade'                 => $request->grade,
                'schedule_item_id'      => $scheduleItem['id'],
                'course_category_id'    => null,
                'course_item_id'        => null,
                'lesson_course'         => $request->lessonCourse,
                'lesson_material'       => $request->lessonMaterial,                        
                'lesson_subject'        => $request->lessonSubject,
                'member_id'             => $scheduleItem['member_id'],
                'lesson_level'          => $request->lessonLevel,                        
            ];

            ReportCard::create($reportData);
        }

        
        return redirect()->route('admin.lesson.index')->with('message', "Report card saved.");
        
    }

    public function show(ScheduleItem $cheduleItem) {

    }
}