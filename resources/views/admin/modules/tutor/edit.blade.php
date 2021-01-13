@extends('layouts.admin')
@section('content')

<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="container">

        <div class="row">
            <div class="col-12 pt-4">
                @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @elseif (session('error_message'))
                <div class="alert alert-danger">
                    {{ session('error_message') }}
                </div>
                @endif
            </div>
        </div>

        <div class="card esi-card mt-2">
            <div class="card-header esi-card-header">Reset Password</div>
            <div class="card-body">
                <form action="{{ route("admin.tutor.resetPassword", $tutor->user_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row m-0">
                        <label for="password" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Password') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-sm ml-2">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--[end] reset password card-->
    </div>
    <!--[end] reset password containter-->

    <div class="container">
        <!--[start] create member form -->
        <div class="card esi-card mt-4">
            <div class="card-header esi-card-header">Update Tutor Information</div>
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
                                        <div id="image_demo">
                                            test
                                        </div>
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

                <form method="POST" action="{{ route('admin.tutor.update', $tutor->user_id) }}">
                    @csrf
                    @method('put')

                    <div class="form-group row">
                        <label for="email" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('E-Mail') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">

                            <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email', isset($tutor->user->email ) ? $tutor->user->email : '') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="Sort" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Sort') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input id="sort" type="number" class="form-control form-control-sm @error('sort') is-invalid @enderror" name="sort" value="{{ old('sort', isset($tutor->sort) ? $tutor->sort : '') }}" required autocomplete="sort">

                            @error('sort')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="salary_rate" class="col-md-2 pr-0 col-form-label ">
                            <!--<span class="text-danger">* </span>-->
                            {{ __('Salary Rate') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input id="salary_rate" type="number" class="form-control form-control-sm @error('salary_rate') 
                            is-invalid @enderror" name="salary_rate" value="{{ old('salary_rate', isset($tutor->salary_rate ) ? $tutor->salary_rate : '') }}" required autocomplete="salary_rate">

                            @error('salary_rate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="grade" class="col-md-2 pr-0 col-form-label ">
                            <span class="text-danger">* </span>
                            {{ __('Grade') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                        
                            @foreach($grades as $grade)
                            <input type="radio" name="grade" required value="{{ strtoupper($grade['name']) }}" @if( old('grade')===$grade['name'] || strtolower($grade['name']) === strtolower($tutor->grade) )? {{ "checked" }} @endif> {{ $grade['name'] }}
                            @endforeach
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="skype_name" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Skype Name') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input id="skype_name" type="skype_name" class="form-control form-control-sm @error('skype_name') is-invalid @enderror" name="skype_name" value="{{ old('skype_name', isset($tutor->skype_name ) ? $tutor->skype_name : '') }}" required autocomplete="skype_name">

                            @error('skype_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="skype_id" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Skype ID') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input id="skype_id" type="skype_id" required class="form-control form-control-sm @error('skype_id') is-invalid @enderror" name="skype_id" value="{{ old('skype_id', isset($tutor->skype_id ) ? $tutor->skype_id : '') }}" required autocomplete="skype_id">

                            @error('skype_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="name_en" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Name (English)') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input id="name_en" type="name_en" class="form-control form-control-sm @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en', isset($tutor->user->firstname ) ? $tutor->user->firstname : '') }}" required autocomplete="name_en">

                            @error('name_en')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="name_jp" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Name (Japanese)') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input id="name_jp" type="name_jp" class="form-control form-control-sm @error('name_jp') is-invalid @enderror" name="name_jp" value="{{ old('name_jp', isset($tutor->user->japanese_firstname ) ? $tutor->user->japanese_firstname : '') }}" required autocomplete="name_jp">

                            @error('name_jp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gender" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Gender') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input type="radio" name="gender" value="MALE" checked="" class="@error('gender') is-invalid @enderror" name="gender" value="{{ old('gender') }}" required autocomplete="gender"> 男 (Male)
                            <input type="radio" name="gender" value="FEMALE" class="@error('gender') is-invalid @enderror" name="gender" value="{{ old('gender', isset($tutor->gender ) ? $tutor->gender : '') }}" required autocomplete="gender"> 女 (Female)
                            @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="hobby" class="col-md-2 pr-0 col-form-label ">{{ __('Hobby') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input type="text" name="hobby" class="form-control form-control-sm @error('hobby') is-invalid @enderror" value="{{ old('hobby', isset($tutor->hobby ) ? $tutor->hobby : '') }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="birthdate" class="col-md-2 pr-0 col-form-label ">
                            {{ __('Birthday') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input type="date" name="birthdate" class="datepicker form-control form-control-sm @error('birthdate') is-invalid @enderror" value="{{ old('birthdate', isset($tutor->birthday ) ? $tutor->birthday : '') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="major_in" class="col-md-2 pr-0 col-form-label ">{{ __('Major in') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input type="text" name="major_in" class="form-control form-control-sm" value="{{ old('major_in', isset($tutor->major ) ? $tutor->major : '') }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="introduction" class="col-md-2 pr-0 col-form-label ">{{ __('Introduction By Host') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <textarea name="introduction" class="form-control">{{ old('major', isset($tutor->introduction ) ? $tutor->introduction : '') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="japanese_fluency" class="col-md-2 pr-0 col-form-label ">
                            <span class="text-danger">* </span>
                            {{ __('Japanese') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">

                            <select name="japanese_fluency" class="form-control form-control-sm @error('japanese_fluency') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="FLUENTLY" @if(old('japanese_fluency')=='FLUENTLY' || $tutor->fluency =='FLUENTLY' ) {{"selected"}} @endif>流暢に話す (Fluently)</option>
                                <option value="DAILY_CONVERSATION" @if(old('japanese_fluency')=='DAILY_CONVERSATION' || $tutor->fluency =='DAILY_CONVERSATION') {{"selected"}} @endif>日常会話程度 (Daily Conversation)</option>
                                <option value="LITTLE" @if(old('japanese_fluency')=='LITTLE' || $tutor->fluency =='FLUENTLY') {{"LITTLE"}} @endif>少し話せる (Little)</option>
                                <option value="CANT_SPEAK" @if(old('japanese_fluency')=='CANT_SPEAK' || $tutor->fluency =='CANT_SPEAK') {{"selected"}} @endif>話せない (Can't Speak)</option>
                            </select>

                            @error('japanese_fluency')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="shift" class="col-md-2 pr-0 col-form-label ">
                            <span class="text-danger">* </span>
                            {{ __('Shift') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <select name="shift" class="form-control form-control-sm @error('shift') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                @foreach ($shifts as $shift)
                                <option value="{{$shift->id}}" @if(old('shift')==$shift->id || $tutor->lesson_shift_id == $shift->id) {{"selected"}} @endif>{{$shift->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('shift')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="form-group row">
                        <label for="is_default_main_tutor" class="col-md-2 pr-0 col-form-label ">{{ __('Default Main Tutor') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input type="checkbox" name="is_default_main_tutor" value="true" @if( old('is_default_main_tutor') || $tutor->is_default_main_tutor ) {{ "checked" }} @endif class="@error('is_default_main_tutor') is-invalid @enderror">
                            @error('is_default_main_tutor')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="is_terminated" class="col-md-2 pr-0 col-form-label "> {{ __('Is Terminated') }}
                            <div class="float-right">:</div>
                        </label>
                        <div class="col-md-3">
                            <input type="checkbox" name="is_terminated" value="true" @if( old('is_terminated') || $tutor->is_terminated ) {{ "checked" }} @endif class="@error('is_terminated') is-invalid @enderror">
                            @error('is_terminated')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>



                    <div class="row py-4">
                        <div class="col-2"></div>
                        <div class="col-3 text-left">
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            <input type="reset" value="Cancel" class="btn btn-primary btn-sm">
                        </div>
                    </div>

                </form>

            </div>
        </div>
        <!--[emd] member form-->
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
    (function($) {
        "use strict";

        $(document).ready(function() {

            var $image_crop = $('#image_demo').croppie({
                enableExif: true, viewport: {
                    width: 200, height: 200,
                    type: 'square' //circle
                }, boundary: {
                    width: 300, height: 300
                }
            });

            $('#upload_image').on('change', function() {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function() {
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
                $('#uploadimageModal').modal('show');
            }); //[end] #upload_image


            $('.crop_image').click(function(event) {
                $image_crop.croppie('result', {
                    type: 'canvas'
                    , size: 'viewport'
                }).then(function(response) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        url: "{{ url('admin/image-upload') }}",
                        type: "POST",
                        data: {
                            _token: "{{csrf_token()}}",
                            "id": "{{ $tutor->user_id }}",
                            "user_type": "tutors", //plural
                            "image": response
                        }, success: function(data) {
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
