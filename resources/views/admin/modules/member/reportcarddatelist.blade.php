@extends('layouts.admin')
@section('content')
<div class="container bg-light px-0">
    @include('admin.modules.member.includes.menu')
    <div class="esi-box">
        <!--@include('admin.modules.member.includes.breadcrumbs')-->
        <div class="container mt-5">
            <div class="card esi-card mt-5">
                <div class="card-header esi-card-header">
                   Writing Report Card Monthly List
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.modules.member.includes.info')
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-4">

                            <table class="table esi-table table-bordered table-striped  ">
                                <thead>
                                    <tr>
                                        <th>Lesson Date &amp; Time</th>
                                        <th>Course</th>
                                        <th>Material</th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Created By</th>
                                        <th>Uploaded File</th>

                                        @if(Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER')
                                        <th>Action</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($reportcards as $item)
                                    <tr>
                                        <td>{{ $item->lesson_date }}</td>
                                        <td>{{ $item->lesson_course }}</td>
                                        <td>{{ $item->lesson_material }}</td>
                                        <td>{{ $item->lesson_subject }}</td>
                                        <td>{{ $item->grade }}</td>
                                        <td>
                                        @php
                                        $createBy = \App\Models\User::find($item->created_by_id)
                                        @endphp

                                        {{  $createBy->firstname }}
                                        </td>
                                        <td>
                                            <a href="{{ Storage::url("uploads/report_files/". basename($item->file_path)) }}" download>DOWNLOAD</a>
                                            <!--{{ $item->file_name }}-->
                                        </td>

                                        @if(Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER')
                                        <td> 
                                            <a href="{{ route('admin.reportcarddate.edit', $item->id) }}" class="btn btn-sm btn-info">Edit</a>  
                                            <form action="{{ route('admin.reportcarddate.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                            </form>
                                        </td>
                                        @endif
                          
                              
                                    </tr>
                                    @endforeach


                                    @if (count($reportcards) == 0)
                                    <tr>
                                        <td colspan="8">
                                            <div class="text-center">No Result</div>
                                        </td>
                                    </tr>
                                    @endif

                                </tbody>
                            </table>

                            <div class="float-right mt-4">
                                <ul class="pagination pagination-sm">
                                    <small class="mr-4 pt-2">
                                        Page :</small>
                                    {{ $reportcards->appends(request()->query())->links() }}
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
