<template>

    <div class="slides-container">

        <div style="display:none">
            {{ "Channel: "+ this.channelid }} <br>
            {{ "Server:" + this.$props.canvas_server }} <br>
            {{ "props folder ID:" + this.$props.folder_id }} <br>
            {{ "data current folder ID:" + this.currentFolderID }} <br>
            </div>

        <!--[start] toolbox -->
        <div id="toolbox" v-show="this.$props.is_broadcaster == true">
            <div class="tool-container">

                <!-- [START] TOOL WRAPPER -->
                <div class="tool-wrapper" >
                    
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



        <div id="slideSelectorContainer" v-if="this.$props.is_broadcaster == true">

            <LessonSelectorComponent
                ref="lessonSelectorComponent" 
                :user="this.user_info" 
                :api_token="this.api_token" 
                :csrf_token="this.csrf_token"/>

        </div>

        <!--[start] lessonSharedContainer -->
        <div id="lessonSharedContainer"></div>
        <!--[end] -->

        <!--[start] lessonSlide -->
        <div id="lessonSlide" class="left-container mb-2"> 
            <!--[start] slide selector for broadcaster -->
            <div id="slideSelectorButtonContainer" class="d-inline-block">
                <div type="button" class="btn-md" @click="selectSlides" v-show="this.$props.is_broadcaster == true">                    
                    <i class="far fa-images fa-lg"></i>
                </div>
            </div>
            <!--[end] slide selector for broadcaster -->

            <!--[start] breadcrumb -->
            <nav aria-label="breadcrumb" class="d-inline-block">
                <ol class="breadcrumb bg-transparent my-2 p-0" v-if="segments">
                    <li class="breadcrumb-item" aria-current="page">Home</li>
                    <li class="breadcrumb-item" v-for="segment in segments" :key="segment">
                    {{ segment }}
                    </li>                    
                </ol>
                <ol class="breadcrumb bg-transparent my-2 p-0" v-else>
                    <li class="breadcrumb-item text-danger" aria-current="page">No Lesson Selected</li>
                </ol>
            </nav>
            <!--[end] breadcrumb -->

            <!--[start] slide controls -->
            <div class="slide-controls d-inline-block" v-if="segments">                  
                <div class="buttons-container">
                    <div class="d-flex justify-content-center">
                        <div id="prev" class="tool" @click="prevSlide(true)" v-show="this.$props.is_broadcaster == true">
                            <i class="fa fa-backward" aria-hidden="true"></i>
                        </div>
                        <div id="slideInfo" class="tool font-weight-strong" style="width:150px">
                            <div v-show="this.$props.is_broadcaster == true">
                                Slide {{ this.currentSlide }} of {{ this.slides }}
                            </div>
                            <div v-show="this.$props.is_broadcaster == false">
                                Slide {{ this.viewerCurrentSlide }} of {{ this.slides }}
                            </div>
                        </div>
                        <div id="next" class="tool" @click="nextSlide(true)" v-show="this.$props.is_broadcaster == true">
                            <i class="fa fa-forward" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!--[end] slide controls -->

            <div id="audio-container" class="d-inline-block" v-if="segments">
                <AudioPlayerComponent ref="audioPlayer" :is-broadcaster="this.$props.is_broadcaster"></AudioPlayerComponent>
                <div id="newSlideButton" class="tool d-inline-block" @click="prepareNewSlide" >
                    <i class="far fa-file-alt" v-if="this.$props.is_broadcaster == true"></i>
                </div>
                <SlideUploaderComponent 
                    ref="slideUploader" 
                    :reservation="this.$props.reservation"
                    :folder_id="this.$props.folder_id"
                    :is-broadcaster="this.$props.is_broadcaster"
                    :api_token="this.api_token" 
                    :csrf_token="this.csrf_token"/>            
            </div>

            <div id="slide-container"></div>            
        </div>
         <!--[end] lessonSlide -->


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

        <TutorSlideNotesComponent 
            ref="TutorSlideNotes" 
            v-if="this.$props.is_broadcaster == true"        
            :api_token="this.api_token"
            :csrf_token="this.csrf_token"/>

    </div>
