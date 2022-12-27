<template>

    <div class="audioPlayerContainer">

        <!-- Start custom Audio player-->
        <div class="audio-player" v-show="this.audioFiles.length <= 0" >
            <div class="nomedia small text-center text-light">
                <div class="nomedia-information">Slide has no audio</div>
            </div>
        </div>

        <div class="audio-player" v-show="this.audioFiles.length >= 1">

            <div id="audio-info" class="text-center small text-light">
                <div class="text-center" v-if="this.audioFiles[this.audioIndex]">
                    <span id="track_heading">Now Playing</span>
                    <span id="track_index">({{ ( this.audioIndex + 1 ) + "/" + this.audioFiles.length }})</span>
                    <span id="track_sep"> : </span>                    
                    <span id="trackname font-style-bold">{{ this.audioFiles[this.audioIndex].file_name }}</span>
                </div>
            </div>

            <audio id="audio" ref="myMusic" @timeupdate="onTimeUpdateListener" >
                <source src="" type="audio/mp3">
            </audio>

            <div class="player-controls" >

                <b-dropdown id="dropdown-audio-list" class="m-md-2" no-caret v-show="this.$props.isBroadcaster == true">
                    <template #button-content>
                        <b-icon-list class="toggle"></b-icon-list>
                    </template>
                    <b-dropdown-item v-for="(audio, audioIndex) in audioFiles" :key="audioIndex" @click="goToAudio(audioIndex)">
                        {{ (audioIndex + 1) }}  - {{ audio.file_name }}
                    </b-dropdown-item>    
                </b-dropdown>



                <button id="prevAudio" @click="prevAudio" class="button-transparent d-inline-block" >
                    <i class="fa fa-fast-backward" aria-hidden="true" v-show="this.$props.isBroadcaster == true"></i>
                </button>

            

                <button class="button-transparent"  @click="play()" v-show="this.$props.isBroadcaster == true">                    
                   <b-icon-play font-scale="2" v-show="playBtn == true"></b-icon-play>
                   <b-icon-pause font-scale="2" v-show="playBtn == false"></b-icon-pause>
                </button>

                <button id="nextAudio" @click="nextAudio" class="button-transparent d-inline-block">
                    <i class="fa fa-fast-forward" aria-hidden="true" v-show="this.$props.isBroadcaster == true"></i>
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


<style lang="scss" >

    input.verticalRangeScrubber
    {
        writing-mode: bt-lr; /* IE */
        -webkit-appearance: slider-vertical; /* Chromium */
        width: 8px;
        height: 20px;
        padding: 0px;
    }

    .audioPlayerContainer {
        display: inline-block;
        vertical-align: text-bottom;
    }

    .nomedia {
        height: 100%;
        vertical-align: middle;
        position: relative;
        display: table;
        width: 100%;
    }

    .nomedia-information {
        height: 50px;
        vertical-align: middle;
        vertical-align: middle;
        display: table-cell;                
    }

    .audio-player {
        width: 400px;
        padding: 0px 4px 0px;
        margin: 10px 0px 10px;
        background-color: #0074bc;
        //border: 1px solid black;
        border-radius: 5px;
        z-index: 0;

        .volumeBar {
            display:block;
            height:50px;
            position:relative;
            top: 4px;
            right:0;
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

        .dropdown-toggle, #dropdown-audio-list {
            background: transparent !important;
            border: none;
        }

        #currentTime {
            color: #fff;
            font-size: 9.5px;
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
            height: 30px;
            top: -4px;
            right: 7px;
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
                background-color: #999;
                border: 1px solid #999;
                border-radius: 10px;

                #percentage {
                    position: absolute;
                    left: 0;
                    top: 0;
                    height: 100%;
                    background-color: coral;
                    border-radius: 10px;
                }
            }
        }
    }

</style>

