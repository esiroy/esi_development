<template>
    <div class="AdminChatWrapper">


        <div id="AdminChat" class="adminChat">

            <div class="container bg-light">
                <div class="row">

                    <!-- USER LIST CONTAINER -->
                    <div class="col-md-4 pr-1">

                        <div class="card memberlist-panel mt-2">
                            <div class="card-header">
                                Users
                                <div class="float-right">                               
                                     <span class="primary-outline" v-b-modal.createNewMessage>
                                        <b-icon icon="pencil-square" aria-hidden="true"></b-icon>
                                     </span>
                                </div>                            
                            </div>
                            
                            <div class="card-body">

                                <div v-for="(user, index) in this.users" :key="'user_'+ index" v-on:click="openChatBox(user)" > 

                                    <div id="user-list" v-if="user.userid !== userid" >

                                        <div v-if="user.totalMsg > 0 || user.supportMsg > 0 " :class="[user.userid == activeUserID ? 'active' : '', 'user-info']">
                                            <div class="profile-photo align-top">
                                                <img :src="user.user_image"  class="img-fluid rounded">
                                            </div>

                                            <div class="profile-user-info align-bottom">

                                                <div class="username small font-weight-bold">{{ user.username }}</div>
                                                <div class="nickname small text-secondary">{{ user.nickname }}</div>
                                                <div id="user-recent-message" class="small text-secondary">
                                                    {{ user.recentMsg }}
                                                </div>


                                                <div class="small float-left">
                                                    <span class="badge badge-pill badge-success" v-show="user.status == 'online'">{{ user.status }}</span>
                                                    <span class="badge badge-pill badge-secondary" v-show="user.status == 'offline'">{{ user.status }}</span>
                                                </div>

                                                <div class="small float-left ml-1">
                                                    <span id="message_counter" class="badge badge-danger small" v-if="user.unreadMsg > 0" >
                                                        {{ user.unreadMsg }}
                                                    </span>
                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- [end] USER LIST -->

                    <div class="col-md-8 pl-1">
                        <div class="chatbox mt-2">    

                            <div v-if="this.current_user !== null">
                            
                                <!--[start] card -->
                                <div class="card">

                                   <!--[start] User Information Header -->
                                    <current-user-component :user="this.current_user"></current-user-component>
                                    <!--[end] User Information Header -->

                                    <!-- Chat Log Information Header -->
                                    <div class="card-body">

                                        <div v-if="isLoading == true" class="loading-container text-center">
                                            <div class="btn btn-xs btn-secondary">
                                                <i class="fas fa-sync fa-spin"></i> 
                                                Loading...
                                            </div> 
                                        </div>
                                        <div id="user-chatlog" class="border rounded" v-else-if="isLoading == false" @scroll="handleScroll"> 


                                            <div class="text-center floating-history-fetcher mt-3" v-show="historyNotifier == true"> 
                                                <div v-on:click="getChatHistory(current_user, false)" id="floating-history-btn" class="btn btn-xs btn-secondary" 
                                                    v-show="isFetching == false">
                                                    Fetch History                        
                                                </div>   
                                                <div v-show="isFetching == true"  id="floating-history-btn" class="btn btn-xs btn-primary">
                                                    <i class="fas fa-sync fa-spin"></i>  Loading
                                                </div>                                                     
                                            </div>                                


                                            <chatlogs-component :chatlogs="chatlogsHistory"></chatlogs-component>

                                            <div v-if="chatlogsUnread.length == 0 " >
                                                <div class="text-center mb-5" v-if="chatlogs.length == 0 && chatlogsUnread.length == 0">
                                                    <div class="hr-center small"> Unread Messages </div>
                                                    <div class="badge badge-xs badge-light text-secondary small rounded p-2">
                                                        You have no unread messages
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="chatlogsUnread.length >= 1">
                                                <div class="hr-center small" v-if="chatlogs.length == 0"> Unread Messages </div>
                                                <div class="hr-center small" v-if="chatlogs.length >= 1">{{ chatlogsUnread[0].time }}</div>
                                                <chatlogs-component :chatlogs="chatlogsUnread"></chatlogs-component>
                                            </div>


                                            <div v-if="chatlogs.length >= 1">
                                                <div class="hr-center small"> Just Now </div>
                                                <chatlogs-component :chatlogs="chatlogs"></chatlogs-component>
                                            </div>
                                            
                                        </div>


                                        <div id="attached-progress-report" class="mt-2" >
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
                                                    <div :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}" role="progressbar" :style="{width: file.progress + '%'}">{{file.progress}}%</div>
                                                </div>

                                            </div>                                                                               
                                        </div>


                                        <div class="d-inline-block" style="width:90%">
                                            <input type="text"  
                                                v-on:keyup.13="sendMessage"
                                                autocomplete="off" 
                                                v-model="message" 
                                                placeholder="Type a message" 
                                                class="form-control form-control-sm mb-1" 
                                                aria-label="Type a message"/>
                                        </div>

                                        <div class="d-inline-block">
                                        
                                        
                                            <div id="attach-button" class="input-group-append d-inline-block">
                                                <label id="file-select-button" for="file" class="btn btn-primary btn-sm mb-0">
                                                    <i class="fas fa-paperclip"></i>
                                                </label>
                                            </div>                                            

                                            <div id="send-button" class="input-group-append d-inline-block">
                                                <label :id="'btn_'+ current_user.userid" class="btn btn-primary mr-1 btn-sm mb-0" @click.prevent="$refs.upload.active = false; sendMessage(); ">                                                    
                                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                </label>
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
                                <!--[end] card -->




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
                        'current_chatbox_userid': this.activeUserID
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

        


        <div id="create-mesage-container">
            <!--[start] Modal Create New Message -->
            <b-modal id="createNewMessage" title="Create New Message" button-size="sm">

                <div id="member-select" class="small">
                    <multiselect                     
                        v-model="selectedMember" 
                        @select="onSelect"
                        id="multiselect-members" 
                        class="small"
                        label="name" 
                        track-by="name" 
                        placeholder="Type to search" 
                        open-direction="bottom" 
                        :options="members" 
                        :multiple="false" 
                        :searchable="true" 
                        :loading="isLoadingMembers" 
                        :internal-search="false" 
                        :clear-on-select="true" 
                        :close-on-select="true" 
                        :options-limit="300" 
                        :limit="1" 
                        :limit-text="limitText" :max-height="600" 
                        :show-no-results="true" 
                        :hide-selected="false" 
                        @search-change="findMember">

                        <template slot="singleLabel" slot-scope="{ option }">                        
                            <span class="small">{{ option.name }}</span>
                        </template>


                        <template slot="option" slot-scope="props">                        
                            <span class="option__desc small"><span class="option__title">{{ props.option.id}} {{ props.option.name}}</span></span>
                        </template>

                        <span slot="noResult">Oops! No Member found.</span>

                        <span slot="noOptions">Please search for a member</span>

                    </multiselect>
                    
                </div>



                <div class="outgoing-message mt-2">
                    <b-form-textarea id="outgoingMessage" v-model="outgoingMessage" placeholder="Enter Message..." rows="5" max-rows="2"></b-form-textarea>
                </div>

                <template #modal-footer>
                     <b-button size="sm" variant="primary" @click="sendNewMessage()">
                        <i aria-hidden="true" class="fa fa-paper-plane"></i> Send
                    </b-button>
                </template>
              
                <!--{{ this.selectedMember }}-->

            </b-modal>
            <!--[end] Modal Create New Message-->
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
import Multiselect from 'vue-multiselect'

