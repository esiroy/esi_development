@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box mb-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User</li>
            </ol>
        </nav>

        <!--[start] container-->
        <div class="container pb-5">
            <div class="row">
                <!--sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>
                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div>
                <!--[end sidebar]-->

                <div class="col-md-9">
                    @include('modules.member.includes.profile')
                </div>
            </div>
        </div><!--[end container]-->
    </div>
</div>
@endsection


@section('scripts')
@parent
<script type="text/javascript">
    var api_token = "{{ Auth::user()->api_token }}";

    function cancel(id)
    {
        if (confirm('このレッスンをキャンセル（欠席）されるとポイントは消化されます。キャンセル(欠席）しますか？')) 
        {
            $.ajax({
                type: 'POST', 
                url: 'api/cancelSchedule?api_token=' + api_token,
                data: {
                    id: id
                }, headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function(data) {
                
                    $('.row_reserve_' + id ).hide();                
                }
            });
        }
    }

</script>
@endsection
