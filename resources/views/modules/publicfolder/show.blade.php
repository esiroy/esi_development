@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row">
        <div class="folder-container col-md-12">
            <vue-tree-list-component 
                ref="treeListComponent"

                :public="{{ 'true' }}"
                :public_folder_id="{{ $folder->id }}"

                :can_user_create_folder="{{ $can_user_create_folder }}"
                :can_user_edit_folder="{{ $can_user_edit_folder }}"
                :can_user_delete_folder="{{ $can_user_delete_folder }}"
                :can_user_upload="{{ $can_user_upload }}"
                :can_user_delete_uploads="{{ $can_user_delete_uploads }}"
                :folders="{{ json_encode($folders) }}"
                api_token="{{ '' }}"
                csrf_token="{{ csrf_token() }}"
                
            />
        </div>




    </div>
</div>
@endsection
