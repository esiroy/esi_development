<template>
    <div class="tutor-session-invite-container">

      
        <!-- 
            /*************************************************** 
                            CALL USER MODAL
            ***************************************************/
        -->
        <b-modal id="modal-callUser"  :title="'Calling Student Please wait'" content-class="esi-modal" :header-bg-variant="headerBgVariant" no-close-on-esc no-close-on-backdrop hide-header-close>
             <div v-if="timeLimitExpired == true">
                <div>Time is up the student did not show on the lesson.</div>
                <div>This lesson is counted please slide the button to confirm.</div>
            </div>
            <div v-else-if="callAttemptFailed == false">
            
                <div class="text-center">                    
                    <span class="text-primary small">
                        Sending student a lesson invitation, please wait
                    </span>
                </div>
            </div>           
            <div v-else-if="callAttemptFailed == true">
                We called several times but the user did not answer
                Would you like to mark this student absent or a no show?
            </div>

            <template #modal-footer>
                <div class="container text-center">
                    <div v-if="callAttemptFailed == true || timeLimitExpired == true">                
                       <b-button variant="primary">Yes, I Confirm</b-button>
                    </div>                    
                    <div v-else-if="callAttemptFailed == false">
                        <span v-if="redialTimer > 5 ">Connecting....</span>
                        <span v-if="redialTimer >= 1 && redialTimer <= 5">Redialing in {{ redialTimer }} seconds </span>
                        <div class="redial-wrapper" v-if="redialTimer == 0 "> 
                            Please wait while we are redialing ... 
                            <div class="attemp-container">(attempt {{ redialCounter }}) </div>
                        </div>
                    </div>                                                       
                </div>
            </template>
        </b-modal>



        <!-- 
            /*************************************************** 
                            CALL WAITING FOR PARTICIPANTS
            ***************************************************/
        -->
        <b-modal id="modal-participants" :title="modalTitle" content-class="esi-modal" :header-bg-variant="headerBgVariant" no-close-on-esc no-close-on-backdrop hide-header-close>

            <div class="modal-body" v-if="participants.length <= 0">
                <div id="waiting" class="text-center">

                    <div v-if="waitingTimer <= 0"> 
                        <span class="text-primary small">
                            Sending student a lesson another invitation, please wait
                        </span>
                    </div>
                    <div v-else>

                        <div class="pb-3 alert alert-primar" role="alert">
                        
                            <div class="text-primary small" v-if="is_broadcaster == true">  
                                <div v-if="isDisconnected == false">                           
                                    Invite recieved, Please wait for the student to accept and connect...                            
                                </div>
                                <div v-if="isDisconnected == true">  
                                    Please wait for the student to reconnect... 
                                    <b-spinner v-for="variant in variants" :variant="variant" :key="variant"></b-spinner>     
                                </div> 
                            </div>

                            <span class="text-primary small" v-if="is_broadcaster == false">
                                <div v-if="isDisconnected == false">                           
                                    
                                    <div class="py-2">Please wait for your tutor to connect... </div>

                                    <b-spinner v-for="variant in variants" :variant="variant" :key="variant"></b-spinner>                 
                                </div>

                                <div v-if="isDisconnected == true">

                                    Please wait for the tutor to reconnect...  

                                    <div v-if="showTechnicalSupportLink == false">                                        
                                        <div class="py-2">Technical support will assist you in {{ this.supportCountdownTimer }} when you are not connected</div>
                                        <b-spinner v-for="variant in variants" :variant="variant" :key="variant"></b-spinner>    
                                    </div>

                                    <div class="mt-3" v-else-if="showTechnicalSupportLink == true">
                                        <b-button pill variant="primary" @click="contactCustomerSupport">
                                            <span class="small">Click here to contact constumer support</span>
                                        </b-button>
                                    </div>

                                </div>
                            </span>          
                        </div>
                                              
                    </div>
                </div>
            </div>
            <div v-else-if="participants.length >= 1">

                <div  class="text-center" v-for="(participant, index) in participants" :key="index">             
                    <div class="text-center">
                        {{ participant.firstname }} {{ participant.lastname }}
                    </div>
                    <div class="img-fluid">
                        <img :src="baseURL(participant.image)" class="participant-image">
                    </div>
                </div>
            </div>

            <template #modal-footer>
                <div class="container text-center"  v-if="is_broadcaster == true">
                    <div v-if="participants.length <= 0">                        
                        <div v-if="waitingTimer <= 0"> Redialing, Please wait </div>                        
                        <div v-else> Redialing in {{ waitingTimer }} </div>
                    </div>
                    <div v-else>
                        Lesson will start {{ lessonStartTimer }}
                    </div>
                </div>

                <div class="container text-center"  v-if="is_broadcaster == false">
                    <div v-if="participants.length <= 0">
                       
                        <div v-if="isDisconnected == false">                           
                            Please wait for your tutor to connect...              
                        </div>
                        <div v-if="isDisconnected == true">                                     
                            Please wait for the tutor to reconnect...  
                        </div>

                    </div>
                    <div v-else>
                        Lesson will start {{ lessonStartTimer }}
                    </div>
                </div>

            </template>
        </b-modal>


        <!-- 
            /*************************************************** 
                        LESSON EXPIRED MODAL
            ***************************************************/
        -->


    </div>
   
