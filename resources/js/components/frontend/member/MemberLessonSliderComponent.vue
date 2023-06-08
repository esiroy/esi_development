<template>
    
    <div id="lessonSliderComponent">

        <audio id="alarmAudio">
            <source src="" type="audio/mp3">
        </audio>

        <!-- Tutor to Member Feedback-->
        <MemberFeebackComponent ref="memberFeedback" 
            :user_info="this.$props.user_info" 
            :reservation="this.$props.reservation" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>

        <!-- Member Satisfaction Survey Component-->
        <SatisfactionSurveyComponent 
            ref="satisfactionSurvey" 
            :folder_id="this.$props.folder_id" 
            :is-broadcaster="this.$props.isBroadcaster" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>   
    
        <TutorSessionInvitePopUpComponent 
            ref="TutorSessionInvite" 
            :reservation="this.$props.reservation" 
            :lesson_history="this.lesson_history" 
            :is_broadcaster="this.$props.is_broadcaster" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>

        <NavigationMenu 
            ref="NavigationMenu"
            :reservation="this.$props.reservation" 
            :is_lesson_completed="isLessonCompleted"        
            :is_broadcaster="this.$props.is_broadcaster" 
            :api_token="this.api_token" 
            :csrf_token="this.csrf_token"/>

        <MemberConsecutiveLessons 
            ref="MemberConsecutiveLessons"
            :consecitive_lesson="consecutiveSchedules"
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
    import TutorSlideNotesComponent from './TutorSlideNotesComponent.vue';
    import SlideSelectorComponent from './SlideSelectorComponent.vue';
    import AudioPlayerComponent from './AudioPlayerComponent.vue';
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
            TutorSessionInvitePopUpComponent, TutorSlideNotesComponent, SlideSelectorComponent, 
            AudioPlayerComponent, SlideUploaderComponent, SatisfactionSurveyComponent, MemberFeebackComponent,
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
                isLessonCompleted: false, 
                isUserAbsent: false,    
                isSessionExpired: true,   
                isLessonStarted: false,   

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
            this.consecutiveSchedules   = this.$props.consecutive_schedules;  
            
            this.currentFolderURLArray         = this.$props.folder_url_array;              //ARRAY     - folderURLArray        

        },
        mounted() {

            console.log(this.currentFolderURLArray)

            this.socket = io.connect(this.$props.canvas_server);
            window.lessonSliderComponent = this;
          
            //send the current folder URL so it feedback url lesson will update
            this.$refs['memberFeedback'].updateLessonDetails(this.currentFolderURLArray);

            this.consecutiveSchedules = this.$props.consecutive_schedules;
           

            if (this.lessonStatus == "CLIENT_NOT_AVAILABLE") {

                this.title   = "NOTIFICATION";
                this.message = "CLIENT WAS NOT AVAILABLE<br> ";
                this.message += "Our client informed us that they are not available for this lesson, please proceed to your next lesson. thank you!";
                this.promptUser();
                this.$refs['NavigationMenu'].updateLessonStatus(true);

            } else if (this.lessonStatus == "TUTOR_CANCELLED") {

                this.title   = "NOTIFICATION";
                this.message = "TUTOR WAS NOT AVAILABLE<br> ";
                this.message += "Tutor not available for this lesson, please proceed to your next lesson. thank you!";
                this.promptUser();
                this.$refs['NavigationMenu'].updateLessonStatus(true);            

            } else if (this.isLessonCompleted == true) {

                this.promptSessionComplete();

            } else {

                if (this.$props.is_lesson_started == true) {

                    console.log('consecutiveSchedules', this.consecutiveSchedules.duration)
                
                    this.startLesson(this.consecutiveSchedules.duration);
                }                
            }

            //register as currently active users can see ONLINE  status
            this.user = {
                userid: this.member_info.user_id ,
                nickname: this.user_info.firstname,            
                username: this.user_info.username,     
                firstname: this.user_info.firstname,    
                lastname: this.user_info.lastname,               
                channelid: this.channelid,
                status: "ONLINE",
                type: this.user_info.user_type, 
                image:   this.$props.user_image  
            }

            this.socket.emit('REGISTER', this.user); 

            this.customSelectorBounds(fabric);

            this.socket.on('update_user_list', users => {
                this.updateUserList(users); 
            });
            
            /** [start] SOCKETS SERVERS **/
            this.socket.on("START_MEMBER_TIMER", (data) =>  {
                if (this.$props.is_broadcaster == false) {
                    this.$refs['MemberLessonTimer'].setTimeRemaining(data.timeRemaining);
                    this.$refs['MemberLessonTimer'].startCountdown(); 
                }
            });

            this.socket.on("STOP_MEMBER_TIMER", (data) =>  {
                if (this.$props.is_broadcaster == false) {
                    this.$refs['MemberLessonTimer'].stopCountdown(); 
                }
            });


          
            this.socket.on('UPDATE_DRAWING', (response) => {
            
               if (this.$props.is_broadcaster == true) {
                    console.log("broadcaster update halted...");   
               } else {                  

                    this.$refs['LessonSlider'].delegateUpdateCanvas(response.currentSlide, response.canvasData);
                    this.$refs['LessonSlider'].delegateSetZoom(response.currentSlide, response.canvasZoom);

                    if (response.canvasDelta !== null) {
                        this.$refs['LessonSlider'].delegateRelativePan(response.currentSlide, response.canvasDelta);
                    }
               }
            });

            this.socket.on("GOTO_SLIDE", (data) =>  {
                console.log("goto slide socket sent", data)

                /*
                if (this.$refs['audioPlayer']) {
                    this.$refs['audioPlayer'].resetAudioIndex();
                } 
                */

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
            /*[end] TUTOR SELECT A NEW SLIDE */                   



            //this will show consecutive lesson confirmation and let tutor confirm
            this.$root.$on('tiggerStartSession', (params) => {
                this.showConsecutiveLessons();            
            });

            this.$root.$on('tiggerEndSession', (params) => {
                console.log("triggering end session", params)
                 this.confirmEndLesson();            
            });            

            

            //[start] Lesson (this will force everything to start)
            this.$root.$on('tiggerStartLesson', (params) => {
                this.startLesson(params);                
            });
            

            this.$root.$on('triggerFloatinChatBox', (params) => {
                this.openFloatingChatBox();
            });

            //[start] Mini Task Timer
            this.$root.$on('triggerShowMiniTaskTimer', (params) => {
                this.triggerShowMiniTaskTimer();                
            });

            this.$root.$on('startMemberTimer', (timeRemaining) => {   
                if (this.$props.is_broadcaster == true) {
                    this.socket.emit('START_MEMBER_TIMER', {
                        'channelid': this.channelid,
                        'timeRemaining': timeRemaining
                    });
                }
            });

            this.$root.$on('stopMemberTimer', (timeRemaining) => {        
                if (this.$props.is_broadcaster == true) {
                    this.socket.emit('STOP_MEMBER_TIMER', {
                        'channelid': this.channelid,
                        'timeRemaining': timeRemaining
                    });
                }
            });

            this.$root.$on('playAlarmAudio', (alarmAudio) => {
                this.playAlarmAudio(alarmAudio);
            });
            //[end] Mini Task Timer


         

        },
       methods: {
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
                let sessionData = {
                    //'totalSlide'    : this.slides,
                    //'currentslide'  : this.currentSlide,
                    'totalSlide'    : 1,
                    'currentslide'  : 1,   
                    'channelid'     : this.channelid,
                    'sender'        : { userid: this.user_info.id, username: this.user_info.username},
                    'recipient'     : this.getRecipient()
                };  
                return sessionData;  
            },
            getRecipient() { 
                return this.$props.recipient_info;
            },            
            promptLessonAbsent() {

                this.$refs['NavigationMenu'].updateLessonStatus(this.isUserAbsent);                            

                if (this.$props.is_broadcaster == true) 
                {
                
                    let params = {                                  
                                'title'     : this.title, 
                                'message'   : this.message,
                                'callWaitingLimit': this.callWaitingLimit,
                            }

                    this.$refs['TutorSessionInvite'].showModalAbsent(params);  

                }
             
            },
            promptSessionExpiredOptions() {                
                let params = {  'title'     : this.title, 
                                'message'   : this.message
                            }
                this.$refs['TutorSessionInvite'].showSessionExpiredOptionsModal(params);   

            },
            promptSessionExpired() {    
                let params = {'title': this.title, 'message': this.message }
                this.$refs['NavigationMenu'].updateLessonStatus(this.isSessionExpired);
                this.$refs['TutorSessionInvite'].showModalExpired(params);                           
            },
            promptUser() {               
                let params = {'title': this.title, 'message': this.message }
                this.$refs['TutorSessionInvite'].showUserPromptModal(params); 
            },
            promptSessionComplete() {

                console.log("promptSessionComplete");

                this.$refs['NavigationMenu'].updateLessonStatus(this.isLessonCompleted);
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
                    //update this 
                    let duration = {
                        'startTime' : this.consecutiveSchedules.duration.startTime,
                        'endTime'   : this.consecutiveSchedules.duration.endTime,
                        'length'    : this.consecutiveSchedules.duration.length,
                        'isLessonStarted': true, //force lesson started (since)
                    };
                    this.startLesson(duration); 
                }               
            },
            startLesson(params) {                 
                this.postLessonStartHistory(this.reservation, params);
            },
            
            openFloatingChatBox() {
                this.$refs['memberFloatingChat'].openFloatingChatIcon()
                this.$refs['memberFloatingChat'].openChatBox();
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

                    this.isLessonStarted        = response.data.isLessonStarted;
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

                        //Tutor started lesson but has expired, this will start the session and prompt expiry options
                        this.isSessionExpired = true;                                                   

                        this.startSession();  
                        this.promptSessionExpiredOptions();
                        
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

                console.log('start Session', this.slidesData, " Is lesson Started? " + this.isLessonStarted)

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

                    if (response.data.success == true) {
                    
                        this.isSessionExpired = false;
                        this.$refs['NavigationMenu'].startTimer();

                        this.socket.emit('START_SESSION', this.getSessionData());    
                        this.startCountdown();
                        this.$refs['MemberConsecutiveLessons'].hideConsecutiveLessonModal();

                        //if session has not started then save all slides to slide history
                        if (this.isLessonStarted == false) {
                          this.$refs['LessonSlider'].saveAllSlides();
                        }

                    } else {
                    

                    }
                });

            },
            startCountdown(millisecondsLeft) 
            {
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
                this.socket.emit('START_MEMBER_TIMER', this.getSessionData()); 
                this.isMemberTimerStarted = true;
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

            //[start] End Lesson
            async confirmEndLesson() {

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
            async endSession() {
                console.log( this.$refs['LessonSlider'].$data.userID);
                this.$refs['LessonSlider'].saveAllSlides();                
                this.postLessonEndSessionHistory(this.reservation);
            },
            async postLessonEndSessionHistory(reservationData) 
            {
                let slidesData = await this.$refs['LessonSlider'].getAllSlideData();

                if (this.$props.isBroadcaster == false) {                       
                    alert ("Member is not allowed to end a session");
                    return false
                }    

                //@note: save session history
                axios.post("/api/postLessonEndHistory?api_token=" + this.api_token,
                {
                    'method'          : "POST",
                    'folder_id'       : this.$props.folder_id,
                    'totalSlides'     : this.slides,
                    'currentSlide'    : this.currentSlide,
                    'reservation'     : reservationData,                
                    'slidesData'      : slidesData,
                    'isTimerStarted'  : this.isTimerStarted                
                }).then(response => {
        
                    if (this.$props.isBroadcaster == true) 
                    {   
                        console.log("session end was broadcasted");

                        this.stopTimer(); 
                        this.hideEndSessionButton();                   
                        this.socket.emit('END_SESSION', this.getSessionData());  

                        if (this.sessionActive == true)  {
                            this.sessionActive = false;
                            this.showMemberFeedbackModal(this.reservation, this.files);
                        }

                    }             

                });
            }
        }
    }
 
</script>