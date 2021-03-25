@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">
    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Settings</li>
            </ol>
        </nav>

        <!--[start] container -->
        <div class="container">
            @include('includes.session.message')
            <!--[start] asi-card-->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                   ユーザーの設定
                </div>
                <div class="card-body">

                    <div class="bg-gray p-2 mt-4">
                        <div class="pl-2 font-weight-bold small">ユーザの写真</div>
                    </div>

                    <div id="uploaded_image" class="mt-4 mb-4">
                        @php                
                            //get photo
                            $memberObj = new \App\Models\Member;
                            $memberPhoto = $memberObj->where('user_id', Auth::user()->id)->first();

                            $userImageObj = new \App\Models\UserImage;
                            $userImage = $userImageObj->getMemberPhoto($memberPhoto); 
                        @endphp

                        @if ($userImage == null)
                            <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" >
                        @else 
                            <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
                        @endif

                    </div>

                    <div class="container">
                        <div class="row mb-4">                            
                            <div class="col-md-12">
                                <div class="panel-body">
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


                    ファイルを選択」→ご自身の写真などを選択→「Upload]をクリック<br/>
                    お勧め画像方式とサイズ　　JPG方式　サイズ100X100（75X75)

                    <!--[start] change password-->
                    @include('modules.member.settings.includes.formPassword')
                    <!--[end] change password-->
                    
                    <!--[start] change user details-->
                    @include('modules.member.settings.includes.changeUserDetails')
                    <!--[end] change user details-->
                </div>
            </div>
            <!--[end] card-->
        </div>
        <!--[end] container-->

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
                            url: "{{ url('image-upload') }}",
                            type: "POST",
                            data:{
                                _token:"{{csrf_token()}}",
                                "id": "{{ Auth::user()->id }}",
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
