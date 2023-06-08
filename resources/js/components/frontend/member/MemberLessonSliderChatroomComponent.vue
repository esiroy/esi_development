<template>

    <div id="lesson-chat-service">

        <div id="fileUpload" class="position-right" style="display:none">
            <file-upload
                name="file"
                input-id="lesson-chatroom-file"
                class="btn btn-primary"
                extensions="jpeg,jpg,gif,pdf,png,webp"
                accept="image/png, application/pdf, image/gif, image/jpeg, image/webp"
                v-model="files"
                post-action="/uploader/fileUploader"            
                :data="{ 
                    'current_chatbox_userid': this.current_chatbox_userid,
                    'message_type': 'MEMBER',            
                    'folder': 'notes',                
                }"
                :headers="{'X-CSRF-TOKEN': this.csrf_token }"
                :multiple="true"
                :drop="true"
                :drop-directory="true"
                @input="updatetValue"
                @input-file="inputFile"
                @input-filter="inputFilter" ref="lessonChatUploader">      
            </file-upload>
        </div>


        <div class="chatbox-container">
         
        
            <div class="card">
                <div class="card-body bg-white pb-4" style="min-height:250px;">

                    <div id="user-chatlog" class="user-chatlog border rounded text-center">
                        <div class="chatlog-wrapper">

                            <div class="chat mt-1 p-1" v-for="(chatlog, chatlogIndex) in messages" :key="'my_chatlog_'+ chatlogIndex">                               
                                <div v-if="current_chatbox_userid == chatlog.sender.userid">
                                    <div class="sender-container">  
                                        <div class="sender-wrapper text-right small">                                          
                                            <div class="sender small text-right"  v-if="chatlogIndex >= 1 && messages[chatlogIndex - 1].sender.type !== messages[chatlogIndex].sender.type">
                                                {{chatlog.sender.nickname}}
                                            </div>
                                            <div  class="sender small text-right"  v-if="chatlogIndex == 0">
                                                {{ chatlog.sender.nickname}}
                                            </div>
                                            
                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.time }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="recipient-container">
                                        <div class="recipient-wrapper text-left small">
                                            <div class="sender small text-left" v-if="chatlogIndex >= 1 && messages[chatlogIndex - 1].sender.type !== messages[chatlogIndex].sender.type" >
                                                {{ chatlog.sender.nickname}}
                                            </div>

                                            <div  class="sender small text-left"  v-if="chatlogIndex == 0">
                                                {{ chatlog.sender.nickname}}
                                            </div>

                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.time }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="strike" v-if="unreadMessages.length >= 1">
                                <span class="small">Unread Message(s)</span>
                            </div>

                            <div class="chat mt-1 p-1" v-for="(chatlog, chatlogIndex) in unreadMessages" :key="'my_unread_chatlog_'+ chatlogIndex">                               
                                <div v-if="current_chatbox_userid == chatlog.sender.userid">
                                    <div class="sender-container">  
                                        <div class="sender-wrapper text-right small">                                          
                                            <div class="sender small text-right"  v-if="chatlogIndex >= 1 && unreadMessages[chatlogIndex - 1].sender.type !== unreadMessages[chatlogIndex].sender.type">
                                                {{chatlog.sender.nickname}}
                                            </div>
                                            <div  class="sender small text-right"  v-if="chatlogIndex == 0">
                                                {{ chatlog.sender.nickname}}
                                            </div>
                                            
                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.time }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="recipient-container">
                                        <div class="recipient-wrapper text-left small">
                                            <div class="sender small text-left" v-if="chatlogIndex >= 1 && unreadMessages[chatlogIndex - 1].sender.type !== unreadMessages[chatlogIndex].sender.type" >
                                                {{ chatlog.sender.nickname}}
                                            </div>

                                            <div  class="sender small text-left"  v-if="chatlogIndex == 0">
                                                {{ chatlog.sender.nickname}}
                                            </div>

                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.time }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="strike" v-if="newMessageAdded == true">
                                <span class="small">Recent Message(s)</span>
                            </div>

                            <div class="chat mt-1 p-1" v-for="(chatlog, chatlogIndex) in recentMessages" :key="'my_unread_chatlog_'+ chatlogIndex">                               
                                <div v-if="current_chatbox_userid == chatlog.sender.userid">
                                    <div class="sender-container">  
                                        <div class="sender-wrapper text-right small">                                          
                                            <div class="sender small text-right"  v-if="chatlogIndex >= 1 && recentMessages[chatlogIndex - 1].sender.type !== recentMessages[chatlogIndex].sender.type">
                                                {{chatlog.sender.nickname}}
                                            </div>
                                            <div  class="sender small text-right"  v-if="chatlogIndex == 0">
                                                {{ chatlog.sender.nickname}}
                                            </div>
                                            
                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.time }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="recipient-container">
                                        <div class="recipient-wrapper text-left small">
                                            <div class="sender small text-left" v-if="chatlogIndex >= 1 && recentMessages[chatlogIndex - 1].sender.type !== recentMessages[chatlogIndex].sender.type" >
                                                {{ chatlog.sender.nickname}}
                                            </div>

                                            <div  class="sender small text-left"  v-if="chatlogIndex == 0">
                                                {{ chatlog.sender.nickname}}
                                            </div>

                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.time }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    

                    <div id="user-reply-container" class="row mt-2">

                        <div class="col-9 pr-0 mr-0">
                            <div v-for="(file, index) in files" :key="file.id" class="image-prieview-container bg-light  w-25 d-inline-block p-1 border border-light rounded">
                                <div class="remove-image-upload float-right">
                                    <a class="" href="#" @click.prevent="$refs.lessonChatUploader.remove(file)" style="padding:5px; background-color:#fff; color:#000">X</a>
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


                        <div class="col-7 pr-1 mr-1">
                            <input id="message" 
                                v-on:keyup.13="sendMessage(privateMessage)"  
                                type="text" 
                                autocomplete="off"
                                class="form-control form-control-sm mb-1" 
                                v-model="privateMessage" 
                                placeholder="Type a message" 
                                aria-label="Type a message"
                            >
                        </div>
                        <div class="col-4 px-0">
                            <div id="attach-button" class="input-group-append d-inline-block float-left">
                                <label id="file-select-button" for="lesson-chatroom-file" class="btn btn-primary mr-1 btn-sm">
                                    <i class="fas fa-paperclip"></i>
                                </label>
                            </div>

                            <div id="send-button" class="input-group-append d-inline-block float-left">
                                <button type="button"  @click.prevent="$refs.lessonChatUploader.active = false; sendMessage(privateMessage); " class="btn btn-sm btn-primary">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </button>
                            </div>     

                            <span class="button-controls" style=" display:none">
                                <button id="startUpload" type="button" class="btn btn-sm btn-success" v-if="!$refs.lessonChatUploader || !$refs.lessonChatUploader.active" @click.prevent="$refs.lessonChatUploader.active = true">
                                    <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                                </button>

                                <button type="button" class="btn btn-sm btn-danger" v-else @click.prevent="$refs.lessonChatUploader.active = false">
                                    <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                                </button>
                            </span>                                  
                        </div>  

                    </div>

                </div>
            </div>
           
        </div>
    </div>


