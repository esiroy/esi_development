@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">

     
    </div>
</div>


@endsection

@section('scripts')
@parent
<script type="text/javascript">

    var api_token = "{{ Auth::user()->api_token }}";

    window.addEventListener('load', function () 
    {
            $.ajax({
                type: 'POST',
                url: 'api/get_members?api_token=' + api_token,
                data: {
           
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                 
                    console.log(data.members);

                }
            });
    });

</script>
@endsection