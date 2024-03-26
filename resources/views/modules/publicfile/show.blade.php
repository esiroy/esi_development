@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row">
        <div class="folder-container col-md-12">

            <div class="text-center">
                <div class="small">{{ $file->file_name }}</div>
                <div class="mb-2" >
                    <a href="{{ $url }}" download="{{ $file->file_name }}">
                        <button type="button" class="btn btn-success btn-sm">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cloud-download" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                            <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                            </svg> Download
                        </button>
                    </a>

                    <a id="copy" href="{{ url('file/'. $file->id) }}" onclick="copy();return false;">
                        <button type="button" class="btn btn-success btn-sm">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                            <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                            </svg>
                            Copy Link
                        </button>
                    </a>
                </div>
            </div>

            @if(strtolower($extension) == 'mp3')
                <div id="playBtnContainer" class="text-center" style="display:none">
                    <div id="btn-playAudio" class="btn-circle btn-custom-lg rounded-circle fa-regular fa-circle-play blinking-icon"></div>
                </div>

                <div id="audioContainer" class="text-center">
                    <audio id="myAudio" controls autoplay>
                        <source src="{{ $url }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            @elseif (strtolower($extension) == 'png' || strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg' || strtolower($extension) == 'gif')
                <div class="text-center">
                    <img src="{{ $url }}" title="{{ $filename }}" alt="{{ $filename }} " width="60%"/>
                </div>
            @elseif (strtolower($extension) == 'mp4' || strtolower($extension) == 'mpeg' || strtolower($extension) === 'mpeg4' || strtolower($extension) == 'mpg' )
                <div class="text-center">
                    <video width="80%" height="auto" controls autoplay>
                        <source src="{{ $url }}" type="video/{{ $extension }}">
                        Your browser does not support the video tag.
                    </video>
                </div>

            @elseif (strtolower($extension) == 'pdf')
                <div id="pdfObject" style="height:100%; min-height: 650px"></div>
            @endif
        </div>
    </div>
</div>
@endsection


@section('scripts')

    @if (strtolower($extension) == 'pdf')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js"></script>
        <script>PDFObject.embed("{{ $url }}", "#pdfObject");</script>
    @endif

    <script>

        var timer;            
       

        function copy() {
            let link = document.getElementById('copy').href;
            textToClipboard (link)
            return false;
        }

        function textToClipboard (text) {
            let dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
        }

        function toggleVisibility(elementID) {
            var element = document.getElementById(elementID);
            if (element.style.display === 'none') {
                element.style.display = 'block'; // or 'inline', 'inline-block', etc. depending on the element type
            } else {
                element.style.display = 'none';
            }
        }

        function showElement(elementID) {
            var element = document.getElementById(elementID);
            element.style.display = 'block'; // or 'inline', 'inline-block', etc. depending on the element type
          
        }

        function hideElement(elementID) {
            var element = document.getElementById(elementID);
            element.style.display = 'none';
        }

        function checkPlayButton() 
        {
            var promise =  document.getElementById("myAudio").play();

            if (promise !== undefined) {
                promise.then(_ => {
                    // Autoplay started!
                    console.log("Autoplay started");
                    hideElement("playBtnContainer");

                    timer = setInterval(checkAudioTime, 500); // Check time every second

                }).catch(error => {
                    console.log("Autoplay prevented");                   
                    
                    showElement("playBtnContainer");
                    toggleVisibility("audioContainer");
                    // Autoplay was prevented.
                    // Show a "Play" button so that user can start playback.
                });
            }
        }

        function checkAudioTime() {

            const audioPlayer = document.getElementById('myAudio');
            const urlParams = new URLSearchParams(window.location.search);

            const end = urlParams.get('e');
          

            console.log("timer: " + audioPlayer.currentTime + "  >=  " + end + " ???");

            if (end !== null) {
                if (audioPlayer.currentTime >= end) { // Stop audio after 60 seconds (1 minute)
                    audioPlayer.pause();
                    clearInterval(timer);
                    console.log("Audio stopped after " + end + " seconds.");
                }
            }

            //check e
        }        

        document.addEventListener("DOMContentLoaded", function () 
        {
            checkPlayButton();
            checkAudioTime();

            
            const playAudioBtn = document.getElementById('btn-playAudio');
            const audioPlayer = document.getElementById('myAudio');

            const urlParams = new URLSearchParams(window.location.search);
            const startTime = urlParams.get('t');
           

            if (startTime !== null) {
                audioPlayer.currentTime = parseInt(startTime, 10);
            }

            
            // Add click event listener to the button
            playAudioBtn.addEventListener('click', function () {
                
                if (audioPlayer.paused) {
                    // If audio is paused, play it
                    audioPlayer.play();                    

                    timer = setInterval(checkAudioTime, 1000); // Check time every second

                    toggleVisibility("audioContainer");
                    toggleVisibility("btn-playAudio");
                     //playAudioBtn.textContent = 'Pause Audio';

                } else {
                    // If audio is playing, pause it
                    audioPlayer.pause();
                    let elementID = "myAudio";
                    toggleVisibility("myAudio");
                    toggleVisibility("btn-playAudio");     
                    //playAudioBtn.textContent = 'Play Audio';
                }
            }); 


        });


    </script>
@endsection

@section('styles')
<style>
    .btn-custom-lg {
        font-size: 365px;
    }

    .btn-circle:hover {
        cursor: pointer;
    }        


    .blinking-icon {
        animation: blink-animation 1s infinite;
    }

    @keyframes blink-animation {
        0% { opacity: 1; }
        50% { opacity: 0; }
        100% { opacity: 1; }
    }
</style>
@endsection