<?php
    $accountID = Request::get('accountID');

    if (isset($accountID)) {
        Session::put('accountID', $accountID);
    } 

    $accountID = Session::get('accountID');
    

    if ($accountID == null) 
    {
        $accountAliasModel = new \App\Models\MemberMultiAccountAlias();
        $accountAlias = $accountAliasModel->getMemberDefaultAccount(Auth::user()->id);

        if ($accountAlias) {
            $accountID = $accountAlias->member_multi_account_id;
        }
    }
?>

@if (isset($accountID)) 
<member-lessonviewer-component
    :memberinfo="{{  json_encode($memberInfo )  }}" 
    api_token="{{ Auth::user()->api_token }}" 
    csrf_token="{{ csrf_token() }}"
    :selected_account_id="{{ $accountID }}"
    >
</member-lessonviewer-component>
@else 

<member-lessonviewer-component
:memberinfo="{{  json_encode($memberInfo )  }}" 
api_token="{{ Auth::user()->api_token }}" 
csrf_token="{{ csrf_token() }}"
>
</member-lessonviewer-component>
@endif