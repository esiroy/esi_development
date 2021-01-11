@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Agent</li>
            </ol>
        </nav>

        <div class="container">

            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
            @endif


            <div class="card esi-card mt-4">
                <div class="card-header esi-card-header">Update Password</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.agent.resetPassword', $agent->user_id) }}">
                        @csrf
                        <div class="row pt-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 small pr-0">
                                        <label for="password" class="px-0 col-md-12 col-form-label"><span class="text-danger">*</span> Password<div class="float-right">:</div></label>
                                    </div>
                                    <div class="col-6">
                                        <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
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

            <div class="card esi-card mt-4">
                <div class="card-header esi-card-header">Agent Form</div>
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

                    
                    <form method="POST" action="{{ route('admin.agent.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> 業種
                            </div>
                            <div class="col-3">
                                <select name="industry_type" class="form-control form-control-sm @error('industry_type') is-invalid @enderror" value="{{ old('industry_type') }}" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach ($industries as $industry)
                                    <option value="{{$industry['value']}}" @if ( old('industry_type')==$industry['value'] || $agent->industry_type ) {{ 'selected' }} @endif >{{$industry['name'] }}</option>
                                    @endforeach;
                                </select>
                                @error('industry_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> Email
                            </div>
                            <div class="col-3">
                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email', isset($agent->user->email ) ? $agent->user->email : '') }}" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> Name (English)
                            </div>
                            <div class="col-3">
                                <input id="name_en" type="name_en" class="form-control form-control-sm @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en') }}" required autocomplete="name_en">
                                @error('name_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> Name (Japanese)
                            </div>
                            <div class="col-3">
                                <input id="name_jp" type="name_jp" class="form-control form-control-sm @error('name_jp') is-invalid @enderror" name="name_jp" value="{{ old('name_jp') }}" required autocomplete="name_jp">
                                @error('name_jp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> ID
                            </div>
                            <div class="col-3">
                                <input id="id" type="id" class="form-control form-control-sm @error('id') is-invalid @enderror" name="id" value="{{ old('id') }}" required autocomplete="id">
                                @error('id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span> 担当者
                            </div>
                            <div class="col-3">
                                <input id="representative" type="representative" class="form-control form-control-sm @error('representative') is-invalid @enderror" name="representative" value="{{ old('representative') }}" required autocomplete="representative">
                                @error('representative')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                ひらがな
                            </div>
                            <div class="col-3">
                                <input type="text" name="hiragana" class="form-control form-control-sm" placeholder="ひらがな Agent Hiragana" value="{{ old('hiragana') }}">
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                Address
                            </div>
                            <div class="col-3">
                                <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                ふりがな
                            </div>
                            <div class="col-3">
                                <input type="text" name="inclination" class="form-control form-control-sm" placeholder="ふりがな Agent Inclination" value="{{ old('inclination') }}">
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                <span class="text-danger">*</span>
                                ポイント購入日
                            </div>
                            <div class="col-3">
                                <input type="date" name="contract_date" class="datepicker form-control form-control-sm @error('contract_date') is-invalid @enderror" required value="{{ old('contract_date') }}">
                                @error('contract_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row pt-2">
                            <div class="col-2 small">
                                備考
                            </div>
                            <div class="col-3">
                                <textarea name="remark" class="form-control" placeholder="備考 Agent Remark">{{ old('remark') }}</textarea>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-2"></div>
                            <div class="col-3 text-left">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                <button type="clear" class="btn btn-primary btn-sm">Cancel</button>
                            </div>
                        </div>

                    </form>
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
                            "id": "{{ $agent->user_id }}",
                            "user_type": "agents", //plural
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