<template>

	<div id="timeManager-TOEIC_Listening_and_Reading" class="d-none">

		<div class="row">
			<div :class="this.size.leftColumn">
				<div class="pl-2 small"> <span class="text-danger">&nbsp;</span> Course </div>
			</div>
			<div :class="this.size.rightColumn">
				<input type="text" id="course" :value="this.content.courseTextValue"
					disabled name="course"  :class="' form-control form-control-sm '">  
			</div>
		</div>


		<div class="row pt-2">
			<div :class="this.size.leftColumn">                       
				<div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
			</div>
			<div :class="this.size.rightColumn">
				<input v-on:keyup="getTotalMinutes" id="TOEIC_Listening_and_Reading-listeningScore" name="listeningScore" v-model="data.listening" :class="this.size.select +' form-control form-control-sm'">					
			</div>
			
		</div>



		<div class="row pt-2">
			<div :class="this.size.leftColumn">                                       
				<div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
			</div>
			<div :class="this.size.rightColumn">            
				<input v-on:keyup="getTotalMinutes" id="TOEIC_Listening_and_Reading-readingScore" name="vScore" v-model="data.reading" :class="this.size.select +' form-control form-control-sm'">
					
			</div>
		</div>


		<div class="row pt-2">
			<div :class="this.size.leftColumn">
				<div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
			</div>
			<div :class="this.size.rightColumn">
				<input type="text" id="total" disabled name="TOEIC_Listening_and_Readingtotal" v-model="data.total" :class="this.size.select +' form-control form-control-sm '"> 
			</div>
		</div>

	</div>

</template>

<script>

export default {
  name: "TOEIC_Listening_and_ReadingScoreComponent",
  data() {
    return {                 
              
        data: {       
            reading: "",
            listening: "",
            total: ""
        } 
   
    };
  },
  props: {
    content: Object,
    size: Object,
  },
  methods: {
    getTotalMinutes() {
        
    }
  },
  computed: {},
  updated: function () {
    
  },
  mounted: function () 
  {
    //console.log("TOEIC_Listening_and_Reading- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>