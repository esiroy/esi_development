<template>
    <div class="tutor-session-invite-container">


        <b-modal id="modal-participants" :title="this.modalTitle">

            <div class="text-center" v-for="(participant, index) in participants" :key="index">
             
                {{ participant }}

            </div>


            <template #modal-footer>
                <div class="container text-center">
                    <b-button variant="danger" @click="cancelInvite">Cancel Lesson Request</b-button>
                </div>
            </template>
        </b-modal>
    </div>
   
</template>

<style scoped>

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
            modalTitle: null,
            participants: [],
        }
    },
    mounted() {    
        if (this.is_broadcaster) {

            this.modalTitle = "Waiting for your student to join..";

        } else {

            this.modalTitle = "Waiting for your tutor to join..";
        }

        this.startRedialCountdown();
    },
    methods: {


        showCallingModal() {
        
        },
        showWaitingListModal() {       
            this.$bvModal.show('modal-participants');
        },
        hideWaitingListModal() {
       
            this.$bvModal.hide('modal-participants');             
        },
        cancelInvite() {
       
        },
        addTutor() {
        
        },
        addParticipants(user) {
            if (this.participants.length == 0) {            
                this.participants.push(user);            
            } else {
                let result = this.participants.find(participant => participant.userid === user.userid);
                if (result) {
                    //alert ("already on the list")
                }
            }            
        },
        startRedialCountdown() {

        }

    }    
}
</script>