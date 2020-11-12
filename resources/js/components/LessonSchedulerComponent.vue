<!--
@Name: LessonScheduleComponent.vue
@Description: This will add the tutor schedule for the member(student)
@Developer: Roy
@Company: ESucess Inc.

@todo: add schedule
-->
<template>
    <div id="schedules">

        <b-modal id="schedulesModal" 
            title="BootstrapVue"
            button-size="sm"
            @show="resetModal"
            @hidden="hideModal"
            @ok="handleOk"
        
        >
            <div class="row">
                <div class="col-md-3">
                    <label>Status :</label>
                </div>
                <div class="col-md-9">
                    <select
                        name="status"
                        ref="status"     
                        v-model="status"                  
                        @change="setMemberListLock()"
                    >
                        <option value="">Please Select A Status</option>
                        <option value="Tutor Scheduled">Tutor Scheduled</option>

                        <option value="Client Reserved">Client Reserved</option>
                        <option value="Client Reserved B">Client Reserved B</option>
                        <option value="Tutor Cancelled">Tutor Cancelled</option>
                        <option value="Nothing">Nothing</option>

                        <option value="Client Not Available"
                            >Client Not Available</option
                        >
                        <option value="Suppressed Schedule"
                            >Suppressed Schedule</option
                        >
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label>Member :</label>
                </div>
                <div class="col-md-9">
                    <!--@todo: 1. disable if "tutor scheduled, suppressed schedule, completed" -->
                    <!--@todo: 2. enabled only if "Client Resererve (A, B), -->
                    <select :disabled="this.isStatusDisabled"
                        name="membersSelection"
                        id="membersSelection"
                        ref="membersSelection"
                        v-model="memberSelectedID"
                        class="form-control form-control-sm"
                    >
                        <option value="">-- Select A Member --</option>
                        <option v-for="member in members"
                            :value="member.user_id"
                            :key="member.user_id">{{ member.first_name }} {{ member.last_name }}</option>
                    </select>



                </div>
            </div>

            <!--#SHOW - DISABLED -->
            <div class="row" v-show="this.status === 'Tutor Cancelled'">
                <div class="col-md-3">Email Type:</div>
                <div class="col-md-9">
                    <!--@todo: show only if client is cancelling -->
                    <select
                        name="cancelationType"
                        id="cancelationType"
                        class="form-control form-control-sm hidden"
                        ref="cancelationType"
                        v-model="cancelationType">

                        <option value="">-- Sect Email Type --</option>
                        <option value="Regular Cancel">Regular Cancel</option>
                        <option value="Cancel with replacement"
                            >Cancel with replacement</option
                        >
                    </select>
                </div>
            </div>

  
  
            <div class="row" v-show="this.status === 'Client Reserved' || this.status === 'Client Reserved B'">
                <div class="col-md-3">Email Type:</div>
                <div class="col-md-9">                 
                    <select name="reservationType"
                            id="reservationType"
                            class="form-control form-control-sm hidden"
                            ref="reservationType"
                            v-model="reservationType">
                            
                        <option value="">-- Sect Email Type --</option>
                        <option value="Regular reservation">Regular reservation</option>
                        <option value="For replacement">For replacement</option>
                    </select>
                </div>
            </div>


        </b-modal>

        <div class="card">
            <div class="card-header text-center">
                {{ year }} 年 {{ month }}月 {{ day}}日
            </div>

            <div class="card-body scrollable-x">

                <table class="table table-bordered table-schedules">

                    
                    <tr>
                        <td></td>
                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span class="class-schedule class-schedule-start">10:00</span>
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end">11:00</span>
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >10:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >11:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >11:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >12:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >11:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >12:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >12:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >13:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >12:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >1:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >13:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >14:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >13:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >14:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >14:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >15:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >14:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >15:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >15:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >16:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >15:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >16:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >16:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >17:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >16:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >17:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >17:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >18:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >17:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >18:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >18:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >19:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >18:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >19:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >19:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >20:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >19:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >20:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >20:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >21:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >20:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >21:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >21:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >22:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >21:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >22:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >22:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >23:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >22:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >23:30</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >23:00</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >24:00</span
                                >
                            </div>
                        </td>

                        <td>
                            <div class="class-schedule-container">
                                <span class="flag-ph"></span>
                                <span
                                    class="class-schedule class-schedule-start"
                                    >23:30</span
                                >
                            </div>
                            <div class="class-schedule-container">
                                <span class="flag-jp"></span>
                                <span class="class-schedule class-schedule-end"
                                    >24:30</span
                                >
                            </div>
                        </td>
                    </tr>

                    <tr v-for="tutor in tutors" :key="tutor.id">
                        <td class="schedTime">
                            <div style="width:125px">{{ tutor.name_en }}</div>
                        </td>

                        <td class="schedTime" v-for="time in timeList" :key="time.id">

                            {{ time.startTime  }} - {{ time.endTime}}
                            <input type="button" value="" class="btnAdd" v-b-modal.addScheduleModal
                                @click="openSchedule({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime })"/>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Tutor Scheduled' })" class="tutor_scheduled">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'Tutor Scheduled'})"><img src="/images/iDelete.gif"></a></div>
                            </div>
                            
                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Client Reserved' })" class="client_reserved">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'Client Reserved'})"><img src="/images/iDelete.gif"></a></div>
                            </div> 

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Client Reserved B' })" class="client_reserved_b">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'Client Reserved B'})"><img src="/images/iDelete.gif"></a></div>  
                            </div>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Tutor Cancelled' })" class="tutor_cancelled">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'Tutor Cancelled'})"><img src="/images/iDelete.gif"></a></div>  
                            </div>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Nothing' })" class="nothing">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'Nothing'})"><img src="/images/iDelete.gif"></a></div>  
                            </div>

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Client Not Available' })" class="client_not_available">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'Client Not Available'})"><img src="/images/iDelete.gif"></a></div>  
                            </div> 

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Suppressed Schedule' })" class="suppressed_schedule">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'Suppressed Schedule'})"><img src="/images/iDelete.gif"></a></div>  
                            </div> 

                            <div v-show="checkStatus({tutorID: tutor.id, startTime: time.startTime, endTime: time.endTime, status: 'Completed' })" class="completed">
                                <div class="iEdit"><a href="javascript:void(0);" @click="editSchedule(tutorData)"><img src="/images/iEdit.gif"></a></div>
                                <div class="iDelete"><a href="javascript:void(0);" @click="deleteSchedule({tutorID: tutor.id, 'startTime': time.startTime, 'endTime': time.endTime, status: 'completed'})"><img src="/images/iDelete.gif"></a></div>  
                            </div>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</template>



