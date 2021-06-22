<div style="margin-top:5px; font-size: 12px">
    Hi  {{ $data['name']}}
</div>


<div style="margin-top:5px; font-size: 12px">
    マイチューターに御登録ありがとうございます。
</div>


<div style="margin-top:5px; font-size: 12px">
    次のURLをクリックすることで、登録が完了致します。
</div>

<div style="margin-top:5px; font-size: 12px">
    <a href="{{ url('activation/'. $data['activation_code'] .'/'. $data['a8']) }}">{{ url('activation/'. $data['activation_code'] ."/". $data['a8']) }}</a>
</div>

<div style="margin-top:5px; font-size: 12px">
クリックできない場合はURLをコピーしてWEBブラウザのURLの欄に張り付けてください。
</div>

<div style="margin-top:5px; font-size: 12px">
マイチューター　事務局
</div>

<div style="margin-top:5px; font-size: 12px">
------------------------------------<br/>
このメールに心あたりのない方は、<br/>
info@net-english.com までメールをお願いします。<br/>
</div>