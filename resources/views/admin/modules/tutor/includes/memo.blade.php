<div class="modal fade" id="tutorMemoModal" tabindex="-1" role="dialog" aria-labelledby="tutorMemoLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="tutorMemoLabel">Memo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <div class="modal-body">

                <input id="scheduleID" type="hidden" value="">
                    <div class="container">

                        <!--
                        <div class="row">
                            <div class="col-md-3">
                                <div id="memberProfile" class="text-center">
                                    <img id="memberImage" src="" class="img-fluid border" alt="profile photo" style="max-width: 77px;float: left;">
                                </div>
                                  
                                                                                             
                            </div>
                            <div class="col-md-9">
                                <div id="message" class="teacher-speech-bubble"></div>
                            </div>
                        </div>

                        <div id="teacherProfile" style="display:none">
                            <div class="profile-image text-center mt-2 mr-3">
                                @php      
                                    $userImageObj = new \App\Models\UserImage;
                                    $userImage = $userImageObj->getTutorPhotobyID(Auth::user()->id); 
                                @endphp

                                @if ($userImage == null)
                                    <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" >
                                @else 
                                    <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
                                @endif
                            </div>
                        </div>                           
                        -->
                       
                        <div class="row">
                            <div id="teacherProfile" style="display:none">
                                <div class="profile-image text-center mt-2">
                                    @php      
                                        $userImageObj = new \App\Models\UserImage;
                                        $userImage = $userImageObj->getTutorPhotobyID(Auth::user()->id); 
                                    @endphp

                                    @if ($userImage == null)
                                        <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" >
                                    @else 
                                        <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div id="memberProfile" style="display:none">
                                <img id="memberImage" src="" class="img-fluid border" alt="profile photo">
                            </div>
                        </div>

                        <div id="teacherReplies" style="height:220px; overflow:auto;"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="reply">
                                    <textarea class="form-control" id="teacherTextReply" rows="1" cols="1" style="min-height:70px"></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button id="btnReply" type="button" class="btn btn-primary" style="height:70px; width:100%">Reply</button>
                                <!--<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>-->
                            </div>
                        </div>
                    </div>                        
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function()     
    {

        let teacherProfileImage = $('#teacherProfile').html();

        /*MEMO REPLY BUTTON ACTIONS */
        $('#btnReply').click(function() 
        {   
            let scheduleID = $('#scheduleID').val(); 
            let teacherMessage = $('#teacherTextReply').val();
            
            let tMessage = $.trim(teacherMessage);
            if (tMessage) {
                sendTutorReply(scheduleID, teacherMessage);
                $('#teacherTextReply').val(""); 
            } else {
                $('#teacherTextReply').val(""); 
            }
        });

        /*
        function addTeacherReply(image, message) 
        {
            $( "#teacherReplies" ).append( "<div class='row'> <div class='col-md-9'><div class='speech-bubble'>"+ message +"</div></div><div class='col-md-3'>" + image + "</div> </div>"); 
            var objDiv = document.getElementById("teacherReplies");
            
            //objDiv.scrollTop = objDiv.scrollHeight;
            $("#teacherReplies").animate({scrollTop: objDiv.scrollHeight }, 500);            
        }*/

        function sendTutorReply(scheduleID, message) 
        {
            if (message) 
            {
                $.ajax({
                    type: 'POST', 
                    url: "{{ url('api/sendMemoReply?api_token=') }}" + api_token,
                    data: {
                        'scheduleID': scheduleID,
                        'tutorID': {{ Auth::user()->id }},
                        'message': message
                    }, headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function (data, error) {             
                        alert("Error Found while sending memo: " + error);
                    },            
                    success: function(data) 
                    {
                        addTeacherReplyBubble(teacherProfileImage, data.message) 
                    
                    }
                });
            }
        }
    });
</script>
@endsection        

