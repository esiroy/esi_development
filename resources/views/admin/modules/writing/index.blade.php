@extends('layouts.admin')

@section('content')
    <div class="container bg-light px-0">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light ">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tutor</li>
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


                <div class="card esi-card">
                    <div class="card-header esi-card-header">
                        Form
                    </div>

                    <!--[START DYNAMIC FORMS]-->
                    <form id="dynamicForms" name="dynamicForms" method="POST"  action="{{ route('admin.writing.update', 1) }}">                                       
                     @csrf
                        <div id="form-content" class="card-body esi-card-body">
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

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" defer></script>

    <script type="text/javascript">
        var api_token = "{{ Auth::user()->api_token }}";


        function addNewSelectionChoice(id, ctr)
        {            
            console.log(id);

            let addButton = '<a class="field_choice_add"><i class="fas fa-plus-circle pt-2"></i></a> ';
            let removeButton = '<a class="field_choice_remove"><i class="fas fa-minus-circle pt-2"></i></a> ';

            let leftColumn = '<div class="col-md-10 pr-0"><input id="select_choice_text_'+ ctr +'" type="text" value="" class="form-control mb-1"> </div>';
            let rightColumn = '<div class="col-md-2 pl-1">'+ addButton + ' ' +  removeButton + '</div>'

            $("#"+ id).after('<div id="choice_'+ ctr +'" class="row mb-1">'+ leftColumn + rightColumn +"</div>");
        }


        function appendSelectionChoice(id, ctr)
        {            
            
            let element = $("#"+ id).attr('id');
            let elementName = element.split("_");
            let fieldID = elementName[0];

            console.log(id);

            let addButton = '<a class="selected_field_choice_add"><i class="fas fa-plus-circle pt-2"></i></a> ';
            let removeButton = '<a class="selected_field_choice_remove"><i class="fas fa-minus-circle pt-2"></i></a> ';

            let leftColumn = '<div id="dropdown_'+ ctr +'" class="col-md-10 pr-0"><input name="'+fieldID+'_selected_choice_text[]" type="text"  class="form-control mb-1 appendedSelection"> </div>';
            let rightColumn = '<div class="col-md-2 pl-1">'+ addButton + ' ' +  removeButton + '</div>'


            $("#"+ id).after('<div id="'+fieldID+'_new_selected_choice_'+ ctr +'" class="row mb-1">'+ leftColumn + rightColumn +"</div>");
        }        


        window.addEventListener('load', function() 
        {
            $( ".tabs" ).tabs();

            let ctr = 1;

            //Show Field Options, Activate Field Container on click 
            $(".field_container").on("click", function() {
                let id = $(this).find('#id').val();
                $("#dynamicForms").find('.tab-container').addClass('d-none');
                $(this).find('.tab-container').removeClass('d-none');                        
            });

            $(document).on("click", '.field_container', function() 
            {
               let id = $(this).find('#id').val();
                $("#dynamicForms").find('.tab-container').addClass('d-none');
                $(this).find('.tab-container').removeClass('d-none');     
            });


            //Show SimpleText
            $("#btn_simpleInputText").on("click", function() {
                $("#modal_simpleText").modal();
                $('#form_simpleText').trigger("reset");
            });

            /* DROPDOWN SELECT */           
            $("#btn_dropdownSelect").on("click", function() 
            {
                $("#select_choices").html("");
                $("#select_choices").append("<div id='select_choice_start'></div>");
                addNewSelectionChoice('select_choice_start', 1);                
                $("#modal_dropdownSelect").modal();
                $('#modal_dropdownSelect').trigger("reset");
            });           

        
            $(document).on("click", '.field_choice_add', function() 
            {
                ctr = ctr + 1;
                let id = $(this).parent().parent().attr('id');
                addNewSelectionChoice(id, ctr);
            });

            //append from previous
            $(document).on("click", '.selected_field_choice_add', function() 
            {
                ctr = ctr + 1;
                let id = $(this).parent().parent().attr('id');
                appendSelectionChoice(id, ctr);
                //update value when see changes              
            });

           


            
            //Save
            $("#btnSimpleTextSave").on("click", function() 
            {              
                $.ajax({
                    type: 'POST',
                    url: "{{ url('api/saveSimpleTextField?api_token=') }}" + api_token,
                    data: {
                        formID      : 1,
                        label       :  $('#modal_simpleText').find('input#label').val(),
                        description :  $('#modal_simpleText').find('textarea#description').val(),
                        maximum_characters :  $('#modal_simpleText').find('input#maximum_characters').val(),
                        required :  $('#modal_simpleText').find('input#required').prop("checked")
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field );
                        $( ".tabs" ).tabs();
                    }
                });

            });
            


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
                        formID              :    1,
                        label               :  $('#modal_dropdownSelect').find('input#label').val(),
                        description         :  $('#modal_dropdownSelect').find('textarea#description').val(),
                        maximum_characters  :  $('#modal_dropdownSelect').find('input#maximum_characters').val(),                        
                        selected_choices    :  choices,
                        required :  $('#modal_simpleText').find('input#required').prop("checked")
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {                       
                        $( "#form-content" ).append( data.field );
                        $( ".tabs" ).tabs();
                    }
                });
            });
            

        });
    </script>
@endsection
