@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">


    @include('admin.modules.member.includes.menu')

    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>

        <div class="container mt-4">
            <div class="card esi-card">
                <div class="card-header esi-card-header">Update Member Form</div>
                <div class="card-body">

                    <div id="uploaded_image" class="mt-4 mb-4">
                        @if ($userImage == null)
                        <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" width="250">
                        @else
                        <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo" width="250">
                        @endif
                    </div>

                    <div class="container">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="panel-heading">Select Profile Image</div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel-body" align="center">
                                    <input type="file" name="upload_image" id="upload_image" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="uploadimageModal" class="modal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">                                    
                                    <h4 class="modal-title">Upload & Crop Image</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div id="image_demo"></div>
                                        </div>
                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-success btn-sm crop_image">Crop & Upload Image</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <member-update-component :memberships="{{ json_encode($memberships) }}" 
                        attributes="{{ json_encode($attributes) }}" 
                        :shifts="{{ json_encode($shifts) }}" 
                        :agentinfo="{{ json_encode($agentInfo) }}" 
                        :userinfo="{{ json_encode($userInfo)  }}" 
                        :memberinfo="{{ json_encode($memberInfo) }}" 
                        :purposes="{{ json_encode($lessonGoals) }}" 
                        :lessonclasses="{{ json_encode($lessonClasses) }}" 
                        :desiredschedule="{{ json_encode($desiredSchedule) }}" 
                        api_token="{{ Auth::user()->api_token }}" 
                        csrf_token="{{ csrf_token() }}" />
                </div>
            </div>

        </div>
    </div>
</div>

</div>
@endsection

@section('scripts')
@parent

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js' type='text/javascript'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css" />

<script>
    (function($) 
    {
            "use strict";

            $(document).ready(function() 
            {

                var $image_crop = $('#image_demo').croppie({
                        enableExif: true,
                        viewport: {
                            width: 200,
                            height: 200,
                            type: 'square' //circle
                        },
                        boundary: {
                            width: 300, height: 300
                        }
                });

                $('#upload_image').on('change', function(){
                    var reader = new FileReader();
                    reader.onload = function (event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                    }
                    reader.readAsDataURL(this.files[0]);
                    $('#uploadimageModal').modal('show');
                });//[end] #upload_image


                $('.crop_image').click(function(event){
                    $image_crop.croppie('result', {
                        type: 'canvas',
                        size: 'viewport'
                    }).then(function(response){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },                            
                            url: "{{ url('admin/image-upload') }}",
                            type: "POST",
                            data:{
                                _token:"{{csrf_token()}}",
                                "id": "{{ $memberInfo->user_id }}",
                                "user_type": "members",
                                "image": response
                            },
                            success:function(data)
                            {
                                $('#uploadimageModal').modal('hide');
                                $('#uploaded_image').html(data);
                            }
                        });
                    })
                });
                                
            
            }); //[end] $(document)

    })(jQuery);
    
</script>
@endsection
