<?php

namespace App\Http\Controllers\Writing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;

class WritingController extends Controller
{
    public function index() {

        $form_id = 1; //all forms are 1(for now)
        $formFields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();
        $formFieldHTML[] = "";
        
        foreach ($formFields as $formField) 
        {
            //covert json objec to array                
            $display_meta = (array) json_decode($formField->display_meta, true);
            $id     = $formField->id;                
            $label  = $formField->name;

            if (isset($display_meta['description'])) {
                $description = $display_meta['description'];
            }

            if (isset($display_meta['content'])) {
               $content = $display_meta['content'];
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
             }

           
        }


        return view("modules.writing.index", compact('form_id','formFields', 'formFieldHTML'));
    }




    public function ielts() {
        return vieW('modules.writing.ielts');
    }
}
