<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ScheduleItem;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\Member;
use App\Models\UserImage;


class ReportCardController extends Controller
{   

    /*
     Description: a link will show the report card in the tutor lesson plan  and will let the tutor report
    */
    
    public function index(Request $request) 
    {
        $scheduleitemid = $request->scheduleitemid;

        $scheduleItem = ScheduleItem::find($scheduleitemid);

        $reportCard = ReportCard::where('schedule_item_id', $scheduleitemid)->first();

        //get member details        
        $member = Member::find( $reportCard->member_id);

        //get User image
        $userImage = UserImage::where('user_id', $member->user_id)->first();


        return view('admin.modules.member.reportcard', compact('scheduleitemid', 'userImage', 'scheduleItem', 'reportCard', 'member'));
    }



    public function reportcardlist($member_id, Request $request) {

        $member = Member::find( $member_id);

        $reportcards = ReportCard::where('member_id', $member->id)->orderBy('created_at', 'DESC')->paginate(30);

        

        return view('admin.modules.member.reportcardlist', compact('reportcards', 'member'));
    }


    public function reportcarddatelist($member_id, Request $request) {

        
        $member = Member::find( $member_id);

        $reportcards = ReportCardDate::where('member_id', $member->id)->orderBy('created_at', 'DESC')->paginate(30);

        return view('admin.modules.member.reportcarddatelist', compact('reportcards', 'member'));
    }



    public function store(Request $request) 
    {
        $scheduleItem = ScheduleItem::find($request->scheduleitemid);

        $reportCard = ReportCard::where('schedule_item_id', $scheduleItem['id'])->first();

        if (isset($reportCard->schedule_item_id)) 
        {
            //update

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