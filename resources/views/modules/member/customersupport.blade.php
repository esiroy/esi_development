@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Customer Support</li>
            </ol>
        </nav>

        <div class="container">

            <div class="row">
                <!--[start] sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>
                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div>
                <!--[end sidebar]-->

                <!--content-->
                <div class="col-md-9">

                    <!--[start] session message-->
                    @include('includes.session.message')
                    <!--[end] session message -->

                    <div id="member-lesson-schedules" class="card esi-card">
                        <div class="card-header esi-card-header">
                            マイチューター Customer Support
                        </div>

                        <div class="card-body">
                            <p>お問い合わせの前に <a href="https://www.mytutor-jpn.com/faq.html" style="color:red" target="_blank">よくあるご質問</a> のページをご確認ください。</p>

                            <div>
                                <b>お問い合わせフォーム</b>
                            </div>

                            <form action="{{ route("customersupport.store") }}"  method="POST" enctype="multipart/form-data" onsubmit="return validate(this)">
                                @csrf

                                     <table class="tblRegister" cellpadding="4" cellspacing="0" style="width: 600px; margin: 0px auto;">
                                    <tbody>
                                        <tr valign="top">
                                            <td>お名前 <em>（必須）</em></td>
                                            <td>:</td>
                                            <td><input required disabled type="text" name="japanese_name" id="name*" alt="お名前" value="{{ $member->user->japanese_lastname ?? '' }}, {{ $member->user->japanese_firstname ?? '' }}  " class="form-control form-control-sm"></td>
                                        </tr>
                                
                                       <tr valign="top">
                                            <td>Name</td>:
                                            <td>:</td>
                                            <td>
                                                <input required disabled type="text" name="name" id="name*" alt="お名前" value="{{ $member->user->lastname ?? '' }}, {{ $member->user->firstname ?? '' }}" class="form-control form-control-sm"></td>
                                        </tr>

                                        <tr valign="top">
                                            <td>ご登録メールアドレス <em>（必須）</em></td>
                                            <td>:</td>
                                            <td><input required disabled type="text" name="email" id="email" alt="ご登録メールアドレス " value="{{ $member->user->email ?? '' }}" class="form-control form-control-sm"></td>
                                        </tr>
                        
                                        <tr valign="top">
                                            <td>お問い合わせ内容 <em>（必須）</em></td>
                                            <td>:</td>
                                            <td><textarea required name="inquiry" id="inquiry*" alt="お問い合わせ内容" class="form-control form-control-sm"  rows="4"></textarea></td>
                                        </tr>

                                        <tr valign="top">
                                            <td>Attachment</td>
                                            <td>:</td>
                                            <td><input name="file_upload" type="file" id="fileItem" class="" style="" multiple=""></td>
                                        </tr>

                                        <tr valign="top">
                                            <td colspan="3" align="right">
                                                <input type="submit" value="送信" class="btn-pink">
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>


                                @php
                                /*
                                <table class="tblRegister" cellpadding="4" cellspacing="0" style="width: 600px; margin: 0px auto;">
                                    <tbody>
                                        <tr valign="top">
                                            <td>お名前 <em>（必須）<em></em></em></td>
                                            <td>:</td>
                                            <td><input required type="text" name="name" id="name*" alt="お名前" value="{{ $member->user->firstname ?? '' }} {{ $member->user->lastname ?? '' }} " class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr valign="top">
                                            <td>フリガナ</td>
                                            <td>:</td>
                                            <td><input required type="text" name="nickname" id="nickname*" alt="フリガナ" value="{{ $member->nickname ?? '' }}" class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr valign="top">
                                            <td>ご登録メールアドレス <em>（必須）</em></td>
                                            <td>:</td>
                                            <td><input required type="text" name="email" id="email" alt="ご登録メールアドレス " value="{{ $member->user->email ?? '' }}" class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr>
                                            <td>確認のためもう一度 <em>（必須）</em></td>
                                            <td>:</td>
                                            <td><input required type="text" id="confirmation" alt="確認のためもう一度" class="form-control form-control-sm"></td>
                                        </tr>
                                        <tr valign="top">
                                            <td>お問い合わせ内容 <em>（必須）</em></td>
                                            <td>:</td>
                                            <td><textarea required name="inquiry" id="inquiry*" alt="お問い合わせ内容" class="form-control form-control-sm"  rows="4"></textarea></td>
                                        </tr>

                                        <tr valign="top">
                                            <td>Attachment</td>
                                            <td>:</td>
                                            <td><input name="file_upload" type="file" id="fileItem" class="" style="" multiple=""></td>
                                        </tr>

                                        <tr valign="top">
                                            <td colspan="3" align="right">
                                                <input type="submit" value="送信" class="btn-pink">
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                                */
                                @endphp

                            </form>


                            <div class="mt-3">
                                <p>講師からコールがなかったり、レッスン中のトラブルなど、緊急な対応については、セブ・講師管理センター(セブマネジャー)がスカイプ<span style="color:red;">又は ZOOM</span> チャットで対応いたします。</p>                                                                
                                <p>マイページ左側「管理人」の中にある「スカイプ<span style="color:red;">(ZOOM)</span>のアイコン」をクリックするとセブマネジャーへつながります</p>
                            </div>

                        </div>
                    </div>


                </div>
            </div>


        </div>


    </div>
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
    function validate() {
        var email = document.getElementById("email").value;
        var confirm = document.getElementById("confirmation").value;
        if(email != confirm) {
            alert('Email Not Matching!');
            return false;
        } else {
            return true;
        }
    }
</script>
@endsection