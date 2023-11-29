<div class="monthly-monthlyreadme-container">    

    <div class="anchor-readme-wrapper">
        <a href="JavaScript:openMonthlyTermsModal();"  class="text-small text-danger">
        月の途中でご購入された場合、開始月のポイントは日割り計算で付与されます。
        </a>
    </div>
</div>



<div class="modal fade" id="modal-monthlyreadme-dialog"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">月額制プラン ご留意点</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <ul>
            <li>全てのコースを受講いただけます。（1P=1レッスン、但し、<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/tensaku.html','',900,820);">「添削くん」</a>は文字数で消費ポイントが変わります）<br>
                一部のコースは対応できる講師が限られますので<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0307211019.html','',900,820);">対応講師をご確認ください</a>。<br>
                対応講師が限られたコースを受講される方は、<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0719232615.html','',900,820);">マイページ「カスタマーサポート」</a>までご連絡ください。代行をしない設定にさせていただきます。</li>
            <li>無料体験を含め、初めての予約の際は、希望レッスンコースを、<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0717162035.html','',900,820);">「講師への連絡」</a>から英字でご連絡ください。<br>
                一度、受講頂くと、全講師共有の受講歴からカリキュラムの順番で進めてまいります。<br>
                後日、コース変更が可能です。コース変更の際は、上記同様にご連絡ください。</li>
            <li>毎月ご契約の回数分ポイントを利用できます。ポイントの翌月繰越しはできません。</li>
            <li>月途中でポイントを１ポイントから追加購入できます。追加ポイントは購入した月内で利用できます。</li>
            <li><a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2020/0526183735.html','',900,820);">担任制―固定予約</a>をご利用いただけます。8回-1コマ、12回以上-2コマ</li>
            <li><a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0418180730.html','',900,820);">パーソナル・サポート・プログラム（PSP）</a>をご利用いただけます。複数アカウント保有者はPSPの登録・手続きをメインアカウントのみでご利用いただけます。<br>
                <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/0429212109.html','',900,820);">（複数アカウント データ同期化（メインアカウント作成）</a></li>
            <li>お支払い名義と会員登録名が異なる場合は、<a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0719232615.html','',900,820);">マイページ「カスタマーサポート」</a>からお支払い名義をご連絡ください。</li>
            <li><a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2020/0407173928.html','',900,820);">同時並行で複数コースを受講する場合、</a>アカウントを分けていただくことをお勧めします。</li>
            <li>解約（休会）について：　月額制プランは自動更新となります。解約・休会の際は、引落予定日（更新日）の５日前までにメールで通知が必要です。</li>
            <li>複数アカントをお持ちの方はお支払い後に、当該マイページ「カスタマーサポート」からお支払いが完了したことをご連絡ください。</li>
            <li>6日以降、月途中でお申込みの場合、初月と最終月が日割りでポイントが設定されます<br><a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2023/1118120224.html','',900,820);">（月額制プラン月途中開始で、開始月のポイントが日割りになる理由）</a></li>

        </ul>      

        <p>＜初月と最終月が日割りでポイントが設定＞</p>

        <div class="container">
          <div class="row">
            <div class="col-12 px-5">
              <img src={{asset('images/img_month.gif')}} alt="monthly" class="img-fluid w-100 px-5">
            </div>  
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="memberMarkAgreedMonthlyTerms({{ Auth::user()->id }})">I understand and don't show this again</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeMonthlyTermsModal()">Close</button>

      </div>

    </div>
  </div>
</div>