<script>
export default {
    
	components: {
        
	},
    props: {
        year: { type: String },
        month: { type: String },
        day: { type: String },
        scheduled_at: { type: String },
        duration: { type: Number },
        //date for schedules
        lessons: { type: Object },
        tutors: {type: Array },
        members: { type: Array },
        //api tokens
        csrf_token: {type: String },
        api_token: { type: String },
    },
    data() {
        return {
            
            //Data
            lessonsData: [],
            status: "",
            tutorData: null,
            memberSelectedID: "",

            //emailType
            cancelationType: "",
            reservationType: "",
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
                {id:8, startTime: '13:00', endTime: '14:30'},

                {id:9, startTime: '14:00', endTime: '15:00'},
                {id:10,startTime: '14:00', endTime: '15:30'},

                {id:11, startTime: '15:00', endTime: '16:00'},
                {id:12, startTime: '15:00', endTime: '16:30'},

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
    beforeMount() {
       
    },
    mounted() {

        //console.log("Component mounted.");
        this.setMemberListLock(); //disabler of additoinal options
        
        this.lessonsData = this.lessons;

        console.log("Lessons Mounted : ", this.lessonsData);

        this.shiftDuration  = this.duration;


    },
    methods: {
        checkStatus(data) 
        {      
            let isFound = false;

            $.each(this.lessonsData[data.tutorID], function(key, value) 
            {   
                //console.log(data)

                let test = null;

                if ( value.scheduled_at === this.scheduled_at) {
                     test = "same";
                } else{
                     test = "not same";
                }

                console.log(value.scheduled_at + " ===? "+ this.scheduled_at + " " + test);

                if (
                    value.tutor_id === data.tutorID &&
                    value.status === data.status && 
                    value.startTime === data.startTime && 
                    value.endTime === data.endTime &&
                    value.scheduled_at === this.scheduled_at //&&
                    //value.duration === this.shiftDuration
                    )
                {
                    isFound = true;
                    console.log("found")
                   
                }
            });          

            return isFound;

                    
        },
        openSchedule(tutorData) 
        {

            console.log(tutorData);
            
            //show the modal
            this.$bvModal.show("schedulesModal");

            //log tutorData
            //console.log("opened item -> ", tutorData);

            //make this data global (this.tutorData)
            this.tutorData = tutorData;
        },    
        hideModal() {
            //console.log("hide modal");
        },
        resetModal() {
           //console.log("reset modal");
        },
        isScheduleExist() {

        },
        handleOk(bvModalEvt) 
        {   

            this.setTutorSchedule();
            bvModalEvt.preventDefault();
        },

        scheduleExists(scheduleData) 
        {   
            if (this.lessonsData[scheduleData.tutorID] == undefined)  {
                return false
            } 


            let result =  this.lessonsData[scheduleData.tutorID].find(item => item.startTime === scheduleData.startTime && item.duration === scheduleData.duration);
            if (result) {               
                return true; //found
            } else {
                return false; 
            }          

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

            console.log("go on creating schedule");

            axios.post("/api/create_tutor_schedule?api_token=" + this.api_token, 
            {
                method              : "POST",               
                memberData          : memberData,
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

                        /*
                        if (this.lessonsData[response.data.tutorData.tutorID] === undefined) {                           
                         this.lessonsData[response.data.tutorData.tutorID] = [];
                        } 
                        this.lessonsData[response.data.tutorData.tutorID].push({
                            tutorID:  response.data.tutorData.tutorID,
                            startTime: response.data.tutorData.startTime,
                            endTime: response.data.tutorData.endTime,
                            status: this.status
                        });
                        */

                                                
                      
                    });
                } 
                else 
                {                    
                    alert (response.data.message);                   
                }

			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                console.log(error);
			});
        },
        deleteSchedule(scheduleData) 
        {         

            axios.post("/api/delete_tutor_schedule?api_token=" + this.api_token,             
            {
                method              : "POST",             
                scheduled_at       : this.scheduled_at,
                shiftDuration       : this.shiftDuration,                  
                scheduleData         : scheduleData,
            })
            .then(response => 
            {                

                if (response.data.success === true) 
                {

                    this.lessonsData = response.data.tutorLessonsData;

                } else {
                   
                    alert (response.data.message);
                }


			}).catch(function(error) {
                // handle error
                alert("Error " + error);
                console.log(error);
			});
              
        },

        setMemberListLock() {
            
            if (this.status === "Tutor Cancelled") {

                this.membersSelection = 0;
                this.isStatusDisabled = true;
                
            } else if (this.status === 'Client Reserved' || this.status === "Client Reserved B" 
                || this.status === "Tutor Cancelled" || this.status === "Nothing"
                || this.status === 'Client Not Available'
            )
            {
                this.isStatusDisabled = false;
            

            } else {
                this.membersSelection = 0;
                this.isStatusDisabled = true;
            }
           
        }
    }
};
</script>

<style >
    .table-schedules td.schedTime {
        background: #ffffff;
        text-align: center;
        font: bold 12px Arial;
        border: 1px solid #d0e8f7;
        border-left: none;
        border-top: none;
        vertical-align: top;
        width: 110px;
        height: 40px;
        padding: 3px;
    }

    .iEdit, .iDelete {
        display: inline-block;
        width: 15px;
    }

    a {
        text-decoration: none;
        color: #c60000;
    }
    .btnAdd {
        margin: 2px 2px 0 0;
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

    .actionButtons {
        margin: 3px 0 0 0;
        padding: 2px 0 0 0px;
        background: #ececec;
        text-align: center;
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

</style>
