@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">


        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tutor</li>
            </ol>
        </nav>

        <div class="container">

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

            <!--[start] tutor list-->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Tutor List
                </div>
                <div class="card-body esi-card-body">

                    <div class="row">
                        <form class="form-inline" style="width:100%" method="GET">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tutor_id" class="small col-4">ID:</label>
                                    <input name="tutor_id" type="text" class="form-control form-control-sm col-8" 
                                    value="{{ request()->has('tutor_id') ? request()->get('tutor_id') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name" class="small col-4">Name:</label>
                                    <input name="name" type="text" class="form-control form-control-sm col-8" value="{{ request()->has('name') ? request()->get('name') : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="small col-2">Email:</label>
                                    <input name="email" type="text" class="form-control form-control-sm col-4" value="{{ request()->has('email') ? request()->get('email') : '' }}">

                                    <input type="submit" class="btn btn-primary btn-sm col-1 ml-1" value="Go"></button>
                                </div>
                            </div>
                        </form>
                    </div>                    



                    <!--[start] Tutor List -->
                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="table-responsive">
                                @include('admin.modules.tutor.includes.tutorlist')
                            </div>
                        </div>
                    </div>
                    <!--[end] Tutor List -->

                </div>
            </div><!--[end] tutor list card-->


            @include('admin.modules.tutor.includes.createTutor')    
           

        </div>
    </div>

</div>
@endsection

@section('styles')
@parent
<style>
.dataTables_filter {
    display: none;
    float: left !important;
    padding-left: 20px;
}
</style>
@endsection

@section('scripts')
@parent
<script src="https://cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>
<script type="text/javascript">
    window.addEventListener('load', function() {
        ///let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        let dtButtons = $.extend(true, [], [])
        let _token = "{{ csrf_token() }}"

        $.extend(true, $.fn.dataTable.defaults, {
            order: [[0, 'DES']],
            pageLength: 1000,
            "columnDefs": [{
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }]
        });


        $('#dataTable').DataTable({
            buttons: dtButtons,
            "paging":   false,
        })

        CKEDITOR.replace('introduction', {
            toolbarGroups: [
                { name: 'document', groups: ['mode', 'document', 'doctools'] },
                { name: 'clipboard', groups: ['clipboard', 'undo']},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                {name: 'forms', groups: ['forms']},
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'links', 'indent', 'blocks', 'styles', 'align', 'bidi', 'paragraph']},
            ],
            removePlugins: 'easyimage, exportpdf, cloudservices',
            removeButtons: 'Save,Templates,Cut,Undo,SelectAll,Find,Scayt,Form,CopyFormatting,About,TextColor,Image,Outdent,Blockquote,BidiLtr,NewPage,ExportPdf,Preview,Print,Flash,CreateDiv,Indent,RemoveFormat,Underline,Copy,Paste,PasteText,PasteFromWord,Redo,Replace,Checkbox,Radio,TextField,Select,Textarea,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,BidiRtl,Language,BGColor,Styles,Format,Font,Anchor,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks'
        });


    });

</script>
@endsection