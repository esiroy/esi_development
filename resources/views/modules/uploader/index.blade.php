@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="folder-container col-md-12 my-4">

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