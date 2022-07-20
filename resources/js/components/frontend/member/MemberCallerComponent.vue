<template>

    <div class="container">

        Calling

    </div>
</template>

<script>
    
    import io from "socket.io-client";

    export default {
        name: "Member-Caller-Component",
        props: 
        {
            csrf_token: String,		
            api_token: String,
            userInfo: {
                type: [Object, String],
                required: true
            },       
            memberInfo: {
                type: [Object, String],
                required: true
            },                   
        },
        mounted() {
            this.socket = io.connect(this.canvasServer);

            let user = {
                userid: this.memberInfo.user_id ,
                username: this.memberInfo.username,
                nickname: this.memberInfo.nickname,
                channelid: this.channelid,        

                type: "MEMBER",      
            }                

            this.socket.emit('REGISTER', user);  

            this.addListeners();  

             window.test = this;


        },
        methods: {

            test() {
                alert ("hello member caller test!")
            },
            callMemeber(scheduleId, startDate, startTime, endTime) {
                alert ("this is a member caller test")
            }

        }

    };
</script>