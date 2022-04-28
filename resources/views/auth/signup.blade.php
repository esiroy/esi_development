@extends('layouts.esi-public')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card">

            
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @elseif (session('error_message'))
                    <div class="alert alert-danger">
                        {{ session('error_message') }}
                    </div>
                @endif

                <div class="text-center">
                    <a href="{{ url('/') }}">
                    <img title="My Tutor" alt="My Tutor" src="images/title_full.png" style="vertical-align: middle;margin-top: 27px; margin-right: 11px;height: 64px; "></a> &nbsp; 
                    <span style="font-weight: bolder;position: relative;top: 20px;">無料会員登録</span> 
                </div>

                <hr style="background-color: rgb(0,175,239);height: 3px;border: none;">


                <!--<div class="card-header">{{ __('signup') }}</div>-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">  
                            <div id="steps_nav">
                                <div class="arrow-active">STEP 1 <br> 登録情報のご入力</div>
                                <div class="arrow">STEP 2 <br> 内容のご確認</div>						
                                <div class="arrow">STEP 3 <br> メールのご確認</div>						
                                <div class="arrow">STEP 4 <br> 登録完了</div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6 text-center">
                            <div class="mt-3 font-weight-bold">
                                <div>確認メールが届かない場合でも、12時間以内に会員登録は完了します。</div>
                                <div>無料会員にご登録後、無料体験レッスンをご受講いただけます。</div>
                                <div>ご登録内容をご入力後、確認ボタンをクリックしてください。</div>
                                無料体験は2回受講できます
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-8">
                            全てご記入ください
                        </div>
                    </div>

                    <form method="POST" action="{{ route('validateSignUp') .'?a8='. app('request')->input('a8') }}">
                        @csrf

                        <div class="form-group row mb-0">

                            <label for="email" class="col-md-4 col-form-label text-md-right       py-4 offset-md-1 border-top border-left">
                                <span class="font-weight-bold">{{ __('メールアドレス') }}</span>
                            </label>

                            <div class="col-md-6 border-left border-top border-right py-4">

                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                       

                        <div class="form-group row mt-0 mb-0">
                            <label for="password" class="col-md-4 col-form-label text-md-right    mt-0 pt-0 py-4 offset-md-1 border-top border-left">
                                <span class="font-weight-bold">{{ __('パスワード') }}</span>
                            </label>

                            <div class="col-md-6  border-left border-top border-right py-4">
                                <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                半角英数字のみ有効 4文字以上32文字以内

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-0 mb-0">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left border-bottom">
                                <span class="font-weight-bold">{{ __('パスワードを認証する') }}</span>
                            </label>
                            <div class="col-md-6  border-left border-top border-right border-bottom py-4">
                                <input name="confirm_password" type="password" class="form-control form-control-sm"  required autocomplete="new-password">
                            </div>
                        </div>                        

                        <div class="form-group row mt-0 mb-0">
                            <label for="last_name_jp" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left">                            
                                <span class="font-weight-bold">お名前(漢字)</span>
                            </label>
                            <div class="col-md-6  border-left border-top border-right py-4">
                                <div class="row">
                                    <label class="col-md-1 mr-0 pr-0">(姓)</label>
                                    <div class="col-md-5 ml-0 pl-0">                                       
                                        <input id="last_name_jp" type="text" class="form-control form-control-sm @error('last_name_jp') is-invalid @enderror" name="last_name_jp" value="{{ old('last_name_jp') }}" required autocomplete="last_name_jp" autofocus>
                                        @error('last_name_jp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                     <label class="col-md-1 mr-0 pr-0">(名)</label>
                                    <div class="col-md-5 ml-0 pl-0">                                      
                                        <input id="first_name_jp" type="text" class="form-control form-control-sm @error('first_name_jp') is-invalid @enderror" name="first_name_jp" value="{{ old('first_name_jp') }}" required autocomplete="first_name_jp" autofocus>
                                        @error('first_name_jp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="form-group row mt-0 mb-0">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left">                            
                                <div class="font-weight-bold">お名前</div>
                                <div class="font-weight-bold">(アルファベット)</div>
                            </label>
                            <div class="col-md-6  border-left border-top border-right py-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-center">{{ __('Last Name') }} (姓)</div>
                                        <input id="last_name" type="text" class="form-control form-control-sm @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center">{{ __('First Name') }} (名)</div>
                                        <input id="first_name" type="text" class="form-control form-control-sm @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!--
                        <div class="form-group row mt-0 mb-0">
                            <label for="username" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left">
                                <span class="font-weight-bold">{{ __('Username') }}</span>
                            </label>

                            <div class="col-md-6  border-left border-top border-right py-4">
                                <input id="username" type="username" class="form-control form-control-sm  @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="email">

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        -->


                        <div class="form-group row mt-0 mb-0">
                            <label for="nickname" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left">
                                <span class="font-weight-bold">
                                    ニックネーム<br>
                                    (アルファベット)                                    
                                </span>
                            </label>

                            <div class="col-md-6 border-left border-top border-right py-4">
                                <input id="nickname" type="nickname" class="col-md-8 form-control form-control-sm @error('nickname') is-invalid @enderror" name="nickname" value="{{ old('nickname') }}" required autocomplete="email">

                                @error('nickname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

           

                        <div class="form-group row mt-0 mb-0">                        
                            <label for="communication_app" class="col-md-4 col-form-label text-md-right mt-0 pt-5 py-4 offset-md-1 border-top border-left">
                                <span class="font-weight-bold">
                                    Skype または　ZOOM どちらか一つを選び、登録してください<br>
                                    <a target="_blank" style="color:#0000EE;text-decoration: underline;" href="https://www.mytutor-jpn.com/info/2019/0327165712.html">＊登録したIDはマイページからご確認いただけます。</a>                                  
                                </span>
                            </label>

                            <div class="col-md-6 border-left border-top border-right py-4">
                                <table width="100%">
                                    <tbody>
                                    <tr>
                                        <td style="width:100px;text-align:center"><img style="width:100px;text-align:center" title="skype" src="images/skype.jpg"><h5>Skype</h5></td>
                                        <td style="width:100px;text-align:center"><img style="width:100px;text-align:center" title="zoom" src="images/zoom_logo.jpg"><h5>Zoom</h5></td>
                                    </tr>
                                    <tr>
                                        <td style="width:100px;text-align:center">
                                            <input checked="checked" type="radio" name="commApp" value="skype" id="skypeBtn" onclick="chooseApp('skype_image.jpg','skype','Skype','名');">
                                        </td>
                                        <td style="width:100px;text-align:center">
                                            <input type="radio" name="commApp" id="zoomBtn" value="zoom" onclick="chooseApp('zoom.jpg','zoom','Zoom','メールアドレス');">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>


                                @error('communication_app')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mt-0 mb-0 ">
                            <label for="communication_app_username" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left border-bottom ">
                                <span id="commAppText" class="font-weight-bold"></span>
                            </label>

                            <div class="col-md-6  border-left border-top border-right py-4 border-bottom">
                                <input id="communication_app_username" type="text" name="communication_app_username"
                                    class="col-md-8 form-control form-control-sm @error('communication_app_username') is-invalid @enderror"  
                                    value="{{ old('communication_app_username') }}" required autocomplete="communication_app_username">

                                <div id="commAppDescription"></div>                               

                                <img id="appImg" title="skype" src="images/skype_image.jpg">                                

                                @error('communication_app_username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @php /*
                        <div class="form-group row mt-0 mb-0">

                            <label for="last_name" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left border-bottom">
                                <div class="font-weight-bold">Purpose (Max 3 Purpose)</div>
                                <div class="font-weight-bold">(受講目的）</div>
                            </label>
                             <div class="col-md-6 border-left border-top border-right border-bottom py-4">
                                @include('modules/member/includes/memberPurpose') 
                            </div>                        
                        </div>*/
                        @endphp



                        <div class="form-group row mb-2 mt-2">
                            <div class="col-md-12 text-center small py-3">
                                <input type="checkbox" id="agree" name="agree" required must_be_checked="true" not_checked_message="利用規約とプライバシーポリシーに同意されていません" class="mr-3">
                                私は<span style="color: rgb(0,176,240);"></span>
                                <a href='https://www.mytutor-jpn.com/policy.html' target="_blank">mytutor 利用規約 と プラ イバシ ー ポリ シ ー</a> を読み同意しました。
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    確認 
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


@section('scripts')
@parent
<script type="text/javascript">    
    window.addEventListener('load', function () {
         chooseApp('skype_image.jpg','skype','Skype','名');
    });        

   

    function chooseApp(fileName,nameValue,commAppName,additionalText){

        //$("#communication_app_username").attr('name', '' + nameValue);

        $('#commAppText').text(''+commAppName+additionalText);

        var appImage = document.getElementById('appImg');	
        
        if(commAppName ==='Zoom'){
            $('#commAppDescription').html('ZOOM登録時に使用したメールアドレスを入力してください。<br>ZOOMにサインインする時に入力するメールアドレスです。<br><a style="color:#0000EE;text-decoration: underline;" target="_blank" href="https://www.mytutor-jpn.com/info/2019/0218124810.html"><b>*</b>ZOOM無料アカウントを取得する</a><br><br>');		 	
            appImage.style.visibility = 'hidden';
        }else{
            $('#commAppDescription').html('半角英数字有効 <br><br>下記画像を参考にSkype名を入力してください。 <br>Skype表示名を入力した場合トレーナーからのコールが届きません。<br>');
            appImage.style.visibility = 'visible';	 	
        } 
    }        

    
</script>
@endsection
