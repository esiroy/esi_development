<template>   

    <div id="member-time-manager-component" class="">
    
        <div class="bg-primary col-12 text-white pt-2 pb-2 text-center">
            <strong>Time Manager</strong>        
            <div class="btnAddScoreHolder float-right">
                <span v-b-modal.modalTimeManager><i class="fas fa-plus"></i></span>
            </div>
        </div>
        <div class="blueBox">
            test content
        </div>



            
        <b-modal id="modalTimeManager" title="Time Manager" size="xl" @hide="resetModal">

            <form id="formTimeManager" name="formTimeManager" @submit.prevent="create">   
              
                <div id="examination-section" class="section">
                    <div class="row">
                        <div class="col-12">

                            <div class="d-inline-block">
                                <div class="pl-2 small"><span class="text-danger">*</span> Select Course</div>
                                <select id="timeManagerCourse" name="timeManagerCourse" v-model="timeManagerCourse" @change="handleCourseChange($event)" class="form-control form-control-sm">
                                    <option value="" class="mx-0 px-0">Select Course</option>
                                    <option value="IELTS" class="mx-0 px-0">IELTS</option>
                                    <option value="TOEFL">TOEFL iBT</option>
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
                            </div>
                       
                            <div class="d-inline-block">
                                <div class="pl-2 small"><span class="text-danger">*</span> Select Start Date</div>
                                <datepicker id="examStartDate" 
                                    name="examStartDate"                                          
                                    v-model="examStartDate"
                                    :value="examStartDate"
                                    :format="dateFormatter"
                                    :placeholder="'Select Date'"
                                    :input-class="[ 'form-control form-control-sm bg-white']"
                                    :language="ja"
                                ></datepicker> 
                            </div>


                            <div class="d-inline-block">
                                <div class="pl-2 small"><span class="text-danger">*</span> Select End Date</div>
                                <datepicker id="examDate" 
                                    name="examEndDate"                                          
                                    v-model="examEndDate"
                                    :value="examEndDate"
                                    :format="dateFormatter"
                                    :placeholder="'Select Date'"
                                    :input-class="[ 'form-control form-control-sm bg-white']"
                                    :language="ja"
                                ></datepicker> 
                            </div>

                            <div class="d-inline-block">


                            </div>



                        </div>
                    </div>

                </div>           

                <div id="examScoreContainer" class="row">
                    <div class="col-12">  
                      
                    </div>
                </div>
            </form>

            <template #modal-footer>
                <div class="buttons-container w-100">
                    <p class="float-left"></p>
                    <div v-if="updateType == 'update' || updateType == 'edit'">
                        <b-button variant="primary" size="sm" class="float-right mr" id="updateExamScore" v-on:click="updateExamScore">Update Exam Score</b-button>
                    </div>

                    <div v-else>
                        <b-button variant="primary" size="sm" class="float-right mr" id="create" v-on:click="create">Create Time Manager</b-button>
                    </div>
                    <b-button variant="danger" size="sm" class="float-right mr-2" @click="$bvModal.hide('modalUpdateMemberForm')">Cancel</b-button> 

                    
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
import Datepicker from 'vuejs-datepicker';
import {en, ja} from 'vuejs-datepicker/dist/locale'; 

export default {   
    name: "member-time-manager-component",
    components: {    
        Datepicker
    },     
    data() {
        return {
            timeManagerCourse: "",
            examStartDate: "",
            examEndDate: "",
            updateType: "",

            option: [],
            ja: ja,
            en: en,
            //this is for examp type column
            size: {
                leftColumn  : "col-4",
                rightColumn : "col-8",
                select      : "col-10",
            },             
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
        create() {

            alert ("create")
        },
		handleChangeExamType() {
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
