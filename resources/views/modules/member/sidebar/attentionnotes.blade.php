 <div class="profile bg-lightred pt-0 pb-4 px-0">
    <div class="col-md-12 bg-danger text-white pt-2 pb-2 text-center">
       <strong>Lesson Request</strong>

        <span id="attention-notes" class="float-right">
            <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/1229161444.html','Merged Account Help',900,820);" class="text-white">
            <i aria-hidden="true" class="fa fa-question"></i></a>
        </span>

    </div>
     <div id="attention-notes" class="row mx-0 pt-2" >
         <div class="col-md-12">

             <div class="text-dark small px-2" style="height:100px; overflow-y:auto">
                @php 
                    $lessonGoals = App\Models\LessonGoals::where('member_id', Auth::user()->id)
                                    ->where('purpose', 'OTHERS')->first();
                @endphp
                
              

                @if (isset($lessonGoals))
                    @if (str_replace('<p>&nbsp;</p>', '', $lessonGoals->extra_detail) == '')                      
                      <div class="text-center pt-3">{{ "No Lesson Request" }}</div>
                    @else                     
                        {!! $lessonGoals->extra_detail ?? "" !!}
                    @endif
                @else 
                    <div class="text-center pt-3">{{ "No Lesson Request" }}</div>
                @endif

             </div>
         </div>
     </div>
 </div>