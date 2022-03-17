<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Member;
use App\Models\FormFields;
use App\Models\ConditionalFieldLogic;
use App\Models\WritingEntries;
use App\Models\UploadFile;
use App\Models\Tutor;
use App\Models\WritingEntryGrade;
use App\Models\AgentTransaction;
use App\Models\User;
use App\Models\ReportCardDate;
use App\Models\ScheduleItem;

use Gate, Auth, Config;

class WritingController extends Controller
{

    public function __construct()
    {
        
        $this->middleware(function ($request, $next) {
            //authenticated by has no "admin_access" in his role attached
            //@do: redirect to home (authenticated member will be his view)
            if (Gate::denies('admin_access')) {
                return redirect(route('home'));
            }
            return $next($request);           
        });    
    }
    
    
    public function index(FormFields $formFieldModel,   Tutor $tutor) 
    {
        //abort_if(Gate::denies('writing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER') 
        {
        
            $form_id = 1; //all forms are 1(for now)
            $formFields = FormFields::where('form_id', $form_id)->where('page_id', 0)->orderBy('sequence_number', 'ASC')->get();

            $cfields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();

           
            $pages =  FormFields::distinct()->where('page_id', '>=', 1)->orderBy('page_id', 'ASC')->get(['page_id']);
            $pageCounter =  $pages->count() + 1;

          

            return view('admin.modules.writing.writing-edit', compact('pages', 'pageCounter',  'form_id', 'cfields' ));        
        
        } else if (Auth::user()->user_type == 'TUTOR') {
            
            $form_id = 1;

            //get form fields for name of header
            $formFields  = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();

            //[*Update] Get Entry of data for the current TEacher
            $entries     = WritingEntries::where('form_id', $form_id)
                                            ->where('appointed_tutor_id', Auth::user()->id)
                                            ->orderBy('id', 'DESC')
                                            ->paginate(Auth::user()->items_per_page ?? 15);
            $tutors      = $tutor->getTutorByID(Auth::user()->id);            
            return view('admin.modules.writing.entries', compact('form_id','entries','formFields', 'tutors'));        
        }
    }


