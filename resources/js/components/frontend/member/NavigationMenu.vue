<template>

    <nav class="navbar navbar-expand-md navbar-light shadow-sm bg-darkblue" v-if="isNavMenuVisible == true">
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
            <ul class="navbar-nav" v-if="this.isNavMenuDisabled == false">
                
                <li class="pr-4 pt-2" id="memberTimerButtonContainer" v-if="this.$props.is_broadcaster == true && isNavMenuDisabled == false">
                    <button id="memberTimerButton" class="btn btn-md btn-success small" @click="triggerShowMiniTaskTimer()">
                        <b-icon icon="alarm" aria-hidden="true"></b-icon> 
                        <span class="small">Set Countdown Timer</span>
                    </button>
                </li>

                <li class="pr-4" id="memberTimerContainer">
                    <div class="badge d-flex align-items-center border rounded-pill" v-if="isNavMenuDisabled == false">
                        <span id="memberTimer" class="text-white font-weight-bold h4 mt-2 px-3"  
                            :class="{'text-danger': isLessThan5Seconds}">
                            {{ miniTimer }}
                        </span>  
                    </div>                            
                </li>
            
                <li class="pr-2" v-if="this.$props.is_broadcaster == false">
                    <a class="navbar-brand" href="#" @click="triggerFloatinChatBox">
                        <i class="fas fa-headset  fa-2x text-white"></i>
                    </a>
                </li>

                <li class="pr-4 pt-2" id="countDownTimerContainer">

                    <span class="text-white font-weight-bold h2 mt-3" :class="{'text-danger': isTimerNegative}" id="countDownTimer">
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
                        <i class="fas fa-sign-in-alt fa-2x text-white" @click="tiggerConfirmEndSession()"></i>    
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

                isNavMenuVisible: false,
                isNavMenuDisabled: false,

                countdownTimer: "00:00:00",
                miniTimer:  "00:00:00",
            }            
        },
        mounted() {


            this.showNavigationMenu(true);
            this.enableNavigationMenu(true);
            
        },
        computed: {
            isTimerNegative() {
                return this.countdownTimer.charAt(0) === '-';
            },
            /*
            isLessThan5Seconds() {
                const timerString = this.miniTimer.toString(); // Convert to string
                const seconds = parseInt(timerString.split(':')[2]);
                return (seconds > 0 && seconds <= 5);   
            },*/    
            isLessThan5Seconds() {
                const timerString = this.miniTimer.toString(); // Convert to string
                const [hours, minutes, seconds] = timerString.split(':').map(Number);

                return (hours === 0 && minutes === 0 && seconds > 0 && seconds <= 5);
            }                    
        },  
        methods: {
            miniTimerUpdate(time) {               
                this.miniTimer = time;
            },
            showNavigationMenu() {            
                this.isNavMenuVisible = true;
            },
            hideNavigationMenu() {
                this.isNavMenuVisible = false;
            },

            enableNavigationMenu() {          
                this.isNavMenuDisabled = false;
            },
            disableNavigationMenu() {          
                this.isNavMenuDisabled = true;
            },
         
         
            //Start Lesson
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
            tiggerConfirmEndSession() {
                this.$root.$emit('tiggerConfirmEndSession')        
            },             

            //Timer
            updateTimer(countdownTimer) {
                this.countdownTimer = countdownTimer;
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