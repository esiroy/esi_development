@extends('layouts.writing.template')


@section('content')

<form action="admin/writing/upload" class="dropzone" id="dropzonewidget">
    <div id="template"></div>
</form> 

<div id="previews"></div>

<a href="#" class="fileinput-button">Select Files </a>



  
@endsection


@section('styles')
    @parent
    <link rel="stylesheet" href="{{ url('css/dropzone/dropzone.min.css') }}"></link>

@endsection

@section('scripts')
<script src="{{ url('js/dropzone/dropzone.min.js') }}"></script>

<script>    

    window.addEventListener('load', function () 
    {
        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template");
        //previewNode.id = "";
       var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            maxFiles: 1,
            maxFilesize: 10,
            url: "/uploader/fileUploader", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 1,
            uploadMultiple: false,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button",// Define the element that should be used as click trigger to select files.,
            init: function() {
                this.on("addedfile", function() {
                    if (this.files[1]!=null){
                        //    this.removeFile(this.files[1]);
                    }
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
        /*
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
        };
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true);
        };      */
        
    });
</script>
@endsection