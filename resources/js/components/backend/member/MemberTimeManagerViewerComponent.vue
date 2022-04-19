<template>
    <div id="time-manager-section" class="row">

        <div id="timemanager-content" class="col-6" >

            <div class="text-center" v-show="this.updateType == 'new'">
                No Time Manager Data
            </div>
            
            <div class="current-timemanager-container" v-show="this.updateType == 'update'">

                <div class="row">
                    <div class="col-12">
                        <strong>Course</strong>: 
                        <span id="course">{{ formatCourse(this.content.course) }}</span>
                    </div>
                </div>
                

                <div class="row" v-if="this.content.course == 'EIKEN'">
                    <div class="col-12">
                        <strong>Grade Level</strong>: 
                        <span id="course">{{ formatCourse(this.content.gradeLevel) }}</span>
                    </div>
                </div>
                                

                <div class="row">
                    <div class="col-12">
                        <div class="d-inline">
                            <strong>Current Score</strong>: {{ this.content.currentScore }}
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                      <div class="d-inline"><strong>Target Score</strong>: {{ this.content.targetScore }}</div>
                    </div>
                </div>

                <div class="row  pt-2 mb-2">
                    <div class="col-12 text-primary font-weight-bold">
                        {{ dateFormatter(this.content.startDate) }} - {{ dateFormatter(this.content.endDate) }}                          
                    </div>
                    <div class="col-12">
                        <div class="">
                            <span class="font-weight-bold">Duration</span>:
                            <span>{{ this.content.numberOfdays }} days </span>
                        </div> 
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-12">
                        <div class="">
                            <span class="font-weight-bold">Required Hours</span>:
                            <span>{{ this.content.requiredHoursFormatted }}  </span>
                        </div> 
                    </div>


                    <div class="col-12">
                        <div class="">
                            <span class="font-weight-bold">Average Hours Per Day</span>:
                            <span>{{ this.content.averageHoursPerDay }}</span>
                        </div> 
                    </div>        


                    <div class="col-12">
                        <div class="">
                            <span class="font-weight-bold">Spent Hours</span>:
                            <span>{{ this.content.spentHours }}</span>
                        </div> 
                    </div> 



                    <div class="col-12">
                        <div class="">
                            <span class="font-weight-bold">Remaining Hours</span>:
                            <span>{{ this.content.remainingHours }}</span>
                        </div> 
                    </div> 


                    <div class="col-12">
                        <div class="">
                            <span class="font-weight-bold">Expected Hours</span>:
                            <span>{{ this.content.expectedHours }}</span>
                        </div> 
                    </div> 


        
                    <div class="col-12 mt-2">                         

                        <div class="" >
                            <span class="font-weight-bold">Time Achievement </span>:

                            <!--PERCENTAGE LEFT-->
                            <span class="text-danger" v-if="this.content.ellapsedDays > this.content.numberOfdays">
                                {{ this.content.percentageLeft }}%  Date Expired
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
                        <div class="text-danger small" v-if="this.content.spentHourPercentage < 70">
                            <span class="pb-2 pt-2">警告！ 学習時間が大変遅れています。</span>
                        </div>
                        -->


                    </div>  
                </div>
            </div>

            <div id="buttonContainer" class="row mt-2" v-show="this.updateType == 'update'">
                <!-- View Scores -->
                <div class="col-6 pr-0">
                    <span v-b-modal.modalTimeManagerProgressList>
                        <b-button size="sm" block variant="dark"  pill >
                            <b-icon-calculator></b-icon-calculator> <span class="">Progress List</span> 
                        </b-button>                   
                    </span>
                </div>

                <!--Score Graphs Button -->
                <div class="col-6 pl-1 pr-0">
                    <span v-b-modal.modalTimeManagerProgressGraph>
                        <b-button size="sm" block variant="primary" pill>
                            <b-icon-bar-chart-fill></b-icon-bar-chart-fill> <span class="">View Graph </span>
                        </b-button>                   
                    </span>
                    &nbsp;
                </div>
            </div>
        </div>

        <div class="col-6">
            <span class="font-weight-bold">Materials</span>
            <div v-for="(material, materialKey) in this.content.materials" :key="materialKey">
                {{ material.value }}
            </div>
        </div>        

        <b-modal id="modalTimeManagerProgressList" title="Time Manager Progress List" size="lg" @show="getTimeManagerProgressList(1)">

            <div class="card esi-card">
                <div class="car-body esi-card-body">

                    <table class="table esi-table table-bordered table-striped">
                        <thead>
                            <tr>
                                <!--<th>ID</th>-->
                                <th scope="col" class="font-weight-bold">Date</th>
                                <th scope="col" class="font-weight-bold">Minutes</th>
                                <th scope="col" class="font-weight-bold">Total Minutes</th>
                                <th scope="col" class="font-weight-bold">Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, itemsIndex) in items" :key="itemsIndex">

                                <!--<td>{{item.id}}</td>-->
                                
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
                    <b-button variant="primary" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalTimeManagerProgressList')">Close List</b-button>   
                </div>

                <div class="w-100" v-show="!listLoaded">
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
    name: "member-time-manager-viewer-component",
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

                        this.content.materials = JSON.parse(response.data.content.materials);

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
                        labels: ['Required Hours', 'Expected Hours', 'Spent Hours'],  
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
                    console.log("error retrieveing graph stats");                    
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