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
        //go through the field iD
        foreach($request->id as $id) {

            //FORM ID
            $form_id = 1;

            if (strtolower($request[$id.'_fieldType']) == "dropdownselect") {

                $type = 'dropdownSelect';

                //REQUEST FIELDS (STANDARD)
                $required = $request[$id.'_required'];
                $label = $request[$id.'_label'];
                $description = $request[$id.'_description'];
                $maximum_characters = $request[$id.'_maximum_characters'];                
                $default_value = $request[$id.'_default_value'];

                //SELECTION
                $selected_choices = $request[$id.'_selected_choice_text'];
               
                
                $display_meta = [
                    'required'              =>  $required,
                    'label'                 => str_replace(' ', '_',  $label),
                    'description'           => $description,
                    'selected_choices'      => $selected_choices, 
                    'type'                  => $type,
                    'default_value'         => $default_value
                ];


                $id = FormFields::find($id)->update([
                    'form_id'           => $form_id,
                    'name'              => $label,
                    'description'       => $description,
                    'type'              => $type,
                    'display_meta'      => json_encode($display_meta),
                    
                ]);      

            }
        }

        return redirect()->route('admin.writing.index', $id)->with('message', 'Form updated successfully!');


    }
}
