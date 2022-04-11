<template>

    <!--[start] timeManager - EIKEN-->
    <div id="timeManager-EIKEN" class="d-none">
    
        <div class="message"></div> 

        <form name="form-timemanager-EIKEN" id="form-timemanager-EIKEN">
        
            <div class="row">
                <div :class="this.size.leftColumn">
                    <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
                </div>
                <div :class="this.size.rightColumn">
                    <input type="text" id="course" :value="this.content.courseTextValue "
                        disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
                </div>
            </div>


           <div class="row pt-2">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small"> <span class="text-danger">*</span> Level of Examination </div>
                </div>                   
                <div :class="this.size.rightColumn">
                    <select id="gradeLevel" name="gradeLevel" v-model="gradeLevel" @change="handleChangeGradeLevel($event)" class="form-control form-control-sm" disabled>
                        <option value="" class="mx-0 px-0">Select Grade Level</option>
                        <option value="5" class="mx-0 px-0">Grade 5</option>
                        <option value="4" class="mx-0 px-0">Grade 4</option>
                        <option value="3" class="mx-0 px-0">Grade 3</option>
                        <option value="pre_2" class="mx-0 px-0">Grade Pre 2</option>
                        <option value="2" class="mx-0 px-0">Grade 2</option>
                        <option value="pre_1" class="mx-0 px-0">Grade Pre 1</option> 
                        <option value="1" class="mx-0 px-0">Grade 1</option> 
                    </select>       
                </div>                     
            </div>


            <div class="row pt-2">
                <div :class="this.size.leftColumn">
                    <div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Date </div>
                </div>
                <div :class="this.size.rightColumn">
        
                    <datepicker id="startDate" 
                        name="startDate"                                          
                        v-model="date"
                        :value="date"
                        :format="dateFormatter"
                        :placeholder="'Select Date'"
                        :input-class="[this.size.select +' form-control form-control-sm bg-white',  { 'is-invalid' : submitted  && $v.date.$error }] "
                        :language="ja"
                        :disabledDates="disabledDates"
                    ></datepicker>   

                    <div v-if="submitted && !$v.date.required" class="invalid-feedback">
                        date required
                    </div>
                                                
                </div>
            </div>



 


            <div id="grade_5" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade 5</div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_5" name="grade_5" v-model="data.grade_5"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                   
                </div>
            </div>


            <div id="grade_4" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade 4</div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_4" name="grade_4" v-model="data.grade_4"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">
                </div>
            </div>



            <div id="stage_1_separator" class="row pt-2">
                <div class="col-12">                  
                    <div class="strike">
                        <span class="text-secondary small">1st Stage</span>
                    </div>                
                </div>
            </div>



            <div id="grade_3_stage_1" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade 3 1st stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_3_1st_stage" name="grade_3_1st_stage" v-model="data.grade_3_1st_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">
                        
                </div>
            </div>         


            <div id="grade_pre_2_stage_1" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 2 1st Stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_pre_2_1st_stage" name="grade_pre_2_1st_stage" v-model="data.grade_pre_2_1st_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                  
                </div>
            </div>        


            <div id="grade_2_stage_1" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade 2 1st stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_2_1st_stage" name="grade_2_1st_stage" v-model="data.grade_2_1st_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                   
                </div>
            </div>           


            <div id="grade_pre_1_stage_1" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 1 1st Stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_pre_1_1st_stage" name="grade_pre_1_1st_stage" v-model="data.grade_pre_1_1st_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                    
                </div>
            </div>        

            <div id="grade_1_stage_1" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade 1 1st Stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_1_1st_stage" name="grade_1_1st_stage" v-model="data.grade_1_1st_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                    
                </div>
            </div>     

            <div id="stage_2_separator" class="row pt-2">
                <div class="col-12">                  
                    <div class="strike">
                        <span class="text-secondary small">2nd Stage</span>
                    </div>                
                </div>
            </div>

            <!-- 2nd stage -->
            <div id="grade_3_stage_2" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade 3 2nd stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_3_2nd_stage" name="grade_3_2nd_stage" v-model="data.grade_3_2nd_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                 
                </div>
            </div>                

            <div id="grade_pre_2_stage_2" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 2 2nd Stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_pre_2_2nd_stage" name="grade_pre_2_2nd_stage" v-model="data.grade_pre_2_2nd_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                  
                </div>
            </div>        


            <div id="grade_2_stage_2" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade 2 2nd stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_2_2nd_stage" name="grade_2_2nd_stage" v-model="data.grade_2_2nd_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                  
                </div>
            </div>           


            <div id="grade_pre_1_stage_2" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small "> <span class="text-danger">*</span> Grade pre 1 2nd Stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_pre_1_2nd_stage" name="grade_pre_1_2nd_stage" v-model="data.grade_pre_1_2nd_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                  
                </div>
            </div>        

            <div id="grade_1_stage_2" class="minutes-entry row pt-2 grade_level_container">
                <div :class="this.size.leftColumn">                       
                    <div class="pl-2 small"> <span class="text-danger">*</span> Grade 1 2nd Stage </div>                
                </div>
                <div :class="this.size.rightColumn">
                    <input  v-on:keyup="getTotalMinutes" id="EIKEN-grade_1_2nd_stage" name="grade_1_2nd_stage" v-model="data.grade_1_2nd_stage"  placeholder="分 minutes" :class="this.size.select +' form-control form-control-sm'">                 
                </div>
            </div>             

            <div class="row pt-2 total">
                <div :class="this.size.leftColumn">
                    <div class="pl-2 small "> <span class="text-danger">*</span> Total</div>
                </div>
                <div :class="this.size.rightColumn">
                    <input type="text" id="total" disabled name="EIKENtotal" v-model="data.total" :class="this.size.select +' form-control form-control-sm '"> 
                </div>
            </div>
        </form>

    </div>
    <!--[end]-->

