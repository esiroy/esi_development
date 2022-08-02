<script>
    
    import io from "socket.io-client";    
    import Moment from "moment-timezone";

    export default {
        name: "Member-Caller-Component",
        props: 
        {
            csrf_token: String,		
            api_token: String,


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

                //caller
                caller: null,
                recipient: null,
                callReservation: null,


            }
        },
        mounted() {

            this.socket = io.connect(this.canvasServer);

            //Transfer the object to the window
            window.memberCallerComponent = this;

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
                if (this.user.userid == data.tutorData.userid ) {                   
                    this.$bvModal.HIDE('modal-call-alert');   
                }               
            });           

   



            this.socket.on("SEND_CALL_ALERT", (data) =>  
            {
                if (this.user.userid == data.tutorData.userid )
                {
                  
                    //Member is calling TUTOR
                    this.caller              = data.memberData;                        
                    this.recipient           = data.tutorData;
                    this.callReservation     = data.reservationData;

                    this.$bvModal.show('modal-call-alert');  
                }
            });


            this.socket.on("START_SLIDER", (data) =>  
            {

                if (this.user.type == "MEMBER") {

                    console.log("slider start 1");

                    if (this.user.userid == data.memberData.userid)
                    {                 
                        //this.caller            = data.memberData;
                        //this.callReservation   = data.reservationData;
                        //this.recipient         = data.tutorData;                        

                        this.$bvModal.hide('modal-call-teacher'); 
                        this.$bvModal.show('modal-lesson-slider');   
                    }     

                
                } else if (this.user.type == "TUTOR") {

                    if (this.user.userid == data.tutorData.userid)
                    {                 
                        //this.caller            = data.tutorData;
                       // this.callReservation   = data.reservationData;
                        //this.recipient         = data.memberData;                        

                        this.$bvModal.hide('modal-call-alert');                         
                        this.$bvModal.show('modal-lesson-slider');   
                    }
                }

            }); 

            
        },
        methods: {
            updateUserList: function(users) 
            {
                this.users = users;      
                this.$forceUpdate();
            },
           acceptCall() {

                ///console.log(this.caller,  this.recipient, this.callReservation );
                if (this.user.type == "TUTOR") 
                {                
                    let data = {
                        memberData      :   this.caller,
                        tutorData       :   this.recipient,
                        reservationData :   this.reservation
                    }

                    console.log("start slider");

                    this.socket.emit('START_SLIDER', data);
                }     
            },
            //CALL TUTOR IS INITIATED AT THE FRONT END USING JS (window.memberCallerComponent.callTutor) COMMAND
            callTutor(tutor, member, reservation) {

                console.log(reservation);

                this.now                = new Moment().tz("Japan");
                this.currentTime        = Moment(this.now).tz("Japan").format("YYYY-MM-DD HH:mm:ss"); 

                this.reservation        = JSON.parse(reservation);
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);

                this.lessonStartTime    = Moment(this.reservation.lesson_time ).format('YYYY-MM-DD HH:mm:ss')
                this.lessonEndTime      = Moment(this.reservation.lesson_time ).add(this.reservation.duration, 'minutes').format('YYYY-MM-DD HH:mm:ss');

                if (this.user.type == "MEMBER") 
                {
                    let userIndex = this.users.findIndex(user => user.userid == this.tutor.userid);
                    
                    this.recipient = this.users[userIndex];

                    console.log(this.recipient);

                    if (typeof this.recipient == 'undefined') {

                        alert ("Sorry, teacher is not online at the moment");

                    } else {
                        this.$bvModal.show('modal-call-teacher');
                    }

                } else {
                    let userIndex = this.users.findIndex(user => user.userid == this.member.userid)

                    this.recipient = this.users[userIndex];

                    if (typeof this.recipient == 'undefined') {

                        alert ("Sorry, Member is not online at the moment");

                    } else {
                        this.$bvModal.show('modal-call-teacher');
                    }
                  
                }

                //CALL USER (EMIT DATA)
                let data = {
                    memberData      :   this.member,
                    tutorData       :   this.tutor,
                    reservationData :   this.reservation
                }

                this.socket.emit('CALL_USER',  data);  
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

<template>
    <div class="container">


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
                <div class="h5 mb-0 pb-0">{{ this.caller.firstname + " " + this.caller.lastname }} ({{ this.caller.nickname }})</div>
                <div>{{ this.caller.email }}</div>
                <img :src="this.caller.image" class="rounded-circle">   
            </div>

            <template #modal-footer>
                <div class="container text-center">
                    <b-button variant="success" @click="acceptCall">Accept Lesson Request</b-button>
                </div>
            </template>
        </b-modal>



        <b-modal id="modal-lesson-slider" title="Lesson">
            {{ "we are in a lesson session" }}
        </b-modal>






    </div>
</template>