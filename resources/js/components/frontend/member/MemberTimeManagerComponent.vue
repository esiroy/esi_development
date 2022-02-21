<template>    
    <div id="memberTimeManager">  
      
        <div class="row">
            <div class="col-4">  
				<label for="examType">Exam Type</label>          
                <select id="examType" name="examType" v-model="examType" @change="handleChangeExamType($event)" class="form-control form-control-sm">
                    <option value="" class="mx-0 px-0">Select Examination Type</option>
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
            <div class="col-4">                 
				<label for="currentScore">Current Score</label>
				<input id="currentScore" name="currentScore" class="form-control form-control-sm" type="text"></input>
            </div>
            <div class="col-4">
                Target 
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-4">

				Examination Start Date
                <datepicker id="examStartDate" 
                    name="examStartDate"                                          
                    v-model="examStartDate"
                    :value="examStartDate"
                    :format="dateFormatter"
                    :placeholder="'Select Exam Start Date'"
                    :input-class="[ 'form-control form-control-sm bg-white']"
                    :language="ja"></datepicker>

            </div>

            <div class="col-4">

				Examination Last Date

                <datepicker id="examEndDate" 
                    name="examEndDate"                                          
                    v-model="examEndDate"
                    :value="examEndDate"
                    :format="dateFormatter"
                    :placeholder="'Select Exam Last Date'"
                    :input-class="[ 'form-control form-control-sm bg-white']"
                    :language="ja"></datepicker>                
            </div>


            
        </div>

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
            examType: "",
            examStartDate: "",
            examEndDate: "",
            option: [],
            ja: ja,
            en: en,
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
        create() {


        },
		handleChangeExamType() {
		
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
