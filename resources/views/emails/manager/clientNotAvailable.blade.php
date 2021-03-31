<div class="container">

    <div style="margin-top:20px; font-size: 14px">
        {{ $member->user->lastname ?? '' }}, {{ $member->user->firstname ?? ''}} 様
    </div>

    <div style="margin-top:20px; font-size: 14px">
        いつもマイチューターをご利用いただきありがとうございます。
    </div>


    <div style="margin-top:20px; font-size: 14px">
        <div>本日、ご予約いただきましたレッスンで</div>
        <div>講師からコールを差し上げましたが、</div>
        <div>生徒様のオンライン状況を確認できませんでした。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>もしレッスンご受講の際に何か問題がございましたら</div>
        <div>お教えくださいませ。</div>
        <div>こちらは受講者が「欠席」の際、発信される自動メールです。既にご連絡いただいている場合はご容赦ください）</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>予約日時　：{{ date("F j, Y, H:i", strtotime($scheduleItem->lesson_time)) }}</div>
        <div>担当講師　：{{ $tutor->user->firstname ?? '' }}</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        キャンセルは3時間前まで可能でございます。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        <div>また、それ以降は欠席として「欠席ボタン」でお知らせいただければ助かります。</div>
        <div>ご不明な点がございましたら、こちらカスタマーサポートまでご連絡くださいませ。</div>
    </div>

    <div style="margin-top:20px; font-size: 14px">
        これからもマイチューターのご利用をよろしくお願いいたします。
    </div>

    <div style="margin-top:20px; font-size: 14px">
        マイチューター カスタマーサポート
    </div>

    <div style="margin-top:20px; font-size: 14px">
        お問い合せ 　support@mytutor.co.jp
    </div>
</div>
