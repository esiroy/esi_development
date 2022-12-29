@php

    //search if user has merged account
    $mergedAccount = \App\Models\MergedAccount::where('merged_member_id', Auth::user()->id)->first();

    if ($mergedAccount) 
    {

        $memberInfo = \App\Models\User::find($mergedAccount->member_id)->memberInfo;

        //override report cards if secondary
        $reportCardModel = new \App\Models\ReportCard();
        $latestReportCard = $reportCardModel->getLatest($memberInfo->user_id);

       $mainAccount = \App\Models\User::select('users.id', 'users.email')->find( $mergedAccount->member_id );

    } else {
        $memberInfo = Auth::user()->memberInfo;   
    }
@endphp

<div class="col-md-3">
    <div>@include('modules.member.sidebar.profile')</div>
    <div class="mt-3">@include('modules.member.sidebar.customerchatsupport')</div>
    <div class="mt-3">@include('modules.member.sidebar.memberlevel')</div>    
    <div class="mt-3">@include('modules.member.sidebar.reports')</div>
    <div class="mt-3">@include('modules.member.sidebar.membertestscores')</div>    
    <div class="mt-3">@include('modules.member.sidebar.memberpurpose')</div>
    <div class="mt-3">@include('modules.member.sidebar.attentionnotes')</div>
    <div class="mt-3">@include('modules.member.sidebar.timemanager')</div>
</div>
