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


    public function edit($reportcardID, Request $request)
    {
        $reportCardDate = ReportCardDate::where('id', $reportcardID)->first();

        if ($reportCardDate) 
        {
            //get member details 
            $memberInfo = Member::where('user_id', $reportCardDate->member_id)->first();

            //get photo
            $userImageObj = new UserImage();
            $userImage = $userImageObj->getMemberPhoto($memberInfo);  

            if (isset($memberInfo->tutor_id)) {
                $tutorInfo = Tutor::where('user_id',  $memberInfo->tutor_id)->first();
            } else {
                $tutorInfo = null;
            }
            return view('admin.modules.member.reportcarddateupdate', compact('memberInfo', 'userImage', 'tutorInfo', 'reportCardDate'));        
            
        } else {
            return abort(404);
        }       
        
    }    

    public function show($id, Request $request) 
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
            $storagePath = 'public/uploads/report_files/';

            $newFilename = time()."_". preg_replace('/\s+/', '_', $files->getClientOriginalName());
            $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);            
            // Remove any runs of periods (thanks falstro!)
            $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

            //check if the filesize is not 0 / or cancelled
            if ($request->file('file')->getSize() > 0) {
                //save in storage -> storage/public/uploads/
                $path = $request->file('file')->storeAs(
                    //file path
                        $storagePath, $newFilename
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

    public function update(Request $request) 
    {       
        if ($files = $request->file('file')) 
        {
            $reportCardDate = ReportCardDate::where('id', $request->reportcardID)->first();
            if (isset($reportCardDate->file_path)) 
            {
                $filename = basename($reportCardDate->file_path);
                $oldfile = storage_path('app/public/uploads/report_files/'. $filename);

                if(is_file($oldfile))
                {    
                    unlink($oldfile);                  
                }                
            }

            //upload the file
            $storagePath = 'public/uploads/report_files/';

            $newFilename = time()."_". preg_replace('/\s+/', '_', $files->getClientOriginalName());
            $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);            
            // Remove any runs of periods (thanks falstro!)
            $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

            //check if the filesize is not 0 / or cancelled
            if ($request->file('file')->getSize() > 0) {
                //save in storage -> storage/public/uploads/
                $path = $request->file('file')->storeAs(
                    //file path
                     $storagePath, $newFilename
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
                'lesson_date'               =>  $request->inputDate,

                'lesson_course'             => $request->lessonCourse,               
                'lesson_material'           =>  $request->lessonMaterial,
                'lesson_subject'            =>  $request->lessonSubject,
                'created_by_id'             =>  Auth::user()->id, //@note: (Create by tutor iD)
                'member_id'                 =>  $request->memberid,
                'tutor_id'                  =>  $request->tutorid,
                'display_tutor_name'        =>  (boolean) $request->displayTutorName,
            ];
            ReportCardDate::create($reportData);

            return redirect( url('admin/reportcarddatelist/'.$request->memberid) )->with('message', "Report card saved.");

            

        } else if ( $request->file('file') == null) {

          
            $reportCardDate = ReportCardDate::where('id', $request->reportcardID)->first();

            if ($reportCardDate) {

                $reportData = [
                    'valid'                     => 1,
                    'comment'                   => $request->comment,
                    'grade'                     => $request->grade,
                    'lesson_date'               =>  $request->inputDate,
                    'lesson_course'             => $request->lessonCourse,                   
                    'lesson_material'           =>  $request->lessonMaterial,
                    'lesson_subject'            =>  $request->lessonSubject,
                    'created_by_id'             =>  Auth::user()->id, //@note: (Create by tutor iD)
                    'member_id'                 =>  $request->memberid,
                    'tutor_id'                  =>  $request->tutorid,
                    'display_tutor_name'        =>  (boolean) $request->displayTutorName,
                ];
                $reportCardDate->update($reportData);     
                
                return redirect( url('admin/reportcarddatelist/'.$request->memberid) )->with('message', "Report card saved.");
                
            } else {
                return redirect( url('admin/reportcarddate/'.$request->reportcardID .'/edit') )->with('error_message', "Error in updating please try again (0x1111).");

            }
        } else {
            $public_file_path = null;
            return redirect( url('admin/reportcarddate/'.$request->reportcardID .'/edit') )->with('error_message', "Error in updating please try again. (0x1112)");
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

  

        //$reportcardDate   = ReportCardDate::find($id);

        $reportCardDate = ReportCardDate::where('id', $id)->first();


        if (isset($reportCardDate->file_path)) 
        {
       
            $filename = basename($reportCardDate->file_path);
            $oldfile = storage_path('app/public/uploads/report_files/'. $filename);

            if(is_file($oldfile))
            {  
                unlink($oldfile);
             
            }                
        }

        if ($reportCardDate) {
            $reportCardDate->forceDelete();
        }
    


        return back()->with('message', 'report card has been deleted successfully!');
    }    

}
