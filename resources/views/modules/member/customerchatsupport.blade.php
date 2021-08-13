@extends('layouts.esi-chat')

@section('content')


<div style="padding-top:0px; padding-bottom:0px">
    @php
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
        
    @endphp   

                                                            
    <customer-chat-component 
            userid="{{ Auth::user()->id }}" 
            username="{{ Auth::user()->username }}"
            user_image="{{ $memberProfileImage }}"
            nickname="{{ $nickname }}"
            api_token="{{ Auth::user()->api_token }}"
        >
    </chat-component>     
</div>


@endsection

