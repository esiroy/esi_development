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
                      
                        <option value="Regular Cancel" selected>Regular Cancel</option>
                        <option value="Cancel with replacement">Cancel with replacement</option>
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

            <div class="card-body scrollable-x p-0"  style="height:680px">
                <table class="table table-bordered table-schedules">

                    <thead>
                        <tr>
                            <th class="schedTime static">
                                <div class="bordered">
                                    <div class="class-schedule-container"></div>
                                </div>
                            </th>
                            <td class="schedTime" v-for="time in timeList" :key="time.id">
                                <div class="bordered">
                                    <div class="class-schedule-container">
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
                                  <!-- 
                                    <div class="tutor-id">{{ tutor.id }}</div>
                                    <div class="tutor-user-id">{{ tutor.user_id }}</div>
                                    -->
                                    <div class="tutor-name">{{ tutor.firstname }}</div>
                                </div>
                            </th>

                            <td v-for="time in timeList" :key="time.id">                        


                                <div :id="'btnAdd-' + tutor.id + '-' + time.startTime"
                                    class="addSchedule SCHEDULE_ITEM" 
                                    v-show="checkButton({'tutorID':tutor.id, 'startTime':time.startTime, 'endTime': time.endTime })">
                                    <input type="button" value="" class="btnAdd" v-b-modal.addScheduleModal @click="openSchedule({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"/>
                                </div>

                                <div :id="tutor.id + '-' + time.startTime"
                                    v-show="checkStatus({'tutorID':tutor.id, 'startTime':time.startTime, 'endTime': time.endTime })" 
                                    :class="getStatus({'tutorID':tutor.id , 'startTime':  time.startTime, 'endTime': time.endTime }) + ' SCHEDULE_ITEM' ">
                                    <div class="client">
                                        <div v-html="getMember({'tutorID':tutor.id, 'startTime':time.startTime, 'endTime': time.endTime })"></div>                                   
                                    </div>
                                    <div class="btn-container">
                                        <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime})"><img src="/images/iEdit.gif"></a></div>
                                        <div class="iDelete"><a href="javascript:void(0);" @click="confirmDelete({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime})"><img src="/images/iDelete.gif"></a></div>
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

            isFound : false,

            fromDay : this.scheduled_at,
            nextDay : "",

            memberOptionList: [],
            modalType: null,

            //Data
            lessonsData: [],           
            tutorData: null,
            status: "",
            memberSelectedID: "",
            memberList: [],

            //Schedule Data [edit]
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
    async beforeMount() {
       
        this.setMemberListLock(); //disabler of additoinal options        

   
        
        //console.log("Lessons Mounted : ", this.schedules);

        this.shiftDuration  = this.duration;

        //@todo: ajax load schedules (DONE)
        //this.getSchedules(this.scheduled_at, this.shiftDuration); 

        this.getMemberList();

       

        this.nextDay  = this.schedule_next_day;
        this.fromDay = this.scheduled_at;        
               
    },
    mounted() {


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

        this.nextDay  = this.schedule_next_day;
        this.fromDay = this.scheduled_at;

        console.log(this.fromDay);

        //this data is from the laravel controller
        this.lessonsData = this.schedule_items;

        console.log(this.lessonsData);

     
        //get member array



    },
    methods: {
        getScheduleData(data) {
           try {
                //23:00 - will be 1 hour advance in japan (00:00) is the time will midnight.
                //23:30 - will be 1 hour advance in japan (00:30) is the time will midnight and a half :D
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorID][this.nextDay][data.startTime];                   
                     return lessonData;
                } else {
                    let lessonData = this.lessonsData[data.tutorID][this.scheduled_at][data.startTime];
                    return lessonData;
                }                
            }
            catch(err) { return ""; }
        },
        getMember(data) 
        {          
            try {
                //23:00 - will be 1 hour advance in japan (00:00) is the time will midnight.
                //23:30 - will be 1 hour advance in japan (00:30) is the time will midnight and a half :D
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorID][this.nextDay][data.startTime];
                    //return lessonData.status_checker;
                    return "<a id='"+lessonData.id+"' href='/admin/member/"+ lessonData.member_id +"' target='_blank'>"+ lessonData.nickname +"</a>";

                } else {
                    let lessonData = this.lessonsData[data.tutorID][this.scheduled_at][data.startTime];
                    return "<a id='"+lessonData.id+"' href='/admin/member/"+ lessonData.member_id +"' target='_blank'>"+ lessonData.nickname +"</a>";
                }
                //console.log(this.lessonsData[data.tutorID][this.scheduled_at][data.startTime])
            }
            catch(err) { return ""; }               
         },
        //check button if it has schedule then we will hide it, and if not we need to show it
        checkButton(data) {
            let isFound = false;
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorID][this.nextDay][data.startTime];       
                    isFound = lessonData.status;
                } else {
                    let lessonData = this.lessonsData[data.tutorID][this.scheduled_at][data.startTime];       
                    isFound = lessonData.status;
                }
            }
            catch(err) {
               isFound =false
            } 
            return !isFound;
        },
        //check reservation status and show if available
        checkStatus(data) {
            //console.log(data.tutorID)
            let isFound = false;
            try {
                if (data.startTime == '23:00' || data.startTime == '23:30') {
                    let lessonData = this.lessonsData[data.tutorID][this.nextDay][data.startTime];       
                    isFound = lessonData.status;
                } else {
                    let lessonData = this.lessonsData[data.tutorID][this.scheduled_at][data.startTime];       
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

                    let lessonData = this.lessonsData[data.tutorID][this.nextDay][data.startTime];
                    return lessonData.status;

                } else {
                    let lessonData = this.lessonsData[data.tutorID][this.scheduled_at][data.startTime];
                    return lessonData.status;
                }
                //console.log(this.lessonsData[data.tutorID][this.scheduled_at][data.startTime])
            }
            catch(err) { return ""; }                
        },
        scheduleExists(scheduleData) 
        {
            return this.checkStatus();
        },        
        openSchedule(tutorData) 
        {
            this.modalType = "save";
            console.log(tutorData);
            this.$bvModal.show("schedulesModal");
            this.tutorData = tutorData;
        },
        hideModal() {
            console.log("hide modal");

            //update the schedules when you hide it?
            //this.getSchedules(this.scheduled_at, this.shiftDuration);
        },
        resetModal() {
            console.log("reset modal"); //this will reset every time it closes.
            this.memberSelectedID = "";
            this.status = "";
            this.isStatusDisabled = true; 
        },
        handleOk(bvModalEvt) 
        {
            if (this.status === "NOTHING") this.confirmDelete(this.tutorData);
            else {
                if (this.modalType === 'save') {
                    console.log ("saving...")            
                    this.setTutorSchedule();
                } else {
                    this.updateTutorSchedule();
                }
            }
            bvModalEvt.preventDefault();
        },        
        editSchedule(scheduleData) 
        {
            //show the modal first and update the values below             
            this.$bvModal.show("schedulesModal");

            //update the modal values
            this.modalType = "edit";
            this.currentScheduledData = this.getScheduleData(scheduleData);
            this.tutorData = scheduleData;
            this.status = this.currentScheduledData.status;
            
            this.memberSelectedID = [{ id: this.currentScheduledData.member_id , 'name': this.currentScheduledData.firstname }];
            
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
                        console.log(response.data.tutorData.tutorID);
                        console.log(response.data.tutorData.startTime);
                        
                        let tutorID = response.data.tutorData.tutorID;
                        let startTime = response.data.tutorData.startTime;

                        this.lessonsData[tutorID][this.scheduled_at][startTime];

                        //set the schedule to display
                        let schedule = document.getElementById(tutorID + "-" + startTime);
                        schedule.style.display = "block";
                        schedule.classList.add(this.status);

                        //hide the add button
                        let addButton = document.getElementById("btnAdd-" + tutorID + "-" + startTime);
                        addButton.style.display = "none";

                        this.getSchedules(this.scheduled_at, this.shiftDuration);

                        this.$forceUpdate();
                    });

                    /* this will be transfered to when schedule modal is closed
                    this.$nextTick(function()
                    {  
                        this.lessonsData = response.data.tutorLessonsData;
                        this.$forceUpdate(); 
                    });
                    */
                } 
                else {                    
                    alert (response.data.message);    
                }

                //@note: auto refresh... (this is only used when there is duplicate)
                if (response.data.refresh === true) 
                {
                    this.$nextTick(function()
                    {  
                        this.lessonsData = response.data.tutorLessonsData;
                        this.$forceUpdate(); 
                    });
                }
                
			}).catch(function(error) {
                alert("Error " + error);                
			});
        },        
        updateTutorSchedule() {
            //get the selected id

           
            let memberData = {
                'id': this.currentScheduledData.member_id,
                'name': this.currentScheduledData.firstname //@optionial?
            };

                           

            axios.post("/api/update_tutor_schedule?api_token=" + this.api_token, 
            {
                method              : "POST",               
                memberData          : memberData,
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
        getSchedules(scheduled_at, shiftDuration) 
        {
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

                        console.log( this.lessonsData);

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

    .iEdit, .iDelete {
        display: inline-block;
        width: 15px;
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
        width: 85px;
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
</style>
