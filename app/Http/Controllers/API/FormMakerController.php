<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WritingFields;

class FormMakerController extends Controller
{
    
    public function saveSimpleTextField(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $required = $request->required;
        $description = $request->description;
        $maximum_characters = $request->maximum_characters;
        $type = 'simpletextfield';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            'maximum_characters'    => $request->maximum_characters,
            'type'                  => $type,
        ];

        array_walk_recursive($display_meta, function(&$item){
            if(is_null($item)) $item = '';
        });

        $max_seq = WritingFields::where('form_id', $form_id)->max('sequence_number');

       
        $id = WritingFields::Create([
            'form_id'           => $form_id,
            'name'              => $request->label,
            'description'       => $request->description,
            'type'              => $type,
            'display_meta'      => json_encode($display_meta),
            'sequence_number'   => $max_seq + 1
        ])->id;       
        
        //CONDITIONAL FIELDS
        $cfields = WritingFields::all();            

        $data = view('admin.forms.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        return Response()->json([
            'id'            => $id,
            "success"       => true,
            "field"         => $data,
        ]); 
    }

    public function saveDropDownSelect(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $description = $request->description;
        $maximum_characters = $request->maximum_characters;
        $required = $request->required;
        $selected_choices = $request->selected_choices;

        $type = 'dropdownSelect';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            'maximum_characters'    => $request->maximum_characters,
            'selected_choices'      => $request->selected_choices, 
            'type'                  => $type,
        ];

        array_walk_recursive($display_meta, function(&$item){
            if(is_null($item)) $item = '';
        });

        $max_seq = WritingFields::where('form_id', $form_id)->max('sequence_number');

        $id = WritingFields::Create([
            'form_id'           => $form_id,
            'name'              => $request->label,
            'description'       => $request->description,
            'type'              => $type,
            'display_meta'      => json_encode($display_meta),
            'sequence_number'   => $max_seq + 1
        ])->id;   
        

        //CONDITIONAL FIELDS
        $cfields = WritingFields::all();
        
        
        $data = view('admin.forms.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'selected_choices', 'required', 'display_meta', 'cfields' ))->render();

        return Response()->json([
            "success"       => true,
            'id'            => $id,
            "field"          => $data,
            "selected_choices" => $selected_choices
        ]);         
    }


    public function saveHTMLContent(Request $request) {

        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $content = $request->content;       
        $type = 'htmlContent';

        $display_meta = [
            //'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'content'               => $request->content,
            'maximum_characters'    => $request->maximum_characters,
            'selected_choices'      => $request->selected_choices, 
            'type'                  => $type,
        ];
        
        array_walk_recursive($display_meta, function(&$item){
            if(is_null($item)) $item = '';
        });

        $max_seq = WritingFields::where('form_id', $form_id)->max('sequence_number');

        $id = WritingFields::Create([
            'form_id'           => $form_id,
            'name'              => $request->label,
            'description'       => null, //content(saved to: display_meta)
            'type'              => $type,
            'display_meta'      => json_encode($display_meta),
            'sequence_number'   => $max_seq + 1
        ])->id;

        //CONDITIONAL FIELDS
        $cfields = WritingFields::all();        
        
        $data = view('admin.forms.htmlContent', compact('id', 'label', 'display_meta', 'cfields'))->render();

        return Response()->json([
            "success"       => true,
            'id'            => $id,
            "field"          => $data,           
        ]);   


    }
  
    public function removeField(Request $request)
    {
        $field = WritingFields::where('form_id', $request->formID)->where('id', $request->id)->first();

        if ($field) {
            $field->delete();
            return Response()->json([
                "success"       => true,
            ]); 
        } else {
            return Response()->json([
                "success"       => false,
            ]);             
        }

    }
    
    public function getHTMLFieldContent(Request $request) 
    {

        $field = WritingFields::where('form_id', $request->formID)->where('id', $request->field_id)->first();
        $meta = json_decode($field->display_meta, true);

        if (isset($meta['content'])) {

            return Response()->json([
                "success"       => true,
                "content"       => $meta['content']
            ]);          }

    }
}