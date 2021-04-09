
{{ date('M d, Y')}} 
{{PHP_EOL}} {{PHP_EOL}}


お名前 （必須）: {{ $member->user->firstname }}  {{ $member->user->lastname }}
{{PHP_EOL}} {{PHP_EOL}}


フリガナ: {{ $member['nickname'] }}
{{PHP_EOL}} {{PHP_EOL}}

 
ご登録メールアドレス （必須）: {{ $member->user->email }} 
{{PHP_EOL}} {{PHP_EOL}}


お問い合わせ内容 （必須）: {{ $data['inquiry']}} 
{{PHP_EOL}} {{PHP_EOL}}