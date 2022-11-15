<template>

    <div class="chat-container mb-2">

        <h6 class="font-weight-bold text-center text-white m-0 p-2" :style="'background-color:#ccc'" >Chat Messages</h6>

        <b-card style="max-width: 20rem;" class="mb-2 p-0">       
            <b-card-text id="chatlogs" class="chatlogs text-dark" style="height: 280px; overflow: auto;">
                <div :class="'chatlog-'+chatlogIndex" v-for="(chatlog, chatlogIndex) in chatlogs" :key="'chatlogs_'+ chatlogIndex">
                    <span v-html="chatlog.nickname"></span> : <span v-html="chatlog.message"></span>
                </div>           
            </b-card-text>

        </b-card>
    

        <div class="chat_message mt-1 row">
            <div class="col-10 mr-0 pr-0">
                <input type="text" v-model="privateMessage" @keyup="isEnter($event)" class="form-control form-control-sm d-inline-block" placeholder="Enter a message" >
            </div>

            <div class="col-2 ml-0 pl-1">
                <button type="button"  @click="sendPrivateMessage(privateMessage)" class="btn btn-sm btn-primary d-inline-block">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>                    

</template>                 


<script>
    import io from "socket.io-client";

    export default {
        name: "lessonSliderChatroomComponent",
        props: {
            csrf_token: String,		
            api_token: String,
            channelid: {            
                type: [String, Number],
                required: true
            },
            canvas_server: {
                type: [String],
                required: true        
            },           
            user_info: {
                type: [Object, String],
                required: true
            },        
            member_info: {
                type: [Object, String],
                required: true
            },            
            isBroadcaster: {
                type: [Boolean],
                required: true        
            },               
            user_info: {
                type: [Object, String],
                required: true
            },             
            member_info: {
                type: [Object, String],
                required: true
            },
        },
        data() {
          
            return {
                tutor_chat_message: "",
                privateMessage: "",
                chatlogs: [],
            }
        },

        mounted() {
        
            this.socket = io.connect(this.$props.canvas_server);



            let nickname = null;

            if (this.user_info.user_type == "TUTOR") {
                nickname = this.user_info.firstname
            } else {
                nickname = this.member_info.nickname
            }


            //register as user
            let user = {
                userid: this.member_info.user_id ,
                nickname: nickname,
                username: this.user_info.username,            
                channelid: this.channelid,
                status: "ONLINE",
                type: this.user_info.user_type,      
            }

            this.socket.emit('REGISTER', user); 

            this.socket.on('SEND_SLIDE_PRIVATE_MESSAGE', (response) => {     

                console.log("response ##", response)
                /* response
                let messageData = {
                    channelid: this.channelid,
                    userid: this.member_info.user_id,
                    nickname: nickname,
                    username: this.user_info.username,            
                    channelid: this.channelid,
                    type: this.user_info.user_type,
                    message: message,
                    time: time
                }*/

                if (response.userid !== this.user_info.userid) 
                {
                    new Promise((resolve, reject) => {

                        this.pushPrivateMessage(response);
                        resolve('private message resolved');
                        
                    }).then((result) => {

                        this.privateMessage = null;
                        this.scrollToBottom();
                    });                

                } 

            });


        },
        methods: {
            pushPrivateMessage(data) {
                // does something
                this.chatlogs.push(data);
            },
            scrollToBottom() {
                console.log("scroll to bottom");

                var textarea = document.getElementById('chatlogs');
                textarea.scrollTop = textarea.scrollHeight;            
            },
            isEnter(e) {
                if (e.keyCode === 13) {

                    if (this.privateMessage !== null) {
                        this.sendPrivateMessage(this.privateMessage);
                        this.privateMessage = null;
                    } else {
                        console.log("please enter a message")
                    }
                }
            },
            sendPrivateMessage(message) 
            {
              

                if (message == null) {
                    return false;
                }

                let nickname = null;

                if (this.user_info.user_type == "TUTOR") {
                    nickname = this.user_info.firstname
                } else {
                    nickname = this.member_info.nickname
                }

                

                var currentTime = new Date();    
                let time        = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

                let data = {
                    channelid: this.channelid,
                    userid: this.member_info.user_id,
                    nickname: nickname,
                    username: this.user_info.username,            
                  
                    type: this.user_info.user_type,
                    message: message,
                    time: time
                }

            
                console.log("messageData " , data);


                this.socket.emit("SEND_SLIDE_PRIVATE_MESSAGE", data); 

                 this.privateMessage = null;
            }
        
        }

    }


</script>
