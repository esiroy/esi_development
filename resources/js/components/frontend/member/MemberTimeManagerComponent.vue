<template>
    <div id="time-manager-section" class="">
        
      
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
                

                <div class="row" v-if="this.content.course == 'EIKEN'">
                    <div class="small col-12">
                        <strong>Grade Level</strong>: 
                        <span id="course">{{ formatCourse(this.content.gradeLevel) }}</span>
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

                <div class="row  pt-2 mb-2">
                    <div class="col-12 small">
                        {{ dateFormatter(this.content.startDate) }} - {{ dateFormatter(this.content.endDate) }}                          
                    </div>
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Duration</span>:
                            <span>{{ this.content.numberOfdays }} days </span>
                        </div> 
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Required Hours</span>:
                            <span>{{ this.content.requiredHoursFormatted }}  </span>
                        </div> 
                    </div>


                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Average Hours Per Day</span>:
                            <span>{{ this.content.averageHoursPerDay }}</span>
                        </div> 
                    </div>        


                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Spent Hours</span>:
                            <span>{{ this.content.spentHours }}</span>
                        </div> 
                    </div> 



                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Remaining Hours</span>:
                            <span>{{ this.content.remainingHours }}</span>
                        </div> 
                    </div> 


                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Expected Hours</span>:
                            <span>{{ this.content.expectedHours }}</span>
                        </div> 
                    </div> 


        
                    <div class="col-12 mt-2">

                        <!--
                        num day {{ this.content.numberOfdays }} <br>
                        elapse {{ this.content.ellapsedDays }} <br><br>
                        Average Hours Per Day : {{ this.content.averageHoursPerDay }}<br>
                        Spent Hours: {{ this.content.spentHours }}<br>
                        Remaining Hours: {{ this.content.remainingHours }}<br>
                        Expected Hours:{{ this.content.expectedHours }}<br><br>
                        Spent Hours Percentage / Expected Hours : {{ this.content.spentHourPercentage  }} %
                        -->
                         

                        <div class="small" >
                            <span class="font-weight-bold">Time Achievement </span>:
                            <!--PERCENTAGE LEFT-->
                            <span class="text-danger" v-if="this.content.ellapsedDays > this.content.numberOfdays">
                                Date Expired
                            </span>                            
                            <span v-else-if="this.content.percentageLeft < 100">
                                {{ this.content.percentageLeft }}% 
                            </span>  
                            <span class="text-success" v-else-if="this.content.percentageLeft >= 100">
                                Completed
                            </span>
                            <span class="text-danger" v-else-if="this.content.requiredHours <= 0">
                                Please update required hours
                            </span>                            
                            <span class="text-success" v-else>
                                Calculating...
                            </span>
                        </div> 

                        <!--
                        <div class="text-danger small" v-if="this.content.ellapsedDays >= 5 && this.content.currentDayProgressCounter == 0">
                            <span class="pb-2 pt-2">警告！ 学習時間が大変遅れています。</span>
                        </div>
                        -->

                        <div class="text-danger small" v-if="this.content.spentHourPercentage < 70">
                            <span class="pb-2 pt-2">警告！ 学習時間が大変遅れています。</span>
                        </div>
                        

                    </div>                

                </div>


            </div>


            <div id="buttonContainer" class="row mt-2" v-show="this.updateType == 'update'">
                <!-- View Scores -->
                <div class="col-6 d-flex justify-content-end mx-0 px-0">

                    <span v-if="this.content.ellapsedDays > this.content.numberOfdays || this.content.percentageLeft >= 100">
                        <b-button size="sm" block variant="dark"  pill disabled="disabled">
                            <b-icon-calculator></b-icon-calculator> <span class="small">Progress Update</span> 
                        </b-button>                   
                    </span>   

                    <span v-b-modal.modalTimeManagerProgressCreate v-else-if="this.content.percentageLeft < 100">
                        <b-button size="sm" block variant="dark"  pill >
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

            <template #modal-title>     
                Time Manager
                <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/1215193414.html','必要な時間',900,820);" class="text-primary small">
                   <i class="fa fa-question-circle" aria-hidden="true"></i> 
                   <!--<strong>必要な時間</strong>-->
                </a>
            </template>

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


        <b-modal id="modalTimeManagerProgressList" title="Time Manager Progress List" size="lg" @show="getTimeManagerProgressList(1)">

            <div class="card esi-card">
                <div class="car-body esi-card-body">

                    <table class="table esi-table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th scope="col" class="small font-weight-bold">Date</th>
                                <th scope="col" class="small font-weight-bold">Minutes</th>
                                <th scope="col" class="small font-weight-bold">Total Minutes</th>
                                <th scope="col" class="small font-weight-bold">Total Hours</th>
                                <th scope="col" class="small font-weight-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, itemsIndex) in items" :key="itemsIndex">

                                <td>{{item.id}}</td>
                                <td class="text-left pl-2">{{ item.date }}</td>
                                <td class="text-left pl-2">
                                    <div v-for="(value, itemIndex) in item.minutes" :key="itemIndex" v-show="value !== null && itemIndex !== 'total' ">
                                        {{ capitalizeFirstLetter(itemIndex) }}: {{ value }}
                                    </div>
                                </td>   
                                <td class="text-left pl-2">
                                    {{ item.total_minutes}}
                                </td>
                                <td class="text-left pl-2">
                                    {{ item.total_hours }}
                                </td>

                                <td class="text-center">                                    
                                    <a href="#" @click.prevent="showUpdateTimeManagerForm(item.id, itemsIndex)" class="mr-1 text-primary">
                                        <span class="h6 mb-2"><b-icon icon="pencil-square" aria-hidden="true"> </b-icon></span>
                                    </a>
                                    <a href="#" @click.prevent="deleteTimeManagerItem(item.id)">
                                        <span class="h6 mb-2"><b-icon icon=" trash" aria-hidden="true"></b-icon></span>
                                    </a>
                                </td>      

                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4">                        
                        <b-pagination
                            v-model="currentPage"
                            @input="changePage(currentPage)"
                            :total-rows="rows"
                            :per-page="perPage"
                            first-text="<<"
                            prev-text="<"
                            next-text=">"
                            last-text=">>"
                            size="sm"
                            align="center"                                            
                        ></b-pagination>
                    </div>

                </div>
            </div>

            <template #modal-footer>

                <div class="w-100" v-show="listLoaded"> 
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalTimeManagerProgressList')">Cancel</b-button>   
                </div>

                <div class="w-100" v-show="!listLoaded">
                    <b-button variant="primary" size="sm" class="float-right mr">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </b-button>
                </div>
            </template>
          

        </b-modal>    

        <b-modal id="modalTimeManagerProgressCreate" title="Time Manager Progress Create"  @show="resetTimeManagerCreateModal">
        
            <time-manager-progress-create-component ref="timeManagerProgressCreate" :memberinfo="memberinfo" :content="content" :csrf_token="csrf_token" :api_token="api_token"/>

            <template #modal-footer>

                <div class="buttons-container w-100">       
                   <b-button variant="primary" size="sm" class="float-left mr" v-b-modal.modalTimeManagerProgressList>Progress List</b-button>

                    <b-button variant="primary" size="sm" class="float-right mr" v-on:click="addProgress">Save Progress</b-button>
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalTimeManagerProgressCreate')">Cancel</b-button>                         
                </div>

                <div class="loading-container">
                    <b-button variant="primary" size="sm" class="float-right mr">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </b-button>
                </div>
            </template>

        </b-modal>       


        <b-modal id="modalTimeManagerProgressUpdate" title="Time Manager Progress Update"  @hide="resetTimeManagerUpdateModal">
            
            <time-manager-progress-update-component ref="timeManagerProgressUpdate" 
                :items="items" 
                :progressupdateid="progressUpdateID" 
                :progressupdateindex="progressUpdateIndex"
                :memberinfo="memberinfo" 
                :content="content" 
                :csrf_token="csrf_token" 
                :api_token="api_token"/>

            <template #modal-footer>

                <div class="buttons-container w-100">       
                    <b-button variant="primary" size="sm" class="float-right mr" v-on:click="updateProgress">Update Progress</b-button>
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
        
            <b-modal id="modalTimeManagerProgressGraph"  title="Time Manager Graph" size="lg" @show="getTimeManagerProgressGraph"> 

                <div v-if="loaded == true">

                    <div id="memberGraphModalMessage" class="row" v-if="entries.length == 0">
                        <div class="text-center col-md-12 my-4">                               
                            <span class="text-success"> No data found </span>
                        </div>
                    </div>

                    <div class="row graph-list" v-else>
                        <div class="col-12">
                            <horizontal-bar-chart :chart-data="datacollection"  v-if="loaded"  :options="extraOptions" :height="150"></horizontal-bar-chart>
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

