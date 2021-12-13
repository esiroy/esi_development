<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UploadFile;
use App\Models\WritingFields;
use App\Models\WritingEntries;

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
            'conditional_logic'     => false,
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


    public function saveParagraphText(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $required = $request->required;
        $description = $request->description;
        $enableWordLimit = $request->enableWordLimit;
        $wordLimit = $request->wordLimit;

        $type = 'paragraphtext';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            'enableWordLimit'       => $request->enableWordLimit,
            'wordLimit'             => $request->wordLimit,      
            'type'                  => $type,
            'conditional_logic'     => false,
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

        $data = view('admin.forms.paragraphtext', compact('id', 'label', 'description', 'required', 'enableWordLimit', 'wordLimit', 'display_meta', 'cfields'))->render();

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
            'conditional_logic'     => false,            
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
            'conditional_logic'     => false,            
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
  

    /** advance field */
    public function saveFirstNameField(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $required = $request->required;
        $description = $request->description;
        //$maximum_characters = $request->maximum_characters;
        $type = 'firstnamefield';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            //'maximum_characters'    => $request->maximum_characters,
            'type'                  => $type,
            'conditional_logic'     => false,            
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

        $data = view('admin.forms.firstname', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

        return Response()->json([
            'id'            => $id,
            "success"       => true,
            "field"         => $data,
        ]); 
    }    

    
    public function saveLastNameField(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $required = $request->required;
        $description = $request->description;
        //$maximum_characters = $request->maximum_characters;
        $type = 'lastnamefield';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            //'maximum_characters'    => $request->maximum_characters,
            'type'                  => $type,
            'conditional_logic'     => false,            
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

        $data = view('admin.forms.lastname', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

        return Response()->json([
            'id'            => $id,
            "success"       => true,
            "field"         => $data,
        ]); 
    }
        
    public function saveEmailField(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $required = $request->required;
        $description = $request->description;
        //$maximum_characters = $request->maximum_characters;
        $type = 'emailfield';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            //'maximum_characters'    => $request->maximum_characters,
            'type'                  => $type,
            'conditional_logic'     => false,            
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

        $data = view('admin.forms.email', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

        return Response()->json([
            'id'            => $id,
            "success"       => true,
            "field"         => $data,
        ]); 
    }    

    public function saveUploadField(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $required = $request->required;
        $description = $request->description;
        //$maximum_characters = $request->maximum_characters;
        $type = 'uploadfield';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            //'maximum_characters'    => $request->maximum_characters,
            'type'                  => $type,
            'conditional_logic'     => false,            
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

        $data = view('admin.forms.upload', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

        return Response()->json([
            'id'            => $id,
            "success"       => true,
            "field"         => $data,
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


    public function upload(Request $request,  UploadFile $uploadFile) 
    {
        $file = $request->file('file');

        $storagePath = 'public/uploads/writing_materials/';

        $uploadFileName = $uploadFile->uploadFile($storagePath, $file);

        if ($uploadFileName) 
        {
            //echo "uploaded $uploadFileName : $file <BR>" ;

            return Response()->json([
                "success"               => true,
                "filenameuploaded"      => $uploadFileName,
                "file"                  => $file,
                "message"               => "uploaded"
            ]);    

        }
    }

    public function getWritingImages(Request $request) 
    {
        $mp3Image = url('images/audio.png');    
        $imageHTML = view('admin.modules.writing.includes.imageGallery.images', compact('mp3Image'))->render();
        
        return Response()->json([
            "success"       => true,
            "imageHTML"     => $imageHTML
        ]);

    }


    public function assignTutor(Request $request) {
        $entryID = $request->get('entryID');
        $tutorID = $request->get('tutorID');

        $entry = WritingEntries::find($entryID);

        if ($entry) {

            if (!$tutorID) {
                $tutorID = null;
            }

            $updatedEntryID = $entry->update([
                'appointed_tutor_id' => $tutorID
            ]);   
            
            return Response()->json([
                "success"           => true,   
                "appointed_tutor_id" => $tutorID,            
            ]);   
        }
    }
}
