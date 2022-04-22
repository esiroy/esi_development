@php

    //search if user has merged account
    $mergedAccount = \App\Models\MergedAccount::where('merged_member_id', Auth::user()->id)->first();
    if ($mergedAccount) {
        $memberInfo = \App\Models\User::find($mergedAccount->member_id)->memberInfo;
    } else {
        $memberInfo = Auth::user()->memberInfo;   
    }
@endphp

<member-time-manager-component 
    :memberinfo="{{  json_encode($memberInfo )  }}" 
    api_token="{{ Auth::user()->api_token }}" 
    csrf_token="{{ csrf_token() }}">
</member-time-manager-component>