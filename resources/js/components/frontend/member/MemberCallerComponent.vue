<template>

    <div id="caller-wrapper">
        <div class="container">


            <audio id="incomingCallAudio">
                <source src="" type="audio/mp3">
            </audio>

            
            <b-modal id="modal-call-member" content-class="esi-modal" title="Requesting Lesson..." :header-bg-variant="headerBgVariant" no-close-on-esc no-close-on-backdrop hide-header-close >
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


            <b-modal id="modal-call-teacher" content-class="esi-modal" title="Requesting Lesson..." :header-bg-variant="headerBgVariant" no-close-on-esc no-close-on-backdrop hide-header-close>
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


            <b-modal id="modal-call-alert" :title="'A tutor is inviting you for a call'" content-class="esi-modal" :header-bg-variant="headerBgVariant" no-close-on-esc no-close-on-backdrop hide-header-close>
                <div class="row text-center" v-if="this.callReservation !== null" >                     
                    <div class="col-12 text-center">
                        <div class="alert alert-primary" role="alert">
                            Please accept a lesson invitation from <span v-show="this.caller.nickname">({{ this.caller.nickname }})</span>
                            <!--  <div class="fullname">{{ this.caller.firstname + " " + this.caller.lastname }}</div>-->                            
                            <!-- Email: {{ this.caller.email }}  </div>-->
                        </div>

                         <img :src="this.caller.image" class="rounded-circle " width="150px">   
                     </div>                
                   
                </div>

                <template #modal-footer>
                    <div class="container text-center">
                        <b-button variant="success" @click="acceptCall">
                            <b-icon icon="telephone-inbound" animation="throb" font-scale="1"></b-icon> 
                            <span class="pb-3" animation="throb">Accept Lesson Request</span>
                        </b-button>
                    </div>
                </template>
            </b-modal>

        </div>


        <!-- SELECT LESSON -->
        <div id="select-lesson-container" class="container-fluid">
             <b-modal id="modalSelectLesson"  title="Select a Lesson" size="xl" @show="getLessonsList" >
                Select Lesson 
                <b-form-select id="lessonSelector" v-model="lessonSelectedFolderID" :options="lessonOptions" v-on:change="getOptionSelected('lessonSelector')"></b-form-select>
             
                <!--[START] - (NEW! 2023) PREVIEW: Lesson Image gallery for the selected lesson-->
                <div class="mt-3" v-if="folder !== null">
                    <div class="pt-2">You have selected:</div>                
                    <div>Lesson Name: <strong>{{ folder.folder_name }} </strong></div>
                    <div>Lesson Description: {{ folder.folder_description }}</div>                 
                    <div class="container pt-4" v-if="files != null">
                        <div class="row">
                            <div class="col-2" v-for="(file, fileIndex) in files" :key="fileIndex">
                                <img :src="getBaseURL(file.path)" class="img-fluid  cursor-pointer" @click="imageViewer(getBaseURL(file.path))"/>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center">
                        <span class="text-danger">No Images found for this slides</span>
                    </div>

                </div>
                <div v-else class="mt-3 text-center">
                    <div class="text-danger">Please select a folder</div>
                </div>
                <!--[END] PREVIEW: Lesson Image gallery for the selected lesson-->  

                <template #modal-footer>
                    <div class="w-100">
                        <b-button variant="primary" size="sm" class="float-right" @click="saveOptionSelected('lessonSelector')"> Select Lesson Material </b-button>
                    </div>
                </template>
             </b-modal>
        </div>

        <div id="image-viewer-container" class="container-fluid">
            <b-modal id="modalImageViewer"  title="Image Preview" ok-only>
                <div v-if="imageURL !== null">
                    <img :src="imageURL" class="img-fluid">
                </div>
            </b-modal>
        </div>


        <div id="lesson-slider-container" class="container-fluid" tabindex="9999999999999">

            <b-modal id="modal-lesson-slider" ref="modalLessonSlider" @hide="clearLessonChannel()" title="Lesson" size="xl"  :tabindex="9999999999999">                
                <div v-if="this.sliderLoaded == true && this.channelid != null">
                    <lesson-slider-component 
                        ref="lessonSliderComponent"
                        :editor_id="'canvas'"
                        :channelid="this.channelid"
                        :reservation="this.lessonReservationData"
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


    </div>