</template>

<script>

import * as Moment from 'moment';
import Datepicker from 'vuejs-datepicker';
import {en, ja} from 'vuejs-datepicker/dist/locale'; 

export default 
{
    name: "EikenTimeManagerComponent",
    data() {
        return {        

            ja: ja,
            en: en,
            submitted: false,

            disabledDates: {
                from: new Date(Date.now() + 8640000)
            },

            date: "",
            gradeLevel: "",

            data: {
                    grade_5: "",
                    grade_4: "",
                    grade_3_1st_stage: "",
                    grade_pre_2_1st_stage: "",
                    grade_2_1st_stage: "",
                    grade_pre_1_1st_stage: "",
                    grade_1_1st_stage: "",

                    grade_3_2nd_stage: "",
                    grade_pre_2_2nd_stage: "",
                    grade_2_2nd_stage: "",
                    grade_pre_1_2nd_stage: "",
                    grade_1_2nd_stage: "",     
                    total: ""               
                },            
        }
    },
    components: {    
        Datepicker
    },      
    props: {
        mastercontent: Object,    
        content: Object,
        size: Object,
        type: String,        
    },
    methods: 
    {
        hideFieldsContainer() 
        {
            document.querySelectorAll('.grade_level_container').forEach(function(element) {
                element.style.display = 'none';
            });  

        },
        hideField(id) 
        {
            let element =  document.getElementById(id);
            if (element) {
                element.style.display = 'none';
            }
        },    
        showField(id) {         
            let element =  document.getElementById(id);
            if (element) {
                element.style.display = 'flex';
            }
        },
        resetField(id) {           
            this.data[id] = "";                  
        },
        getValue(id) {
            let element =  document.getElementById(id);
            if (element) {
                return element.value;
            }
        },
        handleChangeGradeLevel() 
        {
            //change main exam level
            ///this.$parent.$parent.$parent.examLevel = this.gradeLevel;


           

            this.total = "";
            this.data.total  = this.total;
            
            this.hideFieldsContainer();
            if (this.gradeLevel === "" || this.gradeLevel === null || this.gradeLevel == null) {
                //hide separators
                this.hideField('stage_1_separator');
                this.hideField('stage_2_separator'); 
            } else {
            
                if (this.gradeLevel > 3) 
                { 
                    this.showField('grade_'+ this.gradeLevel);
                    this.resetField("grade_" + this.gradeLevel);  

                    this.hideField('stage_1_separator');
                    this.hideField('stage_2_separator');                    
                }
                else {
                    this.showField('grade_'+ this.gradeLevel + "_stage_1");
                    this.showField('grade_'+ this.gradeLevel + "_stage_2");

                    this.resetField("grade_" + this.gradeLevel + "_1st_stage");  
                    this.resetField("grade_" + this.gradeLevel + "_2nd_stage");  

                    //show separators
                    this.showField('stage_1_separator');
                    this.showField('stage_2_separator');             
                }

            }
        },
    
        //Getter
        getDate() {
            return this.date;
        },
        getMinutesData() {
            return this.data;
        },
        dateFormatter(date) 
        {
            let fdate = Moment(date).format('YYYY年 MM月 D日');  
            return fdate;
        },    
        //get Total Minutes
        getTotalMinutes()
        {
            this.data.total = this.$parent.$options.methods.getTotalMinutes(this.content.course);
        }    
    },
    computed: {},
    updated: function () {
    
    },
    mounted: function () 
    {

        this.gradeLevel = this.content.gradeLevel;

        this.handleChangeGradeLevel();

       

        if (this.type == 'update') {
            Object.keys(this.content).forEach(key => 
            {    
                //since we add course textvalue and date, filter it 
                if (key !== 'course' || key !== 'courseTextValue' || key !== 'date') {
                    this.data[key] = this.content[key];
                }                
            });
        }

        this.content.course  = this.mastercontent.course;
        this.content.courseTextValue = this.mastercontent.courseTextValue;
        this.date = this.mastercontent.date;        
    }
};
</script>
<style scoped>
    .scores-container {
        width: 100%
    }
    
    .grade_level_container {
       display: none;
    }

    .strike {
        display: block;
        text-align: center;
        overflow: hidden;
        white-space: nowrap;
    
    font-size: 20px;
    }

    .strike > span {
        position: relative;
        display: inline-block;
    }
	
    .strike > span:before,
    .strike > span:after {
        content: "";
        position: absolute;
        top: 50%;
        width: 9999px;
        /* Here is the modification */
        border-top: 1px dotted #c9c9c9;
    }

    .strike > span:before {
        right: 100%;
        margin-right: 15px;
    }

    .strike > span:after {
        left: 100%;
        margin-left: 15px;
    }
</style>