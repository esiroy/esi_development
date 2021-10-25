<?php

namespace App\Http\Controllers\Writing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;
use App\Models\WritingEntries;
use App\Models\UploadFile;

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


    public function store(Request $request, UploadFile $uploadFile) 
    {
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
                
                    $fields[$key] = $value;
                }
            } else {
                
                echo $key ." not found in form field <BR>";
            }

        }
   
     
        

        //create a array for the upload request
        /*
        foreach ($request->all() as $key => $value)         
        {
            if ($key != '_token') {

                $fkey = explode("_", $key);
                $id = $fkey[0];

                $formField = formFields::find($id);

                if ($formField) {
                    //echo $id ."<BR> ";
                    //echo $formField->type ." <BR>";

                    if (strtolower($formField->type) == 'uploadfield' || strtolower($formField->type) == 'upload') {
                        
                        $file = $request->file($key);

                        $uploadFileName = $uploadFile->uploadFile($storagePath, $file);

                        if ($uploadFileName) {
                            echo "uploaded $uploadFileName : $file <BR>" ;
                        }

                        $fields[$key] = $uploadFileName;


                    } else {

                        //standad field
                        $fields[$key] = $value;
                    }
                } else {

                    
                    echo $key ." not found in form field <BR>";

                }
                
            }


        echo "<pre>";
        print_r (json_encode($fields));
        echo "</pre>";
        exit();

        }*/

    
        

       WritingEntries::create([
            'form_id'   => $request->get('form_id'),
            'value'     => json_encode($fields)
       ]);

       return redirect()->route('writing.index')->with('message', 'Writing entry has been added successfully!');


    }


    public function ielts() {
        return vieW('modules.writing.ielts');
    }
}
