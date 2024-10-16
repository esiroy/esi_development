@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">
    
    @include('admin.menus.manage')


    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pages</li>
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
                    Pages
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="float-right">
                                <ul class="pagination pagination-sm">
                                    <small class="mr-4 pt-2">Page :</small>
                                    {{ $pages->appends(request()->query())->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="table-responsive ">
                                <table class="table table-bordered table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="small pl-2 text-left small bg-light text-dark font-weight-bold">Title</th>                                            
                                            <th class="small pl-2 text-left small bg-light text-dark font-weight-bold">Content</th>
                                            <th class="small pl-2 text-left small bg-light text-dark font-weight-bold" style="width:175px">Publishing | Origin </th>  
                                            <th class="small pl-2 text-left small bg-light text-dark font-weight-bold" style="width:125px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($pages as $page)
                                        <tr>
                                            <th class="small text-left pl-2 ">{{ $page->title }}</th>
                                            <th class="small text-left pl-2"> 
                                                @php 
                                                    $stripptedContent = $page->content;
                                                    $cleanedText = strip_tags($stripptedContent); // Strip the HTML tags
                                                @endphp
                                                {!! html_entity_decode(Str::limit($cleanedText, 150) ) !!}
                                            </th>
                                            <th class="small ">
                                                @if ($page->is_published == true)
                                                    <span class="badge badge-primary small">Published</span>
                                                @else 
                                                    <span class="badge badge-secondary small">Unpubished</span>
                                                @endif

                                                @if ($page->is_netenglish == true && $page->is_mytutor)            
                                                    <span class="badge badge-info small">Both</span>  
                                                @elseif ($page->is_netenglish == true)                                                
                                                    <span class="badge badge-info small">NET English</span>
                                                @elseif ($page->is_mytutor == true)                                                
                                                    <span class="badge badge-info small">MyTutor</span>                                                    
                                                @endif
                                            </th>  
                                            <th class="small text-left pl-2">
                                                <a href="{{ route('pages.show', $page->slug)}}" class="esiModal">View</a> |         
                                                <a href="{{ route('admin.pages.edit', $page->id)}}">Edit</a> |                                               
                                                <a href="{{ route('admin.pages.destroy', ['page' => $page]) }}" onclick="confirmSubmitAction('delete-form-{{ $page->id }}'); return false; ">Delete</a>
                                                <form id="delete-form-{{ $page->id }}" action="{{ route('admin.pages.destroy', ['page' => $page]) }}" method="POST" style="display: none;">
                                                    @method("DELETE")
                                                    @csrf
                                                </form>
                                            </th>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="float-right">
                                <ul class="pagination pagination-sm">
                                    <small class="mr-4 pt-2">Page :</small>
                                    {{ $pages->appends(request()->query())->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!--[end] card-->
        </div>


        <div class="container mt-4">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Create Page
                </div>
                <div class="card-body">

                

                    <form action="{{ route("admin.pages.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <table cellspacing="9" cellpadding="0" class="table table-borderless">
                            <tbody>

                                <tr valign="top">
                                    <td>Title </td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm col-8"  id="title" name="title" required maxlength="190"></input>
                                    </td>
                                </tr>                            

                                <tr valign="top">
                                    <td>Content </td>
                                    <td>:</td>
                                    <td>
                                        <textarea required style="width: 450px; height: 300px; display: none;" id="content" name="content"></textarea>
                                    </td>
                                </tr>

                             
                                <tr valign="top">
                                    <td colspan="2">Publish To :</td>
                                    <td colspan="4">
                                        <input type="checkbox" name="is_netenglish" value="NETENGLISH"> NETENGLISH
                                        <span class="mr-2"></span>
                                        <input type="checkbox" name="is_mytutor" value="MYTUTOR"> MY TUTOR
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td colspan="2">PUBLISHED?</td>
                                    <td colspan="4">
                                        <input type="checkbox" name="is_published" value="PUBLISHED"> 
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <input type="submit" value="Save" class="btnPink">
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
@endsection




@section('styles')
@parent
<style>
    input.inputDate {}

    input.inputDate:before {
        content: attr(data-date);
    }

    input.inputDate::-webkit-datetime-edit,
    input.inputDate::-webkit-inner-spin-button,
    input.inputDate::-webkit-clear-button {
        display: none;
    }

    input.inputDate::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }

</style>
@endsection

@section('scripts')
<script src="https://mypage.mytutor-jpn.com/js/ckeditor/ckeditor.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
<script type="text/javascript">
    function confirmSubmitAction(formID) {
        let confirmAction = confirm("Are you sure to execute this action?");
        if (confirmAction) {            
            document.getElementById(formID).submit();            
        } else {             
            return false;
        }
    }

    window.addEventListener('load', function() 
    {        
        $(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        });

        //editor
        CKEDITOR.replace('content');
    });


</script>
@endsection