<template>

    <div class="audioPlayerContainer">

        <!-- Start custom Audio player-->
        <div class="audio-player">

            <audio id="audio" controls="control" preload="auto"  ref="myMusic" @timeupdate="onTimeUpdateListener">
                <source src="" type="audio/mp3">
            </audio>

            <div class="player-controls">

                <b-dropdown id="dropdown-1" class="m-md-2" no-caret>
                    <template #button-content>
                        <b-icon-list class="toggle"></b-icon-list>
                    </template>
                    <b-dropdown-item v-for="audio in audioFiles" :key="audio.id" @click="loadAndPlay(audio)">
                        {{audio.id }} - {{ audio.file_name }}
                    </b-dropdown-item>    
                </b-dropdown>



                <button id="prevAudio" class="button-transparent d-inline-block px-2">
                    <i class="fa fa-fast-backward" aria-hidden="true"></i>
                </button>

                <!--<button id="playAudio" @click="togglePlay()" class="button-transparent"></button>-->

                <button class="button-transparent"  @click="togglePlay()">                    
                   <b-icon-play font-scale="3" v-show="playBtn == true"></b-icon-play>
                   <b-icon-pause font-scale="3" v-show="playBtn == false"></b-icon-pause>
                </button>

                <button id="nextAudio" class="button-transparent d-inline-block px-2">
                    <i class="fa fa-fast-forward" aria-hidden="true"></i>
                </button>

                <div id="seekObjContainer">
                    <div id="seekObj" @click="seekAudio">
                        <div id="percentage"></div>
                    </div>
                </div>
                <p style="width:100px;">
                    <small id="currentTime">00:00</small>
                </p>
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

        #currentTime {
            color: #fff;
        }
        
        .button-transparent {    
            outline: none;
            cursor: pointer;
            border: none;
            color: #fff;
            background: transparent;            
        }

        .player-controls {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            playBtn: true,
            newAudio: null,
            currentTime: null,
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
        loadAndPlay(audio) {
            this.loadAudio(audio);
            this.togglePlay();      
        },
        loadAudio(audio) {
            this.newAudio = document.getElementById('audio');

            if (audio) {
                this.newAudio.src = window.location.origin +"/"+ audio.path;
                this.newAudio.load();

                this.newAudio.onloadedmetadata = () => {
                    this.onTimeUpdateListener();
                };

            } else {            
                console.log("no audio for this slide")
            }
        },
        seekAudio(e) {

            let seekAudio = document.getElementById('audio');

            seekAudio.currentTime = 20;
            seekAudio.play();


           
        },        
        getDuration() {
           return this.$refs.myMusic.duration;
        },
        onTimeUpdateListener() {
            let currentTime = this.$refs.myMusic.currentTime;

            let duration =  this.getDuration();    
            if (duration >= 0) {            
                let percentage = (currentTime / duration).toFixed(2) * 100;            
                var percentageBar = document.getElementById('percentage');
                percentageBar.style.width = `${percentage}%`;
                //update current time on display
                var currentTimeInfo = document.getElementById('currentTime');
                currentTimeInfo.innerHTML = this.calculateCurrentValue(currentTime) + "/ " + this.calculateCurrentValue(duration);
            }     
        },
        calculateCurrentValue(currentTime) {
            const currentMinute = parseInt(currentTime / 60) % 60;
            const currentSecondsLong = currentTime % 60;
            const currentSeconds = currentSecondsLong.toFixed();
            const currentTimeFormatted = `${currentMinute < 10 ? `0${currentMinute}` : currentMinute}:${
            currentSeconds < 10 ? `0${currentSeconds}` : currentSeconds}`;            
            return currentTimeFormatted;
        },



        togglePlay() {
        
            this.media = document.getElementById('audio');
           

            if (this.media.paused === false) {
                this.media.pause();   
                this.playBtn = true;
            } else {                
                this.media.play();
                this.playBtn = false;
            }
        }
    }
 }
 </script>