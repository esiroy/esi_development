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
                    <!-- A8 PARAMETER HERE -->
                    <input type="hidden" name="a8" value="{{ app('request')->input('a8') }}">

                    <div class="row m-2 blueSolidBorderBox">
                            <table border="0" cellspacing="9" cellpadding="0" align="center" class="tblRegister" width="100%">
                            <tbody>
                            <tr>
                                <th colspan="13">Personal Information</th>
                            </tr>
                        
                            <tr valign="top">
                                <td class="red">&nbsp;</td>                                
                                <td align="left" style="padding-left: 15px;">氏名　(漢字)<em>(Japanese)</em></td>
                                <td>:</td>
                                <td colspan="5" style="width: 70%;">
                                    姓  {{ old('last_name_jp') }}                                   
                                    <input type="hidden" name="last_name_jp" value="{{ old('last_name_jp') }}">
                                    &nbsp;&nbsp;
                                    名  {{ old('first_name_jp') }}
                                    <input type="hidden" name="first_name_jp" value="{{ old('first_name_jp') }}">
                                </td>
                            </tr>
                            
                          <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">氏名　(漢字)<em>(Japanese)</em></td>
                                <td>:</td>
                                <td colspan="5" style="width: 20%;">
                                    姓  {{ old('last_name') }}                                   
                                    <input type="hidden" name="last_name" value="{{ old('last_name') }}">
                                    &nbsp;&nbsp;
                                    名  {{ old('first_name') }}
                                    <input type="hidden" name="first_name" value="{{ old('first_name') }}">
                                </td>
                            </tr>
                            
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">ニックネーム <em>(Nickname)</em></td>
                                <td>:</td>
                                <td colspan="9">
                                    {{ old('nickname') }}
                                    <input type="hidden" name="nickname" value="{{ old('nickname') }}">	
                                </td>	        
                            </tr>
                        
                            
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">{{ old('commApp') }}</td>
                                <td>:</td>
                                <td colspan="3">
                                    {{ old('commApp') }} -  {{ old('communication_app_username') }}

                                    <input type="hidden" name="communication_app_username" value="{{ old('communication_app_username') }}">
                                    <input type="hidden" name="commApp" value="{{ old('commApp') }}">
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

                            @php /*
                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td align="left" style="padding-left: 15px;">Purpose (受講目的）</td>
                                <td>:</td>
                                <td colspan="3">

                                    <div class="IELTS">
                                        <div class="main_option">
                                            {{ old('IELTS') ?? ''  }}
                                            <input type="hidden" name="IELTS" value="{{ old('IELTS') }}">
                                        </div>
                                        @if (is_array(old('IELTS_option')))
                                            <div class="ml-4">
                                                @foreach(old('IELTS_option') as $IELTS_option_value )
                                                    <input type="hidden" name="IELTS_option[]" value="{{ $IELTS_option_value }}">
                                                    <div>{{ $IELTS_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="TOEFL">
                                        <div class="main_option">
                                            {{ old('TOEFL') ?? ''  }}
                                            <input type="hidden" name="TOEFL" value="{{ old('TOEFL') }}">
                                        </div>
                                        @if (is_array(old('TOEFL_option')))
                                            <div class="ml-4">
                                                @foreach(old('TOEFL_option') as $TOEFL_option_value )
                                                    <input type="hidden" name="TOEFL_option[]" value="{{ $TOEFL_option_value }}">
                                                    <div>{{ $TOEFL_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>


                                    <div class="TOEFL_Primary">
                                        <div class="main_option">
                                            {{ old('TOEFL_Primary') ?? ''  }}
                                             <input type="hidden" name="TOEFL_Primary" value="{{ old('TOEFL_Primary') }}">
                                        </div>
                                    </div>

                                    <div class="TOEIC">
                                        <div class="main_option">
                                            {{ old('TOEIC') ?? ''  }}
                                            <input type="hidden" name="TOEIC" value="{{ old('TOEIC') }}">
                                        </div>
                                        @if (is_array(old('TOEIC_option')))
                                            <div class="ml-4">
                                                @foreach(old('TOEIC_option') as $TOEIC_option_value )
                                                    <input type="hidden" name="TOEIC_option[]" value="{{ $TOEIC_option_value }}">   
                                                    <div>{{ $TOEIC_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="EIKEN">
                                        <div class="main_option">
                                            {{ old('EIKEN') ?? '' }}
                                            <input type="hidden" name="EIKEN" value="{{ old('EIKEN') }}">
                                        </div>
                                        @if (is_array(old('EIKEN_option')))
                                            <div class="ml-4">
                                                @foreach(old('EIKEN_option') as $EIKEN_option_value )
                                                    <input type="hidden" name="EIKEN_option[]" value="{{ $EIKEN_option_value }}">
                                                    <div>{{ $EIKEN_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>


                                    <div class="TEAP">
                                        <div class="main_option">
                                            {{ old('TEAP') ?? '' }}
                                            <input type="hidden" name="TEAP" value="{{ old('TEAP') }}">
                                        </div>
                                        @if (is_array(old('TEAP_option')))
                                            <div class="ml-4">
                                                @foreach(old('TEAP_option') as $TEAP_option_value )
                                                    <input type="hidden" name="TEAP_option[]" value="{{ $TEAP_option_value }}">
                                                    <div>{{ $TEAP_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>


                                    <div class="BUSINESS">
                                        <div class="main_option">
                                            {{ old('BUSINESS') ?? '' }}
                                             <input type="hidden" name="BUSINESS" value="{{ old('BUSINESS') }}">
                                        </div>
                                        @if (is_array(old('BUSINESS_option')))
                                            <div class="ml-4">
                                                @foreach(old('BUSINESS_option') as $BUSINESS_option_value )
                                                    <input type="hidden" name="BUSINESS_option[]" value="{{ $BUSINESS_option_value }}">
                                                    <div>{{ $BUSINESS_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="BUSINESS_CAREERS">
                                        <div class="main_option">
                                            {{ old('BUSINESS_CAREERS') ?? '' }}
                                            <input type="hidden" name="BUSINESS_CAREERS" value="{{ old('BUSINESS_CAREERS') }}">
                                        </div>
                                        </div>
                                        @if (is_array(old('BUSINESS_CAREERS_option')))
                                            <div class="ml-4">
                                                @foreach(old('BUSINESS_CAREERS_option') as $BUSINESS_CAREERS_option_value )
                                                    <input type="hidden" name="BUSINESS_CAREERS_option[]" value="{{ $BUSINESS_CAREERS_option_value }}">
                                                    <div>{{ $BUSINESS_CAREERS_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>


                                    <div class="DAILY_CONVERSATION">
                                        <div class="main_option">
                                            {{ old('DAILY_CONVERSATION') ?? '' }}
                                            <input type="hidden" name="DAILY_CONVERSATION" value="{{ old('DAILY_CONVERSATION') }}">
                                        </div>
                                        @if (is_array(old('DAILY_CONVERSATION_option')))
                                            <div class="ml-4">
                                                @foreach(old('DAILY_CONVERSATION_option') as $DAILY_CONVERSATION_option_value )
                                                    <input type="hidden" name="DAILY_CONVERSATION_option[]" value="{{ $DAILY_CONVERSATION_option_value }}">
                                                    <div>{{ $DAILY_CONVERSATION_option_value }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="OTHERS">
                                        <div class="main_option">
                                            {{ old('OTHERS') ?? '' }}
                                             <input type="hidden" name="OTHERS" value="{{ old('OTHERS') }}">
                                        </div>                                      
                                        <div class="ml-4">                                            
                                            <input type="hidden" name="OTHERS_value" value="{{ old('OTHERS_value') }}">
                                            {{ old('OTHERS_value') ?? '' }}
                                        </div>                                        
                                    </div>
                                </td>
                            </tr>                            
                            */@endphp



                            <tr>
                                <td colspan="10" align="right">
                                    <div class="pr-5 pt-3">
                                        <input type="submit" value="内容確認しました >>" class="btn btn btn-primary">
                                    </div>
                                </td>
                            </tr>



                                
                        </tbody>
                        </table>


                    </div>
                </form>


            </div>

        </div>
    </div>
</div>
@endsection