<script>
 export default {
    name: "AudioPlayerComponent",
    props: {
        isBroadcaster: {
            type: [Boolean],
            required: true        
        },       
    },
    data() {
        return {           
            audioFiles: [],
            audioIndex: 0,
        
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
        resetAudioIndex() {
            this.audioIndex = 0;
        },
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

            if (audioFiles) 
            { 
                this.audioFiles = audioFiles[num];               

                if (this.audioFiles.length >= 1) {
                    //load the first on the list           
                    this.loadAudio(this.audioFiles[this.audioIndex], {'autoPlay': false }); 
                } else {
                
                    console.log("can't load audio listings for this slide")
                }

            } else {
                console.log("undefined?", audioFiles)
            }           
        },
        loadAudio(audio, settings) 
        {              
            this.audio = document.getElementById('audio');
            if (audio) {               
                this.audio.src = window.location.origin +"/"+ audio.path;                              
                this.audio.load();  
                this.audio.onloadedmetadata = () => {
                    this.onTimeUpdateListener();
                    if (settings.autoPlay === true) {
                        this.togglePlay();   
                    } else if (settings.alwaysPlay === true) {
                        this.audio.play();
                    }
                };
            } else {            
                console.log("no audio for this slide")
            }
        },        
        gotoAndPlayClientAudio(index) 
        {
            if (this.audioFiles[index]) {  
                if (this.audioFiles[index].path == this.audioFiles[this.audioIndex].path) 
                {                    
                    this.playBtn = false;  
                    this.audio.play();
                } else {
                    this.audioIndex = index;                
                    this.loadAudio(this.audioFiles[this.audioIndex], {'alwaysPlay': true });             
                }                                
            }          
        },
        goToAudio(index) 
        {
            //BROADCASTER ONLY
            if (this.audioFiles[index]) 
            {    
                if (this.audioFiles[index].path == this.audioFiles[this.audioIndex].path) 
                {
                    this.playBtn = false;  
                    this.audio.play();
                } else {                
                    this.audioIndex = index;
                    this.loadAudio(this.audioFiles[index], {'autoPlay': true });                    
                }

                //we will send this and let client play audio
                this.$root.$emit('goToAudio', this.audioIndex)                
            }          
        },
        play() {
            this.togglePlay();         
        },
        togglePlay() {        

            this.audio = document.getElementById('audio');
            if (this.audio.paused === false) {
                this.audio.pause();   
                this.playBtn = true;
                //we will send this through server and let client play audio
                this.$root.$emit('pauseAudio', this.audioIndex); 
            } else {
                this.audio.play();
                this.playBtn = false;  
                //we will send this through server and let client play audio
                this.$root.$emit('playAudio', this.audioIndex);
            }
        },        
        stopAudio() {
            this.playBtn = true;
            this.audio.pause();
        },        
        nextAudio() {
            if (this.audioIndex <  this.audioFiles.length -1 ) {
                this.audioIndex = this.audioIndex + 1;
                this.loadAudio(this.audioFiles[this.audioIndex], {'autoPlay': true });

                //we will send this through server and let client get the next audio
                this.$root.$emit('nextAudio', this.audioIndex)                    
            }            
        },
        prevAudio() {
            if (this.audioIndex >= 1 ) {
                this.audioIndex = this.audioIndex - 1;
                this.loadAudio(this.audioFiles[this.audioIndex], {'autoPlay': true }); 

                //we will send this through server and let client get the prev audio
                this.$root.$emit('prevAudio', this.audioIndex)                 
            }      
        },
        loadAndPlay(audio) {
            this.loadAudio(audio);
            this.togglePlay();  

            //we will send this through server and let client get the next audio
            this.$root.$emit('playAudio', this.audioIndex)                   
        },   

        seekAudio(e) {          

            if (this.$props.isBroadcaster == true) {

                let seekAudio = document.getElementById('audio');
                let offsetWidth = document.getElementById("seekObj").offsetWidth;

                const percent = e.offsetX / offsetWidth;
                seekAudio.currentTime = percent * seekAudio.duration;

                console.log(e.offsetX , offsetWidth, percent * seekAudio.duration);

                let trackTime = percent * seekAudio.duration

                console.log("track time", trackTime)
                // seekAudio.play();

                //we will send this through server and let client get the prev audio
                this.$root.$emit('seekAudio', {'index': this.audioIndex, 'trackTime': trackTime});

            }
        },   
        updateAudioTrackTime(trackTime) {

            let seekAudio = document.getElementById('audio');
            seekAudio.currentTime = trackTime;

            if (this.audio.paused === true) {
                this.audio.pause();                              
            } else {
                this.audio.play();   
            }
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
                currentTimeInfo.innerHTML = this.calculateCurrentValue(currentTime) + " / " + this.calculateCurrentValue(duration);
            }     
        },
        calculateCurrentValue(currentTime) {
            const currentMinute = parseInt(currentTime / 60) % 60;
            const currentSecondsLong = currentTime % 60;
            const currentSeconds = currentSecondsLong.toFixed();
            const currentTimeFormatted = `${currentMinute < 10 ? `0${currentMinute}` : currentMinute}:${
            currentSeconds < 10 ? `0${currentSeconds}` : currentSeconds}`;            
            return currentTimeFormatted;
        }
    }
 }
 </script>