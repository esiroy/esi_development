<div class="profile bg-lightorange pt-0 px-0">
     <div class="col-md-12 bg-orange text-white pt-2 pb-2 text-center">
        <strong>セブ・マネジャー</strong>
     </div>

    <!--
     <table cellpadding="4" cellspacing="0" class="mt-2">
         <tbody>
             <tr>
                 <td valign="top" width="65px">
                    <div class="pl-2">
                        <img src="{{ url('images/cs.jpg')}}" width="60px">
                    </div>
                 </td>

                 <td valign="top" class="pl-2 text-align-center">
                    <div class="cs-speech-bubble">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#customerChatSupportModal">Chat Support</a>
                    </div>
                    <div class="small pt-1 pb-1">                        
                        <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0816220249.html','Chat Support ご利用方法',900,820);">Chat Support ご利用方法</a>
                    </a>
                 </td>
             </tr>

         </tbody>
     </table>
     -->

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
            
            $chatserver_url = env('APP_CHATSERVER_URL', 'https://chatserver.mytutor-jpn.info:30001');

        @endphp   

        <member-floating-chat-component
            userid="{{ Auth::user()->id }}" 
            username="{{ Auth::user()->username }}"
            user_image="{{ $memberProfileImage }}"        
            nickname="{{ $nickname }}"        
            customer_support_image="{{ url('images/cs-profile.png') }}"        
            chatserver_url="{{ $chatserver_url }}"
            api_token="{{ Auth::user()->api_token }}"
            csrf_token="{{ csrf_token() }}"    
            is_netenglish="{{ $is_netenglish }}"
            :show_sidebar="true"        
        >
        </member-floating-chat-component>  

 </div>
 

@section('styles')
@parent
<style>
 .cs-speech-bubble {
	position: relative;
	background: #0399d3;
	border-radius: .4em;
    text-align: center;
    width: 130px;
    height: 30px;

}

.cs-speech-bubble a {
    position: relative;
    top: 4px;
    color: #fff
}

.cs-speech-bubble:after {
	content: '';
	position: absolute;
	left: 0;
	top: 50%;
	width: 0;
	height: 0;
	border: 20px solid transparent;
	border-right-color: #0399d3;
	border-left: 0;
	border-bottom: 0;
	margin-top: -10px;
	margin-left: -12px;
}
</style>
@endsection