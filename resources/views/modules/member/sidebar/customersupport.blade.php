 <div class="profile bg-lightorange pt-0 px-0">
     <div class="col-md-12 bg-orange text-white pt- pb-1">
         メンバー
     </div>

     <table cellpadding="4" cellspacing="0" class="mt-2">
         <tbody>
             <tr>
                 <td valign="top" width="65px">
                     <img src="{{ url('images/mytutor-logo-skype.jpg')}}" width="60px">
                 </td>

                 <td valign="top" class="pl-2">
                     マイチューター
                     <br>

                     @if (strtolower($member->communication_app) == 'zoom') 

                     <a href="https://us02web.zoom.us/j/4671382877" style="padding-top:100px;">

                         <img src="{{ url('images/zoom_logo.jpg') }}">
                         <span style="font-size:12px;color:#0099CC;line-height:39px;">セブ・マネジャー</span>
                     </a>
                     <br>
                     <a class="small text-danger" href="http://www.mytutor-jpn.com/info/2018/0315134516.html" target="_blank">セブマネジャーとは？</a>
                                          

                     @elseif (strtolower($member->communication_app) == 'skype') 

                     <a href="skype:netenglish.cebumanager?call" style="padding-top:100px;">
                         <i class="fab fa-skype fa-2x" style="color:#0099CC;font-size:1.6em"></i>
                         <span style="font-size:12px;color:#0099CC;line-height:39px;">セブ・マネジャー</span>
                     </a>
                     <br>
                     <a class="small text-danger" href="http://www.mytutor-jpn.com/info/2018/0315134516.html" target="_blank">セブマネジャーとは？</a>

                     @endif
                 </td>
             </tr>

         </tbody>
     </table>
 </div>