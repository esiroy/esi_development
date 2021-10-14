@extends('layouts.admin')

@section('content')
    <div class="container bg-light px-0">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light ">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Writing</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container bg-light">
        <div class="row">
            <div class="col-md-8">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @elseif (session('error_message'))
                    <div class="alert alert-danger">
                        {{ session('error_message') }}
                    </div>
                @endif


                <div class="card esi-card mb-2">
                    <div id="form-navigation" class="card-body esi-card-body">              
                        <div class="form-inline">
                            <a class='text-success' href="{{ url('admin/writing/'.$form_id) }}">
                                <button class="btn btn-sm btn-outline-success mr-2" type="button">
                                    Edit
                                </button>
                            </a>
                            <a class='text-secondary' href="{{ url('admin/writing/entries/'.$form_id) }}">
                                <button class="btn btn-sm btn-outline-secondary mr-2" type="button">Entries</button>
                            </a>
                            <a class='text-secondary' href="{{ url('admin/writing/preview/'.$form_id) }}">
                                <button class="btn btn-sm btn-outline-secondary mr-2" type="button">                                
                                    Preview
                                </button>
                            </a>                            
                        </div>                                                
                    </div>
                </div>


                <div class="card esi-card">
                    <div class="card-header esi-card-header">
                        Form
                    </div>

                    <!--[START DYNAMIC FORMS]-->
                    <form id="dynamicForms" name="dynamicForms" method="POST"  action="{{ route('admin.writing.update', 1) }}">                                       
                     @csrf
                        <div id="form-content" class="card-body esi-card-body">
                            @foreach ($pages as $page)
                                <div id="page-{{ $page->page_id }}" class="card-header esi-card-header-page mb-4 droptrue  ">
                                    {{ "Page : ".  $page->page_id }}

                                    @if(isset($formFieldChildrenHTML[$page->page_id]))
                                        @foreach($formFieldChildrenHTML[$page->page_id] as $formFieldChildHTML) 
                                            {!! $formFieldChildHTML !!}
                                        @endforeach
                                    @endif


                                </div>
                            @endforeach

                            @foreach($formFieldHTML as $HTML) 
                                {!! $HTML !!}
                            @endforeach
                        </div>
                    </form>
                    <!--[START DYNAMIC FORMS]-->

                </div>

            </div>

            <div class="col-md-4">
                @include('admin.modules.writing.includes.fieldButtons')                
                <input type="button" value="Cancel" class="btn btn-danger mt-4" onclick="window.location.href='{{  url('admin/writing') }}' ">
                <input type="button" value="Update" class="btn btn-primary mt-4" onclick="event.preventDefault();document.getElementById('dynamicForms').submit();">               
            </div>

        </div>
    </div>


    @include('admin.modules.writing.includes.FormFields.simpleTextModal')
    @include('admin.modules.writing.includes.FormFields.dropdownSelectModal')
    @include('admin.modules.writing.includes.FormFields.htmlModal')

    <!-- ADVANCE FIELDS -->
    @include('admin.modules.writing.includes.FormFields.firstnameModal')
    @include('admin.modules.writing.includes.FormFields.lastnameModal')
    @include('admin.modules.writing.includes.FormFields.emailModal')
    @include('admin.modules.writing.includes.FormFields.uploadModal')


    <!--image gallery-->
    @include('admin.modules.writing.includes.ImageGallery.galleryModal')

@endsection

@section('styles')
    @parent
    <style>
        .fields .row {
            margin-bottom: 15px;
        }

        .form-label {
            margin-bottom: 0px;
            font-size: 12px;
        }

    </style>
@endsection

