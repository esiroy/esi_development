<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ScheduleItem;
use App\Models\ReportCardDate;
use App\Models\Member;
use App\Models\Tutor;
use App\Models\UserImage;

use Auth;

class ReportCardDateController extends Controller
{
    public function show ($id, Request $request) 
    {
        //get member details        
        $memberInfo = Member::where('user_id', $id)->first();

        //get photo
        $userImageObj = new UserImage();
        $userImage = $userImageObj->getMemberPhoto($memberInfo);  

        if (isset($memberInfo->tutor_id)) {
            $tutorInfo = Tutor::where('user_id',  $memberInfo->tutor_id)->first();
        } else {
            $tutorInfo = null;
        }

        $reportCardDate = ReportCardDate::where('member_id', $id)->first();

        return view('admin.modules.member.reportcarddate', compact('memberInfo', 'userImage', 'tutorInfo'));
    }

    public function store(Request $request) 
    {
        if ($files = $request->file('file')) {

            //file path
            $storagePath = 'storage/uploads/';
            $newFilename = time()."_". preg_replace('/\s+/', '_', $files->getClientOriginalName());
            $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);            
            // Remove any runs of periods (thanks falstro!)
            $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

            //check if the filesize is not 0 / or cancelled
            if ($request->file('file')->getSize() > 0) {
                //save in storage -> storage/public/uploads/
                $path = $request->file('file')->storeAs(
                    'public/uploads/', $newFilename
                );
                //create public path -> public/storage/uploads/{folder_id}
                $public_file_path = $storagePath . $newFilename;                
            } else {
                $public_file_path = null;
            }

            $reportData = [
                'valid'                     => 1,
                'comment'                   => $request->comment,          
                'file_name'                 => $request->file('file')->getClientOriginalName(),
                'file_path'                 => $public_file_path,
                'grade'                     => $request->grade,
                'lesson_course'             => $request->lessonCourse,
                'lesson_date'               =>  $request->inputDate,
                'lesson_material'           =>  $request->lessonCourse,
                'lesson_subject'            =>  $request->lessonSubject,
                'created_by_id'             =>  Auth::user()->id, //@note: (Create by tutor iD)
                'member_id'                 =>  $request->memberid,
                'tutor_id'                  =>  $request->tutorid,
                'display_tutor_name'        =>  (boolean) $request->displayTutorName,
            ];
            ReportCardDate::create($reportData);


            return redirect()->route('admin.member.index')->with('message', "Report card saved.");

        } else {
            $public_file_path = null;
            return redirect()->route('admin.member.index')->with('message', "Error in saving please try again.");
        }
        
    }

}
