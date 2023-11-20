<template>

    <div class="chat-container mb-2">

        <div id="fileUpload" class="position-bottom-right" style="display:none">
            <file-upload
                name="file"
                class="btn btn-primary"
                extensions="jpeg,jpg,gif,pdf,png,webp"
                accept="image/png, application/pdf, image/gif, image/jpeg, image/webp"
                v-model="files"
                post-action="/uploader/fileUploader"            
                :data="{ 
                    'current_chatbox_userid': this.current_chatbox_userid,
                    'message_type': this.user_info.user_type,            
                    'folder': 'member_slides',                
                }"
                :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                :multiple="true"
                :drop="true"
                :drop-directory="true"
                @input="updatetValue"
                @input-file="inputFile"
                @input-filter="inputFilter" ref="upload">      
            </file-upload>
        </div>    

        <h6 class="font-weight-bold text-center text-white m-0 p-2" :style="'background-color:#ccc'" >Chat Messages</h6>

        <b-card class="mb-2 p-2">   

            <b-card-text id="chatlogs" class="chatlogs text-dark" style="height: 280px; overflow: auto;">
                <div :class="'chatlog-'+chatlogIndex" v-for="(chatlog, chatlogIndex) in chatlogs" :key="'chatlogs_'+ chatlogIndex">
                    <span v-html="chatlog.nickname"></span> : <span v-html="chatlog.message"></span>
                </div>           
            </b-card-text>

        </b-card>
    
        <!--- [NEW] CHAT MESSAGE -->
        <div class="chat_message mt-1 row">

            <div class="col-9 pr-0 mr-0">
                <div v-for="(file, index) in files" :key="file.id" class="image-prieview-container bg-light  w-25 d-inline-block p-1 border border-light rounded">
                    <div class="remove-image-upload float-right">
                        <a class="" href="#" @click.prevent="$refs.upload.remove(file)" style="padding:5px; background-color:#fff; color:#000">X</a>
                    </div>

                    <div  v-if="file.type == 'image/jpeg' || file.type == 'image/png'" >
                        <img class="w-100" :src="file.thumb" :id="'image-preview-'+index">  
                    </div>
                    
                    <div v-else>
                        <div>
                            <i class="far fa-file" style="font-size:110px"></i>
                        </div>
                    </div>

                    <div class="progress" v-if="file.active || file.progress !== '0.00'">
                        <div :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}"
                            role="progressbar"
                            :style="{width: file.progress + '%'}"
                        >{{file.progress}}%</div>
                    </div>

                </div>  
            </div>

            <div class="col-9 pr-0 mr-0">
                <input id="lesson-message"  type="text" 
                    v-model="privateMessage" @keyup="isEnter($event)" 
                    class="form-control form-control-sm d-inline-block" placeholder="Enter a message">
            </div>

            <div class="col-3 px-1">

                <div id="lesson-attach-button">
                    <div id="attach-button" class="input-group-append d-inline-block float-left">
                        <label id="lesson-file-select-button" for="file" class="btn btn-primary mr-1 btn-sm">
                        <i class="fas fa-paperclip"></i>
                        </label>
                    </div>
                </div>

                <div id="lesson-send-button" class="col-2">
                    <button type="button"  @click="sendPrivateMessage(privateMessage)" class="btn btn-sm btn-primary d-inline-block">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    </button>
                </div>

                <span class="button-controls" style=" display:none">
                    <button id="lessonStartUpload" type="button" class="btn btn-sm btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
                        <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                    </button>

                    <button type="button" class="btn btn-sm btn-danger" v-else @click.prevent="$refs.upload.active = false">
                        <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                    </button>
                </span>   

            </div>

        </div>
        <!--[END] CHAT MESSAGE -->
    </div>                    

</template>                 


