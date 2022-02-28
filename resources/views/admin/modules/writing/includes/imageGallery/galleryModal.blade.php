    <!-- Modal -->
    <div id="modal_gallery" class="modal fade" role="dialog" style="z-index:9999;">
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

                      
                                <div class="tab-container esi-tab-container">
                                    <div id="tabs" class="tabs esi-tabs mt-2">
                                        <ul>
                                            <li><a href="#tabs-media-library" id="btnMediaLibraryTab">Media Libray</a></li>                
                                            <li><a href="#tabs-uploader" id="btnUploadTab">Upload Files</a></li>
                                        </ul>

                                        <div id="tabs-media-library" style="overflow-x:scroll; height:425px">
                                            <div class="media-content">
                                            </div>
                                        </div>

                                        <div id="tabs-uploader">
                        
                                            @php
                                              $api_token =  Auth::user()->api_token;
                                              $url = url("api/writing/upload?api_token=$api_token");
                                            @endphp
                                            <form action="{{ $url }}" method="POST"class="dropzone" id="dropzonewidget">
                                                {{ csrf_field() }}
                                                <div id="template"></div>
                                            </form> 

                                            <div id="previews my-2"></div>

                                            <a href="#" class="fileinput-button btn btn-primary my-2">Select Files</a>

                                        </div>


                                    </div>
                                </div> 
                        
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