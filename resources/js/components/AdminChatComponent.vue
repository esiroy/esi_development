<template>

  <div id="AdminChat" class="adminChat">
    <div class="container  bg-light">
        <div class="row">

            <div class="col-md-4 left-panel">

                <div class="memberlist-panel">
                    
                      <div class="member-information-container" v-show="user.userid !== userid" v-for="user in this.users" :key="'user_'+ user.userid">
                        <div class="member" v-if="user.userid !== userid" v-on:click="openChatBox(user)" >
                          <div class="profile-photo">
                            <img :src="user.user_image"  class="img-fluid">
                          </div>
                          <div class="profile-user-info">
                            <a class="">{{ user.username }}</a> 
                          </div>
                        </div>                    
                    </div>
                </div>        
            </div>

            <div class="col-md-8">

                <div class="chatboxes mt-2">

                    <div class="chatbox" v-for="(chatbox, index) in this.chatboxes" :key="index">

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

                              <div class="user-chatlog">
                                  <div v-for="(chatlog, chatlogIndex) in chatlogs[chatbox.userid]" :key="'my_chatlog_'+chatlogIndex">
                                      <img :src="chatlog.sender.user_image" width="25">
                                      <strong>
                                        {{ chatlog.sender.username }} 
                                        {{ chatlog.time }}
                                      </strong>

                                      <div class="user-message">
                                        {{ chatlog.sender.message }}
                                      </div>
                                  </div>
                              </div>

                              <!--
                              <div>
                                  <input type="text" v-model="message[index]" class="user-input-message form-control"/>
                              </div>

                              <button :id="'btn_'+chatbox.userid" v-on:click="sendMessage(chatbox, index)">Send Message</button>
                              -->


                                <div class="input-group mt-3">
                                  <input type="text" class="form-control" v-model="message[index]"  
                                    placeholder="Type a message" aria-label="Message" aria-describedby="basic-addon2"
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
                </div>                            
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import io from "socket.io-client";
const socket = io.connect("http://localhost:30001");
//const socket = io.connect("https://chatserver.mytutor-jpn.info:30001");

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

      if (found == false) 
      {
          //this.chatboxes.push(user);

          this.chatboxes = [user];

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
          'nickname': this.nickname,
          'username': this.username,          
          'message': this.message[index],
          'user_image': "http://localhost:8000/storage/user_images/noimage.jpg", //@todo: make this for customer support                
      };

      //log time
      var time = new Date();
      
      this.chatlogs[chatbox.userid].push({
            sender: sender,
            message: this.message[index],
            time: time.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })
      });

      socket.emit("SEND_USER_MESSAGE", { id, recipient, sender });   

      this.message[index] = "";
      this.$forceUpdate();
    },
    updateUserList: function(users) 
    {
      this.users = users;

      console.log(this.users);

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
      console.log("private message", data)
      //console.log(data.sender.message);

      this.openChatBox(data.sender)

      let sender = {
          'userid': data.sender.userid,
          'username': data.sender.username,          
          'message': data.sender.message,
          'user_image': data.sender.user_image,          
      };

      this.chatlogs[data.sender.userid].push({
            time: data.time,
            sender: sender            
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

.left-panel {
  padding-right: 0px;
  border-right:1px solid #ccc
}

.memberlist-panel {
  margin-top: 5px;
  border-top:1px solid #ccc;
  border-bottom:1px solid #ccc;
  height: 580px;  
  overflow: hidden;
}

.memberlist-panel:hover {
  margin-top: 5px;
  border:1px solid #ccc;
  height: 580px;
  overflow-y: scroll;
  
}

.member {
  border-bottom:1px solid #ccc
}


/*chatbox styles */
.chatbox-info-heading {
   background-color: #fff;
   border-bottom: 1px solid #ccc
}

.user-chatlog {
  padding:5px;
  height: 420px;
}


.profile-photo {
  width: 50px;
  display: inline-block;
}

.profile-user-info {
  display: inline-block;
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