</template>


<script>
import {fabric} from "fabric";
import io from "socket.io-client";

//Uploader
//import SlideSelectorComponent from './SlideSelectorComponent.vue'

import LessonSelectorComponent from './LessonSelectorComponent.vue'


import SlideUploaderComponent from './SlideUploaderComponent.vue'
import AudioPlayerComponent from './AudioPlayerComponent.vue'
import TutorSlideNotesComponent from './TutorSlideNotesComponent.vue';

export default {
    name: "LessonSlider",
    components: { 
        //SlideSelectorComponent, 
        LessonSelectorComponent,
        SlideUploaderComponent, 
        AudioPlayerComponent,
        TutorSlideNotesComponent
    },
    props: {
        channelid       : Number,
        folder_id       : Number,
        reservation     : Object,
        user_info       : Object,
        user_image      : String,   
        member_info     : Object,                    
        recipient_info  : Object,    
        is_lesson_started : Boolean,   
        is_broadcaster  : Boolean,
        csrf_token      : String,		
        api_token       : String,
        canvas_server   : { type: [String], required: true}, 
        canvas_width    : { type: [String, Number], required: true },
        canvas_height   : { type: [String, Number],required: true }        
    },
    data() {

        return {

            isLoading: false,
            socket: null,

            //if user select new folder and or background image
            currentFolderID: null,
            newFolderID: null,
            newBackgroundImage: null,   

            //Canvas
            canvas  : [],
            segments: [],
            imageURL: [],

            //slides
            currentSlide: 1,
            viewerCurrentSlide: 1,
            slides: 0,

            //Action
            isDrawing: false,
            isDrawingLine:false,            
            isDrawingCircle:false,

            //Modes
            isUndo: false,
            isRedo: false,
            isSelector: false,
            isDragger: false,           
            isTextEditing: false,

            //Tools
            isText: false,
            isBrush: false, 
            isPencil: false,
            isCircle: false,
            isZoomIn: false,
            isZoomOut: false,        

            //Line                
            isLine: false,
            Line: null,

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

            colorPreviewStyle:{
                backgroundColor: "#000" 
            }
        }
    },
    created() {
        this.getSlideMaterials(this.$props.reservation);
    },
    mounted() {

        this.socket = io.connect(this.$props.canvas_server);

        if (this.$props.folder_id == '' || this.$props.folder_id == null || this.$props.folder_id == 'null' ) {
            //the user has not selected anything then we create new slide
            console.log("member did not selected a folder, create new empty slide")
            this.createNewSlide();
        } else {
            //current folder is now the selected folder id

            this.currentFolderID = this.$props.folder_id;
        }       

        this.$forceUpdate();
        this.keyPressHandler();
    },
    methods: {
        getRecipient() { 
            return this.$props.recipient_info;
        },    
        getSlideMaterials(reservation) {

            this.isLoading = true;

            axios.post("/api/getLessonMaterialSlides?api_token=" + this.api_token,
            {
                method              : "POST",
                scheduleID          : reservation.schedule_id,
                memberID            : reservation.member_id,
                lesson_time         : reservation.lesson_time
            }).then(response => { 

                this.isLoading      = true;
                this.folderID       = response.data.folderID;
                this.segments       = response.data.folderURLArray;
                let customFiles     = response.data.customFiles;  


                


                //Files, Audio and notes
                this.files = response.data.files;
                this.imageURL = response.data.files;
                this.audioFiles = response.data.audioFiles;  
                this.notes = response.data.notes;


                //Lesson History and its slides
                let lessonHistory   = response.data.lessonHistory;
                let slideHistory    = response.data.slideHistory.data;

                if (this.$props.is_broadcaster == true) {
                    this.$refs['TutorSlideNotes'].loadNotes(this.notes);   
                }

                if (lessonHistory && slideHistory.length >= 1) {

                    console.log("getSlidesFromHistory")

                    this.getSlidesFromHistory(slideHistory);
                
                } else if (response.data.files.length >= 1) {

                    console.log("getSlidesFromFiles")
                
                    this.getSlidesFromFiles(response.data.files);

                } else {

                    this.createNewSlide();
                    
                }

                this.$nextTick(() => {
               
                    //DETERMINE IF USER HAS SLIDE ACCESS AND ENABLE BROADCASTER MODE
                    this.userSlideAccess();      

                    //START SLIDE (SELECT FROM CURRENT HISTORY IF THERE IS CURRENT SLIDE)
                    let currentSlide =  (lessonHistory) ? lessonHistory.current_slide : 1;                            

                    this.startSlide(currentSlide);
                    
                    //LOAD AUDIO
                    this.loadAudio();

                    if (this.$props.is_broadcaster == true) {
                        this.canvas[this.currentSlide].on('object:moving', this.handleObjectMoving);
                        this.canvas[this.currentSlide].on('object:modified', this.handleObjectModified); 
                        this.$refs['slideSelector'].closeSlideSelector();          
                    }         

                });
            });

        },
        getSlidesFromFiles(files){
            //Slide Count and Custom Slide length
            this.imageURL       = files;
            this.slides         = files.length;

            for (var i = 0; i <= this.slides; i++) {             
                this.createCanvas(i);
                this.canvas[i]  = new fabric.Canvas('canvas'+i, { backgroundColor : "#fff" });  
                this.setSlideBackgroundImage(i, this.imageURL[i-1]);

                if (i == this.slides) {
                    
                    // Add a delay of 1 seconds (adjust the delay as needed)
                    var delayInMilliseconds = 1000;
                    var delayPromise = new Promise((resolve) => {
                        setTimeout(resolve, delayInMilliseconds);
                    });

                    delayPromise.then(() => {
                       this.saveAllSlides(); 
                    });
                }
            }

        },
        getSlidesFromHistory(slideHistory) {

            this.slides  =  slideHistory.length;
            for (var i = 0; i < this.slides; i++) {
                let slideIndex = i + 1;
                this.createCanvas(slideIndex);
                this.canvas[slideIndex]  = new fabric.Canvas('canvas'+slideIndex, { backgroundColor : "#fff" });
                this.updateCanvas(this.canvas[slideIndex], JSON.parse(slideHistory[i].content)); 
                this.canvas[slideIndex]['scale'] = 1;
            }        
        },
        createEmptySlide() {

            console.log("create empty slide");

            let slideIndex = 1;
            this.createCanvas(slideIndex);
            this.slides     = slideIndex;
            this.canvas[1]  = new fabric.Canvas('canvas'+slideIndex, { backgroundColor : "#fff" }); 

            // Add a delay of 1 seconds (adjust the delay as needed)
           
            var delayInMilliseconds = 1000;
            var delayPromise = new Promise((resolve) => {
                setTimeout(resolve, delayInMilliseconds);
            });

            delayPromise.then(() => {
                this.saveAllSlides(); 
            });              
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
        //Objects
        handleObjectMoving() {
            console.log("test handleObjectMoving")
        },
        handleObjectModified() {
            console.log("test handleObjectMoving");
            let data = this.canvasGetJSON();
            this.canvasSendJSON(this.canvas[this.currentSlide], data);              
        },

        //New Slide
        prepareNewSlide() {
            this.$refs['slideUploader'].prepareSlider(this.$props.reservation, this.slides + 1);
        },       
        userCreateNewEmptySlide() {   

            console.log("user create empty slide")
            this.newBackgroundImage = null;         

            axios.post("/api/saveEmptyCustomSlide?api_token=" + this.api_token,             
            {
                'method'            : "POST",
                'scheduleID'        : this.reservation.schedule_id,
                'folderID'          : this.newFolderID,
                'totalSlides'       : this.slides,
                'slideIndex'        : this.currentSlide,                
                'reservation'       : this.reservation,
            }).then(response => {

                if (response.data.success == true) {
                    console.log("CREATE_NEW_EMPTY_SLIDE"); 
                    this.createNewSlide();
                    this.saveSlideHistoryData(this.canvasGetJSON(), this.currentSlide);
                     
                } else {                
                    alert (response.data.message)                
                }                
            });
        },                  
        createNewSlide() {  

            console.log("createNewSlide");

            let currentSlideCount   = this.slides; 
            this.slides             = this.slides + 1;
            let index               = this.slides;

            //create a canvas based on the current index
            this.createCanvas(index);
            this.canvas[index]  = new fabric.Canvas('canvas'+index, {backgroundColor : "#fff"});         
            this.canvas[index]['scale'] = 1;

            //Audio Files
            this.audioFiles             = null;
            this.currentSlide           = index;
            

            if (this.$props.is_broadcaster == true) {  
            

                if (this.$props.folder_id) {
                    this.goToSlide(index);     
                }

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

            let visibility = 'hidden';
            let display = 'none';

            if (index == 1) {
                visibility = 'visible';
                display = 'block';
            } 

            //Editor Container Element
            var elementExists = document.getElementById("editor" + index);

            if (!elementExists) {            
                //console.log("++++++++ CREATING CANVAS ++++++++");
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

            } else {            
                //console.log("------ CANVAS IS ALREADY CREATED ------ ");
            }
        },
        canvasGetJSON() {
            return this.canvas[this.currentSlide].toJSON();           
        }, 
        canvasSendJSON(canvasID, canvasData) { 

            let recipient = this.getRecipient();

            if (this.$props.is_broadcaster == true) {

                let memberCanvasData = {
                    'currentSlide'  : this.currentSlide,
                    'channelid'     : this.channelid,
                    'sender'        : { 'userid': this.user_info.id, 'username': this.user_info.username },
                    'recipient'     : recipient,
                    'canvasid'      : canvasID,
                    'canvasData'    : canvasData,
                    'canvasZoom'    : this.canvas[this.currentSlide]['scale'],
                    'canvasDelta'   : this.delta,
                };
                this.socket.emit('SEND_DRAWING', memberCanvasData);  
            }
        },
        delegateUpdateCanvas(currentSlide, canvasData) {
            console.log(canvasData);
            this.updateCanvas(this.canvas[this.currentSlide], canvasData);

            if (this.$props.is_broadcaster == false) {
                console.log("delegate update canvas as member")
                if (currentSlide !== this.currentSlide) {
                    this.goToSlide(currentSlide);
                }
                
            } else {
             console.log("delegate update canvas as tutor")
            }    
        },
        delegateRelativePan(currentSlide, canvasDelta) {
            if (canvasDelta !== null) {
                this.canvas[currentSlide].relativePan(canvasDelta);
                this.canvas[this.currentSlide].requestRenderAll();              
            }

        },
        delegateSetZoom(currentSlide, canvasZoom) {
            if (this.canvas[currentSlide]) {
                this.canvas[this.currentSlide]['scale'] = canvasZoom;
                this.canvas[currentSlide].setZoom(canvasZoom);
                this.canvas[this.currentSlide].requestRenderAll(); 
            }

        },    
        updateCanvas(canvas, canvasData){
            if (canvas) {
                canvas.loadFromJSON(canvasData, this.disableCanvas, (o, object) => {            
                    if(this.$props.is_broadcaster == false) {
                        this.deactivateSelector()                
                    }                
                }); 
            }            
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
        deactivateSelector() {        
            this.removeEvents();
            this.resetModes();
            this.disableSelect();
            this.changeObjectSelection(false);
            this.isSelector = false;           
            this.canvas[this.currentSlide].selection = false;
            this.canvas[this.currentSlide].defaultCursor = 'not-allowed';
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
        disableSelect() {
            if (this.isText == true) {
                this.canvas[this.currentSlide].defaultCursor = 'text';  
                console.log("disable select")
            } else if (this.isSelector) {                    
                this.canvas[this.currentSlide].defaultCursor = 'default';   
            } else  if (this.isDragger) {
                this.canvas[this.currentSlide].defaultCursor = 'grab';                  
                console.log("disable select:: grab");
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
        removeEvents() {

            this.canvas[this.currentSlide].isDrawingMode = false;
            this.canvas[this.currentSlide].selection = false;
            this.canvas[this.currentSlide].off('mouse:down');
            this.canvas[this.currentSlide].off('mouse:up');
            this.canvas[this.currentSlide].off('mouse:move');
        },        
        resetInputTextModal() {
            this.inputText = "";
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
                //deltaY: false,
                evented: true,
                selectable: true,
                editable: true,
            });

            this.canvas[this.currentSlide].add(fabricText);

            fabricText.on('editing:entered', ()  =>{           
                fabricText.set("fill", this.brushColor);
                this.canvas[this.currentSlide].renderAll();
                //hack for modal unable to edit iText bug
                //$('#modal-lesson-slider').find('.modal-content').attr("tabindex", 9999999999);
               
            });

            // Add a delay of 1 seconds (adjust the delay as needed)
            var delayInMilliseconds = 1000;
            var delayPromise = new Promise((resolve) => {
                setTimeout(resolve, delayInMilliseconds);
            });

            delayPromise.then(() => {
                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);
                this.saveSlideHistoryData(data, this.currentSlide);  
            }); 

        },         
        resetModes()  {
            //this.canvas[this.currentSlide].isDrawingMode = false;
            this.isDrawingLine      = false;
            this.isDrawing          = false;
            this.isDrawingCircle    = false;            
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
        changeObjectSelection(value) {
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = value;
            });
            this.canvas[this.currentSlide].renderAll();
        },    

        keyPressHandler(e) {     
            window.onkeydown = (event) => {            
                if (event.key === "Delete") {
                    this.deleteObj();
                } else {                
                    //  let data = this.canvasGetJSON();
                    // this.canvasSendJSON(this.canvas[this.currentSlide], data);  
                };
            };
        },
        mouseClickHandler(e) {

            this.canvas[this.currentSlide].on('mouse:down', (options) => 
            {
                var pointer = this.canvas[this.currentSlide].getPointer(options.e);

                if (this.isText == true) {

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

                    console.log(this.panning);

                    if (this.panning) {
                        this.delta = new fabric.Point(options.e.movementX, options.e.movementY);
                        this.canvas[this.currentSlide].relativePan(this.delta);   

                        let data = this.canvasGetJSON();
                        this.canvasSendJSON(this.canvas[this.currentSlide], data);                                             

                    }
                }

            }).on('mouse:up', () => {
        
                if (this.isText == true) {
                
                    this.canvas[this.currentSlide].defaultCursor = 'text';              

                } else if (this.isSelector == true) {

                     this.canvas[this.currentSlide].defaultCursor = 'default';

                } else if (this.isDragger == true) {

                    //reset to default cursor of the current
                    this.canvas[this.currentSlide].defaultCursor = 'grab';
                    document.querySelectorAll('.upper-canvas').forEach(function(element) {                            
                        element.style.cursor = 'grab';
                    });      

                    this.panning  = false;
                    this.delta    = null;

                } else {

                    this.canvas[this.currentSlide].defaultCursor = 'default';   
                }

                
            }).on('mouse:out', (options) => {

                this.canvas[this.currentSlide].renderAll();

                if (this.isDragger == true) {
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
        //Auto Select Tools
        autoSelectTool() {
            console.log("auto select");

            if (this.isSelector) this.activateSelector();
            else if (this.isPencil) this.activatePencil();
            else if (this.isBrush) this.activateBrush();
            else if (this.isLine) this.activateLine();
            else if (this.isText) this.activateTextEditor();
            else if (this.isCircle) this.activateCircle();

            this.canvas[this.currentSlide].on('object:moving', this.handleObjectMoving);
            this.canvas[this.currentSlide].on('object:modified', this.handleObjectModified);              
        },   
        setCursosType(type) {
            this.canvas[this.currentSlide].defaultCursor = type;
            document.querySelectorAll('.upper-canvas ').forEach(function(element) {                            
                element.style.cursor = type;
            });
        },                  
        //Tools
        activateDragger() {   
            this.removeEvents();
            this.resetModes();
            this.disableSelect();
            this.changeObjectSelection(true);
            this.mouseClickHandler();
            this.isDragger = true;
            this.drag();          
        },        
        activateBrush() {
            this.removeEvents(); 
            this.resetModes();
            this.isBrush = true;
            this.draw();
        },        
        activatePencil()  {           
            this.removeEvents();
            this.resetModes();
            this.isPencil = true; 
            this.draw();
        },
        activateLine() {
            this.removeEvents();   
            this.resetModes();            
            this.disableSelect();
            this.isLine = true;
            this.drawLine();
        },
        activateCircle() {
            this.resetModes();
            this.removeEvents();
            this.disableSelect();
            this.isCircle = true;
            this.drawCircle();                  
        },        
        activateTextEditor() {
            this.removeEvents(); 
            this.resetModes();     
            this.disableSelect();
            this.canvas[this.currentSlide].defaultCursor = 'text';
            this.isText = true;
            this.mouseClickHandler();
        },
        //Color Picker
        changeColor() {
            this.setPreviewColor( this.brushColor )
            this.autoSelectTool();
            //this.canvas[this.currentSlide].getActiveObject().set({fill: this.brushColor});
        },
        setPreviewColor(color) {
            this.colorPreviewStyle.backgroundColor = color;
        },
        activateZoomIn() { 
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
            let newScale = this.canvas[this.currentSlide]['scale'] - 0.10;        
            if (newScale < 0.01) {
                this.rescale(0.10);          
            } else {
                this.rescale(newScale);
            }          
        },
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
        //Tool Action
        drag() {    
            let isGrabbing = false;
            this.setCursosType('grab');
            this.canvas[this.currentSlide].on('mouse:down', (object) => {
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
        draw() {

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
                this.saveSlideHistoryData(data, this.currentSlide);

            });            

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

                if (this.isDrawingCircle) {
                    this.disableSelect();
                    var pointer     =  canvas.getPointer(object.e);
                    var activeObj   = canvas.getActiveObject();
                    if (this.origX > pointer.x) { activeObj.set({ left: Math.abs(pointer.x) }); }
                    if (this.origY > pointer.y) { activeObj.set({ top: Math.abs(pointer.y)}); }

                    activeObj.set({ rx: Math.abs(this.origX - pointer.x) / 2});
                    activeObj.set({ ry: Math.abs(this.origY - pointer.y) / 2});
                    activeObj.setCoords();
                    canvas.renderAll(); 
                }

            }).on('mouse:up', (object) => {

                this.isDrawingCircle = false;
                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);  
                this.saveSlideHistoryData(data, this.currentSlide);

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
                this.saveSlideHistoryData(data, this.currentSlide);
            });
        },      
        //Slides
        userSlideAccess() 
        {
            if (this.$props.is_broadcaster == false) 
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
        },          
        selectSlides() {

     
            const userInfo = JSON.stringify(this.member_info); //current member Info is our tutor
            const recipient = JSON.stringify(this.recipient_info); //recipient the member
            const reservation = JSON.stringify(this.reservation);

            this.$refs['lessonSelectorComponent'].showLessonSelectionModal(userInfo, recipient, reservation);


            //this.$refs['slideSelector'].openSlideSelector(this.reservation.schedule_id, this.reservation.member_id);
        },  
        loadAudio() {
            if (this.$refs['audioPlayer']) {
                this.$refs['audioPlayer'].loadAudioList(this.audioFiles, this.currentSlide); 
            } else {
                console.log("audio list hidden")
            }
        },                    
        goToSlide(slide) {   

            console.log("goToSlide triggered!");

            //AUDIO
            if (this.$refs['audioPlayer']) {
                this.$refs['audioPlayer'].stopAudio();
            }
            
            this.loadAudio(slide);

            this.currentSlide = slide;
            this.viewerCurrentSlide = slide;


            if (this.$props.is_broadcaster == true) {
                this.$refs['TutorSlideNotes'].viewNote(this.currentSlide);   
            }           

            for (var i = 1; i <= this.slides; i++) {

                if (i == slide) {

                    let editorElement = document.getElementById('editor'+ i);

                    if (editorElement) {
                        editorElement.style.visibility = "visible";
                        editorElement.style.display = "block";                                              
                    }           

                    if (this.$props.is_broadcaster == true) {  
                        let data = this.canvas[slide].toJSON(); 
                        this.canvasSendJSON(this.canvas[slide], data);
                    }

                } else {
                    let editorElement = document.getElementById('editor'+ i);
                    if (editorElement) {
                        editorElement.style.visibility = "hidden";
                        editorElement.style.display = "none";        
                    }
                }
            }

        },
        prevSlide() {
            //the audio index needs to be reset since it is global
            this.$refs['audioPlayer'].resetAudioIndex();            
            this.$refs['audioPlayer'].stopAudio();

            if (this.currentSlide > 1) {
                this.currentSlide--;
                this.autoSelectTool();
                
                //let data = this.canvasGetJSON();
                //this.canvasSendJSON(this.canvas[this.currentSlide], data);     

                let data = {'channelid': this.channelid, 'num': this.currentSlide }
                this.socket.emit('GOTO_SLIDE', data);
            }
        },
        nextSlide() {
            //the audio index needs to be reset since it is global
            this.$refs['audioPlayer'].resetAudioIndex();
            this.$refs['audioPlayer'].stopAudio();
            this.rescale(1);

            if (this.currentSlide < this.slides) {

                this.currentSlide ++;            
                this.autoSelectTool(); 

                //let data = this.canvasGetJSON();
                //this.canvasSendJSON(this.canvas[this.currentSlide], data); 

                let data = {'channelid': this.channelid, 'num': this.currentSlide }               
                this.socket.emit('GOTO_SLIDE', data);                
            } 
        },
        userUploadedImage(file) {

            this.newBackgroundImage = file.fullpath;
            this.createNewSlide();
            this.setSlideBackgroundImage(this.currentSlide, file.fullpath);
            this.$forceUpdate(); 

            let data = this.canvasGetJSON();            
            this.canvasSendJSON(this.canvas[this.currentSlide], data); 

            this.saveSlideHistoryData(data, this.currentSlide);
        },
        //Slide Retrieval
        async getCurrentSlide() {
            return this.currentSlide;
        },
        async getCanvasSlideData(slideIndex) {
            return this.canvas[slideIndex].toJSON();       
        },       
        async getCanvasSlideImage(slideIndex) {
            return this.canvas[slideIndex].toDataURL('image/jpeg', 0.1)  
        },         
        async getAllSlideData() {
            let slidesDataArray = new Array();
            for (var i = 1; i <= this.slides; i++) {
                let canvasData  = await this.getCanvasSlideData(i);
                let canvasImage = await this.getCanvasSlideImage(i);
                let data = {
                    'slideIndex': i,
                    'canvasData': canvasData,   
                    'imageData':  canvasImage
                };                    
                slidesDataArray.push(data);
            }
            return slidesDataArray;
        },        
        async saveAllSlides() {
           
            for (var index = 1; index <= this.slides; index++) {
                let canvasData  = await this.getCanvasSlideData(index);                
                this.saveSlideHistoryData(canvasData, index);                                       
            }

            return true;
        },
       openNewSlideMaterials(newFolderID) { 

            this.newFolderID = newFolderID;
            //Slide Selector already update the slide selector folder, 
            //So lets refresh the member slides!
            if (this.$props.is_broadcaster == true) { 
                //call remove slides and open new slides
                this.removeOldSlidesAndOpenNewSlides();
                this.refreshMemberSlides();
            } else {             
                //opening slide for member
                this.removeOldSlidesAndOpenNewSlides();
            }

            this.goToSlide(1); //When opening new slide always go to first slide(?)
            this.$forceUpdate(); 
        },
        refreshMemberSlides() {
            if (this.$props.is_broadcaster == true) {   
        
                let slidesData = {
                    'currentSlide'  : this.currentSlide,
                    'channelid'     : this.channelid,
                    'sender'        : {
                                        userid: this.user_info.id,
                                        username: this.user_info.username
                                    },
                    'folderID'      :  this.newFolderID,
                    'recipient'     : this.getRecipient()
                }; 
                this.socket.emit('TUTOR_SELECTED_NEW_SLIDES', slidesData);                  
            }
            
        },        
        removeOldSlidesAndOpenNewSlides() {         
            for (var i = 1; i <= this.slides; i++) {                                
                if (i ==  this.slides) {                    
                    let slideElement = document.getElementById('slide-container')                    
                    slideElement.innerHTML = '';
                    this.getSlideMaterials(this.reservation);
                }
            }        
        },        
        saveSlideHistoryData(canvasData, slideIndex) {
            //console.log("saving... slide history " + slideIndex)
            axios.post("/api/saveLessonSlideHistory?api_token=" + this.api_token,
            {
                'method'          : "POST",
                'folderID'          : this.newFolderID,
                'totalSlides'       : this.slides,
                'slideIndex'        : slideIndex,                
                'reservation'       : this.reservation,
                'canvasData'        : canvasData,
                'imageData'         : this.canvas[slideIndex].toDataURL('image/jpeg', 0.5)

            }).then(response => {

                if (response.data.success == true) {
                    console.log(" SLIDE DATA : ", response.data);
                } else {
                   // console.log(" SLIDE DATA SAVING FAILED : ", response.data);
                }

                
            });
        
        },        

        startSlide(currentSlide) {
            if (this.$props.is_lesson_completed == true) {
                return false;            
            }

            if (currentSlide >= 1) {
                this.currentSlide = currentSlide;
            } else {
                this.currentSlide = 1;
            }            

            if (this.$props.is_broadcaster == true) {
                this.$refs['TutorSlideNotes'].viewNote(this.currentSlide);   
            }

            this.autoSelectTool();
            
            let data = this.canvasGetJSON();

            this.canvasSendJSON(this.canvas[this.currentSlide], data); 
            document.getElementById('editor'+ this.currentSlide).style.visibility = "visible";

            //Load List of Audio Array
            this.loadAudio()

            //@todo: if tutor then broadcast current slide
            if (this.$props.is_broadcaster == true) {            
                let data = {
                    'channelid': this.channelid,
                    'num': this.currentSlide
                }
                this.socket.emit('GOTO_SLIDE', data);                  
            } else {

                //when user lands a page (he will not dictate it but go to current slide)            
                this.goToSlide(currentSlide)
            }

        },

        //Delete Object
        deleteObj() {
            var selectedObj = this.canvas[this.currentSlide].getActiveObject();

            if (selectedObj) {
                if (selectedObj.type === 'activeSelection') {
                    var objectsInSelection = selectedObj.getObjects();
                    objectsInSelection.forEach((obj) => {
                        this.canvas[this.currentSlide].remove(obj);
                    });

                    this.canvas[this.currentSlide].discardActiveObject();
                } else {
                    this.canvas[this.currentSlide].remove(selectedObj);
                }
                
                // Add a delay of 1 seconds (adjust the delay as needed)
                var delayInMilliseconds = 1000;
                var delayPromise = new Promise((resolve) => {
                    setTimeout(resolve, delayInMilliseconds);
                });

                delayPromise.then(() => {
                    let data = this.canvasGetJSON();
                    this.canvasSendJSON(this.canvas[this.currentSlide], data);
                    this.saveSlideHistoryData(data, this.currentSlide);  
                });
                
            }            

           
            this.mouseClickHandler();
        },
    }
}
</script>


<style lang="scss" scoped>

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

    
    .btn-md {
        font-size: 22px;
        padding: 12px 10px 0px;
        position: relative;
        top: 3px;
        color: #0072ba;
    }

</style>