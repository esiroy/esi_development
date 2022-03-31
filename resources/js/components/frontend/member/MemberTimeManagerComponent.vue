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


            <div id="buttonContainer" class="row mt-2" v-show="this.updateType == 'update'">
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
                    <span v-b-modal.modalTimeManagerProgressGraph>
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
                    <b-button variant="primary" size="sm" class="float-right mr" id="create" v-on:click="addProgress">Progress Update</b-button>
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

       
        <!-- [START] SCORE TIME PROGRESS GRAPH -->
        <div id="timeManagerProgressGraph" class="modal-container">
        
            <b-modal id="modalTimeManagerProgressGraph"  title="Time Manager Graph" size="md" @show="getTimeManagerProgressGraph"> 

                <div v-if="loaded == true">

                    <div id="memberGraphModalMessage" class="row" v-if="entries.length == 0">
                        <div class="text-center col-md-12 my-4">                               
                            <span class="text-success"> No data found </span>
                        </div>
                    </div>

                    <div class="row graph-list" v-else>
                        <div class="col-12">
                            <line-chart :chart-data="datacollection"  v-if="loaded"  :options="extraOptions"></line-chart>
                        </div>
                    </div>

                </div>


                <div v-else>
                    <div class="d-flex justify-content-center my-4">
                        <b-spinner label="Loading..." variant="success"></b-spinner>
                    </div>
                </div>    
                <template #modal-footer>
                    <div class="buttons-container w-100">
                        <p class="float-left"></p>
                        <b-button variant="primary" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalTimeManagerProgressGraph')">Close</b-button>                            
                    </div>
                </template>                         
                
            </b-modal>
        </div>
        <!-- [END] SCORE MODAL -->

    </div>
</template>


<script>

import LineChart from '../../frontend/chart/lineChartComponent.vue';

import * as Moment from 'moment';
import TimeManagerComponent from '../../modules/TimeManagerComponent.vue';
import TimeManagerProgressUpdateComponent from '../../modules/TimeManagerProgressUpdateComponent.vue';

