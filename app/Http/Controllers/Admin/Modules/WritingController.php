<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;

class WritingController extends Controller
{
    //

    public function indeX() 
    {

        $form_id = 1; //all forms are 1(for now)
        $formFields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();
        $formFieldHTML[] = "";

        foreach ($formFields as $formField) 
        {
            //covert json objec to array                
            $display_meta = (array) json_decode($formField->display_meta, true);
            $id     = $formField->id;                
            $label  = $formField->name;
            $description = $display_meta['description'];

           if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
           {
                $type   = 'simpletextfield';                               
                $formFieldHTML[] = view('admin.forms.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta'))->render();

           } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

                $type   = 'dropdownselect';                               
                $formFieldHTML[] = view('admin.forms.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta'))->render();


           }
        }

        return view('admin.modules.writing.index', compact('formFields', 'formFieldHTML'));
    }

    public function update(Request $request) 
    {
        echo "<pre>";

        return print_r ($request->all());
    }
}
