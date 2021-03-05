<div class="container">

    <div style="margin-top:20px; font-size: 14px">
        Hi {{ $member->user->lastname ?? '' }}, {{ $member->user->firstname ?? ''}}  様
    </div>

    <div style="margin-top:20px; font-size: 14px">
        いつもマイチューターをご利用いただきありがとうございます。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        下記の内容で、講師のレッスン予約をこちら管理者で完了しました。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>予約日時　：{{ date("F j, Y, H:i", strtotime($scheduleItem->lesson_time)) }}</div>
        <div>担当講師　：{{ $tutor->user->firstname ?? '' }}</div>
    </div>


    <div style="margin-top:20px; font-size: 14px">      
        <div>レッスン開始5分前にはSkype又はZOOMを立ち上げて先生からの連絡をお待ちくださいレッスン開始予定時刻10分後までにスカイプ又はZOOMにログイン頂けない場合には、自動的にレッスンが欠席扱いとなってしまいますのでご注意下さいませ。</div>        
    </div>

    <div>
        <hr>
            <div>ご予約をこちら管理者が行う場合について</div>
        <hr>
    <div>

    <div>
        <ul>
            <li>予め決められた担任講師の予約。</li>
            <li>当初予約した講師の都合でレッスンを行えない場合の代講として。</li>
            <li>その他特別な事情で生徒様に代わり講師を予約する場合がございます。</li>
        </ul>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>
            <hr>
            ご予約のキャンセルについて
            <hr>
        </div>

        <div>レッスン開始3時間前までがキャンセル有効期限となります。有効期限までにキャンセル処理されず、レッスンを欠席された場合はレッスンが消化されたものとみなされますのでご注意ください。キャンセルまたは欠席処理は、お客様マイページから行うことができます。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>マイチューター カスタマーサポート</div>
        <div style="margin-top:12px; font-size: 14px">お問い合せ 　support@mytutor.co.jp</div>
    </div>
</div>
