<?php

namespace App\Http\Controllers\Writing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;
use App\Models\WritingEntries;
use App\Models\UploadFile;
use App\Models\Tutor;
use App\Models\User;
use App\Models\Member;
use App\Models\ScheduleItem;
use App\Models\AgentTransaction;

use Auth, Config;

class WritingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function index(FormFields $formFieldModel) 
    {
        try {

            $member = Member::where('user_id', Auth::user()->id)->first();
            if (isset($member)) {

                /* FRONT END WRITING FORM */

                $form_id = 1; //all forms are 1(for now)
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

                return view("modules.writing.index", compact('pages', 'pageCounter', 'form_id','formFields', 'formFieldHTML', 'formFieldChildrenHTML'));

            } else {
                sleep(3);
                return redirect()->away( url('/admin/writing'));        
            }
           
        }  catch(\Exception $e) {

             echo $e->getMessage();
        }
    }


    /* Front End
        @request->data : (json key pair of writing form)
    */
    public function store(Request $request, UploadFile $uploadFile, Tutor $tutor,  WritingEntries $writingEntries) 
    {

        echo "store";

        exit();


        $fields = array();        
        $tutor_id = null;
        $userAttachedFile = false;
        $totalWords = 0;
        $pointToDeduct = 0;

        $storagePath = 'public/uploads/writing/';
        $dataArray = json_decode($request->get('data'), true);


        dd ( $dataArray);

        exit();

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
                        if ($uploadFileName) 
                        {
                            //echo "uploaded $uploadFileName : $file <BR>" ;   
                            $fields[$key] = $uploadFileName;
                            //Add A Key value pair for Email Template
                            $fieldsArray[] = ['name'=> $formField->name, 'type' => $formField->type, "value"=> $uploadFileName];  

                            //user has succesfully attached a file
                            $userAttachedFile = true;
                        }                       
                    }

                } else if (strtolower($formField->type) == 'paragraphtext') {       

                    $display_meta = json_decode($formField->display_meta, true);

                    if ($display_meta['memberPointChecker'])  
                    {
                        $wordCount = countWords(strip_tags_content($value));

                        $totalWords = $totalWords +  $wordCount;   

                        $fieldsArray[] = ['name'=> $formField->name, 'type' => $formField->type, "value"=> $value];  
                        $fields[$key] = $value;  

                    } else {
                    
                        $fieldsArray[] = ['name'=> $formField->name, 'type' => $formField->type, "value"=> $value];  
                        $fields[$key] = $value; 
                    
                    }

                } else { 

                    //this detects the appoint teacher hidden id and search through they $fkeyid
                    if (isset($request->appoint_teacher_field_id)) 
                    {
                        if ($id == $request->appoint_teacher_field_id) {
                            $tutor_id = $value;
                            $fields["appointed"] = true;
                            $fields["teacher_id"] = $value;

                            //value change to name of tutor
                            $tutorInfo = $tutor->where('user_id', $tutor_id)->first();
                            $fields[$key] =  $tutorInfo->user->firstname;

                            //replace id to tutor name
                            $value = $tutorInfo->user->firstname;

                        } else {
                        
                            $fields[$key] = $value;
                        }
                    } else {
                        $fields[$key] = $value;
                    }


                    $fields[$key] = $value;  
                    
                    //Add A Key value pair for Email Template
                    $fieldsArray[] = ['name'=> $formField->name, 'type' => $formField->type, "value"=> $value];
                }               

            } else {
                
                //echo $key ." not found in form field <BR>";
            }
        }

        //detect if theres an attachement
        if ($pointToDeduct == 0 && $userAttachedFile == true) 
        {
            //User has no attachment, then point deduction is automcatically 1
            $pointToDeduct  = 1;
        } else {
            $pointToDeduct =  $writingEntries->getWordPointDeduct($totalWords);        
        }

        //make the point double if he assigns a tutor
        $remarks = "";
        if (isset($tutor_id )) 
        {
            $pointToDeduct = $pointToDeduct * 2;

            if ($tutorInfo) {
                $remarks = "Appointed Tutor : " . $tutorInfo->user->firstname;
            }
        
        }

       //get the member info and determine what membership type
       $memberInfo = Member::where('user_id', Auth::user()->id)->first();

       if (isset($memberInfo->membership)) 
       {
            if ($memberInfo->membership == "Monthly") 
            {   
                //only (00,30) allowed
                $minutes = date('i');
                if ($minutes > 30) {
                    $min = 30;
                } else {
                    $min =  00;
                }

                if ($userAttachedFile == true) {
                     $remarks .= " with Attached File";
                }

                $lessonData = [
                    'lesson_time' => date('Y-m-d H:i:00', strtotime(date('Y-m-d H:'.$min.':00'))),
                    'member_id' => Auth::user()->id,
                    'tutor_id'  => $tutor_id ?? null,
                    'schedule_status' => "WRITING",
                    "memo"  => "Writing Entry Point : $pointToDeduct " . $remarks,
                    'valid' => 0,
                ];
                $schedule = ScheduleItem::create($lessonData);

                $entryID = WritingEntries::create([
                    'form_id'               => $request->get('form_id'),
                    'type'                  => $memberInfo->membership,
                    'user_id'               => Auth::user()->id,
                    'schedule_id'           => $schedule->id,
                    'appointed_tutor_id'    => $tutor_id ?? null,
                    'total_words'           => $totalWords,
                    'total_points'          => $pointToDeduct,
                    'value'                 => json_encode($fields)
                ]);

            } else if ($memberInfo->membership == "Point Balance" || $memberInfo->membership == "Both") {                

                //add member transaction (agent subtract since we are deducting point)
                $agentCredit = [
                    'valid' => 1,
                    'transaction_type' => 'AGENT_SUBTRACT',
                    'agent_id' => $memberInfo->agent_id,
                    'member_id' => $memberInfo->user_id,
                    'lesson_shift_id' => $memberInfo->lesson_shift_id,
                    'created_by_id' => Auth::user()->id,
                    'amount' => $pointToDeduct,
                    'price' => 1,
                    'remarks' => "WRITING ENTRY - " . $remarks,
                    //'credits_expiration' => $expiry_date,
                    //'old_credits_expiration' => $old_credits_expiration,
                ];
                AgentTransaction::create($agentCredit);     


                $entryID = WritingEntries::create([
                    'form_id'               => $request->get('form_id'),
                    'type'                  => $memberInfo->membership,
                    'user_id'               => Auth::user()->id,
                    'schedule_id'           => null,
                    'appointed_tutor_id'    => $tutor_id ?? null,
                    'total_words'           => $totalWords,
                    'total_points'          => $pointToDeduct,
                    'value'                 => json_encode($fields)
                ]);                
                
            }       
       }        
     
        if ($entryID) 
        {
            //render the fields
            $formatEntryHTML = view('emails.writing.mailEntryHTML', compact('fieldsArray'))->render();

            //send the authenticated user the email, since the Authenticated user
            $user = Auth::user();
            //E-Mail Template
            $emailTemplate = 'emails.writing.autoreply';           

            //E-Mail Recipient
            $emailTo['name'] = $user->firstname ." ". $user->lastname;
            $emailTo['email'] = $user->email; 

            //Email Reply To
            $emailFrom['name']   = Config::get('mail.from.name');
            $emailFrom['email']  = Config::get('mail.from.address');

            $emailSubject =  'マイチューター : 添削サービス受付のご案内'; //Information on correction service reception
            $emailMessage =  $formatEntryHTML;

            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job);       


            //send to admins

            //Admin E-Mail Recipient
            $emailTo['name'] =  "takemura0717@yahoo.co.jp";
            $emailTo['email'] = "takemura0717@yahoo.co.jp"; 
            $job = new \App\Jobs\SendAutoReplyJob( $emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job);


            //Admin E-Mail Recipient
            $emailTo['name']    = "james.4communication@gmail.com";
            $emailTo['email']   = "james.4communication@gmail.com";             
            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job); 

            //Admin E-Mail Recipient
            $emailTo['name'] = "criz.4communication@gmail.com";
            $emailTo['email'] = "criz.4communication@gmail.com";
            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job); 

      

            //E-Mail Recipient
            $emailTo['name'] =  "bhadz.trex@gmail.com";
            $emailTo['email'] = "bhadz.trex@gmail.com"; 
            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job);  

            //E-Mail Recipient
            $emailTo['name'] =  "abellana@gmail.com";
            $emailTo['email'] = "abellana@gmail.com"; 
            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job);          
        }

        return redirect()->route('writing.success')->with('message', 'ライティングエントリが正常に追加されました！');
    }

    public function success(Request $request) 
    {      
        $message = session('message');
        if (isset($message)) {
            return view("modules.writing.success", compact('message'));
        } else {    
            return redirect( url('/writing') );
        }
    }
}