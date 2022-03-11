<template>
    <div id="member-timemanager-component" class="">

        <div class="bg-primary col-12 text-white pt-2 pb-2 text-center">
            <strong>Time Manager</strong>        
            <div class="btnAddScoreHolder float-right">
                <span v-b-modal.modalTimeManager><i class="fas fa-plus"></i></span>
            </div>
        </div>
     

        <div id="timemanager-content" class="blueBox">

            <div class="text-center small" v-show="this.updateType == 'new'">
                No Time Manager Data
            </div>
            
            <div class="current-timemanager-container" v-show="this.updateType == 'update'">
                <div class="row">
                    <div class="small col-12">
                        <strong>Course</strong>: 
                        <span id="course">{{ formatCourse(this.content.course) }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="small d-inline">
                            <strong>Current Score</strong>: {{ this.content.currentScore }}
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                      <div class="small d-inline"><strong>Target Score</strong>: {{ this.content.targetScore }}</div>
                    </div>
                </div>

                <div class="row  pt-2 ">
                    <div class="col-12 small">
                        {{ dateFormatter(this.content.startDate) }} - {{ dateFormatter(this.content.endDate) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Required Days</span>:
                            <span>{{ this.content.requiredDays }} </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Remaining Days </span>:
                            <span>{{ this.content.remainingDays }} </span>
                        </div> 
                    </div>

                 <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Required Hours</span>:
                            <span>{{ this.content.requiredHours }} </span>
                        </div> 
                    </div>


                    <div class="col-12 mt-2">
                        <div class="small">
                            <span class="font-weight-bold">Time Achievement </span>:
                            <span>{{ this.content.percentTimeAchievement }}% </span>
                        </div> 
                    </div>                

                </div>


            </div>


            <div id="buttonContainer" class="row mt-2">
                <!-- View Scores -->
                <div class="col-6 d-flex justify-content-end mx-0 px-0">
                    <span v-b-modal.modalTimeManagerProgressUpdate >
                        <b-button size="sm" block variant="dark"  pill>
                            <b-icon-calculator></b-icon-calculator> <span class="small">Progress Update</span> 
                        </b-button>                   
                    </span>
                    &nbsp;
                </div>

                <!--Score Graphs Button -->
                <div class="col-6 justify-content-end mx-0 pl-0 pr-2">
                    <span v-b-modal.modalMemberExamScoreGraph>
                        <b-button size="sm" block variant="primary" pill>
                            <b-icon-bar-chart-fill></b-icon-bar-chart-fill> <span class="small">View Graph </span>
                        </b-button>                   
                    </span>
                    &nbsp;
                </div>
            </div>

        </div>

        <b-modal id="modalTimeManager" title="Time Manager" size="xl" @hide="resetTimeManagerModal">

            <time-manager-component ref="timeManager" :memberinfo="memberinfo"  :content="contentData" :csrf_token="csrf_token" :api_token="api_token"/>
            <template #modal-footer>
                <div class="buttons-container w-100">                    
                    <div v-if="updateType == 'update' || updateType == 'edit'">
                        <!--[start] Edit-->
                        <b-button variant="danger" size="sm" class="float-left mr" id="deleteTimeManager" v-on:click="deleteTimeManager">Delete Time Manager Data </b-button>
                        <b-button variant="primary" size="sm" class="float-right mr" id="updateTimeManager" v-on:click="updateTimeManager">Update Time Manager Data</b-button>
                        <!--[end] Edit -->
                    </div>

                    <div v-else>
                        <!--[start] Create -->
                        <b-button variant="primary" size="sm" class="float-right mr" id="create" v-on:click="create">Create Time Manager</b-button>
                        <!--[end] Create -->
                    </div>
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalTimeManager')">Cancel</b-button>                         
                </div>

               

                <div class="loading-container">
                <b-button variant="primary" size="sm" class="float-right mr">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </b-button>
            </div>
            </template>                  
        </b-modal>


        <b-modal id="modalTimeManagerProgressUpdate"   title="Time Manager Progress Update"  @hide="resetTimeManagerUpdateModal">
        
            <time-manager-progress-update-component ref="timeManagerProgressUpdate" :memberinfo="memberinfo" :content="content" :csrf_token="csrf_token" :api_token="api_token"/>

            <template #modal-footer>
                <div class="buttons-container w-100">                                        
                    <b-button variant="primary" size="sm" class="float-right mr" id="create" v-on:click="create">Progress Update</b-button>
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalTimeManagerProgressUpdate')">Cancel</b-button>                         
                </div>

                <div class="loading-container">
                <b-button variant="primary" size="sm" class="float-right mr">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </b-button>
            </div>
            </template>                  
        </b-modal>        

       

    </div>
</template>


<script>
import * as Moment from 'moment';
import TimeManagerComponent from '../../modules/TimeManagerComponent.vue';
import TimeManagerProgressUpdateComponent from '../../modules/TimeManagerProgressUpdateComponent.vue';

export default {   
    name: "member-time-manager-component",
    components: {    
        TimeManagerComponent, TimeManagerProgressUpdateComponent
    },     
    data() {
        return {

            updateType: "",

            //time manager content 
            content: {
                material_checkbox: "",
                course: "",   

                startDate: "",
                endDate: "",

                currentScore: "",
                targetScore: "",
                requiredHours: "",

                materials: [],

                //auto calculated
                requiredDays: "",
                remainingDays: "",
                requiredHours: "",

                percentTimeAchievement: 10,
            },

            contentData: {
                course: "",   
                materials: [],            
            }

        }
    },
    mounted: function () 
	{
        this.contentData.materials.push({'id': 1, 'value': "" })
        this.contentData.materials.push({'id': 2, 'value': "" })
        this.contentData.materials.push({'id': 3, 'value': "" })
        this.getTimeManager()  
    },

    props: {
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
    },

    methods: {     
        resetTimeManagerModal() {
            this.getTimeManager();
        },
        resetTimeManagerUpdateModal() {

        },
        deleteTimeManager() {

            axios.post("/api/deleteTimeManager?api_token=" + this.api_token, 
            {
                method          : "POST",
                memberID        : this.memberinfo.user_id
            }).then(response => {

                if (response.data.success == true) 
                {  
                    this.updateType = "new";

                    this.content = this.resetData;
                    this.contentData = this.resetData();
                }
            }).catch(function(error) {
                console.log(error);
            });   


        },
        updateTimeManager() 
        {

            axios.post("/api/updateTimeManager?api_token=" + this.api_token, 
            {
                method          : "POST",
                memberID        : this.memberinfo.user_id
            }).then(response => {

                if (response.data.success == true) 
                {                    
                    //when user opens to create it will show update info and update button
                    this.updateType = "update";

                    let content= response.data.content;

                    this.$nextTick(() => {
                        this.content = this.assignData(content);
                        this.contentData = this.assignData(content);
                    });

                } else {

                     this.contentData = this.resetData();
                     this.updateType = "new";
                }
            }).catch(function(error) {
                console.log(error);
            });  

        },
        getTimeManager() 
        {
            axios.post("/api/getTimeManager?api_token=" + this.api_token, 
            {
                method          : "POST",
                memberID        : this.memberinfo.user_id
            }).then(response => {

                if (response.data.success == true) 
                {                    
                    //when user opens to create it will show update info and update button
                    this.updateType = "update";

                    let content= response.data.content;

                    this.$nextTick(() => {
                        this.content = this.assignData(content);
                        this.contentData = this.assignData(content);
                    });

                } else {

                     this.contentData = this.resetData();
                     this.updateType = "new";
                }
            }).catch(function(error) {
                console.log(error);
            });        
        },  
        create() 
        {
            this.$refs.timeManager.saveTimeManager();
            //this.$parent.$bvModal.hide('modalTimeManager');  
        },
        
        resetData() {
            return  {
                    course: "",
                    courseTextValue: "",
                    startDate: "",
                    endDate: "",
                    currentScore: "",
                    targetScore: "",
                    requiredHours: "",
                    material_checkbox: false,
                    materials: [],
                    requiredDays: "",
                    remainingDays: "",
                    requiredHours: "",
                    percentTimeAchievement: "",
                }        
        
        },
        assignData(content) 
        {        
            return  {
                    course: content.course,   
                    courseTextValue: this.formatCourse(content.course),
                    startDate: content.start_date,
                    endDate: content.end_date,
                    currentScore: content.current_score,
                    targetScore: content.target_score,
                    requiredHours: content.required_hours,
                    material_checkbox: content.has_materials,
                    materials: JSON.parse(content.materials),                
                    requiredDays: content.required_days,
                    remainingDays: content.remaining_days,
                    requiredHours: content.required_hours,      
                    percentTimeAchievement: content.time_achievement                  
                }
        },
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');                      
            return fdate;            
        },

        formatCourse(string) {
            let newString = string.charAt(0).toUpperCase() + string.slice(1);
            newString = newString.replace(/_/g, " ")            
            return newString;
        },
    },     

    updated: function() {
       
    }
};
</script>
