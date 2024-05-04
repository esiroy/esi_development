<script>
import Vue from 'vue';
import VueSocketIO from 'vue-socket.io';
import FileUpload from 'vue-upload-component'
import TextareaAutosize from 'vue-textarea-autosize'
Vue.use(TextareaAutosize)


Vue.use(new VueSocketIO({
    debug: true,
    connection: 'https://chatserver.mytutor-jpn.info:30001/',
}));

export default {
    name: "member-floating-chat-component",
    components: {
        FileUpload
    },
    data() {
        return {
            users: [], // Initialize the 'users' property as an empty array
        };
    },
    sockets: {
        connect: (data) => {
            console.log(data);
            console.log('member suppoert socket connected')
        },
        update_user_list: function (users) {
            this.users = users;
            console.log('Updated users:', this.users);
        },
    },
    props: {
        userid: String,
        username: String,
        nickname: String,
        user_image: String,
        api_token: String,
        csrf_token: String,
        chatserver_url: String,
        customer_support_image: String,
        show_sidebar: Boolean
    },
    data() {
        return {
            isFetching: false,
            interval: "",
            //first load on opening chatbox
            firstLoad: true,
            message: [],
            users: [],
            //chat boxes
            showChatbox: false,
            chatboxes: [],
            chatlogs: [],
            message_count: 0,
            unread_message_count: 0,
            //hold the current recipient userid
            current_chatbox_userid: "",
            //uploader
            files: [],
            //History Loaders
            loadingHistory: false,
            historyNotifier: true,
            //buttons
            ButtonSend: null,
            ButtonUpload: null,
            //page
            page: []
        };
    },
    methods: {
        getUser() {
            return {
                userid: this.$props.userid,
                username: this.username,
                nickname: this.nickname,
                user_image: this.user_image,
                status: 'online',
                type: "MEMBER",
            }
        },
        registerUser() {
            this.$socket.emit('REGISTER', this.getUser);
        },
        updatetValue(value) {
            this.files = value;
        },
        /**
         * Has changed
         * @param  Object|undefined   newFile   Read only
         * @param  Object|undefined   oldFile   Read only
         * @return undefined
         */
        inputFile: function (newFile, oldFile) {

            this.prepareButtons();

            if (newFile && oldFile && !newFile.active && oldFile.active) {
                if (newFile.xhr) {
                    if (newFile.xhr.status === 200) {
                        //file information
                        let file = [{
                            'id': newFile.response.id,
                            'file_name': newFile.response.file,
                            'size': newFile.response.size,
                        }];

                        var currentTime = new Date();
                        let time = currentTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true })

                        //get the sender from props  (admin)
                        let sender = {
                            'msgCtr': 0,
                            'userid': this.userid,
                            'nickname': this.nickname,
                            'username': this.username,
                            'message': newFile.response.image,
                            'user_image': this.user_image, //@todo: make this for customer support 
                            'type': "MEMBER"
                        };
                        this.$forceUpdate();

                        //get the sender from props (user)                        
                        let id = newFile.response.id
                        let recipient = {
                            'id': newFile.response.id,
                            'userid': newFile.response.recipient_id,
                            'username': newFile.response.recipient_username,
                        };

                        socket.emit("SEND_USER_MESSAGE", { id, time, recipient, sender });
                    }

                }
            }

            if (this.$refs.upload.uploaded) {
                // console.log("all queue uploaded");
                this.files = [];

                this.scrollToEnd();
            }
        },
        /**
         * Pretreatment
         * @param  Object|undefined   newFile   Read and write
         * @param  Object|undefined   oldFile   Read only
         * @param  Function           prevent   Prevent changing
         * @return undefined
         */
        inputFilter: function (newFile, oldFile, prevent) {
            if (newFile && !oldFile) {
                // Filter non-image file
                if (!/\.(jpeg|jpe|jpg|gif|png|webp|pdf|doc|docx)$/i.test(newFile.name)) {
                    return prevent();
                }
            }


            if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
                // Create a blob field
                newFile.blob = ''
                let URL = window.URL || window.webkitURL
                if (URL && URL.createObjectURL) {
                    newFile.blob = URL.createObjectURL(newFile.file)
                }
                // Thumbnails
                newFile.thumb = ''
                if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
                    newFile.thumb = newFile.blob
                }
            }
        },
        prepareButtons: function () {
            //console.log(this.files.length);

            let message = document.getElementById("message").value;
            let fileSelectBtn = document.getElementById("file-select-button");
            let sendBtn = document.getElementById("send-button");

            if (message == "" && this.files.length == 0) {

                if (fileSelectBtn) {
                    fileSelectBtn.style.display = "block";
                }

                if (sendBtn) {
                    sendBtn.style.display = "none";
                }


            } else {

                if (fileSelectBtn) {
                    fileSelectBtn.style.display = "none";
                }

                if (sendBtn) {
                    sendBtn.style.display = "block";
                }
            }
        },
        closeChatBox() {
            this.showChatbox = false;
            this.unread_message_count = 0;
        },
        openChatBox() {
            this.showChatbox = true;
            let user = this.getUser();
            if (isNaN(this.page[user.userid])) {
                this.page[user.userid] = 1;
            }
            this.scrollToEnd();

            //mark read after opening
            this.delayandMarkUnread();
        },
    },
    mounted() {
        // Call the registerUser method when the component is mounted
        this.registerUser();
    },
};
</script>


