<template>

  <div id="timeManager-TOEFL_Primary_Step_1"  class="d-none">

	<div class="message"></div> 

	<form name="form-timemanager-TOEFL_Primary_Step_1" id="form-timemanager-TOEFL_Primary_Step_1">

		<div class="row">
			<div :class="this.size.leftColumn">
				<div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
			</div>
			<div :class="this.size.rightColumn">
				<input type="text" id="course" :value="this.content.courseTextValue"
					disabled name="course"  :class="this.size.select +' form-control form-control-sm '">  
			</div>
		</div>

		<div class="row pt-2">
			<div :class="this.size.leftColumn">
				<div class="pl-2 small"> <span class="text-danger">*</span> Date </div>
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
				></datepicker>   

				<div v-if="submitted && !$v.date.required" class="invalid-feedback">
					date required
				</div>
											
			</div>
		</div>        


		<div class="minutes-entry row pt-2">
			<div :class="this.size.leftColumn">                       
				<div class="pl-2 small "> <span class="text-danger">*</span> Listening  </div>                
			</div>
			<div :class="this.size.rightColumn">
				<input v-on:keyup="getTotalMinutes" id="TOEFL_Primary_Step_1-listening" name="listening" v-model="data.listening" :class="this.size.select +' form-control form-control-sm'">
			</div>
		</div>

		<div class="minutes-entry row pt-2">
			<div :class="this.size.leftColumn">                                       
				<div class="pl-2 small "> <span class="text-danger">*</span> Reading  </div>                
			</div>
			<div :class="this.size.rightColumn">            
				<input v-on:keyup="getTotalMinutes" id="TOEFL_Primary_Step_1-reading" name="reading" v-model="data.reading" :class="this.size.select +' form-control form-control-sm'">
				
			</div>
		</div>


		<div class="row pt-2">
			<div :class="this.size.leftColumn">
				<div class="pl-2 small "> <span class="text-danger">*</span> Total Minutes </div>
			</div>
			<div :class="this.size.rightColumn">
				<input type="text" id="total" disabled name="TOEFL_Primary_Step_1-total" v-model="data.total" :class="this.size.select +' form-control form-control-sm '">               
			</div>
		</div>

	</form>

  </div>

</template>

<script>
import * as Moment from "moment";
import Datepicker from "vuejs-datepicker";
import { en, ja } from "vuejs-datepicker/dist/locale";

export default {
  name: "TOEFL_Primary_Step_1_TimeManagerComponent",
  data() {
	return {
	  ja: ja,
	  en: en,
	  submitted: false,
	  date: "",

	  data: {
		reading: "",
		listening: "",
		total: "",
	  },
	};
  },
  components: {
	Datepicker,
  },
  props: {
	content: Object,
	size: Object,
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
  updated: function () {},
  mounted: function () {
	//console.log("TOEFL_Primary_Step_1- junior mounted")
  },
};
</script>

<style scoped>
.scores-container {
  width: 100%;
}
</style>