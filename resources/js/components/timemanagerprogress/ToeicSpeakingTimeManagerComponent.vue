<template>

  <div id="timeManager-TOEIC_Speaking"  class="d-none">

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
			<div class="pl-2 small "> <span class="text-danger">*</span> Speaking Score </div>                
		</div>
		<div :class="this.size.rightColumn">
			<input @change="getTotalMinutes" id="TOEIC_Speaking-speakingScore" name="speakingScore" v-model="data.speaking" :class="this.size.select +' form-control form-control-sm '">
				
		</div>
	</div>


	<div class="row pt-2">
		<div :class="this.size.leftColumn">
			<div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
		</div>
		<div :class="this.size.rightColumn">
			<input type="text" id="total" disabled name="TOEIC_totalScore" v-model="data.total" :class="this.size.select +' form-control form-control-sm '"> 
		</div>
	</div>
    
  </div>
</template>

<script>

export default {
  name: "ToeicSpeakingScoreComponent",
  data() {
    return {                 
        data: {       
            reading: "",
            listening: "",
			speaking: "",
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
    //console.log("TOEIC_Speaking- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>