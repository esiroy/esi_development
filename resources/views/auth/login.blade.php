@extends('layouts.public-login')

@section('content')

<div id="login" class="pt-4">
    <div class="mt-5">
        <div class="container">

            <div class="bg-member-login">
                <div class="row">
                    <div class="col-7 col-lg-7 col-md-6 col-sm-7 col-xs-6">
                        <div class="row">

                            <div class="offset-2 offset-lg-4 offset-md-2 offset-sm-4 offset-xs-2">

                                <div id="logo" class="mr-5">
                                    <img src="{{ url("images/mytutor_logo.png") }}" alt="my-tutor administratrion panel" class="img-fluid">
                                </div>

                                <div class="login-box">

                                    <h2 class="heading"> 会員ログイン</h2>

                                    <form method="POST" action="{{ route('login') .'?a8='. app('request')->input('a8') }}">
                                        @csrf

                                        <div class="form-group row mb-1 mt-1">
                                            <label for="username" class="col-4 col-form-label text-md-right pt-1">{{ __('メールアドレス	') }}</label>
                                            <div class="col-7 pl-0">
                                                <input id="username" type="text" class="form-control form-control-sm  @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                                @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-2 mt-2">
                                            <label for="password" class="col-4 col-form-label text-md-right">{{ __('パスワード') }}</label>
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
                                        </div>-->


                                        <div class="form-group row mb-0 mt-0">
                                            <div class="col-md-11 text-right text-md-right">
                                                <button type="submit" id="btn-login" class="btn btn-sm btn-success px-4">
                                                    {{ __('ログイン') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0 mt-0 mr-0 pr-0">
                                            <div class="col-md-12">
                                                @if (Route::has('password.request'))
                                                <a class="btn btn-link float-right" href="{{ route('password.request') .'?a8='. app('request')->input('a8')  }}">
                                                    {{ __('パスワードを忘れた方') }}
                                                </a>
                                                @endif
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>



                        </div>
                    </div>
                    <!--<div id="register" class="col-4 col-lg-5 col-md-6 col-sm-5 col-xs-6" >-->

                    <div id="register" class="col-5">
                        <!--[start] regsiter button -->
                        <div class="btn-register">
                            <a href="{{ url('signup') .'?a8='. app('request')->input('a8') }}"><img src="{{ url("images/btnRegister.png") }}" alt="my-tutor administratrion panel" class="img-fluid"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection



@section('old')
<div class="login">

    <div class="container bg-user-login mt-5 p-5">
        <div class="row">
            <div class="col-md-2"> &nbsp;

            </div>
            <div class="col-md-5 col-sm-2">
                <div class="login-form-heading text-left mt-4">
                    <img src="{{ url("images/mytutor_logo.png") }}" alt="my-tutor" alt="my-tutor administratrion panel" class="img-fluid">
                </div>
                <div class="login-box mt-3 p-3">
                    <h2 class="heading"> Member Login </h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row mb-1 mt-4">
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

            <div class="col-md-5 col-sm-1">
                test
            </div>

        </div>

    </div>

</div>

@endsection
