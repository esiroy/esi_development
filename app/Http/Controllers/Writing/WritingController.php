<?php

namespace App\Http\Controllers\Writing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;
use App\Models\WritingEntries;

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

            /*
            //covert json objec to array                
            $display_meta = (array) json_decode($formField->display_meta, true);
            $id     = $formField->id;                
            $label  = $formField->name;
            $required  = $formField->required;
            $maximum_characters  = $formField->maximum_characters;
            

            if (isset($display_meta['description'])) {
                $description = $display_meta['description'];
            } else {
                $description = "";
            }

            if (isset($display_meta['content'])) {
               $content = $display_meta['content'];
            } else {
                $content = "";
            }            

            if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
            {
                 $type   = 'simpletextfield';                               
                 $formFieldHTML[] = view('modules.writing.fields.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();
 
            } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {
 
                 $type   = 'dropdownselect';                               
                 $formFieldHTML[] = view('modules.writing.fields.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();
 
             } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {
 
                 $type   = 'html';                               
                 $formFieldHTML[] = view('modules.writing.fields.htmlContent', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

             } else if (strtolower($formField->type) == "firstname" || strtolower($formField->type) == "firstnamefield") {

                $type   = 'firstnamefield';                               
                $formFieldHTML[] = view('modules.writing.fields.firstnamefield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();
                
            } else if (strtolower($formField->type) == "lastname" || strtolower($formField->type) == "lastnamefield") {

                $type   = 'lastnamefield';                               
                $formFieldHTML[] = view('modules.writing.fields.lastnamefield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

            } else if (strtolower($formField->type) == "email" || strtolower($formField->type) == "emailfield") {

                $type   = 'emailfield';                               
                $formFieldHTML[] = view('modules.writing.fields.emailfield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();
            }
            */                   
        }

        /************ GET CHILDREN HTML ************/
        $formFieldChildrenHTML[] = "";
        //Get Pages
        $pages =  FormFields::distinct()->where('page_id', '>=', 1)->orderBy('page_id', 'ASC')->get(['page_id']);
        $pageCounter =  $pages->count() + 1;    
        

        foreach ($pages as $page) 
        {           

            $formChildFields = FormFields::where('form_id', $form_id)->where('page_id', $page->page_id)->orderBy('sequence_number', 'ASC')->get();

            $child_cfields = $formChildFields;

            foreach ($formChildFields as $formChildField) 
            {
                $formFieldChildrenHTML[$page->page_id][] =  $formFieldModel->generateFrontEndFormFieldHTML($formChildField, $child_cfields);
            }
        }



        return view("modules.writing.index", compact('pages', 'pageCounter', 'form_id','formFields', 'formFieldHTML', 'formFieldChildrenHTML'));
    }


    public function store(Request $request) 
    {
        $fields = array();

        foreach ($request->all() as $key => $value) {
            if ($key != '_token') {
                $fields[$key] = $value;
            }            
        }

        //print_r (json_encode($fields));

        //exit();

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
