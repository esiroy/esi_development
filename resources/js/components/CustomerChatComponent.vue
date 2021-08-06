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

      <div class="chatbox" v-for="(chatbox, index) in this.chatboxes" :key="chatbox.id">
        <form :name="chatbox.userid" onsubmit="return false;">
          <div id="chatlogs" class="user-chatlog">
              <div v-for="chatlog in chatlogs[chatbox.userid]" :key="chatlog.id">
                <strong>{{ chatlog.sender.username }}:</strong>
                <span>{{ chatlog.sender.message }}</span>
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
    username: String
  },
  methods: {
    openChatBox: function(user) 
    {

      console.log(user);

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
     
      if (this.message[index] !== "") 
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

        this.$nextTick(function()
        {
          this.scrollToEnd();
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
  
    let admin = {
      userid: 1,
      username: "admin",
    }
    this.openChatBox(admin);


    //register as user
    let user = {
      userid: this.$props.userid ,
      username: this.username,
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
    min-height: 225px;
    max-height: 225px;
    margin-bottom: 3px;
    overflow-x: hidden;
    overflow-y: scroll;
  }
</style>