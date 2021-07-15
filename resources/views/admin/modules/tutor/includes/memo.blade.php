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
                            @include('admin.modules.tutor.includes.uploadPreview')                            
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="reply">
                                    <textarea class="form-control" id="teacherTextReply" rows="1" cols="1" style="min-height:70px"></textarea>
                                    @include('admin.modules.tutor.includes.uploader')    
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
<script src="{{ url('js/dropzone/dropzone.min.js') }}"></script>
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

<script>
    window.addEventListener('load', function () 
    {
        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            maxFiles: 20,
            maxFilesize: 10,
            url: "/target-url", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 1,
            uploadMultiple: false,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button",// Define the element that should be used as click trigger to select files.,
            init: function() {
                this.on("addedfile", function() 
                {
                    /*
                    if (this.files.length >= 1) {
                        $('.fileinput-button').hide();
                    } */                

                    /*
                    if (this.files[1]!=null) {
                        this.removeFile(this.files[1]);
                    }
                    */
                });
            }            
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1";
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0";
        });

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#btnReply").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };

        document.querySelector("#actions .cancel").onclick = function() 
        {
            myDropzone.removeAllFiles(true);
        };      
        
    });
</script>
@endsection      


@section('styles')
@parent
<style>

.file-row {
    background: #ffffff;
   
}

</style>
@endsection

