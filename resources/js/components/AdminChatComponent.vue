<template>
<div class="AdminChatWrapper">
    <div id="AdminChat" class="adminChat">
        <div class="container  bg-light">
            <div class="row">

                <div class="col-md-4">
                    <div class="card memberlist-panel mt-2">
                    <div class="card-header">
                        Users
                    </div>
                    <div class="card-body">
                        <div :id="'member-'+user.userid" class="member-information-container" v-show="user.userid !== userid" v-for="(user, index) in this.users" :key="'user_'+ index"  v-on:click="openChatBox(user)" >
                            <div class="member" v-if="user.userid !== userid">
                            <div class="profile-photo">
                                <img :src="user.user_image"  class="img-fluid">
                            </div>
                            <div class="profile-user-info">
                                <a class="">{{ user.username }}</a> 
                                <span class="badge badge-danger" v-show="chatCount[user.userid] >= 1">
                                {{ chatCount[user.userid] }}
                                </span>
                            </div>
                            
                            </div>                    
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-md-8">

                    <div class="chatboxes mt-2">

                        <div :id="'chatbox-'+chatbox.userid" class="chatbox" v-for="(chatbox, index) in this.chatboxes" :key="index">
                            <div class="card">
                                <div class="card-header">
                                    <div style="float:left">                            
                                        <h5>{{ chatbox.nickname }}</h5>
                                        <!--<div class="small">{{ chatbox.username }}</div>-->
                                        <div class="small">ID Number: {{ chatbox.userid }}</div>
                                    </div>

                                    <div style="float:right">          
                                        <button v-on:click="deleteChatbox(index)" style="border:none">X</button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <form :name="chatbox.userid" onsubmit="return false;">
                                    
                                    <div class="text-center floating-history-fetcher">                                        
                                        <button v-on:click="getChatHistory(chatbox, false)" id="floating-history-btn" class="btn btn-sm btn-secondary" style="display:none">
                                            Fetch History                                                
                                        </button>
                                      

                                        <button id="history-notify" class="btn btn-sm btn-primary" style="display:inline-block">
                                            Fetching History...
                                        </button>                                        
                                    </div>

                                    <div id="user-chatlog" class="user-chatlog">
                                        <div class="container" v-for="(chatlog, chatlogIndex) in chatlogs[chatbox.userid]" :key="'my_chatlog_'+chatlogIndex">                                               
                                            <div class="row" v-if="chatlog.sender.type == 'CHAT_SUPPORT'">
                                            <div class="col-md-3">&nbsp;</div>
                                            <div class="col-md-9">
                                                <div v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'CHAT_SUPPORT'">                                                                                      
                                                <chatsupport-info-component 
                                                    :userid="chatlog.sender.userid"
                                                    :image="chatlog.sender.user_image"
                                                    :nickname="chatlog.sender.nickname" 
                                                    :time="chatlog.time">
                                                    </chatsupport-info-component>
                                                </div>

                                                <div style="float:right">
                                                <div class="chatsupport-message" v-html="chatlog.sender.message"></div>
                                                </div>
                                            </div>
                                            </div>

                                            <div class="row" v-if="chatlog.sender.type == 'MEMBER'" >
                                                <div class="col-md-9">
                                                    <div v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'MEMBER'">                                                                                      
                                                    <member-info-component 
                                                        :userid="chatlog.sender.userid"
                                                        :image="chatlog.sender.user_image"
                                                        :nickname="chatlog.sender.nickname" 
                                                        :time="chatlog.time">
                                                        </member-info-component>
                                                    </div>
                                                    <div class="member-message" v-html="chatlog.sender.message"></div>
                                                </div>
                                                <div class="col-md-3">&nbsp;</div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="input-group mt-3">

                                        <div style="background-color:#F1F1F4; display:inline-block; padding: 5px; width: 88%; margin-right: 8px">
                                            <div style="border:1px solid #ccc; width: 100%">
                                                <div v-for="(file, index) in files" :key="file.id" style="display:inline-block; padding:5px; " class="image-prieview-container">

                                                    <div class="remove-image-upload" style="float:right;">
                                                        <a class="" href="#" @click.prevent="$refs.upload.remove(file)" style="padding:5px; background-color:#fff; color:#000">X</a>
                                                    </div>

                                                    <img v-if="file.type == 'image/jpeg' || file.type == 'image/png'" :src="file.thumb" style="width:150px" :id="'image-preview-'+index">  
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
                                    
                                            <input id="message" v-on:keyup.13="sendMessage(chatbox, index)"  type="text" class="form-control" v-model="message[index]" placeholder="Type a message" aria-label="Type a message" >
                                        </div>

                                        <div style="background-color:#fff; display:inline-block">
                                        
                                            <label id="file-select-button" for="file" class="btn btn-lg btn-outline-dark" 
                                                    style="margin:0px;font-size:20px">
                                                <i class="fas fa-paperclip"></i>
                                            </label>

                                            <div id="send-button" class="input-group-append" style="display:inline-block;">
                                                <button type="button" :id="'btn_'+chatbox.userid" 
                                                    @click.prevent="$refs.upload.active = false; sendMessage(chatbox, index); "
                                                    class="btn btn-lg btn-primary">
                                                        <i class="far fa-share-square"></i>
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
                                    
                                    </form>


                                </div>
                            </div>



                        </div>
                    </div>                            
                </div>
            </div>
        </div>

        <div style="display:none">
            <file-upload
                name="file"
                class="btn btn-primary"
                extensions="jpeg,jpg,gif,pdf,png,webp"
                accept="image/png, application/pdf, image/prigif, image/jpeg, image/webp"
                v-model="files"
                post-action="/uploader/fileUploader"            
                :data="{ 
                    'message_type': 'CHAT_SUPPORT',
                    'current_chatbox_userid': this.current_chatbox_userid
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

    </div>


    <div id="enterChat"  class="container bg-light text-center">       
        <button type="button" class="btn btn-success my-5" v-on:click="enterAdminChat()">Enter Administrator Chat</button>
        <div>
            <small>You must click "Enter Administrator Chat" button to enter</small>
            <small>Sorry for the inconvenience</small>
        </div>
    </div>

