@extends('layouts.adminsimple')
@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/questionnaires') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/company') }}">Company</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Course</li>
            </ol>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if (session('message'))
                    <div class="alert alert-success">
                        {!! session('message') !!}
                    </div>
                    @elseif (session('error_message'))
                    <div class="alert alert-danger">
                        {!! session('error_message') !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>


        <div class="container pt-4">
            <div class="card esi-card">

                <div class="card-header esi-card-header">
                    Category Form
                </div>

                <div class="card-body esi-card-body">

                    <!--UPLOAD PHOTO - IMAGE SIZE REQUIREMENTS 100x100 -->
                    <div class="col-md-4">

                        <div class="category_image_container">
                            @if ($courseCategoryImage == null)
                            <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded">
                            @else
                            <img src="{{ Storage::url("$courseCategoryImage->path") }}" class="img-fluid border" alt="profile photo">
                            @endif
                        </div>


                        <form action="{{ route("admin.course.uploadCourseImage", ['course_category_id' => $course]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <table cellspacing="0" cellpadding="0" class="table table-borderless mt-4">
                                <tr valign="top">
                                    <td class="p-0 m-0">
                                        <input type="file" id="file" name="file" multiple="multiple" accept="image/*" />
                                    </td>
                                    <td class="p-0 m-0">
                                        <input type="submit" value="upload">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>


                    <form action="{{ route("admin.course.update", ['course' => $course]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <table cellspacing="9" cellpadding="0" class="table table-borderless">
                            <tbody>
                                <tr valign="top">
                                    <td>Parent Category</td>
                                    <td>:</td>
                                    <td>
                                             
                                        <select name="parentid">
                                            <option value="">-- Select --</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}" @if($category->id == $courseCategory->parent_course_category) {{ "selected" }}@endif>                                                  
                                                    @php 
                                                    //get the heirchy of parent eg: parent <-sub
                                                    $courseCategoryOption = new \App\Models\CourseCategory();
                                                    @endphp                                                   
                                                    {!! $courseCategoryOption->getParentNameHeirachy($category->id) !!}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>


                                <tr valign="top">
                                    <td>Name</td>
                                    <td>:</td>
                                    <td>
                                        <input required type="text" id="name*" name="name" value="{{ $courseCategory['name'] }}">
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td>Category Description</td>
                                    <td>:</td>
                                    <td>
                                        <textarea id="body" name="body">{{ $courseCategory['description'] }}</textarea>
                                    </td>
                                </tr>



                                <tr valign="top">
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <input type="submit" value="Save" class="btn-pink">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>             

                    <div class="pt-4">
                        <form method="post" action="{{ route("admin.course.uploadlessonmaterial", ['course' => $course]) }}" enctype="multipart/form-data" onsubmit="disableSubmit(this);">
                            @csrf
                            <input type="hidden" name="course_category_id" value="{{ $courseCategory->id  }}">
                             <table cellspacing="9" cellpadding="0" class="table table-borderless">
                                <tbody id="sortable" class="ui-sortable">
                                    <tr>
                                        <td>
                                            <p>Drag the files to sort</p>
                                            <!-- List all Lesson Materials -->
                                            @foreach ($lessonMaterials as $lessonMaterial)
                                                <div id="{{$lessonMaterial->filename}}">
                                                    <a href="{{ Storage::url("$lessonMaterial->path") }}" download>
                                                        <img src="{{ url('images/pdf.gif') }}"> {{$lessonMaterial->filename}} 
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a href="#" onclick="event.preventDefault();document.getElementById('delete-lessonMaterial-{{ $lessonMaterial->id }}').submit();">[delete]</a>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>                                     
                                        <td>
                                            <input type="file" name="upload[]"><a href="javascript:void(0)" onclick="addFileUpload(this);">Add another</a>
                                        </td>
                                    </tr>
                                </tbody>
                                <tr>                                  
                                    <td><input type="submit" value="Upload" class="btn-pink"></td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    @foreach ($lessonMaterials as $lessonMaterial)
                    <form id="delete-lessonMaterial-{{ $lessonMaterial->id }}" action="{{ route('admin.course.destroyLessonMaterial', ['id' => $lessonMaterial->id]) }}" method="POST" style="display: none;">
                        @method("DELETE")
                        @csrf
                    </form>                    
                    @endforeach

                </div>
            </div>
            <!--[end] card esi-card-->
        </div><!-- [end] contaner-->



    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('body', {
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

</script>

<script>
    function addFileUpload(obj) {
        var objTbody = jQuery(obj).parent().parent().parent();

        var courseCategoryId = '{{ $courseCategory->id  }}';

        /* Limiting the number of file upload
        if(objTbody.children().length < 6) {
        	objTbody.append("<tr><td></td><td><input type='hidden' name='courseItemId' value='"+courseItemId+"' /><input type='file' name='upload' /></td></tr>");
        } else {
        	alert("File upload exceeded. Maximum of 5 items.");
        }*/

        var inputFile = jQuery("input[type='file']");
        if (inputFile.length < 20) {
            objTbody.append("<tr><td colspan='3'><input type='file' name='upload[]' /></td></tr>");
        } else {
            alert("Maximum of 20 files per batch");
        }
    }

</script>
@endsection
