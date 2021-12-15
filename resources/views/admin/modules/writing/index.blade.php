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
                            <a class='text-success' href="{{ url('admin/writing/?id='.$form_id) }}">
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
                            <div id="page-{{ $page->page_id }}" class="card esi-card-header-page mb-4">
                                <div class='card-header'>
                                    {{ "Page : ".  $page->page_id }}
                                </div>
                                <div class='card-body droptrue'>
                                    @if(isset($formFieldChildrenHTML[$page->page_id]))
                                        @foreach($formFieldChildrenHTML[$page->page_id] as $formFieldChildHTML) 
                                            {!! $formFieldChildHTML !!}
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                            @endforeach
                           

                            <div class="sortable">
                                @foreach($formFieldHTML as $HTML) 
                                    {!! $HTML !!}
                                @endforeach
                            </div>

                        </div>
                    </form>
                    <!--[START DYNAMIC FORMS]-->

                </div>

            </div>

            <div class="col-md-4">
                <div id="righ-sidebar" style="position: -webkit-sticky;position: sticky; top: 20px;">
                    @include('admin.modules.writing.includes.fieldButtons')                
                    <input type="button" value="Cancel" class="btn btn-danger mt-4" onclick="window.location.href='{{  url('admin/writing') }}' ">
                    <input type="button" value="Update" class="btn btn-primary mt-4" onclick="event.preventDefault();document.getElementById('dynamicForms').submit();">               
                </div>
            </div>

        </div>
    </div>

    <!-- STANDARD FIELDS -->
    @include('admin.modules.writing.includes.FormFields.simpleTextModal')
    @include('admin.modules.writing.includes.FormFields.dropdownSelectModal')
    @include('admin.modules.writing.includes.FormFields.htmlModal')
    @include('admin.modules.writing.includes.FormFields.paragraphTextModal')

    <!-- ADVANCE FIELDS -->
    @include('admin.modules.writing.includes.FormFields.firstnameModal')
    @include('admin.modules.writing.includes.FormFields.lastnameModal')
    @include('admin.modules.writing.includes.FormFields.emailModal')
    @include('admin.modules.writing.includes.FormFields.uploadModal')

    <!--image gallery-->
    @include('admin.modules.writing.includes.imageGallery.galleryModal')

@endsection

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ url('css/dropzone/dropzone.min.css') }}"></link>

    <style>

        #writing-form img {
            width: 100%
        }

        .handle {
            cursor: move;
        } 

        .fields .row {
            margin-bottom: 15px;
        }

        .form-label {
            margin-bottom: 0px;
            font-size: 12px;
        }

        .ui-sortable-placeholder 
        {            
            border: 1px dotted #ff0099;
            visibility: visible !important;
            height: 40px;
            margin-bottom: 10px;

        }

        .esi-card-header-page {
            font-weight: bold;
            font-size: 12px;
            border-color: #28a745;                        
            border: 3px solid #28a745;
        }

        .esi-card-header-page >.card-body {
            background-color: #e9f1ec;
        }

        .esi-card-header-page >.card-header {
            background-color: #28a745; 
            text-align: center;
            color: #fff;
        }
        
        
        .ui-draggable-dragging {
            z-index: 999999999;
        }
        
        #dynamicForms .ui-sortable-helper {
           display: none;
        }

        #dynamicForms .ui-draggable-dragging {
            z-index: 99999999;
        }
    </style>
@endsection

