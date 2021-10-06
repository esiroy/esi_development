    <!-- Modal -->
    <div id="modal_gallery" class="modal fade" role="dialog" >
        <div class="modal-dialog  modal-lg" style="max-width:90%; height:80%">
            <!-- Modal content-->
            <div class="modal-content" style="height:100%">
                <div class="modal-header">
                    <h4 class="modal-title">Media Libary</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body bg-white">
                    <div class="row">
                        <div class="col-md-9">
                            @php
                                //MP3
                                $mp3Image = url('images/audio.png');
                                $files = File::files(public_path(). '/storage/uploads/writing_materials');
                                $images = [];
                            @endphp

                            <form id="form_gallery" name="form_gallery">
                                <div class="tab-container esi-tab-container">
                                    <div id="tabs" class="tabs esi-tabs mt-2">
                                        <ul>
                                            <li><a href="#tabs-library">Media Libray</a></li>                
                                            <li><a href="#tabs-uploader">Upload Files</a></li>
                                        </ul>
                                        <div id="tabs-library" style="overflow-x:scroll; height:520px">
                                            <div class="row">                                        
                                                @foreach ($files as $file) 
                                                    <div class="col-md-2 mb-4 img-container">
                                                        <div class="img-wrapper" style="margin:auto; border:3px solid #D2E0EB; background-color:#F6FBFD; display: flex;height: 100%; max-height: 125px; overflow:hidden">

                                                            @php                                                                
                                                                $filename   = $file->getRelativePathname();
                                                                $filetype  = pathinfo($filename, PATHINFO_EXTENSION);
                                                                $image = url('storage/uploads/writing_materials/' . $filename);

                                                                if ($filetype == 'jpg' || 
                                                                    $filetype == 'jpeg' || 
                                                                    $filetype == 'png' || 
                                                                    $filetype == 'gif'  
                                                                ) {
                                                                    $shownImage =  $image;                                                                    
                                                                } else {
                                                                    $shownImage = $mp3Image;                                                                    
                                                                }
                                                                echo "<img src='$shownImage' class='img-fluid' style='margin: auto;'>";
                                                            @endphp 
                                                        </div>

                                                        <div class="img-url-container text-center d-none" style="background-color:#D2E0EB;">
                                                            <span class="img-url text-break small">{{$shownImage}}</span>
                                                        </div>

                                                        <div class="img-filename-container text-center" style="background-color:#D2E0EB;">
                                                            <span class="img-filename text-break small">{{$filename}}</span>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div id="tabs-uploader">
                                            uploader
                                        </div>
                                    </div>
                                </div> 
                            </form>
                        </div>
                        <div class="col-md-3">
                            <!-- CONTROL MEDIA INFORMATION -->
                            <div class="row">
                                <div class="col-md-12">                                   

                                    <div class="card">
                                        <div class="card-header font-weight-bold">
                                            MEDIA ATTACHMENT DETAILS
                                        </div>
                                        <div class="card-body">              

                                            <div class="row mb-2">
                                                <div id="preview" class="col-md-12 d-none">
                                                    <img id='mediaImgPreview' src="" class="img-fluid border">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">URL: </div>
                                                <input id='selectedFilename' type='text' class="form-control form-control-sm col-md-8" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>                                    
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="modal-footer bg-white">                   
                    <button id="btnGalleryInsert" type="button" class="btn btn-primary" data-dismiss="modal" disabled>Insert</button>
                </div>
            </div>

        </div>
    </div>