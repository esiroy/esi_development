@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">
    <div class="esi-box">

       
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">File Manager</li>
            </ol>
        </nav>
      

        <div class="container">  
                        
            <vue-tree-list-component 
                ref="treeListComponent"
                :user="{{ Auth::user() }}"
                :users="{{ json_encode($users) }}"
                :can_user_share_uploads="{{ $can_user_share_uploads }}"
                :can_user_share_folder="{{ $can_user_share_folder }}"
                :can_user_create_folder="{{ $can_user_create_folder }}"
                :can_user_edit_folder="{{ $can_user_edit_folder }}"
                :can_user_delete_folder="{{ $can_user_delete_folder }}"
                :can_user_upload="{{ $can_user_delete_uploads }}"
                :can_user_delete_uploads="{{ $can_user_delete_uploads }}"
                :can_user_manage_folder="{{ $can_user_manage_folder }}"
                :folders="{{ json_encode($folders) }}"
                api_token="{{ Auth::user()->api_token }}"
                csrf_token="{{ csrf_token() }}"
            />       
        </div>

        

    </div>
</div>
@endsection

@section('scripts')
@parent
<script src="{{ url('js/ckeditor/ckeditor.js')  }}"></script>
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
            //extraPlugins: 'html5audio',                        
            removeButtons: 'Templates,Print,Form,SelectAll,Find,Replace,Maximize,About,ExportPdf,NewPage,Save,Cut,PasteFromWord,PasteText,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,CopyFormatting,RemoveFormat,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks,Format,Font,Styles,Anchor'
        });    
    }

    window.addEventListener('load', function() {
        
    });

</script>
@endsection