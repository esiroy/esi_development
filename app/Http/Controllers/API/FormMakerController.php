<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UploadFile;
use App\Models\WritingFields;
use App\Models\FormFields;
use App\Models\WritingEntries;
use App\Models\ConditionalFieldLogic;
use App\Models\WritingEntryGrade;

use App\Models\AgentTransaction;
use App\Models\Member;
use Auth;

class FormMakerController extends Controller
{


    /* 
        get options and get writing_conditional_logic  selected option
        @var fieldID            : current option id
        @var selectedOptionID   : the selected option
    */
    public function getDropDownOptions (Request $request) 
    {    
        $fieldID           = $request->get('fieldID');
        $selectedOptionID  = $request->get('selectedOptionID');

        $formField = FormFields::find($selectedOptionID);


        if ($formField) 
        {
            $display_meta = json_decode($formField->display_meta, true);
            $options =  $display_meta['selected_choices'];

            $selected_value = ConditionalFieldLogic::where('field_id', $fieldID)->where('selected_option_id', $selectedOptionID)->first();

            return Response()->json([
                "success"       => true,
                "message"       => "Field Options successfully fetched",            
                "options"       => $options,  
                "selected_value" => $selected_value
            ]); 

        } else {
            return Response()->json([
                "success"       => false,
                "message"          => "No options found",            
            ]);
        }
    
    }


    public function editFormField(Request $request, FormFields $formFields) 
    {    
        $form_id = 1;
        $id = $request->get('id');
        $field = $formFields->find($id);
        $cfields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();        
        $data = $formFields->generateFormEditFieldHTML($field, $cfields);        
        return Response()->json([
            "success"       => true,
            'id'            => $id,
            "field"          => $data,
           
        ]); 
    }




