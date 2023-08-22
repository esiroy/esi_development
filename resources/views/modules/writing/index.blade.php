@extends('layouts.esi-app')

@section('content')

@php
    $attribute = Auth::user()->memberInfo->attribute;
@endphp

<div class="container bg-light">
    <div class="esi-box mb-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Writing</li>
            </ol>
        </nav>

        <div class="container pb-5">
            <div class="row">
            
                <!--{{ "mail address" . Config::get('mail.from.address') }}-->

                <!--[start sidebar]-->
                @include('modules.member.sidebar.index')
                <!--[end sidebar]-->           

            

                @if (strtolower($attribute) == 'trial') 

                    <div class="col-md-9">
                        <div class="row">

   							@include('modules.member.includes.ecommerce.buycredits')

                        </div>
                    </div>

                @else 
                    <div class="col-md-9">
                        <div class="row">

                            <div class="col-12  message-container">
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
                            
                                    
                            <div class="col-12">
                                <div class="card esi-card mb-3">
                                    <div class="card-header esi-card-header py-2">
                                        Writing Service
                                    
                                        <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/tensaku.html', 'tensaku', 980, 720);" class="small ml-4">「添削くん」ご利用方法 </a>
                                    </div>
                                    <div class="card-body">
                                        
                                        <form id="writing-form-test" method="POST" enctype="multipart/form-data" 
                                            action="{{ route('writingSaveEntry.store', ['form_id' => $form_id  ]) }}" class="form-horizontal">
                                            @csrf
                                          
                                                            
                                            <label for="name">Name:</label>
                                            <input type="text" id="name" name="name" required><br><br>

                                            <label for="address">Address:</label>
                                            <input type="text" id="address" name="address" required><br><br>

                                            <label for="email">Email:</label>
                                            <input type="email" id="email" name="email"><br><br>

                                            <label for="phone">Phone Number:</label>
                                            <input type="tel" id="phone" name="phone"><br><br>

                                            <label for="birthdate">Date of Birth:</label>
                                            <input type="date" id="birthdate" name="birthdate"><br><br>

                                            <label for="gender">Gender:</label>
                                            <select id="gender" name="gender">
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select><br><br>

                                            <input type="submit" value="Submit">

                                        </form>                              
                                    </div>
                                </div>                  
                            </div>
                            
                        </div>
                    </div>
                @endif
            </div>
        </div>  


    </div>
</div>
@endsection

@section('styles')

@endsection
