@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/lesson') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tutor</li>
            </ol>
        </nav>

        <div class="container">

            <!--
            @if($errors->any())
            <h4>{{$errors->first()}}</h4>
            @endif
            -->

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @elseif (session('error_message'))
                <div class="alert alert-danger">
                    {{ session('error_message') }}
                </div>
            @endif
            
            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Tutor List
                </div>
                <div class="card-body">

                
                    <div class="row">
                        <form class="form-inline" style="width:100%">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Nickname:</label>
                                    <input id="nickname" name="nickname" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Name:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="small col-2">Email:</label>
                                    <input id="email" name="name" type="text" class="form-control form-control-sm col-4" value="">

                                    <select id="filterLessonShift" name="filterLessonShift" class="form-control form-control-sm col-3 ml-1">
                                        <option value="">-- Select --</option>
                                        <option value="4">25 mins</option>
                                        <option value="5">40 mins</option>
                                    </select>
                                    <button type="button" class="btn btn-primary btn-sm col-1 ml-1">Go</button>

                                </div>
                            </div>
                        </form>
                    </div>

                    <!--
                    <div class="row">
                        <div class="col-12 pt-3">
                            <button type="button" class="btn btn-primary btn-sm">Generate Tutor List</button>
                        </div>
                    </div>
                    -->

                    <div class="row">
                        <div class="col-12 pt-3">

                            <!--
                            <tutor-list-component
                                :tutors="{{ json_encode($tutors) }}"
                            />
                            -->

                        <div class="table-responsive">
                            <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="small text-center">Sort</th>
                                    <th class="small text-center">ID</th>
                                    <th class="small text-center">Name</th>
                                    <th class="small text-center">Nickname</th>
                                    <th class="small text-center">Email</th>                                    
                                    <th class="small text-center">Member (Main)</th>
                                    <th class="small text-center">Member (Support)</th> 
                                    <th class="small text-center">Action</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($tutors))
                                @foreach ($tutors as $tutor)
                                <tr>
                                    <td class="small text-center">{{ $tutor->tutorInfo->sort}}</td>
                                    <td class="small text-center">{{ $tutor->tutorInfo->user_id}}</td>
                                    <td class="small text-center">{{$tutor->tutorInfo->name_en}}</td>
                                    <td class="small text-center">{{$tutor->username}}</td>
                                    <th class="small text-center">{{$tutor->email}}</th>
                                    <td class="small text-center"><a href="{{ url('admin/maintutor') }}"><img src="/images/iMemberMain.gif"></a></td>
                                    <td class="small text-center"><a href="{{ url('admin/supporttutor') }}"><img src="/images/iMemberSupport.gif"></a></td>                
                                    <td class="small text-center">Edit  | Delete</td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
            <!--[end] tutor list card-->

            <div class="card mt-4">
                <div class="card-header">Member Form</div>
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.tutor.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('E-Mail') }}
                                <div class="float-right">:</div>
                            </label>
                            <div class="col-md-3">
                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
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

                        <div class="form-group row">
                            <label for="Sort" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Sort') }}
                                <div class="float-right">:</div>
                            </label>
                            <div class="col-md-3">
                                <input id="sort" type="sort" class="form-control form-control-sm @error('sort') is-invalid @enderror" name="sort" value="{{ old('sort') }}" required autocomplete="sort">
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
                                <input id="salary_rate" type="salary_rate" class="form-control form-control-sm @error('salary_rate') is-invalid @enderror" name="salary_rate" value="{{ old('salary_rate') }}" required autocomplete="salary_rate">
                                @error('salary_rate')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="grade" class="col-md-2 pr-0 col-form-label ">{{ __('Grade') }}
                                <div class="float-right">:</div>
                            </label>
                            <div class="col-md-3">
                                @foreach($grades as $grade)
                                    <input type="radio" name="grade" value="{{ $grade->id }}" @if(old('grade') == $grade->id)? {{ "checked" }} @endif>  {{$grade->name}} 
                                @endforeach
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="skype_name" class="col-md-2 pr-0 col-form-label "><span class="text-danger">* </span>{{ __('Skype Name') }}
                                <div class="float-right">:</div>
                            </label>
                            <div class="col-md-3">
                                <input id="skype_name" type="skype_name" class="form-control form-control-sm @error('skype_name') is-invalid @enderror" name="skype_name" value="{{ old('skype_name') }}" required autocomplete="skype_name">
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
                                <input id="skype_id" type="skype_id" required class="form-control form-control-sm @error('skype_id') is-invalid @enderror" name="skype_id" value="{{ old('skype_id') }}" required autocomplete="skype_id">
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
                                <input id="name_en" type="name_en" class="form-control form-control-sm @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en') }}" required autocomplete="name_en">
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
                                <input id="name_jp" type="name_jp" class="form-control form-control-sm @error('name_jp') is-invalid @enderror" name="name_jp" value="{{ old('name_jp') }}" required autocomplete="name_jp">
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
                                <input type="radio" name="gender" value="FEMALE" class="@error('gender') is-invalid @enderror" name="gender" value="{{ old('gender') }}" required autocomplete="gender"> 女 (Female)
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
                                <input type="text" name="hobby" class="form-control form-control-sm @error('hobby') is-invalid @enderror"  value="{{ old('hobby') }}">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="birthdate" class="col-md-2 pr-0 col-form-label ">
                                {{ __('Birthday') }}
                                <div class="float-right">:</div>
                            </label>
                            <div class="col-md-3">
                                <input type="date" name="birthdate" class="datepicker form-control form-control-sm @error('birthdate') is-invalid @enderror"  value="{{ old('birthdate') }}">
                            </div>
                        </div>

                        <div class="form-group row">                            
                            <label for="major" class="col-md-2 pr-0 col-form-label ">{{ __('Major in') }}
                                <div class="float-right">:</div>
                            </label>                            
                             <div class="col-md-3">                               
                                <input type="text" name="major" class="form-control form-control-sm">
                            </div>
                        </div>


                        <div class="form-group row">                            
                            <label for="introduction" class="col-md-2 pr-0 col-form-label ">{{ __('Introduction By Host') }}
                                <div class="float-right">:</div>
                            </label>                            
                             <div class="col-md-3">
                               <textarea name="introduction" class="form-control"></textarea>
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
                                    <option value="FLUENTLY" @if(old('japanese_fluency') == 'FLUENTLY')? {{"selected"}} @endif>流暢に話す (Fluently)</option>					
                                    <option value="DAILY_CONVERSATION" @if(old('japanese_fluency') == 'DAILY_CONVERSATION')? {{"selected"}} @endif>日常会話程度 (Daily Conversation)</option>					
                                    <option value="LITTLE" @if(old('japanese_fluency') == 'LITTLE')? {{"selected"}} @endif>少し話せる (Little)</option>					
                                    <option value="CANT_SPEAK" @if(old('japanese_fluency') == 'CANT_SPEAK')? {{"selected"}} @endif>話せない (Can't Speak)</option>					
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
                                    <option value="4" @if(old('shift') == 4)? {{"selected"}} @endif>25 mins</option>
                                    <option value="5" @if(old('shift') == 5)? {{"selected"}} @endif>40 mins</option>					
                                </select>
                            </div>
                            @error('shift')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror                            
                        </div>                        


                        <div class="form-group row">
                            <label for="default_main_tutor" class="col-md-2 pr-0 col-form-label ">{{ __('Default Main Tutor)') }}
                                <div class="float-right">:</div>
                            </label>
                            <div class="col-md-3">
                                <input type="checkbox" name="default_main_tutor" value="true" class="@error('default_main_tutor') is-invalid @enderror" >
                                @error('default_main_tutor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="is_terminated" class="col-md-2 pr-0 col-form-label "> {{ __('Is Terminated)') }}
                                <div class="float-right">:</div>
                            </label>
                            <div class="col-md-3">
                                <input type="checkbox" name="is_terminated" value="true" class="@error('is_terminated') is-invalid @enderror" >
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
                                <button type="clear" class="btn btn-primary btn-sm">Cancel</button>
                            </div>
                        </div>

                </div>
            </div>
            <!--[emd] member form-->


            </form>

        </div>
    </div>

</div>
@endsection
