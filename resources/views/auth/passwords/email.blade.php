@extends('layouts.adminpublic')

@section('content')

<div id="login" class="pt-4">
    <div class="mt-5">
        <div class="container">

            <div class="bg-admin-login">
                <div class="row">
                    <div class="col-8 col-lg-7 col-md-6 col-sm-6 col-xs-6">
                        <div class="row">
                            <div class="offset-2 offset-lg-4 offset-md-5 offset-sm-2 offset-xs-2">

                                <div id="logo" class="mr-5">
                                    <a href="{{ url('/') }}"><img src="{{ url("images/mytutor_logo.png") }}" alt="Login" class="img-fluid"></a>
                                </div>

                                @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                                @endif

                                <div class="login-box">
                                    <h2 class="heading">{{ __('Reset Password') }}</h2>
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <div class="form-group row">
                                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-5">
                                            <div class="col-md-7 offset-md-4">
                                                <button type="submit" class="btn btn-sm btn-success px-4">
                                                    {{ __('Send Password Reset Link') }}
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>


                            </div>



                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('contentold')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
