 <div class="profile blueBox">
     <div class="profile-image text-center">
         <img src="/images/samplePictureNoImage.jpg" width="145" height="145">
     </div>
     <div class="profile_settings text-center">
         <a href="{{ url('settings') }}" class="small">[click here]</a>
     </div>

     <div id="userDetails" class="row">
         <div class="col-md-12">
             <div class="text-secondary">Name:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                 {{ ucfirst(Auth::user()->first_name) }}, {{ ucfirst(Auth::user()->last_name) }}
             </div>
         </div>

         <div class="col-md-12">
             <div class="text-secondary">Lecturer</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">{{ $data['lecturer'] }}</div>
         </div>
         <div class="col-md-12">
             <div class="text-secondary">Skype ID:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">{{ $data['skypeID'] }}</div>
         </div>
     </div>
 </div>
