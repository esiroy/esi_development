<template>

    <div class="container">

        
        

        <table cellpadding="4" cellspacing="0" class="mt-2">
            <tbody>
                <tr>
                    <td valign="top" width="65px">
                        <div class="pl-2">
                            <img src="/images/cs.jpg" width="60px">
                        </div>
                    </td>

                    <td valign="top" class="pl-2 text-align-center">
                        <div class="cs-speech-bubble">
                            <a href="" @click.prevent="openChatBox">Chat Support</a>
                        </div>
                        <div class="small pt-1 pb-1">                        
                            <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0816220249.html','Chat Support ご利用方法',900,820);">Chat Support ご利用方法</a>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>

       

        <div id="fileUpload" class="position-bottom-right">
            <file-upload
                name="file"
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
                @input-filter="inputFilter" ref="upload">      
            </file-upload>
        </div>

        <!--[START] CHAT BUTTON -->
        <div class="position-bottom-right" v-show="showChatbox == false">
            <b-button class="chat-button mb-4 mr-5"  variant="primary" @click="openChatBox">
                 <h2 class="fa fa-comments mt-1"></h2>
                 <span class="badge badge-danger text-white small m-0 p-1" v-show="this.unread_message_count > 0">
                 {{this.unread_message_count}}
                 </span>
            </b-button>
        </div>
        <!--[END] CHAT BUTTON -->
       
        <div class="position-bottom-right mb-4 mr-5" v-show="showChatbox == true">
            <div class="float-right">
                <div class="card border-blue">
                    <div class="card-header bg-blue text-white">
                        Customer Support
                        <span class="float-right">
                            <a href="" @click.prevent="closeChatBox"><i class="fas fa-times-circle text-white"></i></a>
                        </span>
                    </div>
                    <div class="card-body text-blue" style="min-width: 30rem; max-width: 20rem;">
                                             
                        <div class="chatboxes">

                            <div class="chatbox" v-for="(chatbox, index) in this.chatboxes" :key="'chatbox_' + index">

                                <div id="chatlogs" class="user-chatlog">

                                    <div class="text-center floating-history-fetcher">                                        
                                        <button v-on:click="getChatHistory(chatbox, false)" id="floating-history-btn" class="btn btn-sm btn-secondary d-none">
                                            Fetch History                                                
                                        </button>
                                        <button id="history-notify" class="btn btn-sm btn-primary" v-show="loadingHistory">
                                            Fetching History...
                                        </button>                                        
                                    </div>                        

                                    <div v-for="(chatlog, chatlogIndex) in chatlogs[chatbox.userid]" :key="'my_chatlog_'+ chatlogIndex">

                                        <div class="row" v-if="chatlog.sender.type == 'CHAT_SUPPORT'">     

                                            <div class="col-md-9 pl-4">
                                                <div v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'CHAT_SUPPORT'">                           
                                                <span class="small">{{ chatlog.sender.nickname }}, {{ chatlog.time  }}</span>                              
                                                </div>
                                                <div class="chat-support-message" v-html="chatlog.sender.message"></div>
                                            </div>

                                            <div class="col-md-3">&nbsp;</div>
                                        </div>


                                        <div class="row" v-if="chatlog.sender.type == 'MEMBER'" >                                            
                                            <div class="col-md-10">                    
                                                <div class="member-info" v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'MEMBER'">    
                                                <span class="small">{{ chatlog.sender.nickname }}, {{ chatlog.time  }}</span>                       
                                                </div>

                                                <div class="member-message-container">
                                                <div class="member-message" v-html="chatlog.sender.message"></div>
                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="member-info" v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'MEMBER'">
                                                <img :src="chatlog.sender.user_image" class="img-fluid member-image"/> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                

                                <div class="row mt-2">

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
                                        <input id="message" 
                                            v-on:keyup.13="sendMessage(chatbox, index)"  
                                            type="text" 
                                            class="form-control form-control-sm mb-1" 
                                            v-model="message[index]" 
                                            placeholder="Type a message" 
                                            aria-label="Type a message"
                                        >
                                    </div>
                                    <div class="col-3 px-1">
                                        <div id="attach-button" class="input-group-append d-inline-block float-left">
                                            <label id="file-select-button" for="file" class="btn btn-primary mr-1 btn-sm">
                                                <i class="fas fa-paperclip"></i>
                                            </label>
                                        </div>

                                        <div id="send-button" class="input-group-append d-inline-block float-left">
                                            <button type="button"  @click.prevent="$refs.upload.active = false; sendMessage(chatbox, index); " class="btn btn-sm btn-primary">
                                                <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </button>
                                        </div>     

                                        <span class="button-controls" style=" display:none">
                                            <button id="startUpload" type="button" class="btn btn-sm btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
                                                <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger" v-else @click.prevent="$refs.upload.active = false">
                                                <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                                            </button>
                                        </span>                                  
                                    </div>  

                                </div>

                                
                            </div>

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
//const socket = io.connect("http://localhost:30001");