import HorizontalBarChart from '../../frontend/chart/horizontalBarChartComponent.vue';

import * as Moment from 'moment';
import TimeManagerComponent from '../../modules/TimeManagerComponent.vue';
import TimeManagerProgressCreateComponent from '../../modules/TimeManagerProgressCreateComponent.vue';
import TimeManagerProgressUpdateComponent from '../../modules/TimeManagerProgressUpdateComponent.vue';


export default {   
    name: "member-time-manager-component",
    components: {    
        HorizontalBarChart,
        TimeManagerComponent, 
        TimeManagerProgressCreateComponent,
        TimeManagerProgressUpdateComponent,
    },     
    data() {
        return {

            //charts
            loaded: false,
            datacollection: [],
            extraOptions: [],
        
            updateType: "",
            timer: null,

            warningMessageValue: null,

            //Progress Listing
            listLoaded: false,
            rows: 0,
            perPage: 0,
            currentPage: 1,
            items: [],

            //Progress Update ID
            progressUpdateIndex: null,
            progressUpdateID: null,

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
                requiredHoursFormatted: "",

                materials: [],

                //auto calculated
                averageHours: "",
                requiredDays: "",
                remainingDays: "",
               
                averageHoursPerDay: "",
                spentHours: "",
                remainingHours:"",
                expectedHours: "",       

                currentDayProgressCounter: 0,        
                

                ellapsedDays: "", 
                numberOfdays:  "",

                spentHourPercentage: 0,
                percentageLeft: 0,
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
        resetTimeManagerCreateModal() 
        {
            clearTimeout(this.timer);
        },
        resetTimeManagerUpdateModal() {
            clearTimeout(this.timer);
        },    
        changePage(page) 
        {
           this.getTimeManagerProgressList(page);
            
        },
        getTimeManagerProgressList(page) 
        {            
            this.listLoaded = false;

            axios.post("/api/getTimeManagerProgressList?page="+ page +"&api_token=" + this.api_token, 
            {
                'method'        : "POST",
                'memberID'      : this.memberinfo.user_id,
                'timeManagerID' : this.content.id
            }).then(response => {

                if (response.data.success == true) 
                {             
                    let progress = response.data.progress;

                    this.items = progress;

                    

                    //page details
                    this.currentPage = response.data.currentPage;
                    this.perPage = response.data.perPage;
                    this.rows = response.data.rows;

                    this.listLoaded = true;

                } else {
                    this.items = [];
                    this.listLoaded = true;
                }
            }).catch(function(error) {
                console.log(error);
                this.items = [];
                this.listLoaded = true;               
            });   

        
        },

        deleteTimeManagerItem(id) {

            alert (id);
            
            axios.post("/api/deleteTimeManagerProgress?api_token=" + this.api_token, 
            {
                method          : "POST",
                memberID        : this.memberinfo.user_id,
                itemID          : id,        
            }).then(response => {

                if (response.data.success == true) 
                {         
                
                    this.getTimeManagerProgressList(this.page)
                    this.$forceUpdate();


                } else {

                    alert ("item was not")
                }

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
                    if (response.data.is_time_manager_notified == false) 
                    {
                        if (response.data.ellapsedDays >= 5 && response.data.currentDayProgressCounter == 0) 
                        {
                            //show only if ellapse days is 5 or greater;
                            if (response.data.spentHourPercentage < 70) {                            
                                this.showWarningMessage(response.data.time_manager_no_progress_notification);                   
                            } else {
                                console.log ("greater then 70, current percentage spent")
                            }
                            
                        }           
                    }

                    //when user opens to create it will show update info and update button
                    this.updateType = "update";

                    let content = response.data.content;

                    this.$nextTick(() => {
                        this.content = this.assignData(content);
                        this.contentData = this.assignData(content);

                        //GET CALCULATED THE PERECENTAGE OF PROGRESS
                        this.content.numberOfdays =  response.data.numberOfdays;
                        this.content.ellapsedDays = response.data.ellapsedDays;  

                        //display calculated avreate
                        this.content.averageHoursPerDay = response.data.averageHoursPerDay;
                        this.content.spentHours = response.data.spentHours;
                        this.content.remainingHours = response.data.remainingHours;
                        this.content.expectedHours = response.data.expectedHours;    
                        
                        this.content.requiredHoursFormatted = response.data.requiredHours;   

                        //progress counter
                        this.content.currentDayProgressCounter = response.data.currentDayProgressCounter;

                        //percentage
                        this.content.percentageLeft = response.data.percentageLeft;

                        this.content.spentHourPercentage =  response.data.spentHourPercentage;

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

                    let colorb1 = this.addAlpha('#808080', 0.65);
                    let colorb2 = this.addAlpha('#FFA500', 0.65); //orange
                    let colorb3 = this.addAlpha('#0000FF', 0.65);

                    this.entries = ["1"];

                    this.datacollection = {
                        labels: ['Required Hours', 'Epected Hours', 'Spent Hours'],  
                        datasets: [
                            {
                                data: [response.data.requiredHours, response.data.expectedHours, response.data.spentHours, 99],
                                label: this.formatCourse(this.content.course),
                                backgroundColor: [colorb1, colorb2, colorb3],
                                fill: false,       
                            }

                        ],   
                                                
                    } 

                    this.extraOptions = { 
                        indexAxis: 'y',
                        scales: {          
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true
                            },
                            xAxes: [
                            {
                                ticks: {
                                    min: 0,
                                    //max: this.content.requiredHours,
                                    stepSize: 25,
                                    reverse: false,
                                    beginAtZero: true,
                                                              
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
        showUpdateTimeManagerForm(id, index) {
            //get time manager id, and  index id for the list
            this.progressUpdateID = id;
            this.progressUpdateIndex = index;
            this.$bvModal.show('modalTimeManagerProgressUpdate');  
        },
        updateProgress() 
        {
            //Determine Component Opened
            let progressUpdate = this.$refs['timeManagerProgressUpdate'];
            let updaterModal = progressUpdate.$refs[this.content.course + 'TimeManagerUpdateComponent'];

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
                this.commitUpdateProgress(data);                
            } else {            
                alert ("you have to enter date and minutes")
            }   
        },
        commitUpdateProgress(data) {
         
            //SHOW LOADER HERE
            $(document).find('#modalTimeManagerProgressUpdate').find('.modal-footer').find('div.buttons-container').hide();
            $(document).find('#modalTimeManagerProgressUpdate').find('.modal-footer').find('div.loading-container').show();

            axios.post("/api/updateTimeManagerProgress?api_token=" + this.api_token, 
            {
                'method'      : "POST",
                'updateID'    : this.progressUpdateID,
                'data'        : data

            }).then(response => {
                if (response.data.success == true) 
                {  
                    this.$nextTick(() => {
                 
                        $(document).find('#modalTimeManagerProgressUpdate').find('.modal-footer').hide();                        
                        $(document).find('#modalTimeManagerProgressUpdate').find('#timeManager-'+this.content.course).find('.message').html('<div class="alert alert-success text-center" role="alert">Thank you! Progress has been submitted</div>'); 
                        $(document).find('#modalTimeManagerProgressUpdate').find('#form-timemanager-'+this.content.course).slideUp(500);        

                        this.timer = setTimeout(function(scope) {
                            scope.$bvModal.hide('modalTimeManagerProgressUpdate');
                        }, 2500, this);

                        this.getTimeManager();
                        this.getTimeManagerProgressList(this.page)
                        this.$forceUpdate();
                    });            

                } else {
                    //show error message 

                    //HIDE LOADER HERE
                    $(document).find('#modalTimeManagerProgressUpdate').find('.modal-footer').find('div.buttons-container').show();
                    $(document).find('#modalTimeManagerProgressUpdate').find('.modal-footer').find('div.loading-container').hide();

                }

            }).catch(function(error) {
                console.log(error);
                
                //HIDE LOADER HERE
                $(document).find('#modalTimeManagerProgressUpdate').find('.modal-footer').find('div.buttons-container').show();
                $(document).find('#modalTimeManagerProgressUpdate').find('.modal-footer').find('div.loading-container').hide();
            });   
        },
        addProgress() 
        {
            //Determine Component Opened
            let progressUpdate = this.$refs['timeManagerProgressCreate'];
            let updaterModal = progressUpdate.$refs[this.content.course + 'TimeManagerComponent'];

           

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
                        $(document).find('#formTimeManagerCreate').find('#form-timemanager-'+this.content.course).slideUp(300); ;  
                        this.timer = setTimeout(function(scope) {
                            scope.$bvModal.hide('modalTimeManagerProgressCreate');
                        }, 2500, this);
                        this.getTimeManager();
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
        showWarningMessage(message) {

            this.$bvModal.msgBoxOk(message, {
                title: 'Confirmation',
                size: 'md',
                buttonSize: 'sm',
                okVariant: 'primary',      
                okTitle: 'Okay, I understand',         
                headerClass: 'p-2 border-bottom-0',
                footerClass: 'p-2 border-top-0',
                centered: true
            })
            .then(value => {
                this.warningMessageValue = value
                window.location.href = window.location.href + "#time-manager-section";
            })
            .catch(err => {
                // An error occurred
            })
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
                    averageHours: "",
                    requiredDays: "",
                    remainingDays: "",
                   
                    percentageLeft: "",
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
                    material_checkbox: content.has_materials,
                    materials: JSON.parse(content.materials),                
                    requiredDays: content.required_days,
                    
                    remainingDays: content.remaining_days,
                    requiredHours: content.required_hours,
                    percentageLeft: content.percentageLeft
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

<style scoped>

html {
  scroll-behavior: smooth;
}

</style>