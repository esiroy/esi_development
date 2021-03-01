<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Tutor;
use App\Models\Shift;
use App\Models\Member;
use App\Models\Status;
use App\Models\ScheduleItem;
use App\Models\ReportCard;
use App\Models\CourseCategory;
use App\Models\LessonMaterial;

use Gate;
use Validator;
use Input;
use DB;
use Auth;

class LessonMaterialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(ReportCard $reportcard, CourseCategory $courseCategory) 
    {
        $member = Member::where('user_id', Auth::user()->id)->first();

        if (isset($member)) 
        {
            $latestReportCard = $reportcard->getLatest($member->user_id);
            $courseParents = $courseCategory->getParents();
            return view('modules/lessonmaterials/index', compact('member', 'latestReportCard', 'courseParents'));

        } else {
            
            abort(403, 'Unauthorized action, you are not allowed to view this page');
        }
    }


    public function show($id, ReportCard $reportcard, CourseCategory $courseCategory, LessonMaterial $lessonMaterial) {

        $member = Member::where('user_id', Auth::user()->id)->first();

        if (isset($member)) 
        {
            $latestReportCard = $reportcard->getLatest($member->user_id);

            $courseParents = $courseCategory->getCourses($id);

            if (count($courseParents) == 0) 
            {
                $course = $courseCategory->getCourseInfo($id);

                //get course siblings ()
                if (isset($course->parent_course_category)) {
                    $courseSiblings = $courseCategory->getSiblings($id);
                } else {
                  
                    $courseSiblings = array();
                }         

                $lessonMaterials = $lessonMaterial->getLessonMaterial($id);

                return view('modules/lessonmaterials/materials', compact('member', 'latestReportCard', 'lessonMaterials', 'course', 'courseSiblings'));

            } else {
                
                //subcategories
                
                $course = $courseCategory->where('parent_course_category', $id)->first();
                $courseParent = $courseCategory->getCourseInfo($course->parent_course_category);

                $lessonMaterials = $lessonMaterial->getLessonMaterial($id);


                return view('modules/lessonmaterials/index', compact('id', 'member',  'latestReportCard', 'lessonMaterials', 'course', 'courseParents', 'courseParent'));
            }

            

        } else {
            
            abort(403, 'Unauthorized action, you are not allowed to view this page');
        }

    }
}
