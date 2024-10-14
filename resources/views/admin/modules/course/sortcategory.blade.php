@extends('layouts.adminsimple')

@section('content')
<div class="container bg-light px-0">

    @include('admin.menus.manage')

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
                    <form method="post" action="{{ route("admin.course.savesortedcategory")  }}">
                    @csrf
                        <table class="esi-table table table-hover table-bordered table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                </tr>
                            </thead>
                            <tbody id="sortable" class="ui-sortable">
                                @foreach($categories as $category)
                                <tr>
                                    <td class="small">
                                        <input type="hidden" name="courseids[]" value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="float-right my-4">
                            <input type="submit" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

</div>
@endsection


@section('scripts')
@parent
<script src="https://code.jquery.com/jquery-1.12.4.js" defer></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" defer></script>
<script>
    window.addEventListener('load', function() {
        jQuery( function() {
            jQuery( "#sortable" ).sortable();
            jQuery( "#sortable" ).disableSelection();
        } );
    });
</script>
@endsection