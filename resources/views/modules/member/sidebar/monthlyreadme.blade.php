<!--
  <div class="monthly-monthlyreadme-container">
    <div class="anchor-readme-wrapper">
        <a href="JavaScript:openMonthlyTermsModal();"  class="text-small text-danger">
        月の途中でご購入された場合、開始月のポイントは日割り計算で付与されます。
        </a>
    </div>
</div>
-->



<div class="modal fade" id="modal-monthlyreadme-dialog" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h2 class="modal-title text-primary">
                    月額制プラン-マイページ表示について
                </h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

           

                    <h4 class="text-center my-3 text-danger">月額制会員様　月間受講上限　及び　月間残り受講回数</h4>

                    <h5 class="text-center mb-3 text-danger">注意：　　予約した時点でポイントは一旦消化されます</h5>

                    <div class="container">

                        <div class="row">

                            <div class="col-12 px-5">

                                <p>マイページ（以下）で表示される「月間〇〇回クラス」は受講している「月」でご利用いただける</p>
                                <p>ポイント数になります。</p>
                                <p class="text-danger">注意：　月半ば（6日以降）にお申込みいただいた場合は、お申込み月と退会（休会）月</p>
                                <p class="text-danger">で日割りになります。</p>
                                <p>お申込み翌月からはご契約されました「毎月〇〇回コース」回数が表示されます。</p>
                                <p>
                                    <span class="font-weight-bold">※　									
                                        <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2023/1118120224.html','月額制プラン月途中開始で、開始月のポイントが日割りになる理由',900,820);"
                                            class="text-primary">月額制プラン月途中開始で、開始月のポイントが日割りになる理由</a></span>
                                </p>

                                <img src={{asset('images/monthly-plan.png')}} alt="monthly"
                                    class="img-fluid w-100 px-5">


                                <p class="mt-5 font-weight-bold">
									★　6日以降、月途中でお申込みの場合、初月と最終月が日割りでポイントが設定されます
								</p>

                                <img src={{asset('images/img_month.gif')}} alt="monthly" class="img-fluid w-100 px-5">



                                <div class="mt-3">
                                    <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/service.html#notice_month','その他「月額制プラン」 ご留意点について',900,820);" class="text-primary">
										その他「月額制プラン」 ご留意点について
									</a>
                                </div>


                            </div>

                        </div>

                    </div>

              
            </div>


			<!-- Modal Footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-primary"
					onclick="memberMarkAgreedMonthlyTerms({{ Auth::user()->id }})">I understand and don't show this
					again</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"
					onclick="closeMonthlyTermsModal()">Close</button>

			</div>

        </div>



    </div>
</div>
