@extends('layouts.admin')

@section('content')
<div class="container bg-light pb-5 rounded-bottom">
    <div class="row">
        <div class="col-md-12">
            <input type="date" class="inputDate form-control form-control-sm col-sm-12 col-md-12">
        </div>
    </div>
</div>
@endsection

@section('styles')
@parent
<style>


.inputDate::after {    
font-family: "Font Awesome\ 5 Free";
    content: "\F133";
    padding-right: 200px;
    padding-left: 200px;
    position: absolute;
    right: 0%;
    color: red
}

</style>
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