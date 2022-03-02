<template>
    <div id="member-time-manager-component" class="">

        <div class="bg-primary col-12 text-white pt-2 pb-2 text-center">
            <strong>Time Manager</strong>        
            <div class="btnAddScoreHolder float-right">
                <span v-b-modal.modalTimeManager><i class="fas fa-plus"></i></span>
            </div>
        </div>
     

        <div class="blueBox">


            <div class="current-timemanager-container">
                <div class="row">
                    <div class="small col-12">
                        <strong>Course</strong>: 
                        <span id="course">TOEIC Listening and Reading</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="small d-inline">
                            <strong>Current Score</strong>: {{ currentScore }}
                        </div>
                        <div class="small d-inline ml-1">
                            <strong>Target Score</strong>: {{ targetScore }}
                        </div>
                    </div>
                </div>
                <div class="row  pt-2 ">
                    <div class="col-12 small">
                        {{ dateStart }} - {{ dateEnd }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Required Days</span>:
                            <span>{{ requiredDays }} </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Remaining Days </span>:
                            <span>{{ remainingDays }} </span>
                        </div> 
                    </div>
                    <div class="col-12">
                        <div class="small">
                            <span class="font-weight-bold">Required Hours</span>:
                            <span>{{ requiredHours }}hrs </span>
                        </div> 
                    </div>                    
                </div>


            </div>



            <b-modal id="modalTimeManager" title="Time Manager" size="xl" @hide="resetModal">
                <time-manager-component ref="timeManager" :memberinfo="memberinfo"  :csrf_token="csrf_token" :api_token="api_token"/>

                <template #modal-footer>
                    <div class="buttons-container w-100">
                        <p class="float-left"></p>
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

            
        </div>


    </div>
</template>


<script>
import TimeManagerComponent from '../../modules/TimeManagerComponent.vue';


export default {   
    name: "member-time-manager-component",
    components: {    
        TimeManagerComponent
    },     
    data() {
        return {
            updateType: "",
            currentScore: 2250,
            targetScore: 2250,
            dateStart: "1/2/999",
            dateEnd: "1/2/999",
            requiredDays: 365,
            remainingDays: 365,
            requiredHours: 500,

        }
    },
    mounted: function () 
	{
      
    },

    props: {
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
    },

    methods: {     
        resetModal() {
        
        },
        create() 
        {
            this.$refs.timeManager.create()
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
