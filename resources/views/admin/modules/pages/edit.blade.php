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

        <div class="container mt-4">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Edit Page
                </div>
                <div class="card-body">

                
                    <form action="{{ route('admin.pages.update', $page->id ) }}" method="POST" enctype="multipart/form-data">                    
                        @csrf
                        @method('PATCH')
                        <table cellspacing="9" cellpadding="0" class="table table-borderless">
                            <tbody>

                                <tr valign="top">
                                    <td>Title </td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm col-8"  id="title" name="title" required maxlength="190" value="{{ old('title', isset($page->title ) ? $page->title : '') }}"></input>
                                    </td>
                                </tr>                            

                                <tr valign="top">
                                    <td>Content </td>
                                    <td>:</td>
                                    <td>
                                        <textarea required style="width: 450px; height: 300px; display: none;" id="content" name="content">{{ old('title', isset($page->content ) ? $page->content : '') }}</textarea>
                                    </td>
                                </tr>

                             
                                <tr valign="top">
                                    <td colspan="2">Publish To :</td>
                                    <td colspan="4">
                                        <input type="checkbox" name="is_netenglish" value="NETENGLISH" @if ($page->is_netenglish == true) {{ " checked "}} @endif > NETENGLISH
                                        <span class="mr-2"></span>
                                        <input type="checkbox" name="is_mytutor" value="MYTUTOR" @if ($page->is_mytutor == true) {{ " checked "}} @endif > MY TUTOR
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <td colspan="2">PUBLISHED?</td>
                                    <td colspan="4">
                                        <input type="checkbox" name="is_published" value="PUBLISHED" @if ($page->is_published == true) {{ " checked "}} @endif> 
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