<div class="container">

    <div style="margin-top:20px; font-size: 14px">
        Hi {{ $member->user->firstname }} {{ $member->user->lastname}} 様
    </div>

    <div style="margin-top:20px; font-size: 14px">
        いつもマイチューターをご利用いただき、誠にありがとうございます。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>ご予約いただいておりましたレッスンにつきまして、</div>
        <div>キャンセルを受け付けました。</div>
        <div>下記、ご確認ください。</div>
        <div>またのご予約をお待ちしております。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>●キャンセル完了レッスン</div>
        <div>レッスン日時： {{ date("F j, Y, H:i", strtotime($scheduleItem->lesson_time)) }}</div>
        <div>講師：{{ $tutor->user->firstname }}</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div><strong>【キャンセル期限について】</strong>●レッスン開始3時間前までがキャンセル有効期限となります。</div>
        <div>有効期限までにキャンセル処理されず、レッスンを欠席された場合は</div>
        <div>レッスンが消化されたものとみなされますのでご注意ください。</div>
        <div>キャンセルまたは欠席処理は、お客様マイページから行うことができます。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>マイチューター カスタマーサポート</div>
        <div>お問い合せ 　support@mytutor.co.jp</div>
    </div>
    
</div>