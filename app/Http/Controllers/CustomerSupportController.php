<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\ReportCard;
use App\Models\ScheduleItem;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\User;
use App\Models\CustomerSupport as CustomerSupportModel;
use App\Mail\CustomerSupport as CustomerSupportMail;
use Auth;
use Gate;
use DateTime;
use Mail;

class CustomerSupportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }    
    
    public function index(ReportCard $reportcards) 
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        if (isset($member)) {
            $memberData = Member::find($member->id);
            $skypeID = $memberData->communication_app_username;

            $tutorData = Tutor::find($member->main_tutor_id);
            $lecturer = (isset($tutorData->name_en)) ? $tutorData->name_en : '';

            $data = [
                'lecturer' => $lecturer,
                'skypeID' => $skypeID,
            ];


            $latestReportCard = $reportcards->getLatest($member->user_id);


            return view('modules/member/customersupport', compact('member', 'data', 'latestReportCard'));
        } else {

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

    public function store (Request $request) 
    {

        $data = [
                'valid' => true,
                'member_id' => Auth::user()->id,
                'inquiry' => $request->inquiry
                ];
               
        CustomerSupportModel::create($data);

        $mailData = [
            'name' => $request->name,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'inquiry' => $request->inquiry,
            'attachment' => $request->file('file_upload')
        ];       

        $member = Member::where('user_id', Auth::user()->id)->first();

        Mail::send(new CustomerSupportMail($member, $mailData));

        return redirect()->route('customersupport.index')->with('message', '送信完了しました');
    }
}