</template>

<script>
import io from "socket.io-client";
import FileUpload from 'vue-upload-component'

var socket = null;

export default {
  name: "lessonSliderChatroomComponent",
  components: {
    FileUpload,
  },    
  data() {

    return {            

        //history is fetching status
        isFetching: false,
        interval: "",

        //first load on opening chatbox
        firstLoad: true,        

        privateMessage: "",

        messages: [],
        unreadMessages:[],
        recentMessages:[],

        
        users: [],

        //chat boxes
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
    

        newMessageAdded: false,
    };
  },
  props: {
    userid: String,
    username: String,
    nickname: String,
    user_image: String,
    api_token: String,
    csrf_token: String,   
    chatserver_url: String,
    show_sidebar: Boolean,

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
    recipient_info: {
        type: [Object, String],
        required: true
    },      
    isBroadcaster: {
        type: [Boolean],
        required: true        
    },           

  },

  mounted() 
  {
    
    
    //Transfer the object to the window
    window.lessonSliderChatroom = this;


    socket     = io.connect(this.$props.canvas_server);
    let user    = this.getUser();

    socket.emit('REGISTER', user); 

    socket.on('SEND_SLIDE_PRIVATE_MESSAGE', (response) => { 
        new Promise((resolve, reject) => {

            this.newMessageAdded = true;

            this.markMessageRead();
            this.pushPrivateMessage(response);

            resolve('private message resolved');    


        }).then((result) => {
            this.message = null;
            this.scrollToEnd();
        }); 
    });

    this.getChatHistory();

    window.addEventListener("keyup", (e) =>{
        this.prepareButtons();       
    });    


    this.initializeChatBox(user);

  },
  methods: {
   
    popUpImage(index, message) {     
       return false;
    },
    getUser() {

        if (this.$props.user_info.user_type == "MEMBER") 
        {
            return {
                channelid: this.channelid,
                userid: this.$props.member_info.user_id ,
                username: this.$props.member_info.username,
                nickname: this.$props.member_info.nickname,
                user_image: this.user_image,    
                type: this.$props.user_info.user_type,      
                status: 'online',
                chat_type: 'sender'     
            } 
        } else if (this.$props.user_info.user_type == "TUTOR") {        
            return {
                channelid: this.channelid,
                userid: this.$props.member_info.user_id ,
                username: this.$props.user_info.username,
                nickname: this.$props.user_info.firstname,
                user_image: this.user_image,    
                type: this.$props.user_info.user_type,      
                status: 'online',
                chat_type: 'sender'               
            } 
        }
    },
    getRecipient() {
        return this.$props.recipient_info;
    },
    pushPrivateMessage(data) {
        this.recentMessages.push(data);
    },    
    sendMessage: function(message) {

        //files is empty and message is empty, stop sending message
        if (this.files.length == 0 && message === "" || message === undefined)
        {           
            return false;
        }

        if (message === "" || message === undefined) {

            document.getElementById("startUpload").click();         

        } else {

            document.getElementById("startUpload").click();
            this.emitMessage(this.privateMessage);           
            
            this.privateMessage = "";
            this.$forceUpdate();   
            
            this.$nextTick(() => {
                this.scrollToEnd();
                this.prepareButtons();            
            });        
        } 
    },
    emitMessage(message) {

        var currentTime = new Date();    
        let channelid   = this.channelid;           
        let time        = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: false })

        let sender      = this.getUser();
        let recipient   = this.getRecipient();

        socket.emit("SEND_SLIDE_PRIVATE_MESSAGE", { channelid, time, recipient, sender, message }); 


        axios.post("/api/saveLessonChat?api_token=" + this.api_token, 
        {
            method              : "POST",
            channelid           : this.channelid,
            sender_id           : sender.userid,
            recipient_id        : recipient.userid,
            message             : message,
            is_read             : false,
            valid               : true,
            message_type        : sender.type,

        }).then(response => {
            if (response.data.success === true) {

            } else {
                //@todo: HIGHLIGHT error
            }
        }).catch(function(error) {
            console.log("Error " + error);                
        }); 
    },
     markMessageRead() {
        this.scrollToEnd();

        axios.post("/api/markLessonChatMessagesRead?api_token=" + this.api_token,        {
            method              : "POST",
            channelid           : this.channelid
        }).then(response => {
            //console.log("marked read")
        });
    },
    getChatHistory() {
    
        this.isFetching = true;

        let channelid   = this.channelid;

        //user is the sender
        axios.post("/api/getLessonChathistory?api_token=" + this.api_token, 
        {
            method              : "POST",
            channelid           : channelid
        }).then(response => {  

            if (response.data.success === true) {                               

                this.isFetching = false;


                //unread
                let unreadChatHistoryItems =  response.data.unreadChatHistoryItems
                
                unreadChatHistoryItems.forEach(data => {
                    let message = data.message;
                    let time    =  data.created_at;

                    let sender = {
                        userid:     data.sender_id,
                        username:   this.$props.member_info.username,                
                        nickname:   data.nickname ?? data.firstname,
                        type:       data.message_type
                    }

                    let recipient = {
                        userid:     data.recipient_id,
                        //nickname:   "recipient",
                        type:       data.message_type                    
                    }

                    this.unreadMessages.unshift({ channelid, time, recipient, sender, message });                  
                });


                //read
                let readChatHistoryItems =  response.data.readChatHistoryItems
                
                readChatHistoryItems.forEach(data => {
                    let message = data.message;
                    let time    =  data.created_at;

                    let sender = {
                        userid:     data.sender_id,
                        username:   this.$props.member_info.username,                
                        nickname:   data.nickname ?? data.firstname,
                        type:       data.message_type
                    }

                    let recipient = {
                        userid:     data.recipient_id,
                        //nickname:   "recipient",
                        type:       data.message_type                    
                    }

                    this.messages.unshift({ channelid, time, recipient, sender, message });                  
                });

                this.$nextTick(() => {
                    this.scrollToEnd();
                }); 

            }
        });

    },
    scrollToTop: function() {
        this.$forceUpdate();

        var container = this.$el.querySelector("#user-chatlog");
        if(container) {            
            container.scrollTop = 0;
        }
    },
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

                    this.emitMessage(newFile.response.image);
                }               

            }
        }

        if (this.$refs.lessonChatUploader.uploaded) {
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
    prepareButtons: function() 
    {
        //console.log(this.files.length);

        let message = document.getElementById("message").value; 
        let fileSelectBtn = document.getElementById("file-select-button");
        let sendBtn = document.getElementById("send-button");        
        
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

    initializeChatBox: function(user) 
    {   
        this.current_chatbox_userid = user.userid;
        this.messages = [];
        this.$forceUpdate();
        this.$nextTick(function(){
            this.scrollToEnd();
            this.prepareButtons(); 
        });
    },
    delayandMarkUnread() {
        console.log("delay fired")
        this.interval = setInterval(this.prepeareMarkRead, 1500);
    },
    prepeareMarkRead() {
        this.markMessagesRead(this.userid)
    },
    markMessagesRead(userid) {

        console.log("marking read")
        clearInterval(this.interval);

        axios.post("/api/markChatMessagesRead?api_token=" + this.api_token, 
        {
            method           : "POST",
            userID           : userid,
            message_type     : 'CHAT_SUPPORT'
        }).then(response => {  

            if (response.data.success === true) 
            {              
                this.unread_message_count = 0;
                clearInterval(this.interval);

            } else {
                clearInterval(this.interval);
            }
        });  
    },
    updateUserList: function(users) 
    {
      this.users = users;      
      this.$forceUpdate();
    },
  	scrollToEnd: function() 
    {
        this.$forceUpdate();
              
        this.$nextTick(function()
        {
            var container = this.$el.querySelector("#user-chatlog");
            
            if (container) {
                container.scrollTop = container.scrollHeight;         
            }            
        });

    }    
  },
  computed: {},

};
</script>