export default {   
    name: "member-time-manager-component",
    components: {    
        LineChart,
        TimeManagerComponent, 
        TimeManagerProgressUpdateComponent
    },     
    data() {
        return {

            //charts
            loaded: false,
            datacollection: [],
            extraOptions: [],
        
            updateType: "",
            timer: null,

            //time manager content 
            content: {
                material_checkbox: "",
                course: "",   

                gradeLevel: "",
                gradeLevelTextValue: "",


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
            },

            entries: ""

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
        resetTimeManagerUpdateModal() 
        {
            clearTimeout(this.timer);
        },

        getFormArray() {          
            var form = document.querySelector('#form-timemanager-'+this.content.course);
            var data = new FormData(form);
            for (let entry of data) {
                console.log(entry);
            }
        },
        getTimeManagerProgressGraph() {

            this.loaded = false;
        

            axios.post("/api/getTimeManagerProgressGraph?api_token=" + this.api_token, 
            {
                method      : "POST",
                memberID    : this.memberinfo.user_id,
                timeManagerID : this.content.id,

            }).then(response => {    
                       
                this.loaded = true;

                if (response.data.success === true) 
                {

                    $('#memberGraphModalMessage').hide();

                    this.entries = response.data.entries;
                    let entries = this.entries;

                    let dates   = [];
                    let minutes     = [];

                    entries.forEach((entry)=> {
                        dates.push(entry.date)
                        minutes.push(entry.total_minutes)
                    });
                    
                    console.log( dates, minutes)

                    //let randColor =  '#'+ Math.floor(Math.random()*16777215).toString(16); 
                    //let color = this.addAlpha(randColor, 0.4)
                      
                    let color = this.addAlpha('#2F5233', 0.4)

                    this.datacollection = {
                        labels: dates,  
                        datasets: [
                            {
                                label: this.content.course,                                   
                                backgroundColor: color,                     
                                data: minutes,                   
                            },
                        ],                           
                    } 

                    this.extraOptions = { 
                        scales: {
                            yAxes: [
                            {
                                ticks: {
                                    min: 0,
                                    max: this.content.requiredHours,
                                    stepSize: 1,
                                    reverse: false,
                                    beginAtZero: true
                                }
                            }]
                        }
                    };                    
                    
                    if (this.isMobile() == true) {
                        $(".modal-dialog").css({
                            'max-width': '90%'
                        });                  
                    } 

                } else {

                    console.log("error retrieveing graph stats")
                    
                }

            }).catch(function(error) {
                console.log("Error " + error);
            });
        },

        addProgress() 
        {     

            //Determine Component Opened
            let progressUpdate = this.$refs['timeManagerProgressUpdate'];
            let updaterModal = progressUpdate.$refs[this.content.course + 'TimeManagerComponent'];

            console.log(this.content.course + 'TimeManagerComponent')

            let date       = updaterModal.getDate();
            let minutes    = updaterModal.getMinutesData();

            //Get the total from minutes object
            let totalMinutes = minutes.total;

            if (date && totalMinutes > 0) {

                //format data
                let data = {
                    'memberID'      : this.memberinfo.user_id,
                    'date'          : date,
                    'id'            : this.content.id,
                    'course'        : this.content.course,
                    'gradelLevel'   : this.content.gradelLevel,
                    'minutes'       : minutes 
                }

                this.saveProgress(data);

            } else {
            
                alert ("you have to enter date and minutes")
            }
        },
        saveProgress(data)  {
            
            //SHOW LOADER HERE
            $(document).find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('.modal-footer').find('div.loading-container').show();

            axios.post("/api/createTimeManagerProgress?api_token=" + this.api_token, 
            {
                'method'      : "POST",
                'data'        : data

            }).then(response => {


                if (response.data.success == true) 
                {  
                    this.$nextTick(() => {
                 
                        $(document).find('.modal-footer').hide();
                        
                        $(document).find('#timeManager-'+this.content.course).find('.message').html('<div class="alert alert-success text-center" role="alert">Thank you! Progress has been submitted</div>'); 

                        $(document).find('#form-timemanager-'+this.content.course).slideUp(500);             

                        this.timer = setTimeout(function(scope) {
                            scope.$bvModal.hide('modalTimeManagerProgressUpdate');
                        }, 2500, this);

                        this.$forceUpdate();
                    });            

                } else {
                    //show error message 

                    //HIDE LOADER HERE
                    $(document).find('.modal-footer').find('div.buttons-container').show();
                    $(document).find('.modal-footer').find('div.loading-container').hide();

                }

            }).catch(function(error) {
                console.log(error);
                
                //HIDE LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();
            });   

        },
        deleteTimeManager() {

            //SHOW LOADER HERE
            $(document).find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('.modal-footer').find('div.loading-container').show();

            axios.post("/api/deleteTimeManager?api_token=" + this.api_token, 
            {
                method          : "POST",
                memberID        : this.memberinfo.user_id
            }).then(response => {

                if (response.data.success == true) 
                {  
                    this.updateType = "new";

                    this.$nextTick(() => {
                        this.content = this.resetData;
                        this.contentData = this.resetData();
                        this.$bvModal.hide('modalTimeManager')                 
                    });        

                } else {

                    //error
                    
                    $(document).find('.modal-footer').find('div.buttons-container').show();
                    $(document).find('.modal-footer').find('div.loading-container').hide();

                }


            }).catch(function(error) {
                console.log(error);
                
                $(document).find('.modal-footer').find('div.buttons-container').show();
                $(document).find('.modal-footer').find('div.loading-container').hide();                
            });   


        },
        updateTimeManager() 
        {
            this.$refs.timeManager.updateTimeManager();
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

                    let content = response.data.content;

                    this.$nextTick(() => {
                        this.content = this.assignData(content);
                        this.contentData = this.assignData(content);
                    });

                } else {

                     this.contentData = this.resetData();
                     this.content = this.resetData();
                     
                     this.updateType = "new";
                }
            }).catch(function(error) {
                console.log(error);
            });        
        },  
        create() 
        {
            //this.$refs.timeManager.saveTimeManager();

            this.$refs.timeManager.saveTimeManager();
            
            //this.$parent.$bvModal.hide('modalTimeManager');  
        },
        resetData() {

            return  {
                    id: null,
                    course: "",
                    courseTextValue: "",
                    gradeLevel: "",
                    gradeLevelTextValue:  "",
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
                    id: content.id,
                    course: content.course,   
                    courseTextValue: this.formatCourse(content.course),
                    gradeLevel: content.grade_level,
                    gradeLevelTextValue:  this.formatCourse(content.grade_level),

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
        removeUnderscore(string) {
            let wordArray = string.split("_");
            let words = wordArray.join(" ");
            return words;
        }, 
        formatCourse(string) 
        {
            if (string) {                       
                let newString = string.charAt(0).toUpperCase() + string.slice(1);
                newString = this.removeUnderscore(newString);
                return newString;
            }
        },
        capitalizeFirstLetter(string) {
            let words = this.removeUnderscore(string);
            let newString = words.charAt(0).toUpperCase() + words.slice(1); 
            return newString.trim(); 
        },  
        addAlpha(color, opacity) {
            // coerce values so ti is between 0 and 1.
            var _opacity = Math.round(Math.min(Math.max(opacity || 1, 0), 1) * 255);
            return color + _opacity.toString(16).toUpperCase();
        },               
        isMobile() {
            if (window.innerWidth <= 1024 || screen.width  <= 1024 ) {
                return true
            } else {
                return false
            }
        }        
    },     

    updated: function() {
       
    }
};
</script>
