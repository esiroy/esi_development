{{ date('M d, Y')}} <br/>
お名前 （必須）: {{ $member->user->firstname }}  {{ $member->user->lastname }}<br/>
Name: {{ $member->user->lastname ?? ''}}, {{ $member->user->firstname ?? '' }}<br/>
ご登録メールアドレス （必須）: {{ $member->user->email }} <br/>
お問い合わせ内容 （必須）: {{ $data['inquiry']}} <br/>