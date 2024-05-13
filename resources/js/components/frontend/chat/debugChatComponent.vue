<template><div>test</div></template>
<script>
import Vue from 'vue';
import VueSocketIO from 'vue-socket.io';

Vue.use(new VueSocketIO({
  debug: false,
  connection: 'https://chatserver.mytutor-jpn.info:30001/',
}));

export default {
  data() {
    return {
      // Your component's data
    };
  },
  sockets:{
    connect: function(){
      console.log('socket connected')
    },
    update_user_list: function($users) {
      console.log($users);
    },
    MESSAGE: function(val){
      console.log('this method was fired by the socket server. eg: io.emit("customEmit", data)')
    }
  },  
  methods: {
    sendMessage() {      
      this.$socket.emit('SEND_MESSAGE', "test");      
    },
    registerUser() {
      // Emit 'REGISTER' event to the server
      this.$socket.emit('REGISTER', {
        userid: this.$props.userid ,
        username: this.username,
        nickname: this.nickname,
        user_image: this.user_image,    
        status: 'online',     
        type: "MEMBER",   
      });
    },
    // Other methods if needed
  },
  mounted() {
    // Call the registerUser method when the component is mounted
    this.registerUser();
    this.sendMessage();
   

  },
};
</script>