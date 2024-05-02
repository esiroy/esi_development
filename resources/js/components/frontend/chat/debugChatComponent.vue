<script>
import Vue from 'vue';
import VueSocketIO from 'vue-socket.io';

Vue.use(new VueSocketIO({
  debug: true,
  connection: 'https://chatserver.mytutor-jpn.info:30001/',
}));

export default {
  data() {
    return {
      // Your component's data
    };
  },
  methods: {
    registerUser() {
      // Emit 'REGISTER' event to the server
      this.$socket.emit('REGISTER', {
        userid: 2307,
        username: "debugroy",
        nickname: "debugroy",
        user_image: "",
        status: "online",
        type: "MEMBER"
      });
    },
    // Other methods if needed
  },
  mounted() {
    // Call the registerUser method when the component is mounted
    this.registerUser();
  },
};
</script>