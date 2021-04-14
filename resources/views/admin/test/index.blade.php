@extends('layouts.admin')

@section('content')
<div class="container bg-light pb-5 rounded-bottom">
    <div class="row">
        <div class="col-md-12">
            <input id="inputDate" type="date" class="inputDate form-control form-control-sm col-sm-12 col-md-12">

        </div>
    </div>
</div>
@endsection



@section('scripts')
@parent

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>


<script type="text/javascript">

    var api_token = "{{ Auth::user()->api_token }}";

    window.addEventListener('load', function () 
    {


        jQuery(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        }).trigger("change")


     



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