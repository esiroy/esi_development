 <div class="profile bg-lightred pt-0 pb-4 px-0">
    <div class="col-md-12 bg-danger text-white pt-2 pb-2 text-center">
       <strong>Lesson Request</strong>

        <span id="attention-notes" class="float-right">
            @if($is_netenglish == true)   
                <a href="{{ url('/pages/request-for-lesson') }}" class="esiModal text-white">
            @else         
                <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2022/1229161444.html','Merged Account Help',900,820);" class="text-white">
            @endif 

            <i aria-hidden="true" class="fa fa-question"></i></a>
        </span>

    </div>
    <div id="attention-notes" class="row mx-0 pt-2" >

         <div class="col-md-12">

            <div class="text-dark small">

                <div class="text-center mt-3">
                    <button class="btn btn-danger btn-sm px-3 rounded-pill" data-toggle="modal" data-target="#lessonRequestNotesModal">
                        <i class="fas fa-book"></i>
                        <span class="small ml-1">View Lesson Request Note</span>
                    </button>
                </div>

                <!-- Modal -->
                <div class="modal fade " id="lessonRequestNotesModal" tabindex="-1"  role="dialog" aria-labelledby="lessonRequestNotesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="lessonRequestNotesModalLabel">Lesson Request Note</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">       

                                <div style="max-height:420px; padding:5px">                     
                                    @php 
                                        $lessonGoals = App\Models\LessonGoals::where('member_id', Auth::user()->id)->where('purpose', 'OTHERS')->first();
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
 </div>