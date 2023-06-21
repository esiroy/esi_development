<template>

    <div id="caller-wrapper">

        <!--[start] Incoming Call -->
        <div class="container">
            <audio id="incomingCallAudio">
                <source src="" type="audio/mp3">
            </audio>

            <b-modal id="modal-call-alert" :title="'A tutor is inviting you for a call'" content-class="esi-modal" :header-bg-variant="headerBgVariant" no-close-on-esc no-close-on-backdrop hide-header-close>
                <div class="row text-center" v-if="this.callReservation !== null" >                     
                    <div class="col-12 text-center">
                        <div class="alert alert-primary" role="alert">
                            Please accept a lesson invitation from <span v-show="this.caller.nickname">({{ this.caller.nickname }})</span>
                            <!--  <div class="fullname">{{ this.caller.firstname + " " + this.caller.lastname }}</div>-->                            
                            <!-- Email: {{ this.caller.email }}  </div>-->
                        </div>

                         <img :src="this.caller.image" class="rounded-circle " width="150px">   
                     </div>              
                   
                </div>

                <template #modal-footer>
                    <div class="container text-center">
                        <b-button variant="success" @click="acceptCall">
                            <b-icon icon="telephone-inbound" animation="throb" font-scale="1"></b-icon> 
                            <span class="pb-3" animation="throb">Enter Lesson</span>
                        </b-button>
                    </div>
                </template>
            </b-modal>
        </div>
        <!--[end]-->


        <!-- SELECT LESSON -->
        <div id="select-lesson-container" class="container-fluid">
            <b-modal id="modalSelectLesson"  @show="getLessonsList" title="Select a Lesson" 
                size="xl" header-bg-variant="primary" header-text-variant="white" 
                hide-footer no-close-on-esc
            > 

                <div v-if="hasSelected == true">
                    <div class="alert alert-success" role="alert">
                        You selected a new lesson.
                    </div>                                    
                </div>

                <div v-if="hasSelected == false">
                    <div class="container" v-if="currentSelectedFolder !== null">
                        <div class="card mb-3">
                            <h5 class="card-header bg-secondary text-white">Current Lesson Selected </h5>
                            <div class="card-body bg-light">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-title">{{ currentSelectedFolder.folder_name }}</div>
                                        <p class="card-text">{{ currentSelectedFolder.folder_description }}</p>
                                    </div>
                                    <div class="col-12">
                                        <div class="row" v-if="currentSelectedFiles != null">
                                            <div class="col-2" v-for="(file, fileIndex) in currentSelectedFiles" :key="'image-'+fileIndex">
                                                <img :src="getBaseURL(file.path)" class="img-fluid cursor-pointer" @click.prevent="imageViewer(getBaseURL(file.path))"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="mt-3 text-center">
                        <div class="text-danger">Please select a folder</div>
                    </div>

                    <div id="ui-search-folders" class="container">
                        <div id="slideSelection" v-if="isSearching == false">

                            <div id="lesson-instructions" class="row mb-2">
                                <div class="col-7">
                                    <h5 class="text-maroon">
                                        Please select a Lesson 
                                    </h5>              
                                    <div class="font-weight-bold small float-left">前回のレッスンコースを継続する場合、入力は必要ありません。</div>
                                </div>
                                <div class="col-5">
                                    <div class="float-right">
                                        <b-button variant="primary" @click="openSearchUI()">
                                            <b-icon icon="search" aria-hidden="true"></b-icon>
                                        </b-button>

                                        <b-button variant="primary" @click="selectCategory(parentID)" v-if="parentID !== null">
                                            <b-icon icon="arrow-return-left" aria-hidden="true"></b-icon>
                                        </b-button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentFolder !== null">
                                <!--[start] Lesson Card -->
                                <div class="card">

                                    <!--[start] Preview Images -->
                                    <!--
                                    <div class="card-header">                                   
                                        <b-carousel id="carousel-files" v-model="slide" class="cursor-pointer"
                                            :interval="4000" controls indicators background="#ababab" style="text-shadow: 1px 1px 2px #333;"
                                            @sliding-start="onSlideStart" @sliding-end="onSlideEnd">
                                            <b-carousel-slide v-for="file in files" :key="'file_'+ file.id" >
                                                <template #img>
                                                    <div class="text-center " style="height:150px">
                                                        <img :src="getBaseURL(file.path)" class="img-fluid  cursor-pointer" height="auto" @click="imageViewer(getBaseURL(file.path))"/>                                        
                                                    </div>
                                                </template>
                                            </b-carousel-slide>
                                        </b-carousel>                                    
                                    </div>
                                    -->
                                    <!--[end] Preview Images -->

                                    <!-- [start] Lesson Information  -->
                                    <div class="card px-2">
                                        <div class="row">
                                            <div class="col-6">
                                                <div id="lesson-information" class="py-3">
                                                    <h4 class="card-title h3">{{ currentFolder.folder_name }}</h4>
                                                    <p  class="card-text small text-secondary">{{ currentFolder.folder_description }}</p>

                                                    <div id="button-lesson-select" v-if="folderCategories.length > 0 ">
                                                        <div class="alert alert-primary" role="alert">
                                                            <span class="text-success">Please select a category</span>
                                                        </div> 
                                                    </div>

                                                    <div v-else id="more-lessons-container" class="text-secondary">
                                                        <div class="alert alert-primary" role="alert">
                                                            <span class="text-success">Please select a lesson</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">

                                                <div id="lessons-container" class="accordion my-4" v-if="rows >= 1" >
                                                    <b-table  id="lesson-table" ref="lesson-table"
                                                        :items="lessons" 
                                                        :fields="fields"                                                
                                                        :per-page="perPage"
                                                        :current-page="currentPage"
                                                        :striped="false"
                                                        :hover="true"
                                                        borderless
                                                        :outlined="false"
                                                        :class="'no-padding'"
                                                    >

                                                    
                                                        <template #cell(actions)="row" >

                                                            <b-card no-body>
                                                                <b-card-header header-tag="header" class="p-0" role="tab">
                                                                    <b-button-group block class="w-100">        
                                                                        <b-button block variant="primary" @click="getLessonImages(row.index, row.item.id)">
                                                                            {{ row.item.folder_name }} 
                                                                        </b-button>
                                                                        <b-button variant="success" size="sm" class="w-25" @click.prevent="selectNewLesson(row.item.id)">                                                               
                                                                            <div class="small text-center"> Select Lesson</div>              
                                                                        </b-button>                                                                    
                                                                    </b-button-group>
                                                                </b-card-header>                                                                
                                                                <b-collapse v-model="isCollapsed[row.index]" id="my-collapse-id">
                                                                    <b-card-body v-if="folder_images[row.index]">
                                                                        <div class="row">
                                                                            <div class="col-4" v-for="(images, imageIndex) in folder_images[row.index]" 
                                                                                    :key="'folder_images_'+row.index+'_'+imageIndex">
                                                                                <img class="img-fluid cursor-pointer" :src="getBaseURL(images.path)"  @click.prevent="imageViewer(getBaseURL(images.path))">   
                                                                            </div>
                                                                        </div>
                                                                    </b-card-body>
                                                                </b-collapse>
                                                            </b-card>
                                                            

                                                        </template>
                                                    </b-table>

                                            

                                                
                                                    <div class="mt-3">                                                     
                                                        <!--<b-pagination v-model="currentPage" :total-rows="rows" :input="openPage('page-' +currentPage)" :per-page="perPage" :limit="10"></b-pagination>-->
                                                        <b-pagination 
                                                            v-model="currentPage" 
                                                            :total-rows="rows" 
                                                            :per-page="perPage" 
                                                            :limit="5"
                                                            @input="clearFolderImages"
                                                            aria-controls="lesson-listings"
                                                            >
                                                        </b-pagination>
                                                    </div>

                                                    <!--
                                                    <div class="col-12 cursor-pointer" v-for="lesson in lessons" :key="'lesson_'+lesson.id" @click="selectNewLesson(lesson.id)">
                                                        <div class="card mb-3 border-primary cursor-pointer">
                                                            <div class="card-header bg-primary">
                                                                <span class="text-white">
                                                                    {{ lesson.folder_name }}
                                                                </span>
                                                            </div>
                                                            <div class="card-body card-body-minimum">
                                                                <p class="card-text small">
                                                                    {{ lesson.folder_description }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    -->

                                                </div>

                                                <!--
                                                <div v-else>
                                                    <div class="text-center my-4">
                                                        <span class="small text-maroon">No Lessons for this category</span>
                                                    </div>
                                                </div>-->
                                            </div>

                                        </div>

                                    </div>
                                    <!-- [end] Lesson Information  -->                           
                                </div>
                                <!--[end] Lesson Card-->
                            </div>

                        

                            <!-- Categories -->
                            <div class="mt-3" v-if="folderCategories.length >= 1">
                                <fieldset class="border p-2">

                                    <legend  class="w-auto text-primary">
                                        <span v-if="parentID !== null"> Related Categories</span>
                                        <span v-else>Lesson Categories</span>
                                    </legend>

                                    <div id="folder-categories" class="row"  >
                                        <div class="col-3 cursor-pointer" v-for="category in folderCategories" :key="'category_'+category.id" @click="selectCategory(category.id)">
                                            <div class="card mb-3 border-primary cursor-pointer">
                                                <div class="card-header bg-primary">
                                                    <span class="text-white">
                                                        {{ category.folder_name }}
                                                    </span>
                                                </div>
                                                <div class="card-body card-body-minimum">
                                                    <p class="card-text small">
                                                        {{ category.folder_description }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!--[end] Categories -->
                            
                        </div>

                        <div v-else-if="isSearching == true">

                            <div id="lesson-instructions" class="row mb-2">
                                <div class="col-4">
                                    <h5 class="text-maroon">Search </h5>              
                                </div>
                                <div class="col-8">
                                    <div class="float-right">
                                        <b-button variant="primary" @click="closeSearchUI()">
                                            <b-icon icon="x" aria-hidden="true"></b-icon>
                                        </b-button>
                                    </div>
                                </div>
                            </div>

                            <div role="group" class="input-group">
                                <!--[start] Seach Icon-->
                                <div class="input-group-prepend">
                                    <div class="input-group-text">                            
                                        <b-icon icon="search" aria-hidden="true"></b-icon>                              
                                    </div>
                                </div> 
                                <!--[end] Search Icon -->                        
                                <!--[start] Seach Keyword -->
                                <input id="bv-icons-table-search" type="search" v-model="searchKeyword" 
                                    @blur="handleSearch()"  @keyup="handleSearch()" @change="handleSearch()" @clear:append="handleSearch()" @click="handleSearch()"
                                    autocomplete="off" aria-controls="bv-icons-table-result" 
                                    class="form-control"
                                >
                                <!--[end] Search Keyword-->
                            </div>

                            <!--[start] search results -->

                            <fieldset class="border p-2 mt-2">

                                <legend class="w-auto text-primary">                              
                                    <span>Search Results</span>
                                </legend>

                                <div class="container">
                                    <div class="row mt-1" v-if="searchResults.length >= 1">
                                        <div class="col-3 cursor-pointer" v-for="category in searchResults" :key="'search_'+category.id"  @click="selectCategory(category.id)">
                                            <div class="card mb-3 border-primary">
                                                <div class="card-header bg-primary">
                                                    <span class="text-white">
                                                        {{ category.folder_name }}
                                                    </span>
                                                </div>
                                                <div class="card-body card-body-minimum">
                                                    <p class="card-text small">
                                                        {{ category.folder_description }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>   
                                    </div>
                                    <div v-else class="col-12 text-center">
                                        <div class="py-4">
                                            <span class="text-danger small">No results found</span>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        
                            <!--[end] -->

                        </div>
                    </div>
                </div>
             </b-modal>
        </div>




        <div id="image-viewer-container" class="container-fluid">
            <b-modal id="modalImageViewer" size="xl" title="Image Preview" ok-only>
                <div v-if="imageURL !== null">
                    <img :src="imageURL" class="img-fluid">
                </div>
            </b-modal>
        </div>

        <!--
        <div id="image-viewer-container" class="container-fluid">
            <b-modal id="modalImageViewer"  title="Image Preview" ok-only>
                <div v-if="imageURL !== null">
                    <img :src="imageURL" class="img-fluid">
                </div>
            </b-modal>
        </div>
        -->
        
    </div>


</template>

<script>
    
    import io from "socket.io-client";    
    import Moment from "moment-timezone";
    import MemberLessonSliderComponent from "./MemberLessonSliderComponent.vue";


    export default {
        name: "memberCallerComponent",
        components: {    
            MemberLessonSliderComponent
        },        
        props: 
        {
            csrf_token: String,		
            api_token: String,
            user_image: String,
            isBroadcaster: {
                type: [Boolean],
                required: true        
            },
            userInfo: {
                type: [Object, String],
                required: true
            },     
            canvasServer: {
                type: [String],
                required: true        
            },           
            memberInfo: {
                type: [Object, String],
                required: true
            },                   
        },
        data() {
            return {

                isModalMaximized: false,

                headerBgVariant: 'lightblue',


                user: null,
                users: null,
                tutor: null,
                reservation: null,

                now         : null,
                currentTime: null,
                lessonStartTime: null,
                lessonEndTime: null,

                //Selected lesson Options
                selectedOption: null,

                //Model lesson Value
                lessonSelected: null,
                lessonSelectedFolderID: null,

                //Lesson Options
                lessonOptions: [],

                //caller
                caller: null,
                recipient: null,
                callReservation: null,

                //loader
                sliderLoaded: false,
                channelid: null,


                //Member Reservation
                lessonReservationData: null,

               


                isSearching: false,
                searchTimeout: null,
                searchKeyword: null,
                searchResults: [],

                //Carousel sliders
                slide: 0,
                sliding: null,

                //Lesson Selected ID
                selectedLessonID: null,

                //[list]
                currentFolderID: null,
                currentFolder: null,
                parentID: null,
        
                folderCategories: [],
                lessons: [],
                files: [],
                folder: null,
                imageURL: null,         

                //client ID
                lessonID: null,
                userID : null,
        

                //Model lesson Value
                lessonSelected: null,
                lessonSelectedFolderID: null,                

                //Current Selected (Lesson)
                currentMember: null,
                myReservation: null,

                currentSelectedFolder: null,
                currentSelectedFiles: [],

                //Lesson list
                hasSelected: false,
                selectSuccessMessage: null,

                currentPage: null,
                rows: 0,
                perPage: 5,               
                fields: [
                    //{ key: 'folder_name', label: 'Lesson', sortable: true, sortDirection: 'desc' },
                    { key: 'actions', label: 'Actions' }
                ],                
              

                //accordion images
                folder_images: [],
                isCollapsed: [],
            }
        },
        mounted() {

          
            //Transfer the object to the window
            window.memberCallerComponent = this;
            this.socket = io.connect(this.canvasServer);

            //Register the member Info
            this.user = {
                channelid: this.channelid, 
                userid: this.memberInfo.user_id,
                username: this.userInfo.username,
                nickname: this.userInfo.nickname,
                image:   this.$props.user_image,
                type: this.userInfo.user_type,
                status: "ONLINE",
               
            }  
       

           
            this.socket.emit('REGISTER', this.user); 


            //update the list
            this.socket.on('update_user_list', users => {
                this.updateUserList(users); 
            });


            this.socket.on("CALL_USER", (userData) =>  
            {
                if (this.user.userid == userData.recipient.userid ) 
                {       
                    this.caller              = userData.caller;                        
                    this.recipient           = userData.recipient;
                    this.callReservation     = userData.reservationData;

                    console.log("socket CALL_USER ", userData, this.caller);
                    
                    this.$bvModal.show('modal-call-alert');  

                    //SEND THE CALL USER PING BACK WITH CHANNEL ID
                    this.recipient.channelid = userData.reservationData.schedule_id;
                    this.recipient.channelid = userData.reservationData.schedule_id;

                    
                    this.playIncomingCallAudio({'path': 'mp3/incoming-call.mp3'}, 50); // Play 50. times


                    this.socket.emit('CALL_USER_PINGBACK', this.recipient); 
                    
                    console.log(userData.recipient,  "emit call user pingback")
                }                
            });

            this.socket.on("ACCEPT_CALL", (data) =>  
            {
                console.log("ACCEPT_CALL", data);

                if (this.user.userid == data.tutorData.userid ) 
                {    
                    this.$bvModal.hide('modal-call-alert');   
                    this.caller              = data.caller;                        
                    this.recipient           = data.recipient;
                    this.callReservation     = data.reservationData;                    
                } 
            });

            this.socket.on("START_SLIDER", (data) =>  {

                if (this.user.userid == data.recipient.userid ) 
                {
                    this.lessonReservationData  = data.reservationData;
                    this.$bvModal.hide('modal-call-teacher'); 
                    this.$bvModal.hide('modal-call-member'); 
                    this.$bvModal.hide('modal-call-alert');            
                    //this.openSelfWindow(data.channelid);       

                } else if (this.user.userid == data.caller.userid ) {
                 
                    this.lessonReservationData  = data.reservationData;
                    this.$bvModal.hide('modal-call-teacher'); 
                    this.$bvModal.hide('modal-call-member'); 
                    this.$bvModal.hide('modal-call-alert');            
                    //this.openSelfWindow(data.channelid);  
                 
                 }

            }); 
        },
        methods: {
            maximizeModal() {
                this.isModalMaximized = !this.isModalMaximized;
            },        
            showCollapse(index) 
            {             
                for (var i = 0; i <= this.perPage; i++) 
                {                    
                    if (i == index)  {                    
                        if (this.isCollapsed[index] == false) {
                            this.$set(this.isCollapsed, index, true)
                        } else {                          
                            this.$set(this.isCollapsed, index, false) //hide
                        } 
                    } else {                        
                        this.$set(this.isCollapsed, i, false) //hide
                    }                
                }                
            },        
            getLessonImages(index, folderID) {
                this.showCollapse(index) 
                axios.post("/api/getLessonImages?api_token=" + this.api_token, 
                {
                    method                  : "POST",
                    folderID                : folderID,
                }).then(response => {

                    if (response.data.success == true) 
                    {       
                        this.$set(this.folder_images, index, response.data.files)
                        this.$forceUpdate();

                    } else {
                        
                    }
                });
            }, 
            clearFolderImages() {
                this.folder_images = [];
                for (var i = 0; i <= this.perPage; i++) {  
                    this.$set(this.isCollapsed, i, false);
                }
            },
            
            updateUserList: function(users) 
            {
                this.users = users;      
                this.$forceUpdate();
            },
            playIncomingCallAudio(audioConfig, remainingPlays) {

                console.log("playing incoming call audio, remaining plays:", remainingPlays)

                if (remainingPlays <= 0) {
                    console.log("user busy, we called 50 times")
                    return;
                }

                let incomingCallAudio = document.getElementById('incomingCallAudio');
                if (incomingCallAudio) {      
                    incomingCallAudio.src = window.location.origin +"/"+ audioConfig.path;                              
                    incomingCallAudio.load();
                    incomingCallAudio.play();  
                }

              
                setTimeout(() => {
                    this.playIncomingCallAudio(audioConfig, remainingPlays - 1);
                    console.log("replaying IncomingCallAudio")
                }, 3000); // Delay of 1000 milliseconds (1 second)
            },
            openSelfWindow(channelid) {
                window.location.href = window.location.origin + "/webRTC?roomid="+ channelid
            },
            openNewChannelTab(channelid) {
                var baseURL = window.location.origin + "/webRTC?roomid="+ channelid
                window.open(baseURL, '_blank');
            },
            acceptCall() {
                ///console.log(this.caller,  this.recipient, this.callReservation );                             
                this.sliderLoaded = false;
                this.channelid                 = this.callReservation.schedule_id;
                this.lessonReservationData     = this.callReservation;

                this.$bvModal.hide('modal-call-alert'); 

                let data = {
                    channelid       :  this.channelid,
                    caller          :   this.caller,
                    recipient       :   this.recipient,
                    reservationData :   this.callReservation
                }
                
                this.$bvModal.hide('modal-call-teacher'); 
                this.$bvModal.hide('modal-call-member'); 

                //this.$bvModal.show('modal-lesson-slider'); (v1)              
                this.socket.emit('START_SLIDER', data); //(v2)
                this.openSelfWindow(this.channelid);  //v3

            },
            callMember(tutor, member, reservation) {

                this.now                = new Moment().tz("Japan");
                this.currentTime        = Moment(this.now).tz("Japan").format("YYYY-MM-DD HH:mm:ss"); 
                this.reservation        = JSON.parse(reservation);
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);

                this.lessonStartTime    = Moment(this.reservation.lesson_time ).format('YYYY-MM-DD HH:mm:ss')
                this.lessonEndTime      = Moment(this.reservation.lesson_time ).add(this.reservation.duration, 'minutes').format('YYYY-MM-DD HH:mm:ss');


                //console.log(this.lessonStartTime)
                //console.log(this.lessonEndTime);
                //console.log(this.reservation)

                //search member
                let userIndex = this.users.findIndex(user => user.userid == this.member.userid)
                this.recipient = this.users[userIndex];

                if (typeof this.recipient == 'undefined') {
                    alert ("Sorry, Member is not online at the moment");
                    return false
                }  else {         
                    let response = this.checkTime();
                    if (response.valid == true) {
                        this.$bvModal.show('modal-call-member');       
                        //CALL USER (EMIT DATA)
                        let data = {
                            recipient       :   this.member,    //recipient 
                            caller          :   this.tutor,     //caller
                            reservationData :   this.reservation
                        }
                        this.socket.emit('CALL_USER',  data);  
                    }
                }
            },
            //CALL TUTOR IS INITIATED AT THE FRONT END USING JS (window.memberCallerComponent.callTutor) COMMAND
            callTutor(tutor, member, reservation) {

                console.log("call tutor")

                this.channelid = reservation.schedule_id;
                
                this.lessonReservationData  = reservation;

                this.now                = new Moment().tz("Japan");
                this.currentTime        = Moment(this.now).tz("Japan").format("YYYY-MM-DD HH:mm:ss"); 


                this.reservation        = JSON.parse(reservation);
                this.tutor              = JSON.parse(tutor);
                this.member             = JSON.parse(member);

                this.lessonStartTime    = Moment(this.reservation.lesson_time ).format('YYYY-MM-DD HH:mm:ss')
                this.lessonEndTime      = Moment(this.reservation.lesson_time ).add(this.reservation.duration, 'minutes').format('YYYY-MM-DD HH:mm:ss');

                
                let userIndex = this.users.findIndex(user => user.userid == this.tutor.userid);
                this.recipient = this.users[userIndex];



                if (typeof this.recipient == 'undefined') {

                    alert ("Sorry, teacher is not online at the moment");
                    return false;

                } else {               

                    
                    /** 
                    **  
                    **  [CHECK CURRENT TIME TO AVOID DUPLICATE CALLS]    
                    ** 
                    */
                    let response = this.checkTime();

                    if (response.valid == true) 
                    {

                        this.$bvModal.show('modal-call-teacher');

                        //CALL USER (EMIT DATA)
                        let data = {
                            caller          :   this.member,
                            recipient       :   this.tutor,   //recipient tutor 
                            reservationData :   this.reservation
                        }

                        this.socket.emit('CALL_USER',  data);  

                    }


                }
            }, 
            cancelCall() 
            {
                if (this.user.type == "TUTOR") 
                {
                     this.$bvModal.hide('modal-call-alert');

                } else if (this.user.type == "MEMBER") {

                    this.$bvModal.hide('modal-call-teacher');                   
                }
                
            
                let data = {
                    memberData      :   this.member,
                    tutorData       :   this.tutor,
                    reservationData :   this.reservation
                }

                this.socket.emit('DROP_CALL', data);

            },
            checkTime() 
            {
                var duration        = Moment.duration(this.now.diff(this.lessonStartTime));
                var days            = duration.asDays();

                //*** TEMPORARY VALID ALL TO TRUE */
                return ({'valid': true});


                if (this.currentTime >= this.lessonStartTime && this.currentTime <= this.lessonEndTime) 
                {
                    this.$bvModal.show('modal-call-teacher');

                    return ({'valid': true})

                } else if (this.currentTime >=  this.lessonEndTime) {



                    alert ("The Lesson has already ended" +  this.currentTime +">=  " + this.lessonEndTime);

                    return ({'valid': false})

                } else {

                    let numberOfDays = parseInt(days);

                    if (numberOfDays == 0) {
                    
                        alert ("Your lesson will start on " + this.lessonStartTime + " Today");


                    } else {

                         alert ('Lesson will start after '+ Math.abs(numberOfDays) +' days ');
                    }

                   

                    return ({'valid': false})

                }
            
            },

            /****** START SEACH UI****  */     
            openSearchUI() {
                this.isSearching = true;
            },
            closeSearchUI() {
                this.isSearching = false;
            },
            handleSearch() {            
                const str = ''+ this.searchTimeout + ''; 
                const trimmedStr = str.trim(); 
                const isEmpty = trimmedStr.length === 0;

                if (isEmpty) {
                //@todo: add empty message
                } else {
                    clearTimeout(this.searchTimeout);
                    // Start a new timeout to delay the search by half a second
                    this.searchTimeout = setTimeout(this.search, 500);
                }
            },
            search() {
                console.log("searching...");

                axios.post("/api/searchFolders?api_token=" + this.api_token, 
                {
                    method          : "POST",                
                    searchKeyword          : this.searchKeyword,
                
                }).then(response => {

                    if (response.data.success == true) {       
            
                        this.searchResults = response.data.folders;

                    } else {                
                        //alert ("Error, we can't do a search on your keyword, please try again later")
                        this.searchResults = [];
                        this.$forceUpdate();
                    }
                });
            },

            /* Select Lesson */
            selectLesson(tutor, member, reservation) 
            {           
                //this.tutor              = JSON.parse(tutor);
                //this.member             = JSON.parse(member);
                //this.reservation        = JSON.parse(reservation);
                let myTutor                 = JSON.parse(tutor);
                this.myReservation          = JSON.parse(reservation); 
                this.currentMember          = JSON.parse(member);
                this.getMemberSelectedLesson(this.myReservation, this.currentMember )                             
            },                                
           
            getMemberSelectedLesson(myReservation, currentMember) {
                axios.post("/api/getMemberLessonSelected?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    userID          : currentMember.userid,
                    lessonID        : myReservation.schedule_id
                }).then(response => {

                    if (response.data.success == true) {
                        this.lessonSelectedFolderID =  response.data.memberSelectedLesson.folder_id;
                        this.getLessonSelectedPreviewByID(myReservation, this.lessonSelectedFolderID)
                        this.$bvModal.show('modalSelectLesson');
                    } else {  
                        this.$bvModal.show('modalSelectLesson');     
                    }
                });
            },
            getLessonSelectedPreviewByID(myReservation, lessonSelectedFolderID) 
            {
                axios.post("/api/getLessonSelectedPreview?api_token=" + this.api_token, 
                {
                    method                  : "POST",
                    userID                  : myReservation.member_id,
                    lessonID                : this.selectedLessonID,
                    lessonSelectedFolderID  : lessonSelectedFolderID

                }).then(response => {

                    if (response.data.success == true) {        

                        this.currentSelectedFolder = response.data.folder;      

                        //determine the file
                        if (response.data.files.length == 0) {
                            this.currentSelectedFiles = null;
                            this.$forceUpdate();
                        } else {
                            this.currentSelectedFiles = response.data.files;
                            this.$forceUpdate();
                        }
                    } else {
                        //@note:  nullify files to null to make the notication appear
                        this.currentSelectedFiles = null;
                        this.folder = null;   
                        this.$forceUpdate();  
                    }
                });
            },                      
            openSlideSelector(lessonID, userID) {
                this.selectedLessonID = null;
                this.parentID  = null;
                //MEMBER INFO
                this.lessonID   = lessonID;
                this.userID     = userID;
                this.$refs['slideSelectorModal'].show();
            },
            closeSlideSelector() {
                this.$refs['slideSelectorModal'].hide();
            },
            selectNewLesson(folderID) {        
                this.lessonSelectedFolderID = folderID;
                this.saveOptionSelected(folderID);
            },
            saveOptionSelected(folderID) {

                axios.post("/api/saveSelectedLessonSlideMaterial?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    userID          : this.currentMember.userid,
                    lessonID        : this.myReservation.schedule_id,
                    folderID        : folderID,

                }).then(response => {

                    if (response.data.success == true) {
                        this.hasSelected = true;
                    
                        setTimeout(() => {
                            this.$bvModal.hide('modalSelectLesson');  
                        }, 1500);
                      
                    } else {
                        alert (response.data.message);
                    }
                });

            },             
            selectCategory(id) {
                this.isSearching = false;
                if (id == 0) {                
                    this.selectedLessonID = null; 
                } else {
                    this.selectedLessonID = id; 
                }
                this.getLessonsList();
            },
            openPage(num) {
                axios.post("/api/getLessonList?api_token=" + this.api_token, 
                {
                    method          : "POST",                
                    folderID        : this.selectedLessonID,
                    //public_folder_id : null,
                }).then(response => {
                    if (response.data.success == true) {
                        this.lessons            = response.data.lessons.data; 
                    } else {                    
                        alert ("Error, we can't get your list of lesson, please try again later")
                    }
                });             
            },                        
            getLessonsList() {

                this.hasSelected = false;

                axios.post("/api/getLessonFolders?api_token=" + this.api_token, 
                {
                    method          : "POST",                
                    folderID        : this.selectedLessonID,
                    //public_folder_id : null,
                }).then(response => {

                    if (response.data.success == true) {

                        this.parentID           = response.data.parentID;
                        this.currentFolder      = response.data.currentFolder;
                        this.folderCategories   = response.data.folderCategories;                          
                        this.lessons            = response.data.lessons;
                        this.rows               = response.data.lesson_rows; 
                        this.files              = response.data.files;

                        //this.lessons            = response.data.lessons.data; (lazy loading)

                        this.$forceUpdate();
                    } else {                    
                        alert ("Error, we can't get your list of lesson, please try again later")
                    }
                });
            },
            onSlideStart(slide) {
                this.sliding = true
            },
            onSlideEnd(slide) {
                this.sliding = false
            },
            getBaseURL(path) {
                return window.location.origin + "/" +path
            },
            imageViewer(imageURL) {
                this.imageURL = imageURL;
                this.$bvModal.show('modalImageViewer');
            }, 
            toggleDetails(row) {
                return row;
            }
        }
    };
</script>

<style scoped>
    .cursor-pointer {
        cursor: pointer;
    }
    .card-body-minimum {
        min-height: 100px;
    }    

</style>

<style >
    .no-padding td {
        padding: 0;
    }
</style>