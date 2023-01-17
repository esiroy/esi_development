<template>
    <li class="pr-4 pt-2" id="countDownTimerContainer">
        <span class="text-white font-weight-bold h2 mt-3" id="countDownTimer"></span>                       
    </li>
</template>        


<script>
 export default {
    name: "CountDownTimerComponent",
    props: {
        isBroadcaster: {
            type: [Boolean],
            required: true        
        },       
    },
    data() 
    {
        return {    
            myIntervalTimer: null,
            timerSpeed: 1000,
            timer: 1800, //30 minutes = 1800 seconds
        }
    },
    methods: {      
        test() {
            alert("test");
        }, 
        setTimer() {
            var elem = document.querySelector('#countDownTimer');
            elem.innerHTML = this.getTime();           
        },
        startTimer() {
            this.myIntervalTimer = setInterval(this.updateTimer, this.timerSpeed);
        },
        updateTimer() {            
            this.setTimer();
            this.timer--;
        },    
        stopTimer() {
            clearInterval(this.myIntervalTimer); 
        }
    }
 }
 </script>