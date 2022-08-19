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