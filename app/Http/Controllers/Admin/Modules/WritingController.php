<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormFields;
use App\Models\ConditionalFieldLogic;
use App\Models\WritingEntries;
use App\Models\UploadFile;
use App\Models\Tutor;
use Gate, Auth;

class WritingController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            //authenticated by has no "admin_access" in his role attached
            //@do: redirect to home (authenticated member will be his view)
            if (Gate::denies('admin_access')) {
                return redirect(route('home'));
            }
            return $next($request);           
        });    
    }
    
    
    public function index(FormFields $formFieldModel) 
    {
        $form_id = 1; //all forms are 1(for now)
        $formFields = FormFields::where('form_id', $form_id)->where('page_id', 0)->orderBy('sequence_number', 'ASC')->get();
        $formFieldHTML[] = "";

        //CONDITIONAL FIELDS (init)
        $cfields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();

        foreach ($formFields as $formField) 
        {
            $formFieldHTML[] = $formFieldModel->generateFormFieldHTML($formField, $cfields);
        }

        // GET CHILDREN HTML
        $formFieldChildrenHTML[] = "";

        $pages =  FormFields::distinct()->where('page_id', '>=', 1)->orderBy('page_id', 'ASC')->get(['page_id']);
        $pageCounter =  $pages->count() + 1;        

        foreach ($pages as $page) 
        {           
            $formChildFields = FormFields::where('form_id', $form_id)->where('page_id', $page->page_id)->orderBy('sequence_number', 'ASC')->get();
            $child_cfields = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();           
            foreach ($formChildFields as $formChildField) 
            {
                $formFieldChildrenHTML[$page->page_id][] =  $formFieldModel->generateFormFieldHTML($formChildField, $child_cfields);
            }
        }

        return view('admin.modules.writing.index', compact('pages', 'pageCounter',  'form_id', 'formFields', 'formFieldHTML', 'formFieldChildrenHTML'));
    }


    public function entries($form_id, Tutor $tutor) 
    {
        //get form fields for name of header
        $formFields  = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();

        //Get Entry of data
        $entries     = WritingEntries::where('form_id', $form_id)->get();

        $tutors      = $tutor->getTutors();
        
        return view('admin.modules.writing.entries', compact('form_id','entries','formFields', 'tutors'));
    }

    public function entry($form_id, $entry_id, Tutor $tutor)  {

        //get form fields for name of header
        $formFields  = FormFields::where('form_id', $form_id)->orderBy('sequence_number', 'ASC')->get();

        //Get Entry of data
        $entries     = WritingEntries::where('form_id', $form_id)->where('id', $entry_id)->get();

        $tutors      = $tutor->getTutors();

        return view('admin.modules.writing.entry', compact('form_id', 'entry_id', 'entries','formFields', 'tutors'));
    }


    public function preview($id, FormFields $formFieldModel) 
    {        
        $form_id = $id; 
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
        return view('admin.modules.writing.preview', compact('pages', 'pageCounter', 'form_id','formFields', 'formFieldHTML', 'formFieldChildrenHTML'));

    }


    public function update(Request $request,  UploadFile $uploadFile) 
    {
        $form_id = 1;

        if (is_array($request->id) == false) {
            return redirect()->route('admin.writing.index', $form_id)->with('error_message', "Form can't be updated, please add a field from fields menu");
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

        return redirect()->route('admin.writing.index', $form_id)->with('message', 'Form updated successfully!');
    }


    public function store(Request $request, UploadFile $uploadFile) {
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

       WritingEntries::create([
            'form_id'               => $request->get('form_id'),
            'user_id'               => Auth::user()->id,
            'appointed_tutor_id'    => null,
            'value'                 => json_encode($fields)
       ]);

       return redirect()->route('admin.writing.index')->with('message', 'Writing entry has been added successfully!');
    }


}