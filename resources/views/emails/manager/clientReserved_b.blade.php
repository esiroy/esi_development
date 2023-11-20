<div class="container">

    <div style="margin-top:20px; font-size: 14px">
        {{ $member->user->lastname ?? '' }}, {{ $member->user->firstname ?? ''}} 様
    </div>


    <div style="margin-top:20px; font-size: 14px">
        いつもマイチューターをご利用いただきありがとうございます。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        下記の内容で、講師のレッスン予約をこちら管理者で完了しました。
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
        ご予約をこちら管理者が行う場合について
        <hr>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <ul>
        	<li>予め決められた担任講師の予約。</li>
            <li>当初予約した講師の都合でレッスンを行えない場合の代講として。</li>
            <li>その他特別な事情で生徒様に代わり講師を予約する場合がございます。</li>
        </ul>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        ご予約のキャンセルについて
        <hr>

        <div>こちらのレッスン予約はキャンセルできません。</div>
        <div>レッスンを受けられない場合にはマイページにある「欠席」</div>
        <div>ボタンでお知らせください。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>マイチューター カスタマーサポート</div>
        <div>お問い合せ 　{{ Config::get('mail.from.address') }}</div>
    </div>
    
</div>