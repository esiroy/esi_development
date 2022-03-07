<template>
    <div id="member-timemanager-component" class="">

        <div class="bg-primary col-12 text-white pt-2 pb-2 text-center">
            <strong>Time Manager</strong>        
            <div class="btnAddScoreHolder float-right">
                <span v-b-modal.modalTimeManager><i class="fas fa-plus"></i></span>
            </div>
        </div>
     

        <div id="timemanager-content" class="blueBox">

            <div class="current-timemanager-container">
                <div class="row">
                    <div class="small col-12">
                        <strong>Course</strong>: 
                        <span id="course">{{ content.course }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="small d-inline">
                            <strong>Current Score</strong>: {{ content.currentScore }}
                        </div>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                      <div class="small d-inline"><strong>Target Score</strong>: {{ content.targetScore }}</div>
                    </div>
                </div>

                <div class="row  pt-2 ">
                    <div class="col-12 small">
                        {{ dateFormatter(content.startDate) }} - {{ dateFormatter(content.endDate) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Required Days</span>:
                            <span>{{ content.requiredDays }} </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Remaining Days </span>:
                            <span>{{ content.remainingDays }} </span>
                        </div> 
                    </div>

                    <div class="col-12 mt-2">
                        <div class="small">
                            <span class="font-weight-bold">Time Achievement </span>:
                            <span>{{ content.percentTimeAchievement }}% </span>
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
            <time-manager-component ref="timeManager" :memberinfo="memberinfo"  :csrf_token="csrf_token" :api_token="api_token"/>
            <template #modal-footer>
                <div class="buttons-container w-100">                    
                    <div v-if="updateType == 'update' || updateType == 'edit'">
                        <b-button variant="primary" size="sm" class="float-right mr" id="updateExamScore" v-on:click="updateExamScore">Update Exam Score</b-button>
                    </div>
                    <div v-else>
                        <b-button variant="primary" size="sm" class="float-right mr" id="create" v-on:click="create">Create Time Manager</b-button>
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


        <b-modal id="modalTimeManagerProgressUpdate" title="Time Manager Progress Update"  @hide="resetTimeManagerUpdateModal">
        
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
                course: "IELTS",   

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
            }


        }
    },
    mounted: function () 
	{
      
        this.content.materials.push({'id': 1, 'value': "test 1" })
        this.content.materials.push({'id': 2, 'value': "test 2" })
        this.content.materials.push({'id': 3, 'value': "test 3" })      
        this.content.materials.push({'id': 3, 'value': "test 3" })     
        this.content.materials.push({'id': 3, 'value': "test 3" })     
        this.content.materials.push({'id': 3, 'value': "test 3" })     
        this.content.materials.push({'id': 3, 'value': "test 3" })     
        this.content.materials.push({'id': 3, 'value': "test 3" })     
    },

    props: {
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
    },

    methods: {     
        resetTimeManagerModal() {
        
        },
        resetTimeManagerUpdateModal() {

        },
        create() 
        {
            this.$refs.timeManager.create();
        },
        assign(data) {

            alert ("assigning!!")
            console.log(data)

        

        },
		handleCourseChange() {
            return "test";
		},
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');                      
            return fdate;            
        },
    },     

    updated: function() {
       
    }
};
</script>
