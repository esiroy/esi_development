<template>

  <div id="AdminChat" class="adminChat">

    <!--
    <div class="memberlist-panel">
        <div class="list-group">

          <div v-for="user in this.users" :key="user.id">
            <div v-if="user.userid !== userid" v-on:click="openChatBox(user)" class="member">              
               <a  class="list-group-item list-group-item-action">{{ user.username }}</a> 
            </div>
          </div>
        </div>
    </div>
    -->

    <div class="chatboxes">

      <div class="chatbox" v-for="(chatbox, index) in this.chatboxes" :key="'chatbox_' + chatbox.id">
        <form :name="chatbox.userid" onsubmit="return false;">
        
          <div id="chatlogs" class="user-chatlog">
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

                  <div class="col-md-4">&nbsp;</div>
                  <div class="col-md-7">

                    <div class="member-info" v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'MEMBER'">    
                      <span class="small">{{ chatlog.sender.nickname }}, {{ chatlog.time  }}</span>                       
                    </div>

                    <div class="member-message-container">
                      <div class="member-message" v-html="chatlog.sender.message"></div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="member-info" v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'MEMBER'">
                      <img :src="chatlog.sender.user_image" class="img-fluid member-image" width="50"  style='float:right; border-radius:50%; margin-left:30px'/> 
                    </div>
                  </div>

                </div>
              </div>
          </div>
          

          <div class="input-group mb-3">
            <input type="text" class="form-control" v-model="message[index]"  
              placeholder="Message" aria-label="Message" aria-describedby="basic-addon2"
              v-on:keyup.13="sendMessage(chatbox, index)"
            >
            <div class="input-group-append">
              <button type="button" :id="'btn_'+chatbox.userid" v-on:click="sendMessage(chatbox, index)" class="btn btn-outline-secondary">
                Send Message
              </button>
            </div>
          </div>

        </form>
      </div>

    </div>


  </div>
</template>

<script>
import io from "socket.io-client";
//const socket = io.connect("http://localhost:30001");
const socket = io.connect("https://chatserver.mytutor-jpn.info:30001");

export default {
  name: "customer-chat-component",
  data() {
    return {                 
      message: [],
      users: [],

      //chat boxes
      chatboxes: [],
      chatlogs: [],
      message_count: 0,
      test: 0,
    };
  },
  props: {
    userid: String,
    username: String,
    nickname: String,
    user_image: String,
    api_token: String,
  },
  methods: 
  {
    openChatBox: function(user) 
    {       

        let found = false;
        //add new to array if not present
        for (var i in this.chatboxes) {
            if (user.username == this.chatboxes[i].username) {
                found = true;
            }
        }

        if (found == false) {
            this.chatboxes.push(user);
            //instantiate chat log for when send message logs it does not empty out
            this.chatlogs[user.userid] = [];
        }
    },
    sendMessage: function(chatbox, index) 
    {
     
        if (this.message[index] == "") {
            return false;
        }

        var currentTime = new Date();    

        //recipient
        let id = chatbox.id;     
        let time = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

        //get the sender from props (user)
        let recipient = {
            'id': chatbox.id,
            'userid': chatbox.userid,
            'username':  chatbox.username,
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

        this.chatlogs[chatbox.userid].push({
            sender: sender,
            message: this.message[index],
            time: time,
        });

        socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender }); 

        let userMessage = this.message[index]

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
      
      var container = this.$el.querySelector("#chatlogs");
      container.scrollTop = container.scrollHeight;
      console.log("scroll to end")
      
    }    
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
  
    //automatically open for admin when popup is created.
    let admin = {
      userid: 1,
      username: "admin",
    }
    this.openChatBox(admin);


    //register as user
    let user = {
      userid: this.$props.userid ,
      username: this.username,
      nickname: this.nickname,
      user_image: this.user_image,      
    }
    console.log(user);
    socket.emit('REGISTER', user);
    //dummy

    
    /*
    for (let i = 0; i < 100; i++) {
      //register as user
      let user = {
        userid: "user_" + i,
        nickname: "dummy " + i,
        username: "dummy only - username_" + i,
        type: "support"
      }
      socket.emit('REGISTER', user);      
    }
    */


    

    //update the list
    socket.on('update_user_list', users => {
      this.updateUserList(users); 
    });


    socket.on('PRIVATE_MESSAGE', data => {
      //console.log("private message", data)
      //console.log(data.sender.message);

      
      this.openChatBox(data.sender)

      let sender = {
          'userid': data.sender.userid,
          'username': data.sender.username,   
          'nickname': data.sender.nickname,
          'message': data.sender.message,
          'type': data.sender.type,
      };

      this.chatlogs[data.sender.userid].push({
            time: data.time,
            sender: sender,
            //message: data.sender.message        
      });      
     
      this.$forceUpdate();  
      
      this.$nextTick(function()
      {
        this.scrollToEnd();
      }); 
    });


  },
};
</script>

<style scoped>
  .user-chatlog {
    border: 1px solid #ececec;
    padding: 5px;
    min-height: 360px;
    max-height: 360px;
    margin-bottom: 3px;
    overflow-x: hidden;
    overflow-y: scroll;
  }

  .member-info {
    text-align: right;
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