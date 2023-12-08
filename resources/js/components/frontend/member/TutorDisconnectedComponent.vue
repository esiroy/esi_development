<template>

    <div class="container">

        <b-modal id="modal-tutor-disconnected"  :title="'Tutor Disconnected'" size="lg"             
            content-class="esi-modal" 
            :header-bg-variant="headerBgVariant" 
            hide-footer no-close-on-esc no-close-on-backdrop hide-header-close
            @shown="initSupportTimer" >
        
            <div class="text-primary text-center small">

                <div class="py-2">Please wait for your tutor to reconnect... </div> 

                <div v-if="this.supportCountdownTimer >= 1">
                    <div class="py-2">Technical support will assist you in {{ this.supportCountdownTimer }} seconds when you are not connected</div>
                    <b-spinner v-for="variant in variants" :variant="variant" :key="variant"></b-spinner>    
                </div>
                <div v-else-if="this.supportCountdownTimer == 0">
                    <div class="py-2">Opening Chat Support, please wait... </div>
                    <b-spinner v-for="variant in variants" :variant="variant" :key="variant"></b-spinner>    
                </div>
                <div v-else>
                    <div class="mt-3">
                        <b-button pill variant="primary" @click="contactCustomerSupport">
                            <span class="small">Click here to contact constumer support</span>
                        </b-button>
                    </div>     
                </div> 

            </div>           

        </b-modal>

        <b-modal id="modal-tutor-reconnected"  :title="'Tutor Disconnected'" size="lg"             
            content-class="esi-modal" 
            :header-bg-variant="headerBgVariant" 
            hide-footer no-close-on-esc no-close-on-backdrop hide-header-close>

            <div class="text-primary text-center small p-5">
                Tutor has connected, we will commence with your lesson
            </div>
            

        </b-modal>

    </div>

</template>

<script>
export default {
    name: "TutorDisconnectedComponent",
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
            variants: ['primary'],
            supportCountdownTimer: 15,
            headerBgVariant: 'lightblue',
            supportTimerInterval: false,
            timerSpeed: 1000,
        }
    }, 
    methods: {
        initSupportTimer() {
            this.supportCountdownTimer = 15,
            this.isSupportTimerStarted = false;
            this.startSupportCountdownTimer();
        },
        startSupportCountdownTimer() {
            if (this.isSupportTimerStarted == false) {
                this.isSupportTimerStarted = true;
                this.supportTimerInterval = setInterval(()=> {
                    this.supportCountdownTimer --;  
                    if (this.supportCountdownTimer < 0) {
                        this.stopSupportCountdownTimer();
                    }
                }, this.timerSpeed);  
            } else {
                this.stopSupportCountdownTimer();
            }
        },
        stopSupportCountdownTimer() {
            clearInterval(this.supportTimerInterval); 
        },
        showDisconnectedModal() {         
            this.isLessonTimeStarted = false;  
            this.$bvModal.show('modal-tutor-disconnected');
        },
        hideDisconnectedModal() {         
            this.isLessonTimeStarted = false;  
            this.$bvModal.hide('modal-tutor-disconnected');
        },          
        contactCustomerSupport() {
            this.hideDisconnectedModal();
            this.$root.$emit('openCustomerSupport');
        },
        showReconnected() {
            this.stopSupportCountdownTimer();
            this.$bvModal.show('modal-tutor-reconnected');
        },
        delayHideReconnected() {
            setTimeout(() => {            
                this.hideReconnected();
            }, 2000);  
        },      
        hideReconnected() {
            this.stopSupportCountdownTimer();
            this.$bvModal.hide('modal-tutor-reconnected');
        }
    }

}
</script>