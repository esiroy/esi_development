<div class="container">
    <div style="margin-top:20px; font-size: 14px">
        {{ $member->user->lastname ?? '' }}, {{ $member->user->firstname ?? ''}} 様
    </div>
    <div style="margin-top:20px; font-size: 14px">
        いつもマイチューターをご利用いただきありがとうございます。
    </div>
    <div style="margin-top:20px; font-size: 14px">
        下記の内容で、レッスンのご予約を受け承りました。
    </div>
    <div style="margin-top:20px; font-size: 14px">
        予約日時 : {{ ESIMailDateTimeFormat($scheduleItem->lesson_time) }}
    </div>
    <div style="margin-top:20px; font-size: 14px">
        担当講師 : {{ $tutor->user->firstname ?? '' }}
    </div>
    <div style="margin-top:20px; font-size: 14px">
        <div>レッスン開始5分前にはSkype又はZOOMを立ち上げて先生からの連絡をお待ちください</div>
        <div>レッスン開始予定時刻10分後までにスカイプ又はZOOMにログイン頂けない場合には、自動的にレッ</div>
        <div>スンが欠席扱いとなってしまいますのでご注意下さいませ。</div>
    </div>
    <div style="margin-top:20px; font-size: 14px">
        <hr>
        ご予約のキャンセルについて
        <hr>
    </div>
    <div style="margin-top:20px; font-size: 14px">
        <div>レッスン開始3時間前までがキャンセル有効期限となります。</div>
        <div>有効期限までにキャンセル処理されず、レッスンを欠席された場合は</div>
        <div>レッスンが消化されたものとみなされますのでご注意ください。</div>
        <div>キャンセルまたは欠席処理は、お客様マイページから行うことができます。</div>
    </div>
    <div style="margin-top:20px; font-size: 14px">
        マイチューター カスタマーサポート
    </div>
    <div style="margin-top:20px; font-size: 14px">
        お問い合せ :　support@mytutor.co.jp
    </div>
</div>
