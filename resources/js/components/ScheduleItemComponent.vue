<!--
@Description: This will add the tutor schedule for the member(student)
@Developer: Roy
@Company: ESucess Inc.
-->
<template>
    <div id="scheduleItemModal">


        <b-modal id="memberMemoModal" ref="memberMemoModal"  v-bind:title="'Member Memo - ' + this.selectedlessonTime"  
        @show="retrieveMemo()" ok-only ok-variant="secondary" ok-title="Close" no-fade class="small">
            <div id="user-chatlog" ref="userChatlog" style="height: 420px;overflow-y: scroll;overflow-x: hidden;">
                <p class="my-2 " v-html="memberMemo"></p>
            </div>
        </b-modal>

        <b-modal id="schedulesModal" 
            title="Schedule Lesson"
            button-size="sm"
            no-fade
            :cancel-disabled="modalBusy"
            :ok-disabled="modalBusy"
            :no-close-on-backdrop="true"
            @show="resetModal"
            @hidden="hideModal"
            @ok="handleOk"
            
            
        >
            <div class="row">
                <div class="col-md-3">
                    <label>Status :</label>
                </div>
                <div class="col-md-9">
                    <select name="status" ref="status" v-model="status" @change="setMemberListLock()"  class="form-control form-control-sm">
                        <!--<option value="">Please Select A Status</option>-->
                        <option value="TUTOR_SCHEDULED" v-show="checkSelectedSchedulStatus()">Tutor Scheduled</option>
                        <option value="CLIENT_RESERVED" v-show="checkSelectedSchedulStatus()">Client Reserved</option>
                        <option value="CLIENT_RESERVED_B" v-show="checkSelectedSchedulStatus()">Client Reserved B</option>                        
                        <!--<option value="SUPPRESSED_SCHEDULE" v-show="checkSelectedSchedulStatus()">Suppressed Schedule</option>-->
                        <option value="SUPPRESSED_SCHEDULE">Suppressed Schedule</option>
                        <option value="CLIENT_NOT_AVAILABLE">Client Not Available</option>
                        <option value="TUTOR_CANCELLED">Tutor Cancelled</option>
                        <option value="COMPLETED">Completed</option>
                        <option value="OVERRIDE" v-show="checkOverrideStatus()">Override</option>
                        <option value="NOTHING">Delete</option>
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-3">
                    <label>Member :</label>
                </div>
                <div class="col-md-9">
                    <multiselect style='color:#000; font-size:12px'
                        ref="membersSelection"
                        :disabled="this.isStatusDisabled"
                        v-model="memberSelectedID"
                        :options="this.memberOptionList"
                        placeholder="-- Select A Member --"
                        track-by="id"
                        label="name"
                        @input="onChange"
                        @close="onTouch"
                        @select="onSelect"                        
                    >
                        <template slot="singleLabel" slot-scope="{ option }">
                            <strong style='color:#000; font-size:12px'>{{ option.name }}</strong>
                        </template>
                    </multiselect>
                </div>
            </div>

            <!--#SHOW - DISABLED -->
            <div class="row mt-2" v-show="this.status === 'TUTOR_CANCELLED'">
                <div class="col-md-3">Email Type:</div>
                <div class="col-md-9">
                    <!--@todo: show only if client is cancelling -->
                    <select name="cancelationType" id="cancelationType" class="form-control form-control-sm hidden" ref="cancelationType" v-model="cancelationType">                      
                        <option value="Regular Cancel" selected>Regular Cancel</option>
                        <option value="Cancel with replacement">Cancel with replacement</option>
                    </select>
                </div>
            </div> 
  
            <div id="multi-account-field-wrapper" class="row mt-2" style="display: none;">
                <div class="col-md-3">Member Account:</div>  
                <div class="col-md-9">
                    <select id="multi-account-field" class="form-control form-control-sm" v-model="multiAccountID">
                        <option v-for="account in accounts" :key="'account-' + account.member_multi_account_id"                            
                            :value="account.member_multi_account_id">
                            AC{{ account.member_multi_account_id }} - {{ account.name }}
                        </option>
                    </select>                    
                </div>
            </div>

            <!--#Email Type-->
            <div class="row mt-2" v-show="this.status === 'CLIENT_RESERVED' || this.status === 'CLIENT_RESERVED_B'">
                <div class="col-md-3">Email Type:</div>
                <div class="col-md-9">                 
                    <select name="reservationType" id="reservationType" class="form-control form-control-sm hidden" ref="reservationType" v-model="reservationType">                            
                      
                        <option value="Regular reservation" selected>Regular reservation</option>
                        <option value="For replacement">For replacement</option>
                    </select>
                </div>
            </div>
        </b-modal>

        <div class="card">
            <div class="card-header esi-card-header-title text-center">
                {{ year }} 年 {{ month }}月 {{ day}}日
            </div>

            <div class="card-body scrollable-x p-0 text-center"  style="height:680px">

                <div id="preloader" class="text-center">
                    <div class="preloader-bg">                
                        <div class="spinner-border text-primary" role="preloader-status">                  
                            <span class="visually-hidden"></span>
                        </div>
                        <div role="preloader-text">
                            <span id="preloader-text" class="small">loading schedules please wait.</span>
                        </div>
                    </div>
                </div>

                <table id="tableSchedules" class="table table-bordered table-schedules">
                    <thead>
                        <tr>
                            <th class="schedTime static">
                                <div class="bordered">
                                    <div class="class-schedule-container "></div>
                                </div>
                            </th>
                            <td class="schedTime" v-for="time in timeList" :key="time.id">
                                <div class="bordered">
                                    <div class="class-schedule-container ">
                                        <span class="flag-ph"></span>
                                        <span class="class-schedule class-schedule-start">{{time.startTime}}</span>
                                    </div>
                                    <div class="class-schedule-container">
                                        <span class="flag-jp"></span>
                                        <span class="class-schedule class-schedule-end">{{time.endTime}}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tutor in tutors" :key="tutor.id">
                            <th>
                                <div :id="tutor.user_id" class="hbordered"> 
                                    <div class="tutor-name">{{ tutor.firstname }}</div>
                                </div>
                            </th>

                            <td v-for="time in timeList" :key="time.id"> 


                                <div :id="'btnAdd-' + tutor.user_id + '-' + time.startTime"
                                    class="addSchedule SCHEDULE_ITEM" 
                                    v-show="checkButton({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime':time.startTime, 'endTime': time.endTime })">
                                    <input type="button" value="" class="btnAdd" v-b-modal.addScheduleModal 
                                    @click="openSchedule({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime })"/>
                                </div>

                                <div :id="tutor.user_id + '-' + time.startTime"
                                    v-show="checkStatus({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime':time.startTime, 'endTime': time.endTime })" 
                                    :class="getStatus({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime':  time.startTime, 'endTime': time.endTime }) + ' SCHEDULE_ITEM' ">

                                    <!--- [START] NAME OF STUDENT-->
                                    <div class="client">                                        
                                        <div v-html="getMember({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime':time.startTime, 'endTime': time.endTime })"></div>                                   
                                    </div>
                                    <!--- [END]NAME OF STUDENT-->

                                    <div class="btn-container">

                                        <div class="iReportCard2" v-show="hasQuestionnaire({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})">
                                            <a href="javascript:void(0);" @click="showQuestionnaire({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})">
                                                <img src="/images/iQuestionnaire.gif">
                                            </a>
                                        </div>


                                        <div class="iReportCard2" v-show="hasReportCard({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})">
                                            <a href="javascript:void(0);" @click="showReportCard({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})"><img src="/images/iReportCard2.gif"></a>
                                        </div>

                                        <div class="iMail2" v-show="checkMemo({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})" >
                                            <a href="javascript:void(0);" @click="getMemberMemo({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})"><img src="/images/iMail2.gif"></a>
                                        </div>

                                        
                                        <div class="iEdit"><a href="javascript:void(0);" 
                                            @click="editSchedule({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})">
                                            <img src="/images/iEdit.gif"></a>
                                        </div>
                                        <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({'tutorID':tutor.id, 'tutorUserID': tutor.user_id, 'startTime': time.startTime, 'endTime': time.endTime})"><img src="/images/iDelete.gif"></a></div>

                                        <div class="badge badge-pill badge-info" v-html="getAccountTag({'tutorUserID': tutor.user_id, 'startTime':time.startTime, 'endTime': time.endTime })"></div>                                                                           
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
            </div>

 

        </div>
    </div>
