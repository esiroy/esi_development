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
                    Category
                </div>
                <div class="card-body">

                    <div class="mb-3 float-right">
                        <a href="{{ route('admin.course.sortcategory') }}">Sort parent category</a>
                    </div>


                    <table class="esi-table table table-hover table-bordered table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Parent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td class="small">
                                    @php 
                                        //get the heirchy of parent eg: parent <-sub
                                        $courseCategory = new \App\Models\CourseCategory();
                                    @endphp
                                    {!! $courseCategory->getParentNameHeirachy($category->id) !!}

                                    @php                                     
                                        $lessonMaterials = new \App\Models\LessonMaterial();
                                        $materialCount = $lessonMaterials->where('course_category_id', $category->id)->count();
                                    @endphp

                                    ({{$materialCount . " Files"}})
                                </td>
                                <td>
                                    {{ \App\Models\CourseCategory::find($category->parent_course_category)['name']}}               
                                </td>
                                <td>
                                    <a href="{{ route('admin.course.edit', ['course' => $category->id]) }}">Edit</a> |
                                   
                                    <a href="{{ route('admin.course.sortsubcategory', ['id' => $category->id]) }}">Sort</a> |

                                    <a href="{{ route('admin.course.destroy', ['course' => $category->id]) }}" onclick="event.preventDefault();document.getElementById('delete-form-{{ $category->id }}').submit();">Delete
                                    </a>
                                    <form id="delete-form-{{ $category->id }}" action="{{ route('admin.course.destroy', ['course' => $category->id]) }}" method="POST" style="display: none;">
                                        @method("DELETE")
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="float-right mt-4">
                        <ul class="pagination pagination-sm">
                            {{ $categories->appends(request()->query())->links() }}
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pt-4">
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Category Form
                </div>
                <div class="card-body esi-card-body">

                    <form action="{{ route("admin.course.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <table cellspacing="9" cellpadding="0" class="table table-borderless">
                            <tbody>

                                <tr valign="top">
                                    <td>Parent Category</td>
                                    <td>:</td>
                                    <td>
                                        <select name="parentid" class="form-control form-control-sm col-md-3">
                                            <option value="">-- Select --</option>
                                            @foreach($optionCategories as $category)
                                                <option value="{{ $category->id }}">
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
                                        <input required type="text" id="name*" name="name" class="form-control form-control-sm col-md-4">
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td>Category Description</td>
                                    <td>:</td>
                                    <td>
                                        <div class="col-md-8 p-0 m-0">
                                            <textarea id="body" name="body"></textarea>
                                        </div>
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



                </div>
            </div>
        </div>

    </div>
</div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.15.1/full/ckeditor.js"></script>
<script>
 CKEDITOR.replace('body', {
	toolbarGroups: [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },		
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'links', 'indent', 'blocks',  'styles', 'align', 'bidi', 'paragraph' ] },
	],
    removePlugins: 'easyimage, exportpdf, cloudservices',
	removeButtons: 'Save,Templates,Cut,Undo,SelectAll,Find,Scayt,Form,CopyFormatting,About,TextColor,Image,Outdent,Blockquote,BidiLtr,NewPage,ExportPdf,Preview,Print,Flash,CreateDiv,Indent,RemoveFormat,Underline,Copy,Paste,PasteText,PasteFromWord,Redo,Replace,Checkbox,Radio,TextField,Select,Textarea,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,BidiRtl,Language,BGColor,Styles,Format,Font,Anchor,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,ShowBlocks'
});
</script>
@endsection