    public function saveWritingFields(Request $request,  UploadFile $uploadFile) 
    {
        $form_id = 1;  

        if (is_array($request->id) == false) {
            return Response()->json([
                "success"       => false,
                "message"       => "Form can't be updated, please add a field from fields menu"
            ]); 
        }


        $ids = ConditionalFieldLogic::where('form_id', $form_id)->delete();


        //initial sq number
        $sequence_number = 1;

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

            } else if (strtolower($request[$id.'_fieldType']) == "dropdownteacherselect") {                          

                $type = 'dropdownteacherselect';  
                $display_meta['type'] = $type;

            } else if (strtolower($request[$id.'_fieldType']) == "simpletextfield") {
                $type = 'simpletextfield';
                $display_meta['type'] = $type;
                $display_meta['maximum_characters'] = $maximum_characters;

            } else if (strtolower($request[$id.'_fieldType']) == "html" || strtolower($request[$id.'_fieldType']) == "htmlcontent") {
                $type = 'htmlContent';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];;

            } else if (strtolower($request[$id.'_fieldType']) == "firstname" || strtolower($request[$id.'_fieldType']) == "firstnamefield") {
                $type = 'firstnamefield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];

            } else if (strtolower($request[$id.'_fieldType']) == "lastname" || strtolower($request[$id.'_fieldType']) == "lastnamefield") {
                $type = 'lastnamefield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];
            
            } else if (strtolower($request[$id.'_fieldType']) == "email" || strtolower($request[$id.'_fieldType']) == "emailfield") {
                $type = 'emailfield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];


            } else if (strtolower($request[$id.'_fieldType']) == "upload" || strtolower($request[$id.'_fieldType']) == "uploadfield") {
                $type = 'uploadfield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];

            } else if (strtolower($request[$id.'_fieldType']) == "paragraphtext") {
                $type = 'paragraphtext';
                $display_meta['type'] = $type;

                //word limiter
                $display_meta['enableWordLimit'] =  ($request[$id.'_enableWordLimit'] == "on") ? true : false; 
                $display_meta['wordLimit'] = $request[$id.'_wordLimit'];
                  

                //enable member credit checker [180 = 1 point ] [180 to 500 words  = 2 points ] [501 to 800 = 3 points]
                $display_meta['memberPointChecker'] =  ($request[$id.'_memberPointChecker'] == "on") ? true : false;
            }
            

            //Conditonal Field Logic
            if (isset( $request[$id.'_cfield_id'] )) {
                foreach ($request[$id.'_cfield_id'] as $key => $fieldID) {
                    $cfID               = $request[$id.'_cfield_id'][$key];
                    $cfRule              = $request[$id.'_cfield_rule'][$key];
                    $cfValue            = ($request[$id.'_cfield_value'][$key]) ?? "";

                    ConditionalFieldLogic::create([
                        'form_id'               =>  $form_id,
                        'field_id'              =>  $id,
                        'selected_option_id'    =>  $cfID,
                        'field_rule'            =>  $cfRule,
                        'field_value'           => $cfValue,
                    ]);
                }
            }

            //GET the page array number for the page
            if (isset($request[$id.'_page'])) {
                $formPageArr = explode("-", $request[$id.'_page']);            
                $page = $formPageArr[1];          
                                
            }

            $form = FormFields::find($id);
            if ($form) {
                $form->update([
                    'form_id'           => $form_id,
                    'page_id'           => $page,
                    'name'              => $label,
                    'description'       => $description,
                    'type'              => $type,
                    'display_meta'      => json_encode($display_meta),
                    'sequence_number'     => $sequence_number
                ]); 
            }

            $sequence_number = $sequence_number + 1;
        }            

        return Response()->json([
            "success"       => true,
            "message"       => "Updated!"
        ]); 
    }  


    public function sortWritingFields(Request $request) 
    {    
        $form_id = $request->get('formID');
        $sorting = json_decode($request->get('sorting'), true);
        $sequence_number = 1;        
        foreach($sorting as $sort) 
        {

            $field = FormFields::find($sort['id']);

            if ($field) {

                if (isset($sort['page'])) {
                    $formPageArr = explode("-", $sort['page']);            
                    $page = $formPageArr[1];
                }
                

                $field->update([
                    'form_id'           => $form_id,
                    'page_id'           => $page,
                    'sequence_number'   => $sequence_number
                ]); 

            }
             $sequence_number = $sequence_number + 1;


        }

        return Response()->json([
            "success"       => true,
            "message"       =>  $sorting 
        ]); 
    }


    public function updateWritingFields(Request $request,  UploadFile $uploadFile) 
    {
        $form_id = 1;  

        if (is_array($request->id) == false) 
        {
            return Response()->json([
                "success"       => false,
                "message"       => "Form can't be updated, please add a field from fields menu"
            ]); 
        }  

        //initial sq number
        $sequence_number = 1;

        ConditionalFieldLogic::where('form_id', $form_id)->where('field_id', $request->id)->delete();


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

            } else if (strtolower($request[$id.'_fieldType']) == "dropdownteacherselect") {                          

                $type = 'dropdownteacherselect';  
                $display_meta['type'] = $type;

            } else if (strtolower($request[$id.'_fieldType']) == "simpletextfield") {
                $type = 'simpletextfield';
                $display_meta['type'] = $type;
                $display_meta['maximum_characters'] = $maximum_characters;

            } else if (strtolower($request[$id.'_fieldType']) == "html" || strtolower($request[$id.'_fieldType']) == "htmlcontent") {
                $type = 'htmlContent';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];;

            } else if (strtolower($request[$id.'_fieldType']) == "firstname" || strtolower($request[$id.'_fieldType']) == "firstnamefield") {
                $type = 'firstnamefield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];

            } else if (strtolower($request[$id.'_fieldType']) == "lastname" || strtolower($request[$id.'_fieldType']) == "lastnamefield") {
                $type = 'lastnamefield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];
            
            } else if (strtolower($request[$id.'_fieldType']) == "email" || strtolower($request[$id.'_fieldType']) == "emailfield") {
                $type = 'emailfield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];


            } else if (strtolower($request[$id.'_fieldType']) == "upload" || strtolower($request[$id.'_fieldType']) == "uploadfield") {
                $type = 'uploadfield';
                $display_meta['type'] = $type;                
                $display_meta['content'] = $request[$id.'_content'];

            } else if (strtolower($request[$id.'_fieldType']) == "paragraphtext") {
                $type = 'paragraphtext';
                $display_meta['type'] = $type;

                //word limiter
                $display_meta['enableWordLimit'] =  ($request[$id.'_enableWordLimit'] == "on") ? true : false; 
                $display_meta['wordLimit'] = $request[$id.'_wordLimit'];
                  

                //enable member credit checker [180 = 1 point ] [180 to 500 words  = 2 points ] [501 to 800 = 3 points]
                $display_meta['memberPointChecker'] =  ($request[$id.'_memberPointChecker'] == "on") ? true : false;
            }
            

            //Conditonal Field Logic
            if (isset( $request[$id.'_cfield_id'] )) 
            {
                foreach ($request[$id.'_cfield_id'] as $key => $fieldID) 
                {
                    $cfID               = $request[$id.'_cfield_id'][$key];
                    $cfRule              = $request[$id.'_cfield_rule'][$key];
                    $cfValue            = ($request[$id.'_cfield_value'][$key]) ?? "";


                    if ($conditional_logic == true) {
                        ConditionalFieldLogic::create([
                            'form_id'               =>  $form_id,
                            'field_id'              =>  $id,
                            'selected_option_id'    =>  $cfID,
                            'field_rule'            =>  $cfRule,
                            'field_value'           =>  $cfValue,
                        ]);
                    }


                }
            }

            //GET the page array number for the page
            if (isset($request[$id.'_page'])) {
                $formPageArr = explode("-", $request[$id.'_page']);            
                $page = $formPageArr[1];
            }

            $form = FormFields::find($id);
            if ($form) {
                $form->update([
                    'form_id'           => $form_id,
                    'page_id'           => $page,
                    'name'              => $label,
                    'description'       => $description,
                    'type'              => $type,
                    'display_meta'      => json_encode($display_meta),
                    //'sequence_number'     => $sequence_number
                ]); 
            }

            $sequence_number = $sequence_number + 1;
        }            

        return Response()->json([
            "success"       => true,
            "message"       => "Updated!"
        ]); 
    }    



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

        $data = view('admin.modules.writing.includes.fields.view.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

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
        $memberPointChecker = $request->memberPointChecker;
        $wordLimit = $request->wordLimit;

        $type = 'paragraphtext';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            'memberPointChecker'    => $request->memberPointChecker,
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

        $data = view('admin.modules.writing.includes.fields.view.paragraphtext', compact('id', 'label', 'description', 'required', 'enableWordLimit', 'wordLimit', 'display_meta', 'cfields'))->render();

        return Response()->json([
            'id'            => $id,
            "success"       => true,
            "field"         => $data,
        ]); 
    }

    public function copyField(Request $request, FormFields $formFields )
    {       
        $field = WritingFields::find($request->fieldID);
        $pageSlug = explode('-', $request->pageID);
        $page = $pageSlug[1];

        $newField = $field->replicate();
        $newField->page_id = $page;
        $newField->save();
       

        $conditionalFields = ConditionalFieldLogic::where('field_id', $request->fieldID)->get();
        foreach($conditionalFields as $condtionalField)
        {
            $replicated = $condtionalField->replicate();
            $replicated->field_id = $newField->id;
            $replicated->save();
        }

        $display_meta = json_decode($newField->display_meta, true);

        $id             = $newField->id;
        $label          = $newField->label;


        $data = $formFields->generateFormViewFieldHTML($newField, WritingFields::all());

        return Response()->json([
            'id'            => $id,
            'pageID'        => $request->pageID,
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
        
        
        $data = view('admin.modules.writing.includes.fields.view.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'selected_choices', 'required', 'display_meta', 'cfields' ))->render();

        return Response()->json([
            "success"       => true,
            'id'            => $id,
            "field"          => $data,
            "selected_choices" => $selected_choices
        ]);         
    }



    public function saveDropDownTeacherSelect(Request $request) 
    {
        //FORM ID
        $form_id = 1;

        $label = $request->label;
        $description = $request->description;
        $maximum_characters = $request->maximum_characters;
        $required = $request->required;
        //$selected_choices = $request->selected_choices;

        $type = 'dropdownTeacherSelect';

        $display_meta = [
            'required'              => $request->required,
            'label'                 => str_replace(' ', '_', $request->label),
            'description'           => $request->description,
            'maximum_characters'    => $request->maximum_characters,
            //'selected_choices'      => $request->selected_choices, 
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
        
        
        $data = view('admin.modules.writing.includes.fields.view.dropdownTeacherSelect', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields' ))->render();

        return Response()->json([
            "success"       => true,
            'id'            => $id,
            "field"          => $data,
            //"selected_choices" => $selected_choices
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
        
        $data = view('admin.modules.writing.includes.fields.view.htmlContent', compact('id', 'label', 'display_meta', 'cfields'))->render();

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

        $data = view('admin.modules.writing.includes.fields.view.firstname', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

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

        $data = view('admin.modules.writing.includes.fields.view.lastname', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

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

        $data = view('admin.modules.writing.includes.fields.view.email', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

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

        $data = view('admin.modules.writing.includes.fields.view.upload', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();

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

    public function getSubmittedWritingPoints(Request $request, Member $member, AgentTransaction $agentTransaction, WritingEntries $writingEntries) 
    {

        $userID = $request->userID;
        $targetFieldID = $request->field_id;
        $entries = $writingEntries->where('user_id', $request->userID)->get();

        $wordEntries = null;
        $unapprovedTotalDeduction = null;

        foreach ($entries as $entry) 
        {
            //exclude if it is already graded
            $entryGrade = WritingEntryGrade::where('writing_entry_id',  $entry->id)->first();
            //check if no grade then get the paragrapthTextfield
            if (!$entryGrade) 
            {
                $entryValue = (array) json_decode($entry->value, true);

                if (isset($entryValue[$targetFieldID . '_paragraphTextfield'])) {
                    $wordcount = str_word_count($entryValue[$targetFieldID . '_paragraphTextfield']);
                    $pointDeduct = $writingEntries->getWordPointDeduct($wordcount);
                } else {                
                    $wordcount = 0;
                    $pointDeduct = 0;
                }               

                //Add to array                
                //$wordEntries[] = ['id'        => $entry->id,'wordcount' => $wordcount,'point'     => $pointDeduct];
                $unapprovedTotalDeduction = $unapprovedTotalDeduction + $pointDeduct;
            }
        }

        //Currently Submitted 
        $memberInfo = $member->where('user_id', Auth::user()->id )->first();

        //Get the submitted deduction points
        $submittedWritingEntryPoints = $writingEntries->getWordPointDeduct($request->words);

        if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER' || Auth::user()->user_type == "TUTOR") 
        {
            return Response()->json([
                "success"  => true,
                "totalPointsLeft" => 1
            ]); 

            exit();
        } 

        if ($memberInfo->membership == "Monthly") {
            $getMonthlyLessonsLeft = $member->getMonthlyLessonsLeft();
            $pointsLeft = $getMonthlyLessonsLeft - $submittedWritingEntryPoints;

        } else if ($memberInfo->membership  == "Point Balance" ) {

            $credits = $agentTransaction->getCredits( Auth::user()->id ); 
            $pointsLeft = $credits - $submittedWritingEntryPoints;
        }

        if (isset($unapprovedTotalDeduction)) {
            $totalPointsLeft = $pointsLeft - $unapprovedTotalDeduction;
        } else {
            $totalPointsLeft = $pointsLeft;
        }
        
        if ($totalPointsLeft < 0) {
            if ($memberInfo->membership == "Monthly") 
            {
                $message = "Sorry, you don't have enough monthly credits </br>";
                $message .= "You have ". $getMonthlyLessonsLeft;

                if (isset($unapprovedTotalDeduction)) {
                     $message .= " and  ". $unapprovedTotalDeduction . " points of unapproved submitted writing entry";
                }
            } else {
                $message = "Sorry, you don't have enough point credits  </br>";
                $message .= "You have ". $credits . " points";

                if (isset($unapprovedTotalDeduction)) {
                     $message .= " and  ". $unapprovedTotalDeduction . " points of unapproved submitted writing entry";
                }
            }

        } else {
            $message = "successfully submitted";
        }

        return Response()->json([
            "success"  => true,   
            "message" =>  $message,
            //"wordEntries" => $wordEntries,
            "membership" => $memberInfo->membership,
            "getMonthlyLessonsLeft" => $getMonthlyLessonsLeft ?? "not applicable",
            "unapprovedTotalDeduction" => $unapprovedTotalDeduction,
            "pointsLeft" => $pointsLeft,
            "totalPointsLeft" => $totalPointsLeft
        ]);  

    }
}
