<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use App\Models\Member;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\MemberAttribute;
use App\Models\ScheduleItem;
use App\Mail\SendEmailDemo;
use App\Models\MemoReply;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;
use App\Models\WritingEntries;
use App;
use Gate;
use DB;
use Auth;
use Config;
use Mail;
use App\Models\LessonMailer;
use App\Mail\CustomerSupport as CustomerSupportMail;
use App\Models\PhpSpreadsheetFontStyle as Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

use App\Jobs\SendAutoReplyJob;

class dummyController extends Controller
{

    public function __construct()
    {
    }


    public function test() {
        return view('test.index');
    }

}