    public function entries($form_id, Tutor $tutor) 
    {
        //abort_if(Gate::denies('writing_entries'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER') {

            //get form fields for name of header
            $formFields  = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();
            //Get Entry of data
            $entries     = WritingEntries::where('form_id', $form_id)->orderBy('id', 'DESC')->paginate(Auth::user()->items_per_page ?? 15);
            $tutors      = $tutor->getTutors();            
            return view('admin.modules.writing.entries', compact('form_id','entries','formFields', 'tutors'));

        } else {
              abort(401, "User is not authorized");
        }
    }



    /* 
        Show an entry
        @var $form_id : form id defaults to 1
        @entry_id 
        @tutor model
    */
    public function entry($form_id, $entry_id, Member $member, Tutor $tutor, WritingEntryGrade $writingEntryGrade)  
    {

        //get the posted grade
        $postedEntries =  $writingEntryGrade->where('writing_entry_id', $entry_id)->orderby('created_at', 'DESC')->get();        

        //abort_if(Gate::denies('writing_entry'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER') {
            //get form fields for name of header
            //$formFields  = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();

            //Get Entry of data
            $entry     = WritingEntries::where('form_id', $form_id)->where('id', $entry_id)->first();

            $tutors      = $tutor->getTutors();

            $memberInfo = $member->where('user_id', $entry->user_id)->first();
           
            return view('admin.modules.writing.entry', compact('form_id', 'entry_id', 'entry', 'tutors', 'memberInfo', 'postedEntries'));
        //} else {
           // abort(401, "User is not authorized");
        //}
    }


    public function postGrade($id, Request $request, Member $member, WritingEntries $writingEntries, WritingEntryGrade $writingEntryGrade, UploadFile $uploadFile, ScheduleItem $scheduleItem) 
    {      
        $writingEntry = WritingEntries::find($id);
        $words =  $writingEntry->total_words;
        $member = $member->where('user_id', $writingEntry->user_id)->first();


        /*******************************************************************************
        *          UPDATE WRITING ENTRY FOR DEDUCTION ONLY AND WITH ATTACMENTS SINCE NO ATTACHMENT DEDUCTS (1 POINT OR 2)
        *********************************************************************************/
        if ($writingEntry) {

            if (isset($request->hasAttachement)) {
                $hasAttachement = $request->hasAttachement;
                if ($hasAttachement == true) 
                {
                    $words = $request->words;
                    $writingCredit = $writingEntry->total_points;                
                    $pointsToDeduct = $writingEntries->getWordPointDeduct($words);
                    $additionalPoints =  $pointsToDeduct - $writingCredit;

                    //detect if user has assigned a teacher
                    if (isset($request->appointed_value)) {
                        if ($request->appointed_value == 'on') {                            
                            $totalDeductedPoints = $pointsToDeduct * 2;
                            
                            //update total addtional points
                            $additionalPoints =  $totalDeductedPoints - $writingCredit;
                        } else {
                            $totalDeductedPoints = $pointsToDeduct;
                        }
                    } else {
                        $totalDeductedPoints = $pointsToDeduct;
                    }      

                    //echo $member->membership . " " . $totalDeductedPoints;
                    

                    //Update Deduction for Writing for Monthly 
                    $data = [
                                'total_points' => $totalDeductedPoints,
                                'total_words' =>  $words
                            ];
                    $writingEntry->update($data);
                    
                    //Update Writing Entry Schedule
                    if (isset($writingEntry->schedule_id)) {
                        $scheduleItem = $scheduleItem->find($writingEntry->schedule_id);

                        if ($additionalPoints > 0) {                        
                            if ($scheduleItem) {
                                $scheduleItemData = [
                                    'tutor_id'  => $writingEntry->appointed_tutor_id,
                                    'memo'      => "Writing Entry : " . $writingCredit. " - Additional Point : ". $additionalPoints ." ."
                                ];
                                $scheduleItem->update($scheduleItemData);                    
                            }
                        }
                    }

                    //Update point balance since deduction of point credit for point balance is reading through agent Credit
                    if (isset($member->membership)) {
                        if ($member->membership == "Point Balance") 
                        {
                            //add member transaction (agent subtract since we are deducting point)
                            if ($additionalPoints > 0) {    
                                $agentCredit = [
                                    'valid' => 1,
                                    'transaction_type' => 'AGENT_SUBTRACT',
                                    'agent_id' => $member->agent_id,
                                    'member_id' => $member->user_id,
                                    'lesson_shift_id' => $member->lesson_shift_id,
                                    'created_by_id' => Auth::user()->id,
                                    'amount' => $additionalPoints,
                                    'price' => 1,
                                    'remarks' => "WRITING ENTRY - additional point for file attachment",
                                    //'credits_expiration' => $expiry_date,
                                    //'old_credits_expiration' => $old_credits_expiration,
                                ];
                                AgentTransaction::create($agentCredit); 
                            }
                        }                    
                    }
                } 
            }

        }



        /********************************************
        *               log to report card
        **********************************************/
        $storagePath = 'public/uploads/report_files/';
        $publicURL = 'storage/uploads/report_files/';
       
        $file = $request->file('file');
       
        if ($file) {
            $uploadFileName = $uploadFile->uploadFile($storagePath, $file);
            if ($uploadFileName) {
                $attachment_url = $publicURL . basename($uploadFileName);
            } else {
                $uploadFileName = null;
                $attachment_url = null;
            }
        } else {
            $uploadFileName = null;
            $attachment_url = null;
        }

        /*******************************************************************************
        *           Writing Entry Report Card (Both monthly and Credit transaction )
        *********************************************************************************/       
        if ($writingEntry) 
        {
            //only (00,30) allowed
            $minutes = date('i');
            if ($minutes > 30) { $min = 30; } else { $min =  00; }
            $lessonDate = date('Y-m-d H:'.$min.':00');

            $reportCardData = [
                'valid'                     => 1,           
                'file_name'                 =>  $request->file('file')->getClientOriginalName(),
                'file_path'                 =>  $attachment_url,
                'grade'                     =>  $request->grade,
                'lesson_course'             =>  $request->course,
                'lesson_date'               =>  $lessonDate,
                'lesson_material'           =>  $request->material,
                'lesson_subject'            =>  $request->subject,
                'comment'                   =>  $request->content,
                'created_by_id'             =>  Auth::user()->id, 
                'member_id'                 =>  $writingEntry->user_id,
                'tutor_id'                  =>  $writingEntry->appointed_tutor_id ?? null,
                'display_tutor_name'        =>  (boolean) $request->displayTutorName,
            ];
                
            ReportCardDate::create($reportCardData);        
        }



        /****************************************
                writing entries
        *****************************************/
        $storagePath = 'public/uploads/writing_entries/';
        $publicURL = 'storage/uploads/writing_entries/'; 
        $file = $request->file('file');

        if ($file) {
            $uploadFileName = $uploadFile->uploadFile($storagePath, $file);
            if ($uploadFileName) {
                 $attachment_url = $publicURL . basename($uploadFileName);
            } else {
                $uploadFileName = null;
                $attachment_url = null;
            }
        } else {
            $uploadFileName = null;
            $attachment_url = null;
        }



        $writingGrade = [
                'writing_entry_id'  => $id,
                'course'            => $request->course,
                'material'          => $request->material,
                'subject'           => $request->subject,
                'appointed'         => boolval($request->appointed),
                'grade'             => $request->grade,
                'words'             => $request->words ?? $writingEntry->total_words,
                'content'           => $request->content,
                'attachment'        => $attachment_url
            ];
        $writingEntryGrade->create($writingGrade);
        


        //Get the entry id and know you the member is
        if (isset($writingEntry)) 
        {
          
            if (isset($member)) 
            {
                $user = User::where('id', $member->user_id)->first();

                if (isset($user)) {
                   
                    //E-Mail Template
                    $emailTemplate = 'emails.writing.teacherAutoreply';                    
                    $formatEntryHTML = view('emails.writing.writingTutorReplyHTML', compact('writingGrade'))->render();
                    //E-Mail Recipient
                    $emailTo['name']    = $user->firstname ." ". $user->lastname;
                    $emailTo['email']   = $user->email; 
                    //Email Reply To
                    $emailFrom['name']   = Config::get('mail.from.name');
                    $emailFrom['email']  = Config::get('mail.from.address');

                    $emailSubject =  $request->subject; //Information on correction service reception
                    $emailMessage =  $formatEntryHTML;

                    //$fileURL = url($publicURL . basename($uploadFileName));
                    
                    $attachment = [
                        'clientOriginalName' => $file->getClientOriginalName(),
                        'realPath' => $file->getRealPath(),
                        'clientMimeType'  => $file->getClientMimeType()
                    ];

                   

                    $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate, $attachment);
                    dispatch($job);  
                }
            }                    
        }

        return redirect()->back()->with(['message' => 'The Grade and content was successfully posted']);        
    
    }


    public function preview($id, FormFields $formFieldModel) 
    {        

        //abort_if(Gate::denies('writing_entry'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER') {

            $form_id = $id; 
            $formFields = FormFields::where('form_id', $form_id)->where('page_id', 0)->orderBy('sequence_number', 'ASC')->get();
            $formFieldHTML[] = "";

            $cfields = $formFields;        
            foreach ($formFields as $formField) 
            {
                $formFieldHTML[] = $formFieldModel->generateFrontEndFormFieldHTML($formField, $cfields);             
            }

            /************ GET CHILDREN HTML ************/
            $formFieldChildrenHTML[] = "";
            //Get Pages
            $pages =  FormFields::distinct()->where('page_id', '>=', 1)->orderBy('page_id', 'ASC')->get(['page_id']);
            $pageCounter =  $pages->count() + 1;

            foreach ($pages as $page) 
            {           
                $formChildFields = FormFields::where('form_id', $form_id)->where('page_id', $page->page_id)->orderBy('sequence_number', 'ASC')->get();
                $child_cfields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();
                foreach ($formChildFields as $formChildField) 
                {
                    $formFieldChildrenHTML[$page->page_id][] =  $formFieldModel->generateFrontEndFormFieldHTML($formChildField, $child_cfields);
                }
            }
            return view('admin.modules.writing.preview', compact('pages', 'pageCounter', 'form_id','formFields', 'formFieldHTML', 'formFieldChildrenHTML'));
        } else {
            abort(401, "User is not authorized");
        }            

    }

