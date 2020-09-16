@extends('layouts.adminpublic')

@section('content')
<div class="login">

    <div class="container bg-admin mt-0">

        <div class="row">
            <div class="col-7">

                <div class="text-left">
                    <img src="{{ url("images/mytutor_logo.png") }}" alt="my-tutor" alt="my-tutor administratrion panel">
                </div>

                <div class="mt-2">
                    <!--<div class="card-header">{{ __('Administrator Login') }}</div>-->

                    <div class=" login-box">
                        <h2 class="heading">
                            Manager / Tutor / Agent Login
                        </h2>
                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <div class="form-group row pt-2 mb-1">
                                <label for="email" class="col-4 col-form-label text-md-right pt-1">{{ __('Username') }}</label>
                                <div class="col-7 pl-0">
                                    <input id="email" type="email" class="form-control form-control-sm  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-0">
                                <label for="password" class="col-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                <div class="col-7 pl-0">
                                    <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!--
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            <small>{{ __('Remember Me') }}</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            -->

                            <div class="form-group row mb-0 mt-0">
                                <div class="col-md-11">
                                    <button type="submit" class="btn btn-success float-right px-5">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row mb-0 mt-0 mr-0 pr-0">
                                <div class="col-md-12">
                                    @if (Route::has('password.request'))
                                    <a class="btn btn-link float-right" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection