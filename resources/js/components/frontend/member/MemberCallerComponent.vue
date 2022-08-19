<script>
    
    import io from "socket.io-client";    
    import Moment from "moment-timezone";
    import MemberLessonSliderComponent from "./MemberLessonSliderComponent.vue";


    export default {
        name: "Member-Caller-Component",
        components: {    
            MemberLessonSliderComponent
        },        
        props: 
        {
            csrf_token: String,		
            api_token: String,

            isBroadcaster: {
                type: [Boolean],
                required: true        
            },
            userInfo: {
                type: [Object, String],
                required: true
            },     
            canvasServer: {
                type: [String],
                required: true        
            },           
            memberInfo: {
                type: [Object, String],
                required: true
            },                   
        },
        data() {
            return {
                user: null,
                users: null,
                tutor: null,
                reservation: null,

                now         : null,
                currentTime: null,
                lessonStartTime: null,
                lessonEndTime: null,

                //lesson Options
                lessonSelected: 'a',
                lessonOptions: [
                    { value: null, text: 'Please select some item' },
                    { value: 'a', text: 'This is First option' },
                    { value: 'b', text: 'Default Selected Option' },
                    { value: 'c', text: 'This is another option' },
                    { value: 'd', text: 'This one is disabled', disabled: true }
                ],

                //caller
                caller: null,
                recipient: null,
                callReservation: null,

                //loader
                sliderLoaded: false,

                channelid: null,
                selectedLessonID: null,


            }
        },
        mounted() {

            this.socket = io.connect(this.canvasServer);

            //Transfer the object to the window
            window.memberCallerComponent = this;

            //console.log(this.memberInfo);

            //Register the member Info
            this.user = {
                userid: this.memberInfo.user_id,
                username: this.userInfo.username,
                nickname: this.userInfo.nickname,
                channelid: this.channelid,                
                type: this.userInfo.user_type,
                status: "ONLINE",
            }  
       
            this.socket.emit('REGISTER', this.user); 


            //update the list
            this.socket.on('update_user_list', users => {
                this.updateUserList(users); 
            });

            this.socket.on("ACCEPT_CALL", (data) =>  {

                if (this.user.userid == data.tutorData.userid ) 
                {    
                    this.$bvModal.HIDE('modal-call-alert');   

                    this.caller              = data.caller;                        
                    this.recipient           = data.recipient;
                    this.callReservation     = data.reservationData;

                } 
                console.log(data)
            });

            this.socket.on("CALL_USER", (data) =>  
            {
                if (this.user.userid == data.recipient.userid ) 
                {       
                    this.caller              = data.caller;                        
                    this.recipient           = data.recipient;
                    this.callReservation     = data.reservationData;
                    this.$bvModal.show('modal-call-alert');  
                    console.log("call user");                    
                }                
            });


            this.socket.on("START_SLIDER", (data) =>  
            {
                this.sliderLoaded = false;
                this.channelid = data.reservationData.reservation_id

                if (this.user.userid == data.caller.userid)
                {                 
                    //this.caller            = data.memberData;
                    //this.callReservation   = data.reservationData;
                    //this.recipient         = data.tutorData;
                    this.$bvModal.hide('modal-call-teacher'); 
                    this.$bvModal.hide('modal-call-member'); 
                    this.$bvModal.show('modal-lesson-slider');

                } else if (this.user.userid == data.recipient.userid) {
                    this.$bvModal.hide('modal-call-teacher'); 
                    this.$bvModal.hide('modal-call-member');
                    this.$bvModal.show('modal-lesson-slider');                          
                }

                if (this.isMobile())  {
                    setTimeout(this.expandModal, 1000);
                } else {
                    setTimeout(this.revertModal, 1000);
                }

                console.log("start slider...")
            }); 
            

            window.addEventListener('resize', () => {
                if (this.isMobile()) {
                    setTimeout(this.revertModal, 1000);              
                }
                console.log("resized modal")
            });

            
        },
        methods: {

            selectLesson(tutor, member, reservation) 
            {

              
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);
                this.reservation        = JSON.parse(reservation);

              
                this.selectedLessonID = this.reservation.reservation_id;

                console.log(this.selectedLessonID );


                this.$bvModal.show('modal-select-lesson');
            },
            getLessonsList() {

                console.log(this.lessonOptions);           

                axios.post("/api/get_child_folders?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    lessonID         : this.selectedLessonID,
                    public_folder_id : 6,
                }).then(response => {

                    
                });

            },
            revertModal() {
                this.sliderLoaded = true;

                $(".modal-dialog").css({
                    'max-width': '1140px'
                });
               
            },
            expandModal() {

                this.sliderLoaded = true;
                $(".modal-dialog").css({
                    'max-width': '90%'
                });
                
            },
            isMobile() {
                if (window.innerWidth <= 1024 || screen.width  <= 1024 ) {
                    return true
                } else {
                    return false
                }   
            },

            updateUserList: function(users) 
            {
                this.users = users;      
                this.$forceUpdate();
            },
            acceptCall() {

                ///console.log(this.caller,  this.recipient, this.callReservation );
                             
                let data = {
                    caller          :   this.caller,
                    recipient       :   this.recipient,
                    reservationData :   this.callReservation
                }

                console.log(this.callReservation);

                this.channelid = this.callReservation.reservation_id
                console.log("channel : " + this.channelid);


                this.$bvModal.hide('modal-call-alert'); 
                this.socket.emit('START_SLIDER', data);
            },
            callMember(tutor, member, reservation) {

                this.now                = new Moment().tz("Japan");
                this.currentTime        = Moment(this.now).tz("Japan").format("YYYY-MM-DD HH:mm:ss"); 
                this.reservation        = JSON.parse(reservation);
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);

                this.lessonStartTime    = Moment(this.reservation.lesson_time ).format('YYYY-MM-DD HH:mm:ss')
                this.lessonEndTime      = Moment(this.reservation.lesson_time ).add(this.reservation.duration, 'minutes').format('YYYY-MM-DD HH:mm:ss');


                console.log(this.lessonStartTime)
                console.log(this.lessonEndTime);
                console.log(this.reservation)

                //search member
                let userIndex = this.users.findIndex(user => user.userid == this.member.userid)
                this.recipient = this.users[userIndex];

                if (typeof this.recipient == 'undefined') {
                    alert ("Sorry, Member is not online at the moment");
                    return false
                }  else {         

                    this.$bvModal.show('modal-call-member');       

                    //CALL USER (EMIT DATA)
                    let data = {
                        recipient       :   this.member,    //recipient 
                        caller          :   this.tutor,     //caller
                        reservationData :   this.reservation
                    }
                    this.socket.emit('CALL_USER',  data);  
                }
            },
            //CALL TUTOR IS INITIATED AT THE FRONT END USING JS (window.memberCallerComponent.callTutor) COMMAND
            callTutor(tutor, member, reservation) {

                console.log(reservation);

                this.channelid = reservation.reservation_id;

                this.now                = new Moment().tz("Japan");
                this.currentTime        = Moment(this.now).tz("Japan").format("YYYY-MM-DD HH:mm:ss"); 


                this.reservation        = JSON.parse(reservation);
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);

                this.lessonStartTime    = Moment(this.reservation.lesson_time ).format('YYYY-MM-DD HH:mm:ss')
                this.lessonEndTime      = Moment(this.reservation.lesson_time ).add(this.reservation.duration, 'minutes').format('YYYY-MM-DD HH:mm:ss');

                
                let userIndex = this.users.findIndex(user => user.userid == this.tutor.userid);
                this.recipient = this.users[userIndex];

                if (typeof this.recipient == 'undefined') {
                    alert ("Sorry, teacher is not online at the moment");
                    return false;
                } else {               

                    this.$bvModal.show('modal-call-teacher');

                    //CALL USER (EMIT DATA)
                    let data = {
                        caller          :   this.member,
                        recipient       :   this.tutor,   //recipient tutor 
                        reservationData :   this.reservation
                    }

                    this.socket.emit('CALL_USER',  data);  
                }
            }, 
            cancelCall() 
            {
                if (this.user.type == "TUTOR") 
                {
                     this.$bvModal.hide('modal-call-alert');

                } else if (this.user.type == "MEMBER") {

                    this.$bvModal.hide('modal-call-teacher');                   
                }
                
            
                let data = {
                    memberData      :   this.member,
                    tutorData       :   this.tutor,
                    reservationData :   this.reservation
                }

                this.socket.emit('DROP_CALL', data);

            },
            checkTime() 
            {
                var duration        = Moment.duration(this.now.diff(this.lessonStartTime));
                var days            = duration.asDays();

                if (this.currentTime >= this.lessonStartTime && this.currentTime <= this.lessonEndTime) 
                {
                    this.$bvModal.show('modal-call-teacher');

                } else if (this.currentTime >=  this.lessonEndTime) {

                    alert ("The Lesson has already ended");

                } else {

                    let numberOfDays = parseInt(days);

                    alert ('Lesson will start after '+ Math.abs(numberOfDays) +' days')
                }
            
            }

        }

    };
</script>

<style >
/*
    #modal-lesson-slider .modal-dialog {        
        max-width: 100%;
        margin: 1.75rem 1rem;
    }
*/

</style>

<template>

    <div id="caller-wrapper">
        <div class="container">


            <b-modal id="modal-call-member" title="Requesting Lesson...">
                <div v-if="this.reservation !== null" class="text-center">
                    <!--
                    <div>Lesson time : {{ this.lessonStartTime }} To {{ this.lessonEndTime }}</div>
                    <div>Current Time {{ this.currentTime }}</div>
                    -->

                    <div class="text-primary font-weight-bold">Member {{ this.member.firstname }}  {{ this.member.lastname }}</div>
                    <div class="textsecondary">{{ this.reservation.lessonTimeRage }}</div>

                    <img :src="this.member.image" class="rounded-circle">    

                </div>
                <template #modal-footer>
                    <div class="container text-center">
                        <b-button variant="danger" @click="cancelCall">Cancel Lesson Request</b-button>
                    </div>
                </template>
            </b-modal>


            <b-modal id="modal-call-teacher" title="Requesting Lesson...">
                <div v-if="this.reservation !== null" class="text-center">
                    <!--
                    <div>Lesson time : {{ this.lessonStartTime }} To {{ this.lessonEndTime }}</div>
                    <div>Current Time {{ this.currentTime }}</div>
                    -->

                    <div class="text-primary font-weight-bold">Teacher {{ this.tutor.firstname }}  {{ this.tutor.lastname }}</div>
                    <div class="textsecondary">{{ this.reservation.lessonTimeRage }}</div>

                    <img :src="this.tutor.image" class="rounded-circle">    

                </div>
                <template #modal-footer>
                    <div class="container text-center">
                        <b-button variant="danger" @click="cancelCall">Cancel Lesson Request</b-button>
                    </div>
                </template>
            </b-modal>


            <b-modal id="modal-call-alert" title="Calling...">
                <div v-if="this.callReservation !== null" class="text-center">   
                    <div class="h5 mb-0 pb-0">
                        <span class="fullname">{{ this.caller.firstname + " " + this.caller.lastname }}</span>
                        <span v-show="this.caller.nickname">({{ this.caller.nickname }})</span>
                    </div>
                    <div>{{ this.caller.email }}</div>
                    <img :src="this.caller.image" class="rounded-circle">   
                </div>

                <template #modal-footer>
                    <div class="container text-center">
                        <b-button variant="success" @click="acceptCall">Accept Lesson Request</b-button>
                    </div>
                </template>
            </b-modal>

        </div>

        <div id="lesson-slider-container" class="container-fluid">
            <b-modal id="modal-lesson-slider"  title="Lesson" size="xl">
                <!--{{ this.sliderLoaded  }} | {{ this.channelid  }}-->
                <div v-if="this.sliderLoaded === true && this.channelid != null">
                    <lesson-slider-component 
                        :editor_id="'canvas'"
                        :channel_id="this.channelid"
                        :isBroadcaster="this.isBroadcaster"
                        :canvas_server="this.canvasServer"                
                        :canvas_width="500"
                        :canvas_height="500"
                        :user_info="this.userInfo"
                        :member_info="this.memberInfo" 
                        :api_token="this.api_token" 
                        :csrf_token="this.csrf_token"
                        >
                    </lesson-slider-component> 
                </div>
                <div v-else>
                    <div class="d-flex justify-content-center mb-3">
                        <b-spinner label="Loading..."></b-spinner>
                    </div>                    
                </div>
            </b-modal>
        </div>

        <!-- SELECT LESSON -->
        <div id="select-lesson-container" class="container-fluid">

             <b-modal id="modal-select-lesson"  title="Lesson" size="xl" @show="getLessonsList">

                Select Lesson 
                
                <b-form-select v-model="lessonSelected" :options="lessonOptions"></b-form-select>
                <div class="mt-3">Selected: <strong>{{ lessonSelected }}</strong></div>

             </b-modal>
        </div>



    </div>


</template>