</div>
</template>

<script>
import io from "socket.io-client";
import FileUpload from 'vue-upload-component'

var socket = null;


export default {
  name: "chat-component",
  components: {
    FileUpload,
  },  
    data() {
        return {
            message: [],
            users: [],
            chatCount: [],

            //chat boxes
            chatboxes: [],
            chatlogs: [],
            message_count: 0,
            test: 0,

            //this will hold the current recipient userid
            current_chatbox_userid: "",
            current_chatbox_username: "",
            current_chatbox_nickname: "",

            //WINDOW STATUS FOR TAB TITLE BLINKER
            TabTitle: "",
            windowStatus: "FOCUSED",        
            interval: "",
            tabTitle: "",

            //uploader
            files: [],

            page: [],

            chatFetchStatus: "ACTIVE",
        };
    },
    props: {
        userid: String,
        username: String,
        nickname: String,
        api_token: String,    
        csrf_token: String,  
        chatserver_url: String,              
    },
    methods: 
    {
        windowTitleToggle() {
            if (this.tabTitle == "") {
                this.tabTitle = "You have a message";
            } else {
                this.tabTitle = "";
            }
             document.title = this.tabTitle;
        },
        blink() {            
            this.interval = setInterval(this.windowTitleToggle, 1000);
        },
        stopBlink() {
            document.title = this.TabTitle;
            clearInterval(this.interval);
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
                        //Add to the $ref='folderComponent' - uploader/show.blade.php
                        let file = [{
                                    'id'        : newFile.response.id,
                                    'file_name' : newFile.response.file,
                                    'size'      : newFile.response.size,
                                }]


                        // let files = this.$root.$refs.folderComponent.files.push(...file);
                        //console.log("file uploaded " + newFile.response.image)


                        var currentTime = new Date();
                        let time = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

                        //get the sender from props  (admin)
                        let sender = {
                            'msgCtr': 0,
                            'userid': this.userid,
                            'nickname': this.nickname,
                            'username': this.username,          
                            'message': newFile.response.image,
                            'user_image': "https://mypage.mytutor-jpn.com/storage/user_images/noimage.jpg", //no image for sending image (optional)
                            'type': "CHAT_SUPPORT"
                        };
                    
                        this.chatlogs[newFile.response.recipient_id].push({
                            sender: sender,
                            message: newFile.response.image,
                            time: time
                        });

                        this.$forceUpdate(); 
                      

                        //get the sender from props (user)                        
                        let id = newFile.response.id

                        let recipient = {
                            'id': newFile.response.id,
                            'userid': newFile.response.recipient_id,
                            'username': newFile.response.recipient_username,
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
        scrollToTop: function() {
            this.$forceUpdate();

            var container = this.$el.querySelector("#user-chatlog");
            if(container){            
                container.scrollTop = 250;
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
                    //console.log("scrloginoll to end")   
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
        deleteChatbox: function(index) {
            this.files = [];
            this.chatboxes.splice(index, 1)
        },
        clearMsgCount: function(userid)  {
           // console.log(userid, "clearing count")
            this.chatCount[userid] = 0;
        },
        getPaginatedHistory: function() 
        {             
            let chatScrubber = document.getElementById("user-chatlog");
            let total = parseInt(chatScrubber.scrollTop);          

            if (typeof this.page[this.current_chatbox_userid] !== 'undefined' &&  this.page[this.current_chatbox_userid].length > 0) {
                //chatlogs has an array, we will not instantiate the array again. 
            } else {
               //this.chatlogs[this.current_chatbox_userid] = [];
                //this.page[this.current_chatbox_userid] = 1;
            }
            
            chatScrubber.addEventListener("scroll", (event) => {
                //console.log(chatScrubber.scrollTop);                          
                var shot = parseInt(total) - parseInt(chatScrubber.scrollTop);

                var percent = parseInt((shot / total) * 100);

                //REACHED TOP OF SCROLL
                if (!isNaN(percent) && percent == 100) 
                {
                    console.log("compute precentage : " + shot + " " + total + " : "  + percent)
                    //this.page[this.current_chatbox_userid] = +this.page[this.current_chatbox_userid] + 1;

                    //console.log(this.page[this.current_chatbox_userid]);

                    let user = {
                        'userid': this.current_chatbox_userid,
                        'username': this.current_chatbox_username,
                        'nickname': this.current_chatbox_nickname,
                    };

                    if (this.chatFetchStatus == "ACTIVE") {
                        this.getChatHistory(user, false)
                    }

                    document.getElementById("floating-history-btn").style.display = "none";
                } else {

                    if (this.chatFetchStatus !== "ACTIVE") {
                        document.getElementById("floating-history-btn").style.display = "none";
                    } else {
                        document.getElementById("floating-history-btn").style.display = "inline-block";
                    }
                }
                
            });            
        },
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
                sender_id           : user.userid,
                recipient_id        : this.userid,
                page                : this.page[user.userid]                   
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
        openChatBox: function(user) 
        {   
            //this.chatboxes.push(user); /* {this will open new window} */
            this.chatboxes = [user];
            this.prepareChatBox(user);

            //@note: user is the sender     
            if (isNaN(this.page[user.userid])) {
                this.page[user.userid] = 1;
                //console.log("page initialized")
            }

            if (this.current_chatbox_userid !== user.userid) {
                //console.log("new chatbox")
                this.chatlogs[user.userid] = []
                this.page[user.userid] = 1;
                this.getChatHistory(user, true);
            }     


            //reset bg color      
            var elements = document.getElementsByClassName("member-information-container");
            for(var i = 0; i < elements.length; i++){
                elements[i].style.removeProperty("background");
            }

            //change color for selected item
            document.getElementById("member-"+user.userid).style.background = "#C7EDFB";    


            

            this.$forceUpdate();

            this.$nextTick(function()
            {
                this.scrollToEnd();
                this.prepareButtons(); 
            });

            this.chatCount[user.userid] = 0;

            
            this.current_chatbox_userid = user.userid;
            this.current_chatbox_username = user.username;
            this.current_chatbox_nickname = user.nickname;
        },
        prepareChatBox: function(user) 
        {
            if (typeof this.chatlogs[user.userid] !== 'undefined' &&  this.chatlogs[user.userid].length > 0) {
                //chatlogs has an array, we will not instantiate the array again. 
            } else {
                this.chatlogs[user.userid] = [];
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

                //recipient
                let id = chatbox.id;     

                var currentTime = new Date();
                let time = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

                //get the sender from props (user)
                let recipient = {
                    'id': chatbox.id,
                    'userid': chatbox.userid,
                    'username':  chatbox.username,
                };


                //get the sender from props  (admin)
                let sender = {
                    'msgCtr': 0,
                    'userid': this.userid,
                    'nickname': this.nickname,
                    'username': this.username,          
                    'message': this.message[index],
                    'user_image': this.user_image, //@todo: make this for customer support 
                    'type': "CHAT_SUPPORT"
                };
            
                /*
                this.chatlogs[chatbox.userid].push({
                    sender: sender,
                    message: this.message[index],
                    time: time
                });
                */
            
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
                        message_type        : "CHAT_SUPPORT",
                    }).then(response => {
                        if (response.data.success === true) {

                        } else {
                            //@todo: HIGHLIGHT error
                        }
                    }).catch(function(error) {
                        console.log("Error " + error);                
                    });                     

                });      

                //semd
                socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender });



                //get the sender from props (user)
                let broadcast_recipient = {
                    'id': this.id,
                    'userid': this.userid,
                    'username':  this.username,
                };


                //get the sender from props  (admin)
                let broadcast_sender = {
                    'msgCtr': 0,
                    'userid': chatbox.userid,
                    'nickname': chatbox.nickname,
                    'username': chatbox.username,          
                    'message': this.message[index],
                    'user_image': chatbox.user_image, //@todo: make this for customer support 
                    'type': "CHAT_SUPPORT"
                };

                socket.emit("SEND_OWNER_MESSAGE", { id, time, broadcast_recipient, broadcast_sender }); 

                //clean up and sae
                this.message[index] = "";
                this.$forceUpdate();

                this.$nextTick(function()
                {            
                    this.scrollToEnd();
                    this.prepareButtons();
                });
            }
        },        
        prepareButtons: function() 
        {
            //console.log(this.files.length);

            let message = document.getElementById("message").value;  
            let fileSelectBtn = document.getElementById("file-select-button");
            let sendBtn = document.getElementById("send-button");        
            
            if (message == "" && this.files.length == 0) {            
                fileSelectBtn.style.display = "block";
                sendBtn.style.display = "none";                
            } else {
                fileSelectBtn.style.display = "none";            
                sendBtn.style.display = "block";
            }        
        },
        updateUserList: function(users) 
        {

            //filter duplicates
            let uniqueUsers = this.filterUnique(users);

            //filter chat support, we will not show on the list if the users is a chat support
            let fusers = uniqueUsers.filter(user => user.type !== "CHAT_SUPPORT");
            this.users = fusers;
            this.$forceUpdate();
        }, 
        filterUnique: function (users) {
          let result = users.reduce((unique, o) => {
              if(!unique.some(obj => obj.username === o.username )) {
                unique.push(o);
              }
              return unique;
          },[]);          
          return result;
        },
        markSeenMessages() {
            let isOpenChatbox = document.getElementById("chatbox-"+this.current_chatbox_userid);
            if (isOpenChatbox) {
                setTimeout(function () {
                    this.chatCount[this.current_chatbox_userid] = 0;
                    this.$forceUpdate();
                }.bind(this), 1500)            
            }            
        },
        enterAdminChat: function() {
            //console.log("chat activated");
            //(force TYPE as CHAT_SUPPORT) register as user
            let user = {            
                userid: this.userid,
                username: this.username,
                nickname: "Customer Support",
                type: "CHAT_SUPPORT",
            }
            socket.emit('REGISTER', user);

            let adminChat = document.getElementById("AdminChat");        
            adminChat.style.display = 'block';


            let enterChat = document.getElementById("enterChat");
            enterChat.style.display = 'none';            
        },        
  	},
	computed: {},
	updated: function () {},
	mounted: function () {  

        socket = io.connect(this.$props.chatserver_url);




        let adminChat = document.getElementById("AdminChat");        
        adminChat.style.display = 'none';


        let enterChat = document.getElementById("enterChat");
        enterChat.style.display = 'block';


        this.windowStatus = "FOCUSED";
        this.TabTitle =  document.title;

        window.addEventListener("keyup", (e) =>
        {
            this.prepareButtons();       
        });        

        window.addEventListener("focus", (e) => {
            this.windowStatus = "FOCUSED";            
            this.stopBlink();
            this.markSeenMessages();
        });


        window.addEventListener("blur", (e) => {
            this.windowStatus = "BLURRED";
            //console.log(this.windowStatus);
        });



        //update the list
        socket.on('update_user_list', users => {
            this.updateUserList(users); 
        });

	    socket.on("OWNER_MESSAGE", data => {            
            //console.log(data.broadcast_recipient.userid + " owner => " + this.userid);
		    if (data.broadcast_recipient.userid == this.userid) 
            {
                //console.log(" owner is me");

                this.prepareChatBox(data.broadcast_recipient);

                //console.log("owner message", data);

                let sender = {
                    'msgCtr': 1,
                    'userid': data.broadcast_sender.userid,
                    'username': data.broadcast_sender.username, 
                    'nickname':  "Customer Support",
                    'message': data.broadcast_sender.message,
                    'user_image': data.broadcast_sender.user_image,   
                    'type': data.broadcast_sender.type,          
                };

               
                this.chatlogs[data.broadcast_sender.userid].push({
                        time: data.time,
                        sender: sender            
                });
                
                this.$forceUpdate();
                this.$nextTick(function()
                {
                    this.scrollToEnd();
			    }); 			
            } else {
                //console.log("this is from member");
            }	
	    });

        socket.on('PRIVATE_MESSAGE', data => 
        {

            //this.openChatBox(data.sender)
            this.prepareChatBox(data.sender);

            
            let sender = {
                'msgCtr': 1,
                'userid': data.sender.userid,
                'username': data.sender.username, 
                'nickname': data.sender.nickname,
                'message': data.sender.message,
                'user_image': data.sender.user_image,   
                'type': data.sender.type,          
            };

            
            this.chatlogs[data.sender.userid].push({
                    time: data.time,
                    sender: sender            
            });

            this.$forceUpdate();
            this.$nextTick(function() {
                this.scrollToEnd();
            });             

            this.$nextTick(function()
            {
                if (isNaN(this.chatCount[data.sender.userid])) {
                    this.chatCount[data.sender.userid] = 1;
                } else {
                    this.chatCount[data.sender.userid] += 1;
                }
            }); 

            //DETECTION FOR OPENED CHATBOX,
            //AND ZERO OUT THE CHAT MESSAGE COUNT IN 1 AND A HALF SECOND SINCE IT WILL BE CONSIDERED READ
            //THIS WILL BE DISCREGARDED IF WINDOWSTATUS IS BLURRED
            if (this.windowStatus == "FOCUSED") {
                this.markSeenMessages();
            }
            
            if (this.windowStatus == "BLURRED") {
                this.blink();
                //console.log("window status ", this.windowStatus);                
            }
            
            //console.log("private MSG", data);
            
            if (data.recipient.userid == this.userid || data.recipient.type == "CHAT_SUPPORT") {                
                //play audio
                let audio = new Audio("/mp3/message-sent.mp3");               
                audio.play();
            }             

        });
    },
};


