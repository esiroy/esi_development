@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div id="sidebar" class="col-2">
            @include('admin.partials.sidebar')
        </div>

        <div class="folder-container my-4 col-10">
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
