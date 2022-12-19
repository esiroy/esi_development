<template>

    <div class="audioPlayerContainer">

        <!-- Start custom Audio player-->
        <div class="audio-player">

            <audio id="audio">
                <source src="" type="audio/mp3">
            </audio>

            <div class="player-controls">

                <b-dropdown id="dropdown-1" class="m-md-2" no-caret>
                    <template #button-content>
                        <b-icon-list class="toggle"></b-icon-list>
                    </template>
                    <b-dropdown-item v-for="audio in audioFiles" :key="audio.id" @click="loadAudio(audio)">
                        {{audio.id }} - {{ audio.file_name }}
                    </b-dropdown-item>    
                </b-dropdown>


                <button id="prevAudio" class="button-transparent d-inline-block px-2">
                    <i class="fa fa-fast-backward" aria-hidden="true"></i>
                </button>

                <button id="playAudio" @click="togglePlay()" class="button-transparent"></button>

                <button id="nextAudio" class="button-transparent d-inline-block px-2">
                    <i class="fa fa-fast-forward" aria-hidden="true"></i>
                </button>

                <div id="seekObjContainer">
                    <div id="seekObj">
                        <div id="percentage"></div>
                    </div>
                </div>
                <p><small id="currentTime">00:00</small></p>
            </div>
        </div>


    </div>
</template>


<style lang="scss">

    input.verticalRangeScrubber
    {
        writing-mode: bt-lr; /* IE */
        -webkit-appearance: slider-vertical; /* Chromium */
        width: 8px;
        height: 20px;
        padding: 0px;
    }



    .audio-player {
        width: 460px;
        padding: 10px;
        margin: 10px;
        background-color: #0074bc;
        border: 1px solid black;

        .dropdown-toggle {
            background: transparent;
            border: none;
        }
        
        .button-transparent {    
            outline: none;
            cursor: pointer;
            border: none;
            width: 30px;
            height: 30px;
            color: #fff;
            background: transparent;            
        }

        .player-controls {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #radioIcon {
            width: 30px;
            height: 30px;
            background: url('https://img.icons8.com/ios/50/000000/microphone.png') no-repeat center;
            background-size: contain;
        }

        #playAudio {
            -webkit-appearance: none;
            outline: none;
            cursor: pointer;
            border: none;
            width: 30px;
            height: 30px;
            background: url('https://img.icons8.com/play') no-repeat center;
            background-size: contain;
            &.pause {
                background: url('https://img.icons8.com/pause') no-repeat center;
                background-size: contain;
            }
        }

        p {
            margin: 0 0 0 5px;
            line-height: 1;
            display: inline-flex;

            small {
                font-size: 10px;
            }
        }

        #seekObjContainer {
            position: relative;
            width: 300px;
            margin: 0 5px;
            height: 5px;

            #seekObj {
                position:relative;
                width: 100%;
                height: 100%;
                background-color: #e3e3e3;
                border: 1px solid black;

                #percentage {
                    position: absolute;
                    left: 0;
                    top: 0;
                    height: 100%;
                    background-color: coral;
                }
            }
        }
    }

</style>

<script>
 export default {
    name: "AudioPlayerComponent",
    data() {
        return {
            ui: null,
            audioFiles: [],
            media: null,
        }
    },
    mounted() {
    
    
      

    },
    methods: {
        loadAudioList(audioFiles, num) {           

            this.audioFiles = audioFiles[num];

            //load the first on the list
            this.loadAudio(this.audioFiles[0]);
        },
        loadAudio(audio) {

            var newAudio = document.getElementById('audio');

            if (audio) {
                newAudio.src = window.location.origin +"/"+ audio.path;
                newAudio.load();     

            } else {
            
                console.log("no audio for this slide")
            }

            //this.togglePlay();      
        },
        
        togglePlay() {
        
            let media = document.getElementById('audio');
            let playAudio = document.getElementById('playAudio');

            if (media.paused === false) {
                media.pause();
                 playAudio.classList.toggle('pause');             
            } else {
                media.play();
                playAudio.classList.toggle('pause');
            }
        }
    }
 }
 </script>