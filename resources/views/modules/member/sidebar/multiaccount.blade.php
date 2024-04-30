<?php
    $accountID = Request::get('accountID');

    if (isset($accountID)) {
        Session::put('accountID', $accountID);
    } 

    $accountID = Session::get('accountID');
    
?>

@if (isset($accountID)) 
<member-mutliaccount-component
    :memberinfo="{{  json_encode($memberInfo )  }}" 
    api_token="{{ Auth::user()->api_token }}" 
    csrf_token="{{ csrf_token() }}"
    :selected_account_id="{{ $accountID }}"
    >
</member-mutliaccount-component>
@else 


<member-mutliaccount-component
:memberinfo="{{  json_encode($memberInfo )  }}" 
api_token="{{ Auth::user()->api_token }}" 
csrf_token="{{ csrf_token() }}"
>
</member-mutliaccount-component>
@endif