var socket = null;

export default {
    name: "chatComponent",
    components: {
        FileUpload, Multiselect
    },  
    data() {
        return {
            adminUser : null,

            users: [],              // list for all users (online and offline)
            onlineUsers: [],        // list for online users
            recentUsers: [],        //the list recently messaged user (offline)            
            current_user: null,     //all info of current uses         
            activeUserID: null,     //this will hold the current active userid  

            chatlogsHistory: [],    //Old Read chats
            chatlogsUnread: [],     //Unread Chats
            chatlogs: [],           //live chat logs

            message: "",

            typingTimer:"",             //timer for search user on outgong message
            outgoingMessage:"",     //outgoing message for user

            // Loaders
            isLoading: false,
            isLoadingMembers: false,
            historyNotifier: true,      //History Button Page notifier
            isFetching: false,          //history is fetching status

            //WINDOW STATUS FOR TAB TITLE BLINKER
            TabTitle: "",
            windowStatus: "FOCUSED",        
            interval: "",
            scrollInterval: "",
            isScrollActivated: false,
            tabTitle: "",

            //uploader
            files: [],
            page: [],

            //members
            selectedMember: [],
         
            members: []            
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
    methods: {


        prepareChatBox: function(user) 
        {
            if (typeof this.chatlogs !== 'undefined' &&  this.chatlogs.length > 0) {
                //chatlogs has an array, we will not instantiate the array again. 
            } else {
                this.chatlogs = [];                            
            }

            if (isNaN(this.page)) {
                this.page = 1;                
            }
        },
        sendNewMessage() 
        {        
            this.message = this.outgoingMessage

            let previous_user_id= this.current_user;

            this.current_user = {            
                id:  this.selectedMember.id,
                userid: this.selectedMember.id,
                username: this.selectedMember.username,
                nickname: this.selectedMember.nickname,
                type: "Member",
            }

            if (this.current_user.userid !== previous_user_id) {
                 this.openChatBox(this.current_user); 
                 //no need to send message() since we trigger after 
            } else {
                this.sendMessage();
            }
           
           
        },
        selectUserChatBox(currentUser) {
        
            this.activeUserID  = currentUser.userid;
        },
        openChatBox(currentUser) 
        {
            this.page = 1;
            this.chatlogsHistory    = [];
            this.chatlogsUnread     = [];
            this.chatlogs           = [];
            this.current_user       = currentUser;


            this.selectUserChatBox(currentUser) 

            console.log(this.activeUserID );

            this.isScrollActivated  = false;

            this.historyNotifier    = true;
            
            this.getUnreadMemberMessages(currentUser);

            clearInterval(this.scrollInterval);

            //currentUser.unreadMsg = 0;
            this.$forceUpdate();
        },

        sendMessage() {

            clearInterval(this.scrollInterval)

            //files is empty and message is empty, stop sending message
            if (this.files.length == 0 && (this.message === "" || this.message === undefined)) 
            {           
                return false;
            }

            document.getElementById("startUpload").click();

            if (this.message === "" || this.message === undefined) {

               //No message just upload

            } else {

                let id = this.current_user.id; 

                var currentTime = new Date();
                let time = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

                //get the sender from props (user)
                let recipient = {
                    'id': this.current_user.id,
                    'userid': this.current_user.userid,
                    'username':  this.current_user.username,
                };


                //get the sender from props  (admin)
                let sender = {
                    'msgCtr': 0,
                    'userid': this.userid,
                    'nickname': this.nickname,
                    'username': this.username,          
                    'message': this.message,
                    'user_image': this.user_image, //@todo: make this for customer support 
                    'type': "CHAT_SUPPORT"
                };
            
                /*   
                this.chatlogs.push({
                    sender: sender,
                    message: this.message,
                    time: time
                });
                */
                

                let userMessage = this.message;


                //scroll to end then save to table
                this.$nextTick(() => {           

                    axios.post("/api/saveCustomerSupportChat?api_token=" + this.api_token, 
                    {
                        method              : "POST",
                        sender_id           : this.userid,
                        recipient_id        : this.current_user.userid,
                        message             : userMessage,
                        is_read             : false,
                        valid               : true,
                        message_type        : "CHAT_SUPPORT",
                    }).then(response => {
                        if (response.data.success === true) {

                            //User replied (marked)
                            this.markMessagesRead(this.current_user);

                             let userIndex = this.users.findIndex(user => user.userid == this.current_user.userid)

                            if (this.users[userIndex]) {
                                this.users[userIndex].unreadMsg = 0;
                            }

                            //if (this.outgoingMessage.length >= 1) {
                                this.appendRecentUserChatList(this.onlineUsers);

                                //Create New message / Outgoing message reset
                                this.outgoingMessage = "";
                                this.message = "";
                                this.selectedMember = [];
                                this.$bvModal.hide("createNewMessage")
                            //}                            

                        } else {
                            //@todo: (error sending messages)
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
                    'userid': this.current_user.userid,
                    'nickname': this.current_user.nickname,
                    'username': this.current_user.username,          
                    'message': this.message,
                    'user_image': this.current_user.user_image, //@todo: make this for customer support 
                    'type': "CHAT_SUPPORT"
                };

                socket.emit("SEND_OWNER_MESSAGE", { id, time, broadcast_recipient, broadcast_sender });

                this.$forceUpdate();
                this.$nextTick(function()
                {          
                    //clear (always at bottom)
                    this.message = "";

                    this.scrollToEnd();
                    this.prepareButtons();
                });
            }         
            

        },  

        onSelect (option) {
            console.log (option)
            
        },

 
        /* search */
        limitText (count) {
            return `and ${count}s`
        },
        clearAll () {
            this.selectedMember = []
        },
        findMember(query) {

            clearTimeout(this.typingTimer)

            this.typingTimer = setTimeout(() => {
                
                if (query.length >= 1) {

                    this.isLoadingMembers = true

                    axios.post("/api/searchMemberName?api_token=" + this.api_token, {
                        method         : "POST",
                        query          : query
                    }).then(response => {  
                        this.members = response.data.members;
                        this.isLoadingMembers = false;
                    });
                                
                }
            }, 1000)
        },

        hideChatBox() {
            this.current_user = null;
        },
        getChatHistory(user, scrollToBottom) {        

            this.isFetching = true;

            if (this.page == 1) {
                this.chatlogsHistory = [];

            } else if (this.page > 1 ) {         

                if (this.historyNotifier == false) {
                    // chat history is reached limit
                    console.log("history page limit reached")
                    return false;                    
                }                
            }


            //user is the sender
            axios.post("/api/getAdminChatHistory?api_token=" + this.api_token, 
            {
                method              : "POST",
                sender_id           : user.userid,
                recipient_id        : this.userid,
                page                : this.page

            }).then(response => {                
               
                if (response.data.success === true) 
                {               
                    
                    this.isFetching = false;

                    let chatboxUsername = null;
                    let chatboxNickname = null;
                    let chatboxImage = null;

                    this.page = response.data.chatHistoryItems.current_page + 1;


                    //DETERMINE IF THE LAST PAGE
                    if (response.data.chatHistoryItems.last_page == response.data.chatHistoryItems.current_page) {
                        //hide button for history
                        this.historyNotifier = false;                
                    }


                    let chatHistoryItems = response.data.chatHistoryItems.data;

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
                       
                        let sender = {
                            'msgCtr': 0,
                            'userid': data.userid,
                            'nickname': chatboxNickname,
                            'username': chatboxUsername,          
                            'user_image': chatboxImage,
                            'message': data.message,                            
                            'type': data.message_type
                        };

                        this.chatlogsHistory.unshift({
                            time: data.created_at,
                            sender: sender,                                
                        });
                    });
                    
                    //let reversedChatHistory =  this.chatlogs[user.userid];       

                    this.$nextTick(() => {                      
                        this.$forceUpdate();                        
                        if (scrollToBottom == true) {                            
                            this.scrollToEnd();
                        } else {
                            this.scrollToTop();
                        }
                        //this.markMessagesRead(user);
                    });

                } else {
                    //hide button for history
                    this.historyNotifier = false;
                    this.isFetching = false;        

                   

                    this.$nextTick(() => {                        
                        this.$forceUpdate();                        
                        if (scrollToBottom == true) {
                            this.scrollToEnd();
                        } else {
                            this.scrollToTop();
                        }
                        //this.markMessagesRead(user)
                    });           
                }


                if (this.outgoingMessage.length >= 1) {
                    this.sendMessage();
                }   

            }).catch(function(error) {
                console.log("Error " + error);                
            });

        },        
        getUnreadMemberMessages(user) 
        {

            this.isLoading = true;

            axios.post("/api/getAdminUnreadChatMessages?api_token=" + this.api_token, 
            {
                method           : "POST",
                sender_id         : user.userid,
            }).then(response => {  

                if (response.data.success === true) 
                {
                    response.data.chatItems.forEach(data => {

                        let chatboxUsername = null;
                        let chatboxNickname = null;
                        let chatboxImage = null;

                        if (data.message_type == "MEMBER") {
                            chatboxUsername = user.username;
                            chatboxNickname = user.nickname   
                            chatboxImage = user.user_image;                         
                        } else {
                            chatboxUsername = this.username;
                            chatboxNickname = this.nickname
                            chatboxImage = this.user_image;
                        }

                        let sender = {
                            'msgCtr': 0,
                            'userid': data.userid,
                            'nickname': chatboxNickname,
                            'username': chatboxUsername,          
                            'user_image': chatboxImage,
                            'message': data.message, 
                            'is_read': data.is_read,                           
                            'type': data.message_type
                        };

                        this.chatlogsUnread.unshift({
                                time: data.created_at,
                                sender: sender,
                        });  
                    });
                    

                    this.$nextTick(function()
                    {
                        this.$forceUpdate();

                        this.getChatHistory(user, true);
                        
                        this.isLoading = false;

                    });

                } else {
                    this.$nextTick(function() 
                    {
                        this.$forceUpdate();
                        this.getChatHistory(user, true);
                       
                        this.isLoading = false;
                    });
                }
                
            });
        },      
        scrollToTop() {
            this.$forceUpdate();
            var container = this.$el.querySelector("#user-chatlog");
            if(container){            
                container.scrollTop = 500;
            }
        },          
        scrollToEnd() {
            setTimeout(() => {  this.executeScroll() }, 100);
        },
        executeScroll() {  
            let  container = this.$el.querySelector("#user-chatlog");
            if(container) {
                container.scrollTop = container.scrollHeight;  
                this.isScrollActivated = true;
            }
        },
        handleScroll(event) {
            if (this.isScrollActivated == true) {
                if (event.target.scrollTop == 0) {
                    this.getChatHistory(this.current_user, false)
                }                
            }
        },
        markMessagesRead(user) 
        {
            axios.post("/api/markAdminChatMessagesRead?api_token=" + this.api_token, 
            {
                method           : "POST",
                userID           : user.userid,
                message_type     : 'MEMBER'
            }).then(response => {  

                if (response.data.success === true) 
                { 
                   console.log(response.data.message);
                } 
            });    
        },  
          
        prepareButtons: function() {
           
            let fileSelectBtn = document.getElementById("file-select-button");
            let sendBtn = document.getElementById("send-button");        
            
            if (this.message == "" && this.files.length == 0) 
            {   
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
        appendRecentUserChatList(onlineUsers) {

            axios.post("/api/getRecentUserChatList?api_token=" + this.api_token, 
            {
                method              : "POST",
                
            }).then(response => {

                if (response.data.success === true)
                {
                    this.$nextTick(() => {

                        let onlineUsersList = [];
                        let offlineUserList = [];

                        let users = [];
                        
                        this.recentUsers = response.data.recentUsers;

                        //recent users filter the online list
                        Object.keys(this.recentUsers ).forEach(index => 
                        {
                            let isOnline = onlineUsers.find(onlineUser => onlineUser.userid == this.recentUsers[index].userid); 

                            //We will push all online users together with recent users with chat messages
                            if (isOnline) {
                                this.recentUsers[index].status = 'online'

                                //Online User with chat messages
                                users.push(this.recentUsers[index]);

                            } else {

                                //Offline User with chat messages
                                users.push(this.recentUsers[index])
                            }

                        });


                        //Online Users without recent chats
                        onlineUsers.forEach((onlineUser) => 
                        {
                            let inRecentUsers =  onlineUsersList.find(userList => userList.userid == onlineUser.userid);

                            if (inRecentUsers) {
                                //user is found in recent user list (do not add)
                            } else {
                                //user is online but no messages
                                onlineUser['unreadMsg'] =  0;                                
                                onlineUser['totalMsg'] =  0;
                                onlineUser['recentMsg'] =  "";
                                //onlineUsersList.push(onlineUser);
                            }
                        });


                        this.users = users;


                        if (this.current_user !== null) {
                            //this.openChatBox(this.current_user);
                        }
                            
                    });
                   

                } else {
                
                    //show only online users (no recent users)
                    this.users = onlineUsers;

                    if (this.current_user !== null) {
                        this.openChatBox(this.current_user);
                    }
                }

            }).catch(function(error) {
                console.log("Error " + error);                
            }); 
        },
        updateUserList: function(users) 
        {
            //filter chat support, we will not show on the list if the users is a chat support
            let fusers = users.filter(user => user.type !== "CHAT_SUPPORT");
            
            //filter duplicates
            this.onlineUsers = this.filterUnique(fusers);   

            //Append Users to chat list
            this.appendRecentUserChatList(this.onlineUsers) 

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
            this.markMessagesRead(this.current_user);            
        },
        enterAdminChat: function() {
         
            this.adminUser = {            
                userid: this.userid,
                username: this.username,
                nickname: "Customer Support",
                status: "online",
                type: "CHAT_SUPPORT",
            }
            socket.emit('REGISTER', this.adminUser);

            let adminChat = document.getElementById("AdminChat");        
            adminChat.style.display = 'block';

            let enterChat = document.getElementById("enterChat");
            enterChat.style.display = 'none';            
        },       


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
                    
                        this.chatlogs.push({
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


                        //get the sender from props (user)
                        let broadcast_recipient = {
                            'id': this.id,
                            'userid': this.userid,
                            'username':  this.username,
                        };


                        //get the sender from props  (admin)
                        let broadcast_sender = {
                            'msgCtr': 0,
                            'userid': this.current_user.userid,
                            'nickname': this.current_user.nickname,
                            'username': this.current_user.username,          
                            'message': this.message,
                            'user_image': this.current_user.user_image, //@todo: make this for customer support 
                            'type': "CHAT_SUPPORT"
                        };

                        socket.emit("SEND_OWNER_MESSAGE", { id, time, broadcast_recipient, broadcast_sender }); 

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
            //this.markSeenMessages();
        });


        window.addEventListener("blur", (e) => {
            this.windowStatus = "BLURRED";
        });



        //update the list
        socket.on('update_user_list', users => {
            this.updateUserList(users); 
        });

	    socket.on("OWNER_MESSAGE", data => 
        {
            if (data.broadcast_recipient.userid == this.userid) 
            {
                this.prepareChatBox(data.broadcast_recipient);

                let sender = {
                    'msgCtr': 1,
                    'userid': data.broadcast_sender.userid,
                    'username': data.broadcast_sender.username, 
                    'nickname':  "Customer Support",
                    'message': data.broadcast_sender.message,
                    'user_image': data.broadcast_sender.user_image,   
                    'type': data.broadcast_sender.type,          
                };

               
                if (this.current_user !== null && this.current_user.userid == data.broadcast_sender.userid) 
                {

                    console.log("owner message");

                    this.chatlogs.push({
                            time: data.time,
                            sender: sender            
                    });

                    this.$forceUpdate();
                    
                    this.$nextTick(function()
                    {
                       

                        if (this.current_user.userid == data.broadcast_sender.userid) {
                            console.log("scroll to end 1")
                
                            this.scrollToEnd();
                        }                        
                    });

                }

                
            } else {

                console.log("this is from other users");

                //reset unread message to 0

                if (data.broadcast_sender.type !== 'MEMBER') {

                    let userIndex = this.users.findIndex(user => user.userid == data.broadcast_sender.userid)

                    if (userIndex) {
                        this.users[userIndex].unreadMsg  = 0;  
                    }
                }                

                //log and simultainously show to other admin               
                if (data.broadcast_sender.userid == this.activeUserID) {

                    this.prepareChatBox(data.broadcast_sender);                 

                    let sender = {
                        'msgCtr': 1,
                        'userid': data.broadcast_sender.userid,
                        'username': data.broadcast_sender.username, 
                        'nickname':  "Customer Support",
                        'message': data.broadcast_sender.message,
                        'user_image': data.broadcast_sender.user_image,   
                        'type': data.broadcast_sender.type,          
                    };

            
                    this.chatlogs.push({
                        time: data.time,
                        sender: sender            
                    });

                    this.$forceUpdate();
                    this.$nextTick(function()
                    {

                        if (this.current_user.userid == data.broadcast_sender.userid) {

                            console.log("scroll to end 2")
                            this.scrollToEnd();
                        }                        
                    });

                }
            }	
	    });

        socket.on('PRIVATE_MESSAGE', data => 
        {

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
            


            if (this.current_user !== null && this.current_user.userid == data.sender.userid) {                

                //console.log("PRIVATE_MESSAGE");
                this.chatlogs.push({
                    time: data.time,
                    sender: sender            
                });
                this.$forceUpdate();
            }
 


            this.$nextTick(function() 
            {
                console.log("private message");

                if (this.current_user.userid == data.sender.userid) {
                    this.scrollToEnd();
                }
                

                //DETECTION FOR OPENED CHATBOX,
                //AND ZERO OUT THE CHAT MESSAGE COUNT IN 1 AND A HALF SECOND SINCE IT WILL BE CONSIDERED READ
                //THIS WILL BE DISCREGARDED IF WINDOWSTATUS IS BLURRED
                if (this.windowStatus == "FOCUSED") {
                    this.stopBlink();
                   // this.markSeenMessages();                
                }
                
                if (this.windowStatus == "BLURRED") {

                    if (data.sender.type !== "CHAT_SUPPORT") {
                        this.blink();
                    }                    
                    console.log("window status ", this.windowStatus);                
                }

            });             

            this.$nextTick(function()
            {
                //let chatMessage = this.users.find(user => user.userid == data.sender.userid);

                var length = 30;
                let string = data.sender.message                
                var trimmedString = string.length > length ? string.substring(0, length - 3) + "..." : string;
               

                let userIndex = this.users.findIndex(user => user.userid == data.sender.userid)

                if (this.users[userIndex]) 
                {
                    //USER SENDING A MESSAGE IS FOUND ON THE LIST OF USERS ONLINE
                    if (data.sender.type == "CHAT_SUPPORT") {

                        //chat support is sending a message to a member
                        //we don't need to increase the unread message counter since this counter is for unread message from member

                        //this.markSeenMessages() //marked as read relegated to when a chat support reply to messages only

                    } else {

                        //Member message is recieved, increase to message counter by 1
                        //console.log("Private chat message + indexed for new user")

                      
                        if (isNaN(this.users[userIndex].unreadMsg)) {
                            this.users[userIndex].unreadMsg = 1;
                        } else {
                            this.users[userIndex].unreadMsg++;
                        }

                        if (isNaN(this.users[userIndex].totalMsg)) {
                            this.users[userIndex].totalMsg = 1;
                        } else {
                            this.users[userIndex].totalMsg++;
                        }      

                        this.users[userIndex].recentMsg = trimmedString; 
                        this.users.unshift(this.users.splice(userIndex, 1)[0]);
                        this.$forceUpdate();
                        
                    }

                } else {

                    //USER WHO SENT A MESSAGE IS NOT IN THE LIST OF RECENT USERS, 
                    //WE WILL CREATE A NEW USER ON THE LIST

                    //console.log("private message "+ data.sender.type + " ", data)


                    if (data.sender.type == "CHAT_SUPPORT") {

                        //chat support is sending a message to a user, 
                        //unread message reset to zero
                        let userIndex = this.users.findIndex(user => user.userid == data.recipient.userid)

                        //reset other admin unread message count
                        if (this.users[userIndex]) {
                            this.users[userIndex].unreadMsg = 0;

                            //this.markSeenMessages() //marked as read relegated to when a chat support reply to messages only
                        }                      

                    } else {

                         //the user is online but not on the list since he has no recent messages, 
                        //append the online uses to recent user list and initiazlie unread messages and total messages

                        data.sender.unreadMsg    = 1;
                        data.sender.totalMsg     = 1;
                        data.sender.recentMsg    = trimmedString; 
                        data.sender.status      = "online"; 
                        this.users.unshift(data.sender);
             
                    }
                
                }
            }); 

 
            
            //console.log("private MSG", data);
            
            if (data.recipient.userid == this.userid || data.recipient.type == "CHAT_SUPPORT") {                
                //play audio
                let audio = new Audio("/mp3/message-sent.mp3");               
                audio.play();
            }             

        });


       

          
    },
};


Vue.component("chatlogs-component", {
  props: ['chatlogs'],
  data: function () {
    return {};
  },
  methods: {
    formatMessage(message) 
    {
        return this.urlify(message);
    },
    urlify(text) {
        var exp = /(\b((https?|ftp|file):\/\/|(www))[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]*)/ig; 
        return text.replace(exp,"<a href='$1' target='_blank' >$1</a>");
    },
  },
  template:
    `<div>
        <div class="container" v-for="(chatlog, chatlogIndex) in chatlogs" :key="'chatlog_'+chatlogIndex">

            <div class="row" v-if="chatlog.sender.type == 'CHAT_SUPPORT'">
                <div class="col-md-3">&nbsp;</div>
                <div class="col-md-9">
                
                    <div v-if="chatlogIndex == 0 || chatlogs[chatlogIndex - 1].sender.type !== 'CHAT_SUPPORT'">  
                        <chatsupport-info-component 
                        :userid="chatlog.sender.userid"
                        :image="chatlog.sender.user_image"
                        :nickname="chatlog.sender.nickname" 
                        :time="chatlog.time">
                        </chatsupport-info-component>
                    </div>

                    <div style="float:right">
                    <div class="chatsupport-message" v-html="formatMessage(chatlog.sender.message)"></div>
                    </div>
                </div>
            </div>


            <div class="row" v-if="chatlog.sender.type == 'MEMBER'" >
                <div class="col-md-9">
                    <div v-if="chatlogIndex == 0 || chatlogs[chatlogIndex - 1].sender.type !== 'MEMBER' ">       
                                
                        <member-info-component 
                            :userid="chatlog.sender.userid"
                            :image="chatlog.sender.user_image"
                            :nickname="chatlog.sender.nickname" 
                            :time="chatlog.time">
                        </member-info-component>
                    </div>
                    <div class="member-message" v-html="formatMessage(chatlog.sender.message)"></div>
                </div>
                <div class="col-md-3">&nbsp;</div>
            </div>
        </div>
    </div>`
    ,
});




Vue.component("current-user-component", {
  props: ['user'],
  data: function () {
    return {};
  },
  methods: {
    closeChat() {
        //this.$parent.hideChatBox()
        this.$root.$refs["adminChatComponent"].hideChatBox()
    }
  },
  template:
    `<div class="card-header">
        <div class="float-left">
            <div class="h5 mb-0">{{ user.nickname}}</div>
            <div class="small">{{  user.username }}</div>
            <div class="small">ID Number: {{ user.userid }}</div>
        </div>

        <div class="float-right">          
            <button v-on:click="closeChat()" style="border:none">X</button>
        </div>
    </div>`
    ,    
});





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


<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style>
.img_preview {
    width: 100px;
}
.custom-pdf {
    font-size: 100px;
}
</style>


<style >
.floating-history-fetcher {
    position: absolute;
    top: 65px;
    display: inline-block;    
    text-align: center;
    width: 100%;
    z-index: 99;
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

.user-info { 
    padding: 8px 3px 8px; border-bottom:1px solid rgb(236, 236, 236);
    cursor: pointer;
}

.user-info:hover { 
    background-color: #e1f6fd;
}

.user-info.active { 
    background-color: #b8ebfc;
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

#user-chatlog, .loading-container {
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


#floating-history-btn {
    font-size: 11px;
}
 

.btn-xs {
    font-size: 11px;
    padding: 2px 5px 2px;
}

.hr-center {
    display: flex;
    flex-basis: 100%;
    align-items: center;
    color: rgba(0, 0, 0, 0.35);
    margin: 8px 0px;
}
.hr-center:before,
.hr-center:after {
    content: "";
    flex-grow: 1;
    background: rgba(0, 0, 0, 0.35);
    height: 1px;
    font-size: 0px;
    line-height: 0px;
    margin: 0px 8px;
}

#attached-progress-report {
    width: 100%;
}



#multiselect-members { 
    font-size: 14px;
}

.multiselect__option {
    font-size: 14px;
}


.multiselect__placeholder {
    font-size: 14px;
}


 
</style>