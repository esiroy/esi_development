<?php

namespace App\Http\Controllers\Writing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;
use App\Models\WritingEntries;
use App\Models\UploadFile;
use App\Models\Tutor;
use App\Models\User;
use Auth, Config;

class WritingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function index(FormFields $formFieldModel) 
    {
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
    }


    public function store(Request $request, UploadFile $uploadFile, Tutor $tutor) 
    {
        $fields = array();

        $storagePath = 'public/uploads/writing/';

        $dataArray = json_decode($request->get('data'), true);

        $tutor_id = null;

        $fields["appointed"] = false;

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
                            echo "uploaded $uploadFileName : $file <BR>" ;                            
                        }
                        $fields[$key] = $uploadFileName;

                        //Add A Key value pair for Email Template
                        $fieldsArray[] = ['name'=> $formField->name, 'type' => $formField->type, "value"=> $uploadFileName];                        
                    }
                } else { 


                    //this detects the appoint teacher hidden id and search through they $fkeyid
                    if (isset($request->appoint_teacher_field_id)) 
                    {
                        $fields["appointed"] = true;
                        $fields["teacher_id"] = $value;

                        if ($id == $request->appoint_teacher_field_id) {
                            $tutor_id = $value;

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
    

        $entryID = WritingEntries::create([
            'form_id'               => $request->get('form_id'),
            'user_id'               => Auth::user()->id,
            'appointed_tutor_id'    => $tutor_id ?? null,
            'value'                 => json_encode($fields)
       ]);        
        
        //render the fields
        $formatEntryHTML = view('emails.writing.mailEntryHTML', compact('fieldsArray'))->render();

       // print_r ($fieldsArray);
        //echo $formatEntryHTML;
       // exit();
       
        if ($entryID) 
        {
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

            $emailSubject =  '添削サービス受付のご案内'; //Information on correction service reception
            $emailMessage =  $formatEntryHTML;
            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job);                    
        }

     
       return redirect()->route('writing.index')->with('message', 'Writing entry has been added successfully!');
    }
    

}