/*
    public function update(Request $request,  UploadFile $uploadFile) 
    {

        //this method is now disregarded       

        exit(); 

        $form_id = 1;

        if (is_array($request->id) == false) {
            return redirect()->route('admin.writing.index', $form_id)->with('error_message', "Form can't be updated, please add a field from fields menu");
        }

        $ids = ConditionalFieldLogic::where('form_id', $form_id)->delete();

        //initial sq number
        $sequence_number = 1;

        //go through the field iD
        foreach($request->id as $id) 
        {

            //REQUEST FIELDS (STANDARD)
            $label = $request[$id.'_label'];
            $description = $request[$id.'_description'];
            $maximum_characters = $request[$id.'_maximum_characters'];                
            $default_value = $request[$id.'_default_value'];

            $required = ($request[$id.'_required'] == "on") ? true : false;
            $conditional_logic = ($request[$id.'_activate_coditional_logic'] == "on") ? true : false;
            
            $display_meta = [
                'required'              => $required,
                'conditional_logic'     => $conditional_logic,
                'label'                 => str_replace(' ', '_',  $label),
                'description'           => $description,
                'default_value'         => $default_value
            ];

            ///******************** TYPES OF INPUT **********************
            
            if (strtolower($request[$id.'_fieldType']) == "dropdownselect") {
                $type = 'dropdownSelect';                
                $selected_choices = $request[$id.'_selected_choice_text'];                                               
                $display_meta['type'] = $type;
                $display_meta['selected_choices'] = $selected_choices;                

            } else if (strtolower($request[$id.'_fieldType']) == "simpletextfield") {
                $type = 'simpletextfield';
                $display_meta['type'] = $type;
                $display_meta['maximum_characters'] = $maximum_characters;

            } else if (strtolower($request[$id.'_fieldType']) == "html" || strtolower($request[$id.'_fieldType']) == "htmlcontent") {
                $type = 'htmlContent';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];;

            } else if (strtolower($request[$id.'_fieldType']) == "firstname" || strtolower($request[$id.'_fieldType']) == "firstnamefield") {
                $type = 'firstnamefield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];

            } else if (strtolower($request[$id.'_fieldType']) == "lastname" || strtolower($request[$id.'_fieldType']) == "lastnamefield") {
                $type = 'lastnamefield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];
            
            } else if (strtolower($request[$id.'_fieldType']) == "email" || strtolower($request[$id.'_fieldType']) == "emailfield") {
                $type = 'emailfield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];


            } else if (strtolower($request[$id.'_fieldType']) == "upload" || strtolower($request[$id.'_fieldType']) == "uploadfield") {
                $type = 'uploadfield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];

            } else if (strtolower($request[$id.'_fieldType']) == "paragraphtext") {
                $type = 'paragraphtext';
                $display_meta['type'] = $type;

                //word limiter
                $display_meta['enableWordLimit'] =  ($request[$id.'_enableWordLimit'] == "on") ? true : false; 
                $display_meta['wordLimit'] = $request[$id.'_wordLimit'];

                //enable member credit checker [180 = 1 point ] [180 to 500 words  = 2 points ] [501 to 800 = 3 points]
                $display_meta['memberPointChecker'] =  ($request[$id.'_memberPointChecker'] == "on") ? true : false;
            }
            

            //Conditonal Field Logic
            if (isset( $request[$id.'_cfield_id'] )) {
                foreach ($request[$id.'_cfield_id'] as $key => $fieldID) {
                    $cfID               = $request[$id.'_cfield_id'][$key];
                    $cfRule              = $request[$id.'_cfield_rule'][$key];
                    $cfValue            = ($request[$id.'_cfield_value'][$key]) ?? "";

                    ConditionalFieldLogic::create([
                        'form_id'               =>  $form_id,
                        'field_id'              =>  $id,
                        'selected_option_id'    =>  $cfID,
                        'field_rule'            =>  $cfRule,
                        'field_value'           => $cfValue,
                    ]);
                }
            }

            //GET the page array number for the page
            if (isset($request[$id.'_page'])) {
                $formPageArr = explode("-", $request[$id.'_page']);            
                $page = $formPageArr[1];          
                                
            }

            $form = FormFields::find($id);
            if ($form) {
                $form->update([
                    'form_id'           => $form_id,
                    'page_id'           => $page,
                    'name'              => $label,
                    'description'       => $description,
                    'type'              => $type,
                    'display_meta'      => json_encode($display_meta),
                    'sequence_number'     => $sequence_number
                ]); 
            }

            $sequence_number = $sequence_number + 1;
        }

        return redirect()->route('admin.writing.index', $form_id)->with('message', 'Form updated successfully!');
    }
    */


    public function store(Request $request, UploadFile $uploadFile, Tutor $tutor) {
        $fields = array();

        $storagePath = 'public/uploads/writing/';

        $dataArray = json_decode($request->get('data'), true);

        foreach ($dataArray as $key => $value)         
        {

            $fkey = explode("_", $key);
            $id = $fkey[0];

            $formField = formFields::find($id);
           


            if ($formField) 
            { 
                if (strtolower($formField->type) == 'uploadfield' || strtolower($formField->type) == 'upload') 
                {                    
                    $file = $request->file($key);

                    if ($file) {
                        $uploadFileName = $uploadFile->uploadFile($storagePath, $file);
                        if ($uploadFileName) {
                            echo "uploaded $uploadFileName : $file <BR>" ;
                        }
    
                        $fields[$key] = $uploadFileName;
                    }

                } else {
                

                    //this detects the appoint teacher hidden id and search through they $fkeyid
                    if (isset($request->appoint_teacher_field_id)) 
                    {
                        if ($id == $request->appoint_teacher_field_id) {

                            $fields["teacher_id"] = $value;
                            $fields["appointed"] = true;

                            $tutor_id = $value;

                            //value change to name of tutor
                            $tutorInfo = $tutor->where('user_id', $tutor_id)->first();
                            $fields[$key] =  $tutorInfo->user->firstname;

                        } else {
                           
                            $fields[$key] = $value;
                        }
                    } else {                       
                        $fields[$key] = $value;

                    }
                    
                }
            } else {
                
                echo $key ." not found in form field <BR>";
            }

        }

       WritingEntries::create([
            'form_id'               => $request->get('form_id'),
            'user_id'               => Auth::user()->id,
             'appointed_tutor_id'    => $tutor_id ?? null,
            'value'                 => json_encode($fields)
       ]);

       return redirect()->route('admin.writing.index')->with('message', 'Writing entry has been added successfully!');
    }


}