@section('scripts')
    <link rel="stylesheet" href="{{ url('css/jquery/jquery-ui.min.css') }}">
    <script src="{{ url('js/ckeditor/ckeditor.js')  }}" ></script>
    <script src="{{ url('js/jquery/jquery-ui.min.js') }}" defer></script>
    <script src="{{ url('js/dropzone/dropzone.min.js') }}" deferd></script>
    <script type="text/javascript" defer>
        var api_token = "{{ Auth::user()->api_token }}";
        window.addEventListener('load', function() 
        {

            $('.ckEditor').each( function () {
                console.log(this.name + " add ckeditor");
                CKEDITOR.replace( this.name , {
                    removePlugins: 'easyimage, exportpdf, cloudservices',
                    extraPlugins: 'html5audio',
                    //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                    //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                });
            });

            $('.fa-caret-up').hide();

            let currentMousePos = { x: -1, y: -1 };
            let pageCtr = '{{ $pageCounter ?? 1 }}';            

            $( ".tabs" ).tabs();

            //sortable with no page
            $( ".sortable" ).sortable({               
                connectWith: "div", 
                handle: '.handle',
                sort: function(e) {
                    //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                    $('#dynamicForms').find('.field_container').show();                    
                },
            });
           
            //this is for the sortable in the page area
            $( ".droptrue" ).sortable({        
                connectWith: "div", 
                handle: '.handle',              
                sort: function(e) {
                    //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                    $('#dynamicForms').find('.field_container').show();                   
                },               
                update: function( event, ui) {
                    console.log($(this).parent().attr('id'));
                    let page = $(this).parent().attr('id');
                    $('#'+page).find('.page').val(page)
                }                
            });



            $( ".draggable" ).draggable({
                
                distance : 1,
                connectToSortable: ".droptrue",
                revert: "invalid",
                placeholder: 'ui-state-highlight',
                over: function(event, ui) {
                    let cl = ui.item.attr('class');
                    $('.ui-state-highlight').addClass(cl);
                },
                helper: function(event, ui) {
                    return "<div style='width:100%; margin:10px 0px; padding:0px'><div class='handle'>(Component Drag Test)</div></diV>"
                },            
                stop: function() {
                    $('#container_drop').removeClass('highlight_container');
                    //$('.field_container').css("display", "block");
                },                   
                drag: function(e, ui) {
                    $('.ui-draggable-dragging').css("background-color", "yellow");
                    $('.ui-sortable-helper').css("background-color", "yellow");
                    $('.ui-sortable-placeholder').text("test");                    
                   
                }                
            });
              



            let ctr = 1;
            /***************************************************************
                            [START] - (TAB) [SHOW, HIDE - TAB OPTIONS]
            *****************************************************************/

            //SHOW TAB WHEN FIELD CONTAINER IS CLICKED
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

                if (!$(this).find('.tab-container').hasClass('open')) 
                {
                    $(this).find('.tab-container').addClass('open');
                    $(this).find('.tab-container').slideToggle('slow');
                    $(this).find('.fa-caret-up').show();
                    $(this).find('.fa-caret-down').hide();   
                    
                } else {
                    //$(this).find('.tab-container').removeClass('open');
                    //$(this).find('.tab-container').slideToggle('slow');
                }               
            });

            $(document).on('click', '.btnShowFieldOptions', function() {
                let element = $(this).parent().parent().parent().find('.tab-container');
                
                if (!element.hasClass('open')) {
                   
                    $(element).addClass('open');  
                    $(element).slideToggle('slow');                  
                }                                
                $(this).parent().find('.fa-caret-up').show();
                $(this).parent().find('.fa-caret-down').hide();  
                return false;               
            });


            $(document).on('click', '.btnHideFieldOptions', function() {
                let element = $(this).parent().parent().parent().find('.tab-container');
                
                if (element.hasClass('open')) {
                    element.slideToggle('slow');
                    element.removeClass('open');                    
                }                                
                $(this).parent().find('.fa-caret-up').hide(); 
                $(this).parent().find('.fa-caret-down').show(); 
                return false;               
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

            //ADD Custom Field
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
 
                /*Columns Actually*/
                let btnRow = "<div class='col-md-4'>"+ cfield_id + "</div>";
                    btnRow += "<div class='col-md-3'>"+ cfield_rule + "</div>";
                    btnRow += "<div id='"+ fieldID +"_cfield_value_container_"+ insCtr +"' class='col-md-4'>"+ cfield_value + "</div>";
                    btnRow += "<div class='col-md-1 pl-1'>"+ btns + "</div>";
                
                if (insCtr == 1) {
                   // console.log("you added the first instance field");                    
                    $("#"+ fieldID +'_tab_container').find(".conditional_fields").append("<div class='row "+fieldID+"_conditional_fields_"+insCtr+" mt-2'>"+ btnRow +"</div>");
    
                } else {
                    // console.log("you added the instance field 2 and above");
                    $("#"+ fieldID +'_tab_container').find(".conditional_fields").find("."+ fieldID + "_conditional_fields_"+ btnIndex)
                    .after("<div class='row "+fieldID+"_conditional_fields_"+insCtr+" my-2'>"+ btnRow +"</div>");
                }

                populateFieldIDOptions(fieldID, insCtr);
                populateRulesOptions(fieldID, insCtr)

                 //[TARGET]clean and populate

                let selectedOptionID = $('#'+fieldID +"_cfield_id_"+ insCtr).find(":selected").val();
                let targetFieldType = $('#'+selectedOptionID+"_fieldType").val();
                if (targetFieldType == "dropdownSelect") {
                   
                    populateValuesDropdownOptions(selectedOptionID, fieldID, insCtr);

                    console.log("hi created here!")
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
                if (targetFieldType == "dropdownSelect") 
                {

                    let selectedRuleValue = $('#'+targetFieldID+"_cfield_rule_"+index).find(":selected").val();

                    console.log(selectedRuleValue)

                    if (selectedRuleValue === 'contains') 
                    {    

                        createNewTextField(selectedOptionID, targetFieldID, index)

                    } else {
                        populateValuesDropdownOptions(selectedOptionID, targetFieldID, index)
                    }
                    
                } else {
                    createNewTextField(selectedOptionID, targetFieldID, index)
                }
            });

            //on change of select
            $(document).on('change', '.cfield_rule_select', (elem) => {
                //GET ARGET FIELD TO UPDATE
                let targetID = elem.currentTarget.id;
                let targetFieldID = getFieldID(targetID);
                let index = getFieldCtr(targetID)              

                //[@note] target id is the full id of an element
                $('#'+targetID).css('border', '1px solid red');
                $('#'+targetID).find(":selected").val();

                let selectedRuleValue= $('#'+targetID).find(":selected").val();

                if (selectedRuleValue === 'contains') 
                {              
                    /*Currend field ir (Rule)  */
                    createNewTextField(targetID, targetFieldID, index);
                } else {                  
                     /*Get the selected Field Option dropdown*/
                     let selectedOptionID = $('#'+targetFieldID +"_cfield_id_"+ index).find(":selected").val();
                     let targetFieldType = $('#'+selectedOptionID+"_fieldType").val();

                    if (targetFieldType == "dropdownSelect") {   
                        populateValuesDropdownOptions(selectedOptionID, targetFieldID, index)
                    } else {
                        createNewTextField(selectedOptionID, targetFieldID, index)
                    }
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

            let newField = true;
            let targetFieldID = null;

            /***************************************************************
                            [START] - (BUTTON) [HTML CONTENT]
            *****************************************************************/
            let fileURL = "";

            //SHOW HTML MODAL
            $('#btn_html').on('click', function(){
                newField = true;
                $("#modal_html").modal();
                CKEDITOR.instances['content'].setData("");
                $('#form_html').trigger("reset");
            });

            $('#modal_gallery').on('hidden.bs.modal', function () {
                resetModalGallery();
            });


            //SHOW IMAGE GALLERY 
             $(document).on('click', '#btnInsertImage, #btnInsertAudio', (elem, id) => {      
                $("#modal_gallery").modal();
                $('#form_gallery').trigger("reset");
                $( ".tabs" ).tabs();
                $('#btnMediaLibraryTab').trigger('click');
             }); 


           /*
            $('#btnInsertImage, #btnInsertAudio').on('click', function(){
                $("#modal_gallery").modal();
                $('#form_gallery').trigger("reset");
                $( ".tabs" ).tabs();
                $('#btnMediaLibraryTab').trigger('click');
            });
            */

            //ADD THE IMAGE INFORMATION FOR GALLERY
            $(document).on('click', '.img-container', (elem, id) => {       

                resetModalGallery();
                
                let filename = $(elem.currentTarget).find('.img-filename-container').find('.img-filename').text();

                imageURL = $(elem.currentTarget).find('.img-url-container').find('.img-url').text();
                fileURL = $(elem.currentTarget).find('.img-filename-container').find('.img-filename').text();
                $(elem.currentTarget).find('.img-wrapper').css("border-color", "#0072A8");
                

                $(elem.currentTarget).find('.img-filename-container').css("background-color", "#0072A8");
                $(elem.currentTarget).find('.img-filename-container').find('.img-filename').css("color", "#fff !important");

                $('#preview').removeClass('d-none');
                $('#mediaImgPreview').attr('src', imageURL);

                $('#selectedFilename').val("{{ url('storage/uploads/writing_materials') }}" + "/" + fileURL)
                $('#btnGalleryInsert').prop('disabled', false)
                
            });
            
            $('.fileinput-button').on('click', function(){
                $('.dropzone').trigger('click');
            })
            

            $(document).on('click', '.insertToMediaAddedField', function(elem, id) {
                newField = false;
                targetFieldID =  $(this).parent().find('.addedContentFieldID').val();

                $("#modal_gallery").modal();
                $('#form_gallery').trigger("reset");
                $( ".tabs" ).tabs();                

                $('#btnMediaLibraryTab').trigger('click');
            });


            //INSERT THE IMAGE ON NEW
            $('#btnGalleryInsert').on('click', function()
            {
                let selectedFilename = $('#selectedFilename').val();


                let checkSelectedFilename = basename(selectedFilename);
                let fileURLArray = checkSelectedFilename.split(".");
                let extension = fileURLArray[1];

                console.log(extension);

                if (extension === 'mp3') 
                {
                    let formattedHTML = '<div class="ckeditor-html5-audio" style="text-align:center">' +
                                          '<audio controls="controls" controlslist="nodownload" src="'+selectedFilename+'">&nbsp;</audio>'+
                                        '</div>';
                    if (newField === true) {
                        let oldContent              = CKEDITOR.instances['content'].getData();
                        let updatedContent          = oldContent + " " + formattedHTML + " <br/>";

                        //ADD CONTENT OF OLD AND THE NEW FORMATTED HTML CONTENT
                        CKEDITOR.instances['content'].setData(updatedContent);
                        $("#modal_html").find('#content').val(updatedContent);
                        $("#modal_gallery").modal('toggle'); 
                    } else {
                        let oldContent          = CKEDITOR.instances[targetFieldID + '_content'].getData();
                        let updatedContent      = oldContent + " " + formattedHTML + " <br> ";

                        //ADD CONTENT OF OLD AND THE NEW FORMATTED HTML CONTENT
                        CKEDITOR.instances[targetFieldID + '_content'].setData(updatedContent);                    
                        $('.'+targetFieldID+"_content").val(updatedContent);
                        $("#modal_gallery").modal('toggle');
                    }
                } else {
                    if (newField === true) {
                        // the new field is coming from a modal
                        let formattedHTML           = "<p><img src='"+selectedFilename+"' width='100%' height='100%'></p>";
                        let oldContent              = CKEDITOR.instances['content'].getData();
                        let updatedContent          = oldContent + " " + formattedHTML + " <br/>";

                        //ADD CONTENT OF OLD AND THE NEW FORMATTED HTML CONTENT
                        CKEDITOR.instances['content'].setData(updatedContent);
                        $("#modal_html").find('#content').val(updatedContent);
                        $("#modal_gallery").modal('toggle'); 

                    } else {
                        let formattedHTML       = "<img src='"+selectedFilename+"' width='100%' height='100%'>";
                        let oldContent          = CKEDITOR.instances[targetFieldID + '_content'].getData();
                        let updatedContent      = oldContent + " " + formattedHTML + " <br> ";

                        //ADD CONTENT OF OLD AND THE NEW FORMATTED HTML CONTENT
                        CKEDITOR.instances[targetFieldID + '_content'].setData(updatedContent);                    
                        $('.'+targetFieldID+"_content").val(updatedContent);
                        $("#modal_gallery").modal('toggle');   
                    }     
                }


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
                        [START] - WRITING IMAGES
            *****************************************************************/
     
                            

            /***************************************************************
                            [START] - (BUTTON) [SIMPLE INPUT TEXT]
            *****************************************************************/            
            $("#btn_simpleInputText").on("click", function() {
                $("#modal_simpleText").modal();
                $('#form_simpleText').trigger("reset");
            });    


            /***************************************************************
                            [START] - (BUTTON) [PARAGRAPH TEXT]
            *****************************************************************/            
            $("#btn_paragraphText").on("click", function() {
                $("#modal_paragraphText").modal();
                $('#form_paragraphText').trigger("reset");
            });                


            /***************************************************************
                            [START] - (BUTTON) [HTML]
            *****************************************************************/
            $("#btn_simpleInputText").on("click", function() {
                $("#modal_HTML").modal();
                CKEDITOR.instances['modal_simpleText_description'].setData("");
                $('#form_HTML').trigger("reset");
            });


            /***************************************************************
                            [START] - (BUTTON) [FIRSTNAME]
            *****************************************************************/            
            $("#btn_firstname").on("click", function() {
                $("#modal_firstname").modal();
                 CKEDITOR.instances['modal_firstname_description'].setData("");
                $('#form_firstname').trigger("reset");
            });

            /***************************************************************
                            [START] - (BUTTON) [LASTNAME]
            *****************************************************************/
            $("#btn_lastname").on("click", function() {
                $("#modal_lastname").modal();
                 CKEDITOR.instances['modal_lastname_description'].setData("");
                $('#form_lastname').trigger("reset");
            });

            /***************************************************************
                            [START] - (BUTTON) [EMAIL]
            *****************************************************************/  
            $("#btn_email").on("click", function() {
                $("#modal_email").modal();                
                CKEDITOR.instances['modal_email_description'].setData("");
                $('#form_email').trigger("reset");
            });

            /***************************************************************
                            [START] - (BUTTON) [UPLOAD]
            *****************************************************************/
            $("#btn_upload").on("click", function() {
                $("#modal_upload").modal();                
                CKEDITOR.instances['modal_upload_description'].setData("");
                $('#form_upload').trigger("reset");
            });

            /***************************************************************
                            [START] - (BUTTON) [DROPDOWN SELECT]
            *****************************************************************/
            $("#btn_dropdownSelect").on("click", function() {
                $("#select_choices").html("");
                $("#select_choices").append("<div id='select_choice_start'></div>");
                addNewSelectionChoice('select_choice_start', 1);                
                $("#modal_dropdownSelect").modal();
                $('#form_dropdownSelect').trigger("reset");
                CKEDITOR.instances['modal_dropdown_description'].setData("");
            
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
                        //description         : $('#modal_firstname').find('textarea#description').val(),
                         description         :  CKEDITOR.instances['modal_firstname_description'].getData(), 

                       //maximum_characters  : $('#modal_firstname').find('input#maximum_characters').val(),
                        required            : $('#modal_firstname').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle',
                            sort: function(e) {
                                //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                                $('#dynamicForms').find('.field_container').show();                    
                            },                            
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                        CKEDITOR.replace( data.id +"_description", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });                          
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
                        //description         : $('#modal_lastname').find('textarea#description').val(),
                        description         :  CKEDITOR.instances['modal_lastname_description'].getData(), 

                       //maximum_characters  : $('#modal_lastname').find('input#maximum_characters').val(),
                        required            : $('#modal_lastname').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle',
                            sort: function(e) {
                                //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                                $('#dynamicForms').find('.field_container').show();                    
                            },                            
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                        CKEDITOR.replace( data.id +"_description", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });                          
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
                        //description         : $('#modal_email').find('textarea#description').val(),
                        description         :  CKEDITOR.instances['modal_email_description'].getData(),      
                       //maximum_characters  : $('#modal_email').find('input#maximum_characters').val(),
                        required            : $('#modal_email').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle',
                            sort: function(e) {
                                //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                                $('#dynamicForms').find('.field_container').show();                    
                            },                            
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                        CKEDITOR.replace( data.id +"_description", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });                          
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
                        //description         : $('#modal_upload').find('textarea#description').val(),
                        description         :  CKEDITOR.instances['modal_upload_description'].getData(), 

                       //maximum_characters  : $('#modal_upload').find('input#maximum_characters').val(),
                        required            : $('#modal_upload').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle',
                            sort: function(e) {
                                //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                                $('#dynamicForms').find('.field_container').show();                    
                            },                            
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                        CKEDITOR.replace( data.id +"_description", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });                           
                    }
                });
            });


           
            $('#btnMediaLibraryTab').on('click', function()
            {
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/writing/getWritingImages?api_token=') }}" + api_token,
                    data: {
                        formID              : 1,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) 
                    {
                        if (data.success == true) 
                        {                            
                            $('#modal_gallery').find('#tabs-media-library').find('.media-content').html(data.imageHTML);
                        } else {
                            alert ("Sorry, Can't fetch writing images.")
                        }                         
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
                        //description         : $('#modal_simpleText').find('textarea#description').val(),
                        description         :  CKEDITOR.instances['modal_simpleTextdescription'].getData(), 

                        maximum_characters  : $('#modal_simpleText').find('input#maximum_characters').val(),
                        required            : $('#modal_simpleText').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle',
                            sort: function(e) {
                                //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                                $('#dynamicForms').find('.field_container').show();                    
                            },                            
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                        CKEDITOR.replace( data.id +"_description", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });                           
                    }
                });

            });



            //[START] - [SIMPLE TEXT]
            $("#btnParagraphTextSave").on("click", function() 
            {         
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveParagraphText?api_token=') }}" + api_token,
                    data: {
                        formID              : 1,
                        label               : $('#modal_paragraphText').find('input#label').val(),
                        //description         : $('#modal_paragraphText').find('textarea#description').val(),
                        description         :  CKEDITOR.instances['modal_paragraphText_description'].getData(),                       
                        
                        enableWordLimit     : $('#modal_paragraphText').find('input#enableWordLimit').prop("checked"),
                        wordLimit           : $('#modal_paragraphText').find('input#wordLimit').val(),
                        required            : $('#modal_simpleText').find('input#required').prop("checked"),
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field ).sortable({ 
                            connectWith: "div", 
                            handle: '.handle',
                            sort: function(e) {
                                //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                                $('#dynamicForms').find('.field_container').show();                    
                            },                            
                        });
                        $( ".tabs" ).tabs();
                        //addCField(data.id, 1);
                        CKEDITOR.replace( data.id +"_description", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });                           
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
                        //description         :  $('#modal_dropdownSelect').find('textarea#description').val(),
                        description         :  CKEDITOR.instances['modal_dropdown_description'].getData(),

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
                            handle: '.handle',
                            sort: function(e) {
                                //console.log('X:' + e.screenX, 'Y:' + e.screenY);
                                $('#dynamicForms').find('.field_container').show();                    
                            },                            
                        });
                        $( ".tabs" ).tabs();
                        
                        CKEDITOR.replace( data.id +"_description", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });   

                    }
                });
            });


            $('#btnHTMLSave').on("click", function() 
            {  
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveHTMLContent?api_token=') }}" + api_token,
                    data: {
                        formID          :  1,
                        label           :  $('#modal_html').find('input#label').val(),
                        //content        :  $('#modal_html').find('textarea#content').val()
                        content          :  CKEDITOR.instances['content'].getData()
                       
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

                        CKEDITOR.replace( data.id +"_content", {
                            removePlugins: 'easyimage, exportpdf, cloudservices',
                            extraPlugins: 'html5audio',
                            //extraAllowedContent: 'html5audio; div(!ckeditor-html5-audio){text-align,float,margin-left,margin-right}; audio[src, autoplay, controls, controlslist];',
                            //allowedContent: 'html5audio; object(*); object param embed a p b i; audio[src, autoplay, controls, controlslist]; a[!href, target, onclick]; object[data]; object[width]; object[height]; param[name]; param[value]; iframe[*]; iframe[width]; iframe[height], audio',                           
                        });                       

                    }
                });
            });            

            $(document).on('click', '.btnCopyField', function() { 

                let elementKey = $(this).attr('id');
                let fieldID = getFieldID(elementKey);
                let fieldType = $('#'+fieldID+"_fieldType").val();
                let pageID = $('#'+fieldID+"_page").val();

                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/copyField?api_token=') }}" + api_token,
                    data: {
                        formID          :  1,
                        fieldID         : fieldID,
                        pageID          : pageID,
                        fieldType       : fieldType
                        //label           :  $('#modal_html').find('input#label').val(),
                        //description     :  $('#modal_html').find('textarea#description').val(),
                        //content          :  CKEDITOR.instances['content'].getData()                       
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.success == true) 
                        {
                            console.log(pageID);
                            if (pageID === 0) {
                                console.log("orphaned");
                                $( "#form-content").append( data.field ).sortable({ 
                                    connectWith: "div", 
                                    handle: '.handle'
                                });     
                            } else {                            
                                console.log("paged");
                                $(data.field).insertAfter('#'+fieldID+'_field');                              
                            }               

                            if ($('#'+fieldID+'_content').hasClass('ckEditor')) {
                                CKEDITOR.replace( data.id +"_content", {
                                    removePlugins: 'easyimage, exportpdf, cloudservices',
                                    extraPlugins: 'html5audio',                            
                                });        
                            } else {
                            
                                CKEDITOR.replace( data.id +"_description", {
                                    removePlugins: 'easyimage, exportpdf, cloudservices',
                                    extraPlugins: 'html5audio',                            
                                });                            
                            }
                            //addCField(data.id, 1);                        

                            $( ".tabs" ).tabs();
                        }
                    }
                });

                 return false;
            });    
            
            /***************************************************************
                            [START] - [NEW PAGE]
            *****************************************************************/

            //[start] - ADD Button AUTO SAVE
            $("#btn_page").click(function () {

                var $div = $("<div>", { 
                        "id": "page-"+ pageCtr +"",
                        "class": "card esi-card-header-page mb-4 handle", 
                        "style": "min-height:200px",
                        //"text":' Page :' + pageCtr
                });
                //.sortable();
                $("#form-content").append($div);
                $("#form-content").find('#page-'+pageCtr).append("<div class='card-header'>Page : "+ pageCtr +"</div>");
                $("#form-content").find('#page-'+pageCtr).append("<div class='card-body droptrue'> </div>");
                $( ".droptrue" ).sortable({ 
                    connectWith: "div", 
                    handle: '.handle',
                    update: function( event, ui) {
                        console.log($(this).parent().attr('id'));
                        let page = $(this).parent().attr('id');
                        $('#'+page).find('.page').val(page)
                    }                
                });


                pageCtr = parseInt(pageCtr) + 1;
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

        function basename(str)
        {
            return str.split('/').reverse()[0];            
        }

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

    <script>  
       window.addEventListener('load', function () 
        {
            $('#btnUploadTab').on('click', function()
            {
                //$('#modal_gallery').find('#template').html("test");
            });
        });
    </script>

@endsection



@section('scripts')
<script src="{{ url('js/dropzone/dropzone.min.js') }}"></script>

<script>    

    window.addEventListener('load', function () 
    {
        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template");
        //previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);



        var myDropzone = new Dropzone('form#writing_dropzone', { // Make the whole body a dropzone
            maxFiles: 1,
            maxFilesize: 10,
            url: "{{ url('api/writing/upload?api_token=') }}" + api_token, // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 1,
            uploadMultiple: false,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button",// Define the element that should be used as click trigger to select files.,
            init: function() {
                this.on("addedfile", function() {
                    if (this.files[1]!=null){
                        //    this.removeFile(this.files[1]);
                    }
                });
            }            
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1";
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0";
        });

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        /*
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true);
        };      
        */
    });
</script>
@endsection