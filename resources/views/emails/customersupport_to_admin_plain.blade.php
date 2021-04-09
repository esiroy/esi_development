
{{ date('M d, Y')}} 
{{PHP_EOL}} {!! nl2br("\n") !!}{!! nl2br("\n") !!}


お名前 （必須）: {{ $member->user->firstname }}  {{ $member->user->lastname }}
{{PHP_EOL}}{!! nl2br("\n") !!} {!! nl2br("\n") !!}


フリガナ: {{ $member['nickname'] }}
{{PHP_EOL}}{!! nl2br("\n") !!} {!! nl2br("\n") !!}

 
ご登録メールアドレス （必須）: {{ $member->user->email }} 
{{PHP_EOL}}{!! nl2br("\n") !!} {!! nl2br("\n") !!}


お問い合わせ内容 （必須）: {{ $data['inquiry']}} 
{{PHP_EOL}}{!! nl2br("\n") !!} {!! nl2br("\n") !!}