</template>

<script>
    
    import io from "socket.io-client";    
    import Moment from "moment-timezone";
    import MemberLessonSliderComponent from "./MemberLessonSliderComponent.vue";


    export default {
        name: "memberCallerComponent",
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
                headerBgVariant: 'lightblue',


                user: null,
                users: null,
                tutor: null,
                reservation: null,

                now         : null,
                currentTime: null,
                lessonStartTime: null,
                lessonEndTime: null,

                //Selected lesson Options
                selectedOption: null,

                //Model lesson Value
                lessonSelected: null,
                lessonSelectedFolderID: null,

                //Lesson Options
                lessonOptions: [],

                //caller
                caller: null,
                recipient: null,
                callReservation: null,

                //loader
                sliderLoaded: false,

                channelid: null,
                selectedLessonID: null,

                //Member Reservation
                lessonReservationData: null,

                //files
                files: null,
                folder: null,

                imageURL: null

            }
        },
        mounted() {

            //Transfer the object to the window
            window.memberCallerComponent = this;

            this.socket = io.connect(this.canvasServer);



            //Register the member Info
            this.user = {
                channelid: this.channelid, 
                userid: this.memberInfo.user_id,
                username: this.userInfo.username,
                nickname: this.userInfo.nickname,                               
                type: this.userInfo.user_type,
                status: "ONLINE",
            }  
       
            this.socket.emit('REGISTER', this.user); 


            //update the list
            this.socket.on('update_user_list', users => {
                this.updateUserList(users); 
            });


            this.socket.on("CALL_USER", (data) =>  
            {
                if (this.user.userid == data.recipient.userid ) 
                {       
                    this.caller              = data.caller;                        
                    this.recipient           = data.recipient;
                    this.callReservation     = data.reservationData;
                    this.$bvModal.show('modal-call-alert');  
                    //SEND THE CALL USER PING BACK WITH CHANNEL ID
                    this.recipient.channelid = data.reservationData.schedule_id;

                    this.playIncomingCallAudio({'path': 'mp3/incoming-call.mp3'})

                    console.log(data.recipient,  "emit call user pingback")

                    
                    this.socket.emit('CALL_USER_PINGBACK', this.recipient); 

                }                
            });



            this.socket.on("ACCEPT_CALL", (data) =>  {

                if (this.user.userid == data.tutorData.userid ) 
                {    
                    this.$bvModal.hide('modal-call-alert');   

                    this.caller              = data.caller;                        
                    this.recipient           = data.recipient;
                    this.callReservation     = data.reservationData;
                    console.log("accept call");
                } 
                console.log(data)
            });






            this.socket.on("START_SLIDER", (data) =>  {

                if (this.user.userid == data.recipient.userid ) 
                {
                    this.lessonReservationData  = data.reservationData;
                    this.$bvModal.hide('modal-call-teacher'); 
                    this.$bvModal.hide('modal-call-member');             
                    this.openSelfWindow(data.channelid);       

                } else if (this.user.userid == data.caller.userid ) {
                 
                    this.lessonReservationData  = data.reservationData;
                    this.$bvModal.hide('modal-call-teacher'); 
                    this.$bvModal.hide('modal-call-member');             
                    this.openSelfWindow(data.channelid);  
                 
                 }

            }); 
        },
        methods: {

            playIncomingCallAudio(audio) {
                let incomingCallAudio = document.getElementById('incomingCallAudio');
                if (incomingCallAudio) {      
                    incomingCallAudio.src = window.location.origin +"/"+ audio.path;                              
                    incomingCallAudio.load();
                    incomingCallAudio.play();  
                }
            },
            selectLesson(tutor, member, reservation) 
            {           
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);
                this.reservation        = JSON.parse(reservation); 

                //IMPORTANT: SELECTED ID IS FROM THE HTML FORM
                this.selectedLessonID = this.reservation.schedule_id;
                this.getMemberSelectedLesson()             
                
            },
            getMemberSelectedLesson() 
            {            

                axios.post("/api/getMemberLessonSelected?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    userID          : this.member.userid,
                    lessonID        : this.selectedLessonID
                }).then(response => {

                    if (response.data.success == true) {
                        this.lessonSelectedFolderID =  response.data.memberSelectedLesson.folder_id;
                        this.getLessonSelectedPreviewByID(this.lessonSelectedFolderID)
                        this.$bvModal.show('modalSelectLesson');
                    } else {                       
                        //Member Selected Lesson Material not found
                        //alert (response.data.message);
                        this.$bvModal.show('modalSelectLesson');     
                    }
                });
            }, 
            openSelfWindow(channelid) {
                window.location.href = window.location.origin + "/webRTC?roomid="+ channelid
            },
            openNewChannelTab(channelid) {
                var baseURL = window.location.origin + "/webRTC?roomid="+ channelid
                window.open(baseURL, '_blank');
            },
            getBaseURL(path) {
               return window.location.origin + "/" +path
            },
            imageViewer(imageURL) {

                this.imageURL = imageURL;

                this.$bvModal.show('modalImageViewer');
            },
            clearLessonChannel() {
                //make this to mark user is on a channel is awaiting calls and free
                this.user.channelid = null
                this.channelid = null;

            },
            autoAdjustModal() {
                if (this.isMobile()) {
                    setTimeout(this.expandModal, 50);        
                } else {                
                    setTimeout(this.revertModal, 50);
                }       
            },

            getOptionSelected(targetID) 
            {
                let selectedID = document.getElementById(targetID).value;
                this.getLessonSelectedPreviewByID(selectedID);

                let select = document.getElementById(targetID);
                let selectedIndex = select.selectedIndex;
                this.selectedOption = this.lessonOptions[selectedIndex];  

                return this.selectedOption;
            },
            getLessonSelectedPreviewByID(lessonSelectedFolderID) {
                axios.post("/api/getLessonSelectedPreview?api_token=" + this.api_token, 
                {
                    method                  : "POST",
                    userID                  : this.member.userid,
                    lessonID                : this.selectedLessonID,
                    lessonSelectedFolderID  : lessonSelectedFolderID

                }).then(response => {

                    if (response.data.success == true) 
                    {        
                        this.folder = response.data.folder;           
                    
                        //determine the file
                        if (response.data.files.length == 0) {
                            this.files = null;
                            this.$forceUpdate();
                        } else {
                            this.files = response.data.files;
                            this.$forceUpdate();
                        }

                    } else {
                        //@note:  nullify files to null to make the notication appear
                        this.files = null;
                        this.folder = null;   
                        this.$forceUpdate();  
                    }
                });

            },
            saveOptionSelected(targetID) 
            {
                let select = document.getElementById(targetID);
                let selectedIndex = select.selectedIndex;
                this.selectedOption = this.lessonOptions[selectedIndex];                

                axios.post("/api/saveSelectedLessonSlideMaterial?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    userID          : this.member.userid,
                    lessonID        : this.selectedLessonID,
                    selectedOption  : this.selectedOption

                }).then(response => {

                    if (response.data.success == false) {
                        alert (response.data.message);
                    } else {
                         alert (response.data.message);
                    }
                });

            },          
            getTabbings(hierarchy) {
                let tab = '';
                if (hierarchy >= 1) {
                    for(let i= 1; i<= hierarchy; i++) {
                        tab += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }                        
                }
                return tab;
            },
            getFolderOptions(FolderName, folders, hierarchy) 
            {   
                if (hierarchy == 0) 
                {
                    this.lessonOptions = [{
                        value: null,
                        html: "Please select lesson",       
                        label: "Please select lesson",
                        description:  "Please select lesson"                            
                    }];                
                }

                folders.forEach((folder) => { 

                    let folderOptionName = null;

                    if (FolderName !== null) {
                        folderOptionName = FolderName + " ====> " + folder.name;
                    } else {
                        folderOptionName = folder.name;                    
                    }


                    this.lessonOptions.push({                  
                        id              : folder.id,
                        name            : folder.name,
                        label           : folderOptionName,
                        html            : folderOptionName,
                        description     : folder.description,                             
                        value           : folder.id
                    });    
                       
                    if (folder.children.length >= 1) 
                    {
                        this.getFolderOptions(folderOptionName, folder.children, hierarchy + 1);
                    }

                });
                
            },
            getLessonsList() {
                axios.post("/api/get_folders?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    lessonID         : this.selectedLessonID,
                    //public_folder_id : null,
                }).then(response => {

                    if (response.data.success == true) {
                        
                        this.getFolderOptions(null, response.data.folders, 0);
                    }
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
                             
                this.sliderLoaded = false;
                this.channelid                 = this.callReservation.schedule_id;
                this.lessonReservationData     = this.callReservation;       
               
                //console.log("accept call " , this.callReservation);

                this.$bvModal.hide('modal-call-alert'); 


                let data = {
                    channelid       :  this.channelid,
                    caller          :   this.caller,
                    recipient       :   this.recipient,
                    reservationData :   this.callReservation
                }
                
                this.$bvModal.hide('modal-call-teacher'); 
                this.$bvModal.hide('modal-call-member'); 

                //this.$bvModal.show('modal-lesson-slider');                
                this.socket.emit('START_SLIDER', data);

                

            },
            callPingBack() {


            
            },
            callMember(tutor, member, reservation) {

                this.now                = new Moment().tz("Japan");
                this.currentTime        = Moment(this.now).tz("Japan").format("YYYY-MM-DD HH:mm:ss"); 
                this.reservation        = JSON.parse(reservation);
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);

                this.lessonStartTime    = Moment(this.reservation.lesson_time ).format('YYYY-MM-DD HH:mm:ss')
                this.lessonEndTime      = Moment(this.reservation.lesson_time ).add(this.reservation.duration, 'minutes').format('YYYY-MM-DD HH:mm:ss');


                //console.log(this.lessonStartTime)
                //console.log(this.lessonEndTime);
                //console.log(this.reservation)

                //search member
                let userIndex = this.users.findIndex(user => user.userid == this.member.userid)
                this.recipient = this.users[userIndex];

                if (typeof this.recipient == 'undefined') {
                    alert ("Sorry, Member is not online at the moment");
                    return false
                }  else {         


                    let response = this.checkTime();

                    if (response.valid == true) 
                    {

                        this.$bvModal.show('modal-call-member');       

                        //CALL USER (EMIT DATA)
                        let data = {
                            recipient       :   this.member,    //recipient 
                            caller          :   this.tutor,     //caller
                            reservationData :   this.reservation
                        }

                        this.socket.emit('CALL_USER',  data);  
                    }
                }
            },
            //CALL TUTOR IS INITIATED AT THE FRONT END USING JS (window.memberCallerComponent.callTutor) COMMAND
            callTutor(tutor, member, reservation) {

                console.log("call tutor")

                this.channelid = reservation.schedule_id;
                
                this.lessonReservationData  = reservation;

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

                    
                    /** 
                    **  
                    **  [CHECK CURRENT TIME TO AVOID DUPLICATE CALLS]    
                    ** 
                    */
                    let response = this.checkTime();

                    if (response.valid == true) 
                    {

                        this.$bvModal.show('modal-call-teacher');

                        //CALL USER (EMIT DATA)
                        let data = {
                            caller          :   this.member,
                            recipient       :   this.tutor,   //recipient tutor 
                            reservationData :   this.reservation
                        }

                        this.socket.emit('CALL_USER',  data);  

                    }


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

                //*** TEMPORARY VALID ALL TO TRUE */
                return ({'valid': true});


                if (this.currentTime >= this.lessonStartTime && this.currentTime <= this.lessonEndTime) 
                {
                    this.$bvModal.show('modal-call-teacher');

                    return ({'valid': true})

                } else if (this.currentTime >=  this.lessonEndTime) {



                    alert ("The Lesson has already ended" +  this.currentTime +">=  " + this.lessonEndTime);

                    return ({'valid': false})

                } else {

                    let numberOfDays = parseInt(days);

                    if (numberOfDays == 0) {
                    
                        alert ("Your lesson will start on " + this.lessonStartTime + " Today");


                    } else {

                         alert ('Lesson will start after '+ Math.abs(numberOfDays) +' days ');
                    }

                   

                    return ({'valid': false})

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



 .cursor-pointer {
    cursor: pointer;
 }

</style>

