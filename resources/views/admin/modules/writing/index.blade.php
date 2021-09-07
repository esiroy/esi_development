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
                    <div class="card-body esi-card-body">
                        Field(s) Here
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                @include('admin.modules.writing.includes.fieldButtons')

            </div>
        </div>
    </div>

    @include('admin.modules.writing.includes.FormFields.simpleTextModal')




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

    <script type="text/javascript">
        window.addEventListener('load', function() {

            //Show SimpleText
            $("#btn_simpleInputText").on("click", function() {
                $("#modal_simpleText").modal();
                $('#form_simpleText').trigger("reset");
            });

            $("#btn_simpleText_Save").on("click", function() {
                alert('saving...');
               

                $.ajax({
                    type: 'POST',
                    url: 'api/saveField?api_token=' + api_token,
                    data: {
                        formID: formID,
                        id:id,

                        parent: parentid,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        //total credits
                        $('#total_credits').text(data.credits);

                        if (data.refresh == true) {
                            location.reload();
                        }
                    }
                });


            });
            //Add to Table SimpleText

        });
    </script>
@endsection
