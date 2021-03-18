@extends('layouts.esi-app-single')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="login-box mb-3" style="border: 2px solid #cbe5ee;padding: 10px;margin: 20px 0 0 0;">
                    <!--<h2 class="heading">{{ __('Reset Password') }}</h2>-->
                    <div class="row">
                        <div class="col-md-12  ml-2 px-5 py-4">

                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <strong>英会話教室</strong><br>
                            会員パスワード再設定 <br><br>

                            パスワードを再設定しますので、マイチューターに登録してあるPCメールアドレスを入力して下さい。　入力されたメールアドレス宛てにマイチューターからメールが送信されます。 <br><br>
                            送信されたメールでご案内するURLをクリックするとパスワード再設定画面が表示されますので、そこで新しいパスワードをご入力ください。<br> <br>



                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-2 col-form-label text-md-left pr-0 mr-0">{{ __('メールアドレス') }} :</label>

                                    <div class="col-md-3 pl-0">
                                        <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-sm btn-pink px-4">
                                            {{ __('メールを送信') }}
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
@endsection

@section('contentold')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

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
