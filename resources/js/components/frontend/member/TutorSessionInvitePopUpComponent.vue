<template>
    <div class="tutor-session-invite-container">

        <b-modal id="modal-callUser" title="Calling Student Please wait">
            <div> Calling Student... </div>
            <template #modal-footer>
                <div class="container text-center">                   
                    <span v-if="redialTimer == 8">Connecting....</span>
                    <span v-if="redialTimer == 7">Connecting...</span>
                    <span v-if="redialTimer == 6">Connecting..</span>
                    <span v-if="redialTimer <= 5">Redialing in {{ redialTimer }} seconds </span>
                                                       
                </div>
            </template>
        </b-modal>




        <b-modal id="modal-participants" :title="modalTitle">
            <div v-if="participants.length <= 0">
                <div id="waiting" class="text-center">
                    <b-spinner v-for="variant in variants" :variant="variant" :key="variant"></b-spinner>                
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
                        Please wait for your students to connect
                    </div>
                    <div v-else>
                        Lesson will start {{ lessonStartTimer }}
                    </div>
                </div>

                <div class="container text-center"  v-if="is_broadcaster == false">
                    <div v-if="participants.length <= 0">
                        Please wait for your tutor to connect
                    </div>
                    <div v-else>
                        Lesson will start {{ lessonStartTimer }}
                    </div>
                </div>

            </template>
        </b-modal>


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
        csrf_token: String,		
        api_token: String 
    },    
    data() {
        return {
            loaded: false,
            variants: ['primary'],
            modalTitle: null,
            participants: [],          

            timerSpeed: 1000,

            callRedialInterval: null,
            redialCounter: 3,
            redialTimer: 8, //5 seconds (countdown), (3 seconds to waiting time to connect)

            lessonStartInterval: null,
            lessonStartTimer: 5,
        }
    },
    mounted() {   


        if (this.is_broadcaster) {
            this.modalTitle = "Waiting for your student to join..";
        } else {
            this.modalTitle = "Waiting for your tutor to join..";
        }

        this.startRedialTimer();
    },
    methods: {


        showCallUserModal() {
            this.$bvModal.show('modal-callUser');


            this.startRedialTimer();
        },
        hideCallUserModal() {
            this.$bvModal.hide('modal-callUser');             
        },

        showWaitingListModal() {       
            this.$bvModal.show('modal-participants');
        },
        hideWaitingListModal() {       
            this.$bvModal.hide('modal-participants');             
        },

        redial() {

        },
        /* Lesson Timers */      
        startLessonStartTimer() {
            this.lessonStartTimer   = 5;          
            this.lessonStartInterval = setInterval(this.updateRedialTimer, this.timerSpeed);
        },
        stopLessonTimer() {
            this.lessonStartTimer   = 5;       
            clearInterval(this.lessonStartInterval);
        },
        updateRedialTimer() {   
            this.lessonStartTimer--;
            this.$forceUpdate();
            if (this.lessonStartTimer < 1) {
                this.hideWaitingListModal();
                this.stopLessonTimer();                
            }
        },  
        /* Redial Timers*/
        startRedialTimer() {
            this.callRedialInterval = setInterval(this.updateRedialTimer, this.timerSpeed);        
        },
        stopRedialTimer() {
            clearInterval(this.callRedialInterval);
        },
        resetRedialTimer() {
            this.redialCounter++;
            this.redialTimer = 5;            
        },
        updateRedialTimer() {   
            this.redialTimer--;
            this.$forceUpdate();

            if (this.redialTimer < 1) {

                console.log("test emit redial");
                
                this.$root.$emit('redialUser', this.participants)

                this.stopRedialTimer();
                this.resetRedialTimer();               
            }
        }, 
        cancelInvite() {
       
        },
        addTutor() {
        
        },
        addParticipants(user) {
            if (this.participants.length == 0) {

                this.participants.push(user);
                this.startLessonStartTimer();
                
            } else {
                let result = this.participants.find(participant => participant.userid === user.userid);
                if (result) {
                    //alert ("already on the list")
                    console.log("user is already on the list, commence the lesson")
                }
            }
        },
        removeParticipants(user) {
            for (var i in this.participants) {
                if (this.participants[i].userid === user.userid) {
                    console.log(this.participants[i]);  
                    this.participants.splice(i)
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