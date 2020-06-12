@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div id="sidebar" class="col-md-2">
            @include('admin.partials.sidebar')
        </div>

        <div class="col-7 py-4">
            <div class="card">
                <div class="card-header">Upload Files</div>
                <div class="card-body">

                   @can('filemanager_upload')

                        <simple-uploader-component 
                            ref="uploaderComponent" 
                            user_can_delete="{{ $can_user_delete_uploads }}"
                            folder_id="{{ $folder->id }}" csrf_token="{{ csrf_token() }}"
                        />

                    @elsecan('filemanager_show')

                        <div class="alert alert-danger">
                            <strong>Warning!</strong> @lang('global.accessDenied')</a>.
                        </div>
                        
                    @endcan

                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header pr-0">
                    All Files
                </div>

                <div class="card-body">
                    <folder-files-component 
                        ref="folderComponent" 
                        :folder_files="{{ json_encode($files) }}"
                        :can_user_delete_uploads="{{ $can_user_delete_uploads }}"
                    />
                </div>
            </div>
        </div>

        <div class="col-md-3 py-4">
            <div class="card">
                <div class="card-header">
                    Folder Details

                    @canany(['filemanager_edit', 'filemanager_delete'])
                    <div class="dropdown" style="display:inline-block;">
                        <button class="btn btn-sm pl-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="bi bi-gear" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.837 1.626c-.246-.835-1.428-.835-1.674 0l-.094.319A1.873 1.873 0 0 1 4.377 3.06l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292a1.873 1.873 0 0 1-1.115 2.692l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 1 1.115 2.693l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 1 2.692 1.116l.094.318c.246.835 1.428.835 1.674 0l.094-.319a1.873 1.873 0 0 1 2.693-1.115l.291.16c.764.415 1.6-.42 1.184-1.185l-.159-.291a1.873 1.873 0 0 1 1.116-2.693l.318-.094c.835-.246.835-1.428 0-1.674l-.319-.094a1.873 1.873 0 0 1-1.115-2.692l.16-.292c.415-.764-.42-1.6-1.185-1.184l-.291.159A1.873 1.873 0 0 1 8.93 1.945l-.094-.319zm-2.633-.283c.527-1.79 3.065-1.79 3.592 0l.094.319a.873.873 0 0 0 1.255.52l.292-.16c1.64-.892 3.434.901 2.54 2.541l-.159.292a.873.873 0 0 0 .52 1.255l.319.094c1.79.527 1.79 3.065 0 3.592l-.319.094a.873.873 0 0 0-.52 1.255l.16.292c.893 1.64-.902 3.434-2.541 2.54l-.292-.159a.873.873 0 0 0-1.255.52l-.094.319c-.527 1.79-3.065 1.79-3.592 0l-.094-.319a.873.873 0 0 0-1.255-.52l-.292.16c-1.64.893-3.433-.902-2.54-2.541l.159-.292a.873.873 0 0 0-.52-1.255l-.319-.094c-1.79-.527-1.79-3.065 0-3.592l.319-.094a.873.873 0 0 0 .52-1.255l-.16-.292c-.892-1.64.902-3.433 2.541-2.54l.292.159a.873.873 0 0 0 1.255-.52l.094-.319z"/>
                            <path fill-rule="evenodd" d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492zM4.754 8a3.246 3.246 0 1 1 6.492 0 3.246 3.246 0 0 1-6.492 0z"/>
                            </svg>
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            @can('filemanager_edit')
                            <a class="dropdown-item small" href="{{ URL::route('admin.module.filemanager.edit', $folder->folder_name) }}">Edit Folder</a>
                            @endcan

                            @can('filemanager_delete')
                            <form action="{{ URL::route('admin.module.filemanager.destroy', $folder->id) }}" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="dropdown-item small">Delete Folder</button>
                            </form>
                            @endcan
                            
                        </div>
                    </div>
                    @endcanany

                    <div class="float-right">
                        <a href="{{ asset('folder/' . $folder->folder_name) }}" alt="open public link" title="open public link" target="_blank">
                            <svg class="bi bi-link" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
                                <path d="M6.764 6.5H7c.364 0 .706.097 1 .268A1.99 1.99 0 0 1 9 6.5h.236A3.004 3.004 0 0 0 8 5.67a3 3 0 0 0-1.236.83z"/>
                                <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
                                <path d="M8 11.33a3.01 3.01 0 0 0 1.236-.83H9a1.99 1.99 0 0 1-1-.268 1.99 1.99 0 0 1-1 .268h-.236c.332.371.756.66 1.236.83z"/>
                            </svg>
                        </a>

                        <a href="{{ URL::route('admin.module.filemanager.index') }}" alt="back to folders" title="back to folders">
                            <svg class="bi bi-arrow-left-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z"/>
                            <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    

                     <div class="row">
                        <div class="col-md-5 small text-muted">
                            Folder ID
                        </div>
                        <div class="col-md-7 small text-muted">
                            <a href="{{ asset('folder/' . $folder->folder_name) }}" alt="open public link" title="open public link" target="_blank">
                                {{ $folder->id }}
                            </a>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-5 small text-muted">
                            Folder 
                        </div>
                        <div class="col-md-7 small text-muted">
                            <a href="{{ asset('folder/' . $folder->folder_name) }}" alt="open public link" title="open public link" target="_blank">
                                {{ $folder->folder_name }}
                            </a>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-5 small text-muted">
                            Description
                        </div>
                        <div class="col-md-7 small text-muted">
                           {{ $folder->folder_description }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    Folder List
                    <div class="float-right">
                        <a href="{{ URL::route('admin.module.filemanager.index') }}">
                            <svg class="bi bi-arrow-left-square" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z"/>
                            <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="folders" >
                        @foreach ($folders as $folder_item)
                            <div class="mx-2">
                                <a href="{{ URL::to('/admin/module/filemanager/'. $folder_item->folder_name ) }}">
                                    <svg class="bi bi-folder" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.828 4a3 3 0 0 1-2.12-.879l-.83-.828A1 1 0 0 0 6.173 2H2.5a1 1 0 0 0-1 .981L1.546 4h-1L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3v1z"/>
                                        <path fill-rule="evenodd" d="M13.81 4H2.19a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zM2.19 3A2 2 0 0 0 .198 5.181l.637 7A2 2 0 0 0 2.826 14h10.348a2 2 0 0 0 1.991-1.819l.637-7A2 2 0 0 0 13.81 3H2.19z"/>
                                    </svg>
                                </a>
                                <a href="{{ URL::to('/admin/module/filemanager/'. $folder_item->id ) }}">{{ $folder_item->folder_name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>

    </div>
</div>
@endsection