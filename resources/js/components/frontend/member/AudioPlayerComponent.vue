<template>

    <div class="audioPlayerContainer">

        <!-- Start custom Audio player-->
        <div class="audio-player text-center text-light" v-show="this.audioFiles.length <= 0" >
            <div class="my-2">
                Slide has no audio
            </div>
        </div>

        <div class="audio-player" v-show="this.audioFiles.length >= 1">
            <audio id="audio" ref="myMusic" @timeupdate="onTimeUpdateListener">
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



                <button id="prevAudio" @click="prevAudio" class="button-transparent d-inline-block">
                    <i class="fa fa-fast-backward" aria-hidden="true"></i>
                </button>

                <!--<button id="playAudio" @click="togglePlay()" class="button-transparent"></button>-->

                <button class="button-transparent"  @click="togglePlay()">                    
                   <b-icon-play font-scale="2" v-show="playBtn == true"></b-icon-play>
                   <b-icon-pause font-scale="2" v-show="playBtn == false"></b-icon-pause>
                </button>

                <button id="nextAudio" @click="nextAudio" class="button-transparent d-inline-block">
                    <i class="fa fa-fast-forward" aria-hidden="true"></i>
                </button>

                <div id="seekObjContainer">
                    <div id="seekObj" @click="seekAudio">
                        <div id="percentage"></div>
                    </div>
                </div>
               
                <div id="currentTime" class="small">00:00</div>

                <div class="volumeBar">
                    <div class="volumebkg"></div>
                    <div id="volume" class="volume"></div>
                </div>


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
        padding: 0px 10px 0px;
        margin: 10px;
        background-color: #0074bc;
        border: 1px solid black;


        .volumeBar {
            display:block;
            height:50px;
            position:relative;
            top: 7px;
            right:0;
            background-color:none;
            z-index:100;
            width: 50px;
            cursor:pointer;
        }


        .volume {
            /*background-color:#888;*/
            position: absolute;
            clip: rect(0px, 45px, 40px, 0px);
            width: 50px;
            height: 0;
            border-style: solid;
            border-width: 0 0 25px 50px;
            border-color: transparent transparent #ffffff transparent;
            line-height: 0px;
            _border-color: #000000 #000000 #007bff #000000;
            _filter: progid:DXImageTransform.Microsoft.Chroma(color='#000000');
        }

        .volumebkg {
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 25px 50px;
            border-color: transparent transparent #999 transparent;
            line-height: 0px;

            _border-color: #000000 #000000 #999 #000000;
            _filter: progid:DXImageTransform.Microsoft.Chroma(color='#000000');
        }
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
            width: 100px;
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
           
            audioFiles: [],
            audioIndex: 0,
            media: null,
            playBtn: true,
            audio: null,
            currentTime: null,
            //volume
            isVolumeDragged: false,
        }
    },
    mounted() { 
       
        $('.volume,.volumeBar').on('mousedown',  (e) => { //onTouchStart
            this.isVolumeDragged = true;
            this.audio.muted = false;
            $('.sound').removeClass('muted');
            this.updateVolume(e.pageX);
        });

        $(document).on('mouseup',  (e) => { //touchend
            if (this.isVolumeDragged) {
                this.isVolumeDragged = false;
                this.updateVolume(e.pageX);
            }
        });

        $(document).on('mousemove',  (e) => { //touchmove
            if (this.isVolumeDragged) {
                this.updateVolume(e.pageX);
            }
        });
    },
    methods: {      
        updateVolume(x) {
            var volume = $('.volume');
            let volumeWidth = document.getElementById("volume").offsetWidth
            
            var position = x - volume.offset().left;
            let percentage = 100 * position / volumeWidth;        

            if (percentage > 100) {
                percentage = 100;
            }

            if (percentage < 0) {
                percentage = 0;
            }

            //set the volume
            let newVolume = percentage / 100; 
            this.audio.volume = newVolume; 

            $('.volume').css('clip', 'rect(0px, '+(percentage / 2)+'px, 50px, 0px)');
        },
        loadAudioList(audioFiles, num) {
            this.audioFiles = audioFiles[num];

            //load the first on the list           
            this.loadAudio(this.audioFiles[this.audioIndex]);            
        },
        nextAudio() {
            if (this.audioIndex <  this.audioFiles.length -1 ) {
                this.audioIndex = this.audioIndex + 1;
                this.loadAudio(this.audioFiles[this.audioIndex]);     
                this.togglePlay();            
            }            
        },
        prevAudio() {            
            
            if (this.audioIndex >= 1 ) {
                this.audioIndex = this.audioIndex - 1;
                this.loadAudio(this.audioFiles[this.audioIndex]);  
                 this.togglePlay();   
            }      
        },
        loadAndPlay(audio) {
            this.loadAudio(audio);
            this.togglePlay();      
        },
        loadAudio(audio) {

            console.log(audio);

            this.audio = document.getElementById('audio');

            if (audio) {
                this.audio.src = window.location.origin +"/"+ audio.path;
                this.audio.load();
                this.audio.onloadedmetadata = () => {
                    this.onTimeUpdateListener();
                };
            } else {            
                console.log("no audio for this slide")
            }
        },
        seekAudio(e) {          

            let seekAudio = document.getElementById('audio');
            let offsetWidth = document.getElementById("seekObj").offsetWidth;

            const percent = e.offsetX / offsetWidth;
            console.log(e.offsetX , offsetWidth, percent * seekAudio.duration);
            seekAudio.currentTime = percent * seekAudio.duration;
            seekAudio.play();

            //@todo: send current time to student
            //seekAudio.currentTime 
           
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
        stopAudio() {
            this.playBtn = true;
            this.audio.pause();
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