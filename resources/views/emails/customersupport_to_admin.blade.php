
{{ date('M d, Y')}} 
<Br/><Br/>


お名前 （必須）: {{ $member->user->firstname }}  {{ $member->user->lastname }}
<Br/><Br/>


フリガナ: {{ $member['nickname'] }}
<Br/><Br/>

 
ご登録メールアドレス （必須）: {{ $member->user->email }} 
<Br/><Br/>


お問い合わせ内容 （必須）: {{ $data['inquiry']}} 
<Br/><Br/>




[this message is read in html format ]