</template>

<style scoped>

    .participant-image {
        max-width:  175px;
        border-radius: 100px;
    }

</style>

<script>
export default {
    name: "TutorSessionInvitePopUpComponent",
    components: {},
    props: {
        is_broadcaster: Boolean,	
        lesson_history: Object,
        reservation: Object,
        csrf_token: String,		
        api_token: String,      
    },    
    data() {
        return {
            headerBgVariant: 'lightblue',

            loaded: false,
            variants: ['primary'],
            modalTitle: null,
            participants: [],          

            timerSpeed: 1000,

            callAttemptFailed: false,
            timeLimitExpired: false,

            callRedialInterval: null,            
            redialTimer: 8, //5 seconds (countdown), (3 seconds to waiting time to connect)
            redialCounter: 10,

            lessonStartInterval: null,
            lessonStartTimer: 5,

            /******** WAITING TIMER  */

            waitingInterval: null,
            waitingTimer: 15,
            waitingRedialCounter: 10,

            specificDate: null,
            expiredLessonDate: null,
            currentDate: null,


            //Support link timer (15 seconds)
            isSupportTimerStarted: false,
            supportTimerInterval: false,
            supportCountdownTimer: 15,


            //Determin if user has been disconnected
            isDisconnected: false,

            showTechnicalSupportLink: false,

        }
    },
    mounted() { 

        this.specificDate = new Date( this.reservation.lesson_time);
        // Add 15 minutes to the specific date and time
        this.specificDate.setTime(this.specificDate.getTime() + (15 * 60 * 1000));

        this.expiredLessonDate = new Date( this.reservation.lesson_time);
        
        // Add 30 minutes to the specific date and time
        this.expiredLessonDate.setTime(this.specificDate.getTime() + (30 * 60 * 1000));

        // Get the current date and time
        this.currentDate = new Date();

        if (this.is_broadcaster) {
            this.modalTitle = "Waiting for your student to join..";
        } else {
            this.modalTitle = "Waiting for your tutor to join..";
        }

        
    },
    methods: {

        contactCustomerSupport() {

            this.$root.$emit('openCustomerSupport');
        },
        /***************************************************** 
                Support Countdown timer
        *****************************************************/
        startSupportCountdownTimer() {

            this.resetSupportCountdownTimer();

            this.showTechnicalSupportLink = false;

            if (this.isSupportTimerStarted == false) {
                this.isSupportTimerStarted = true;
            } else {
            
            }

            if (this.isSupportTimerStarted == true) {
                this.supportTimerInterval = setInterval(()=> {
                    this.supportCountdownTimer --;  
                    if (this.supportCountdownTimer < 0) {  
                        //@todo: trigger customer support module
                        this.stopSupportCountdownTimer();
                        this.showTechnicalSupportLink = true;
                    }
                }, this.timerSpeed);  
            }
        },
        resetSupportCountdownTimer() {
            this.supportCountdownTimer = 15;
        },        
        stopSupportCountdownTimer() {

            console.log("stop support timer triggered...")
            this.isSupportTimerStarted = false;
            clearInterval(this.supportTimerInterval); 
        },


        /***************************************************** 
                CALL USER MODAL 
        *****************************************************/
        showCallUserModal() {

            console.log("show call user modal");
            
            this.$bvModal.show('modal-callUser');

            // Compare the two dates to see if the current time is after 15 minutes from the specific date and time
            if (this.$props.lesson_history == null) {
                //User has no lesson history (the lesson was not started after 15 min, we will promted time limit exceeded)
                if (this.currentDate.getTime() > this.specificDate.getTime()) {
                    this.timeLimitExpired = true;
                    this.stopRedialTimer();
                    console.log('The current time is after 15 minutes from the specific date and time.');
                } else if (this.currentDate.getTime() > this.expiredLessonDate.getTime()) {

                    alert ("expired")
                } else {
                    this.startRedialTimer();
                    console.log('The current time is not yet after 15 minutes from the specific date and time.');
                }
            } else {

                if (this.currentDate.getTime() > this.expiredLessonDate.getTime()) {
 
                    console.log("expired");
                    this.stopRedialTimer();
                } else {
                
                    console.log('The Timer has started, so we need to call till end of time');
                    this.startRedialTimer();                
                }
            }
          
        },
        hideCallUserModal() {
            this.$bvModal.hide('modal-callUser');  
            this.resetRedialTimer();   
            this.stopRedialTimer();        
        },
        /***************************************************** 
                SHOW WAITING MODAL 
        *****************************************************/
        showWaitingListModal() {       

            if (this.$props.lesson_history == null) {
                //User has no lesson history (the lesson was not started after 15 min, we will promted time limit exceeded)
                if (this.currentDate.getTime() > this.specificDate.getTime()) 
                {
                    this.timeLimitExpired = true;
                    this.$bvModal.show('modal-callUser');
                    this.stopRedialTimer();
                    this.stopWaitingTimer();

                    console.log('The current time is after 15 minutes from the specific date and time.');

                } else {
                    this.resetLessonTimer();   
                    this.startWaitingTimer();
                    this.hideCallUserModal();
                    this.$bvModal.show('modal-participants');
                }
            } else {

                // check if more than 30 minutes ()
                if (this.currentDate.getTime() > this.expiredLessonDate.getTime()) {
                    console.log("expired")                    
                } else {
                    this.resetLessonTimer();   
                    this.startWaitingTimer();
                    this.hideCallUserModal();
                    this.$bvModal.show('modal-participants');
                }
            }


        },
        hideWaitingListModal() {       
            this.$bvModal.hide('modal-participants');             
            this.resetLessonTimer();
            this.stopRedialTimer();
            this.stopLessonTimer();
            console.log("hide and stop redialing")
        },

        /***************************************************** 
                    LESSON COMMENCING MODAL
        *****************************************************/     
        resetLessonTimer() {
             this.lessonStartTimer = 5;
        },        
        startLessonStartTimer() {
            this.lessonStartInterval = setInterval(this.updateLessonTimer, this.timerSpeed);
            this.stopWaitingTimer();
        },
        stopLessonTimer() {
            clearInterval(this.lessonStartInterval);
        },
        updateLessonTimer() {   
            this.lessonStartTimer--;
            this.$forceUpdate();
            if (this.lessonStartTimer < 1) {
                this.hideWaitingListModal();
                this.stopLessonTimer();  
                this.stopWaitingTimer();              
            }
        },


        /* Redial Timers*/
        resetRedialTimer() {
            this.redialTimer = 8;            
        },        
        stopRedialTimer() {
           clearInterval(this.callRedialInterval);
        },        
        startRedialTimer() {

            console.log("start Redial Timer");

            this.callRedialInterval = setInterval(() => {
                this.redialTimer--;
                if (this.redialTimer < 0) {

                    this.$root.$emit('redialUser', this.participants);   


                    this.resetRedialTimer();
                    // Compare the two dates to see if the current time is after 15 minutes from the specific date and time
                    if (this.currentDate.getTime() > this.specificDate.getTime()) {
                        console.log('The current time is after 15 minutes from the specific date and time.');                        
                        this.timeLimitExpired = true;
                        this.stopRedialTimer();
                    } else {
                   
                        console.log('The current time is not yet after 15 minutes from the specific date and time.');
                    }

                    /*
                    this.redialCounter--; //
                    if (this.redialCounter <= 0) {

                        this.callAttemptFailed = true;
                        this.stopRedialTimer();       
                    } 
                    */                   
                }
                //console.log(this.redialTimer);

            }, this.timerSpeed)   
        },


        /***************************************************** 
                WAITING TIMER (30 seconds)   
        *****************************************************/
        resetWaitingTimer() {
            this.waitingTimer = 30;            
        },
        stopWaitingTimer() {
            clearInterval(this.waitingInterval);
        },        
        startWaitingTimer() {

            this.stopWaitingTimer();

            if (this.$props.is_broadcaster == true) {

               

                this.waitingInterval = setInterval(() => {
                    this.waitingTimer--;
                    if (this.waitingTimer < 0) {

                        this.$root.$emit('redialUser', this.participants);   
                        this.resetWaitingTimer();
                        this.waitingRedialCounter--; 

                        // Compare the two dates to see if the current time is after 15 minutes from the specific date and time
                        if (this.currentDate.getTime() > this.specificDate.getTime()) {
                            console.log('The current time is after 15 minutes from the specific date and time.');
                            this.stopWaitingTimer();       

                        } else {
                            console.log('The current time is not yet after 15 minutes from the specific date and time.');
                        }      
                    }
                    //console.log(this.waitingTimer);
                }, this.timerSpeed)       

            } else {
            
                console.log("waiting timer of user is in progress")
            }     
        },


        /***************************************************** 
                    PARTICIPANTS
        *****************************************************/

        addParticipants(user) {

            //console.log("participant added", user, this.participants.length);

            this.showWaitingListModal();

            this.isDisconnected = false;

            if (this.participants.length == 0) {

                this.participants.push(user);
                this.startLessonStartTimer();
                this.stopWaitingTimer();
                
            } else {
                let result = this.participants.find(participant => participant.userid === user.userid);
                if (result) {
                    //alert ("already on the list")
                    //console.log("user is already on the list, commence the lesson")
                    this.stopWaitingTimer();
                    this.resetWaitingTimer();
                }
            }
        },
        removeParticipants(user) {

            for (var i in this.participants) {

                if (this.participants[i].userid === user.userid) {

                    console.log(this.participants[i], "- left the session");  
                    this.participants.splice(i);
                    this.isDisconnected = true;
                    this.resetWaitingTimer();
                    this.stopWaitingTimer();

                    /**
                    ** @date: MARCH: 28. 2023
                    ** @todo: Start Support Countdown For members only 
                    **/
                    this.resetSupportCountdownTimer();
                    this.startSupportCountdownTimer();

                    this.$forceUpdate();
                    break;
                }
            }
        },
        baseURL(path) {
            return window.location.origin + path
        }
        
    }    
}
</script>