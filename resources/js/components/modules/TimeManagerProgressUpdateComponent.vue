<template>

    <form id="formTimeManager" name="formTimeManager" @submit.prevent="create">   
        
        <div id="examination-section" class="section">

            <div class="row">
                <div class="col">

                    <!--[start] Course Select-->
                    <div id="content" class="d-none">
                        <div class="row">
                            <div class="col-4">
                                <div class="small">Course: {{ content.timeManagerCourse }} </div>                           
                            </div>

                            <div class="col-4">
                                <div class="small">Start Date: {{ dateFormatter(content.examStartDate) }} </div>
                            </div>

                            <div class="col-4">
                                <div class="small">End Date: {{ dateFormatter(content.examEndDate) }} </div>
                            </div>

                        </div>

                        <div class="row mt-2">
                            <div class="col-4">
                                <div class="small">Current Score: {{ content.currentScore }}</div>
                            </div>
                            <div class="col-4">
                                <div class="small">Target Score: {{ content.targetScore }} </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-4">
                                <div class="small">Required Hours : {{ content.requiredHours }}hrs </div>
                            </div>
                        </div>
                    </div>

          
                    <div id="minutes-entry" class="card-body">
                        <IELTSTimeManagerComponent :content="content" :size="this.size"></IELTSTimeManagerComponent>
                    </div>

                </div>    

         
            </div>
        </div>

    </form>   
</template>

<script>
import * as Moment from 'moment'; 


//Import Score Types
import IELTSTimeManagerComponent from "../timemanagerprogress/IELTSTimeManagerComponent.vue";
import ToeflTimeManagerComponent from "../timemanagerprogress/ToeflTimeManagerComponent.vue";
import ToeflJuniorTimeManagerComponent from "../timemanagerprogress/ToeflJuniorTimeManagerComponent.vue";
import ToeflPrimaryStep1TimeManagerComponent from "../timemanagerprogress/ToeflPrimaryStep1TimeManagerComponent.vue";
import ToeflPrimaryStep2TimeManagerComponent from "../timemanagerprogress/ToeflPrimaryStep2TimeManagerComponent.vue";
import ToeicListeningAndReadingTimeManagerComponent from "../timemanagerprogress/ToeicListeningAndReadingTimeManagerComponent.vue";
import ToeicSpeakingTimeManagerComponent from "../timemanagerprogress/ToeicSpeakingTimeManagerComponent.vue";
import ToeicWritingTimeManagerComponent from "../timemanagerprogress/ToeicWritingTimeManagerComponent.vue";

export default {   
    name: "time-manager-progress-update-component",
    components: {    
        IELTSTimeManagerComponent, ToeflTimeManagerComponent, ToeflJuniorTimeManagerComponent,
        ToeflPrimaryStep1TimeManagerComponent, ToeflPrimaryStep2TimeManagerComponent, ToeicListeningAndReadingTimeManagerComponent,
        ToeicSpeakingTimeManagerComponent, ToeicWritingTimeManagerComponent
    },      
    props: {
        memberinfo: Object,
        content: Object,
        csrf_token: String,		
        api_token: String,
    },
    data() {
        return {
            submitted: "",
            size: {
                leftColumn  : "col-4",
                rightColumn : "col-8",
                select      : "col-10",
            },
        }
    },
    mounted: function () 
    {
        this.showElementId('timeManager-'+  this.content.timeManagerCourse)
    },             
    methods: {     
        resetModal() {
        
        },
        create() {
            this.submitted = true;
            this.$v.content.$touch();
            console.log(this.data);
            if (!this.$v.content.$invalid) {
                axios.post("/api/createTimeManager?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    memberID        : this.memberinfo.user_id,
                    data            : this.data
                }).then(response => {
                    this.$parent.$bvModal.hide('modalTimeManager');                
                }).catch(function(error) {

                });
            }
        },        
		handleCourseChange(event) {

            let index = event.target.value;
            let course = event.target.selectedOptions[0].text;

            console.log(index + ": " + course)
		},

        showElementId(id) {
            //document.getElementById(id).style.display = "block";
            document.getElementById(id).className = "d-block";
           
        },        
        showMaterials() {

            console.log(this.material_checkbox);

            if (this.content.materials.length == 0) {
                this.content.materials.push({'id': 1, 'value': "" })
                this.content.materials.push({'id': 2, 'value': "" })
                this.content.materials.push({'id': 3, 'value': "" })
            }
            
        },
        addMaterial() {
        
            this.content.materials.push({'id': this.content.materials.length + 1, 'value': "" })
        },
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');                      
            return fdate;            
        },
    },
      
}

</script>