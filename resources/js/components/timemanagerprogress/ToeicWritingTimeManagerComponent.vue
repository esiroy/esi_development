<template>
  <!--[start] TOEIC-Writing -->
  	<div id="timeManager-TOEIC_Writing" class="d-none">

        <div class="message"></div> 

        <form name="form-timemanager-TOEIC_Writing" id="form-timemanager-TOEIC_Writing">

			<div class="row">
				<div :class="this.size.leftColumn">
					<div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
				</div>
				<div :class="this.size.rightColumn">
					<input type="text" id="course" :value="this.content.courseTextValue"
					disabled name="course"  :class="this.size.select  + ' form-control form-control-sm '">  
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
						:disabledDates="disabledDates"
                        :language="ja"
                    ></datepicker>   

                    <div v-if="submitted && !$v.date.required" class="invalid-feedback">
                        date required
                    </div>
                                                
                </div>
            </div>			


			<div class="minutes-entry row pt-2">
				<div :class="this.size.leftColumn">                       
					<div class="pl-2 small "> <span class="text-danger">*</span> Writing Score </div>                
				</div>
				<div :class="this.size.rightColumn">
					<input v-on:keyup="getTotalMinutes" id="TOEIC_Writing-writingScore" name="writingScore" v-model="data.writing" :class="this.size.select +' form-control form-control-sm'">					
				</div>
			</div>


			<div class="row pt-2">
			<div :class="this.size.leftColumn">
				<div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
			</div>
			<div :class="this.size.rightColumn">
				<input type="text" id="total" disabled name="TOEIC_Writing_totalScore" v-model="data.total" :class="this.size.select +' form-control form-control-sm '"> 
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


export default {
	name: "ToeicWritingScoreComponent",
	data() {
		return {            

			ja: ja,
			en: en,
			submitted: false,
			date: "",
			disabledDates: {
				from: new Date(Date.now() + 8640000)
			},
			data: {       
				//reading: "",
				//listening: "",
				//speaking: "",
				writing: "",
				total: ""
			} 
		};
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
	methods: {
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

	},
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>