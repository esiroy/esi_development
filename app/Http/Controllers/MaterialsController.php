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

use Gate;
use Validator;
use Input;
use DB;
use Auth;

class MaterialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

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

           // $schedules = ScheduleItem::where('member_id', Auth::user()->id)->get();

            $reserves =  ScheduleItem::where('member_id', $member->id)->get();
    
            $latestReportCard = ReportCard::OrderBy('created_at', 'DESC')->first();

            return view('modules/member/index', compact('member', 'data', 'schedules', 'reserves', 'latestReportCard'));

        } else {
           
            //abort (404, "Member Not Found");

            $roles = Auth::user()->roles;
            if (!$roles->contains('title', 'Member')) {
                return redirect(route('admin.dashboard.index'));
            } else {                
                /**
                 * @todo: make a proper message here to your users that 
                 * @todo: other roles tried to view this page, abort the page.                 
                 */
                abort(403, 'Unauthorized action, you are not allowed to view this page');
            }
        }
        
        
    }
}
