<?php

namespace App\Models;



use App\Models\ConditionalFieldLogic;

use Illuminate\Database\Eloquent\Model;

class FormFields extends Model
{
    //Writing Fields and Form Fields are merged

    public $table = "writing_fields";

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = false;


    public function generateFrontEndFormFieldHTML($formField, $cfields) 
    {
        //covert json objec to array                
        $display_meta = (array) json_decode($formField->display_meta, true);
        $id     = $formField->id;                
        $label  = $formField->name;
        $required  = $formField->required;
        $maximum_characters  = $formField->maximum_characters;
        

        if (isset($display_meta['description'])) {
            $description = $display_meta['description'];
        } else {
            $description = null;
        }

        if (isset($display_meta['content'])) {
            $content = $display_meta['content'];
        } else {
            $content = null;
        }            

        $formFieldHTML = "";

        if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
        {
            $type   = 'simpletextfield';                               
            $formFieldHTML = view('modules.writing.fields.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

            $type   = 'dropdownselect';                               
            $formFieldHTML = view('modules.writing.fields.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdownteacherselect") {

            $type   = 'dropdownteacherselect';                               
            $formFieldHTML = view('modules.writing.fields.dropdownTeacherSelect', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();


        } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {

            $type   = 'html';                               
            $formFieldHTML = view('modules.writing.fields.htmlContent', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "firstname" || strtolower($formField->type) == "firstnamefield") {

            $type   = 'firstnamefield';                               
            $formFieldHTML = view('modules.writing.fields.firstnamefield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();
            
        } else if (strtolower($formField->type) == "lastname" || strtolower($formField->type) == "lastnamefield") {

            $type   = 'lastnamefield';                               
            $formFieldHTML = view('modules.writing.fields.lastnamefield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "email" || strtolower($formField->type) == "emailfield") {

            $type   = 'emailfield';                               
            $formFieldHTML = view('modules.writing.fields.emailfield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "upload" || strtolower($formField->type) == "uploadfield") {

            $type   = 'uploadfield';                               
            $formFieldHTML = view('modules.writing.fields.uploadfield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "paragraphtext" ) {

            $type   = 'paragraphtext';                               
            $formFieldHTML = view('modules.writing.fields.paragraphtext', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        }        

        return $formFieldHTML;      
    }



    public function generateFrontEndFormFieldHTML_V2($formField, $cfields) 
    {
        //covert json objec to array                
        $display_meta = (array) json_decode($formField->display_meta, true);
        $id     = $formField->id;                
        $label  = $formField->name;
        $required  = $formField->required;
        $maximum_characters  = $formField->maximum_characters;
        

        if (isset($display_meta['description'])) {
            $description = $display_meta['description'];
        } else {
            $description = null;
        }

        if (isset($display_meta['content'])) {
            $content = $display_meta['content'];
        } else {
            $content = null;
        }            

        $formFieldHTML = "";

        if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
        {
            $type   = 'simpletextfield';                               
            $formFieldHTML = view('modules.writing.fields_v2.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

            $type   = 'dropdownselect';                               
            $formFieldHTML = view('modules.writing.fields_v2.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdownteacherselect") {

            $type   = 'dropdownteacherselect';                               
            $formFieldHTML = view('modules.writing.fields_v2.dropdownTeacherSelect', compact('id', 'label', 'description', 'required', 'display_meta', 'cfields'))->render();


        } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {

            $type   = 'html';                               
            $formFieldHTML = view('modules.writing.fields_v2.htmlContent', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "firstname" || strtolower($formField->type) == "firstnamefield") {

            $type   = 'firstnamefield';                               
            $formFieldHTML = view('modules.writing.fields_v2.firstnamefield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();
            
        } else if (strtolower($formField->type) == "lastname" || strtolower($formField->type) == "lastnamefield") {

            $type   = 'lastnamefield';                               
            $formFieldHTML = view('modules.writing.fields_v2.lastnamefield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "email" || strtolower($formField->type) == "emailfield") {

            $type   = 'emailfield';                               
            $formFieldHTML = view('modules.writing.fields_v2.emailfield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "upload" || strtolower($formField->type) == "uploadfield") {

            $type   = 'uploadfield';                               
            $formFieldHTML = view('modules.writing.fields_v2.uploadfield', compact('id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "paragraphtext" ) {

            $type   = 'paragraphtext';                               
            $formFieldHTML = view('modules.writing.fields_v2.paragraphtext', compact('id', 'label', 'content','display_meta', 'cfields'))->render();

        }        

        return $formFieldHTML;      
    }


    /* generate Admin Fields */
    public function generateFormFieldHTML($formField, $cfields) 
    {

      

        //covert json objec to array                
        $display_meta = (array) json_decode($formField->display_meta, true);
        $id     = $formField->id;  
        $page_id = $formField->page_id;              
        $label  = $formField->name;
        
        $required = $formField->required;
        $maximum_characters = $formField->maximum_characters;
        $description = $formField->description;


        if (isset($display_meta['description'])) {
            $description = $display_meta['description'];
        }
        

        if (isset($display_meta['content'])) {
            $content = $display_meta['content'];
        } else {
            $content = "";
        }

        $formFieldHTML = "";

        if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
        {
            $type   = 'simpletextfield';                               
            $formFieldHTML = view('admin.forms.simpleText', compact('id', 'page_id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

            $type   = 'dropdownselect';                               
            $formFieldHTML = view('admin.forms.dropdownSelect', compact('id', 'page_id','label', 'description', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdownteacherselect") {

            $type   = 'dropdownteacherselect';                               
            $formFieldHTML = view('admin.forms.dropdownTeacherSelect', compact('id', 'page_id','label', 'description', 'required', 'display_meta', 'cfields'))->render();            

        } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {

            $type   = 'html';                               
            $formFieldHTML = view('admin.forms.htmlContent', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "firstname" || strtolower($formField->type) == "firstnamefield") {

            $type   = 'firstnamefield';                               
            $formFieldHTML = view('admin.forms.firstname', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "lastname" || strtolower($formField->type) == "lastnamefield") {

            $type   = 'lastnamefield';                               
            $formFieldHTML = view('admin.forms.lastname', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "email" || strtolower($formField->type) == "emailfield") {

            $type   = 'emailfield';                               
            $formFieldHTML = view('admin.forms.email', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "upload" || strtolower($formField->type) == "uploadfield") {

            $type   = 'uploadfield';                               
            $formFieldHTML = view('admin.forms.upload', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "paragraphtext" ) {
            $type   = 'paragraphtext';       
            $formFieldHTML = view('admin.forms.paragraphtext', compact('id', 'page_id', 'label', 'description','display_meta', 'cfields'))->render();
        }    

        return $formFieldHTML;
    }


    public function generateFormViewFieldHTML($formField, $cfields) 
    {
        //covert json objec to array                
        $display_meta = (array) json_decode($formField->display_meta, true);
        $id     = $formField->id;  
        $page_id = $formField->page_id;              
        $label  = $formField->name;
        
        $required = $formField->required;
        $maximum_characters = $formField->maximum_characters;
        $description = $formField->description;


        if (isset($display_meta['description'])) {
            $description = $display_meta['description'];
        }
        

        if (isset($display_meta['content'])) {
            $content = $display_meta['content'];
        } else {
            $content = "";
        }

        $formFieldHTML = "";

        if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
        {
            $type   = 'simpletextfield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.simpleText', compact('id', 'page_id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

            $type   = 'dropdownselect';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.dropdownSelect', compact('id', 'page_id','label', 'description', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdownteacherselect") {

            $type   = 'dropdownteacherselect';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.dropdownTeacherSelect', compact('id', 'page_id','label', 'description', 'required', 'display_meta', 'cfields'))->render();            

        } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {

            $type   = 'html';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.htmlContent', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "firstname" || strtolower($formField->type) == "firstnamefield") {

            $type   = 'firstnamefield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.firstname', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "lastname" || strtolower($formField->type) == "lastnamefield") {

            $type   = 'lastnamefield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.lastname', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "email" || strtolower($formField->type) == "emailfield") {

            $type   = 'emailfield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.email', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "upload" || strtolower($formField->type) == "uploadfield") {

            $type   = 'uploadfield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.upload', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "paragraphtext" ) {
            $type   = 'paragraphtext';       
            $formFieldHTML = view('admin.modules.writing.includes.fields.view.paragraphtext', compact('id', 'page_id', 'label', 'description','display_meta', 'cfields'))->render();
        }    

        return $formFieldHTML;
    }

    public function generateFormEditFieldHTML($formField, $cfields) 
    {
        //covert json objec to array                
        $display_meta = (array) json_decode($formField->display_meta, true);

       
        $id         = $formField->id;  
        $page_id    = $formField->page_id;              
        $label      = $formField->name;
        
        $required = $formField->required;
        $maximum_characters = $formField->maximum_characters;
        $description = $formField->description;


        if (isset($display_meta['description'])) {
            $description = $display_meta['description'];
        }
        

        if (isset($display_meta['content'])) {
            $content = $display_meta['content'];
        } else {
            $content = "";
        }

        $formFieldHTML = "";

        if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
        {
            $type   = 'simpleText';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.simpleText', compact('id', 'page_id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

            $type   = 'dropdownselect';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.dropdownSelect', compact('id', 'page_id','label', 'description', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdownteacherselect") {

            $type   = 'dropdownteacherselect';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.dropdownTeacherSelect', compact('id', 'page_id','label', 'description', 'required', 'display_meta', 'cfields'))->render();            

        } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {

            $type   = 'html';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.htmlContent', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "firstname" || strtolower($formField->type) == "firstnamefield") {

            $type   = 'firstnamefield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.firstname', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "lastname" || strtolower($formField->type) == "lastnamefield") {

            $type   = 'lastnamefield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.lastname', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "email" || strtolower($formField->type) == "emailfield") {

            $type   = 'emailfield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.email', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "upload" || strtolower($formField->type) == "uploadfield") {

            $type   = 'uploadfield';                               
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.upload', compact('id', 'page_id', 'label', 'content','display_meta', 'cfields'))->render();
        
        } else if (strtolower($formField->type) == "paragraphtext" ) {
            $type   = 'paragraphtext';       
            $formFieldHTML = view('admin.modules.writing.includes.fields.update.paragraphtext', compact('id', 'page_id', 'label', 'description','display_meta', 'cfields'))->render();
        }    

        return $formFieldHTML;
    }

    public function includeFormFieldHTML($formField, $cfields) 
    {

        //covert json objec to array                
        $display_meta = (array) json_decode($formField->display_meta, true);
        $id     = $formField->id;  
        $page_id = $formField->page_id;              
        $label  = $formField->name;
        
        $required = $formField->required;
        $maximum_characters = $formField->maximum_characters;

        //Get standard field
        if (isset($display_meta['description'])) {
            $description =  $display_meta['description'];
        } else {
            $description =  "";
        }
      
        //content for HTML
        if (isset($display_meta['content'])) 
        {
            $content = $display_meta['content'];
        } else {
            $content = '';
        }
        

        $cf_items = "";

        if ( strtolower($formField->type) == "simpletext" || strtolower($formField->type) == "simpletextfield") 
        {
            $textType   = "Simple Field Text";
            $type       = 'simpleText';
            
            return [
                    'texttype' => $textType,
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                    'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.simpleText', 
            ];

        } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

            $textType   = "Drop Down Select";
            $type   = 'dropdownselect';
           

            return [      
                    'texttype' => $textType,            
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                     'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.dropdownSelect', 
                ];


        } else if (strtolower($formField->type) == "dropdownteacherselect") {

            $textType   = "Teacher Select";
            $type   = 'dropdownteacherselect';                               
           
            return [                
                    'texttype' => $textType,  
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                     'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.dropdownTeacherSelect', 
                ];

        } else if (strtolower($formField->type) == "html" || strtolower($formField->type) == "htmlcontent") {

            $textType   = "HTML Content";
            $type   = 'html';                               
            
            return [                  
                    'texttype' => $textType,
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                    'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.htmlContent', 
                ];            

        } else if (strtolower($formField->type) == "firstname" || strtolower($formField->type) == "firstnamefield") {


            $textType   = "First Name ";
            $type   = 'firstnamefield';                               
           
            return [      
                    'texttype' => $textType,            
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                     'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.firstname', 
                ];
        
        } else if (strtolower($formField->type) == "lastname" || strtolower($formField->type) == "lastnamefield") {

            $textType   = "Last Name ";
            $type   = 'lastnamefield';                               
         
            return [        
                    'texttype' => $textType,          
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                     'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.lastname', 
                ];         

        } else if (strtolower($formField->type) == "email" || strtolower($formField->type) == "emailfield") {

            $textType   = "E-Mail";    
            $type   = 'emailfield';                               
            
            return [  
                    'texttype' => $textType,                
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                     'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.email', 
                ];            
        
        } else if (strtolower($formField->type) == "upload" || strtolower($formField->type) == "uploadfield") {


            $textType   = "Upload";    
            $type   = 'uploadfield';                               
            
            return [     
                    'texttype' => $textType,             
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                     'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.upload', 
                ];            
        
        } else if (strtolower($formField->type) == "paragraphtext" ) {

            $textType   = "Paragraph Text";    
            $type   = 'paragraphtext';       
            
            return [      
                    'texttype' => $textType,            
                    'type' => $type,
                    'id'    => $id, 
                    'page_id' => $page_id,
                    'label' => $label,
                    'description' => $description,
                    'content'   => $content,
                    'maximum_characters' => $maximum_characters,
                    'required' => $required,
                    'display_meta' => $display_meta,
                    'cfields'   => $cfields,
                    'cf_items'       => $cf_items ?? '',
                    'template'=> 'admin.modules.writing.includes.fields.view.paragraphtext', 
                ];

        }    

      
    }

}