<script>
    import io from "socket.io-client";

    import FileUpload from 'vue-upload-component'

    export default {
        name: "lessonSliderChatroomComponent",
        components: { FileUpload },
        props: {
            csrf_token: String,		
            api_token: String,
            channelid: {            
                type: [String, Number],
                required: true
            },
            canvas_server: {
                type: [String],
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
            isBroadcaster: {
                type: [Boolean],
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
        },
        data() 
        {
            return {

                //history is fetching status
                isFetching: false,
                interval: "",



                tutor_chat_message: "",
                privateMessage: "",
                chatlogs: [],


                message: [],

                
                users: [],

                //chat boxes
                showChatbox: false,

                chatboxes: [],
                chatlogs: [],
                message_count: 0,
                unread_message_count: 0,

            
                //this will hold the current recipient userid
                current_chatbox_userid: "",

                //uploader
                files: [],

                //History Loaders
                loadingHistory: false,
                historyNotifier: true,

                //buttons
                ButtonSend: null,
                ButtonUpload: null,

                //page
                page:[],

            }
        },

        mounted() {
        
            this.socket = io.connect(this.$props.canvas_server);

            let nickname = null;

            if (this.user_info.user_type == "TUTOR") {
                nickname = this.user_info.firstname
            } else {
                nickname = this.member_info.nickname
            }


            //register as user
            let user = {
                userid: this.member_info.user_id ,
                nickname: nickname,
                username: this.user_info.username,            
                channelid: this.channelid,
                status: "ONLINE",
                type: this.user_info.user_type,      
            }

            this.socket.emit('REGISTER', user); 

            this.socket.on('SEND_SLIDE_PRIVATE_MESSAGE', (response) => {     

                console.log("response ##", response)

                /* response data 
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

                        this.privateMessage = null;
                        this.scrollToBottom();
                    });                

                } 

            });


           // this.initializeChatBox(user);


        },
        methods: {

            updatetValue(value) {
                //this.files = value;
            },
            /**
            * Has changed
            * @param  Object|undefined   newFile   Read only
            * @param  Object|undefined   oldFile   Read only
            * @return undefined
            */
            inputFile: function(newFile, oldFile) 
            {

                this.prepareButtons();

                if (newFile && oldFile && !newFile.active && oldFile.active) 
                {

                    if (newFile.xhr) {

                        if ( newFile.xhr.status === 200) 
                        {
                            //file information
                            let file = [{
                                        'id'        : newFile.response.id,
                                        'file_name' : newFile.response.file,
                                        'size'      : newFile.response.size,
                                    }];

                            var currentTime = new Date();
                            let time = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

                            //get the sender from props  (admin)
                            let sender = {
                                'msgCtr': 0,
                                'userid': this.userid,
                                'nickname': this.nickname,
                                'username': this.username,          
                                'message': newFile.response.image,
                                'user_image':  this.user_image, //@todo: make this for customer support 
                                'type': "MEMBER"
                            };
                        
                            /*
                            this.chatlogs[newFile.response.recipient_id].push({
                                sender: sender,
                                message: newFile.response.image,
                                time: time
                            });*/

                            this.$forceUpdate(); 

                            //get the sender from props (user)                        
                            let id = newFile.response.id

                            let recipient = {
                                'id': newFile.response.id,
                                'userid': newFile.response.recipient_id,
                                'username':  newFile.response.recipient_username,
                            };

                            socket.emit("SEND_SLIDE_PRIVATE_MESSAGE", { id, time, recipient, sender });                      
                        }               

                    }
                }

                if (this.$refs.upload.uploaded) {
                    // console.log("all queue uploaded");
                    this.files = [];

                    this.scrollToEnd();
                }
            },
            
            /**
            * Pretreatment
            * @param  Object|undefined   newFile   Read and write
            * @param  Object|undefined   oldFile   Read only
            * @param  Function           prevent   Prevent changing
            * @return undefined
            */
            inputFilter: function(newFile, oldFile, prevent) {
                if (newFile && !oldFile) {
                    // Filter non-image file
                    if (!/\.(jpeg|jpe|jpg|gif|png|webp|pdf|doc|docx)$/i.test(newFile.name)) {
                        return prevent();
                    }
                }


                if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
                    // Create a blob field
                    newFile.blob = ''
                    let URL = window.URL || window.webkitURL
                    if (URL && URL.createObjectURL) {
                    newFile.blob = URL.createObjectURL(newFile.file)
                    }
                    // Thumbnails
                    newFile.thumb = ''
                    if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
                    newFile.thumb = newFile.blob
                    }
                }
            },
            initializeChatBox: function(user) 
            {   
            
                //@note: user is the sender
                this.current_chatbox_userid = user.userid;    

                let found = false;

                //add new to array if not present
                for (var i in this.chatboxes) {
                    if (user.username == this.chatboxes[i].username) {
                        found = true;

                        console.log(user);
                    }
                }

                if (found == false) {

                    //this.chatboxes.push(user);			
                    this.chatboxes = [user];

                    //instantiate chat log for when send message logs it does not empty out
                    this.chatlogs[user.userid] = [];

                    this.$forceUpdate();

                    this.$nextTick(function()
                    {
                        this.scrollToEnd();
                        this.prepareButtons(); 
                    });            
                }    
            },
        scrollToEnd: function() 
        {
            this.$forceUpdate();   

            this.$nextTick(function()
            {      
                var container = this.$el.querySelector("#user-chatlog");
                if(container){
                    container.scrollTop = container.scrollHeight;
                    console.log(container.scrollHeight)   
                }
            });

            this.$nextTick(function() {
                if (this.chatFetchStatus == "ACTIVE") {
                    this.getPaginatedHistory();
                } else {
                    //console.log("unable to fetch chat history result still busy, please try again")
                }
                
            });
        },            
            prepareButtons() {   
                let message = document.getElementById("lesson-message").value; 
                let fileSelectBtn = document.getElementById("lesson-file-select-button");
                let sendBtn = document.getElementById("lesson-send-button");        
                
                if (message == "" && this.files.length == 0) {  

                    if (fileSelectBtn) {
                        fileSelectBtn.style.display = "block";
                    }

                    if (sendBtn) {
                        sendBtn.style.display = "none";
                    }
                    
                    
                } else {

                    if (fileSelectBtn) {
                        fileSelectBtn.style.display = "none";            
                    }

                    if (sendBtn) {
                        sendBtn.style.display = "block";
                    }
                }    

            },
            pushPrivateMessage(data) {
                this.chatlogs.push(data);
            },
            scrollToBottom() {
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
            sendPrivateMessage(message) {     

                if (message === "" || message === undefined) {

                    //No message just upload

                } else {


                }

                document.getElementById("lessonStartUpload").click();


                let nickname = null;

                if (this.user_info.user_type == "TUTOR") {
                    nickname = this.user_info.firstname
                } else {
                    nickname = this.member_info.nickname
                }

                var currentTime = new Date();    
                let time        = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

                let data = {
                    channelid: this.channelid,
                    userid: this.member_info.user_id,
                    nickname: nickname,
                    username: this.user_info.username,
                    type: this.user_info.user_type,
                    message: message,
                    time: time
                }

            
            

                this.socket.emit("SEND_SLIDE_PRIVATE_MESSAGE", data); 

                this.privateMessage = null;
            }
        
        }

    }


</script>