</template>



<script>
import Multiselect from 'vue-multiselect'
// register globally
Vue.component('multiselect', Multiselect)

export default {
	components: {
        
    },
    props: {
        year: { type: Number },
        month: { type: Number },
        day: { type: Number },
        //date for schedules
        schedule_items: { type: Object },
        //dateToday
        scheduled_at: { type: String },
        duration: { type: Number },
        //next day
        schedule_next_day: { type: String },
        //entities
        tutors: {type: Array },
        members: { type: Array },
        //api tokens
        csrf_token: {type: String },
        api_token: { type: String },
    },
    data() {
        return {
            accounts: [], //multi account id
            multiAccountID: null, //select model

            scrollInterval: false,

            showModal: false,
            modalBusy: false,
            selectedlessonTime: "",
            memberMemo: "",
            memberDataList: [],
            isFound : false,
            fromDay : this.scheduled_at,
            nextDay : "",
            //Member Option list
            memberOptionList: [],
            modalType: null,
            //Data
            tutorList: [],
            lessonsData: [],           
            tutorData: null,
            status: "",
            currentStatus: "",
            memberSelectedID: "",
            memberList: [],
            //Schedule Data [edit]
            currentSelectedID: "",
            currentScheduledData: [],            
            //emailType
            cancelationType: "Regular Cancel",
            reservationType: "Regular reservation",
            memberListLock: "disabled",
            isStatusDisabled: true,
            scheduledDate: null,
            shiftDuration: null,
            //List
            membersList: [],            
            schedules: [],
            //time 
            timeList: [
                {id:1, startTime: '10:00', endTime: '11:00'},
                {id:2, startTime: '10:30', endTime: '11:30'},
                {id:3, startTime: '11:00', endTime: '12:00'},
                {id:4, startTime: '11:30', endTime: '12:30'},
                {id:5, startTime: '12:00', endTime: '13:00'},
                {id:6, startTime: '12:30', endTime: '13:30'},
                {id:7, startTime: '13:00', endTime: '14:00'},
                {id:8, startTime: '13:30', endTime: '14:30'},
                {id:9, startTime: '14:00', endTime: '15:00'},
                {id:10,startTime: '14:30', endTime: '15:30'},
                {id:11, startTime: '15:00', endTime: '16:00'},
                {id:12, startTime: '15:30', endTime: '16:30'},
                {id:13, startTime: '16:00', endTime: '17:00'},
                {id:14, startTime: '16:30', endTime: '17:30'},
                {id:15, startTime: '17:00', endTime: '18:00'},
                {id:16, startTime: '17:30', endTime: '18:30'},
                {id:17, startTime: '18:00', endTime: '19:00'},
                {id:18, startTime: '18:30', endTime: '19:30'},
                {id:19, startTime: '19:00', endTime: '20:00'},
                {id:20, startTime: '19:30', endTime: '20:30'},
                {id:21, startTime: '20:00', endTime: '21:00'},
                {id:22, startTime: '20:30', endTime: '21:30'},
                {id:23, startTime: '21:00', endTime: '22:00'},
                {id:24, startTime: '21:30', endTime: '22:30'},
                {id:25 ,startTime: '22:00', endTime: '23:00'},
                {id:26 ,startTime: '22:30', endTime: '23:30'},
                {id:27 ,startTime: '23:00', endTime: '24:00'},
                {id:28 ,startTime: '23:30', endTime: '24:30'},
            ] 
        };
    },
    created() {
        //this.getMemberList();
    },
    async beforeMount() {
        this.shiftDuration  = this.duration;
        this.nextDay  = this.schedule_next_day;
        this.fromDay = this.scheduled_at;    
        
        this.setMemberListLock(); //disabler of additoinal options 
    },
    async mounted() 
    {
        let intervalId;

        this.nextDay  = this.schedule_next_day;
        this.fromDay = this.scheduled_at;       
        //@hide table 
        //let tableSchedules = document.getElementById("tableSchedules");
        //tableSchedules.style.display = "none";
        this.getSchedules(this.scheduled_at, this.shiftDuration);

 
        //this.getMemberDropDownOptionList();       

        //preloader
        let preloader = document.getElementById("preloader");
        preloader.style.display  = "block";

       this.startInterval();

    },
    methods: {      
        startInterval: function () {
            setInterval(() => {
                 this.updateSchedules(this.scheduled_at, this.shiftDuration);
                 
            }, 30000);
        },
        scrollToEnd: function() 
        {              
            this.scrollInterval = setInterval(() => {

                let chatlog  = document.getElementById('user-chatlog')
                let x = chatlog.scrollHeight;
                chatlog.scrollTop = chatlog.scrollHeight

                if (x > 0)  {
                    clearInterval(this.scrollInterval);
                }               
                console.log("finding interval");
            }, 100);                
        },
        getScheduleData(data) {            
           try {
                //23:00 - will be 1 hour advance in japan (00:00) is the time will midnight.
                //23:30 - will be 1 hour advance in japan (00:30) is the time will midnight and a half :D
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];                   
                     return lessonData;
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];
                    return lessonData;
                }                
            }
            catch(err) { return ""; }
        },
        retrieveMemo(bvModal) {
            //@todo : clean up memo
            //@todo: add loading
         
        },
        showReportCard(data) {
            try {
                let baseURI = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');

                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];                  
                    window.open(baseURI + "/admin/reportcard?scheduleitemid="+lessonData.id, "_self");                
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];                 
                    window.open(baseURI + "/admin/reportcard?scheduleitemid="+lessonData.id, "_self");                  
                }                        
            }
            catch(err) { return false; }           
        },
        hasQuestionnaire(data) {
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];    
                    if (lessonData.hasQuestionnaire === true) {
                        return true;                           
                    } else {
                        return false;
                    }                       
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];
                    if (lessonData.hasQuestionnaire === true) {
                        return true;                
                    } else {
                        return false;
                    }
                }                        
            }
            catch(err) { return false; } 
        },    
        showQuestionnaire(data) {
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];    
                    window.open(window.location.protocol + '//' + window.location.hostname + "/admin/questionnaires/"+lessonData.id, "_self");                
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];
                    window.open(window.location.protocol + '//' + window.location.hostname + "/admin/questionnaires/"+lessonData.id, "_self");   
                }                        
            }
            catch(err) { return false; } 
        },
        hasReportCard(data) {
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];    
                    if (lessonData.hasReportCard === true) {
                        return true;                           
                    } else {
                        return false;
                    }                       
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];
                    if (lessonData.hasReportCard === true) {
                        return true;                
                    } else {
                        return false;
                    }
                }                        
            }
            catch(err) { return false; } 
        },

        checkMemo(data) {
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];                  
                    if (lessonData.member_memo === '' || lessonData.member_memo === null || lessonData.member_memo === 'null') {
                        return false;
                    } else {
                        return true;
                    }                       
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];                                
                    if (lessonData.member_memo === '' || lessonData.member_memo === null || lessonData.member_memo === 'null') {
                        return false;
                    } else {
                        return true;
                    }  
                }                        
            }
            catch(err) { return false; } 
        },
        getMemberMemo(scheduleData) 
        {  
            //get current schedule data
            let memoData = this.getScheduleData(scheduleData);

            //check if booked scheduleItemID has conversations 
            axios.post("/api/getAllMemoConversations?api_token=" + this.api_token, 
            {
                method            : "POST",
                scheduleID          : memoData.id,
            })
            .then(response => 
            {                
                this.memberMemo = "";                
                this.$bvModal.show('memberMemoModal');                    
                this.selectedlessonTime = response.data.lessonTime;
                
                let replies = response.data.conversations;                
                let memberPhoto = response.data.memberPhoto;
                let tutorPhoto = response.data.tutorPhoto;

                let data = response.data.scheduleItemMemo;


                if (replies.length >= 1) 
                {
                    replies.forEach((item, index) => {
                        this.createReplyBubble(item, memberPhoto, tutorPhoto) 
                    });    
                } else {
                    let memberProfileImage = "<img src='"+memberPhoto+"' class='img-fluid border'>";
                    this.addMemberReplyBubble(memberProfileImage, data) 
                }
            
                this.$forceUpdate();      
                this.$nextTick(function()
                {     
                    this.scrollToEnd();
                });

            });
        },
        createReplyBubble(item, memberPhoto, tutorPhoto) 
        { 
            if (item.message_type === "MEMBER") {
                let memberProfileImage = "<img src='"+memberPhoto+"' class='img-fluid border'>";
                this.addMemberReplyBubble(memberProfileImage, item);
            } else {
                let teacherProfileImage =  "<img src='"+tutorPhoto+"' class='img-fluid border'>";
                this.addTeacherReplyBubble(teacherProfileImage, item);
            }    
        },
        addMemberReplyBubble(image, data) 
        {
            let content = "<div class='row mt-1'>"
                            + "<div class='col-md-3'>"+ image +" </div>"
                            + "<div class='col-md-6 pl-4'>"
                                + "<div>"
                                + "<span class='small'>"+  data.created_at +"</span>"
                                + "</div> "
                                + "<div class='teacher-speech-bubble'>" + data.message + "</div>"
                            + "</div> "
                            + "<div class='col-md-3'>&nbsp;</div>"
                            + "</div>";    

            this.memberMemo += content;

         
        },    
        addTeacherReplyBubble(image, data) 
        {
            let content ="<div class='row mt-1'>"
                + "<div class='col-md-3'>&nbsp;</div>"
                + "<div class='col-md-6 text-right'>"
                + "<div class='time-stamp'><span class='small'> " + data.created_at +"</span></div> "
                + "<div class='member-message-container'>"
                + "     <div class='member-speech-bubble'>  "+ data.message +"</div>"
                + " </div>"
                + "</div> "
                + "<div class='col-md-3'>"
                + " <div class='member-info'>"
                + image
                + " </div>"
                + "</div>"                    
                + "</div>";

            this.memberMemo += content;
     
        },
        getMultiAccountTag(data) {
            return 1;
        },  
        getAccountTag(data) 
        {
            let tag = null;

            try {
                if (data.startTime !== '23:00' || data.startTime !== '23:30') {

                    if (typeof this.lessonsData[data.tutorUserID] !== 'undefined' &&
                        typeof this.lessonsData[data.tutorUserID][this.scheduled_at] !== 'undefined' &&
                        typeof this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime] !== 'undefined') 
                    {
                        let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];
                        if (lessonData.maid !== null) {
                            tag = "AC"+lessonData.maid;
                            
                        }                          
                        
                    } else {
                        // Code to handle when the value is undefined
                    }

                } else {

                    if (typeof this.lessonsData[data.tutorUserID] !== 'undefined' &&
                        typeof this.lessonsData[data.tutorUserID][this.nextDay] !== 'undefined' &&
                        typeof this.lessonsData[data.tutorUserID][this.nextDay][data.startTime] !== 'undefined') 
                    {

                        
                        let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];
                        if (lessonData.maid !== null) {
                            tag = "AC"+lessonData.maid;
                        }                        
                        
                    } else {
                        // Code to handle when the value is undefined
                    }


                }

                return "<span class='small'>"+ tag +"</span>";

            } catch(err) { console.log(err) }
        },
        getMember(data) 
        {          
            try {
                //23:00 - will be 1 hour advance in japan (00:00) is the time will midnight.
                //23:30 - will be 1 hour advance in japan (00:30) is the time will midnight and a half :D
                if (data.startTime == '23:00' || data.startTime == '23:30') {

                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];

                    //return lessonData.status_checker;
                    //let nickname = this.memberDataList[lessonData.member_id].nickname;

                    let nickname = lessonData.nickname;
                  
                    //return "<a id='"+lessonData.id+"' href='#' onclick='openMemberTab("+lessonData.member_id+")'>"+ lessonData.nickname +"</a>";

                    if (nickname) {
                        return "<a id='"+lessonData.id+"' href='#' onclick='openMemberTab("+lessonData.member_id+")'>"+ nickname +"</a>";
                    }
                    

                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];
                    let nickname = lessonData.nickname;

                    //return "<a id='"+lessonData.id+"' href='#' onclick='openMemberTab("+lessonData.member_id+")'>"+ lessonData.nickname +"</a>";
                    if (nickname) {
                        return "<a id='"+lessonData.id+"' href='#' onclick='openMemberTab("+lessonData.member_id+")'>"+ nickname +"</a>";
                    }
                }
                //console.log(this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime])
            }
            catch(err) { return ""; }               
         },
        //check button if it has schedule then we will hide it, and if not we need to show it
        checkButton(data) {
            let isFound = false;
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];       
                    isFound = lessonData.status;
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];       
                    isFound = lessonData.status;
                }
            }
            catch(err) {
               isFound = false
            } 
            return !isFound;
        },
        //check reservation status and show if available
        checkStatus(data) {
            //console.log(data.tutorID)
            let isFound = false;
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];       
                    isFound = lessonData.status;
                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];       
                    isFound = lessonData.status;
                }
            }
            catch(err) {
               isFound = false
            } 
            return isFound;
        },
        getStatus(data) 
        {            
            try {
                //23:00 - will be 1 hour advance in japan (00:00) is the time will midnight.
                //23:30 - will be 1 hour advance in japan (00:30) is the time will midnight and a half :D
                if (data.startTime == '23:00' || data.startTime == '23:30') {

                    let lessonData = this.lessonsData[data.tutorUserID][this.nextDay][data.startTime];
                    return lessonData.status;

                } else {
                    let lessonData = this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime];
                    return lessonData.status;
                }
                //console.log(this.lessonsData[data.tutorUserID][this.scheduled_at][data.startTime])
            }
            catch(err) { return ""; }                
        },
        scheduleExists(scheduleData) 
        {
            return this.checkStatus();
        },        
        openSchedule(tutorData) 
        {
            this.modalBusy = false;
            
            this.modalType = "save";
            //console.log(tutorData);            
            //console.log("open schedule");
            this.$bvModal.show("schedulesModal");
            this.tutorData = tutorData;

            //UPDATE (MAY 19, 2021): 
            //Set the default status dropdown option  "TUTOR_SCHEDULED" default 
            this.status = "TUTOR_SCHEDULED";
        },
        hideModal() {
            //console.log("hide modal");
            //update the schedules when you hide it?
            //this.getSchedules(this.scheduled_at, this.shiftDuration);
            this.currentStatus = "";
        },
        resetModal() {
            //console.log("reset modal"); //this will reset every time it closes.
            this.memberSelectedID = "";
            this.status = "";
            this.isStatusDisabled = true; 
        },
        handleOk(bvModalEvt) 
        {
            this.modalBusy = true;            
            
            if (this.status === "NOTHING") this.confirmDelete(this.tutorData);
            else if(this.status === "OVERRIDE") this.confirmOverride(this.tutorData);
            else {
                //console.log("the id selected => " + this.memberSelectedID.id)

                if (this.status === 'CLIENT_RESERVED' || this.status === 'CLIENT_RESERVED_B') 
                { 

                    if (this.memberSelectedID.id === null || this.memberSelectedID.id === "" 
                        || this.memberSelectedID.id == undefined || this.memberSelectedID.id == "undefined") 
                    {
                        alert ("Please select member");
                        this.modalBusy = false;

                    } else {

                        //check if booked schedules is more than 15 items
                        axios.post("/api/getBookScheduledCount?api_token=" + this.api_token, 
                        {
                            method              : "POST",               
                            memberID          : this.memberSelectedID.id,
                        })
                        .then(response => 
                        {

                            if (response.data.totalScheduledItem >= 15)  
                            {
                                if (confirm('Attention! 15 reservations reached for this member, Are you sure you want override and save this schedule?')) {
                                    // Save it!
                                    console.log('Thing was saved to the database.');

                                    if (this.modalType === 'save') 
                                    {
                                        console.log ("saving...")
                                        this.setTutorSchedule();
                                    } else {
                                        console.log ("updating...")                                
                                        this.updateTutorSchedule();
                                    }
                                } else {
                                    // Do nothing!
                                    console.log('Thing was not saved to the database.');

                                    //close
                                    this.$bvModal.hide("schedulesModal");
                                }
                            } else {
                                if (this.modalType === 'save') 
                                {
                                    console.log ("saving...")                             
                                    this.setTutorSchedule();
                                } else {                   
                                    console.log ("updating...")                            
                                    this.updateTutorSchedule();
                                }                        
                            }
                        });
                    }

                    /*
                    this.$nextTick(function()
                    {


                    });*/
                    
            
                } else {
                    if (this.modalType === 'save') 
                    {                       
                        //this.checkBookedScheduleLimit();
                        this.setTutorSchedule();

                    } else {                   
                     
                        //this.checkBookedScheduleLimit();
                        this.updateTutorSchedule();
                    }                        
                }
            }            
            bvModalEvt.preventDefault();
        }, 
        checkBookedScheduleLimit() {

        },
        showMultiAccountField(memberID) {
            //@check if there is
            $('#multi-account-field-wrapper').show();
         

            axios.post("/api/getMultiAccountOptions?api_token=" + this.api_token, 
            {
                method            : "POST",
                memberID          : memberID,
            })
            .then(response => 
            { 
                this.accounts = response.data.accounts;
                console.log(response.data.accounts);

                // Find the default account and set its ID as the initial value
                const defaultAccount = this.accounts.find(account => account.is_default);
                if (defaultAccount) {
                    this.multiAccountID = defaultAccount.member_multi_account_id;
                }

            });
        },
        onChange (value) {
            this.multiAccountID = null;
            this.showMultiAccountField(value.id);

            //changing modal selection
            console.log("on change user:  " + value.id);

            try {                
                this.currentSelectedID = value.id;
               
            }   
            catch(err) { 
                //console.log("no value");
                this.currentSelectedID = null; 
            }            
        },
        onSelect (option) {
            //console.log (option)
            
        },
        onTouch () {
           // console.log("touched")
        },
        checkOverrideStatus() {
            //disable if the status is not client not available
             if (this.currentStatus != 'CLIENT_NOT_AVAILABLE') {
                 return false;
             } else {
                 return true;
             }
        },
        checkSelectedSchedulStatus() 
        {
            if (this.modalType == "edit") 
            {

                if (this.currentStatus === "") {
                    return true;
                }

                if (this.currentStatus === 'CLIENT_RESERVED' || this.currentStatus === 'CLIENT_RESERVED_B'
                    || this.currentStatus === 'SUPPRESSED_SCHEDULE' 
                    || this.currentStatus === 'CLIENT_NOT_AVAILABLE'
                    || this.currentStatus === 'TUTOR_CANCELLED' || this.currentStatus === 'COMPLETED'
                ) {
                    return false;
                } else {                
                    return true;
                }
                
                
            } else {
                //modal is create new schedule
                return true;
            }
         
        },
        editSchedule(scheduleData) 
        {

            //show the modal first and update the values below             
            this.$bvModal.show("schedulesModal");            
            this.modalType = "edit";

            //get current schedule data
            this.currentScheduledData = this.getScheduleData(scheduleData);

            console.log(this.currentScheduledData);

            this.tutorData = scheduleData;
            this.status = this.currentScheduledData.status;

            this.currentSelectedID = this.currentScheduledData.member_id;            
            this.currentStatus = this.currentScheduledData.status;

            let memberData = this.memberOptionList[this.currentScheduledData.member_id];
            
            let memberIDFullName = "";



            if (this.currentScheduledData.member_id !== null && this.currentScheduledData.member_id !== '') 
            {         
                let nickname = memberData.nickname;
                let firstname = memberData.firstname;
                let lastname = memberData.lastname;  
                memberIDFullName =  this.currentScheduledData.member_id + " " + firstname  + " " + lastname;  
                this.memberSelectedID = { id: this.currentScheduledData.member_id , 'name': memberIDFullName };   

                this.modalBusy = false;
            } else {
                this.modalBusy = false;
                this.memberSelectedID = { id: "" , 'name': "-- Select A Member --" };
            }
            
           
            
            //this.isStatusDisabled = false;
            /** The reservationType - the live does not have this, so it will be blank; **/
            //this.cancelationType = member.email_type;
            //this.reservationType = member.email_type;

            this.setMemberListLock();
        },
        setTutorSchedule() 
        {
            //get the selected id
            let memberData = {
                id: this.memberSelectedID
            };           
            
            //console.log("save ?? "  + this.memberSelectedID.id);
            if (this.status === 'CLIENT_RESERVED' || this.status === 'CLIENT_RESERVED_B') 
            {                
                if (this.memberSelectedID === "" || this.memberSelectedID === null) {
                    alert ("Please select Member");
                    this.modalBusy = false;
                    return false;                
                }
            }
            
            if (this.scheduleExists(this.tutorData)) 
            {                
                alert ("this schedule is already booked");
                this.modalBusy = false;
                return false;
            } 

            axios.post("/api/create_tutor_schedule?api_token=" + this.api_token, 
            {
                method              : "POST",               
                memberData          : memberData.id,
                scheduled_at        : this.scheduled_at,
                shiftDuration       : this.shiftDuration,
                tutorData           : this.tutorData,
                status              : this.status,
                reservationType     : this.reservationType,
                cancelationType     : this.cancelationType,
                multiAccountID      : this.multiAccountID
            })
            .then(response => 
            {
                
                //hide schedule
                this.modalBusy = false;
                this.$bvModal.hide("schedulesModal");


                if (response.data.success === true) 
                {
                    
                    this.$nextTick(function()
                    {
                        let tutorUserID = response.data.tutorData.tutorUserID;
                        let startTime = response.data.tutorData.startTime;
                        let scheduled_at = this.scheduled_at;

                        //@todo: next day detect
                        if (startTime == '23:00' || startTime == '23:30') {
                             scheduled_at = this.nextDay;
                        }
                       
                        if (typeof this.lessonsData[tutorUserID] === 'undefined') {
                            this.lessonsData[tutorUserID] = {}  
                        }

                        if (typeof this.lessonsData[tutorUserID][scheduled_at] === 'undefined') {
                            this.lessonsData[tutorUserID][scheduled_at] = {}
                        }             

                        //[updated] get memberdata
                        let firstname  = "";
                        let lastname = "";
                        let nickname = "";

                        let memberData = this.memberDataList[response.data.memberData.id];

                        console.log(response.data.memberData.id);

                       
                        if (response.data.memberData.id !== '') {      
                            
                            /*
                           // nickname = memberData.nickname;
                            //firstname = memberData.firstname;
                            //lastname = memberData.lastname;
                            */                            

                            nickname = response.data.memberData.nickname;
                            firstname = response.data.firstname;
                            lastname = response.data.lastname;                                                        
                        } 
                                     
                        

                        this.lessonsData[tutorUserID][scheduled_at][startTime] = {
                            'id': response.data.scheduleItemID,
                            "status": this.status,                      
                            //member info
                            'member_id': response.data.memberData.id,
                            'firstname': firstname,
                            'lastname':  lastname,
                            'nickname':  nickname,
                            'member_memo': null,
                            'maid': response.data.maid
                        }

                        //set the schedule to display
                        let schedule = document.getElementById(tutorUserID + "-" + startTime);
                        schedule.style.display = "block";
                        schedule.classList.add(this.status);

                        //hide the add button
                        let addButton = document.getElementById("btnAdd-" + tutorUserID + "-" + startTime);
                        addButton.style.display = "none";
                        
                        
                        //preloader
                        let preloader = document.getElementById("preloader");                    
                        let preloaderText = document.getElementById("preloader-text");
                        preloader.style.display  = "none";                      
                        preloaderText.textContent  = "refreshing schedules"; 

                        //this is repitive but this will allow the user to see updated from other admin??
                       // this.getSchedules(this.scheduled_at, this.shiftDuration);
                        this.$forceUpdate();
                    });
      
                } else {           
                    
                    this.modalBusy = false;
                    alert (response.data.message);    
                }

                //@note: auto refresh... (this is only used when there is duplicate)
                if (response.data.refresh === true) 
                {
                    this.$nextTick(function()
                    {  
                        this.modalBusy = false;
                        this.lessonsData = response.data.tutorLessonsData;
                        this.$forceUpdate(); 
                    });
                }
                
			}).catch(function(error) {
                this.modalBusy = false;
                alert("Error: setTutorSchedule - " + error);                
			});
        },        
        updateTutorSchedule() 
        {
            //console.log("check selected " + this.currentSelectedID);

            if (this.status === 'CLIENT_RESERVED' || this.status === 'CLIENT_RESERVED_B') 
            { 

                if (this.currentSelectedID === "" || this.currentSelectedID === null) 
                {
                    alert ("Please select Member");
                    this.modalBusy = false;
                    return false;                
                }
            }

            //get the selected member id
            let memberData = {
                id: this.memberSelectedID
            }; 

            axios.post("/api/update_tutor_schedule?api_token=" + this.api_token, 
            {
                method              : "POST",               
                memberData          : memberData.id,
                scheduledItemData   : this.currentScheduledData,
                scheduled_at        : this.scheduled_at,
                shiftDuration       : this.shiftDuration,
                tutorData           : this.tutorData,
                status              : this.status,
                reservationType     : this.reservationType,
                cancelationType     : this.cancelationType,
            })
            .then(response => 
            {
                //hide schedule
                this.modalBusy = false;
                this.$bvModal.hide("schedulesModal");

                if (response.data.success === true) 
                {

                    let tutorUserID =  response.data.tutorData.tutorUserID;
                    let tutorID = response.data.tutorData.tutorID;
                    let startTime = response.data.tutorData.startTime;
                    let scheduled_at = this.scheduled_at;

                    //@todo: next day detect
                    if (startTime == '23:00' || startTime == '23:30') {
                        scheduled_at = this.nextDay;
                    }
                 
                    if (typeof this.lessonsData[tutorUserID] === 'undefined') {
                        this.lessonsData[tutorUserID] = {}  
                    }

                    if (typeof this.lessonsData[tutorUserID][scheduled_at] === 'undefined') {
                        this.lessonsData[tutorUserID][scheduled_at] = {}
                    }

                    this.lessonsData[tutorUserID][scheduled_at][startTime] = {
                        'id': response.data.scheduleItemID,
                        "status": this.status,                      
                        //member info
                        'member_id': response.data.memberData.id,
                        'firstname': response.data.memberData.firstname,
                        'lastname': response.data.memberData.lastname,
                        'nickname': response.data.memberData.nickname,
                        'member_memo': response.data.member_memo,
                    }

                    //set the schedule to display
                    let schedule = document.getElementById(tutorUserID + "-" + startTime);
                    schedule.style.display = "block";
                    schedule.classList.add(this.status);

                    //hide the add button
                    let addButton = document.getElementById("btnAdd-" + tutorUserID + "-" + startTime);
                    addButton.style.display = "none";

                    //preloader
                    let preloader = document.getElementById("preloader");                    
                    let preloaderText = document.getElementById("preloader-text");
                    preloader.style.display  = "none";                      
                    preloaderText.textContent  = "refreshing schedules";                    

                    this.getSchedules(this.scheduled_at, this.shiftDuration);
                    this.$forceUpdate();                    
                } 
                else {            
                    this.modalBusy = false;        
                    alert (response.data.message);                   
                }


                //@note: auto refresh... (this is only used when there is duplicate)
                if (response.data.refresh === true) 
                {
                    this.$nextTick(function()
                    {  
                        this.modalBusy = false;
                        this.lessonsData = response.data.tutorLessonsData;
                        this.$forceUpdate(); 
                    });
                }

			}).catch(function(error) {
                this.modalBusy = false;
                alert("Error : updateTutorSchedule - " + error);                
			});            
        },
        getMemberDropDownOptionList() 
        {
            var options = [];

            axios.post("/api/get_members_dropdown_options?api_token=" + this.api_token, 
            {
                method              : "POST",
                message             : "",                
            }).then(response => {

                if (response.data.success === true) 
                {              
                    var options = [];

                    this.$nextTick(function()
                    {  

                        response.data.members.forEach(function (member, index) {

                            //options.push({'id': member.uid, 'name': member.uid + " " + member.fn + " "+ member.ln  });

                            options[member.uid] = {
                                                            'id': member.uid, 
                                                            'firstname': member.fn,
                                                            'lastname': member.ln,
                                                            'name': member.uid + " " + member.fn + " "+ member.ln
                                                        } ;                            

                        });

                        this.memberOptionList = options;
                        this.$forceUpdate();                        
                    });
                    
                }

            });    


        },
        getMemberList() {
            
            axios.post("/api/get_members?api_token=" + this.api_token, 
            {
                method              : "POST",
                message             : "",
                
            }).then(response => {

                if (response.data.success === true) 
                {
                    this.$nextTick(function()
                    {  
                        this.memberList = response.data.members;                    
     
                        let memberData = [];

                        //var options = [];


                        this.memberList.forEach(function (member, index) 
                        {   
                            memberData[member.uid] = {
                                                            'id': member.uid, 
                                                            'firstname': member.fn,
                                                            'lastname': member.ln,
                                                            'name': member.uid + " " + member.fn + " "+ member.ln, 
                                                            'nickname': member.nn 
                                                        } ;

                            //options.push({'id': member.uid, 'name': member.uid + " " + member.fn + " "+ member.ln  });        
                        });



                        this.memberDataList = memberData;
                        this.$forceUpdate(); 
                    });
                } 
                else {                    
                    alert (response.data.message);                   
                }

			}).catch(function(error) {
                console.log("Error: Get Member List - " + error);                
			});               
        },
        updateSchedules(scheduled_at, shiftDuration) 
        {
            let preloader = document.getElementById("preloader");  

            axios.post("/api/get_schedules?api_token=" + this.api_token, 
            {
                method              : "POST",
                scheduled_at        : scheduled_at,
                shift_duration      : shiftDuration

            }).then(response => {
                if (response.data.success === true) 
                {
                    this.$nextTick(function()
                    {                          
                        //tableSchedules.style.display = "block";
                        preloader.style.display = "none";
                        this.lessonsData = response.data.tutorLessonsData;
                        //this.tutorList = response.data.tutorLessonsData.tutors;
                        this.$forceUpdate(); 
                    });
                } 
                else {                    
                    alert (response.data.message);                   
                }
			}).catch(function(error) {
                console.log("Error " + error);                
			}); 
        },        
        getSchedules(scheduled_at, shiftDuration) 
        {
            let tableSchedules = document.getElementById("tableSchedules");
            let preloader = document.getElementById("preloader");  

            axios.post("/api/get_schedules?api_token=" + this.api_token, 
            {
                method              : "POST",
                scheduled_at        : scheduled_at,
                shift_duration      : shiftDuration

            }).then(response => {

                this.getMemberDropDownOptionList();

                if (response.data.success === true) 
                {
                    this.$nextTick(function()
                    {                          
                        //tableSchedules.style.display = "block";
                        preloader.style.display = "none";
                        this.lessonsData = response.data.tutorLessonsData;
                        //this.tutorList = response.data.tutorLessonsData.tutors;
                        this.$forceUpdate(); 
                    });
                } 
                else {                    
                    alert (response.data.message);                   
                }
			}).catch(function(error) {
                console.log("Error " + error);                
			});            

        },
        confirmOverride(scheduleData) {
            if (confirm('This will mark the schedule inactive, Are you sure you want to override this reservation?')) {
                this.overrideSchedule(this.currentScheduledData);
            } else {
                // Do nothing!
            }
        },        
        confirmDelete(scheduleData) 
        {
            if (confirm('Are you sure you want to delete this reservation?')) {
                this.deleteSchedule(scheduleData);
            } else {
                // Do nothing!
            }
        },
        overrideSchedule(scheduleData) 
        {           
            axios.post("/api/override_tutor_schedule?api_token=" + this.api_token,             
            {
                method             : "POST",             
                scheduled_at       : this.scheduled_at,
                shiftDuration      : this.shiftDuration,                  
                scheduleData       : scheduleData,
            })
            .then(response => 
            {
                if (response.data.success === true) 
                {
                    this.lessonsData = response.data.tutorLessonsData;
                    this.$bvModal.hide("schedulesModal");
                } else {                   
                    alert (response.data.message);
                    this.$bvModal.hide("schedulesModal"); 
                }
			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                this.$bvModal.hide("schedulesModal"); 
			});              
        },        
        deleteSchedule(scheduleData) 
        {           
            axios.post("/api/delete_tutor_schedule?api_token=" + this.api_token,             
            {
                method             : "POST",             
                scheduled_at       : this.scheduled_at,
                shiftDuration      : this.shiftDuration,                  
                scheduleData       : scheduleData,
            })
            .then(response => 
            {
                if (response.data.success === true) 
                {
                    this.lessonsData = response.data.tutorLessonsData;
                    this.$bvModal.hide("schedulesModal");
                } else {                   
                    alert (response.data.message);
                    this.$bvModal.hide("schedulesModal"); 
                }
			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                this.$bvModal.hide("schedulesModal"); 
			});              
        },
        setMemberListLock() {        
            
            if (this.modalType == "edit" &&  this.status === 'CLIENT_NOT_AVAILABLE') {
                this.isStatusDisabled = true;  
            } else if (this.status === "TUTOR_CANCELLED") {
                this.membersSelection = 0;
                this.isStatusDisabled = true;                
            } else if (this.status === 'CLIENT_RESERVED' 
                || this.status === "CLIENT_RESERVED_B" 
                //|| this.status === "SUPPRESSED_SCHEDULE"                
                //|| this.status === "TUTOR_CANCELLED"
                || this.status === 'CLIENT_NOT_AVAILABLE'
                || this.status === "NOTHING")
            {
                //the list of members with the status above will enabled for editing
                this.isStatusDisabled = false;
            } else {
                this.membersSelection = 0;
                this.isStatusDisabled = true;
            }           
        }
    }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .modal-title {
        font-size: 15px;
    }

    #preloader {
        position: absolute;
        top: 224px;
        z-index: 99999;
        margin-left: auto;
        text-align: center;
        width: 100%;
    }

    .preloader-bg {
        background-color: white;
        border: 1px solid #0076be;
        padding: 15px;
        width: 200px;
        height: 85px;
        margin: auto;
        border-radius: 25px;
        color: #0076be;        
    }


    .table td {
        font-size: 11px;
    }

    .table-schedules td.schedTime {
        background: #d0e8f7;
        text-align: center;
        font: bold 12px Arial;
        vertical-align: top;
        width: 70px;
        height: 30px;
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        top: -1px;
        padding-top:0px;
        padding-left:0px;
        padding-right:0px;
        padding-bottom:0px;
        z-index: 99;
    }

    .table-schedules th.schedTime .bordered{
        background: #d0e8f7;
        height: 40px;
        padding: 0px;
        border-bottom: 3px solid #72add2;
        border-right: 3px solid #72add2;        
    }
    

    .table-schedules td .bordered {
        border-top: 0px;
        border-left: 0px;
        border-bottom: 3px solid #72add2;
        border-right: 1px solid #fff;
        width: 100%;
        padding-top: 5px;
        padding-bottom: 5px; 
        height: 40px;

    }

     .table-schedules thead th {
        height: 40px;
        /*border-bottom: 3px solid #72add2;*/
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        background-color: #fff;
        z-index: 999;
        top: -1px;
        padding:0px;
     }

    .table-schedules tbody th, .static {
        position: -webkit-sticky; /* for Safari */
        position: sticky;
        left: -1px;
        background-color: #fff;
        border: 0px;
        padding: 0px;
        margin: 0px;
        height: 60px;        
        z-index: 100;
    }

    .table-schedules tbody th .hbordered {
        border-top: 0px;
        border-left: 0px;
        border-right: 3px solid #72add2;
        border-bottom: 1px solid #72add2;
        width: 100%;
        height: 100%;
        padding-left: 5px;
        padding-right: 5px;
    }

    .table-schedules td {
        vertical-align: top;
        height: 39px;
        width: 110px;
    }

    .class-schedule-container {
        width: 100%;
        text-align: center;
    }

    .iEdit, .iDelete, .iMail2, .iReportCard2 {
        display: inline-block;
        width: 12px;
        margin: 2px 3px 2px;
    }
    

    .btnAdd {
        padding: 1px 2px 1px 12px;
        color: #000000;
        background: url(/images/btnAdd.png) 4px 3px no-repeat #d0d0d0;
        cursor: pointer;
        border: none;
        font-size: 8px;
        border-right: 1px solid #bbbbbb;
        border-bottom: 1px solid #bbbbbb;
        width: 20px;
        height: 17px;
        float: right;
    }

    .client {
        text-align: center;
        height: 13px;
    }

    .client a {
        font-size: 12px;
        line-height: 10px;
        text-decoration: none;
        color: #c60000;
        text-align:center;
        vertical-align: text-bottom;
    }

    .client a:hover {
        color: #6e0000;
    }

    .btn-container {
        margin: 3px 0 0 0;
        padding: 2px 0 0 0px;
        background: #ececec;
        text-align: center;      
    }

    .btn-padded {
        margin: 13px 0 0 0;
        padding: 2px 0 0 0px;
        background: #ececec;
        text-align: center;      
    }

    /** STATUS **/
    .SCHEDULE_ITEM {
        width: 110px;
    }

    .nothing, .NOTHING {
        background: #ffffff;
        border: 1px solid #c3c3c3;
        padding: 2px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;     
    }    

    .tutor_scheduled, .TUTOR_SCHEDULED {
        background: #aae966;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;        
    }

    .client_reserved, .CLIENT_RESERVED {
        background: #f9e15f;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;

    }

    .client_reserved_b, .CLIENT_RESERVED_B {
        background: #f9e15f;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;
    }

    .tutor_cancelled, .TUTOR_CANCELLED {
        background: #b0b0b0;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;             
    }
 
    .client_not_available, .CLIENT_NOT_AVAILABLE {

        background: #f25757;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;              
    }

    .suppressed_schedule, .SUPPRESSED_SCHEDULE {
        background: #55c7f0;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;         
    }

    .completed, .COMPLETED {
        background: #f6b05d;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;        
        margin: 2px;           
    }

    .multiselect__input {
        color: #adadad;
        margin-bottom: 1px;
        padding-top: 0px;
        font-size: 12px !important;
        padding: 0px;
        margin: 0px; 
    }
    
    .multiselect__placeholder {
        color: #495057;
        display: inline-block;
        margin-bottom: 1px;
        padding-top: 0px;
        font-size: 12px;
        padding: 0px;
        margin: 0px;
    }

    /*Modal Memo Reply*/
    .member-speech-bubble {
        position: relative;
        background: #00ff91;
        border-radius: .4em;
        padding-right:30px;
        position: relative;
        background: #00ff91;
        border-radius: .4em;
        padding: 10px 20px 10px;
        float: right;
        margin: 0px -10px 0px;
        text-align: right;
        max-width: 280px;
        overflow-wrap: break-word;           
    }

    .member-speech-bubble:after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        width: 0;
        height: 0;
        border: 18px solid transparent;
        border-left-color: #00ff91;
        border-right: 0;
        border-bottom: 0;
        margin-top: -10px;
        margin-right: -14px;
    }

    #teacherReplies .row 
    {
        /*border: 1px dotted rgb(0, 132, 255);*/
        margin: 5px 0px 15px;
        padding: 3px 0px 8px;
    }


    .teacher-speech-bubble {
        position: relative;
        background: #3e4042;
        border-radius: .4em;
        color: #fff;
        position: relative;
        border-radius: .4em;
        padding-right: 30px;
        margin: 0px 0px 5px;
        padding: 10px;
        display: inline-block;  
        max-width: 280px;
        overflow-wrap: break-word;               
    }

    .teacher-speech-bubble:after {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 0;
        height: 0;
        border: 20px solid transparent;
        border-right-color: #3e4042;
        border-left: 0;
        border-bottom: 0;
        margin-top: -10px;
        margin-left: -15px;
    }    
</style>