<template>

    <div id="memberfloatingChat" class="container">
test
        <!--[start] Sidebar Chat Button -->
        <table cellpadding="4" cellspacing="0" class="mt-2" v-show="this.$props.show_sidebar == true">
            <tbody>
                <tr>
                    <td valign="top" width="65px">
                        <div class="pl-2">
                            <img :src="this.customer_support_image" width="60px">
                        </div>
                    </td>

                    <td valign="top" class="pl-2 text-align-center">
                        <div class="cs-speech-bubble">
                            <a href="" @click.prevent="openChatBox()">Chat Support</a>
                        </div>
                        <div class="small pt-1 pb-1">
                            <a
                                href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/0816220249.html','Chat Support ご利用方法',900,820);">Chat
                                Support ご利用方法</a>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
        <!--[end] Sidebar Chat Button -->


        <div id="fileUpload" class="position-bottom-right" style="display:none">
            <file-upload name="file" class="btn btn-primary" extensions="jpeg,jpg,gif,pdf,png,webp"
                accept="image/png, application/pdf, image/gif, image/jpeg, image/webp" v-model="files"
                post-action="/uploader/fileUploader" :data="{
            'current_chatbox_userid': this.current_chatbox_userid,
            'message_type': 'MEMBER',
            'folder': 'notes',
        }" :headers="{ 'X-CSRF-TOKEN': this.csrf_token }" :multiple="true" :drop="true" :drop-directory="true"
                @input="updatetValue" @input-file="inputFile" @input-filter="inputFilter" ref="upload">
            </file-upload>
        </div>

        <!--[START] CHAT BUTTON -->
        <div class="position-bottom-right" v-show="showChatbox == false">
            <b-button class="chat-button mb-4 mr-5" variant="primary" @click="openChatBox">
                <h2 class="fa fa-comments mt-1"></h2>
                <span class="badge badge-danger text-white small m-0 p-1" v-show="this.unread_message_count > 0">
                    {{ this.unread_message_count }}
                </span>
            </b-button>
        </div>
        <!--[END] CHAT BUTTON -->

        <div class="position-bottom-right mr-5" v-show="showChatbox == true">

            <div class="chatboxes">

                <div class="chatbox" v-for="(chatbox, index) in this.chatboxes" :key="'chatbox_' + index"
                    style="width:490px;">

                    <div class="card">

                        <!-- Float Customer Support (Front end )-->
                        <div class="card-header rounded-top bg-blue text-white" style="padding: 4px 10px 0px;">
                            <div class="small float-left font-weight-bold">Customer Support</div>
                            <span class="float-right">
                                <a href="" @click.prevent="closeChatBox"><i
                                        class="fas fa-times-circle text-white"></i></a>
                            </span>
                        </div>
                        <!--[end] Float Customer Support (Front end )-->

                        <div class="card-body bg-white pb-4" style="min-height:250px;">

                            <div id="user-chatlog" class="user-chatlog border rounded text-center">

                                <button v-on:click="getChatHistory(chatbox, false)" id="floating-history-btn"
                                    class="btn btn-xs btn-secondary"
                                    v-show="historyNotifier == true && isFetching == false">
                                    Fetch History
                                </button>

                                <button v-show="historyNotifier == true && isFetching == true" id="floating-history-btn"
                                    class="btn btn-xs btn-primary">
                                    <i class="fas fa-sync fa-spin"></i> Loading
                                </button>

                                <div class="chatlog-wrapper">

                                    <!--[START] CHATLOG MESSAGE-->
                                    <div class="chat" v-for="(chatlog, chatlogIndex) in chatlogs[chatbox.userid]"
                                        :key="'my_chatlog_' + chatlogIndex">

                                        <div class="row" v-if="chatlog.sender.type == 'CHAT_SUPPORT'">


                                            <div class="col-md-2">
                                                <div class="p-0"
                                                    v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'CHAT_SUPPORT'">

                                                    <b-img thumbnail fluid :src="customer_support_image"
                                                        rounded="circle" alt="CHAT_SUPPORT"></b-img>
                                                </div>
                                            </div>
                                            <div class="col-md-10 pl-0">
                                                <div class="small text-left mt-2"
                                                    v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'CHAT_SUPPORT'">
                                                    {{ chatlog.sender.nickname }}, {{ chatlog.time }}
                                                </div>
                                                <div class="chat-support-message"
                                                    v-html="formatMessage(chatlog.sender.message)"></div>
                                            </div>
                                        </div>


                                        <div class="row" v-if="chatlog.sender.type == 'MEMBER'">
                                            <div class="col-md-10">

                                                <div class="member-info"
                                                    v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'MEMBER'">
                                                    <span class="small">{{ chatlog.sender.nickname }}, {{ chatlog.time
                                                        }}</span>
                                                </div>

                                                <div class="member-message-container">
                                                    <div class="member-message"
                                                        v-html="formatMessage(chatlog.sender.message)"></div>
                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="p-0"
                                                    v-if="chatlogIndex == 0 || chatlogs[chatbox.userid][chatlogIndex - 1].sender.type !== 'MEMBER'">
                                                    <b-img thumbnail fluid :src="chatlog.sender.user_image"
                                                        rounded="circle" alt="Circle image"></b-img>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!--[END] CHATLOG MESSAGE-->

                                </div>

                            </div>


                            <div id="user-reply-container" class="row mt-2">

                                <div class="col-9 pr-0 mr-0">
                                    <div v-for="(file, index) in files" :key="file.id"
                                        class="image-prieview-container bg-light  w-25 d-inline-block p-1 border border-light rounded">
                                        <div class="remove-image-upload float-right">
                                            <a class="" href="#" @click.prevent="$refs.upload.remove(file)"
                                                style="padding:5px; background-color:#fff; color:#000">X</a>
                                        </div>

                                        <div v-if="file.type == 'image/jpeg' || file.type == 'image/png'">
                                            <img class="w-100" :src="file.thumb" :id="'image-preview-' + index">
                                        </div>

                                        <div v-else>
                                            <div>
                                                <i class="far fa-file" style="font-size:110px"></i>
                                            </div>
                                        </div>

                                        <div class="progress" v-if="file.active || file.progress !== '0.00'">
                                            <div :class="{ 'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active }"
                                                role="progressbar" :style="{ width: file.progress + '%' }">
                                                {{ file.progress }}%</div>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-9 pr-0 mr-0">

                                    <TextareaAutosize id="message" ref="messageTextarea" :min-height="25"
                                        :max-height="50" autocomplete="off"
                                        class="form-control form-control-sm auto-expand" v-model="message[index]"
                                        placeholder="Type a message" aria-label="Type a message" />


                                </div>
                                <div class="col-3 px-1">
                                    <div id="attach-button" class="input-group-append d-inline-block float-left">
                                        <label id="file-select-button" for="file" class="btn btn-primary mr-1 btn-sm">
                                            <i class="fas fa-paperclip"></i>
                                        </label>
                                    </div>

                                    <div id="send-button" class="input-group-append d-inline-block float-left">
                                        <button id="btn-send-message" type="button"
                                            @click.prevent="$refs.upload.active = false; sendMessage(chatbox, index);"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        </button>
                                    </div>

                                    <span class="button-controls" style=" display:none">
                                        <button id="startUpload" type="button" class="btn btn-sm btn-success"
                                            v-if="!$refs.upload || !$refs.upload.active"
                                            @click.prevent="$refs.upload.active = true">
                                            <i class="fa fa-arrow-up" aria-hidden="true"></i>Start Upload
                                        </button>

                                        <button type="button" class="btn btn-sm btn-danger" v-else
                                            @click.prevent="$refs.upload.active = false">
                                            <i class="fa fa-stop" aria-hidden="true"></i>Stop Upload
                                        </button>
                                    </span>
                                </div>

                            </div>

                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>


</template>