<template>
    
    <div id="lessonSliderComponent">

        <audio id="alarmAudio">
            <source src="" type="audio/mp3">
        </audio>

        <!--
        <button @click="testEndSession()">end</button>
        -->

        <!-- Tutor to Member Feedback-->
        <MemberFeebackComponent 
            ref="memberFeedback" 
            :user_info="this.$props.user_info" 
            :reservation="this.$props.reservation" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>

        <!-- Member Satisfaction Survey Component-->
        <SatisfactionSurveyComponent 
            ref="satisfactionSurvey" 
            :folder_id="this.$props.folder_id" 
            :is-broadcaster="this.$props.is_broadcaster" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>   
    
        <TutorSessionInvitePopUpComponent 
            ref="TutorSessionInvite" 
            :reservation="this.$props.reservation" 
            :lesson_history="this.lesson_history" 
            :is_broadcaster="this.$props.is_broadcaster" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>

        <MemberConsecutiveLessons 
            ref="MemberConsecutiveLessons"
            :consecitive_lesson="consecutiveSchedules"
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>   

        <NavigationMenu 
            ref="NavigationMenu"
            :reservation="this.$props.reservation" 
            :is_lesson_completed="isLessonCompleted"        
            :is_broadcaster="this.$props.is_broadcaster" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>
        
        <MemberLessonTimerComponent 
            ref="MemberLessonTimer" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>

        <LessonSlider 
            ref="LessonSlider" 
            :channelid="this.$props.channelid"
            :user_info="this.$props.user_info"
            :member_info="this.$props.member_info"
            :recipient_info="this.$props.recipient_info"
            :is_lesson_started="this.isLessonStarted"
            :canvas_server="this.$props.canvas_server"
            :canvas_width="this.$props.canvas_width"
            :canvas_height="this.$props.canvas_height"
            :folder_id="this.$props.folder_id" 
            :reservation="this.$props.reservation" 
            :is_broadcaster="this.$props.is_broadcaster" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>

        <MemberFloatingChat
            ref="memberFloatingChat" 
            v-if="this.$props.is_broadcaster == false"
            :userid="''+this.user_info.id+''" 
            :username="this.user_info.username"
            :user_image="this.user_image"        
            :nickname="this.user_info.firstname"        
            :customer_support_image="'images/cs-profile.png'"
            :chatserver_url="this.chatserver_url"
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"
            :show_sidebar="false"
        />
        
          

    </div>

</template>