<style>
    .img_preview {
        width: 100%;
    }
    .custom-pdf {
        font-size: 100px;
    }



</style>


<style scoped>

    .img_preview {
        width: 100px;
    }
    #fileUpload {
        display: none;
    }


    .bg-blue {    
        background-color: #009fd9
    }
    .border-blue {
        border-color: #009fd9
    }
     

    .position-bottom-right {
        position: fixed;
        bottom: 0px;
        right: 75px;
        z-index: 9999;
    }
    #floating-history-btn {
        font-size: 11px;
        padding: 2px 5px 2px;
        position: absolute;
        top: 42px;
        left: 215px;
        z-index: 1;
    }
 

    .btn-xs {
        font-size: 11px;
        padding: 2px 5px 2px;
    }

    .chatlog-wrapper {
        margin-top: 5px;
    }


    .chat-button {
        border-radius: 50%;
        height: 65px;
        width: 65px;
        padding: 0px;
    }


    .user-chatlog {
        border: 1px solid #ececec;
        padding: 5px 5px 15px;
        min-height: 150px;
        max-height: 175px;
        margin-bottom: 3px;
        overflow-x: hidden;
        overflow-y: scroll;
    }

    .member-info {
        text-align: right;
    }

    .member-image {
        float: right;
        border-radius: 50%;
        margin-left: 30px;
        border: 3px solid #ededed;
    }
 
    /* SENDER BUBBLE */
    .sender-container {
        width: 100%;
        clear:both;
    }

    .sender-container .message-container {    
        position: relative;
        float: right;
    }

    .sender-container .message{
        position: relative;
        background-color: #DBF4FC;
        border-radius: .4em;
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        padding: 5px 15px 5px;
        margin-bottom: 7px;        
    }


    /* RECIPIENT BUBBLE */
    .recipient-container {
        width: 100%;
        clear:both;
    }

    .recipient-container .message-container {    
        position: relative;
        float: left;
    }

    .recipient-container .message{
        position: relative;
        background-color: #F2F6F9;
        border-radius: .4em;
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        padding: 5px 15px 5px;
        margin-bottom: 7px;
    }

    .strike {
        display: block;
        text-align: center;
        overflow: hidden;
        white-space: nowrap; 
        width: 100%;
    }

    .strike > span {
        position: relative;
        display: inline-block;
        font-size: 10px;
    }

    .strike > span:before,
    .strike > span:after {
        content: "";
        position: absolute;
        top: 50%;
        width: 9999px;
        height: 1px;
        background: #ccc;
    }

    .strike > span:before {
        right: 100%;
        margin-right: 5px;
    }

    .strike > span:after {
        left: 100%;
        margin-left: 5px;
    }


</style>