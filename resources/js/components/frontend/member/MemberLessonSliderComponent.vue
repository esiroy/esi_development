<template>

    <div class="container-fluid">

        {{ "channel ID: " + channelid }}


        <div id="videoDiv">
            :D
        </div>


        <div id="editor_content" class="row my-2">

            <div class="col-md-6 col-sm-12 col-xs-12">

                <div class="left-container mb-2">

                    <div v-show="this.$props.isBroadcaster == true">
                        {{ "type: broadcaster" }}
                    </div>


                    <div class="tool-container" v-show="this.$props.isBroadcaster == true">
                        <!-- [START] TOOL WRAPPER -->
                        <div class="tool-wrapper" >
                            <div :class="['tool', (isSelector) ? 'active' : '']" @click="activateSelector">
                                <i class="fa fa-mouse-pointer" aria-hidden="true" ></i>
                            </div>

                            <div :class="['tool', (isText) ? 'active' : '']" @click="activateTextEditor">
                                <i class="fa fa-font" aria-hidden="true"></i>
                            </div>
                
                            <div :class="['tool', (isPencil) ? 'active' : '']"  @click="activatePencil">
                                <i class="fa fa-pen" aria-hidden="true" ></i>
                            </div>                    
                            <div :class="['tool', (isBrush) ? 'active' : '']"  @click="activateBrush">
                                <i class="fa fa-paint-brush" aria-hidden="true" ></i>  
                            </div>                        
                    
                            <div :class="['tool', (isLine) ? 'active' : '']"  @click="activateLine">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </div>        
                                    
                            <div :class="['tool', (isCircle) ? 'active' : '']"  @click="activateCircle">
                                <b-icon icon="circle" font-scale="1"> </b-icon>
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

                    <div class="d-flex justify-content-center">
                   
                        <div :id="'editor'+slide"  v-show="slide == currentSlide" v-for="slide in slides" :key="slide" style="overflow:hidden">

                        
                            <!-- 
                            /********** [START] CANVAS *************/
                            -->
                            <canvas :ref="'canvas'+slide"
                                :id="'canvas'+slide"
                                :width="canvas_width"
                                :height="canvas_height"
                                style="border:1px solid #ccc;"                        
                            ></canvas>
                            <!-- 
                            /********** [END] CANVAS *************/
                            -->

                        </div>
                    </div>

                    <div class="info-container">

                        <div class="d-flex justify-content-center">
                            Total Time {{ this.getTime() }} 
                        </div>

                    </div>

                    <div class="buttons-container mt-3">
                        <div class="d-flex justify-content-center">

                            <div id="firstSlide" class="tool" @click="startSlide" v-show="this.$props.isBroadcaster == true">
                                <i class="fa fa-fast-backward" aria-hidden="true"></i>
                            </div>  

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

                            <div id="lastSlide" class="tool" @click="lastSlide" v-show="this.$props.isBroadcaster == true">
                                <i class="fa fa-fast-forward" aria-hidden="true"></i>
                            </div>


                        </div>
                    </div>

                    
                </div>

            </div>


            <div class="col-md-6 col-sm-12 col-xs-12">

                <div class="right-container">

                    <div class="mb-2" v-if="this.user_info.user_type =='TUTOR'">
                        <b-card-group>
                            <b-card bg-variant="light" header-bg-variant="primary" text-variant="white">
                                <template #header>
                                    <div class="font-weight-bold">Notes</div>
                                </template>
                               <b-card-text v-html="notes" class="text-dark"></b-card-text>
                            </b-card>
                        </b-card-group>
                    </div>


                    <div class="chat-container mb-2">
                        <b-card-group>

                            <b-card bg-variant="light" header-bg-variant="primary" text-variant="white">
                                <template #header>
                                    <div class="font-weight-bold">Chat Messages</div>
                                </template>

                                <b-card-text id="chatlogs" class="chatlogs text-dark" style="height: 280px; overflow: auto;">
                                    <div :class="'chatlog-'+chatlogIndex" v-for="(chatlog, chatlogIndex) in chatlogs" :key="'chatlogs_'+ chatlogIndex">
                                        <span v-html="chatlog.nickname"></span> : <span v-html="chatlog.message"></span>
                                    </div>           
                                </b-card-text>

                            </b-card>
                        </b-card-group>

                        <div class="chat_message mt-1 row">
                            <div class="col-10 mr-0 pr-0">
                                <input type="text" v-model="privateMessage" @keyup="isEnter($event)" class="form-control form-control-sm d-inline-block" placeholder="Enter a message" >
                            </div>

                            <div class="col-2 ml-0 pl-1">
                                <button type="button"  @click="sendPrivateMessage(privateMessage)" class="btn btn-sm btn-primary d-inline-block">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>                    



                </div>
            </div>  
        </div>



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


