<template>
    
     <div class="timer-holder">
         <b-modal id="modal-member-timer" content-class="esi-modal" title="Set Countdown Timer" :header-bg-variant="headerBgVariant" header-text-variant="white" size="lg" hide-footer no-close-on-esc no-close-on-backdrop>        

            <div class="container">         
                <div class="border border-dark rounded">
                    <div class="timer-style">
                        {{ timeRemaining }}<span class="milliseconds">.{{milliseconds}}</span>
                    </div>               
                </div>
            </div>

            <div class="container">
                <div class="row" v-if="isTimerSet == false">
                    <div class="col-9 pr-0 mr-0">

                        <div class="d-flex mt-3">
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(5)">5</button>
                            </div>                
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(6)">6</button>
                            </div>
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(7)">7</button>
                            </div>
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(8)">8</button>
                            </div>
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(9)">9</button>
                            </div>
                        </div>
                        
                        <div class="d-flex mt-2">  
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(0)">0</button>
                            </div>                             
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(1)">1</button>
                            </div>
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(2)">2</button>
                            </div>
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(3)">3</button>
                            </div>
                            <div class="flex-fill mr-1">
                                <button class="btn btn-success w-100 py-2" @click="appendDigit(4)">4</button>
                            </div>                   
                        </div>                    
                    </div>

                    <div class="col-3 ml-0 pl-0">
                        <div class="d-flex mt-3">
                            <!-- SET TIMER -->
                            <div class="flex-fill mr-1">
                                <button class="btn btn-primary w-100 py-2 small" @click="setTimer()">SET</button>
                            </div> 
                        </div>
                        <div class="d-flex mt-2">
                            <!-- CLEAR TIMER -->
                            <div class="flex-fill mr-1">
                                <button class="btn btn-secondary w-100 py-2 small" @click="clearTimer()">CLEAR</button>
                            </div>                      
                        </div>    
                    
                    </div>
                </div>

                <div class="row" v-else>
                    <div class="col-12">
                        <div class="d-flex mt-3">
                            <div class="flex-fill mr-1" v-if="this.isTimerStarted == false">
                                <button class="btn btn-primary w-100 py-2" @click="startCountdown()">Start</button>
                            </div>                
                            <div class="flex-fill" v-else>
                                <button class="btn btn-danger w-100 py-2" @click="stopCountdown()">RESET</button>
                            </div>
                        </div> 
                    </div>

                </div>
            </div>



         </b-modal>
     </div>

</template>

<script>
export default {

    name: "MemberLessonTimerComponent",
    props: {
    },
    data() {
        return {           
            headerBgVariant: 'lightblue',

            timeRemaining: 0,             
            numbers: [],            
            timer: null,         

            //hands of time
            hours: null,
            minutes: null,
            seconds: null,
            milliseconds: '00',

            isTimerSet: false,
            isTimerStarted: false,
            
        };      
    },
    created() {

    },
    mounted() {
    
         this.timeRemaining = this.prependWithColons(this.timeRemaining);

    },
    methods: {
        appendDigit(num) {
            this.numbers.push(num);
            let numbers             = this.numbers.join('');
            this.timeRemaining      = this.prependWithColons(numbers);
        },
        prependWithColons(number) {
            var paddedNumber = ("000000" + number).slice(-6);
            var result = "";
            for (var i = 0; i < paddedNumber.length; i += 2) {
                result += paddedNumber.substring(i, i + 2) + ":";
            }
            return result.slice(0, -1);
        },
        setTimer() {
            if (this.numbers.length >= 1) {
                //Convert any time to standard clock time
                this.isTimerSet = true; 
                this.timeRemaining = this.converToTime(this.timeRemaining); 
            }
        },
        setTimeRemaining(timeRemaining) {
            this.isTimerSet = true; 
            this.timeRemaining = this.converToTime(timeRemaining);
        },
        startCountdown() {
            if (this.isTimerStarted == false) {            
                this.isTimerStarted = true;        
                this.$root.$emit('startMemberTimer', this.timeRemaining)     
                this.startCountdownTimer();                
            }
        },
        stopCountdown() {
            if (this.isTimerStarted == true) {            
                this.isTimerStarted = false;        
                this.$root.$emit('stoptMemberTimer', this.timeRemaining);        
                this.stopCountdownTimer();
            }            
        },
        converToTime(num) {
            const timeString = num;
            const [hours, minutes, seconds] = timeString.split(':').map(parseFloat);
            let totalSeconds = (hours * 3600) + (minutes * 60) + seconds;
            const totalHours = Math.floor(totalSeconds / 3600);
            totalSeconds %= 3600;
            const totalMinutes = Math.floor(totalSeconds / 60);
            const remainingSeconds = totalSeconds % 60;
            const correctedMinutes = totalMinutes + Math.floor(remainingSeconds / 60);
            const correctedSeconds = remainingSeconds % 60;

            //store to set target time
            this.hours = totalHours;
            this.minutes = correctedMinutes;
            this.seconds = correctedSeconds;

            //padd the time
            const paddedHours = totalHours.toString().padStart(2, '0');
            const paddedMinutes = correctedMinutes.toString().padStart(2, '0');
            const paddedSeconds = correctedSeconds.toString().padStart(2, '0');
            return (`${paddedHours}:${paddedMinutes}:${paddedSeconds}`); 
        },
        clearTimer() {
            this.numbers = []; //clear numbers 
            this.timeRemaining = 0;
            this.timeRemaining = this.prependWithColons(0);            
            this.hours = null;
            this.minutes = null;
            this.seconds = null;
            this.milliseconds = '00';

            this.updateMemberTimer(this.timeRemaining)
        },       
        showTimerControlModal() {
            this.$bvModal.show('modal-member-timer');
        },
        hideTimerControlModal() {
            this.$bvModal.hide('modal-member-timer');
        },
        stopCountdownTimer() {
            this.isTimerSet = false;
            clearInterval(this.timer);
            this.clearTimer();        
        },
        startCountdownTimer() 
        {
            const endTime = new Date().getTime() + (this.hours * 3600 + this.minutes * 60 + this.seconds) * 1000

            this.timer = setInterval(() => {
                const timeRemaining = endTime - new Date().getTime()
                if (timeRemaining > 0) {
                    this.hours = Math.floor(timeRemaining / (60 * 60 * 1000))
                    this.minutes = Math.floor((timeRemaining / (60 * 1000)) % 60)
                    this.seconds = Math.floor((timeRemaining / 1000) % 60)
                    this.milliseconds = Math.floor((timeRemaining % 1000) / 10);

                    //add paddings
                    const paddedHours = this.hours.toString().padStart(2, '0');
                    const paddedMinutes = this.minutes.toString().padStart(2, '0');
                    const paddedSeconds = this.seconds.toString().padStart(2, '0');
                    this.milliseconds = this.milliseconds.toString().padStart(2, '0');

                    this.timeRemaining = (`${paddedHours}:${paddedMinutes}:${paddedSeconds}`); // Output: 25:31:30
                    this.updateMemberTimer(this.timeRemaining)

                } else {

                    this.$root.$emit('playAlarmAudio', {'path': 'mp3/alarm.mp3'});        

                    clearInterval(this.timer);
                    this.clearTimer();
                }
            }, 10);          
        },
        updateMemberTimer(time) {
            $("#memberTimer").html(time +"."+ this.milliseconds)
        }
        
    }

}
</script>


<style scoped>
    .timer-style {
        font-size: 42pt;
        text-align: center;
        padding: 0px;
        margin: 0px;
        line-height: 0.9em;
        font-weight: bold;
    }
    .milliseconds {
        font-size: 22pt;
    }
</style>
