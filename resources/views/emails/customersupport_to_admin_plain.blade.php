{{ date('M d, Y')}}{{PHP_EOL}}
お名前 （必須）: {{ $member->user->japanese_lastname ?? '-' }}, {{ $member->user->japanese_firstname ?? '-' }}{{PHP_EOL}}
Name: {{ $member->user->lastname ?? ''}}, {{ $member->user->firstname ?? '' }}{{PHP_EOL}}
ご登録メールアドレス （必須）: {{ $member->user->email }}{{PHP_EOL}}
お問い合わせ内容 （必須）: {{ $data['inquiry']}} {{PHP_EOL}}