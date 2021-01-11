<!--
@Description: This will add the tutor schedule for the member(student)
@Developer: Roy
@Company: ESucess Inc.
-->
<template>
    <div id="scheduleItemModal">

        <b-modal  id="schedulesModal" 
            title="Schedule Lesson"
            button-size="sm"
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
                        <option value="">Please Select A Status</option>
                        <option value="TUTOR_SCHEDULED">Tutor Scheduled</option>
                        <option value="CLIENT_RESERVED">Client Reserved</option>
                        <option value="CLIENT_RESERVED_B">Client Reserved B</option>
                        <option value="TUTOR_CANCELLED">Tutor Cancelled</option>
                        <option value="NOTHING">Nothing</option>
                        <option value="CLIENT_NOT_AVAILABLE">Client Not Available</option>
                        <option value="SUPPRESSED_SCHEDULE">Suppressed Schedule</option>
                        <option value="COMPLETED">Completed</option>
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
                        <!--<option value="">-- Sect Email Type --</option>-->
                        <option value="Regular Cancel">Regular Cancel</option>
                        <option value="Cancel with replacement"
                            >Cancel with replacement</option
                        >
                    </select>
                </div>
            </div> 
  
            <!--#Email Type-->
            <div class="row mt-2" v-show="this.status === 'CLIENT_RESERVED' || this.status === 'CLIENT_RESERVED_B'">
                <div class="col-md-3">Email Type:</div>
                <div class="col-md-9">                 
                    <select name="reservationType" id="reservationType" class="form-control form-control-sm hidden" ref="reservationType" v-model="reservationType">                            
                        <!--<option value="">-- Sect Email Type --</option>-->
                        <option value="Regular reservation">Regular reservation</option>
                        <option value="For replacement">For replacement</option>
                    </select>
                </div>
            </div>


        </b-modal>

        <div class="card">
            <div class="card-header esi-card-header-title text-center">
                {{ year }} 年 {{ month }}月 {{ day}}日
            </div>

            <div class="card-body scrollable-x p-0">
                <table class="table table-bordered table-schedules">
                    <tr>
                        <td class="schedTime"></td>
                        <td class="schedTime" v-for="time in timeList" :key="time.id">
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span class="class-schedule class-schedule-start">{{time.startTime}}</span>
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end">{{time.endTime}}</span>
                            </div>
                        </td>
                    </tr>

                    <tr v-for="tutor in tutors" :key="tutor.id">
                        <td class="">
                            <div style="width:125px">
                                {{ tutor.id }} |
                                {{ tutor.user_id }}  |
                                {{ tutor.firstname }}
                            </div>
                        </td>

                        <td class="" v-for="time in timeList" :key="time.id">
                            <!--{{ time.startTime  }} - {{ time.endTime}}-->
                            <input v-show="checkButton({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })" type="button" value="" class="btnAdd" v-b-modal.addScheduleModal 
                                @click="openSchedule({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"/>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'TUTOR_SCHEDULED' })" class="tutor_scheduled">                              
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>                                   
                                </div>                                
                                <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'TUTOR_SCHEDULED'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'TUTOR_SCHEDULED'})"><img src="/images/iDelete.gif"></a></div>
                                </div>                                
                            </div>
                            
                            <div v-show="checkStatus({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'CLIENT_RESERVED' })" class="client_reserved">
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>                                   
                                </div>
                                <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'CLIENT_RESERVED'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'CLIENT_RESERVED'})"><img src="/images/iDelete.gif"></a></div>
                                </div>
                            </div> 

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'CLIENT_RESERVED_B' })" class="client_reserved_b">
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>
                                </div>
                                <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'CLIENT_RESERVED_B'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'CLIENT_RESERVED_B'})"><img src="/images/iDelete.gif"></a></div>  
                                </div>
                            </div>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'TUTOR_CANCELLED' })" class="tutor_cancelled">
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>
                                </div>                            
                                <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'TUTOR_CANCELLED'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'TUTOR_CANCELLED'})"><img src="/images/iDelete.gif"></a></div>  
                                </div>
                            </div>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'NOTHING' })" class="nothing">
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>
                                </div>                            
                                <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'NOTHING'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'NOTHING'})"><img src="/images/iDelete.gif"></a></div>  
                                </div>
                            </div>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'CLIENT_NOT_AVAILABLE' })" class="client_not_available">
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>
                                </div>                            
                               <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'CLIENT_NOT_AVAILABLE'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'CLIENT_NOT_AVAILABLE'})"><img src="/images/iDelete.gif"></a></div>  
                                </div>
                            </div> 

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'SUPPRESSED_SCHEDULE' })" class="suppressed_schedule">
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>
                                </div>                                
                                <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'SUPPRESSED_SCHEDULE'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'SUPPRESSED_SCHEDULE'})"><img src="/images/iDelete.gif"></a></div>  
                                </div>
                            </div> 

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'COMPLETED' })" class="completed">
                                <div class="client">
                                    <div v-html="getMember({ tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"></div>
                                </div>                            
                                <div class="btn-container">
                                    <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'COMPLETED'})"><img src="/images/iEdit.gif"></a></div>
                                    <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'COMPLETED'})"><img src="/images/iDelete.gif"></a></div>  
                                </div>
                            </div>
                        </td>
                    </tr>

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

        //entities
        tutors: {type: Array },
        members: { type: Array },


        //api tokens
        csrf_token: {type: String },
        api_token: { type: String },
    },
    data() {
        return {
            memberOptionList: [],
            modalType: null,

            //Data
            lessonsData: [],           
            tutorData: null,
            status: "",
            memberSelectedID: "",
            memberList: [],
            
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
    async beforeMount() {
       
        this.setMemberListLock(); //disabler of additoinal options        
        this.lessonsData = this.schedule_items;
        
        console.log("Lessons Mounted : ", this.schedules);

        this.shiftDuration  = this.duration;

        //@todo: ajax load schedules (DONE)
        this.schedules = this.getSchedules(this.scheduled_at, this.shiftDuration); 

        this.memberList = this.getMemberList();
               
    },
    async mounted() {

        /* transferred to before mount 
        this.setMemberListLock(); //disabler of additoinal options        
        this.lessonsData = this.schedule_items;
        
        console.log("Lessons Mounted : ", this.schedules);
        this.shiftDuration  = this.duration;

        //optionLists of Members
        var options = [];
        this.members.forEach(function (member, index) 
        {   
            options.push({'id': member.id, 'name': member.user_id + " " + member.first_name + " "+ member.last_name  });        
        });
        this.memberOptionList = options;
        */


    },
    methods: {
        getMemberData(scheduleData) {
            let data = null;
            if (this.lessonsData[scheduleData.tutorID] === undefined || this.lessonsData[scheduleData.tutorID] == 'undefined') {
                data = null;       
            } else {
                let lessons = this.lessonsData[scheduleData.tutorID];
                lessons.forEach(function (lesson, index) 
                {
                    if (lesson.startTime === scheduleData.startTime && lesson.endTime === scheduleData.endTime) 
                    {
                        data = lesson;                        
                    }
                });
            }
            return data;      
        },
        getMember(scheduleData) {
            let member = null;
            let memberID = null;

            if (this.lessonsData[scheduleData.tutorID] === undefined || this.lessonsData[scheduleData.tutorID] == 'undefined') {
                memberID = null;
                member  = null;
            } else {
                let lessons = this.lessonsData[scheduleData.tutorID];
                lessons.forEach(function (lesson, index) 
                {
                    if (lesson.startTime === scheduleData.startTime && lesson.endTime === scheduleData.endTime) 
                    {
                        memberID = lesson.member_id;
                        member = lesson.member_name_en;
                    }
                });
            }
            return "<a href='/admin/member/"+ memberID +"'>"+ member + "</a>";
         },
        //check button if it has schedule then we will hide it, and if not we need to show it
        checkButton(data) {
            let show = true;
            $.each(this.lessonsData[data.tutorID], function(key, value) 
            {
                if (
                    //value.tutor_id === data.tutorID &&                   
                    value.startTime === data.startTime && 
                    value.endTime === data.endTime &&
                    value.scheduled_at === this.scheduled_at
                )
                {
                    //it has been found in the lesson data array, we will hide the add schedule button
                    show = false;
                }
            });
            return show; 
        },
        //check reservation status and show if available
        checkStatus(data) 
        { 
            let isFound = false;
            $.each(this.lessonsData[data.tutorID], function(key, value) 
            {
                //console.log({value}, {data});
                if (
                   // value.tutor_id === data.tutorID &&
                    value.status === data.status && 
                    value.startTime === data.startTime && 
                    value.endTime === data.endTime &&
                    value.scheduled_at === this.scheduled_at
                )
                {
                    isFound = true;                   
                }
            });
            return isFound;                    
        },
        openSchedule(tutorData) 
        {
            this.modalType = "save";
            console.log(tutorData);
            this.$bvModal.show("schedulesModal");
            this.tutorData = tutorData;
        },
        editSchedule(scheduleData) {
            //set tutor
            this.tutorData = scheduleData;

            this.modalType = "edit";
            let member = this.getMemberData(scheduleData); 
            this.$bvModal.show("schedulesModal");            
            this.status = scheduleData.status;

            console.log(member);

            this.memberSelectedID = [{ id: member.member_id , 'name': member.member_name_en }];

            //this.isStatusDisabled = false;
            this.cancelationType = member.email_type;
            this.reservationType = member.email_type;
            this.setMemberListLock();
        },         
        hideModal() {
            console.log("hide modal");
        },
        resetModal() {
            console.log("reset modal"); //this will reset every time it closes.
            this.memberSelectedID = "";
            this.status = "";
            this.isStatusDisabled = true; 
        },
        handleOk(bvModalEvt) 
        {
            if (this.status === "NOTHING")
            {
                this.confirmDelete(this.tutorData);
            } else {            
                if (this.modalType === 'save') {
                    console.log ("saving...")            
                    this.setTutorSchedule();
                } else {
                    this.updateTutorSchedule();
                }
            }
            bvModalEvt.preventDefault();
        },
        scheduleExists(scheduleData) 
        {
            let scheduleGate = false;
            if (this.lessonsData[scheduleData.tutorID] === undefined || this.lessonsData[scheduleData.tutorID] == 'undefined') {
                scheduleGate  = false;
            } else {
                let lessons = this.lessonsData[scheduleData.tutorID];
                lessons.forEach(function (lesson, index) 
                {
                    if (lesson.startTime === scheduleData.startTime && lesson.endTime === scheduleData.endTime) 
                    {
                        scheduleGate = true;
                    }
                });
            }
            return scheduleGate;
        },
        getMemberList() {
            
            axios.post("/api/get_members?api_token=" + this.api_token, 
            {
                method              : "POST",
                message             : "this is a test message",
                
            }).then(response => {

                if (response.data.success === true) 
                {
                    this.$nextTick(function()
                    {  
                        this.memberList = response.data.members;
                        
                        //@todo: ajax load members         
                        //optionLists of Members
                        var options = [];
                        this.memberList.forEach(function (member, index) 
                        {   
                            options.push({'id': member.user_id, 'name': member.user_id + " " + member.firstname + " "+ member.lastname  });        
                        });
                        this.memberOptionList = options;
                          

                        this.$forceUpdate(); 
                    });
                } 
                else {                    
                    alert (response.data.message);                   
                }
			}).catch(function(error) {
                alert("Error " + error);                
			});               
        },
        getSchedules(scheduled_at, shiftDuration) {

            //getSchedules(this.scheduled_at, this.shiftDuration)

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
                        this.lessonsData = response.data.tutorLessonsData;
                        this.$forceUpdate(); 
                    });
                } 
                else {                    
                    alert (response.data.message);                   
                }
			}).catch(function(error) {
                alert("Error " + error);                
			});            

        },
        updateTutorSchedule() {
            //get the selected id
            let memberData = {
                id: this.memberSelectedID
            };

            axios.post("/api/update_tutor_schedule?api_token=" + this.api_token, 
            {
                method              : "POST",               
                memberData          : memberData.id,
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
                this.$bvModal.hide("schedulesModal");
                if (response.data.success === true) 
                {
                    this.$nextTick(function()
                    {  
                        this.lessonsData = response.data.tutorLessonsData;
                        this.$forceUpdate(); 
                    });
                } 
                else {                    
                    alert (response.data.message);                   
                }
			}).catch(function(error) {
                alert("Error " + error);                
			});            
        },
        setTutorSchedule() 
        {
            //get the selected id
            let memberData = {
                id: this.memberSelectedID
            };           
            
            if (this.scheduleExists(this.tutorData)) 
            {                
                alert ("this schedule is already booked");
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
            })
            .then(response => 
            {
                //hide schedule
                this.$bvModal.hide("schedulesModal");
                if (response.data.success === true) 
                {
                    this.$nextTick(function()
                    {  
                        this.lessonsData = response.data.tutorLessonsData;
                        this.$forceUpdate(); 
                    });
                } 
                else {                    
                    alert (response.data.message);                   
                }
			}).catch(function(error) {
                alert("Error " + error);                
			});
        },
        confirmDelete(scheduleData) 
        {
            if (confirm('Are you sure you want to delete this reservation?')) {
                this.deleteSchedule(scheduleData);
            } else {
            // Do nothing!
            }
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
            if (this.status === "TUTOR_CANCELLED") {
                this.membersSelection = 0;
                this.isStatusDisabled = true;                
            } else if (this.status === 'CLIENT_RESERVED' || this.status === "CLIENT_RESERVED_B" 
                || this.status === "TUTOR_CANCELLED" || this.status === "NOTHING"
                || this.status === 'CLIENT_NOT_AVAILABLE'
            ){
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
    .table td {
        font-size: 11px;
    }

    .table-schedules td.schedTime {
        background: #d0e8f7;
        text-align: center;
        font: bold 12px Arial;
        border: 1px solid #d0e8f7;
        border-bottom: 3px solid #72add2;
         border-right: 1px solid #ffffff;
        vertical-align: top;
        width: 80px;
        height: 29px;
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

    .iEdit, .iDelete {
        display: inline-block;
        width: 15px;
    }
    

    .btnAdd {
        margin: 2px 2px 0 80px;
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
    
    .nothing {
        background: #ffffff;
        border: 1px solid #c3c3c3;
        padding: 2px;
        line-height: 10px;
        color: #000000;
        width: 110px;
        margin: 2px;     
    }    

    .tutor_scheduled {
        background: #aae966;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;
        width: 110px;
        margin: 2px;        
    }

    .client_reserved {
        background: #f9e15f;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;
        width: 110px;
        margin: 2px;

    }

    .client_reserved_b {
        background: #f9e15f;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;
        width: 110px;
        margin: 2px;
    }

    .tutor_cancelled {
        background: #b0b0b0;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;
        width: 110px;
        margin: 2px;             
    }
 
    .client_not_available {

        background: #f25757;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;
        width: 110px;
        margin: 2px;              
    }

    .suppressed_schedule {
        background: #55c7f0;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;
        width: 110px;
        margin: 2px;         
    }

    .completed {
        background: #f6b05d;
        border-bottom: 1px solid #ffffff;
        padding: 2px;
        font-size: 12px;
        line-height: 10px;
        color: #000000;
        width: 110px;
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
</style>
