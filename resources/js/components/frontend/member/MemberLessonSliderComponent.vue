<template>


    <div class="main-component-holder">

     

        <div id="component-container" v-show="sessionActive">

            <div id="satisfactionSurveyContainer">
                <SatisfactionSurveyComponent 
                    ref="satisfactionSurvey" 
                    :folder_id="this.$props.folder_id"
                    :is-broadcaster="this.$props.isBroadcaster"
                    :api_token="this.api_token" 
                    :csrf_token="this.csrf_token"
                >
                </SatisfactionSurveyComponent>
            </div>


            <div id="memberFeebackComponentContainer">
                <MemberFeebackComponent 
                    ref="memberFeedback" 
                    :folder_id="this.$props.folder_id"
                    :is-broadcaster="this.$props.isBroadcaster"
                    :api_token="this.api_token" 
                    :csrf_token="this.csrf_token"
                >
                </MemberFeebackComponent>
            </div>


            <div id="slideSelectorContainer">
                <SlideSelectorComponent
                    ref="slideSelector" 
                    :reservation="this.reservation"
                    :folder_id="this.$props.folder_id"
                    :is-broadcaster="this.$props.isBroadcaster"
                    :api_token="this.api_token" 
                    :csrf_token="this.csrf_token"
                >
                </SlideSelectorComponent>
            </div>

            <!--[start] toolbox -->
            <div id="toolbox" v-show="this.$props.isBroadcaster == true">
                <div class="tool-container">

                    <!-- [START] TOOL WRAPPER -->
                    <div class="tool-wrapper" >

                        <!--
                        <div :class="['tool', (isUndo) ? 'active' : 'text-white']"  @click="activateUndo">
                            <i class="fas fa-undo-alt" ></i>                                
                        </div> 

                        <div :class="['tool', (isRedo) ? 'active' : 'text-white']"  @click="activateRedo">
                            <i class="fas fa-redo-alt" ></i>                                
                        </div> 
                        -->

                        <div :class="['tool', (isZoomIn) ? 'active' : '']"  @click="activateZoomIn">                               
                            <i class="fa fa-search-plus text-white" aria-hidden="true"></i>
                        </div>     

                        <div :class="['tool', (isZoomOut) ? 'active' : '']"  @click="activateZoomOut">                               
                            <i class="fa fa-search-minus text-white" aria-hidden="true"></i>
                        </div>     


                        <div :class="['tool', (isSelector) ? 'active' : '']" @click="activateSelector">
                            <i class="fa fa-mouse-pointer text-white" aria-hidden="true" ></i>
                        </div>
                        
                        <div :class="['tool', (isText) ? 'active' : '']" @click="activateTextEditor">
                            <i class="fa fa-font text-white" aria-hidden="true"></i>
                        </div>


                        <div :class="['tool', (isDragger) ? 'active' : '']" @click="activateDragger">
                            <i class="fa fa-hand-paper text-white"></i>
                        </div>


                        <div :class="['tool', (isPencil) ? 'active' : '']"  @click="activatePencil">
                            <i class="fa fa-pen text-white" aria-hidden="true" ></i>
                        </div>                    
                        <div :class="['tool', (isBrush) ? 'active' : '']"  @click="activateBrush">
                            <i class="fa fa-paint-brush text-white" aria-hidden="true" ></i>  
                        </div>                        
                
                        <div :class="['tool', (isLine) ? 'active' : '']"  @click="activateLine">
                            <i class="fa fa-minus text-white" aria-hidden="true"></i>
                        </div>        

                        <div :class="['tool', (isCircle) ? 'active' : 'text-white']"  @click="activateCircle">
                            <b-icon icon="circle " font-scale="1"> </b-icon>
                        </div> 
                    </div>
                    <!-- [END] TOOL WRAPPER -->

                    <!-- ADDITIONAL OPTIONS -->
                    <div class="tool-wrapper">

                        <div class="tool">
                            <b-form-input type="color" v-model="brushColor" @change="changeColor" class="color-button"></b-form-input>
                        </div>             
                    
                        <!-- Strokes-->
                        <div class="tool" data-target="#brushStrokes"  data-toggle="collapse"  v-show="isBrush || isLine || isCircle">
                            <b-icon icon="border-width" font-scale="1" style="border:0px"> </b-icon>  
                            <div id="brushStrokes" class="collapse" style="z-index:9999">
                                <div class="brushes-container">
                                    <div class="brushes">
                                        <button type="button" value="5" :class="['tool brush', (stroke == 5) ? 'active' : '']"  @click="setBrushStroke(1, 5)"></button>
                                        <button type="button" value="10" :class="['tool brush', (stroke == 10) ? 'active' : '']"  @click="setBrushStroke(1, 10)"></button>
                                        <button type="button" value="15" :class="['tool brush', (stroke == 15) ? 'active' : '']"  @click="setBrushStroke(1, 15)"></button>
                                        <button type="button" value="25" :class="['tool brush', (stroke == 25) ? 'active' : '']"  @click="setBrushStroke(1, 25)"></button>
                                        <button type="button" value="30" :class="['tool brush', (stroke == 30) ? 'active' : '']"  @click="setBrushStroke(1, 30)"></button>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div> 

                </div>
            </div>
            <!--[end] toolbox -->

            <!-- ********** [START] Lesson Slides *************-->

            <div id="lessonSharedContainer"></div>

            <div id="lessonSlide" class="left-container mb-2"> 

                <!--[start] slide selector for broadcaster -->
                <div id="slideSelectorContainer" class="d-inline-block">
                    <button type="button" @click="selectSlides" v-show="this.$props.isBroadcaster == true">
                        <b-icon icon="images" aria-hidden="true"></b-icon>
                    </button>
                </div>
                <!--[end] slide selector for broadcaster -->

                <!--[start] breadcrumb -->
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ol class="breadcrumb bg-transparent my-2 p-0">
                        <li class="breadcrumb-item" aria-current="page">Home</li>
                        <li class="breadcrumb-item" v-for="segment in segments" :key="segment">
                        {{ segment }}
                        </li>                    
                    </ol>
                </nav>
                <!--[end] breadcrumb -->


                <!--[start] slide controls -->
                <div class="slide-controls d-inline-block">                  
                    <div class="buttons-container">
                        <div class="d-flex justify-content-center">
                            <div id="prev" class="tool" @click="prevSlide(true)" v-show="this.$props.isBroadcaster == true">
                                <i class="fa fa-backward" aria-hidden="true"></i>
                            </div>
                            <div id="slideInfo" class="tool font-weight-strong" style="width:150px">
                                <div v-show="this.$props.isBroadcaster == true">
                                    Slide {{ this.currentSlide }} of {{ this.slides }}
                                </div>
                                <div v-show="this.$props.isBroadcaster == false">
                                    Slide {{ this.viewerCurrentSlide }} of {{ this.slides }}
                                </div>
                            </div>
                            <div id="next" class="tool" @click="nextSlide(true)" v-show="this.$props.isBroadcaster == true">
                                <i class="fa fa-forward" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!--[end] slide controls -->


                <div id="audio-container" class="d-inline-block">
                    <AudioPlayerComponent ref="audioPlayer" :is-broadcaster="this.$props.isBroadcaster"></AudioPlayerComponent>
                    
                    <div id="newSlideButton" class="tool d-inline-block" @click="prepareNewSlide" >
                        <i class="far fa-file-alt" v-if="this.$props.isBroadcaster == true"></i>
                    </div>
                    <SlideUploaderComponent 
                        ref="slideUploader" 
                        :reservation="this.$props.reservation"
                        :folder_id="this.$props.folder_id"
                        :is-broadcaster="this.$props.isBroadcaster"
                        :api_token="this.api_token" 
                        :csrf_token="this.csrf_token"
                    >
                    </SlideUploaderComponent>
                </div>
                
                <div id="slide-container"></div>
            

            </div>
            <!--********** [END] Lesson Slides *************-->

            <b-modal
                id="modalAddInputText"
                ref="modalAddInputText"
                title="Add Text"
                @show="resetInputTextModal" 
                @hidden="resetInputTextModal"
                @ok="addInputText">
                <form ref="form" @submit.stop.prevent="handleSubmit"> 
                    <b-form-group label="Text" label-for="textInput" invalid-feedback="Text is required">
                        <b-form-input id="textInput" v-model="inputText" required></b-form-input>
                    </b-form-group>
                </form>
            </b-modal>        

        </div>        

        <div v-show="!sessionActive">
            <div class="container">
                <div class="row">
                    <div class="col-12"> 
                        <div class="mt-4">
                            <b-card header="Lesson Information">   
                                <b-card-text class="text-danger text-center">Session has ended</b-card-text>
                            </b-card>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>
