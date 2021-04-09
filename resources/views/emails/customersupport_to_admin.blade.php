{{ date('M d, Y')}} <br/><br/>
お名前 （必須）: {{ $member->user->firstname }}  {{ $member->user->lastname }}<br/><br/>
フリガナ: {{ $member['nickname'] }}<br/><br/> 
ご登録メールアドレス （必須）: {{ $member->user->email }} <br/><br/>
お問い合わせ内容 （必須）: {{ $data['inquiry']}} <br/><br/>