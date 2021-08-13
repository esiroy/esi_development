<template>

  <div id="AdminChat" class="adminChat">
    <div class="container  bg-light">
        <div class="row">

            <div class="col-md-4">
              <div class="card memberlist-panel mt-2">
                <div class="card-header">
                  Users
                </div>
                <div class="card-body">
                    <div :id="'member-'+user.userid" class="member-information-container" v-show="user.userid !== userid" v-for="user in this.users" :key="'user_'+ user.userid"  v-on:click="openChatBox(user)" >
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
                              <div class="small">{{ chatbox.username }}</div>
                            </div>

                            <div style="float:right">          
                              <button v-on:click="deleteChatbox(index)" style="border:none">X</button>
                            </div>
                          </div>

                          <div class="card-body">
                              <form :name="chatbox.userid" onsubmit="return false;">

                              <div id="user-chatlog" class="user-chatlog">
                                  <div class="container" v-for="(chatlog, chatlogIndex) in chatlogs[chatbox.userid]" :key="'my_chatlog_'+chatlogIndex">                                      

                                      <div class="row" v-if="chatlog.sender.type == 'CHAT_SUPPORT'">
                                        <div class="col-md-3">&nbsp;</div>
                                        <div class="col-md-9">
                                          <div v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'CHAT_SUPPORT'">                                                                                      
                                            <chatsupport-info-component 
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
                                
                                        <input id="message" v-on:keyup.13="sendMessage(chatbox, index)"  type="text" class="form-control" 
                                            v-model="message[index]" placeholder="Type a message" aria-label="Type a message" >
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
            extensions="jpeg,jpg,gif,pdf,mp3,wav,png,webp,mpeg"
            accept="image/png, application/pdf, image/gif, audio/mpeg, audio/mpeg3, audio/x-mpeg-3, video/mpeg, image/jpeg, image/webp"
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
</template>

<script>
import io from "socket.io-client";
import FileUpload from 'vue-upload-component'

//const socket = io.connect("http://localhost:30001");
const socket = io.connect("https://chatserver.mytutor-jpn.info:30001");

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

        //uploader
        files: [],
    };
    },
    props: {
        userid: String,
        username: String,
        nickname: String,
        api_token: String,    
        csrf_token: String,        
    },
    methods: 
    {
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
                            'user_image': "http://localhost:8000/storage/user_images/noimage.jpg", //@todo: make this for customer support 
                            'type': "CHAT_SUPPORT"
                        };
                    
                        this.chatlogs[newFile.response.recipient_id].push({
                            sender: sender,
                            message: newFile.response.image,
                            time: time
                        });

                        this.$forceUpdate(); 
                        this.scrollToEnd();

                        //get the sender from props (user)                        
                        let id = newFile.response.id

                        let recipient = {
                            'id': newFile.response.id,
                            'userid': newFile.response.recipient_id,
                            'username':  'emailroy2002@yahoo.com',
                        };

                        socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender });   
                    }
                }
            }

            if (this.$refs.upload.uploaded) {
               // console.log("all queue uploaded");
                this.files = [];
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
                if (!/\.(jpeg|jpe|jpg|gif|png|webp|pdf|mp3|mp4|doc|docx)$/i.test(newFile.name)) {
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
        scrollToEnd: function() 
        {
            this.$forceUpdate();      
            this.$nextTick(function()
            {      
                var container = this.$el.querySelector("#user-chatlog");
                if(container){
                    container.scrollTop = container.scrollHeight;
                    //console.log("scroll to end")          
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
        getChatHistory: function(user) 
        {        
            //user is the sender
            axios.post("/api/getChathistory?api_token=" + this.api_token, 
            {
                method              : "POST",
                sender_id           : user.userid,
                recipient_id        : this.userid,                        
            }).then(response => {

                if (response.data.success === true) 
                {
                    //{ LOOP HERE FOR CHAT HISTORY }
                    response.data.chatHistoryItems.forEach(data => {
                        //console.log(data);                
                        let sender = {
                            'msgCtr': 0,
                            'userid': data.userid,
                            'nickname': this.nickname,
                            'username': this.username,          
                            'message': data.message,
                            'user_image': "http://localhost:8000/storage/user_images/noimage.jpg", //@todo: make this for customer support 
                            'type': data.message_type
                        };

                        this.chatlogs[user.userid].push({
                                sender: sender,
                                //message: "?????????????",
                                time: data.created_at,
                        });
                    }); 
                
                

                    this.$nextTick(function()
                    {  
                        this.$forceUpdate();
                        this.scrollToEnd();
                    });

                } else {
                    //@todo: HIGHLIGHT error
                }
            
            }).catch(function(error) {
                console.log("Error " + error);                
            });

        },
        openChatBox: function(user) 
        {        
            //@note: user is the sender
            this.current_chatbox_userid = user.userid;        

            //this.chatboxes.push(user); /* {this will open new window} */
            this.chatboxes = [user];
            this.prepareChatBox(user);

            
                

            //reset bg color      
            var elements = document.getElementsByClassName("member-information-container");
            for(var i = 0; i < elements.length; i++){
                elements[i].style.removeProperty("background");
            }

            //change color for selected item
            document.getElementById("member-"+user.userid).style.background = "#C7EDFB";    

            this.getChatHistory(user);

            this.$forceUpdate();

            this.$nextTick(function()
            {
                this.scrollToEnd();

                this.prepareButtons(); 
            });

            this.chatCount[user.userid] = 0;

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
                    'user_image': "http://localhost:8000/storage/user_images/noimage.jpg", //@todo: make this for customer support 
                    'type': "CHAT_SUPPORT"
                };
            
                this.chatlogs[chatbox.userid].push({
                    sender: sender,
                    message: this.message[index],
                    time: time
                });
            
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
            this.users = users;
           // console.log(this.users);
            this.$forceUpdate();
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

    //(force) register as user
    let user = {
      //userid: this.$props.userid ,
      userid: this.userid,
      username: this.username,
      nickname: "Customer Support",
      type: "CHAT_SUPPORT",
    }
    socket.emit('REGISTER', user);

    //update the list
    socket.on('update_user_list', users => {
      this.updateUserList(users); 
    });


    socket.on('PRIVATE_MESSAGE', data => {
       //console.log("private message", data)
      //console.log(data.sender.message);

      
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

        this.$nextTick(function()
        {
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

        //detect
        let isOpenChatbox = document.getElementById("chatbox-"+data.sender.userid);    
        if (isOpenChatbox) {
            setTimeout(function () {
                this.chatCount[data.sender.userid] = 0;
                this.$forceUpdate();
            }.bind(this), 1500)            
        }

    });
  },
};


Vue.component("member-info-component", {
  props: ['image', 'nickname', 'time'],
  data: function () {
    return {
      count: 0,
    };
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
    return {
      count: 0,
    };
  },
  template:
    `<div style='text-align:right'>      
      <span class="pl-3 small">{{ nickname }}, {{ time }}</span>
      
    </div>`
    ,    
});


Vue.component("button-counter", {
  data: function () {
    return {
      count: 0,
    };
  },
  template:
    '<button v-on:click="count++">You clicked me {{ count }} times.</button>',
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