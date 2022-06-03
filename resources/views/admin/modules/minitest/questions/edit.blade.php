@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/admin/minitest/categories') }}">Minitest</a></li>

                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('admin.minitest.questions.index', ['category_id' => $category_id ]) }}"> {{ $category->name ?? '' }} </a>
                </li>



                 <li class="breadcrumb-item">
                    <a href="{{ route('admin.minitest.questions.index', ['category_id' => $category_id ]) }}">Questions</a>
                </li>


                 <li class="breadcrumb-item active" aria-current="page">{!!  $item->question !!}</li>
            </ol>
        </nav>

        <div class="container">

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @elseif (session('error_message'))
                <div class="alert alert-danger">
                    {{ session('error_message') }}
                </div>
            @endif


            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Minitest Question Edit
                </div>
                <div class="card-body">
                
                    <form method="POST" action="{{ route('admin.minitest.questions.update', ['category_id'=> $item->category_id, 'question' => $item ]) }}">
                        @csrf
                        @method('PATCH')

                        <div class="row pt-2">

                            <div class="col-2 small">
                                <label for="question" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Question </label>
                            </div>
                            <div class="col-9">

                                <textarea id="question"  name="question"  type="question" class="form-control form-control-sm @error('question') is-invalid @enderror"
                                value="{{ old('question', isset($item->question) ? $item->question: '') }}" 
                                required autocomplete="question"
                                >{{ old('question', isset($item->question) ? $item->question: '') }}</textarea>


                                @error('question')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                               
                          
                        </div>

                        <div class="row py-4">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Update </button>
                                <a href="{{ route('admin.minitest.questions.index', ['category_id' => $category_id ]) }}" class="btn btn-danger btn-sm">Cancel</a>
                            </div>
                        </div>

                    </form>                    
                    
                </div>
            </div>
            <!--[end] card-->

        </div>
    </div>

</div>
@endsection

@section('styles')
@parent

@endsection

@section('scripts')
@parent
<script src="{{ url('js/ckeditor/ckeditor.js')  }}" ></script>
<script type="text/javascript">
    function addTextFormatter(id) {
        CKEDITOR.replace( id , {
            toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    
                ],
            removePlugins: 'easyimage, exportpdf, cloudservices',
            extraPlugins: 'html5audio',                        
            removeButtons: 'Templates,Print,Form,SelectAll,Find,Replace,Maximize,About,ExportPdf,NewPage,Save,Cut,PasteFromWord,PasteText,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,CopyFormatting,RemoveFormat,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,Format,Font,Styles,Anchor'
        });
    }
</script>
<script type="text/javascript">
    window.addEventListener('load', function() {
        addTextFormatter("question");  
    });

</script>
@endsection