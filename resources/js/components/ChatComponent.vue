<template>

  <div id="AdminChat" class="adminChat">

    <div class="memberlist-panel">
        <div class="list-group">
          <div v-for="user in this.users" :key="user.id">
            <div v-if="user.userid !== userid" v-on:click="openChatBox(user)" class="member">              
               <a  class="list-group-item list-group-item-action">{{ user.username }}</a> 
            </div>
          </div>
        </div>
    </div>



    <div class="chatboxes">
      <div class="chatbox" v-for="(chatbox, index) in this.chatboxes" :key="chatbox.id">

        <div style="text-align:right">
          <button v-on:click="deleteChatbox(index)" style="border:none">X</button>
        </div>


        <form :name="chatbox.userid" onsubmit="return false;">
          <div class="user-chatlog">
              <div v-for="chatlog in chatlogs[chatbox.userid]" :key="chatlog.id">
                <strong>{{ chatlog.sender.username }}: : </strong>
                {{ chatlog.sender.message }}
              </div>

          </div>

          <div>
            <input type="text" v-model="message[index]" class="user-input-message"/>
          </div>
          <button :id="'btn_'+chatbox.userid" v-on:click="sendMessage(chatbox, index)">Send Message</button>
        </form>
      </div>
    </div>


  </div>
</template>

<script>
import io from "socket.io-client";
//const socket = io.connect("http://localhost:30001");
const socket = io.connect("http://chatserver.mytutor-jpn.info:30001");

export default {
  name: "chat-component",
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
    username: String
  },
  methods: {
    deleteChatbox: function(index) {
      this.chatboxes.splice(index, 1)
    },
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
          //inititte chat log for when send message logs it does not empty out
          this.chatlogs[user.userid] = [];
      }
    },
    sendMessage: function(chatbox, index) 
    {
     
      //recipient
      let id = chatbox.id;     
  
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
          'message': this.message[index]                     
      };

      this.chatlogs[chatbox.userid].push({
            sender: sender,
            message: this.message[index]
        });

      socket.emit("SEND_USER_MESSAGE", { id, recipient, sender });   

      this.message[index] = "";
      this.$forceUpdate();
    },
    updateUserList: function(users) 
    {
      this.users = users;
      this.$forceUpdate();
    }  
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {

    //register as user
    let user = {
      userid: this.$props.userid ,
      username: this.username,
      type: "support"
    }
    socket.emit('REGISTER', user);


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
          'message': data.sender.message
      };

      this.chatlogs[data.sender.userid].push({
            sender: sender,
            message: data.sender.message        
      });      

      this.$forceUpdate();
    });


  },
};

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

<style scoped>

  .chatboxes {
    position: fixed;
    bottom: 0px;    
    right: 300px;
    z-index: 9999;
  }

  .chatbox 
  {    
    bottom:0;    
    z-index: 999;    
    float: right;
    padding: 5px;
    margin-right:12px;
    background-color:#fff;
  }

  .user-chatlog {
    height: 125px;
    width: 225px;
    background-color:#fff;
    bordeR:1px solid #ccc;
  }


  .memberlist-panel {
    position: fixed;
    border: 1px solid;
    right: 80px;
    bottom:0;
  
  }

  .member {
    border-bottom: 1px solid #333;
  }  


  .user-input-message {
    width: 225px;
  } 

</style>