import SlideSelectorComponent from './SlideSelectorComponent.vue'
import AudioPlayerComponent from './AudioPlayerComponent.vue'
//Uploader
import SlideUploaderComponent from './SlideUploaderComponent.vue'
//Feedback
import MemberFeebackComponent from './MemberFeebackComponent.vue'
import SatisfactionSurveyComponent from './SatisfactionSurveyComponent.vue'

import {fabric} from "fabric";
import io from "socket.io-client";

export default {
    name: "lessonSliderComponent",
    components: { SlideSelectorComponent, AudioPlayerComponent, SlideUploaderComponent, SatisfactionSurveyComponent, MemberFeebackComponent},
    props: {
        csrf_token: String,		
        api_token: String,
        reservation: Object,       

        lesson_history: Object,    

        isBroadcaster: {
            type: [Boolean],
            required: true        
        },   
        folder_id: {
            type: [String, Number],
            required: true        
        },    

        editor_id: {
            type: String,
            default: "canvas",
            required: false
        },           
        webrtc_server: {
            type: [String],
            required: true         
        },
        canvas_server: {
            type: [String],
            required: true        
        },    
        channelid: {            
            type: [String, Number],
            required: true
        },
        user_info: {
            type: [Object, String],
            required: true
        },        
        member_info: {
            type: [Object, String],
            required: true
        },
        recipient_info: {
            type: [Object, String],
            required: true
        },        
        canvas_width: {
            type: [String, Number],
            required: true
        },
        canvas_height: {
            type: [String, Number],
            required: true
        }
    },
    data() 
    {
        return {

            sessionActive: true,
            //socket
            socket: null,

      
            //loader
            isLoading: false,

            //uri segments
            segments:  "",


            //Audio

            audoFiles: [],            

            //history
            undoCtr: 0,
            redoCtr: 0,
            historyCounter: 0,

            //panning
            panning: false,
            isDraggerMouseDown: false,            
            offsetX: 0,
            offsetY: 0,
            startX: 0,
            startY: 0,
            netPanningX: 0,
            netPanningY: 0,
            delta: null,

            canvas: [],
            canvasMirror: null,

            //slides
            currentSlide: 1,
            viewerCurrentSlide: 1,

            slides: 0,
            lesson_slides_materials: [],

            //Modes
            isUndo: false,
            isRedo: false,

            isSelector: false,
            isDragger: false,
            isText: false,
            isTextEditing: false,

            isBrush: false, 
            isPencil: false,
            isCircle: false,
            isZoomIn: false,
            isZoomOut: false,

            //Action
            isDrawing: false,
            isDrawingLine:false,            
            isDrawingCircle:false, 

            //Line
            isLine: false,
            Line: null,
            
            history: [],
            currentSlide: 1,          

            //brush
            stroke: 5,
            brushColor: '#000000',

            //input text
           
            inputText: "",

            //mouseX
            mouseX: 0,
            mouseY: 0,

            //original set mouseX
            originX: 0,
            originY: 0,


            colorPreviewStyle:{
                backgroundColor: "#000" 
            },

            myIntervalTimer: null,
            timerSpeed: 1000,
            timer: 1800, //30 minutes = 1800
            resetTimerValue: 1800, //Same as timer (this will be not used as a countdown, this will be use to reset value)
            isTimerStarted: false, // determine if the timer started to avoid multipe start of timer

     
            imageURL: [],

            notes: "<bold>lorem epusm dollor </bold>",


            //currentID 
            currentFolderID: null,


            //if user select new folder and or background image
            newFolderID: null,
            newBackgroundImage: null,

            //test variable for testing 
            test: null,
            
           
        };
    },
    created() {

        this.getSlideMaterials(this.reservation);
    
    },
    mounted() 
    {  

        this.socket = io.connect(this.$props.canvas_server);
        window.lessonSliderComponent = this;

        //register as currently active users can see ONLINE  status
        let user = {
            userid: this.member_info.user_id ,
            nickname: this.member_info.nickname,            
            username: this.user_info.username,            
            channelid: this.channelid,
            status: "ONLINE",
            type: this.user_info.user_type,      
        }
        this.socket.emit('REGISTER', user); 

       
        this.socket.on('update_user_list', users => {
            this.updateUserList(users); 
            //this.goToSlide(this.currentSlide);
            //this.alignCanvas();
        });   
        
        this.customSelectorBounds(fabric);


        this.socket.on("GOTO_SLIDE", (data) =>  {
            this.$refs['audioPlayer'].resetAudioIndex();
            this.viewerCurrentSlide = data.num
            this.currentSlide = data.num
            this.goToSlide(data.num);            
        });

        
        this.socket.on("CREATE_NEW_SLIDE", (data) => {
            if (this.$props.isBroadcaster == false) 
            {

                console.log("socket create new slide!", data.slidesCount);

                this.slides = data.slidesCount;

                this.createNewSlide();

                if (data.backgroundURL) {
                    this.setSlideBackgroundImage(this.currentSlide, data.backgroundURL);
                }
                
                this.$forceUpdate();     
            }
        });


        this.socket.on('UPDATE_DRAWING', (response) => {

           //console.log("update", response)
            
            if (this.currentSlide !== response.currentSlide) {
                this.viewerCurrentSlide = response.currentSlide;
            }

            try {
                this.canvas[this.currentSlide].setZoom(response.canvasZoom);   
                this.canvas[this.currentSlide].requestRenderAll();                 
            } catch (error) {
                console.log("canvas setZoom errror", error);
            }


            if (response.sender.userid !== this.user_info.id ) 
            {
                try {
                    if (response.canvasDelta !== null) {
                        this.canvas[this.currentSlide].relativePan(response.canvasDelta);
                    }


                    if (response.canvasData.objects.length >= 1) {                    

                        this.updateCanvas(this.canvas[this.currentSlide], response.canvasData);          
                        
                    } else {

                        console.log("there is no drawing detected")
                        
                    }
                    
                } catch (error) {
                    console.log("canvas delta errror", error);
                }
            } 
        });        
        

        /* TUTOR NEW SLIDE ACTINS */
        this.socket.on('TUTOR_SELECTED_NEW_SLIDES', (response) => {

            try {

                if (this.$props.isBroadcaster == false) {   

                    console.log("member new slide materials");

                    this.openNewSlideMaterials();
                }

            } catch (error) {

                console.log("Error, tutor can't select new slide ", error);
            }
        });


        /****************AUDIO ACTIONS CONTROLLER (Broadcaster) **************** */
        this.$root.$on('playAudio', (index) => {
            if (this.$props.isBroadcaster == true) {
                this.socket.emit('PLAY_AUDIO', {
                    'channelid': this.channelid,
                    'index':index
                });
            } 
        });

        this.$root.$on('goToAudio', (index) => {
            if (this.$props.isBroadcaster == true) {
                this.socket.emit('GOTO_AUDIO', {
                    'channelid': this.channelid,
                    'index':index
                });
            } 
        });


        this.$root.$on('pauseAudio', (index) => {
            if (this.$props.isBroadcaster == true) {
                this.socket.emit('PAUSE_AUDIO', {
                    'channelid': this.channelid,
                    'index':index
                });
            } 
        });
                        
        this.$root.$on('nextAudio', (index) => {
            if (this.$props.isBroadcaster) {
                this.socket.emit('NEXT_AUDIO', {
                    'channelid': this.channelid,
                    'index':index
                }); 
            }
        });

        this.$root.$on('prevAudio', (index) => {
            if (this.$props.isBroadcaster) {
                this.socket.emit('PREV_AUDIO', {
                    'channelid': this.channelid,
                    'index':index
                }); 
            }
        });

        this.$root.$on('seekAudio', (data) => {
            if (this.$props.isBroadcaster) {
                this.socket.emit('SEEK_AUDIO', {
                    'channelid': this.channelid,
                    'trackTime': data.trackTime,
                    'index': data.index
                }); 
            }
        });


        /*************** AUDIO SOCKET CONTROLLER (RECEIVER ONLY) ****************/
        this.socket.on('PLAY_AUDIO', (response) => {
            if (this.$props.isBroadcaster == false) {
               this.$refs['audioPlayer'].gotoAndPlayClientAudio(response.index);  
            }
        });

        this.socket.on('GOTO_AUDIO', (response) => {
            if (this.$props.isBroadcaster == false) {                
                this.$refs['audioPlayer'].gotoAndPlayClientAudio(response.index); 
            }
        });

        this.socket.on('PAUSE_AUDIO', (response) => {
            if (this.$props.isBroadcaster == false) {
                this.$refs['audioPlayer'].stopAudio();       
            }
        });

        this.socket.on('NEXT_AUDIO', (response) => {
            if (this.$props.isBroadcaster == false) {
                 this.$refs['audioPlayer'].goToAudio(response.index);       
            }
        });

        this.socket.on('PREV_AUDIO', (response) => {           
            if (this.$props.isBroadcaster == false) {
                 this.$refs['audioPlayer'].goToAudio(response.index);
            }
        });

        this.socket.on('SEEK_AUDIO', (response) => {           
            if (this.$props.isBroadcaster == false) {
               this.$refs['audioPlayer'].updateAudioTrackTime(response.trackTime);
            }
        });        


        /*************** SESSION HANDLERS ****************/

         this.socket.on('JOIN_SESSION', (response) => {

            if (this.$props.isBroadcaster == false) {

                console.log("USER JOINED A SESSION")
            }
        });  


        this.socket.on('START_SESSION', (response) => {

            if (this.$props.isBroadcaster == false) {

                if (response.channelid == this.channelid) {
                
                    if (response.recipient.userid == this.user_info.id)
                    {                    
                        console.log("TEACHER STARTED A SESSION", response);
                    } else {                    
                        //end a session for all 
                        console.log("TEACHER STARTED A SESSION FOR ALL USERS", response);
                    }                
                   
                    this.startTimer();                  
                } else {
                
                }

            } else if (this.$props.isBroadcaster == true) {
            
                //console.log("TEACHER ENDED OWN SESSION", response);
            }

        }); 

        this.socket.on('END_SESSION', (response) => {

            if (this.$props.isBroadcaster == false) {

                if (response.channelid == this.channelid) {
                
                    if (response.recipient.userid == this.user_info.id)
                    {                    
                        console.log("TEACHER ENDED A SESSION", response);
                    } else {                    
                        //end a session for all 
                        console.log("TEACHER ENDED A SESSION FOR ALL USERS", response);
                    }                
                    this.showRatingModal();
                    this.stopTimer();                  
                } else {
                
                }

            } else if (this.$props.isBroadcaster == true) {
            
                //console.log("TEACHER ENDED OWN SESSION", response);
            }

        });                  

         

         //the user has not selected anything then we create new slide
        if (this.$props.folder_id == '' || this.$props.folder_id == null || this.$props.folder_id == 'null' ) {
            this.createNewSlide();        
        }




        //window.addEventListener('scroll', this.reOffset);
        //window.addEventListener('resize', this.reOffset);


    },
    methods: {
        updateUserList: function(users) {
            this.users = users;      
            this.$forceUpdate();                 
        },
        getSessionData() {
            //@note: sessionData is the data to be sent to the socket server
            let sessionData = {
                'currentslide'  : this.currentSlide,
                'channelid'     : this.channelid,
                'sender'        : { userid: this.user_info.id, username: this.user_info.username},
                'recipient'     : this.getRecipient()
            };  
            return sessionData;  
        },        
        selectSlides() {

            this.$refs['slideSelector'].openSlideSelector(this.reservation.schedule_id, this.reservation.member_id);

        },
        openNewSlideMaterials() {
        
            //Slide Selector already update the slide selector folder, 
            //So lets refresh the member slides!
            if (this.$props.isBroadcaster == true) { 
                //call remove slides and open new slides
                this.removeOldSlidesAndOpenNewSlides();
                this.refreshMemberSlides();
            } else {             
                //opening slide for member
                this.removeOldSlidesAndOpenNewSlides();
            }

        },
        removeOldSlidesAndOpenNewSlides() {

         
            for (var i = 1; i <= this.slides; i++) 
            {
                //this.canvas[i].dispose();

                //re-open with new slides
                if (i ==  this.slides) {

                    //@note: remove the other stuff generated under the slide container
                    let slideElement = document.getElementById('slide-container')                    
                    slideElement.innerHTML = '';            


                    this.getSlideMaterials(this.reservation);
                }
            }        
        },
        enableSession(){
            //@todo: show
            this.sessionActive = true;
        },
        disableSession(){
            //@todo: hide all  and end all stream
            this.sessionActive = false;
        },
        startSession() {
            this.postLessonStartHistory(this.reservation);
        },
        endSession() {
            this.confirmEndLesson();  
        },      
        confirmEndLesson() {

            this.$bvModal.msgBoxConfirm('Please confirm that you want to end lesson.', {                
                title: 'Please Confirm',
                buttonSize: 'sm',
                okVariant: 'primary',
                okTitle: 'YES',

                cancelTitle: 'NO',
                cancelVariant: 'danger',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true,
            }).then(isConfirmed => {

                if (isConfirmed == true)  {      
                    this.postLessonEndSessionHistory(this.reservation);
                } 

            }).catch(err => {
                // An error occurred
                alert (err)                
            }); 
        },
        async getCanvasSlideData(slideIndex) {
            return this.canvas[slideIndex].toJSON();       
        },
        async getAllSlideData() {

            let slidesDataArray = new Array();

            for (var i = 1; i <= this.slides; i++) 
            {
                let canvasData  = await this.getCanvasSlideData(i);
                let data = {
                    'slideIndex': i,
                    'canvasData': canvasData,                    
                };                    
                slidesDataArray.push(data);
            }

            return slidesDataArray;
        },
        async postLessonStartHistory(reservationData) {
        
            let slidesData = await this.getAllSlideData();

            axios.post("/api/postLessonHistory?api_token=" + this.api_token,
            {
                'method'          : "POST",
                'folder_id'       : this.$props.folder_id,
                'totalSlides'     : this.slides,
                'currentSlide'    : this.currentSlide,
                'reservation'     : reservationData,                
                'slidesData'      : slidesData        
            }).then(response => {

                this.startTimer();                
                this.socket.emit('START_SESSION', this.getSessionData()); 
            });

        },
        async postLessonEndSessionHistory(reservationData) 
        {
            let slidesData = await this.getAllSlideData();

            if (this.$props.isBroadcaster == false) {                       
                alert ("Member is not allowed to end a session");
                return false
            }    

            //@note: save session history
            axios.post("/api/postLessonHistory?api_token=" + this.api_token,
            {
                'method'          : "POST",
                'folder_id'       : this.$props.folder_id,
                'totalSlides'     : this.slides,
                'currentSlide'    : this.currentSlide,
                'reservation'     : reservationData,                
                'slidesData'      : slidesData                
            }).then(response => {
       
                if (this.$props.isBroadcaster == true) 
                {   
                    console.log("session end was broadcasted");

                    this.stopTimer();  
                    this.showMemberFeedbackModal();
                    this.socket.emit('END_SESSION', this.getSessionData());  
                }             

            });
           
        },

        showMemberFeedbackModal(){
       
            this.$refs['memberFeedback'].showMemberFeedbackModal(this.reservation);
        },
        showRatingModal() {
            this.$refs['satisfactionSurvey'].showRatingAndFeedbackModal(this.reservation);
        },
        prepareNewSlide() {
            this.$refs['slideUploader'].prepareSlider(this.$props.reservation, this.slides + 1);
        },
        userUploadedImage(file) 
        {
            this.newBackgroundImage = file.fullpath;
            this.createNewSlide();
            this.setSlideBackgroundImage(this.currentSlide, file.fullpath);
            this.$forceUpdate();            
        },
        createNewSlide() 
        {

            let currentSlideCount = this.slides;

            this.slides     = this.slides + 1;            
            let index       = this.slides;

            //create a canvas based on the current index
            this.createCanvas(index);

            this.canvas[index]  = new fabric.Canvas('canvas'+index, {
                backgroundColor : "#fff"
            });    

            //New Image On the Background we will set the scale to default = 1
            this.canvas[index]['scale'] = 1;
            this.audioFiles      = null;
            
            this.currentSlide           = index;
            this.goToSlide(index);     

            if (this.$props.isBroadcaster == true) {  

                let memberCanvasData = {
                    'slidesCount'   : currentSlideCount,
                    'currentslide'  : this.currentSlide,
                    'channelid'     : this.channelid,
                    'sender'        : {
                                        userid: this.user_info.id,
                                        username: this.user_info.username
                                    },
                    'recipient'     : this.getRecipient(),
                    'canvasid'      : this.canvas[this.currentSlide],
                    'canvasData'    : this.canvasGetJSON(),
                    'canvasZoom'    : this.canvas[this.currentSlide]['scale'],
                    'canvasDelta'   : this.delta,
                    'backgroundURL': this.newBackgroundImage 
                };
                

                this.socket.emit('CREATE_NEW_SLIDE', memberCanvasData);  
            } else {            
                this.viewerCurrentSlide = this.currentSlide;
            }
            this.autoSelectTool();
        },

        createCanvas(index) {

            //added to make canvas visible if the index is the first one
            let visibility = 'hidden';
            let display = 'none';

            if (index == 1) {
                visibility = 'visible';
                display = 'block';
            } 

            //Editor Container Element
            let editorElement = document.createElement("div");
            editorElement.setAttribute("id",    "editor" + index);
            editorElement.setAttribute("style", "overflow: hidden; clear: both; visibility: " + visibility + "; display:"+ display +";");

            let slideContainer = document.getElementById('slide-container');
            slideContainer.append(editorElement);            

            //Add canvas to the editor
            let canvasElement = document.createElement("canvas");
            canvasElement = document.createElement('canvas');
            canvasElement.setAttribute("id",    "canvas" + index);
            canvasElement.setAttribute("width", this.canvas_width);
            canvasElement.setAttribute("height", this.canvas_height);
            canvasElement.setAttribute("style", "border: 7px solid rgb(0, 118, 190); height: 100%;");

            let mediaContainer = document.getElementById('editor'+ index);
            mediaContainer.append(canvasElement);            
        },        
        getSlideMaterials(reservation) 
        {

            console.log("hello", reservation);


            this.isLoading = true;

            axios.post("/api/getLessonMaterialSlides?api_token=" + this.api_token,
            {
                method              : "POST",
                scheduleID          : reservation.schedule_id,
                memberID            : reservation.member_id,
                lesson_time         : reservation.lesson_time

            }).then(response => {     
            
                if (response.data.success === true) {

                    this.isLoading = false;

                    //Breadcrumbs
                    this.segments = response.data.folderURLArray;

                    //Slide Count 
                    this.slides  = (response.data.files).length;

                    if (this.slides == 0) {
                       
                        this.slides = 0;
                  
                        this.createNewSlide();

                
                        this.$nextTick(function(){ 

                            //DETERMIN IF USER HAS SLIDE ACCESS AND ENABLE BROADCASTER MODE
                            this.userSlideAccess();
                            //START SLIDE
                            this.startSlide();
                            //LOAD AUDIO
                            this.loadAudio();
                            this.$refs['slideSelector'].closeSlideSelector();                        
                        });
                        
                    } else {
                    

                        //Array of images
                        this.imageURL = response.data.files;
                        this.audioFiles = response.data.audioFiles;   


                        for (var i = 1; i <= this.slides; i++) 
                        {
                            this.createCanvas(i);

                            this.canvas[i]  = new fabric.Canvas('canvas'+i, {
                                backgroundColor : "#fff"
                            });  
                    
                            this.setSlideBackgroundImage(i, this.imageURL[i-1]);

                            this.$forceUpdate();

                            if (i ==  this.slides)
                            {

                                
                                this.$nextTick(function()
                                { 
                                    //DETERMIN IF USER HAS SLIDE ACCESS AND ENABLE BROADCASTER MODE
                                    this.userSlideAccess();
                                    //this.slides = (response.data.files).length;


                                    //START SLIDE
                                    this.startSlide();

                                    //LOAD AUDIO
                                    this.loadAudio()    


                                    this.$refs['slideSelector'].closeSlideSelector();
                                });

                            }
                        }                    
                    
                    }

              

                } else {

                    this.canvas[i]  = new fabric.Canvas('canvas'+i, {
                        backgroundColor : "#fff"
                    });

                    //@todo: get custom slides when restared
                }
			});

        }, 

        alignCanvas() {
            try {
                var delta = new fabric.Point(0, 0);        
                this.canvas[this.currentSlide].relativePan(delta); 
                this.rescale(1);   
            } catch (error) {
                console.log("canvas alignCanvas error", error);
            }

        },
        reOffset(e){

            var canvas = document.getElementById("canvas"+ this.currentSlide);
            let clientRect = canvas.getBoundingClientRect();
            this.offsetX = clientRect.left;
            this.offsetY = clientRect.top; 
        },
        // draw everything in pixels coords
        rescale(scale) {

            try {

                this.canvas[this.currentSlide].setZoom(scale);
                this.canvas[this.currentSlide].requestRenderAll();
                this.canvas[this.currentSlide]['scale'] = scale;

                //tool and send canvas json to viewer
                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);    

                
                let zoomedIn = setInterval(() => {
                    this.isZoomOut = false;
                    this.isZoomIn = false;
                    clearInterval(zoomedIn); 
                }, 50); 


            } catch (error) {
                console.log("canvas setZoom errror", error);
            }
        },

        userSlideAccess() 
        {
            if (this.$props.isBroadcaster == false) 
            {
                //Your are not a broadcaster
                this.disableSelect();
                this.deactivateSelector();
                this.canvas[this.currentSlide].isDrawingMode = false;
                //this.reOffset();
            } else {
                //Your are the viewer  
                this.mouseClickHandler();
                this.activatePencil();
                //this.reOffset();
            }

            this.keyPressHandler();
            this.isSessionActive()
            
        },      

        hideTimer() {
          const elemCountdown = document.getElementById('startSessionContainer');
                if (elemCountdown) {
                    elemCountdown.style.display = 'none';            
                }        
        },
        refreshMemberSlides() {

            if (this.$props.isBroadcaster == true) {   
        
                let slidesData = {
                    'currentSlide'  : this.currentSlide,
                    'channelid'     : this.channelid,
                    'sender'        : {
                                        userid: this.user_info.id,
                                        username: this.user_info.username
                                    },
                    'slide_folder_id': this.newFolderID,
                    'recipient'     : this.getRecipient()
                };            

                this.socket.emit('TUTOR_SELECTED_NEW_SLIDES', slidesData);  
            }
            
        },
        isSessionActive() 
        {

            //@note(1): determine if a session is active when page is refreshed
            //@note(2): if session is active calculate the remaining or running time (negative value)
           
          
            if (this.$props.lesson_history) 
            {
                //@note:  user has lesson history, we will update the minutes and start the timer immediately

                if (this.$props.lesson_history.time_ended  == "0000-00-00 00:00:00") 
                {
                    //@note: this will start session timer automatically since the session was started before
                    this.startTimer();

                    if (this.isTimerStarted == true) {
                        //note(1): change of slides detected
                        //@note (2): if timer is started we will reset countdown.

                        this.timer = this.resetTimerValue // reset to orginal value 
                        this.updateMinutes();

                    } else {

                        //update dates minutes
                        this.updateMinutes();
                    }

                } else {
                    
                    console.log("lesson ended on : ", this.$props.lesson_history.time_ended)

                    if (this.$props.isBroadcaster == true) 
                    {   
                        this.hideTimer();
                        this.showMemberFeedbackModal();
                    } else {
                        this.showRatingModal();
                    }

                    this.stopTimer();

                } 

            } else {


                //hide countdown timer  
                console.log("no lesson history, hide countdown timer");
                
                const elemCountdown = document.getElementById('countDownTimerContainer');
                if (elemCountdown) {
                    elemCountdown.style.display = 'none';            
                }

                const endSession = document.getElementById('endSessionContainer');
                if (endSession) {
                    endSession.style.display = 'none';                    
                }
                
            }

        },
        setSlideBackgroundImage(index, imageURL) 
        {            
            try {
                this.canvas[index].setBackgroundImage(imageURL, this.canvas[index].renderAll.bind(this.canvas[index]), {
                    // Optionally add an opacity lvl to the image
                    //backgroundImageOpacity: 0.5,
                    // should the image be resized to fit the container?
                    scaleX:1,
                    scaleY:1,
                    top: 0,
                    left: 0,
                    originX: 'left',
                    originY: 'top',                                                        
                    backgroundImageStretch: true,
                });

            } catch (error) {
                console.log("no image background...");
            }

        
            //New Image On the Background we will set the scale to default = 1
            this.canvas[index]['scale'] = 1;
        },            
        loadImage(index, imageURL) 
        {          
                             
            let ctx =  this.canvas[index].getContext("2d");
            this.canvas[index].width = this.canvas_width;
            this.canvas[index].height = this.canvas_height;

            var background = new Image();
            background.src = imageURL;
            background.onload = (bg) => {
                ctx.drawImage(background,0,0, this.canvas_width, this.canvas_height);   
            } 
            background.setAttribute('crossorigin', 'anonymous'); // works for me
        },
        getRecipient() { 
            return this.$props.recipient_info;
        },
        updateCanvas(canvas, data)
        {

            canvas.loadFromJSON(data, this.disableCanvas, (o, object) => {            
                if(this.$props.isBroadcaster == false) {
                    this.deactivateSelector()                
                }                
            });            
        },
        disableCanvas() 
        {
            this.canvas[this.currentSlide].forEachObject(function(o) {
                o.selectable = false;
                o.defaultCursor = 'not-allowed';
                o.hoverCursor = "not-allowed";
            });
            this.canvas[this.currentSlide].discardActiveObject();   
            this.canvas[this.currentSlide].requestRenderAll();
            //this.canvas[this.currentSlide].renderAll();
        },
        canvasSendJSON(canvasID, canvasData) 
        { 
            let recipient = this.getRecipient();
            console.log("scale", this.canvas[this.currentSlide]['scale']);

            let memberCanvasData = {
                'currentSlide'  : this.currentSlide,
                'channelid'     : this.channelid,
                'sender'        : {
                                    userid: this.user_info.id,
                                    username: this.user_info.username
                                  },
                'recipient'     : recipient,
                'canvasid'      : canvasID,
                'canvasData'    : canvasData,
                'canvasZoom'    : this.canvas[this.currentSlide]['scale'],
                'canvasDelta'   : this.delta,
            };       
            this.socket.emit('SEND_DRAWING', memberCanvasData);  
        },       
        canvasGetJSON() 
        {
            return this.canvas[this.currentSlide].toJSON();           
        },        
        drawRealTime(pointer, options) {
            if (this.isDrawing) {
                 this.canvas[this.currentSlide].freeDrawingBrush.onMouseMove(pointer, options);               
            }
        },
        sendCavasData() {
            let recipient  = this.getRecipient();
            console.log("sendCavasData");            
            socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender });                      
        },
        convertTZ(date, tzString) {
            return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
        },
        updateMinutes() 
        {        
        
            var now = new Date();
            var nowJTZ= this.convertTZ(now, "Asia/Tokyo");

            //Force the database time to be a tokyo time
            var started = new Date(this.$props.lesson_history.time_started);
            var startedJTZ= this.convertTZ(started, "Asia/Tokyo");
            
            var diff = this.diff(nowJTZ, started);

            console.log("started: ", started)
            console.log("now: ", nowJTZ)
            console.log(diff)
         
            if (diff.days >= 1) {

                alert ("this lesson has lapsed for more than one day, please consult admin");

                //@todo: alert admin and end session
                
                this.disableSession();


                var daysInSeconds           = (((diff.days * 24) * 60) * 60);                
                var hoursInSeconds          = ((diff.hours * 60) * 60);
                var minsInSeconds           =  diff.minutes * 60;                 
                var seconds                 =  diff.seconds;           

                //get the total seconds to dedecut
                var totalSecondsToDeduct    = hoursInSeconds + minsInSeconds + seconds //add the difference for the seconds  
                this.timer =  totalSecondsToDeduct;

                console.log("newTimer with days ", daysInSeconds, diff);
            
             } else if  (diff.days == null && diff.hours >= 1) {

                if (diff.hours >=1) {
                    var hoursInSeconds          = ((diff.hours * 60) * 60);
                } else {
                    var hoursInSeconds = 0;
                }

                if (diff.minutes >= 1) {
                    var minsInSeconds           = diff.minutes * 60; 
                } else {
                    var minsInSeconds           = 0; 
                }
                
                var seconds                 = diff.seconds; 

                //get the total seconds to dedecut
                var totalSecondsToDeduct    = hoursInSeconds + minsInSeconds + seconds //add the difference for the seconds  

                //@note: the hours is more than 30 minutes, so we negate the value
                this.timer                  =  this.timer - (totalSecondsToDeduct);

                console.log("newTimer with hours ", totalSecondsToDeduct);

            } else if (diff.hours == null && diff.minutes >= 1 ) {           

                //timer with minutes and seconds only
                var newTimerMinutes = this.timer - (diff.minutes * 60)     
                var newTimer        = newTimerMinutes - diff.seconds //add the difference for the seconds     
                this.timer          = newTimer;

                console.log("newTimer with only minutes ", newTimer);

            } else if (diff.hours >= 1 && diff.minutes >= 1) {

                //timer with hours and minutes and seconds
                if (diff.hours >= 1) {
                    var hoursInSeconds      = ((diff.hours * 60) * 60);
                } else {
                    var hoursInSeconds      = 0;
                }

                var newTimerMinutes     = this.timer - ((diff.minutes * 60) +  hoursInSeconds);
                var newTimer             = newTimerMinutes - diff.seconds //add the difference for the seconds  

                this.timer = newTimer;
                console.log("newTimer with hours and minutes", newTimer, diff);

            } else {

                //timer with seconds only
                this.timer = this.timer - diff.seconds;
                console.log("we have seconds update", diff.seconds)
            }           

        },
        diff(now, started) 
        {           
            let d            = (new Date(now)) - (new Date(started));
            let weekdays     = Math.floor(d/1000/60/60/24/7);
            let days         = Math.floor(d/1000/60/60/24 - weekdays*7);
            let hours        = Math.floor(d/1000/60/60    - weekdays*7*24            - days*24);
            let minutes      = Math.floor(d/1000/60       - weekdays*7*24*60         - days*24*60         - hours*60);
            let seconds      = Math.floor(d/1000          - weekdays*7*24*60*60      - days*24*60*60      - hours*60*60      - minutes*60);
            let milliseconds = Math.floor(d               - weekdays*7*24*60*60*1000 - days*24*60*60*1000 - hours*60*60*1000 - minutes*60*1000 - seconds*1000);
            let t = {};
            
            ['weekdays', 'days', 'hours', 'minutes', 'seconds', 'milliseconds'].forEach(q=>{ if (eval(q)>0) { t[q] = eval(q); } });

            return t;         
        },        
       getTime() {
            if (this.timer >= 0) {
                return this.secondsToHms(this.timer)    
            } else {
                //Add "warning class" countDownTimer
                var element = document.getElementById("countDownTimer");
                element.classList.add("text-danger");
                return this.secondsToHms(Math.abs(this.timer))
            }
               
        },
        secondsToHms(d) {
            var date = new Date(0);
            date.setSeconds(d); // specify value for SECONDS here
            var timeString = date.toISOString().substring(11, 19);
            if (this.timer >= 0) {
                return timeString;
            } else {
                return "-"+timeString;
            }            
        },
        secondsToHms_PositiveOnly(d) {
            d = Number(d);
            var h = Math.floor(d / 3600);
            var m = Math.floor(d % 3600 / 60);
            var s = Math.floor(d % 3600 % 60);
            var hDisplay = h > 9 ? Math.abs(h) +":" : "0"+ Math.abs(h) +":";
            var mDisplay = m > 9 ? Math.abs(m) +":" : "0"+ Math.abs(m) +":";
            var sDisplay = s > 9 ? Math.abs(s)  : "0"+ Math.abs(s);
            return hDisplay + mDisplay + sDisplay; 
        },         
        startTimer() {
        
            if (this.isTimerStarted == false) {
                this.showSessionControls();
                this.myIntervalTimer = setInterval(this.updateTimer, this.timerSpeed);        

                //turn on the timer
                this.isTimerStarted = true    
            }
        },        
        updateTimer() {            
            this.setTimer();
            this.timer--;
        },           
        setTimer() {        
            var elem = document.querySelector('#countDownTimer');
            elem.innerHTML = this.getTime();           
        },
        stopTimer() {
            clearInterval(this.myIntervalTimer); 
        },
        showSessionControls() {       

            //@note: Session Controls will Show OR Hide (Start Session  / End Session / Countdown timer)

            const elemCountdown = document.getElementById('countDownTimerContainer');
            if (elemCountdown) {
                elemCountdown.style.display = 'block';            
            }         

            const endSession = document.getElementById('endSessionContainer');
            if (endSession) {
                endSession.style.display = 'block';    
            }

            const startSession = document.getElementById('startSessionContainer');
            if (startSession) {
                startSession.style.display = 'none';    
            }
       },
        changeColor() {
            this.setPreviewColor( this.brushColor )
            this.autoSelectTool();
            //this.canvas[this.currentSlide].getActiveObject().set({fill: this.brushColor});
        },        
        startSlide() {
            this.currentSlide = 1;
            this.autoSelectTool();
            
            let data = this.canvasGetJSON();

            this.canvasSendJSON(this.canvas[this.currentSlide], data); 
            document.getElementById('editor'+ this.currentSlide).style.visibility = "visible";

            //Load List of Audio Array
            //this.loadAudio()

            //@todo: if tutor then broadcast current slide
            if (this.$props.isBroadcaster == true) {            
                let data = {
                    'channelid': this.channelid,
                    'num': this.currentSlide
                }
                this.socket.emit('GOTO_SLIDE', data);                  
            }

        },
        loadAudio() {
            this.$refs['audioPlayer'].loadAudioList(this.audioFiles, this.currentSlide);
        },
        goToSlide(slide) {          

            //AUDIO
            this.$refs['audioPlayer'].stopAudio();
            this.loadAudio(slide);

            this.currentSlide = slide;
            this.viewerCurrentSlide = slide;

           

            for (var i = 1; i <= this.slides; i++) 
            {
                if (i == slide) {   

                    let editorElement = document.getElementById('editor'+ i);

                    if (editorElement) {
                        editorElement.style.visibility = "visible";
                        editorElement.style.display = "block";                                              
                    }
                                 

                    //@todo: if tutor then broadcast get the drawn canvas
                    if (this.$props.isBroadcaster == true) {      
                        let data = this.canvas[slide].toJSON(); 
                        this.canvasSendJSON(this.canvas[slide], data);
                    }

                    //@TODO:  GET HISTORY FROM DATABASE                

                } else {

                    let editorElement = document.getElementById('editor'+ i);

                    if (editorElement) {
                        editorElement.style.visibility = "hidden";
                        editorElement.style.display = "none";        
                    }

                }
            }

        },
        lastSlide() {
            this.currentSlide = this.slides;
            this.autoSelectTool();

            let data = this.canvasGetJSON();
            this.canvasSendJSON(this.canvas[this.currentSlide], data);               
        },
        prevSlide(delegateToNode) {

            //the audio index needs to be reset since it is global
            this.$refs['audioPlayer'].resetAudioIndex();            
            this.$refs['audioPlayer'].stopAudio();

            if (this.currentSlide > 1) {

                this.currentSlide--;
                this.autoSelectTool();
                
                //let data = this.canvasGetJSON();
                //this.canvasSendJSON(this.canvas[this.currentSlide], data);     

                let data = {
                    'channelid': this.channelid,
                    'num': this.currentSlide
                }
           
                //if (delegateToNode == true) {
                    this.socket.emit('GOTO_SLIDE', data);                    
                //}
            }
        },
        nextSlide(delegateToNode) {

            //the audio index needs to be reset since it is global
            this.$refs['audioPlayer'].resetAudioIndex();

            this.$refs['audioPlayer'].stopAudio();

            this.rescale(1);

            if (this.currentSlide < this.slides) 
            {
                this.currentSlide ++;            
                this.autoSelectTool(); 


                //let data = this.canvasGetJSON();
                //this.canvasSendJSON(this.canvas[this.currentSlide], data); 



                let data = {
                    'channelid': this.channelid,
                    'num': this.currentSlide
                }


                //if (delegateToNode == true) {
                    this.socket.emit('GOTO_SLIDE', data);     
               // }
                
            } 
        },
        autoSelectTool() {
            if (this.isSelector) this.activateSelector();
            else if (this.isPencil) this.activatePencil();
            else if (this.isBrush) this.activateBrush();
            else if (this.isLine) this.activateLine();
            else if (this.isText) this.activateTextEditor();
            else if (this.isCircle) this.activateCircle();
        },
        customSelectorBounds(fabric) {
            fabric.Object.prototype.transparentCorners = false;
            fabric.Object.prototype.cornerColor = 'blue';
            fabric.Object.prototype.cornerStyle = 'circle';
        },
        keyPressHandler(e) {
            //console.log("keypress handler ");
            window.onkeydown = (event) => {
            
                if (event.key === "Delete") {
                    this.deleteObj();
                    return false;
                } else {                
                  //  let data = this.canvasGetJSON();
                   // this.canvasSendJSON(this.canvas[this.currentSlide], data);  
                };
            };
        },
        mouseClickHandler(e) 
        {

            this.canvas[this.currentSlide].on('mouse:down', (options) => 
            {

                var pointer = this.canvas[this.currentSlide].getPointer(options.e);

                if (this.isText == true)
                {
                    this.canvas[this.currentSlide].defaultCursor = 'text';  

                    this.mouseX = pointer.x ;
                    this.mouseY = pointer.y; 

                    var selectedObj = this.canvas[this.currentSlide].getActiveObject();
                    
                    if (!selectedObj) {          

                        if (this.isTextEditing == true) {
                            this.isTextEditing = false;
                        } else {
                            this.$bvModal.show('modalAddInputText');                           
                        }
                        
                    } else {

                        this.resetModes();     
                        this.isText             = true;
                        this.isTextEditing      = true;
                        //this.isSelector = true;
                    }                    

                } else if (this.isSelector) {

                    this.canvas[this.currentSlide].defaultCursor = 'default';   

                } else if (this.isDragger) {

                    this.panning = true;
                    
                    this.canvas[this.currentSlide].defaultCursor = 'grabbing';   
                    document.querySelectorAll('.upper-canvas ').forEach(function(element) {                            
                        element.style.cursor = 'grabbing';
                    });              

                }  else {
                
                    this.canvas[this.currentSlide].defaultCursor = 'move';   
                    this.$forceUpdate();

                }


            }).on('mouse:move', (options) => {

                if (this.isDragger) {

                    if (this.panning) 
                    {
                        this.delta = new fabric.Point(options.e.movementX, options.e.movementY);
                        this.canvas[this.currentSlide].relativePan(this.delta);
                     
                        let data = this.canvasGetJSON();
                        this.canvasSendJSON(this.canvas[this.currentSlide], data);   
                    }
                }

            }).on('mouse:up', () => {

               
                if (this.isText == true) {

                    this.canvas[this.currentSlide].defaultCursor = 'text';  

            
                } else if (this.isSelector) {

                     this.canvas[this.currentSlide].defaultCursor = 'default';   

                } else  if (this.isDragger) {

                        //reset to default cursor of the current
                    this.canvas[this.currentSlide].defaultCursor = 'grab';

                    document.querySelectorAll('.upper-canvas ').forEach(function(element) {                            
                        element.style.cursor = 'grab';
                    });      

                    this.panning  = false;
                    this.delta    = null;



                } else {
                    this.canvas[this.currentSlide].defaultCursor = 'default';   
                }

                //tool and send canvas json to viewer
                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);    

            }).on('mouse:out', (options) => {
                if (this.isDragger == true) 
                {
                    this.canvas[this.currentSlide].defaultCursor = 'grab';
                    document.querySelectorAll('.upper-canvas ').forEach((element) => { element.style.cursor = 'grab'; });


                    this.canvas[this.currentSlide].renderAll();

                    // Put your mousedown stuff here
                    this.isDraggerMouseDown = false;

                } else {
                
                    //console.log ("the dragger is out!!", this.isDragger)
                }

            });

           
        },   

        resetModes()  {

            //this.canvas[this.currentSlide].isDrawingMode = false;
            this.isDrawingLine      = false;
            this.isDrawing          = false;
            this.isDrawingCircle    = false;           

            //modes
            this.isSelector     = false;
            this.isDragger      = false;
            this.isText         = false;
            this.isBrush        = false;
            this.isPencil       = false;
            this.isLine         = false;
            this.isCircle       = false;

            this.isZoomIn       = false;
            this.isZoomOut      = false;
        },
        handleBrushColors() {                
            let colors = document.getElementsByClassName("colors")[0];

            colors.addEventListener("click", (event) => {
                this.brushColor = event.target.value || "#000000";

                if (this.isBrush) {
                    this.activateBrush()  
                } else if(this.isPencil) {
                    this.activatePencil()
                } else if (this.isLine) {
                    this.activateLine()
                } 

                //set Preview
                this.setPreviewColor( this.brushColor )                  
            });
        },
        setPreviewColor(color) {
            this.colorPreviewStyle.backgroundColor = color;
        },
        setBrushColor(canvasNum, color) {
            this.brushColor = color;
            this.activateBrush(canvasNum);
        },
        setBrushStroke(canvasNum, stroke) {
            this.stroke = stroke;
            if (this.isBrush) {
                this.activateBrush(canvasNum);
            }
        },
        activateSelector() {        
            this.removeEvents();
            this.resetModes();
            this.enableSelect();
            this.changeObjectSelection(true);
            this.mouseClickHandler();
            this.isSelector = true;           

            this.canvas[this.currentSlide].defaultCursor = 'default';   
            this.canvas[this.currentSlide].selection = true;

        },
        activateDragger() {    

            console.log("dragger is activated")    

            this.removeEvents();
            this.resetModes();
            this.disableSelect();
            this.changeObjectSelection(true);
            this.mouseClickHandler();

            this.isDragger = true;
            this.drag();          
        },
        setCursosType(type) {
            this.canvas[this.currentSlide].defaultCursor = type;
            document.querySelectorAll('.upper-canvas ').forEach(function(element) {                            
                element.style.cursor = type;
            });
        },
        drag() {    
            let isGrabbing = false;
            let canvas = this.canvas[this.currentSlide];

            this.setCursosType('grab');                 

            canvas.on('mouse:down', (object) => {

                isGrabbing = true;
                this.setCursosType('grabbing');

            }).on('mouse:move', (object) => {

                if (this.isDragger == true && isGrabbing == true && object && object.e) {
                     this.setCursosType('grabbing');
                }

            }).on('mouse:up', (object) => {               
            
                isGrabbing = false;
                this.setCursosType('grab');
            });

        },
        deactivateSelector() {        
            this.removeEvents();
            this.resetModes();
            this.disableSelect();
            this.changeObjectSelection(false);
            this.isSelector = false;           
            this.canvas[this.currentSlide].selection = false;
            this.canvas[this.currentSlide].defaultCursor = 'not-allowed';
        },      

        activateRedo() {

            console.log(this.history[this.currentSlide].length +" " + this.historyCounter)

            let currentSlideLength = (this.history[this.currentSlide].length - 1);

            if (this.historyCounter < currentSlideLength) 
            {
                this.historyCounter++;                
                let prevData = this.history[this.currentSlide][this.historyCounter];
                this.updateCanvas(this.canvas[this.currentSlide], prevData.data); 
            } else {
                console.log("no more things to redo")            
            }
        
        },
        activateUndo() {
            if (this.historyCounter > 0) 
            {
                this.historyCounter--;                
                let prevData = this.history[this.currentSlide][this.historyCounter];
                this.updateCanvas(this.canvas[this.currentSlide], prevData.data); 
            } 
        },
        activateZoomIn() 
        {            
            this.isZoomIn = true;
            this.isZoomOut = false;    

            let newScale = this.canvas[this.currentSlide]['scale'] + 0.10;

            if (newScale < 0.01) {
                this.rescale(0.10);
            } else {
                this.rescale(newScale);
            }  
        },
        activateZoomOut() {
 
            this.isZoomOut = true;
            this.isZoomIn = false;

            let newScale = this.canvas[this.currentSlide]['scale'] - 0.10

            if (newScale < 0.01) {
                this.rescale(0.10);          
            } else {
                this.rescale(newScale);
            }          
        },
        activateBrush() 
        {
            this.removeEvents(); 
            this.resetModes();
            this.isBrush = true;
            this.draw();
        },        
        activatePencil()  
        {           
            this.removeEvents();
            this.resetModes();
            this.isPencil = true; 
            this.draw();
        },
        activateLine() 
        {

            this.removeEvents();   
            this.resetModes();            
            this.disableSelect();

            this.isLine = true;
            this.drawLine();
        },
        activateCircle() 
        {
            this.resetModes();
            this.removeEvents();
            this.disableSelect();
            this.isCircle = true;
            this.drawCircle();                  
        },
        activateTextEditor() 
        {
            this.removeEvents(); 
            this.resetModes();     
            this.disableSelect();
            this.canvas[this.currentSlide].defaultCursor = 'text';
            this.isText = true;
            this.mouseClickHandler();
        },
        addInputText() 
        {
            let id = (new Date()).getTime().toString().substr(5);

            let fabricText = new fabric.IText(this.inputText, {
                id: id,
                objecttype: 'text',
                left: this.mouseX,
                top: this.mouseY,
                            
                fontFamily: 'Times New Roman',
                fill: this.brushColor,
                fontSize: '40',
                fontWeight: "bold",
                fontStyle: 'normal',

                //stroke: "red",
                //textBackgroundColor: "green",
                //strokeWidth: "1",                
                //"underline: false
                //"overline: false
                //"linethrough: false"
                //deltaY: false

                evented: true,
                selectable: true,
                editable: true,
            });
            this.canvas[this.currentSlide].add(fabricText);


            fabricText.on('editing:entered', ()  =>
            {
                console.log("editing entered # 1")
                fabricText.set("fill", this.brushColor);
                this.canvas[this.currentSlide].renderAll();

                //hack for modal unable to edit iText bug
                $('#modal-lesson-slider').find('.modal-content').attr("tabindex", 9999999999);
               
            });

            let data = this.canvasGetJSON();
            this.canvasSendJSON(this.canvas[this.currentSlide], data);     


        },        
        drawCircle() {
            
            let canvas = this.canvas[this.currentSlide];

            canvas.on('mouse:down', (object) => {

                this.isDrawingCircle = true;               
                var pointer = canvas.getPointer(object.e);                
                this.origX = pointer.x;
                this.origY = pointer.y;
                this.circle = new fabric.Ellipse({
                    left:   this.origX,
                    top:    this.origY,
                    rx: 0,
                    ry: 0, 
                    strokeWidth: this.stroke,
                    fill: 'transparent',
                    stroke: this.brushColor,
                    selectable: true,
                    transparentCorners: false,
                    hasBorders: false,
                    hasControls: false
                });

               canvas.add(this.circle).setActiveObject(this.circle);

            }).on('mouse:move', (object) => {

                if (this.isDrawingCircle ) {

                     this.disableSelect();
                    

                    var pointer =canvas.getPointer(object.e);
                    var activeObj = canvas.getActiveObject();

                    if (this.origX > pointer.x) {
                        activeObj.set({
                            left: Math.abs(pointer.x)
                        });
                    }

                    if (this.origY > pointer.y) {
                        activeObj.set({
                            top: Math.abs(pointer.y)
                        });
                    }
                                        
                    activeObj.set({
                        rx: Math.abs(this.origX - pointer.x) / 2
                    });

                    activeObj.set({
                        ry: Math.abs(this.origY - pointer.y) / 2
                    });

                    activeObj.setCoords();
                    canvas.renderAll(); 

                    //let data = this.canvasGetJSON();
                    //this.canvasSendJSON(this.canvas[this.currentSlide], data);         
                }         

            }).on('mouse:up', (object) => {

                this.isDrawingCircle = false;

                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);   

                //[ADD TO HISTORY]    
                /*            
                this.historyCounter++;
                this.history[this.currentSlide].push({                   
                        'data': this.canvasGetJSON()                
                });
                */

            });

        },        
        drawLine() {   
      
            this.canvas[this.currentSlide].on('mouse:down', (object) => {

                this.isDrawingLine = true;                

                var pointer = this.canvas[this.currentSlide].getPointer(object.e);
                var points = [ pointer.x, pointer.y, pointer.x, pointer.y ];

                this.line = new fabric.Line(points, {
                    strokeWidth: this.stroke,
                    //strokeDashArray: [15, 5],
                    fill: this.brushColor,
                    stroke: this.brushColor,
                    originX: 'center',
                    originY: 'center',
                    selectable: true,
                    hoverCursor: "crosshair"
                });
                this.canvas[this.currentSlide].add(this.line);

            }).on('mouse:move', (object) => {
                if (this.isDrawingLine ) {
                   
                   this.disableSelect();


                    var pointer = this.canvas[this.currentSlide].getPointer(object.e);
                    this.line.set({ x2: pointer.x, y2: pointer.y });
                    this.line.setCoords();
                    this.canvas[this.currentSlide].renderAll();

                    //let data = this.canvasGetJSON();
                    //this.canvasSendJSON(this.canvas[this.currentSlide], data);         
                }

            }).on('mouse:up', (object) => {
             
                this.isDrawingLine = false;
                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);

            });

        },
        draw() 
        {
            this.isDrawing = false;          
            this.canvas[this.currentSlide].freeDrawingBrush.color = this.brushColor;   

            if (this.isPencil) {
                this.canvas[this.currentSlide].freeDrawingBrush.width = 1;
                this.canvas[this.currentSlide].isDrawingMode = true;

            } else if (this.isBrush) {
                this.canvas[this.currentSlide].freeDrawingBrush.width = this.stroke;  
                this.canvas[this.currentSlide].isDrawingMode = true;

            } else {
                 this.canvas[this.currentSlide].isDrawingMode = false;
            }

            this.canvas[this.currentSlide].on('mouse:down', ({e})  => {

                if (this.isBrush || this.isPencil) {
                    this.isDrawing = true;
                } else {
                    this.isDrawing = false;
                }
                
            }).on('mouse:move', (object) => {

                if (this.isDrawing) {

                    //const pointer = this.canvas[this.currentSlide].getPointer(object);

                    //const options = {pointer, e:{}} // required for Fabric 4.3.1
                    //this.drawRealTime(pointer, options);

                    //console.log("draw drag!")
                    
                   // let data = this.canvasGetJSON();
                    //this.canvasSendJSON(this.canvas[this.currentSlide], data);      
                }
            }).on('mouse:up', (object) => {
                this.isDrawing = false;

                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);
            
            });            

        },
       
        resetInputTextModal() {
            this.inputText = "";
        },
        removeEvents() {

            this.canvas[this.currentSlide].isDrawingMode = false;
            this.canvas[this.currentSlide].selection = false;
            this.canvas[this.currentSlide].off('mouse:down');
            this.canvas[this.currentSlide].off('mouse:up');
            this.canvas[this.currentSlide].off('mouse:move');
        },
        changeObjectSelection(value) {
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = value;
            });
            this.canvas[this.currentSlide].renderAll();
        },
        enableSelect() {

            this.canvas[this.currentSlide].defaultCursor = 'default';
        
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = true;
                obj.evented = true;
                obj.hasControls = true;
                
            });
            this.canvas[this.currentSlide].renderAll();        
        },
        disableSelect() 
        {

            if (this.isText == true) {

                this.canvas[this.currentSlide].defaultCursor = 'text';  

                console.log("disable select")

            } else if (this.isSelector) {
                    
                this.canvas[this.currentSlide].defaultCursor = 'default';   

            } else  if (this.isDragger) {

                this.canvas[this.currentSlide].defaultCursor = 'grab';  
                
                console.log("disable select:: grab")

            } else  if (this.isBrush || this.isPencil || this.isLine || this.isCircle) { 

                 this.canvas[this.currentSlide].defaultCursor = 'crosshair';

            } else {
            
                this.canvas[this.currentSlide].defaultCursor = 'default';   
            }
           
            
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = false;
                obj.evented = false;
                obj.hasControls = false;             });

            this.canvas[this.currentSlide].renderAll();            
        },
        setText() {
            var obj = this.canvas[this.currentSlide].getActiveObject();
            if (obj) {
                if (param == 'color') {
                    obj.setColor(value);
                } else {
                    obj.set(param, value);
                }
                this.canvas[this.currentSlide].renderAll();
            } 
            this.mouseClickHandler() 
        },
        deleteObj()
        {
            var selectedObj = this.canvas[this.currentSlide].getActiveObject();

            if (selectedObj) 
            {            
                if (selectedObj.type === 'activeSelection') {
                    selectedObj.canvas = this.canvas[this.currentSlide];
                    selectedObj.forEachObject((obj)=> {
                        selectedObj.canvas.remove(obj);

                        let data = this.canvasGetJSON();
                        this.canvasSendJSON(this.canvas[this.currentSlide], data);                                        
                    });
                } else if(selectedObj !== null ) {
                    this.canvas[this.currentSlide].remove(selectedObj);     

                    let data = this.canvasGetJSON();
                    this.canvasSendJSON(this.canvas[this.currentSlide], data);                
                }                
            }
            this.mouseClickHandler();
        }

    }
};
</script>


<style lang="scss">

    #audio-container {
        display: inline-block;
        vertical-align: middle;
        height: 70px;
    }
    
    #newSlideButton {
        font-size: 45px;
        display: inline-block;
        vertical-align: top;
        margin: 10px 0px 10px;
    }


</style>