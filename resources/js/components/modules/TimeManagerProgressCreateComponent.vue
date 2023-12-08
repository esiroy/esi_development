<template>

    <form id="formTimeManagerCreate" name="formTimeManagerCreate" @submit.prevent="create">
        <div class="row">
            <div class="col">
            
                <div id="minutes-entry" class="card-body">
                    <IELTSTimeManagerComponent ref="IELTSTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></IELTSTimeManagerComponent>
                    <ToeflTimeManagerComponent ref="TOEFLTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></ToeflTimeManagerComponent>                      
                    <ToeflJuniorTimeManagerComponent  ref="TOEFL_JuniorTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></ToeflJuniorTimeManagerComponent>
                    <ToeflPrimaryStep1TimeManagerComponent  ref="TOEFL_Primary_Step_1TimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></ToeflPrimaryStep1TimeManagerComponent>
                    <ToeflPrimaryStep2TimeManagerComponent  ref="TOEFL_Primary_Step_2TimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></ToeflPrimaryStep2TimeManagerComponent>
                    <ToeicListeningAndReadingTimeManagerComponent  ref="TOEIC_Listening_and_ReadingTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></ToeicListeningAndReadingTimeManagerComponent>
                    <ToeicSpeakingTimeManagerComponent  ref="TOEIC_SpeakingTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></ToeicSpeakingTimeManagerComponent>
                    <ToeicWritingTimeManagerComponent  ref="TOEIC_WritingTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></ToeicWritingTimeManagerComponent>
                    <EikenTimeManagerComponent  ref="EIKENTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></EikenTimeManagerComponent>
                    <TeapTimeManagerComponent  ref="TEAPTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></TeapTimeManagerComponent>
                    <OtherTimeManagerComponent  ref="Other_TestTimeManagerComponent" :mastercontent="masterContent" :type="'create'" :content="content" :size="this.size"></OtherTimeManagerComponent>
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
import EikenTimeManagerComponent from "../timemanagerprogress/EikenTimeManagerComponent.vue";
import TeapTimeManagerComponent from "../timemanagerprogress/TeapTimeManagerComponent.vue";
import OtherTimeManagerComponent from "../timemanagerprogress/OtherTimeManagerComponent.vue";


export default {   
    name: "time-manager-progress-create-component",
    components: {    
        IELTSTimeManagerComponent, ToeflTimeManagerComponent, ToeflJuniorTimeManagerComponent,
        ToeflPrimaryStep1TimeManagerComponent, ToeflPrimaryStep2TimeManagerComponent, ToeicListeningAndReadingTimeManagerComponent,
        ToeicSpeakingTimeManagerComponent, ToeicWritingTimeManagerComponent, EikenTimeManagerComponent, TeapTimeManagerComponent,
        OtherTimeManagerComponent
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

            masterContent: {},

            //adjust the column sizes of the entry component modals
            size: {
                leftColumn  : "col-5",
                rightColumn : "col-7",
                select      : "col-8",
            },
        }
    },
    beforeMount: function () {

        //console.log( this.items[this.progressupdateindex].udate);
        //console.log( this.progressupdateid);

     
        this.masterContent.course              = this.content.course;
        this.masterContent.courseTextValue     = this.content.course;

    },    
    mounted: function () 
    {
        this.showElementId('timeManager-'+  this.content.course)
    },             
    methods: {     
        getTotalMinutes(course) {          

            console.log(course) ;

            let values = Array.from(document.querySelectorAll('#timeManager-'+ course +' .minutes-entry input')).map(input => input.value );
            //get total
            let total = 0;
            for (var i = 0, n = values.length; i < n; ++i) {
                if (values[i]) { total = parseFloat(total) + parseFloat(values[i]); }            
            }
            return total;
        },
        addProgress() {
            this.submitted = true;
            this.$v.content.$touch();
            console.log(this.data);

            if (!this.$v.content.$invalid) {
                axios.post("/api/addTimeManagerProgress?api_token=" + this.api_token, 
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
		handleCourseChange(event) 
        {
            let index = event.target.value;
            let course = event.target.selectedOptions[0].text;
            //console.log(index + ": " + course)
		},

        showElementId(id) {
            
            if (document.getElementById(id)) 
            {            
                document.getElementById(id).style.display = "block";
                document.getElementById(id).className = "d-block";
            } else {
                console.log(id + " element does not exists");
            }           
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