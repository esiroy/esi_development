<?php

namespace App\Models;

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
            $description = "";
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
            $formFieldHTML = view('modules.writing.fields.simpleText', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

        } else if (strtolower($formField->type) == "dropdown" || strtolower($formField->type) == "dropdownselect") {

            $type   = 'dropdownselect';                               
            $formFieldHTML = view('modules.writing.fields.dropdownSelect', compact('id', 'label', 'description', 'maximum_characters', 'required', 'display_meta', 'cfields'))->render();

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



}