</template>

<script>

import { fabric } from "fabric";
import io from "socket.io-client";

import { Peer } from "peerjs";

export default {
    name: "lessonSliderComponent",
    props: {
        csrf_token: String,		
        api_token: String,
        reservation: Object,        
        isBroadcaster: {
            type: [Boolean],
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
        canvas_width: {
            type: [String, Number],
            required: true
        },
        canvas_height: {
            type: [String, Number],
            required: true
        },
        editor_id: {
            type: String,
            default: "canvas",
            required: false
        }
    },
    data() {
        return {


            tutor_chat_message: "",
            privateMessage: "",

            chatlogs: [],

            socket: null,


            videoSocket: null,
            myVideoStream: null,
            

            

            //loader
            isLoading: false,

            canvas: [],
            canvasMirror: null,

            //slides
            currentSlide: 1,
            viewerCurrentSlide: 1,

            slides: 5,
            lesson_slides_materials: [],

            //Modes
            isSelector: false,
            isText: false,
            isTextEditing: false,

            isBrush: false, 
            isPencil: false,
            isCircle: false,

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
            timer: 0,


     
            imageURL: [],

            notes: "<bold>lorem epusm dollor </bold>",
           
        };
    },
    mounted() 
    {
        this.socket = io.connect(this.$props.canvas_server);





        this.createPeerConnection();


       

        //register as user
        let user = {
            userid: this.member_info.user_id ,
            nickname: this.member_info.nickname,
            username: this.user_info.username,            
            channelid: this.channelid,
            status: "ONLINE",
            type: this.user_info.user_type,      
        }

        this.socket.emit('REGISTER', user); 

        this.socket.on('update_user_list', users => 
        {
            this.updateUserList(users); 
        });      

        this.getSlideMaterials(this.reservation) 


    /*

        this.imageURL = [
            "http://i.imgur.com/yf6d9SX.jpg",
            "https://i.imgur.com/rQjt0IH.jpeg",
            "https://i.imgur.com/v3DN59v.png",
            "https://i.imgur.com/rgy0H0t.png",
            "https://i.imgur.com/yFoigWV.png",
            "https://i.imgur.com/yFoigWV.png"
        ];
*/

        /*

        for (var i = 1; i <= this.slides; i++) 
        {

            this.canvas[i]  = new fabric.Canvas('canvas'+i, {
                //backgroundColor : "#fff"
            });

            // set canvas width and height based on image size
            //this.canvas[i].setDimensions({ width: this.canvas_width, height: this.canvas_height});

            
            
            this.canvas[i].setBackgroundImage(this.imageURL[i-1], this.canvas[i].renderAll.bind(this.canvas[i]), {
                // Optionally add an opacity lvl to the image
                //backgroundImageOpacity: 0.5,
                // should the image be resized to fit the container?
                //backgroundImageStretch: false,
            });
            
            

            document.getElementById('editor'+i).style.backgroundImage = 'url('+ this.imageURL[i-1] +')';
            
        }
        */

        
        this.customSelectorBounds(fabric);

      

        /*
       //update drawing if member is 
        this.canvas[this.currentSlide]  = new fabric.Canvas('canvas'+this.currentSlide, {
            backgroundColor : "#fff",
        });
        */



        this.socket.on("GOTO_SLIDE", (data) =>  {   
            this.viewerCurrentSlide = data.num
            this.currentSlide = data.num
            this.goToSlide(data.num);
        });

        this.socket.on('UPDATE_DRAWING', (response) => {
            if (this.$props.isBroadcaster == false) {
            
                console.log("updating drawing " + this.$props.isBroadcaster )
                this.updateCanvas(this.canvas[this.currentSlide], response.canvasData);
            } 
        });

        this.socket.on('SEND_SLIDE_PRIVATE_MESSAGE', (response) => {     

            /* response
            let messageData = {
                channelid: this.channelid,
                userid: this.member_info.user_id,
                nickname: nickname,
                username: this.user_info.username,            
                channelid: this.channelid,
                type: this.user_info.user_type,
                message: message,
                time: time
            }*/

            if (response.userid !== this.user_info.userid) 
            {
                new Promise((resolve, reject) => {

                    this.pushPrivateMessage(response);
                    resolve('private message resolved');
                    
                }).then((result) => {

                    //console.log(result)

                    this.privateMessage = null;

                    this.scrollToBottom();
                });                

                /*
                Promise.all([mypromise]).then(arr => {
                    console.log(arr)
                    this.scrollToBottom();
                });*/
            } 

        });


        //ON LOAD WINDOW
        /*
        if (this.$props.isBroadcaster == false) 
        {
            //Your are not a broadcaster
            this.disableSelect();
            this.deactivateSelector();
            
            //console.log("deactivated selector")

            this.canvas[this.currentSlide].isDrawingMode = false; 

        } else {

            //Your are the viewer        
                  
            this.mouseClickHandler();
            this.activatePencil();
        }


        this.keyPressHandler();
        this.startTimer();
        */



    },
    methods: {

        createPeerConnection() {

           

            this.videoSocket = io.connect('https://rtcserver.esuccess-inc.com:40002', {});

          

            this.peer = new Peer({
                config: {'iceServers': [
                    { url: 'https://rtcserver.esuccess-inc.com' },
                ]},
                port: 40002,
                path: '/peerjs'
            });



          
            var myvideo = document.createElement('video');
            myvideo.muted = true;

            const peerConnections = {}

            navigator.mediaDevices.getUserMedia({
                audio: true,
                video: { width: 100, height: 100 }  
            }).then((stream) => {

                this.myVideoStream = stream;

                this.addVideo(myvideo, stream);

                this.peer.on('call', call => 
                {
                    call.answer(stream);
                    const vid = document.createElement('video');

                    call.on('stream', userStream => {
                        this.addVideo(vid, userStream);
                    })
                    call.on('error', (err) => {
                        alert(err)
                    })
                    call.on("close", () => {
                        console.log(vid);
                        vid.remove();
                    })
                    peerConnections[call.peer] = call;
                })
                
            }).catch(err => {
                alert(err.message)
            })


            this.peer.on('open', (id) => {


                //this.videoSocket.emit("newUser", id, roomID);

                this.videoSocket.emit("newUser", id, this.channelid);

                
            })

            this.peer.on('error', (err) => {
                alert(err.type);
            });

            this.videoSocket.on('userJoined', id => {

          

                console.log("new user joined", this.myVideoStream)


                const call = peer.call(id, this.myVideoStream);
                const vid = document.createElement('video');

                call.on('error', (err) => {
                    alert(err);
                });

                call.on('stream', userStream => {
                    this.addVideo(vid, userStream);
                });

                call.on('close', () => {
                    vid.remove();
                    console.log("user disconect")
                })
                peerConnections[id] = call;

            });

            this.videoSocket.on('userDisconnect', id => {
                if (peerConnections[id]) {
                    peerConnections[id].close();
                }
            })
        },
        addVideo(video, stream) 
        {
            let videoGrid = document.getElementById('videoDiv')

            video.srcObject = stream;
            video.addEventListener('loadedmetadata', () => {
                video.play()
            })
            videoGrid.append(video);
        },
        updateUserList: function(users) 
        {
            this.users = users;      
            this.$forceUpdate();
        },
        userSlideAccess() {

            if (this.$props.isBroadcaster == false) 
            {
                //Your are not a broadcaster
                this.disableSelect();
                this.deactivateSelector();
                this.canvas[this.currentSlide].isDrawingMode = false;

                console.log("deactivated selector")

            } else {
                //Your are the viewer  
                this.mouseClickHandler();
                this.activatePencil();
            }


            this.keyPressHandler();
            this.startTimer();
        },
        getSlideMaterials(reservation) 
        {

            console.log('reservation slide ', reservation)

            this.isLoading = true;

            axios.post("/api/getLessonMaterialSlides?api_token=" + this.api_token,
            {
                method              : "POST",
                scheduleID          : reservation.schedule_id,
                memberID            : reservation.member_id,
                lesson_time        : reservation.lesson_time

            }).then(response => {     
            
                if (response.data.success === true) 
                {
                    this.imageURL = response.data.files;
                    this.slides  = (response.data.files).length;

                    for (var i = 1; i <= this.slides; i++) 
                    {
                        this.canvas[i]  = new fabric.Canvas('canvas'+i, {
                            //backgroundColor : "#fff"
                        });

                        // set canvas width and height based on image size
                        //this.canvas[i].setDimensions({ width: this.canvas_width, height: this.canvas_height});                        
                        
                        this.canvas[i].setBackgroundImage(this.imageURL[i-1], this.canvas[i].renderAll.bind(this.canvas[i]), {
                            // Optionally add an opacity lvl to the image
                            //backgroundImageOpacity: 0.5,
                            // should the image be resized to fit the container?
                            //backgroundImageStretch: false,
                        });
                        document.getElementById('editor'+i).style.backgroundImage = 'url('+ this.imageURL[i-1] +')';    

                        if (i ==  this.slides)
                        {
                            this.userSlideAccess();

                            console.log("called user slide access")
                        }
                        
                    }

                   

                } else {
          


                }
			});
        },       

        pushPrivateMessage(data) {
            // does something
           this.chatlogs.push(data);
        },
        scrollToBottom() {
            console.log("scroll to bottom");

            var textarea = document.getElementById('chatlogs');
            textarea.scrollTop = textarea.scrollHeight;            
        },
        isEnter(e) {
             if (e.keyCode === 13) {

                if (this.privateMessage !== null) {
                    this.sendPrivateMessage(this.privateMessage);
                    this.privateMessage = null;
                } else {
                    console.log("please enter a message")
                }
             }
        },
        sendPrivateMessage(message) 
        {
            if (message == null) {
                return false;
            }

            let nickname = null;

            if (this.user_info.user_type == "TUTOR") {
                nickname = this.user_info.firstname
            } else {
                nickname = this.member_info.nickname
            }

            var currentTime = new Date();    
            let time        = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

            let messageData = {
                channelid: this.channelid,
                userid: this.member_info.user_id,
                nickname: nickname,
                username: this.user_info.username,            
                channelid: this.channelid,
                type: this.user_info.user_type,
                message: message,
                time: time
            }

          

            this.socket.emit("SEND_SLIDE_PRIVATE_MESSAGE", messageData); 
        },

        /*
        loadImage(id, imageURL) 
        {

            var canvas = document.getElementById('canvass'+id);                       
            let ctx = canvas.getContext("2d");

            this.canvas[i].width = 934;
            this.canvas[i].height = 622;


            var background = new Image();
            background.src = imageURL;

            background.onload = function(){
                ctx.drawImage(background,0,0);   
            } 

            background.setAttribute('crossorigin', 'anonymous'); // works for me

        },*/


        getRecipient() {        
            let recipient = {
                userid: this.member_info.user_id ,
                username: "test",
                nickname: this.member_info.nickname,            
                type: this.user_info.user_type,      
            }
            return recipient;
        },

        updateCanvas(canvas, data)
        {
            canvas.loadFromJSON(data, this.disableCanvas, (o, object) =>
            {
                this.deactivateSelector()                
            });
        },

        disableCanvas() {
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

            let memberCanvasData = {
                'channelid'     : this.channelid,
                'recipient'     : recipient,
                'canvasid'     : canvasID,
                'canvasData'   : canvasData,
                
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
            socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender });                      
        },
        getTime() {
            return this.secondsToHms(this.timer);
        },
        startTimer() {
            this.myIntervalTimer = setInterval(this.updateTimer, this.timerSpeed);
        },
        stopTimer() {
            clearInterval(this.myIntervalTimer); 
        },
        secondsToHms(d) {
            d = Number(d);
            var h = Math.floor(d / 3600);
            var m = Math.floor(d % 3600 / 60);
            var s = Math.floor(d % 3600 % 60);
            var hDisplay = h > 9 ? h+":" : "0"+h+":";
            var mDisplay = m > 9 ? m+":" : "0"+m+":";
            var sDisplay = s > 9 ? s : "0"+s;
            return hDisplay + mDisplay + sDisplay; 
        },        
        updateTimer() {
            this.timer++;
        },
        changeColor() {
            this.setPreviewColor( this.brushColor )
            this.autoSelectTool();

            //this.canvas[this.currentSlide].getActiveObject().set({fill: this.brushColor});
        },
        
        startSlide() {

            if (this.currentSlide > 1) 
            {
                this.currentSlide = 1;
                this.autoSelectTool();

                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);                      
            } 

            document.getElementById('editor'+ this.currentSlide).style.visibility = "visible";      
             
        },
        
        goToSlide(slide) {          

            //console.log(slide);

            for (var i = 1; i <= this.slides; i++) 
            {
                if (i == slide) {    

                    document.getElementById('editor'+ i).style.visibility = "visible";
                    document.getElementById('editor'+ i).style.display = "block";                                   

                    let data = this.canvas[slide].toJSON(); 
                    this.canvasSendJSON(this.canvas[slide], data);

                } else {

                    document.getElementById('editor'+ i).style.visibility = "hidden";
                    document.getElementById('editor'+ i).style.display = "none";        

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

            console.log("keypress handler ");


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
        mouseClickHandler() 
        {
            this.canvas[this.currentSlide].on('mouse:down', (options) => {

                if (this.isText == true) {

                    this.mouseX = options.pointer.x;
                    this.mouseY = options.pointer.y; 

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

                }  

            }).on('mouse:up', () => {

                console.log ("mouseClickHandler");

                let data = this.canvasGetJSON();
                this.canvasSendJSON(this.canvas[this.currentSlide], data);    

            });
        },   

        resetModes()  {

            this.canvas[this.currentSlide].isDrawingMode = false;
            this.isDrawingLine      = false;
            this.isDrawing          = false;
            this.isDrawingCircle    = false;           

            //modes
            this.isSelector     = false
            this.isText         = false;
            this.isBrush        = false;
            this.isPencil       = false;
            this.isLine         = false;
            this.isCircle       = false;
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
            this.resetModes();
            this.removeEvents();   
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
                    hasControls: true
                });

               canvas.add(this.circle).setActiveObject(this.circle);

            }).on('mouse:move', (object) => {

                if (this.isDrawingCircle ) {

                    

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
            this.canvas[this.currentSlide].defaultCursor = 'crosshair';
            
            this.canvas[this.currentSlide].forEachObject(function (obj) {
                obj.selectable = false;
                obj.evented = false;
                obj.hasControls = false;

                /*
                obj.setControlsVisibility({
                    tl:false, //top-left
                    mt:false, // middle-top
                    tr:false, //top-right
                    ml:false, //middle-left
                    mr:false, //middle-right
                    bl:false, // bottom-left
                    mb:false, //middle-bottom
                    br:false //bottom-right
                });
                */

             });

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
<style>


#videoDiv{
    display: grid;
    grid-gap: 10px;
    height:80%;
    position: relative;
    grid-template-columns: repeat(auto-fill, 100px);
    grid-auto-rows: 100px;
}


.upper-canvas {
    z-index: 1;
    
}

.tools {
    display: flex;
    height: 200px; 
    width: 50px;    
}

.tool {
    width:50%; 
    border:1px solid #000;
    text-align:center;
    margin: 0;
    padding-right: 4px;
    width: 25px;
    height: 25px;
    border: 0;
    border-right: 1px solid black;
    border-bottom: 1px solid black;
    background: transparent;
    outline: 0;
}

.tool-wrapper {
    display: flow-root;
    width: 100%;
}

.left-container {
    background-color: #ececec;
    padding: 10px;

}

.right-container {
    background-color: #ececec;
    padding: 10px;
}

/*tool*/
.tool-container {
    width: 100%;
    display: inline-block;
    margin: 0px ;}

.tool {
    float: left;
    width: 47px;
    height: 25px;
    
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    border-top: 1px solid white;
    border-left: 1px solid white;
    border-right: 1px solid #7b7b7b;
    border-bottom: 1px solid #7b7b7b;    
}

.tool.active {
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEW9vb3///8EwsWUAAAADElEQVQI12NoYHAAAAHEAMFJRSpJAAAAAElFTkSuQmCC) repeat;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    border-top: 1px solid #7b7b7b;
    border-left: 1px solid #7b7b7b;
    border-right: 1px solid #bdbdbd;
    border-bottom: 1px solid #bdbdbd;   
}



.colors-container {   
    background-color: #c0c0c0;
    margin:0px 5px 0px;
    width: 100%;
}

.colors-wrapper {
    display: inline-block;
}

.color-button {

    width: 100% !important;
    height: 100% !important;
    padding: 0px !important;
    margin: 0px;
    border: none;
    background-color: transparent;
    text-align: center;
    margin: auto;
}

.color-selected {    
    background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAIAAAACAQMAAABIeJ9nAAAABlBMVEW9vb3///8EwsWUAAAADElEQVQI12NoYHAAAAHEAMFJRSpJAAAAAElFTkSuQmCC) repeat;
    border:1px solid #ccc;
    width: 45px;
    height: 45px;
    display: inline-block;
    vertical-align: text-bottom;
    margin: 0px 0px 0px 5px;
}

.color-shadow {
    border-top: 1px solid #7B7B7B;
    border-left: 1px solid #7B7B7B;
    border-right: 1px solid #BBBBBB;
    border-bottom: 1px solid #BBBBBB;
    box-shadow: 1px 1px 0px black inset;
}

.colors {
    display: inline-block;
    text-align: center;
    width: 370px;    
}

.color-preview {
    width: 15px;
    height: 15px;    
    text-align: center;
    margin: 13px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    border-top: 2px solid #fff;
    border-left: 2px solid #fff;
    border-right: 2px solid #7B7B7B;
    border-bottom: 2px solid #7B7B7B;    
}



.colors button {
    display: inline-block;
    border: 1px solid #00000026;
    border-radius: 0;
    outline: none;
    cursor: pointer;
    width: 20px;
    height: 20px;
    margin-bottom: 5px;
}

.tool .collapsing {
     -webkit-transition: none;
     transition: none;
}

.brushes-container {

    background-color: #ececec;
    
    width: 235px;
    float: left;
    /* width: 47px; */
    height: 36px;
    /* top: 0px; */
    /* left: 0px; */
    /* right: 0px; */
    /* bottom: 0px; */
    border-top: 1px solid white;
    border-left: 1px solid white;
    border-right: 1px solid #7b7b7b;
    border-bottom: 1px solid #7b7b7b;    

    position: relative;
    z-index: 99999;

}

.brushes {
    padding-top: 2px;
}

.brushes button {
    display: inline-block;
    width: 20%;
    border: 0;
    border-radius: 0;
    background-color: #ece8e8;
    margin-bottom: 5px;
    padding: 5px;
    height: 30px;
    outline: none;
    position: relative;
    cursor: pointer;
    vertical-align: top;
}

.brushes button:after {
    height: 1px;
    display: block;
    background: #808080;
    content: "";
}

.brushes button:nth-of-type(1):after {
    height: 3px;
}

.brushes button:nth-of-type(2):after {
    height: 5px;
}

.brushes button:nth-of-type(3):after {
    height: 10px;
}

.brushes button:nth-of-type(4):after {
    height: 15px;
}

.brushes button:nth-of-type(5):after {
    height: 20px;
}



.buttons {
    height: 80px;
    padding-top: 10px;
}

.buttons button {
    display: block;
    width: 100%;
    border: 0;
    border-radius: 0;
    background-color: #ece8e8;
    margin-bottom: 5px;
    padding: 5px;
    height: 30px;
    outline: none;
    position: relative;
    cursor: pointer;
    font-size: 16px;
}


button.brush.active {
    background-color: #04127b;
}


.right-block {
    width: 640px;
}

#paint-canvas {
    cursor: crosshair;
}




</style>