Vue.component("member-info-component", {
  props: ['image', 'nickname', 'time'],
  data: function () {
    return {};
  },
  template:
    `<div style='text-align:left'>      
      <span class="pl-3 small">{{ nickname }}, {{ time }}</span>
      <img :src="image" class="img-fluid member-image" width="50"  style='float:left; border-radius:50%'/> 
    </div>`
    ,    
});


Vue.component("chatsupport-info-component", {
  props: ['image', 'nickname', 'time'],
  data: function () {
    return {};
  },
  template:
    `<div style='text-align:right'>      
      <span class="pl-3 small">{{ nickname }}, {{ time }}</span>
      
    </div>`
    ,    
});

</script>


<style>
.img_preview {
    width: 100px;
}
.custom-pdf {
    font-size: 100px;
}
</style>

<style scoped>
.floating-history-fetcher {
    position: absolute;
    top: 65px;
    display: inline-block;    
    text-align: center;
    width: 100%;
    z-index: 99999;
}

.memberlist-panel .card-header {
  background-color: #35bbeb;
  border: #35bbeb;
  font-weight: bold;
  color: #fff;
}

.memberlist-panel .card-body {
  padding: 0px;
}

.memberlist-panel .card-body {
  height: 580px;  
  overflow: hidden;
}

.memberlist-panel .card-body:hover {
  height: 580px;
  overflow-y: scroll;
  
}

.member-information-container {
  padding: 15px 3px 15px;
  border-bottom:1px solid rgb(253, 253, 253)
}

.member-information-container:hover {
   background-color: #e1f6fd;
}

.member .profile-photo img {
    border-radius: 50%;
}

.member-image {
  border:1px solid #ccc;
}

.member-message {
  margin-left: 65px;
  color: #242322;
  background-color: #F2F6F9;
  width: fit-content;
  display: block;
  margin-top: 3px;
  position: relative;
  padding: 7px 12px 7px;
}

/*chatbox styles */
.chatbox-info-heading {
   background-color: #fff;
   border-bottom: 1px solid #ccc
}

.chatsupport-image {
  float: left;
}

.user-chatlog {
  padding:5px;
  height: 420px;
  overflow-y: auto;
}

.profile-photo {
  width: 50px;
  display: inline-block;
}

.profile-user-info {
  display: inline-block;
}


.chatsupport-message {
    color: #242322;
    background-color: #DBF4FC;
    width: fit-content;
    display: block;
    margin-top: 5px;
    position: relative;
    padding: 7px 22px 7px; 
}



/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 12px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #ccc; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #333; 
}  


</style>