@section('scripts')

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js" defer></script>

    <script type="text/javascript">
        var api_token = "{{ Auth::user()->api_token }}";

        window.addEventListener('load', function() 
        {            

            let pageCtr = {{ $pageCounter ?? '1' }};

            $( ".tabs" ).tabs();


            $( ".sortable" ).sortable({ 
                connectWith: "div", 
                handle: '.handle'
            });

            $( ".droptrue" ).sortable({ 
                connectWith: "div", 
                handle: '.handle',
                update: function( event, ui) {
                    console.log($(this).attr('id'));
                    let page = $(this).attr('id');
                    $('#'+page).find('.page').val(page)
                }                
            });


            let ctr = 1;
            /***************************************************************
                            [START] - (TAB) [SHOW, HIDE - TAB OPTIONS]
            *****************************************************************/

            //SHOW FIELD WHEN FIELD CONTAINER IS CLICKED
            $(document).on("click", '.field_container', function() {
                let id = $(this).find('#id').val();
                let tabContainers = $(document).find('.tab-container');

                Array.from(tabContainers,function(e){
                    if ($(e).hasClass('open')) {                        
                        //$(e).parent().parent().css('border', "1px solid red")
                        let element = $(e).parent().parent().attr('id');
                        let elementID = getFieldID(element);

                        if (elementID !== id) {                         
                            $(e).removeClass('open');
                            $(e).slideToggle('slow');                        
                        }                       
                    }
                });                

                if (!$(this).find('.tab-container').hasClass('open')) {                    
                    $(this).find('.tab-container').addClass('open');
                    $(this).find('.tab-container').slideToggle('slow');
                }
            });
            

            //(DROPDOWN SELECT CHOICES) ON LOAD, HIDE DELETE WITH ONE ITEMS IN FIELD CHOICES 
            let fields = $(document).find('.field_container');
            Array.from(fields, (field, index) => {                
               let element = $('#'+field.id).find('#id');
               updateSelectedChoicesButtons(element.val() )              
            });

            /***************************************************************
                           CONDITIONAL FIELDS (ON LOAD)
            *****************************************************************/
            init_conditionalFields(false);

            function init_conditionalFields(addNewCFields) {
                let cfields = $(document).find('.field_container');
                Array.from(cfields, (cfield, findex) => {
                   let cfieldID = $('#'+cfield.id).find('#id').val();
                   let fieldsCtr = $("#"+ cfieldID +"_field").find('.conditional_fields').find('.row').length;
                   if (addNewCFields == true) {
                        addCField(cfieldID, 1);
                   }
                });                
            }

            function getLargest(array) {
               return Math.max(...array)
            }

            function getNewID(fieldID) {
                //GET MAX ID
                let elementCtrArr = [];
                let selecIDs = $("#"+ fieldID +"_field").find('.conditional_fields').find('.row').find('.cfield_id_select');
                Array.from(selecIDs, (selectID, findex) => {
                    let elementID = $(selectID).attr('id');
                    //console.log( "select ID  CTR ==> " + getFieldCtr(elementID) );

                    let elementCtr = getFieldCtr(elementID);
                    elementCtrArr.push(elementCtr);
                }); 
                return getLargest(elementCtrArr);
            }

            function addCField(fieldID, btnIndex) 
            {                
                let fieldsCtr = $("#"+ fieldID +"_field").find('.conditional_fields').find('.row').length;
                let insCtr = fieldsCtr + 1;                

                //check duplicate
                if ($('#'+fieldID+"_cfield_id_"+insCtr).length) {                   
                    insCtr = parseInt(getNewID(fieldID)) + 1;
                    console.log("duplicate , new id " + insCtr)
                } 


                //Conditional fields
                let cfield_id       = "<select id='"+fieldID+"_cfield_id_"+insCtr+"' name='"+fieldID+"_cfield_id[]' class='form-control form-control-sm cfield_id_select'></select>";
                let cfield_rule     = "<select id='"+fieldID+"_cfield_rule_"+insCtr+"' name='"+fieldID+"_cfield_rule[]' class='form-control form-control-sm cfield_rule_select'></select>";
                let cfield_value    = "<select id='"+fieldID+"_cfield_value_"+insCtr+"' name='"+fieldID+"_cfield_value[]' class='form-control form-control-sm cfield_value_select'></select>";
                
                //Buttons
                let addButton       = "<a id='"+fieldID+"_cfield_add_"+insCtr+"' class='cfield_add'><i class='fas fa-plus-circle pt-2'></i></a>";
                let removeButton    = "<a id='"+fieldID+"_cfield_remove_"+insCtr+"' class='cfield_remove'><i class='fas fa-minus-circle pt-2'></i></a>";
                let btns            = "<div id='"+fieldID+"_cfield_btn_container_"+insCtr+"'>"+ addButton +" " + removeButton +"</div>";
 

                let btnRow = "<div class='col-md-4'>"+ cfield_id + "</div>";
                    btnRow += "<div class='col-md-3'>"+ cfield_rule + "</div>";
                    btnRow += "<div id='"+ fieldID +"_cfield_value_container_"+ insCtr +"' class='col-md-3'>"+ cfield_value + "</div>";
                    btnRow += "<div class='col-md-2 pl-1'>"+ btns + "</div>";
                
                if (insCtr == 1) {
                   // console.log("test 1");                    
                    $("#"+ fieldID +'_tab_container').find(".conditional_fields").append("<div class='row "+fieldID+"_conditional_fields_"+insCtr+" mt-2'>"+ btnRow +"</div>");

                } else {
                    //console.log("test 2")
                    $("#"+ fieldID +'_tab_container').find(".conditional_fields").find("."+ fieldID + "_conditional_fields_"+ btnIndex).after("<div class='row "+fieldID+"_conditional_fields_"+insCtr+" mt-2'>"+ btnRow +"</div>");
                }

                populateFieldIDOptions(fieldID, insCtr);
                populateRulesOptions(fieldID, insCtr)

                 //[TARGET]clean and populate
                let targetFieldType = $('#'+fieldID+"_fieldType").val();
                if (targetFieldType == "dropdownSelect") {
                    let selectedOptionID = $('#'+fieldID+"_cfield_id_"+insCtr).find(":selected").val();
                    populateValuesDropdownOptions(selectedOptionID, fieldID, insCtr)
                } else {                    
                    createNewTextField(fieldID, fieldID, insCtr)
                }
            }          

            $(document).on('click', '.activate_coditional_logic', (elem) => {
                let elementID = elem.currentTarget.id;
                let cfieldID = getFieldID(elementID);                               
                let btnIndex = getFieldCtr(elem.currentTarget.id);

                if ($('#'+elementID).is(':checked')) {
                    console.log("checked")

                    $('#'+cfieldID+'_tab_container').find('.conditional_fields').show();

                    let fieldsCtr = $("#"+ cfieldID +"_field").find('.conditional_fields').find('.row').length;                

                    if (fieldsCtr == 0) {
                        addCField(cfieldID, 1); 

                        $('#'+cfieldID +'_cfield_id_1').css('border', '1px solid blue');

                        //find first element selected
                        let selectedOptionID = $('#'+cfieldID +'_cfield_id_1').find(":selected").val();
                        let targetFieldType = $('#'+selectedOptionID+"_fieldType").val();                

                        //[TARGET]clean and populate
                        if (targetFieldType == "dropdownSelect") {   
                            populateValuesDropdownOptions(selectedOptionID, cfieldID, 1)
                        } else {
                            createNewTextField(selectedOptionID, cfieldID, 1)
                        }   
                    }
                } else {
                    //console.log("not checked")
                    $('#'+cfieldID+'_tab_container').find('.conditional_fields').hide();
                }                 
            });

            $(document).on('change', '.cfield_id_select', (elem) => { 

                 //GET ARGET FIELD TO UPDATE
                let targetID = elem.currentTarget.id;
                let targetFieldID = getFieldID(targetID);
                let index = getFieldCtr(targetID)              

                //[@note] target id is the full id of an element
                $('#'+targetID).css('border', '1px solid red');
                $('#'+targetID).find(":selected").val();


                let selectedOptionID = $('#'+targetID).find(":selected").val();
                let targetFieldType = $('#'+selectedOptionID+"_fieldType").val(); 

                //[TARGET]clean and populate
                if (targetFieldType == "dropdownSelect") {   
                    populateValuesDropdownOptions(selectedOptionID, targetFieldID, index)
                } else {
                    createNewTextField(selectedOptionID, targetFieldID, index)
                }
            });
     

            function populateFieldIDOptions(targetFieldID, insCtr) {
                //console.log(targetFieldID);
                let cfields = $(document).find('.field_container');
                Array.from(cfields, (cfield, findex) => {                    
                   let fieldID = getFieldID(cfield.id);
                   let coptvalue = $('#'+cfield.id).find('#id').val();
                   let coptlabel = $('#'+fieldID+'_label').val();
                   $('#'+ targetFieldID +'_cfield_id_'+ insCtr).append('<option value="'+coptvalue+'">'+coptlabel+'</option>');
                });
            }


            function populateRulesOptions(targetFieldID, insCtr) 
            {                    
                $('#'+ targetFieldID +'_cfield_rule_'+ insCtr).append('<option value="is" selected="selected">is</option>');
                $('#'+ targetFieldID +'_cfield_rule_'+ insCtr).append('<option value="isnot">is not</option>');
                $('#'+ targetFieldID +'_cfield_rule_'+ insCtr).append('<option value="contains">contains</option>');
            }

            function populateValuesDropdownOptions(selectedOptionID, targetFieldID, index) {
                let selectValueOption = "<select id='"+ targetFieldID +"_cfield_value_"+ index +"' name='"+ targetFieldID +"_cfield_value[]' class='"+ targetFieldID +"_cfield_value form-control form-control-sm'></select>"
                $('#'+ targetFieldID + '_cfield_value_container_'+index).html('');  
                $('#'+ targetFieldID + '_cfield_value_container_'+index).append(selectValueOption);

                let activeChoices = $('#'+selectedOptionID+'_tab_container').find('#'+selectedOptionID+'_selected_choices').find("input");                                                  
                Array.from(activeChoices, (choice) => { 
                    let coptvalue = $(choice).val();
                    let targetValueInputID = '#'+ targetFieldID + '_cfield_value_' + index               
                    $(targetValueInputID).append('<option value="'+coptvalue+'">'+coptvalue+'</option>');
                });   
            }


            function createNewTextField(selectedOptionID, targetFieldID, index) {
                $('#'+ targetFieldID + '_cfield_value_container_'+index).html('');
                let inputHTML = "<input id='"+ targetFieldID +"_cfield_value"+ index +"' name='"+targetFieldID+"_cfield_value[]' class='"+ targetFieldID +"_cfield_value form-control form-control-sm'>";
                $('#'+ targetFieldID + '_cfield_value_container_'+index).append(inputHTML);
            }


            function appendCValues(targetID) 
            {
                let selectedID = $('#'+targetID+"_cfield_id").find(":selected").val();
                let targetFieldType = $('#'+selectedID+"_fieldType").val(); 

        
                if (targetFieldType == "dropdownSelect") {                    
                    $('#'+ targetID + '_cfield_value_container').html('');
                    $('#'+ targetID + '_cfield_value_container').append("<select id='"+ targetID +"_cfield_value' class='"+ targetID +"_cfield_value form-control form-control-sm'></select>");
                    activeChoices = $('#'+selectedID+'_tab_container').find('#selected_choices').find("input");                                                  
                    Array.from(activeChoices, (choice, index) => {
                        //let coptID = $(choice).attr('id');
                        let coptvalue = $(choice).val();
                        $('#'+ targetID + '_cfield_value').append('<option value="'+coptvalue+'">'+coptvalue+'</option>');
                    });
                } else if (targetFieldType == "SimpleTextField") {
                    $('#'+ targetID + '_cfield_value_container_1').html('');
                    $('#'+ targetID + '_cfield_value_container_1').append("<input id='"+ targetID +"_cfield_value' class='"+ targetID +"_cfield_value form-control form-control-sm'>");
                }
            }



            function getFieldCtr(fid) {
                let element = $("#"+ fid).attr('id');
                if (element) {           
                    let elementName = element.split("_");
                    let fieldID = elementName[3];
                    return fieldID;
                } 
            }

            //CONDITIONAL FIELDS BUTTONS
            $(document).on('click', '.cfield_add', (elem) => {
                //conditional_fields
                let elementID = elem.currentTarget.id;
                let cfieldID = getFieldID(elementID);                               
                let btnIndex = getFieldCtr(elem.currentTarget.id)
                addCField(cfieldID, btnIndex);               
            });

            $(document).on('click', '.cfield_remove', (elem) => {
                //conditional_fields
                let elementID = elem.currentTarget.id;
                let cfieldID = getFieldID(elementID);                               
                let btnIndex = getFieldCtr(elem.currentTarget.id);
                let count = $('#'+ cfieldID +'_tab_container').find('.conditional_fields').find('.row').length;
                $('.'+cfieldID+'_conditional_fields_'+btnIndex).remove();                    
            });
  
            

            /**************************** BUTTONS ***************************/

            /***************************************************************
                            [START] - (BUTTON) [HTML CONTENT]
            *****************************************************************/
            let fileURL = "";

            //SHOW HTML MODAL
            $('#btn_html').on('click', function(){
                $("#modal_html").modal();
                $('#form_html').trigger("reset");
            });

            $('#modal_gallery').on('hidden.bs.modal', function () {
                resetModalGallery();
            });


            //SHOW IMAGE GALLERY 
            $('#btnInsertImage, #btnInsertAudio').on('click', function(){
                $("#modal_gallery").modal();
                $('#form_gallery').trigger("reset");
                $( ".tabs" ).tabs();
            });

            //MAKE THE IMAGE INFORMATION
            $('.img-container').on('click', function()
            {   
                resetModalGallery();             

                let filename = $(this).find('.img-filename-container').find('.img-filename').text();
                fileURL = $(this).find('.img-url-container').find('.img-url').text();
                $(this).find('.img-wrapper').css("border-color", "#0072A8");
                $(this).find('.img-filename-container').css("background-color", "#0072A8");
                $(this).find('.img-filename-container').find('.img-filename').css("color", "#fff !important");

                $('#preview').removeClass('d-none');
                $('#mediaImgPreview').attr('src', fileURL);
                $('#selectedFilename').val(filename)
                $('#btnGalleryInsert').prop('disabled', false)
            });
            
            //INSERT THE IMAGE
            $('#btnGalleryInsert').on('click', function()
            {
                //@TODO: DETECT FILE (IMAGE OR AUDIO)
                let formattedHTML = "<img src='"+fileURL+"'>";

                let updatedContent = $("#modal_html").find('#content').val() + " " + formattedHTML + " ";
                $("#modal_html").find('#content').val(updatedContent);                
                $("#modal_gallery").modal('toggle');
                return false;              
            });

            function resetModalGallery() {
                $(document).find('.img-wrapper').css("border-color", "#D2E0EB");
                $(document).find('.img-filename-container').css("background-color", "#D2E0EB");  
                $(document).find('.img-filename-container').find('.img-filename').css("color", "#6c757d");
                $('#preview').addClass('d-none');
                $('#mediaImgPreview').attr('src', "");
                $('#selectedFilename').val("");     
                $('#btnGalleryInsert').prop('disabled', true)
            }


            /***************************************************************
                            [START] - (BUTTON) [SIMPLE INPUT TEXT]
            *****************************************************************/

            //Show SimpleText Modal
            $("#btn_simpleInputText").on("click", function() {
                $("#modal_simpleText").modal();
                $('#form_simpleText').trigger("reset");
            });    


            /***************************************************************
                            [START] - (BUTTON) [HTML]
            *****************************************************************/

            $("#btn_simpleInputText").on("click", function() {
                $("#modal_HTML").modal();
                $('#form_HTML').trigger("reset");
            });


            /***************************************************************
                            [START] - (BUTTON) [FIRSTNAME]
            *****************************************************************/            
            $("#btn_firstname").on("click", function() {
                $("#modal_firstname").modal();
                $('#form_firstname').trigger("reset");
            });



            /***************************************************************
                            [START] - (BUTTON) [LASTNAME]
            *****************************************************************/  

            $("#btn_lastname").on("click", function() {
                $("#modal_lastname").modal();
                $('#form_lastname').trigger("reset");
            });



            /***************************************************************
                            [START] - (BUTTON) [EMAIL]
            *****************************************************************/  

            $("#btn_email").on("click", function() {
                $("#modal_email").modal();
                $('#form_email').trigger("reset");
            });



            /***************************************************************
                            [START] - (BUTTON) [UPLOAD]
            *****************************************************************/  

            $("#btn_upload").on("click", function() {
                $("#modal_upload").modal();
                $('#form_upload').trigger("reset");
            });

            /***************************************************************
                            [START] - (BUTTON) [DROPDOWN SELECT]
            *****************************************************************/
            
            //ON CLICK        
            $("#btn_dropdownSelect").on("click", function() {
                $("#select_choices").html("");
                $("#select_choices").append("<div id='select_choice_start'></div>");
                addNewSelectionChoice('select_choice_start', 1);                
                $("#modal_dropdownSelect").modal();
                $('#form_dropdownSelect').trigger("reset");
                updateChoicesButtons();
            });           

        
            //[DROPDOWN FIELD POPUP] ADD CHOICES FROM
            $(document).on("click", '.field_choice_add', function() {
                ctr = ctr + 1;
                let id = $(this).parent().parent().attr('id');
                addNewSelectionChoice(id, ctr);
                updateChoicesButtons();
            });

            //[DROPDOWN FIELD POPUP] REMOVE CHOICES
            $(document).on("click", '.field_choice_remove', function() {
                ctr = ctr - 1;
                $(this).parent().parent().remove();
                updateChoicesButtons()
            });


            //[SAVED FORM] APPEND NEW TO SAVED CHOICES
            $(document).on("click", '.selected_field_choice_add', function() {
                ctr = ctr + 1;
                let elementID = $(this).parent().parent().attr('id');
                console.log(elementID);
                appendSelectionChoice(elementID, ctr);
                let fieldID = getFieldID(elementID);
                updateSelectedChoicesButtons(fieldID);            
            });

            //[SAVED FORM]  REMOVE CHOICES
            $(document).on("click", '.selected_field_choice_remove', function() {
                ctr = ctr - 1;
                let elementID = $(this).parent().parent().attr('id');
                console.log(elementID);
                let fieldID   = getFieldID(elementID);
                $(this).parent().parent().remove();               
                updateSelectedChoicesButtons(fieldID);
            });          



            
            /***************************************************************
                            [START] - [SAVE ACTIONS - ADVANCE FIELDS]
            *****************************************************************/
            //[START] - [FIRSTNAME TEXT]
            $("#btnFirstNameSave").on("click", function() 
            {         
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveFirstNameField?api_token=') }}" + api_token,
                    data: {
                        formID              : 1,
                        label               : $('#modal_firstname').find('input#label').val(),
                        description         : $('#modal_firstname').find('textarea#description').val(),
                       //maximum_characters  : $('#modal_firstname').find('input#maximum_characters').val(),
                        required            : $('#modal_firstname').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle'
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                    }
                });
            });


            //[START] - [Last TEXT]
            $("#btnLastNameSave").on("click", function() 
            {         
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveLastNameField?api_token=') }}" + api_token,
                    data: {
                        formID              : 1,
                        label               : $('#modal_lastname').find('input#label').val(),
                        description         : $('#modal_lastname').find('textarea#description').val(),
                       //maximum_characters  : $('#modal_lastname').find('input#maximum_characters').val(),
                        required            : $('#modal_lastname').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle'
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                    }
                });
            });


            //[START] - [Email TEXT]
            $("#btnEmailSave").on("click", function() 
            {         
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveEmailField?api_token=') }}" + api_token,
                    data: {
                        formID              : 1,
                        label               : $('#modal_email').find('input#label').val(),
                        description         : $('#modal_email').find('textarea#description').val(),
                       //maximum_characters  : $('#modal_email').find('input#maximum_characters').val(),
                        required            : $('#modal_email').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle'
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                    }
                });
            });

            $('#btnUploadFieldSave').on("click", function() 
            {         
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveUploadField?api_token=') }}" + api_token,
                    data: {
                        formID              : 1,
                        label               : $('#modal_upload').find('input#label').val(),
                        description         : $('#modal_upload').find('textarea#description').val(),
                       //maximum_characters  : $('#modal_upload').find('input#maximum_characters').val(),
                        required            : $('#modal_upload').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle'
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                    }
                });
            });




            
            /***************************************************************
                            [START] - [SAVE ACTIONS - INPUT FIELDS]
            *****************************************************************/

            //[START] - [SIMPLE TEXT]
            $("#btnSimpleTextSave").on("click", function() 
            {         
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveSimpleTextField?api_token=') }}" + api_token,
                    data: {
                        formID              : 1,
                        label               : $('#modal_simpleText').find('input#label').val(),
                        description         : $('#modal_simpleText').find('textarea#description').val(),
                        maximum_characters  : $('#modal_simpleText').find('input#maximum_characters').val(),
                        required            : $('#modal_simpleText').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle'
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                    }
                });

            });
            

            //[START] - [DROPDOWN]
            $("#btnDropdownSelectSave").on("click", function() 
            {              
                let choices = [];

                $("#select_choices :input").each(function(elem) {
                    choices.push($(this).val());
                });           

                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveDropDownSelect?api_token=') }}" + api_token,
                    data: {
                        formID              :  1,
                        label               :  $('#modal_dropdownSelect').find('input#label').val(),
                        description         :  $('#modal_dropdownSelect').find('textarea#description').val(),
                        maximum_characters  :  $('#modal_dropdownSelect').find('input#maximum_characters').val(),                        
                        required            :  $('#modal_dropdownSelect').find('input#required').prop("checked"),
                        selected_choices    :  choices,                        
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle'
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                    }
                });
            });



            $('#btnHTMLSave').on("click", function() 
            {  
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveHTMLContent?api_token=') }}" + api_token,
                    data: {
                        formID           :  1,
                        label           :  $('#modal_html').find('input#label').val(),
                        content         :  $('#modal_html').find('textarea#content').val()                                            
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle'
                        });
                        $( ".tabs" ).tabs(); 
                        //addCField(data.id, 1);
                    }
                });
            });


         



            
            /***************************************************************
                            [START] - [NEW PAGE]
            *****************************************************************/

            //[start] - ADD Button AUTO SAVE
            $("#btn_page").click(function () {

                var $div = $("<div>", { 
                        "id": "page-"+ pageCtr +"",
                        "class": "card-header esi-card-header-page mb-4 droptrue handle", 
                        "style": "min-height:200px",
                        "text":' Page :' + pageCtr
                }).sortable();

                $("#form-content .sortable").append($div);

                pageCtr = pageCtr + 1;

                $( ".droptrue" ).sortable({ 
                    connectWith: "div", 
                    handle: '.handle',
                    update: function( event, ui) {
                        console.log($(this).attr('id'));
                        let page = $(this).attr('id');
                        $('#'+page).find('.page').val(page)
                    }                
                });

            });



            /***************************************************************
                            [START] - [REMOVE ACTIONS - INPUT FIELDS]
            *****************************************************************/
            $(document).on("click", '.btnRemoveField', function() 
            {
                let elementID = $(this).attr('id');                
                let id = getFieldID(elementID);

                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/removeField?api_token=') }}" + api_token,
                    data: {
                        formID              :  1,
                        id                  : id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) 
                    {                  
                   
                        if (data.success == true) {
                            $('#'+id+"_field").remove();                      
                        } else {
                            alert ("Sorry, Can't delete this field right now, it may not exists any longer. please try again in a later time.")
                        }
                        
                    }
                });
                return false;
            });


        });



        //GET FIELD ID (eg.  123_fieldname_ctr - returns 123)
        function getFieldID(fid) {

            let element = $("#"+ fid).attr('id');
            if (element) {           
                let elementName = element.split("_");
                let fieldID = elementName[0];
                return fieldID;
            } 
        }

        //ADD A NEW DROPDOWN SELECTION CHOICES
        function addNewSelectionChoice(id, ctr)
        {
            let addButton = '<a class="field_choice_add"><i class="fas fa-plus-circle pt-2"></i></a> ';
            let removeButton = '<a class="field_choice_remove"><i class="fas fa-minus-circle pt-2"></i></a> ';
            let leftColumn = '<div class="col-md-10 pr-0"><input id="select_choice_text_'+ ctr +'" type="text" value="" class="form-control mb-1"> </div>';
            let rightColumn = '<div class="col-md-2 pl-1">'+ addButton + ' ' +  removeButton + '</div>';
            $("#"+ id).after('<div id="choice_'+ ctr +'" class="row mb-1">'+ leftColumn + rightColumn +"</div>");
        }

         //APPEND ANOTHER CHOICE DROPDOWN SELECTION CHOICES
        function appendSelectionChoice(id, ctr)
        {    
            let fieldID = getFieldID(id);

            let addButton = '<a class="selected_field_choice_add"><i class="fas fa-plus-circle pt-2"></i></a> ';
            let removeButton = '<a class="selected_field_choice_remove"><i class="fas fa-minus-circle pt-2"></i></a> ';
            let leftColumn = '<div id="dropdown_'+ ctr +'" class="col-md-10 pr-0"><input name="'+fieldID+'_selected_choice_text[]" type="text"  class="form-control mb-1 appendedSelection"> </div>';
            let rightColumn = '<div class="col-md-2 pl-1">'+ addButton + ' ' +  removeButton + '</div>';

            $("#"+ id).after('<div id="'+fieldID+'_new_selected_choice_'+ ctr +'" class="row mb-1">'+ leftColumn + rightColumn +"</div>");
        }        

        //HIDE REMOVE IF CHOICES IS ONLY 1
        function updateChoicesButtons() {
            let choicesCount = $('#select_choices').find('.row');
            if (choicesCount.length <= 1) {
                $('.field_choice_remove').hide();
            } else {
                $('.field_choice_remove').show();
            }            
        }

        function updateSelectedChoicesButtons(fieldID) {
            let choicesCount = $('#'+fieldID+"_field").find('#'+ fieldID +'_selected_choices').find('.row');
            if (choicesCount.length <= 1) {
                $('#'+fieldID+"_field").find('.selected_field_choice_remove').hide();
            } else {
                $('#'+fieldID+"_field").find('.selected_field_choice_remove').css('display', '')
            }            
        }        


    </script>
@endsection
