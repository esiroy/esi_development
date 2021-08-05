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
        <form :name="chatbox.userid" onsubmit="return false;">
          <div class="user-chatlog">

            {{ chatlog[chatbox.userid] }}

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
const socket = io.connect("http://localhost:30001");
//const socket = io.connect("http://chatserver.mytutor-jpn.info:30001");

export default {
  name: "chat-component",
  data() {
    return {                 
      message: [],
      users: [],

      //chat boxes
      chatboxes: [],
      chatlog: [],
      message_count: 0,
      test: 0,
    };
  },
  props: {
    userid: String,
    username: String
  },
  methods: {
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
          this.chatlog[user.userid] = [];
      }
    },
    sendMessage: function(chatbox, index) 
    {
      console.log(index)

      let userid = chatbox.userid;
      let username = chatbox.username;
      let message = this.message[index];

      let msgcount = this.chatlog[chatbox.userid].length;

     this.chatlog[chatbox.userid].push(message);

      

      socket.emit("SEND_USER_MESSAGE", { userid, username, message });   

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
    let user = {
      userid: this.$props.userid ,
      username: this.username,
    }

    socket.emit('REGISTER', user);

    socket.on('update_user_list', users => {
      this.updateUserList(users); 
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