<template>

    <form id="formTimeManagerUpdate" name="formTimeManagerUpdate" @submit.prevent="create">  
        <div class="row">
            <div class="col">
                <div id="minutes-entry" class="card-body">
                    <IELTSTimeManagerComponent ref="IELTSTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></IELTSTimeManagerComponent>
                    <ToeflTimeManagerComponent ref="TOEFLTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></ToeflTimeManagerComponent>                    
                    <ToeflJuniorTimeManagerComponent  ref="TOEFL_JuniorTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></ToeflJuniorTimeManagerComponent>
                    <ToeflPrimaryStep1TimeManagerComponent  ref="TOEFL_Primary_Step_1TimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></ToeflPrimaryStep1TimeManagerComponent>
                    <ToeflPrimaryStep2TimeManagerComponent  ref="TOEFL_Primary_Step_2TimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></ToeflPrimaryStep2TimeManagerComponent>
                    <ToeicListeningAndReadingTimeManagerComponent  ref="TOEIC_Listening_and_ReadingTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></ToeicListeningAndReadingTimeManagerComponent>
                    <ToeicSpeakingTimeManagerComponent  ref="TOEIC_SpeakingTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></ToeicSpeakingTimeManagerComponent>
                    <ToeicWritingTimeManagerComponent  ref="TOEIC_WritingTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></ToeicWritingTimeManagerComponent>
                    <EikenTimeManagerComponent  ref="EIKENTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></EikenTimeManagerComponent>
                    <TeapTimeManagerComponent  ref="TEAPTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></TeapTimeManagerComponent>
                    <OtherTimeManagerComponent  ref="Other_TestTimeManagerUpdateComponent" :type="'update'" :content="newContent" :item="item" :size="this.size"></OtherTimeManagerComponent>                    
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
        items: Array,       
        progressupdateid: Number,
        progressupdateindex: Number,
        memberinfo: Object,
        content: Object,
        csrf_token: String,		
        api_token: String,
    },
    data() {
        return {
            submitted: "",

            //this will be passed to child
            newContent: {},
            item: {},

            //adjust the column sizes of the entry component modals
            size: {
                leftColumn  : "col-5",
                rightColumn : "col-7",
                select      : "col-8",
            },
        }
    },
    beforeMount: function () 
    {
        //console.log( this.items[this.progressupdateindex].udate);
        //console.log( this.progressupdateid);

       
        this.newContent = {
            date    :   this.items[this.progressupdateindex].udate,
            course  : this.content.course,
            courseTextValue: this.content.courseTextValue,
            gradeLevel: this.content.gradeLevel,
        }

        //the item will be passed here, together with the minutes
        this.item = this.items[this.progressupdateindex];  

    },
    mounted: function () 
    {
        
        this.showElementId('timeManager-'+  this.content.course);
    },             
    methods: {     
        getTotalMinutes(course) 
        {
            let values = Array.from(document.getElementById("formTimeManagerUpdate").querySelectorAll('#timeManager-'+ course +' .minutes-entry input')).map(input => input.value );

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
        showElementId(id) {
            
            if (document.getElementById("formTimeManagerUpdate").querySelector("#"+id)) 
            {            
                document.getElementById("formTimeManagerUpdate").querySelector("#"+id).style.display = "block";
                document.getElementById("formTimeManagerUpdate").querySelector("#"+id).className = "d-block";
            } else {
                console.log(id + " element does not exists");
            }           
        },        
        showMaterials() {

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