<template>

    <div id="lesson-chat-viewer">
        <div class="chatbox-container">         
        
            <div class="card">

                <div class="card-body bg-white pb-4" style="min-height:250px;" v-if="messages.length >= 1">

                    <div id="user-chatlog" class="user-chatlog border rounded text-center">
                        <div class="chatlog-wrapper">

                            <div class="chat mt-1 p-1" v-for="(chatlog, chatlogIndex) in messages" :key="'my_chatlog_'+ chatlogIndex">                               
                                <div v-if="user.id == chatlog.sender_id">
                                    <div class="sender-container">  
                                        <div class="sender-wrapper text-right small">                                          
                                            <div class="sender small text-right"  v-if="chatlogIndex >= 1 && messages[chatlogIndex - 1].user_type !== messages[chatlogIndex].user_type">
                                                {{chatlog.nickname || chatlog.firstname }}
                                            </div>
                                            <div  class="sender small text-right"  v-if="chatlogIndex == 0">
                                                {{chatlog.nickname || chatlog.firstname }}
                                            </div>
                                            
                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.created_at }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="recipient-container">
                                        <div class="recipient-wrapper text-left small">
                                            <div class="sender small text-left" v-if="chatlogIndex >= 1 && messages[chatlogIndex - 1].user_type !== messages[chatlogIndex].user_type" >
                                                {{chatlog.nickname || chatlog.firstname }}
                                            </div>

                                            <div  class="sender small text-left"  v-if="chatlogIndex == 0">
                                                {{chatlog.nickname || chatlog.firstname }}
                                            </div>

                                            <div class="message-container">
                                                <div class="message small">
                                                    <span v-html="chatlog.message"></span>
                                                    <div class="small text-align-right">
                                                        <span class="small font-italic text-secondary">{{chatlog.created_at }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body bg-white py-4" v-else>
                    <div class="text-danger small text-center"> No Messages </div>
                </div>

            </div>
           
        </div>


        <ImageViewerComponent ref="ImageViewerComponent" />
        
    </div>


</template>

<script>

import ImageViewerComponent from "../../image/imageViewerComponent.vue"; // Import the modal component

export default {
  name: "lessonSlideChatViewerComponent",
  components: { ImageViewerComponent },    
  props: {
    user: Object,
    api_token: String,
    messages: Array,
  },
  data() {
    return {  
     
    };
  },
  mounted() 
  {      
    this.scrollToEnd();
    this.addLessonImagePopupListener();
    console.log("lesson slide chat viewer");
  },
  methods: {

    addLessonImagePopupListener() 
    {

        
        //add the image viewer
        var chatboxElement = document.querySelector('.chatbox-container');
        
        // Check if the element with the class "chatbox" exists
        if (chatboxElement) {        
            // Add a click event listener to the "chatbox" element
            chatboxElement.addEventListener("click", this.imageViewerClick);
        } else {
            console.log("Element with class 'message-container' not found");
        }
    },
    scrollToTop: function() {
        this.$forceUpdate();
        var container = this.$el.querySelector("#user-chatlog");
        if(container) {            
            container.scrollTop = 0;
        }
    },
    scrollToEnd: function() {
        this.$forceUpdate();   

        this.$nextTick(function()
        {      
            var container = this.$el.querySelector("#user-chatlog");
            if(container){
                container.scrollTop = container.scrollHeight;
              
            }
        });

    }, 

    imageViewerClick(event) {
      // Check if the clicked element has the "img_preview" class
      if (event.target.classList.contains("img_preview")) {
        event.preventDefault();
        // Extract the image URL from the clicked element and open the modal
        const imageUrl = event.target.getAttribute("src");
        
        //this.$refs.ImageViewerComponent.isModalOpen = true; // Open the modal
        //this.$refs.ImageViewerComponent.modalImageUrl = imageUrl; // Set the image URL

        this.$refs.ImageViewerComponent.openModal(imageUrl); // Open the modal

      }
    },    
  },
  computed: {},

};
</script>


<style>
    .img_preview {
        width: 100%;
    }
    .custom-pdf {
        font-size: 100px;
    }



</style>


<style scoped>

    .img_preview {
        width: 100px;
    }
    #fileUpload {
        display: none;
    }


    .bg-blue {    
        background-color: #009fd9
    }
    .border-blue {
        border-color: #009fd9
    }
     

    .position-bottom-right {
        position: fixed;
        bottom: 0px;
        right: 75px;
        z-index: 9999;
    }
    #floating-history-btn {
        font-size: 11px;
        padding: 2px 5px 2px;
        position: absolute;
        top: 42px;
        left: 215px;
        z-index: 1;
    }
 

    .btn-xs {
        font-size: 11px;
        padding: 2px 5px 2px;
    }

    .chatlog-wrapper {
        margin-top: 5px;
    }


    .chat-button {
        border-radius: 50%;
        height: 65px;
        width: 65px;
        padding: 0px;
    }


    .user-chatlog {
        border: 1px solid #ececec;
        padding: 5px 5px 15px;
        min-height: 150px;
        max-height: 175px;
        margin-bottom: 3px;
        overflow-x: hidden;
        overflow-y: scroll;
    }

    .member-info {
        text-align: right;
    }

    .member-image {
        float: right;
        border-radius: 50%;
        margin-left: 30px;
        border: 3px solid #ededed;
    }
 
    /* SENDER BUBBLE */
    .sender-container {
        width: 100%;
        clear:both;
    }

    .sender-container .message-container {    
        position: relative;
        float: right;
    }

    .sender-container .message{
        position: relative;
        background-color: #DBF4FC;
        border-radius: .4em;
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        padding: 5px 15px 5px;
        margin-bottom: 7px;        
    }


    /* RECIPIENT BUBBLE */
    .recipient-container {
        width: 100%;
        clear:both;
    }

    .recipient-container .message-container {    
        position: relative;
        float: left;
    }

    .recipient-container .message{
        position: relative;
        background-color: #F2F6F9;
        border-radius: .4em;
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        padding: 5px 15px 5px;
        margin-bottom: 7px;
    }

    .strike {
        display: block;
        text-align: center;
        overflow: hidden;
        white-space: nowrap; 
        width: 100%;
    }

    .strike > span {
        position: relative;
        display: inline-block;
        font-size: 10px;
    }

    .strike > span:before,
    .strike > span:after {
        content: "";
        position: absolute;
        top: 50%;
        width: 9999px;
        height: 1px;
        background: #ccc;
    }

    .strike > span:before {
        right: 100%;
        margin-right: 5px;
    }

    .strike > span:after {
        left: 100%;
        margin-left: 5px;
    }


</style>