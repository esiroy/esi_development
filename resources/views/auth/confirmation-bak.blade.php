@extends('layouts.esi-public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 py-2">

            <div class="card">

                <div class="text-center">
                    <a href="{{ url('/') }}">
                    <img title="My Tutor" alt="My Tutor" src="images/title_full.png" style="vertical-align: middle;margin-top: 27px; margin-right: 11px;height: 64px; "></a> &nbsp; 
                    <span style="font-weight: bolder;position: relative;top: 20px;">無料会員登録</span> 
                </div>

                <hr style="background-color: rgb(0,175,239);height: 3px;border: none;">

                <div class="row mb-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">  
                        <div id="steps_nav">
                            <div class="arrow">STEP 1 <br> 登録情報のご入力</div>
                            <div class="arrow-active">STEP 2 <br> 内容のご確認</div>						
                            <div class="arrow">STEP 3 <br> メールのご確認</div>						
                            <div class="arrow">STEP 4 <br> 登録完了</div>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>




            
                <form method="POST" action="{{ route('createMember') }}">
                    @csrf
                    <div class="row m-2 blueSolidBorderBox">
                            <table border="0" cellspacing="9" cellpadding="0" align="center" class="tblRegister" width="100%">
                                
                            <tbody><tr>
                                <th colspan="13">Personal Information</th>
                            </tr>
                        
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">氏名　(漢字)<em>(Japanese)</em></td>
                                <td>:</td>
                                <td colspan="5" style="width: 20%;">姓
                                    test
                                    <input type="hidden" name="member.japaneseLastname" value="test">
                                    &nbsp;&nbsp;
                                    名 test
                                    <input type="hidden" name="member.japaneseFirstname" value="test">
                                </td>
                            </tr>
                            
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">氏名　(英字)<em>(English)</em></td>
                                <td>:</td>
                                <td colspan="5">姓
                                    dddd
                                    <input type="hidden" name="member.lastname" value="dddd">
                                    &nbsp;&nbsp;
                                    名
                                    zzzzzzz
                                    <input type="hidden" name="member.firstname" value="zzzzzzz">
                                </td>
                            </tr>
                            
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">ニックネーム <em>(Nickname)</em></td>
                                <td>:</td>
                                <td colspan="9">
                                    roy zzzz
                                    <input type="hidden" name="member.nickname" value="roy zzzz">	
                                </td>	        
                            </tr>
                        
                            
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">スカイプ名(Skype)</td>
                                <td>:</td>
                                <td colspan="3">
                                    test
                                    <input type="hidden" name="member.skypeAccount" value="test">
                                    <input type="hidden" name="member.communicationApp" value="Skype">
                                </td>
                            </tr>
                            
                        
                        
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">メールアドレス <em>(Email)</em></td>
                                <td>:</td>
                                <td colspan="3">
                                     {{ old('email') }}
                                     <input type="hidden" name="email" value="{{ old('email') }}">
                                     <input type="hidden" name="password" value="{{ old('password') }}">
                                </td>
                            </tr>
                            
                            <tr></tr>
                            
                            <tr>
                                <td colspan="12" align="right">
                                    <input type="submit" value="内容確認しました" style="background-image: url(images/top_bg.jpg);padding: 3px 30px;cursor: pointer;color: white;border-radius: 10px;">
                                </td>
                            </tr>
                                
                        </tbody>
                        </table>


                    </div>


                    <div class="card mx-3" style="display:none">
                        <div class="card-header">{{ __('Personal Information') }}</div>
                        <div class="card-body">
                                <div class="form-group row mb-0 mt-4">
                                    <label for="email" class="col-md-4 col-form-label text-md-right       py-4 offset-md-1 border-top border-left">
                                        <span class="font-weight-bold">{{ __('E-Mail Address') }}</span>
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
                                        <span class="font-weight-bold">{{ __('Password') }}</span>
                                    </label>

                                    <div class="col-md-6  border-left border-top border-right py-4">
                                        <input id="password" type="password" 
                                            class="form-control form-control-sm @error('password') is-invalid @enderror" 
                                            name="password" required autocomplete="new-password"
                                            value="{{ old('password') }}"
                                            >

                                        ※半角英数字のみ有効 ※4文字以上32文字以内

                
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

                                            </div>
                                        </div>
                                    </div>
                                </div>



                            


                                <div class="form-group row mt-0 mb-0">
                                    <label for="nickname" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left">
                                        <span class="font-weight-bold">
                                            ニックネーム<br>
                                            (半角英数10文字以内)                                    
                                        </span>
                                    </label>

                                    <div class="col-md-6 border-left border-top border-right py-4">
                                        <input id="nickname" type="nickname" class="col-md-8 form-control form-control-sm @error('nickname') is-invalid @enderror" name="nickname" value="{{ old('nickname') }}" required autocomplete="email">

                                    
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
                                    <input type="text" name="commApp" value="{{ old('commApp') }}">
                                    </div>
                                </div>


                                <div class="form-group row mt-0 mb-0 ">
                                    <label for="communication_app_username" class="col-md-4 col-form-label text-md-right mt-0 pt-0 py-4 offset-md-1 border-top border-left border-bottom">
                                        <span id="commAppText" class="font-weight-bold"></span>
                                    </label>


                                    <div class="col-md-6 border-left border-top border-right border-bottom py-4">
                                        
                                        <input  type="text" value="{{ old('communication_app_username') }}">

                                    
                                    </div>
                                </div>
                        </div>
                    </div>

                 

                </form>


            </div>

        </div>
    </div>
</div>
@endsection
