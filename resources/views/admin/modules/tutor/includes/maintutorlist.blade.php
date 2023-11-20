<!--
    @description : Main Tutor List

-->

<table id="dataTable" class="table esi-table table-bordered table-striped table-hover datatable dataTable no-footer">
    <thead>
        <tr>
            <th class="small text-center">Name</th>
            <th class="small text-center">Birthday</th>
            <th class="small text-center">Member Since</th>
            <th class="small text-center">Attribte</th>
            <th class="small text-center">English Level</th>
        </tr>
    </thead>

    <tbody>

        @foreach($members as $member)
        <tr>
            <td>{{ $member->firstname ?? "-" }} {{ $member->lastname }}</td>
            <td>{{ date('F d, Y', strtotime($member->birthday)) }}</td>
            <td>{{ date('F d, Y', strtotime($member->member_since))}}</td>
            <td>{{ $member->attribute ?? "-" }}</td>
            <td>{{ $member->english_level ?? "-" }}</td>
        </tr>
        @endforeach

    </tbody>
</table>

<div class="float-right mt-4">
    <ul class="pagination pagination-sm">
        <small class="mr-4 pt-2"> Page :  {{ $members->currentPage() }} </small>          
        {{ $members->appends(request()->query())->links() }}           
    </ul>
</div>    
