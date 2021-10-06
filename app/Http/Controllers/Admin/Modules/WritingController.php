<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;
use App\Models\ConditionalFieldLogic;

class WritingController extends Controller
{
    //

    public function indeX() 
    {

        $form_id = 1; //all forms are 1(for now)
        $formFields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();
        $formFieldHTML[] = "";

        //CONDITIONAL FIELDS (init)
        $cfields = $formFields;
    
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
                $formFieldHTML[] = view('admin.forms.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

           } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

                $type   = 'dropdownselect';                               
                $formFieldHTML[] = view('admin.forms.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

            } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {

                $type   = 'html';                               
                $formFieldHTML[] = view('admin.forms.htmlContent', compact('id', 'label', 'content','display_meta', 'cfields'))->render();
            }
        }

        return view('admin.modules.writing.index', compact('formFields', 'formFieldHTML'));
    }

    public function update(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        if (is_array($request->id) == false) {
            return redirect()->route('admin.writing.index', $form_id)->with('error_message', "Form can't be updated, please add a field from fields menu");
        }

        
        $ids = ConditionalFieldLogic::where('form_id', $form_id)->delete();


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

            /******************** TYPES OF INPUT ***********************/
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
            }
            
            if (isset( $request[$id.'_cfield_id'] )) {
                foreach ($request[$id.'_cfield_id'] as $key => $fieldID) {
                    $cfID               = $request[$id.'_cfield_id'][$key];
                    $cfRule              = $request[$id.'_cfield_rule'][$key];
                    $cfValue            = $request[$id.'_cfield_value'][$key];

                    ConditionalFieldLogic::create([
                        'form_id'               =>  $form_id,                        
                        'field_id'              =>  $id,
                        'selected_option_id'    =>  $cfID,
                        'field_rule'            =>  $cfRule,
                        'field_value'           => $cfValue,
                    ]);

                    //echo $cfID . " | " . $cfRule . " | " .$cfValue;
                    //echo "<BR>";
                }
            }

            $form = FormFields::find($id);
            if ($form) {
                $form->update([
                    'form_id'           => $form_id,
                    'name'              => $label,
                    'description'       => $description,
                    'type'              => $type,
                    'display_meta'      => json_encode($display_meta),                    
                ]); 
            }
        }

        return redirect()->route('admin.writing.index', $form_id)->with('message', 'Form updated successfully!');
    }
}
