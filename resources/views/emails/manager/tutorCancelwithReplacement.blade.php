<div class="container">
    <div style="margin-top:20px; font-size: 14px">
        {{ $member->user->lastname ?? '' }}, {{ $member->user->firstname ?? ''}} 様
    </div>

    <div style="margin-top:20px; font-size: 14px">
        いつもマイチューターをご利用いただきありがとうございます。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>{{ ESIMailDateTimeFormat($scheduleItem->lesson_time) }}</div>
        <div>でご予約いただいてました講師{{ $tutor->user->firstname }}のレッスンですが、</div>
        <div>講師の都合によりレッスンがキャンセルとなりました。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>ご予約頂いておりました講師がレッスンをご提供できず、</div>
        <div>まことに申し訳ございません。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>このレッスンの代講として、別の講師をこちらで手配させていただきます。</div>
        <div>（枠を確保するために勝手ながら手配させていただきます）</div>
        <div>（別の講師にはなりますが、ご予約いただいた時間帯でのレッスンが可能です）</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>本日はご迷惑をおかけしてしまい、申し訳ございません。</div>
        <div>よろしくお願いいたします。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>マイチューター　カスタマーサポート</div>
        <div>ご連絡先　　　{{ Config::get('mail.from.address') }}</div>
    </div>
</div>