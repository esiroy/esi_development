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

        $data = view('admin.forms.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta'))->render();

        return Response()->json([
            "success"       => true,
            "field"          => $data,
        ]); 
    }


    public function saveDropDownSelect(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $description = $request->description;
        $maximum_characters = $request->maximum_characters;
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
        
        $data = view('admin.forms.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'selected_choices', 'required' ))->render();

        return Response()->json([
            "success"       => true,
            "field"          => $data,
            "selected_choices" => $selected_choices
        ]);         
    }
}