<script>
    import {fabric} from "fabric";
    import io from "socket.io-client";
    import NavigationMenu from './NavigationMenu.vue';
    import LessonSlider from './LessonSliderComponent.vue';
    import MemberLessonTimerComponent from './MemberLessonTimerComponent.vue';
    import SlideSelectorComponent from './SlideSelectorComponent.vue';
    import TutorSessionInvitePopUpComponent from './TutorSessionInvitePopUpComponent.vue';
    import MemberFloatingChat from '../chat/MemberFloatingChatComponent.vue';
    import MemberConsecutiveLessons from './MemberConsecutiveLessonsComponent.vue';
    import SlideUploaderComponent from './SlideUploaderComponent.vue'
    import MemberFeebackComponent from './MemberFeebackComponent.vue'
    import SatisfactionSurveyComponent from './SatisfactionSurveyComponent.vue'

    export default {

        name: "lessonSliderComponent",
        components: { 
            NavigationMenu,
            TutorSessionInvitePopUpComponent, SlideSelectorComponent, 
            SlideUploaderComponent, SatisfactionSurveyComponent, MemberFeebackComponent,
            MemberFloatingChat, MemberLessonTimerComponent, MemberConsecutiveLessons, LessonSlider
        },
        props: {

            chatserver_url: String, //This will fire chat server on webRTC page
            canvas_server: String,
            channelid: Number,
            folder_id: Number,
            folder_url_array: Array,

            reservation: Object,       
            consecutive_schedules: Object,       
           

            user_info: Object,
            user_image: String,   
            member_info: Object,                    
            recipient_info: Object,

            is_broadcaster: Boolean,
            csrf_token: String,		
            api_token: String,

            is_lesson_started: Boolean,            
            is_lesson_completed: Boolean,

            lesson_history: Object, 

            canvas_width: String,
            canvas_height: String
        },
        data() {        
            return {
                message: null,
                currentFolderURLArray: [], //Folder Array Name
                consecutiveSchedules: [],

                //Main Countdown (Timer)
                isMainTimerStarted: false,
                formattedTime: "00:00:00",
                hours: 0,
                minutes: 0,
                seconds: 0,
                countdownInterval: null, 

                //Call waiting (Absent time)
                absentStartTime: null,
                callWaitingLimit: null, //default time

                //Lesson Status
                isLessonStarted: false,   
                isLessonExpired: false,
                isLessonCompleted: false, 
                isUserAbsent: false, 
                isLessonStartTimeInvalid: false,  

                //Session (all of components)
                isSessionExpired: true,   
                



                //Grace Period
                gracePeriodInMinutes: null,

                //slides data
                currentSlide: null,
                slidesData: null,

               
            }
        },
        created() {
            
            this.lessonStatus           = this.$props.reservation.schedule_status;  //TEXT STATUS
            this.isLessonStarted        = this.$props.is_lesson_started;            //BOOLEAN   - LESSON STARTED
            this.isLessonCompleted      = this.$props.is_lesson_completed           //BOOLEAN   - COMPLETED STATUS
            this.consecutiveSchedules       = this.$props.consecutive_schedules;
            this.currentFolderURLArray         = this.$props.folder_url_array;              //ARRAY     - folderURLArray        

        },
        mounted() {

            //console.log(this.currentFolderURLArray)

            this.socket = io.connect(this.$props.canvas_server);

            window.lessonSliderComponent = this;


            //register as currently active users can see ONLINE  status
            this.user = {
                channelid: this.channelid,
                userid: this.member_info.user_id ,
                nickname: this.user_info.firstname,            
                username: this.user_info.username,     
                firstname: this.user_info.firstname,    
                lastname: this.user_info.lastname,      
                status: "ONLINE",
                type: this.user_info.user_type, 
                image:   this.$props.user_image  
            }

            this.socket.emit('REGISTER', this.user); 


          
            //send the current folder URL so it feedback url lesson will update
            this.$refs['memberFeedback'].updateLessonDetails(this.currentFolderURLArray);

            this.consecutiveSchedules = this.$props.consecutive_schedules;

            console.log('consecutiveSchedules', this.$props.consecutive_schedules)


            this.checkLessonSessionStatus();

          

            this.socket.on('update_user_list', users => {
                this.updateUserList(users); 
            });
            
            this.customSelectorBounds(fabric);


            this.socket.on('START_SESSION', (response) => {

                console.log("start session");

                if (this.$props.is_broadcaster == false) {

                    if (response.channelid == this.channelid) {
                    
                        if (response.recipient.userid == this.user_info.id)
                        {                    
                            console.log("TEACHER STARTED A SESSION", response);

                            if (this.isMainTimerStarted == false) {
                                console.log("this.startCountdown();")
                                this.startCountdown(); 
                            }
                        
                        } else {                    
                            //end a session for all 
                            //console.log("TEACHER STARTED A SESSION FOR ALL USERS", response);
                        } 
                    } else {
                        //wrong channel
                    }

                } else if (this.$props.is_broadcaster == true) {
                
                    //console.log("TEACHER ENDED OWN SESSION", response);
                }
            }); 




            this.socket.on('END_SESSION', (response) => { 

                if (this.$props.is_broadcaster == false) {

                    if (response.channelid == this.channelid) {

                        console.log("END SESSION RECIEVED BY MEMBER", response)
                        this.$refs['NavigationMenu'].stopTimer(); 
                        this.$refs['NavigationMenu'].disableNavigationMenu();
                        this.isLessonCompleted = true;
                       // this.$refs['satisfactionSurvey'].showSatisfactionSurveyModal(this.reservation);
                        $("#destroy-session-media").trigger("click");     

                        this.$refs['satisfactionSurvey'].showThankYou(this.reservation);
                        

                    } else {
                        console.log("channel not found");
                    }

                } else if (this.$props.is_broadcaster == true) {

                    console.log("END SESSION RECIEVED BY TUTOR")
                    this.isLessonCompleted = true;  
                    $("#destroy-session-media").trigger("click");                
                }
            });  


            this.socket.on('CALL_USER', (userData) => {

                console.log(" [START] =====  CALL_USER ===== ", userData, userData.caller.type);
                
                //When you are already on the the lesson and tutor calls during a session, 
                //the auto acccept and create a pingback


                if (this.$props.is_broadcaster == true && userData.caller.type == "MEMBER") {

                    if (userData.caller.channelid == this.channelid) {
                    
                        let callData = {
                            tutorData: userData.caller,
                            recipient: userData.recipient,
                            callReservation: userData.reservationData
                        }

                        this.socket.emit('ACCEPT_CALL', callData);

                        this.$refs['TutorSessionInvite'].hideCallUserModal();                                            
                        this.$refs['TutorSessionInvite'].hideWaitingListModal(); 

                        console.log("CALL_USER", userData.caller.type,  "EMMITTING ACCEPT CALL")


                    }
                }            

                if (this.$props.is_broadcaster == false && userData.caller.type == "TUTOR") {

                    if (userData.caller.channelid == this.channelid) {   

                        let callData = {
                            tutorData: userData.caller,
                            recipient: userData.recipient,
                            callReservation: userData.reservationData
                        };

                        this.socket.emit('ACCEPT_CALL', callData);

                        this.$refs['TutorSessionInvite'].hideCallUserModal();                                            
                        this.$refs['TutorSessionInvite'].hideWaitingListModal(); 

                        console.log("CALL_USER", userData.caller.type, "EMMITTING ACCEPT CALL")

                    }
                } 
            });


            this.socket.on("CALL_USER_PINGBACK", (userData) => {

                if (this.$props.is_broadcaster == true  && userData.type == "MEMBER") {
                 
                    console.log("CALL_USER_PINGBACK, RECIEVED FROM :" + userData.type , userData);

                    this.socket.emit('JOIN_SESSION_PINGBACK', userData); 
                    this.$refs['TutorSessionInvite'].hideCallUserModal();
                    this.$refs['TutorSessionInvite'].showWaitingListModal();
                }

                if (this.$props.is_broadcaster == false && userData.type == "TUTOR") {
                    console.log("CALL_USER_PINGBACK, RECIEVED FROM :" + userData.type , userData);
                    this.socket.emit('JOIN_SESSION_PINGBACK', userData);         
                    this.$refs['TutorSessionInvite'].hideCallUserModal(); 
                    this.$refs['TutorSessionInvite'].showWaitingListModal(); 
                }             

            });

            this.socket.on('JOIN_SESSION', (userData) => {

                if (this.$props.is_broadcaster == true && userData.type == "MEMBER") {

                    console.log(" << MEMBER_JOINED_SESSION ==>> ", userData);
                    this.$refs['TutorSessionInvite'].addParticipants(userData); 
                    this.$refs['TutorSessionInvite'].showWaitingListModal(); 
                    this.$refs['TutorSessionInvite'].hideWaitingListModal();                     
                    this.socket.emit('JOIN_SESSION_PINGBACK', this.user);                     

                } else if (this.$props.is_broadcaster == false && userData.type == "TUTOR") {

                    console.log(" <<= TUTOR_JOINED_SESSION ===>> ", userData,  "left", this.millisecondsLeft);



                    if (this.isMainTimerStarted == false) {
                        console.log("this.startCountdown();")
                        this.startCountdown(); 
                    }

                    this.$refs['TutorSessionInvite'].addParticipants(this.user); 
                    this.$refs['TutorSessionInvite'].showWaitingListModal(); 
                    this.$refs['TutorSessionInvite'].hideWaitingListModal();                     
                    this.socket.emit('JOIN_SESSION_PINGBACK', this.user);                     
                }   
            });


            this.socket.on('JOIN_SESSION_PINGBACK', (userData) => {

                if (this.$props.is_broadcaster == true && userData.type == "MEMBER") {

                    console.log("JOIN_SESSION_PINGBACK  FROM [" + userData.type + "]", userData);
                
                    this.$refs['TutorSessionInvite'].hideCallUserModal();
                    this.$refs['TutorSessionInvite'].addParticipants(userData);  

                    if (this.isLessonStarted == true) {
                        console.log("lesson not started, hide waiting list modal")
                        //this.$refs['TutorSessionInvite'].hideWaitingListModal(); //[this will auto join]
                    }
                    
                   
                }

              if (this.$props.is_broadcaster == false && userData.type == "TUTOR") {
                    //TUTOR JOINED THE SESSION
                    console.log("JOIN_SESSION_PINGBACK FROM [" + userData.type + "]", userData);                
                    this.$refs['TutorSessionInvite'].hideCallUserModal(); 
                    this.$refs['TutorSessionInvite'].addParticipants(userData);
                    this.$refs['TutorSessionInvite'].hideWaitingListModal()
                }                
                
            });

            this.socket.on('LEAVE_SESSION', (userData) => {

                let currrentTutor = this.getRecipient();

                if (userData.userid == currrentTutor.userid) {

                    if (this.$props.is_broadcaster == false) 
                    {

                        if (this.isLessonCompleted == false && this.isLessonStartTimeInvalid == false)    {

                            console.log("TUTOR LEFT THE SESSION"); 

                            this.$refs['TutorSessionInvite'].removeParticipants(userData); 
                            this.$refs['TutorSessionInvite'].stopLessonTimer()
                            this.$refs['TutorSessionInvite'].showWaitingListModal();

                            //this.$refs['TutorSessionInvite'].resetLessonTimer()
                            //this.$refs['TutorSessionInvite'].startLessonStartTimer();

                            this.$refs['TutorSessionInvite'].resetWaitingTimer();
                            this.$refs['TutorSessionInvite'].startWaitingTimer()   

                        }                  

                    } else {           

                        if (this.isLessonCompleted == false 
                            && this.isLessonStartTimeInvalid == false
                            &&  this.isUserAbsent  == false
                            && this.isLessonExpired == false
                            )    {
                            
                            console.log("USER LEFT A SESSION");     

                            this.$refs['TutorSessionInvite'].removeParticipants(userData); 
                            this.$refs['TutorSessionInvite'].stopLessonTimer()
                            this.$refs['TutorSessionInvite'].showWaitingListModal();

                            //waiting timer for tutor to make an "emit" a "redialUser"
                            this.$refs['TutorSessionInvite'].resetWaitingTimer();
                            this.$refs['TutorSessionInvite'].startWaitingTimer()   

                        } else {
                            console.log(" USER LEFT A SESSION !!! LESSON COMPLETED !!!");    
                        }
                    }  

                }

           
            ; 
            });


            this.socket.on("ACCEPT_CALL", (userData) =>  {

                if (this.$props.is_broadcaster == false) {
                    console.log("ACCEPT_CALL, (call accepted by member)", userData);
                    this.$refs['TutorSessionInvite'].hideCallUserModal(); 
                    this.$refs['TutorSessionInvite'].hideWaitingListModal(); 
                } 

                if (this.$props.is_broadcaster == true) { 
                    console.log("ACCEPT_CALL (call accepted by tutor)", userData);     
                    this.$refs['TutorSessionInvite'].hideCallUserModal(); 
                    this.$refs['TutorSessionInvite'].hideWaitingListModal(); 
                }

            });

            /** [start] SOCKETS SERVERS **/
            this.socket.on("START_MEMBER_TIMER", (data) =>  {
               //MEMBER LESSON MINI TIMER
                if (this.$props.is_broadcaster == false) {                 
                    this.$refs['MemberLessonTimer'].setTimeRemaining(data.timeRemaining);
                    this.$refs['MemberLessonTimer'].startCountdown(); 
                }
            });

            this.socket.on("STOP_MEMBER_TIMER", (data) =>  {
                //MEMBER LESSON MINI TIMER
                if (this.$props.is_broadcaster == false) {
                    this.$refs['MemberLessonTimer'].stopCountdown(); 
                }
            });

         
            this.socket.on('UPDATE_DRAWING', (response) => {   

                if (this.currentSlide !== response.currentSlide) {
                    this.viewerCurrentSlide = response.currentSlide;
                }

               if (this.$props.is_broadcaster == true) {
                    console.log("UPDATE_DRAWING TUTOR");   
               } else {          
                    console.log("UPDATE_DRAWING MEMBER");   

                    this.$refs['LessonSlider'].delegateUpdateCanvas(response.currentSlide, response.canvasData);
                    this.$refs['LessonSlider'].delegateSetZoom(response.currentSlide, response.canvasZoom);

                    if (response.canvasDelta !== null) {
                        this.$refs['LessonSlider'].delegateRelativePan(response.currentSlide, response.canvasDelta);
                    }

                   ;  
               }
            });

            this.socket.on("GOTO_SLIDE", (data) =>  {
                console.log("goto slide socket sent", data);               
                if (this.$refs['audioPlayer']) {    
                    this.$refs['audioPlayer'].resetAudioIndex() 
                }
                this.viewerCurrentSlide = data.num
                this.currentSlide = data.num;
                this.$refs['LessonSlider'].goToSlide(data.num);            
            }); 


            this.socket.on("CREATE_NEW_SLIDE", (data) => {
                if (this.$props.is_broadcaster == false) {
                    console.log("create new slide")
                    this.slides = data.slidesCount;
                    this.$refs['LessonSlider'].createNewSlide();
                    this.$refs['LessonSlider'].goToSlide(data.currentslide);
                    if (data.backgroundURL) {
                        this.$refs['LessonSlider'].setSlideBackgroundImage(this.currentSlide, data.backgroundURL);
                    }                
                    
                }           
            }); 

            /*[start] TUTOR SELECT A NEW SLIDE */
            this.socket.on('TUTOR_SELECTED_NEW_SLIDES', (response) => {
                try {
                    if (this.$props.is_broadcaster == false) {
                        this.$refs['LessonSlider'].openNewSlideMaterials(response.folderID);                       
                    }
                } catch (error) {
                    console.log("Error, tutor can't select new slide ", error);
                }
            });   
            
            this.$root.$on('triggerCallUser', (params) => {               
                this.callMember();
            });



            //this will show consecutive lesson confirmation and let tutor confirm
            this.$root.$on('tiggerStartSession', (params) => {
                this.showConsecutiveLessons();            
            });

            this.$root.$on('tiggerConfirmEndSession', (params) => {             
                this.confirmEndSession();            
            });
            

            this.$root.$on('triggerEndSession', (params) => {             
                this.triggerEndSession();            
            });
            

            //[start] Lesson (this will force everything to start)
            this.$root.$on('tiggerStartLesson', (params) => {
                this.startLesson(params);                
            });
            

            this.$root.$on('triggerFloatinChatBox', (params) => {
                this.openFloatingChatBox();
            });



            this.$root.$on('triggerMarkStudentAbsent', (params) => {
                this.markStudentAbsent();
            });



            //[start] Mini Task Timer
            this.$root.$on('triggerShowMiniTaskTimer', (params) => {
                this.triggerShowMiniTaskTimer();                
            });

            this.$root.$on('startMemberTimer', (timeRemaining) => {  

                console.log("startMemberTimer") ;

                if (this.$props.is_broadcaster == true) {
                    this.socket.emit('START_MEMBER_TIMER', {
                        'channelid': this.channelid,
                        'timeRemaining': timeRemaining
                    });
                } else {
                    console.log("startMemberTimer")
                }
            });

            this.$root.$on('stopMemberTimer', (timeRemaining) => {        
                if (this.$props.is_broadcaster == true) {
                    this.socket.emit('STOP_MEMBER_TIMER', {
                        'channelid': this.channelid,
                        'timeRemaining': timeRemaining
                    });
                } else {
                    console.log("startMemberTimer")
                }
            });

            this.$root.$on('playAlarmAudio', (alarmAudio) => {
                console.log("play alarm audio", alarmAudio)
                this.playAlarmAudio(alarmAudio);
            });
            //[end] Mini Task Timer


            /****************AUDIO ACTIONS CONTROLLER (Broadcaster) **************** */
            this.$root.$on('playAudio', (index) => {
                if (this.$props.is_broadcaster == true) {
                    this.socket.emit('PLAY_AUDIO', {
                        'channelid': this.channelid,
                        'index':index
                    });
                } 
            });

            this.$root.$on('goToAudio', (index) => {
                if (this.$props.is_broadcaster == true) {
                    this.socket.emit('GOTO_AUDIO', {
                        'channelid': this.channelid,
                        'index':index
                    });
                } 
            });

            this.$root.$on('pauseAudio', (index) => {
                if (this.$props.is_broadcaster == true) {
                    this.socket.emit('PAUSE_AUDIO', {
                        'channelid': this.channelid,
                        'index':index
                    });
                } 
            });
                            
            this.$root.$on('nextAudio', (index) => {
                if (this.$props.is_broadcaster) {
                    this.socket.emit('NEXT_AUDIO', {
                        'channelid': this.channelid,
                        'index':index
                    }); 
                }
            });

            this.$root.$on('prevAudio', (index) => {
                if (this.$props.is_broadcaster) {
                    this.socket.emit('PREV_AUDIO', {
                        'channelid': this.channelid,
                        'index':index
                    }); 
                }
            });

            this.$root.$on('seekAudio', (data) => {
                if (this.$props.is_broadcaster) {
                    this.socket.emit('SEEK_AUDIO', {
                        'channelid': this.channelid,
                        'trackTime': data.trackTime,
                        'index': data.index
                    }); 
                }
            });


            /* AUDIO SOCKET CONTROLLER */
            this.socket.on('PLAY_AUDIO', (response) => {
                if (this.$props.is_broadcaster == false) {
                    this.$refs['LessonSlider'].$refs['audioPlayer'].gotoAndPlayClientAudio(response.index);  
                }
            });

            this.socket.on('GOTO_AUDIO', (response) => {
                if (this.$props.is_broadcaster == false) {                
                    this.$refs['LessonSlider'].$refs['audioPlayer'].gotoAndPlayClientAudio(response.index);  
                }
            });

            this.socket.on('PAUSE_AUDIO', (response) => {
                if (this.$props.is_broadcaster == false) {
                    this.$refs['LessonSlider'].$refs['audioPlayer'].stopAudio();       
                }
            });

            this.socket.on('NEXT_AUDIO', (response) => {
                if (this.$props.is_broadcaster == false) {
                    this.$refs['LessonSlider'].$refs['audioPlayer'].goToAudio(response.index);       
                }
            });

            this.socket.on('PREV_AUDIO', (response) => {           
                if (this.$props.is_broadcaster == false) {
                    this.$refs['LessonSlider'].$refs['audioPlayer'].goToAudio(response.index);
                }
            });

            this.socket.on('SEEK_AUDIO', (response) => {           
                if (this.$props.is_broadcaster == false) {
                    this.$refs['LessonSlider'].$refs['audioPlayer'].updateAudioTrackTime(response.trackTime);
                }
            });                        


            this.$root.$on('redialUser', (response) => {
                console.log(response, data, "redial user fired");      

                let data = {
                    recipient       :   this.recipient_info,    //recipient 
                    caller          :   this.user,     //caller (is always member info since it)
                    reservationData :   this.reservation
                }

                if (this.isUserAbsent == false) {
                    this.socket.emit('CALL_USER',  data); 
                } else {
                    console.log("I will not re-dial, since the user is a no show, we will wait for a manual call to action from the teacher moving forward")
                }

            }); 

        },
       methods: {
            testEndSession() {
                if (this.$props.is_broadcaster == true) {
                    console.log("emit end session")

                    this.socket.emit('END_SESSION', this.getSessionData())
                }
            },
            async checkLessonSessionStatus() {
            
                if (this.lessonStatus == "CLIENT_NOT_AVAILABLE") {

                    this.title   = "NOTIFICATION";
                    this.message = "CLIENT WAS NOT AVAILABLE<br> ";
                    this.message += "Our client informed us that they are not available for this lesson, please proceed to your next lesson. thank you!";

                    this.promptUser();
                    this.$refs['NavigationMenu'].disableNavigationMenu();

                } else if (this.lessonStatus == "TUTOR_CANCELLED") {

                    this.title   = "NOTIFICATION";
                    this.message = "TUTOR WAS NOT AVAILABLE<br> ";
                    this.message += "Tutor not available for this lesson, please proceed to your next lesson. thank you!";

                    this.promptUser();

                    this.$refs['NavigationMenu'].disableNavigationMenu();            

                } else if (this.isLessonCompleted == true) {

                    this.promptSessionComplete();

                } else {

                    if (this.$props.is_lesson_started == true) {
                    
                        this.startLesson(this.consecutiveSchedules.duration);

                    } else {                
                    
                        //check if session is valid and call member or accept 
                        this.checkSessionValid(this.reservation);

                    }                
                }            
            },
            async checkSessionValid(reservationData) {                

                let params = {
                    'startTime' : this.consecutiveSchedules.duration.startTime,
                    'endTime'   : this.consecutiveSchedules.duration.endTime,
                    'length'    : this.consecutiveSchedules.duration.length
                };

                axios.post("/api/checkSessionValidity?api_token=" + this.api_token,
                {
                    'method'            : "POST",
                    'reservation'       : reservationData,
                    'startTime'         : params.startTime,
                    'endTime'           : params.endTime,
                    'duration'          : params.length,
                }).then(response => {

                    /************************************************************/
                    /*   [NEW!!!] LESSON VERIFIER 
                    /*
                    /* @description(1): calls the user is a tutor and if lesson is not started yest and valid lesson...
                    /* @description(2): accepts the tutor call if the user is a student
                    /************************************************************/

                    this.isLessonExpired        = response.data.isLessonExpired
                    this.isLessonStarted        = response.data.isLessonStarted;
                    this.isUserAbsent           = response.data.isUserAbsent;
                    this.callWaitingLimit       = response.data.callWaitingLimit;
                    this.totalElapsedMinutes    = response.data.totalElapsedMinutes;
                    this.gracePeriodInMinutes   = response.data.gracePerionInMinutes;
                    this.millisecondsLeft       = response.data.remaningDurationInMilliseconds;
                    this.message                = response.data.message;
                    this.title                  = response.data.title;
                    this.isLessonStartTimeInvalid = response.data.isStartTimeInvalid;


                    console.log(response.data);

                    if (response.data.success == true && response.data.isLessonStarted == false 
                        && response.data.isLessonExceedGracePeriod == false 
                        && this.isLessonExpired  == false 
                        && this.isLessonExpired  == false 
                        && response.data.isStartTimeInvalid == false 
                        && this.isUserAbsent  == false ) {

                        

                        //[calling is delagated to start lesson button 
                         if (this.$props.is_broadcaster == true) {
                            console.log("check session call Member")
                            this.callMember()
                         }  else { 
                            console.log("check session accept tutor call")
                            this.acceptTutorCall(); 
                        }
                        


                    } else if (response.data.success == false) {

                        if (response.data.isLessonStarted == false && response.data.isLessonExpired == true) {
                            this.isSessionExpired = true;
                            this.promptLessonAbsent();                                                                              
                        } else if (this.isLessonStarted  == false && response.data.isUserAbsent == true) {                        
                            this.isUserAbsent = true;
                            this.promptLessonAbsent();    
                            console.log("absent")

                        } else {                       
;
                            this.promptUser();  
                        }
                    } 

                });

            },
            async postLessonStartHistory(reservationData, params) { 

                //Pre-load the slides for saving
                this.currentSlide = await this.$refs['LessonSlider'].getCurrentSlide()
                this.slidesData = await this.$refs['LessonSlider'].getAllSlideData();

                console.log(this.currentSlide, this.slidesData);

                axios.post("/api/postLessonStartHistory?api_token=" + this.api_token,
                {
                    'method'            : "POST",
                    'reservation'       : reservationData,
                    'startTime'         : params.startTime,
                    'endTime'           : params.endTime,
                    'duration'          : params.length,
                }).then(response => {

                    this.isLessonExpired        = response.data.isLessonExpired
                    this.isSessionExpired      = response.data.isLessonExpired;   

                    this.isLessonStarted        = response.data.isLessonStarted;
                    this.isUserAbsent           = response.data.isUserAbsent;
                    this.callWaitingLimit       = response.data.callWaitingLimit;
                    this.totalElapsedMinutes    = response.data.totalElapsedMinutes;
                    this.gracePeriodInMinutes   = response.data.gracePerionInMinutes;
                    this.millisecondsLeft       = response.data.remaningDurationInMilliseconds;
                    this.message                = response.data.message;
                    this.title                  = response.data.title;



                    if (response.data.success == true && response.data.isLessonExceedGracePeriod == false && response.data.isLessonExpired  == false 
                        && response.data.isUserAbsent  == false && params.isLessonStarted == false) {

                        this.startSession();

                    } else if (response.data.isLessonStarted == true && response.data.isLessonExpired == true) {                      

                        this.startSession(); 

                        if (this.is_broadcaster == true)  {
                            this.promptSessionExpiredOptions();
                        }                        
                        
                    } else if (response.data.isLessonStarted == false && response.data.isLessonExpired == true) { 
                  

                        this.isSessionExpired = true;      
                        this.promptSessionExpired();

                    } else if (response.data.isUserAbsent == true) {

                        this.isUserAbsent = true;
                        this.promptLessonAbsent();                    

                    } else if (response.data.success == true && response.data.isLessonStarted == true) {

                        this.startSession();

                    } else if (response.data.success == true && params.isLessonStarted == true) {

                        this.startSession();

                    } else if (response.data.success == false) { 

                      
                        //Prompt user if success if false                   
                        this.promptUser();    
                    }
                         
                });
            },
            async startSession() {

                axios.post("/api/startLesson?api_token=" + this.api_token,
                {
                    'method'          : "POST",
                    'folder_id'       : this.$props.folder_id,
                    'totalSlides'     : this.slidesData.length,
                    'currentSlide'    : this.currentSlide,
                    'reservation'     : this.reservation, //reservationData,                
                    'slidesData'      : this.slidesData,        
                    'isTimerStarted'  : this.isTimerStarted

                }).then(response => {


                   console.log("success", response.data.success);

                   console.log("is lesson expired?", this.isLessonExpired);
                   console.log("is session expired?", this.isSessionExpired);
             
             

                    if (response.data.success == true) {
                        
                        this.$refs['NavigationMenu'].startTimer();

                        console.log("emit start session")

                        this.socket.emit('START_SESSION', this.getSessionData());   


                        if (this.isMainTimerStarted == false) {
                            console.log("this.startCountdown();")
                            this.startCountdown(); 
                        }

                   

                        this.$refs['MemberConsecutiveLessons'].hideConsecutiveLessonModal(); 

                        //if session has not started then save all slides to slide history
                        if (this.isLessonStarted == false) {

                            console.log("|| =================== SESSION_START =================== ||");

                            this.socket.emit('JOIN_SESSION', this.user); 

                            this.$refs['LessonSlider'].saveAllSlides();

                        } else if (this.isLessonStarted == true && this.isSessionExpired == false) {

                            //[NOTE] ADDED TO COMPLEMENT CALLING THE TUTOR IF REFRESHED

                            if (this.$props.is_broadcaster == true) {

                                console.log("we are calling member, since it has not expired")
                                this.callMember(); 

                            } else {

                                console.log("check session accept tutor call")
                                this.acceptTutorCall(); 

                            }

                        } else {

                            if (this.$props.is_broadcaster == true) {

                                if (this.isLessonStarted == true && this.isSessionExpired == false) {

                                   this.callMember();

                                } else if (this.isLessonStarted == true && this.isSessionExpired == true) {                              

                                  // this.callMember();  (delegate to poup)

                                } else if (this.isLessonStarted == false && this.isSessionExpired == false) {
                                
                                    console.log("|| XXXXXXXXXXXXXXXXXX  SESSION_NOT_STARTED  XXXXXXXXXXXXXXXXXX  ||")

                                } else {

                                    console.log("|| ??????????????????? SESSION IS UNKNOWN HERE ??????????????????? ||")
                                }

                            } else {


                                this.$refs['TutorSessionInvite'].showWaitingListModal();

                            
                                //member joined...
                                console.log("EMIT JOIN_SESSION TO TUTOR");
                                this.socket.emit('JOIN_SESSION', this.user); 
                            }

                        }

                    } else {
                    

                    }
                });

            },       
            customSelectorBounds(fabric) {
                fabric.Object.prototype.transparentCorners = false;
                fabric.Object.prototype.cornerColor = 'blue';
                fabric.Object.prototype.cornerStyle = 'circle';
            },
            updateUserList: function(users) {
                this.users = users;      
                this.$forceUpdate();                 
            },
            getSessionData() {

                //Pre-load the slides for saving
                this.currentSlide = this.$refs['LessonSlider'].getCurrentSlide()
                this.slidesData = this.$refs['LessonSlider'].getAllSlideData();

                let sessionData = {
                    'totalSlide'    : this.slidesData.length,
                    'currentslide'  : this.currentSlide,
                       
                    'channelid'     : this.channelid,
                    'sender'        : { userid: this.user_info.id, username: this.user_info.username},
                    'recipient'     : this.getRecipient()
                };  
                return sessionData;  
            },
            getRecipient() { 
                return this.$props.recipient_info;
            },   
            acceptTutorCall() {

                this.socket.emit('ACCEPT_CALL',  {
                    caller          :   this.user,                      //user data
                    recipient       :   this.$props.recipient_info,    //recipient                                                //caller (is always member info since it)
                    reservationData :   this.$props.reservation                            
                });

            },
            callMember() {

                if (this.$props.is_broadcaster == true) {
                
                    console.log("<<========== START_CALL_MEMBER ===========> ")

                    this.$refs['TutorSessionInvite'].showCallUserModal();

                    this.socket.emit('CALL_USER',  {
                        caller          :   this.user,                      //user data
                        recipient       :   this.$props.recipient_info,    //recipient                                                //caller (is always member info since it)
                        reservationData :   this.$props.reservation                            
                    });

                } else {
                
                    console.log("calling tutor (inactive)");
                }
        
            },                  
            promptLessonAbsent() {

                this.$refs['NavigationMenu'].disableNavigationMenu();                            

                if (this.$props.is_broadcaster == true) {
                
                    let params = {                                  
                                'title'     : this.title, 
                                'message'   : this.message,
                                'callWaitingLimit': this.callWaitingLimit,
                            }

                    this.$refs['TutorSessionInvite'].showModalAbsent(params);  

                } else {
                
                    let params = {                                  
                                'title'     : this.title, 
                                'message'   : this.message,
                                'callWaitingLimit': this.callWaitingLimit,
                            }

                    this.$refs['TutorSessionInvite'].showModalAbsent(params);                  
                }
             
            },
            promptSessionExpiredOptions() {   

                //lesson has been started, show continue
                //@todo: mark its first lesson if expired.

                let params = {  'title'     : this.title, 
                                'message'   : this.message
                            }
                this.$refs['TutorSessionInvite'].showSessionExpiredOptionsModal(params);   

            },
            promptSessionExpired() { 

                //this lesson if for lesson that has not started, 
                //it will not show continue
                //@todo: mark its first lesson if expired.

                let params = {'title': this.title, 'message': this.message }
                this.$refs['NavigationMenu'].disableNavigationMenu();
                this.$refs['TutorSessionInvite'].showModalExpired(params);                           
            },
            promptUser() {               
                let params = {'title': this.title, 'message': this.message }
                this.$refs['TutorSessionInvite'].showUserPromptModal(params); 
            },
            promptSessionComplete() {

                console.log("promptSessionComplete");

                this.$refs['NavigationMenu'].disableNavigationMenu();
                //this.$refs['TutorSessionInvite'].showModalCompleted();

                if (this.$props.is_broadcaster == true) {
                    this.showMemberFeedbackModal();
                } else {
                    this.showSatisfactionSurveyModal();
                }               
            },
            showMemberFeedbackModal() {       
                this.$refs['memberFeedback'].showMemberFeedbackModal(this.reservation, this.files);
            },
            showSatisfactionSurveyModal() {
                this.$refs['satisfactionSurvey'].showSatisfactionSurveyModal(this.reservation);
            },
            showConsecutiveLessons() {
                if (this.consecutiveSchedules.lessons.length > 1) {
                    this.$refs['MemberConsecutiveLessons'].setConsecutiveLessons(this.consecutiveSchedules);
                } else {

                    //(this will auto start lesson since it will not show consecutive lessons)
                    let params = {
                        'startTime' : this.consecutiveSchedules.duration.startTime,
                        'endTime'   : this.consecutiveSchedules.duration.endTime,
                        'length'    : this.consecutiveSchedules.duration.length,
                        'isLessonStarted': true, //force lesson started (since)
                    };

                    this.startLesson(params); 
                }               
            },
            startLesson(params) {                 
                this.postLessonStartHistory(this.reservation, params);
            },
            
            openFloatingChatBox() {
                this.$refs['memberFloatingChat'].openFloatingChatIcon()
                this.$refs['memberFloatingChat'].openChatBox();
            },

            //Main Countdown timer
            startCountdown(millisecondsLeft) {
                this.isMainTimerStarted = true;

                //if method passed a parameter take this parameter else this.milliseconds will be added as value
                if (!(millisecondsLeft == null || millisecondsLeft == undefined || millisecondsLeft == 'undefined')) {
                    this.millisecondsLeft = millisecondsLeft;
                } 
                
                this.countdownInterval = setInterval(() => {
                    this.millisecondsLeft -= 1000;
                    this.calculateTimeLeft();

                    if (this.millisecondsLeft <= 0) {
                        //clearInterval(this.countdownInterval);
                    }
                }, 1000);
            },
            stopCountdown() {
                clearInterval(this.countdownInterval); 
                this.$refs['NavigationMenu'].stopTimer();                
            },
            calculateTimeLeft() 
            {               
                const seconds = Math.floor(Math.abs(this.millisecondsLeft) / 1000);
                const minutes = Math.floor(seconds / 60);
                const hours = Math.floor(minutes / 60);

                const formattedSeconds = String(seconds % 60).padStart(2, "0");
                const formattedMinutes = String(minutes % 60).padStart(2, "0");
                const formattedHours = String(hours).padStart(2, "0");

                const sign = this.millisecondsLeft < 0 ? "-" : "";

                this.formattedTime = `${sign}${formattedHours}:${formattedMinutes}:${formattedSeconds}`;

                //update the navigation menu timer
                this.$refs['NavigationMenu'].updateTimer(this.formattedTime);

                //check expired every timer updates
                if (this.millisecondsLeft <= 0) {

                    console.log(hours >= 1 || minutes >= this.gracePeriodInMinutes)

                    if (hours >= 1 || minutes >= this.gracePeriodInMinutes) 
                    {
                        if (this.isLessonStarted == true) {    
                            //Lesson has been started, prompt user to end it since it has expired                        
                            //this.promptSessionExpiredOptions();
                        } else {
                            clearInterval(this.countdownInterval);
                            this.promptSessionExpired();
                            
                        }
                    }
                }                                
            },           
            //[start] Mini Task Timer
            triggerShowMiniTaskTimer() {
                this.$refs['MemberLessonTimer'].showTimerControlModal()
            },
            startMemberTimer() {
                console.log("START_MEMBER_TIMER - EMIT")
                this.socket.emit('START_MEMBER_TIMER', this.getSessionData()); 
                this.isMainTimerStarted = true;
            },
            //[end] Mini Task Timer        
            playAlarmAudio(audio) {                
                let alarmAudio = document.getElementById('alarmAudio');
                if (alarmAudio) {      
                    alarmAudio.src = window.location.origin +"/"+ audio.path;                              
                    alarmAudio.load();
                    alarmAudio.play();  
                }
            },
            markStudentAbsent() 
            {
                axios.post("/api/postLessonAbsentHistory?api_token=" + this.api_token, 
                {
                    'reservation'     : this.reservation,                            
                }).then(response => {

                    console.log(response);

                    if (response.data.success == true) {

                        this.$nextTick(() => { 
                            this.$refs['TutorSessionInvite'].backToMemberHome();
                        });

                    } else {
                    
                        this.title   = "NOTIFICATION";                        
                        this.message = response.data.message;
                        this.promptUser();    

                    }
                    
                });
            },
            //[start] End Lesson
            async confirmEndSession() {

                const h = this.$createElement;

                const messageVNode = h('div', { class: ['foobar'] }, [
                    h('p', { class: ['text-center'] }, [ h('strong', 'Please confirm that you want to end lesson')]),
                ]);                        

                this.$bvModal.msgBoxConfirm(messageVNode, {                
                    title: 'End Session Confirmation',
                    contentClass: 'esi-modal',
                    headerBgVariant: 'lightblue',
                    headerTextVariant: 'white',
                    buttonSize: 'md',

                    okVariant: 'danger',
                    okTitle: 'END SESSION',
                    cancelVariant: 'primary',
                    cancelTitle: 'CONTINUE SESSION',
                    footerClass: 'p-2',

                    'hideHeaderClose': false,
                    'noCloseOnBackdrop': true,
                    'noCloseOnEsc': true,
                    'centered': false,
                }).then(isConfirmed => {

                    if (isConfirmed == true)  {
                        this.endSession();                      
                    }
                }).catch(err => {                    
                    alert (err)                
                });             
            },
            async triggerEndSession() {            
                if (this.isSessionExpired == true) {

                     this.endSession();               
                }

            },            
            async endSession() {             
                this.$refs['LessonSlider'].saveAllSlides();          
                this.postLessonEndSessionHistory(this.reservation);

            },
            async postLessonEndSessionHistory(reservationData) {
            
                if (this.$props.is_broadcaster == false) {                       
                    alert ("Member is not allowed to end a session");
                    return false
                }    

            
                this.currentSlide = await this.$refs['LessonSlider'].getCurrentSlide();
                this.slidesData = await this.$refs['LessonSlider'].getAllSlideData();

                //@note: save session history
                axios.post("/api/postLessonEndHistory?api_token=" + this.api_token,
                {
                    'method'          : "POST",
                    'folder_id'       : this.$props.folder_id,
                    'currentSlide'    : this.currentSlide,
                    'slidesData'      : this.slidesData,
                    'totalSlides'     : this.slidesData.length,
                    'consecutiveSchedules': this.consecutiveSchedules,
                    'reservation'     : reservationData,                
                    
                    'isTimerStarted'  : this.isTimerStarted  

                }).then(response => {
        
                    if (this.$props.is_broadcaster == true) {

                        this.isLessonCompleted = true;

                        this.stopCountdown(); 

                        if (this.is_broadcaster == true) {
                            this.$refs['NavigationMenu'].stopTimer();                    
                            this.$refs['NavigationMenu'].disableNavigationMenu();
                            this.socket.emit('END_SESSION', this.getSessionData()); 
                            console.log("session end was broadcasted");
                            this.showMemberFeedbackModal(this.reservation, this.files);
                            $("#destroy-session-media").trigger("click"); 
                        }

                        return true;
                    }             

                });
            },



        }
    }
 
</script>