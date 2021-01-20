<div class="float-right">
    <ul class="pagination pagination-sm">{{ $tutors->appends(request()->query())->links() }}</ul>
</div>

<table id="dataTable" class="table esi-table table-bordered table-striped table-hover datatable">
    <thead>
        <tr>
            <th class="small text-center">&nbsp;</th>
            <th class="small text-center">Sort</th>
            <th class="small text-center">ID</th>
            <th class="small text-center">Name</th>
            <th class="small text-center">Member (Main)</th>
            <th class="small text-center">Member (Support)</th>
            <th class="small text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($tutors))
        @foreach ($tutors as $tutor)
        <tr data-entry-id="{{ $tutor->user_id }}">
            <td class="small text-center">&nbsp;</td>
            <td class="small text-center">{{ $tutor->sort}}</td>
            <td class="small text-center">{{ $tutor->user_id}}</td>
            <td class="small text-center">{{ $tutor->user->firstname?? "" }} {{ $tutor->user->lastname ?? "" }}</td>
            <td class="small text-center"><a href="{{ url('admin/maintutor/'. $tutor->user_id) }}"><img src="/images/iMemberMain.gif"></a></td>
            <td class="small text-center">
                <!--<a href="{{ url('admin/supporttutor/'. $tutor->user_id) }}"><img src="/images/iMemberSupport.gif"></a>-->
                <a href="#"><img src="/images/iMemberSupport.gif"></a>
            </td>
            <td class="small text-center">
                @can('tutor_delete')
                <a href="{{ route('admin.tutor.edit', $tutor->user_id) }}" class="btn btn-sm btn-info">Edit</a>
                @endcan

                @can('tutor_delete')
                <form action="{{ route('admin.tutor.destroy', $tutor->user_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                </form>
                @endcan
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>

<div class="float-right">
    <ul class="pagination pagination-sm">{{ $tutors->appends(request()->query())->links() }}</ul>
</div>