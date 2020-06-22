@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div id="sidebar" class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        

        <div class="folder-container col-md-10 my-4">
            <vue-tree-list-component 
                ref="treeListComponent"
                csrf_token="{{ csrf_token() }}" 
                :can_user_delete_uploads="{{ $can_user_delete_uploads }}"
                :folders="{{ json_encode($folders) }}"
                api_token="{{ Auth::user()->api_token }}"
            />
        </div>




    </div>
</div>
@endsection