let socket = "";

export default {
  name: "member-floating-chat-component",
  components: {
    FileUpload,
  },    
  data() {
    return {            

            //first load on opening chatbox
        firstLoad: true,     


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


        //buttons
        ButtonSend: null,
        ButtonUpload: null,


    
    };
  },
  props: {
    userid: String,
    username: String,
    nickname: String,
    user_image: String,
    api_token: String,
    csrf_token: String,      
  },
  methods: 
  {
    getChatHistory: function(user, scrollToBottom) 
    {        
        //console.log("current_page : " + this.page[user.userid]);

        this.chatFetchStatus = "FETCHING";

        let historyNotifier1 = document.getElementById("history-notify");
        if (historyNotifier1) {
            historyNotifier1.style.display = "inline-block";  
            document.getElementById("floating-history-btn").style.display = "none";
        }

        

        //user is the sender
        axios.post("/api/getChathistory?api_token=" + this.api_token, 
        {
            method              : "POST",
            sender_id           : this.userid, //login user id
            recipient_id        : user.userid, //chatbox user id (overrided to admin)
            page                : this.page[this.userid]                   
        }).then(response => 
        {                
            //this.chatlogs[user.userid] = [];  //@NOTE: *** EMPTY CHAT LOGS EVERY QUERY ****

            if (response.data.success === true) 
            {               
                
                let historyNotifier = document.getElementById("history-notify");

                if (historyNotifier) {
                    historyNotifier.style.display = "none";  
                }

                let chatboxUsername = null;
                let chatboxNickname = null;
                let chatboxImage = null;

                this.page[user.userid] =  +this.page[user.userid] + 1;

                
                let chatHistoryItems = response.data.chatHistoryItems.data;
                
                //{ LOOP HERE FOR CHAT HISTORY }
                //response.data.chatHistoryItems.data.forEach(data => {

                chatHistoryItems.forEach(data => {

                    if (data.message_type == "MEMBER") {
                        chatboxUsername = user.username;
                        chatboxNickname = user.nickname   
                        chatboxImage = user.user_image;                         
                    } else {
                        chatboxUsername = this.username;
                        chatboxNickname = this.nickname
                        chatboxImage = this.user_image;
                    }


                    //console.log(data);                
                    let sender = {
                        'msgCtr': 0,
                        'userid': data.userid,
                        'nickname': chatboxNickname,
                        'username': chatboxUsername,          
                        'user_image': chatboxImage,
                        'message': data.message,                            
                        'type': data.message_type
                    };

                    this.chatlogs[user.userid].unshift({
                        time: data.created_at,
                        sender: sender,                                
                    });
                });
                let reversedChatHistory =  this.chatlogs[user.userid];

                this.chatlogs[user.userid] = reversedChatHistory;

                this.$nextTick(()=>
                {  
                    this.$forceUpdate();
                    if (scrollToBottom == true) {
                        this.scrollToEnd();
                    } else {
                        this.scrollToTop();
                    }     
                });

                this.chatFetchStatus = "ACTIVE";

            } else {
                //@todo: HIGHLIGHT error
                let historyNotifier = document.getElementById("history-notify");

                if (historyNotifier) {
                    historyNotifier.style.display = "none";  
                }                    
            }
        
        }).catch(function(error) {
            console.log("Error " + error);                
        });

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

                    socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender });                      
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

                console.log ("1");
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

    closeChatBox() {
         this.showChatbox = false;
    },
    openChatBox() {

        socket = io.connect("https://chatserver.mytutor-jpn.info:30001");

        this.showChatbox = true;
    },

    addChatEventListener() {

        socket = io.connect("https://chatserver.mytutor-jpn.info:30001");

    
        //register as user
        let user = {
            userid: this.$props.userid ,
            username: this.username,
            nickname: this.nickname,
            user_image: this.user_image,      
            type: "MEMBER",      
        }    
        socket.emit('REGISTER', user);     

        //update the list
        socket.on('update_user_list', users => {
            this.updateUserList(users); 
        });


        socket.on('PRIVATE_MESSAGE', data => 
        {  
            let admin = {
                userid: 1,
                username: "admin",
            }
            this.openChatBox(admin);
                
            if (data.sender.username  == this.username) {
                //console.log("my own message")
            
                let sender = {
                    'userid': data.sender.userid,
                    'username': data.sender.username,   
                    'nickname': data.sender.nickname,
                    'message': data.sender.message,
                    'type': data.sender.type,
                    'user_image': this.user_image,
                };
                
                this.chatlogs[admin.userid].push({
                        time: data.time,
                        sender: sender            
                });   

                this.$forceUpdate();  
                this.$nextTick(function()
                {
                    this.scrollToEnd();        
                });             
            }

            if (data.recipient.userid == this.userid) 
            {
                //console.log("sent from support")

                this.unread_message_count++;


                let sender_customer_support = {
                    'userid': data.sender.userid,
                    'username': data.sender.username,   
                    'nickname': data.sender.nickname,
                    'message': data.sender.message,
                    'type': data.sender.type,
                };

                this.chatlogs[admin.userid].push({
                        time: data.time,
                        sender: sender_customer_support,
                        //message: data.sender.message        
                });      
                this.$forceUpdate();  
                this.$nextTick(function()
                {
                    this.scrollToEnd();        
                });
            
                //play audio
                let audio = new Audio("/mp3/message-sent.mp3");
                audio.play();            
            }  

        });

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
				//console.log(this.chatboxes[i])
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
    sendMessage: function(chatbox, index) 
    {
     
        //files is empty and message is empty, stop sending message
        if (this.files.length == 0 && (this.message[index] === "" || this.message[index] === undefined)) 
        {           
            return false;
        }

        document.getElementById("startUpload").click();

        if (this.message[index] === "" || this.message[index] === undefined) {

            //No message just upload

        } else {


            var currentTime = new Date();    

            //recipient
            let id = chatbox.id;     
            let time = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })


            //@NOTE: WE ARE ON MEMBER AREA, AUTOMATIC TYPE IS CHAT SUPPORT AND SENDER TYPE IS MEMBER
            let recipient = {
                'id': chatbox.id,
                'userid': chatbox.userid,
                'username':  chatbox.username,
                'type': "CHAT_SUPPORT",
            };


            //get the sender from props  (admin)
            let sender = {
                'userid': this.userid,
                'username': this.username,        
                'nickname': this.nickname,
                'message': this.message[index],
                'user_image': this.user_image,  
                'type': "MEMBER"
            };

			/*
            this.chatlogs[chatbox.userid].push({
                sender: sender,
                message: this.message[index],
                time: time,
            });
			*/

            //console.log(recipient);
            socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender }); 

            //get the sender from props (user)
            let broadcast_recipient = {
                'id': this.id,
                'userid': this.userid,
                'username':  this.username,
            };


            //get the sender from props  (admin)
            //console.log("sender chat --- > "+ chatbox.userid);

            let broadcast_sender = {
                'msgCtr': 0,
                'userid': chatbox.userid,
                'nickname': chatbox.nickname,
                'username': chatbox.username,          
                'message': this.message[index],
                'user_image': chatbox.user_image, //@todo: make this for customer support 
                'type': "MEMBER"
            };

            //console.log("own message", broadcast_sender);
            socket.emit("SEND_OWNER_MESSAGE", { id, time, broadcast_recipient, broadcast_sender });              

            let userMessage = this.message[index];

            //scroll to end then save to table
            this.$nextTick(() => {           
                axios.post("/api/saveCustomerSupportChat?api_token=" + this.api_token, 
                {
                    method              : "POST",
                    sender_id           : this.userid,
                    recipient_id        : chatbox.userid,
                    message             : userMessage,
                    is_read             : false,
                    valid               : true,
                    message_type        : "MEMBER",
                }).then(response => {
                    if (response.data.success === true) {

                    } else {
                        //@todo: HIGHLIGHT error
                    }
                }).catch(function(error) {
                    console.log("Error " + error);                
                });                     
            });

            //clear (always at bottom)
            this.message[index] = "";
            this.$forceUpdate();   
            
            this.$nextTick(function()
            {
                this.scrollToEnd();
                this.prepareButtons();            
            });

        
        } 
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
            var container = this.$el.querySelector("#chatlogs");
            if (container) {
                container.scrollTop = container.scrollHeight;         
            }            
        });

    }    
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
  
    window.addEventListener("keyup", (e) =>
    {
        this.prepareButtons();       
    });
    

    
    //automatically open for admin when popup is created.
    let admin = {
      userid: 1,
      username: "admin",
      status: 'offline',
    }
    this.initializeChatBox(admin);


    this.addChatEventListener()   



  },
};
</script>

<style scoped>


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

    .chat-button {
        border-radius: 50%;
        height: 65px;
        width: 65px;
        padding: 0px;
    }


    .user-chatlog {
        border: 1px solid #ececec;
        padding: 5px;
        min-height: 300px;
        max-height: 300px;
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
 

  .chat-support-message {   
    color: #242322;
    background-color: #F2F6F9;
    width: -webkit-fit-content;
    width: -moz-fit-content;
    width: fit-content;
    display: block;
    margin-top: 3px;
    position: relative;
    padding: 7px 12px 7px;  
  }

  .member-message-container {    
    position: relative;
    float:right
  }

   .member-message {
    color: #242322;
    background-color: #DBF4FC;
    width: -webkit-fit-content;
    width: -moz-fit-content;
    width: fit-content;
    display: block;
    margin-top: 5px;
    padding: 7px 22px 7px;
  }


</style>