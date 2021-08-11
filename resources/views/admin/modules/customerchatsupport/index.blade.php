@extends('layouts.admin')

@section('content')

@php
/*
    $userImageObj = new \App\Models\UserImage;
    $memberObj = new \App\Models\Member;

    $userImage = $userImageObj->getMemberPhotoByID(Auth::user()->id);

    if ($userImage == null) {
        $memberProfileImage = Storage::url('user_images/noimage.jpg');
    } else {
        $memberProfileImage = Storage::url("$userImage->original");
    }

    $member =  $memberObj->where('user_id', Auth::user()->id)->first();
    $nickname = $member->nickname;
*/

    $memberProfileImage = Storage::url('user_images/noimage.jpg');
    $nickname = "Customer Support";
    
@endphp 


<admin-chat-component 
        userid="{{ Auth::user()->id }}" 
        username="{{ Auth::user()->username }}"
        user_image="{{ $memberProfileImage }}"
        nickname="{{ $nickname }}"
    >
</admin-chat-component> 


@endsection



@section('scripts')
@parent
<script type="text/javascript">
 
</script>
@endsection