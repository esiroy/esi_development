<template>
    <div class="chat-container mb-2">

        <b-card-group>

            <b-card bg-variant="light" header-bg-variant="primary" text-variant="white">

                <template #header>
                    <div class="font-weight-bold">Chat Messages</div>
                </template>

                <b-card-text id="chatlogs" class="chatlogs text-dark" style="height: 280px; overflow: auto;">
                    <div :class="'chatlog-'+chatlogIndex" v-for="(chatlog, chatlogIndex) in chatlogs" :key="'chatlogs_'+ chatlogIndex">
                        <span v-html="chatlog.nickname"></span> : <span v-html="chatlog.message"></span>
                    </div>           
                </b-card-text>

            </b-card>
        </b-card-group>

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
        data: {
            //chat 
            tutor_chat_message: "",
            privateMessage: "",
            chatlogs: [],
        
        }

    }


</script>
