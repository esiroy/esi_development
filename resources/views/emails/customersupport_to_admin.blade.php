
<div style="margin-top:5px; margin-left: 15px; font-weight: bold; font-size: 14px">

    <div style="margin-top:10px; margin-left: 5px; font-weight: bold; font-size: 12px">{{ date(M d, Y)}}</div>

    <div style="margin-top:10px; margin-left: 5px; font-weight: bold; font-size: 12px">
        お名前 （必須）: <span style="font-weight:normal">{{ $data['name']}}</span>
    </div>

 
    <div style="margin-top:10px; margin-left: 5px; font-weight: bold; font-size: 12px">
        フリガナ: 
        <span style="font-weight:normal">
            {{ $data['nickname'] }}
        </span>
    </div>

    <div style="margin-top:10px; margin-left: 5px; font-weight: bold; font-size: 12px">
        ご登録メールアドレス （必須）:
        <span style="font-weight:normal">
            {{ $data['email'] }}
        </span>
    </div>
</div>


<div style="margin-top:20px; font-weight: bold; font-size: 12px">

    <div style="margin-top:5px; font-weight: bold; font-size: 16px">
        お問い合わせ内容 （必須）
    </div>
 
    <div style="margin-top:10px; margin-left: 15px; font-weight:normal; font-size: 12px">
        {{ $data['inquiry']}}
    </div>
</div>

