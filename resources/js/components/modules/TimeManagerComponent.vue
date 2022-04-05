<template>

    <form id="formTimeManager" name="formTimeManager" @submit.prevent="saveTimeManager">   
        
        <div id="examination-section" class="section">

            <div class="row">

                <div class="col-7">

                    <!--[start] Course Select-->
                    <div class="row">
                        <div class="col-4">
                            <div class="small"><span class="text-danger">*</span> Select Course</div>

                            <select id="course" name="course" v-model="data.course" @change="handleCourseChange($event)" 
                                class="form-control form-control-sm"  :class="{ 'is-invalid' : submitted && $v.data.course.$error }">

                                <option value="" class="mx-0 px-0">Select Course</option>
                                <option value="IELTS" class="mx-0 px-0">IELTS</option>
                                <option value="TOEFL">TOEFL</option>
                                <option value="TOEFL_Junior">TOEFL Junior</option>
                                <option value="TOEFL_Primary_Step_1">TOEFL Primary Step 1</option>
                                <option value="TOEFL_Primary_Step_2">TOEFL Primary Step 2</option>
                                <option value="TOEIC_Listening_and_Reading">TOEIC Listening and Reading</option>
                                <option value="TOEIC_Speaking">TOEIC Speaking</option>
                                <option value="TOEIC_Writing">TOEIC Writing</option>
                                <option value="EIKEN">EIKEN(英検）</option>
                                <option value="TEAP">TEAP</option>
                                <option value="Other_Test">Other Test</option>
                            </select>

                            <div v-if="submitted && !$v.data.course.required" class="invalid-feedback">
                                course required
                            </div>
                        </div>


                        <div class="col-4">
                            <div class="small"><span class="text-danger">*</span> Select Start Date</div>
                             
                            <datepicker id="startDate" 
                              
                                name="startDate"                                          
                                v-model="data.startDate"
                                :value="data.startDate"
                                :format="dateFormatter"
                                :placeholder="'Select Start Date'"
                                :input-class="['form-control form-control-sm bg-white',  { 'is-invalid' : submitted  && $v.data.startDate.$error }] "
                                :language="ja"
                            ></datepicker> 

                            <div v-if="submitted && !$v.data.startDate.required" class="text-danger small pt-1">
                                start date required
                            </div>

                        </div>

                        <div class="col-4">
                            <div class="small"><span class="text-danger">*</span> Select End Date</div>

                            <datepicker id="endDate" 
                                name="endDate"                                          
                                v-model="data.endDate"
                                :value="data.endDate"
                                :format="dateFormatter"
                                :placeholder="'Select Date'"                                
                                :input-class="['form-control form-control-sm bg-white',  { 'is-invalid' : submitted && $v.data.endDate.$error }] "
                                :language="ja"
                            ></datepicker>


                            <div v-if="submitted && !$v.data.endDate.required" class="text-danger small pt-1">
                                end date required
                            </div>

                        </div>
                    </div>

                    <!-- [START] Grade Level (Optional) -->
                    <div class="row mt-2" v-show="data.course == 'EIKEN'">
                        <div class="col-4">

                            <div class="small"><span class="text-danger">*</span> Grade Level </div>
                            
                            <select id="gradeLevel" name="gradeLevel" v-model="data.gradeLevel" class="form-control form-control-sm"  
                                :class="{ 'is-invalid' : submitted && $v.data.gradeLevel.$error }">

                                <option value="" class="mx-0 px-0">Select Grade Level</option>
                                <option value="5" class="mx-0 px-0">Grade 5</option>
                                <option value="4" class="mx-0 px-0">Grade 4</option>
                                <option value="3" class="mx-0 px-0">Grade 3</option>
                                <option value="pre_2" class="mx-0 px-0">Grade Pre 2</option>
                                <option value="2" class="mx-0 px-0">Grade 2</option>
                                <option value="pre_1" class="mx-0 px-0">Grade Pre 1</option> 
                                <option value="1" class="mx-0 px-0">Grade 1</option> 
                            </select> 
                            <div v-if="submitted && $v.data.gradeLevel.$error " class="invalid-feedback">
                                Grade level required
                            </div>
                        </div>
                    
                    </div>
                    <!--[END] Grade Level -->

                    <div class="row mt-2">
                        <div class="col-4">
                            <div class="small"><span class="text-danger">*</span> Current Score</div>

                            <input type="text" name="currentScore" v-model="data.currentScore" class="form-control form-control-sm"
                                placeholder="Enter Current Score"  :class="['form-control form-control-sm bg-white', { 'is-invalid' : submitted && $v.data.currentScore.$error }]">

                            <div v-if="submitted && !$v.data.currentScore.required" class="invalid-feedback">
                                current score required
                            </div>
                            <div v-if="submitted && !$v.data.currentScore.numeric" class="invalid-feedback">
                                current score must be numeric
                            </div>  

                        </div>
                        <div class="col-4">
                            <div class="small"><span class="text-danger">*</span> Target Score</div>

                            <input type="text" name="targetScore" v-model="data.targetScore" 
                                :class="['form-control form-control-sm bg-white', { 'is-invalid' : submitted && $v.data.targetScore.$error }]" 
                                placeholder="Enter Target Score">

                            <div v-if="submitted && !$v.data.targetScore.required" class="invalid-feedback">
                                target score required
                            </div>
                            <div v-if="submitted && !$v.data.targetScore.numeric" class="invalid-feedback">
                                target score must be numeric
                            </div>                            

                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-4">
                            <div class="small"><span class="text-danger">*</span> Required Hours</div>                           

                            <input type="text" name="requiredHours" v-model="data.requiredHours"
                                :class="['form-control form-control-sm bg-white', { 'is-invalid' : submitted && $v.data.requiredHours.$error }]" 
                                :placeholder="'Enter Required Hours'" 
                                
                            >
                            <div v-if="submitted && !$v.data.requiredHours.required" class="invalid-feedback">
                                target score required
                            </div>
                            <div v-if="submitted && !$v.data.requiredHours.numeric" class="invalid-feedback">
                                target score must be numeric
                            </div>   

                        </div>   

                        <div class="col-4">
                            <div class="mt-4">

                                <a href="JavaScript:PopupCenter('https://www.mytutor-jpn.com/info/2021/1215193414.html','必要な時間',900,820);" class="text-primary">
                                    <i class="fa fa-question-circle" aria-hidden="true"></i> <strong>必要な時間</strong>
                                </a>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="col-5">
                    <div id="timemanager-materials-container">
                        <input type="checkbox" name="material_checkbox" @change="showMaterials" v-model="data.material_checkbox"> 

                        <span class="pl-2 small"> 受講者自身で用意した教材 (5 materials max)</span>

                        <div id="timemanager-materials" v-show="data.material_checkbox">

                            <input v-for="(material, materialKey) in data.materials" :key="materialKey" :id="material.id" name="material[]" type="text" 
                                placeholder="Enter Material Description" class="form-control form-control-sm mb-2" v-model="data.materials[materialKey].value">
                        </div>

                        

                        <div id="timemanager-materials-buttons" v-show="data.material_checkbox">

                            <a href="#" @click.prevent="addMaterial" class="text-primary small" v-show="this.data.materials.length < 5">
                                <i class="fa fa-plus-circle text-primary" aria-hidden="true"></i> Add New Material
                            </a>
                        </div>       

                    </div>

                </div>
            </div>
        </div>

    </form>   
</template>

<script>
 
import * as Moment from 'moment';
import Datepicker from 'vuejs-datepicker';
import {en, ja} from 'vuejs-datepicker/dist/locale'; 

import Vuelidate from "vuelidate";
import { required, numeric, decimal, requiredIf } from "vuelidate/lib/validators";
Vue.use(Vuelidate);



export default {   
    name: "time-manager-component",
    components: {    
        Datepicker
    },      
    props: {
        memberinfo: Object,
        csrf_token: String,		
        api_token: String,
        content: Object,
    },
    data() {
        return {
            ja: ja,
            en: en,        
            submitted: "",

            data: {
                material_checkbox: "",
                course: "",
                courseTextValue : "",
                gradeLevel: "",
                gradeLevelTextValue: "",
                startDate: "",
                
                currentScore: "",
                targetScore: "",
                requiredHours: "",
                materials: [],
                //auto calculated
                requiredDays: "",
                remainingDays: ""
                
            },
        }
    },
    validations: 
    {        
        data: {
            course: { required },
            startDate: { required },
            endDate: { required },   
            currentScore:     { required, decimal },  
            targetScore:  { required, decimal },
            requiredHours:  { required, decimal },       
            gradeLevel: { required: requiredIf(data => data.course === 'EIKEN'), }
    
        }
    },
    mounted: function () 
	{
        this.data = this.content;
    },    
    methods: {     
        resetTimeMangerEntryModal() {

            this.submitted = "";

            this.data = {             
                material_checkbox: "",
                course: "",
                courseTextValue : "",
                gradeLevel: "",
                gradeLevelTextValue: "",
                startDate: "",
                
                currentScore: "",
                targetScore: "",
                requiredHours: "",
                materials: [],
                //auto calculated
                requiredDays: "",
                remainingDays: ""                
            }
        },
        updateTimeManager() {
            this.submitted = true;
            this.$v.data.$touch();

            if (!this.$v.data.$invalid) 
            {                

                //SHOW LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').hide();
                $(document).find('.modal-footer').find('div.loading-container').show();


                axios.post("/api/updateTimeManager?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    memberID        : this.memberinfo.user_id,
                    data            : this.data
                }).then(response => {

                    if (response.data.success == true) 
                    {                    
                     
                        let content = response.data.content;

                        this.$nextTick(() => {

                            this.$parent.$parent.$parent.updateType = "update";

                            this.$parent.$parent.$parent.content = {
                                course: content.course,   
                                gradeLevel: content.gradeLevel,
                                startDate: content.start_date,
                                endDate: content.end_date,
                                currentScore: content.current_score,
                                targetScore: content.target_score,
                                requiredHours: content.required_hours,
                                material_checkbox: content.has_materials,
                                materials: content.materials,                
                                requiredDays: content.required_days,
                                remainingDays: content.remaining_days,
                                requiredHours: content.required_hours,
                                percentTimeAchievement: 0
                            } 

                             this.$parent.$parent.$parent.$bvModal.hide('modalTimeManager');
                            
                   
                        });
                    } else {
                        $(document).find('.modal-footer').find('div.buttons-container').show();
                        $(document).find('.modal-footer').find('div.loading-container').hide();

                    }
                    
                }).catch(function(error) {
                    console.log(error);

                    $(document).find('.modal-footer').find('div.buttons-container').show();
                    $(document).find('.modal-footer').find('div.loading-container').hide();                    
                });
            }
        },        

        saveTimeManager() {

            this.submitted = true;
            this.$v.data.$touch();

            if (!this.$v.data.$invalid) 
            {                
                //SHOW LOADER HERE
                $(document).find('.modal-footer').find('div.buttons-container').hide();
                $(document).find('.modal-footer').find('div.loading-container').show();

                axios.post("/api/createTimeManager?api_token=" + this.api_token, 
                {
                    method          : "POST",
                    memberID        : this.memberinfo.user_id,
                    data            : this.data
                }).then(response => {

                    if (response.data.success == true) 
                    {                    
                     
                        let content= response.data.content;

                        this.$nextTick(() => {

                            //this.data.requiredHours = content.required_hours;

                            this.$parent.$parent.$parent.updateType = "update";
                            

                            this.$parent.$parent.$parent.content = {
                                course: content.course,
                                gradeLevel: content.gradeLevel,
                                startDate: content.start_date,
                                endDate: content.end_date,
                                currentScore: content.current_score,
                                targetScore: content.target_score,
                                requiredHours: content.required_hours,
                                material_checkbox: content.has_materials,
                                materials: content.materials,                
                                requiredDays: content.required_days,
                                remainingDays: content.remaining_days,
                                requiredHours: content.required_hours,
                                percentTimeAchievement: 0
                            } 


                            this.$parent.$parent.$parent.$bvModal.hide('modalTimeManager')            
                   
                        });
                    } else {
                    
                        $(document).find('.modal-footer').find('div.buttons-container').show();
                        $(document).find('.modal-footer').find('div.loading-container').hide();     

                    }
                }).catch(function(error) {
                    console.log(error);
                    $(document).find('.modal-footer').find('div.buttons-container').show();
                    $(document).find('.modal-footer').find('div.loading-container').hide();                         
                });
            }
        },        

		handleCourseChange(event) 
        {
            let index = event.target.value;
            let course = event.target.selectedOptions[0].text;
            //console.log(index + ": " + course)

            if (typeof this.content.gradeLevel == 'undefined') {
                 this.content.gradeLevel = "";
            } 
           
		},
        showMaterials() {
            if (this.data.materials.length == 0) {
                this.data.materials.push({'id': 1, 'value': "" })
                this.data.materials.push({'id': 2, 'value': "" })
                this.data.materials.push({'id': 3, 'value': "" })
            }
        },
        addMaterial() {
            this.data.materials.push({'id': this.data.materials.length + 1, 'value': "" })
        },
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');  
            return fdate;
        },
        calculateHours() {

            const ONE_DAY = 1000 * 60 * 60;
            // Calculate the difference in milliseconds
            const differenceMs = Math.abs( this.data.startDate - this.data.endDate);
            // Convert back to days and return
            let requiredHours =  differenceMs / ONE_DAY;

            if (this.data.startDate && this.data.endDate)
            {

                if (Number.isNaN(requiredHours)) {
                   
                } else {
                    this.data.requiredHours = requiredHours;
                }   
            }
            
        }
    }
      
}

</script>