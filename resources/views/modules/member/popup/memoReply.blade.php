<div class="modal fade" id="tutorMemoReplyModal" tabindex="-1" role="dialog" aria-labelledby="tutorMemoLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tutorMemoLabel">Memo Reply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
                <div class="modal-body">
                    <div class="container">
                        <input id="scheduleID" type="hidden" value="">

                        <div class="row">
                            <div class="col-md-8">
                                <div id="message" class="member-speech-bubble"></div>                                
                            </div>
                            <div class="col-md-3">
                                <div id="memberProfile">
                                    <div class="profile-image text-center mt-2">
                                        @php      
                                            $memberObj = new \App\Models\Member; 
                                            $memberInfo = $memberObj->where('user_id', Auth::user()->id)->first();

                                            $userImageObj = new \App\Models\UserImage;
                                            $userImage = $userImageObj->getMemberPhoto($memberInfo); 
                                        @endphp

                                        @if ($userImage == null)
                                            <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" >
                                        @else 
                                            <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
                                        @endif
                                    </div>
                                </div>                                              
                            </div>
                        </div>

                        <div class="row">
                            <div id="teacherProfile" style="display:none">
                                <img id="teacherImage" src="" class="img-fluid border" alt="profile photo">
                            </div>
                        </div>

                        <div id="teacherReplies" style="height:220px; overflow:auto; padding-right:25px">                                                                               
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="reply">
                                    <textarea class="form-control" id="memberTextReply" rows="1" cols="1" style="min-height:70px"></textarea>
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



