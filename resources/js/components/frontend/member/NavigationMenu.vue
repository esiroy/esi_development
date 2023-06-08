<template>

    <nav class="navbar navbar-expand-md navbar-light shadow-sm bg-darkblue">
        <div class="container-fluid">

            
            <audio id="alarmAudio">
                <source src="" type="audio/mp3">
            </audio>

            <!--[start] LOGO -->

            <ul class="navbar-nav mr-auto">
                <li class="pr-4" v-if="this.$props.is_broadcaster == true">                        
                    <a class="navbar-brand" href="/admin"><i class="fas fa-home fa-2x text-white"></i></a>
                </li>
                <li class="pr-4" v-if="this.$props.is_broadcaster == false">                        
                    <a class="navbar-brand" href="/"><i class="fas fa-home fa-2x text-white"></i></a>
                </li>                
            </ul>    
             <!--[END] LOGO -->          
            
            
            <!--[start] RIGHT NAVIGATION -->
            <ul class="navbar-nav" v-if="this.isLessonCompleted == false">
                
                <li class="pr-4 pt-2" id="memberTimerButtonContainer" v-if="this.$props.is_broadcaster == true && isLessonCompleted == false">
                    <button id="memberTimerButton" class="btn btn-md btn-success small" @click="triggerShowMiniTaskTimer()">
                        <b-icon icon="alarm" aria-hidden="true"></b-icon> 
                        <span class="small">Set Countdown Timer</span>
                    </button>
                </li>

                <li class="pr-4" id="memberTimerContainer">
                    <div class="badge d-flex align-items-center border rounded-pill" v-if="isLessonCompleted == false">
                        <span id="memberTimer" class="text-white font-weight-bold h4 mt-2 px-3" >
                            0:00
                        </span>  
                    </div>                            
                </li>
            
                <li class="pr-2" v-if="this.$props.is_broadcaster == false">
                    <a class="navbar-brand" href="#" @click="triggerFloatinChatBox">
                        <i class="fas fa-headset  fa-2x text-white"></i>
                    </a>
                </li>

                <li class="pr-4 pt-2" id="countDownTimerContainer">
                    <span class="text-white font-weight-bold h2 mt-3" id="countDownTimer">
                        {{ countdownTimer }}
                    </span>                       
                </li>
                <li class="pr-4 pt-2" id="startSessionContainer" v-if="this.$props.is_broadcaster == true && this.isTimerStarted == false">
                    <button id="startSession" class="btn btn-md btn-success small" @click="tiggerStartSession()">                                
                        <b-icon icon="play" aria-hidden="true"></b-icon> 
                        <span class="small">Start Session</span>
                    </button>
                </li>
                <li class="pr-4 pt-2" id="endSessionContainer" v-if="this.$props.is_broadcaster == true && this.isTimerStarted == true">
                    <button class="btn btn-sm">
                        <i class="fas fa-sign-in-alt fa-2x text-white" @click="tiggerEndSession()"></i>    
                    </button>
                </li> 
            </ul>
            <!--[end] RIGHT NAVIGATION  -->
        </div>
        
    </nav>
</template>

<script>
    export default {

        name: "NavigationMenu",
        components: {},
        props: {
            is_broadcaster: Boolean,            
            is_lesson_completed:Boolean,
            csrf_token: String,		
            api_token: String,
            reservation: Object,       
            user_image: String,
            
        },
        data() {
            return {
                isTimerStarted: false,
                isUserAbsent: false,
                isLessonCompleted: false,
                countdownTimer: "00:00:00",
            }            
        },
        mounted() {

        },
        methods: {
            startTimer() {
                this.isTimerStarted = true;
                console.log(this.isTimerStarted, "timer started!")
            },
            stopTimer() {
                this.isTimerStarted = false;
            },

            tiggerStartSession() {
                this.$root.$emit('tiggerStartSession')        
            },         
            tiggerEndSession() {
                this.$root.$emit('tiggerEndSession')        
            },             

            updateTimer(countdownTimer) {
                this.countdownTimer = countdownTimer;
            },
            updateLessonStatus(isLessonCompleted) {
                this.isLessonCompleted = isLessonCompleted; //true, false
            },

            triggerFloatinChatBox() {
                this.$root.$emit('triggerFloatinChatBox')             
            },
            triggerShowMiniTaskTimer() {
                this.$root.$emit('triggerShowMiniTaskTimer')   
            }
        }
    }
 
</script>