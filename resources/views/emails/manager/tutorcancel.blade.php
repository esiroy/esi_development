<div class="container">

    <div style="margin-top:20px; font-size: 14px">
        {{ $member->user->lastname ?? '' }}, {{ $member->user->firstname ?? ''}} 様
    </div>

    <div style="margin-top:20px; font-size: 14px">
        いつもマイチューターをご利用いただきありがとうございます。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>{{ ESIMailDateTimeFormat($scheduleItem->lesson_time) }}</div>
        <div>でご予約いただいてました講師{{ $tutor->user->firstname }}のレッスンですが</div>
        <div>講師の都合によりレッスンがキャンセルとなりました。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>ご予約頂いておりました講師がレッスンをご提供できず、</div>
        <div>誠に申し訳ございません。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>マイチューター　カスタマーサポート</div>
        <div>お問い合わせ　　support@mytutor.co.jp</div>
    </div>

</div>
