<template>


<!--[start] TOEIC-Writing -->
<div id="timeManager-TOEIC_Writing" class="section examScoreHolder">


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
			<div class="pl-2 small "> <span class="text-danger">*</span> Writing Score </div>                
		</div>
		<div :class="this.size.rightColumn">
			<input @change="getTotalMinutes" id="TOEIC_Writing-writingScore" name="writingScore" v-model="data.writing" :class="this.size.select +' form-control form-control-sm pl-0'">
			
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

	
</div>
<!--[end]-->

    
  
</template>

<script>

export default {
  name: "ToeicWritingScoreComponent",
  data() {
    return {                 
        data: {       
            reading: "",
            listening: "",
			speaking: "",
			writing: "",
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
    //console.log("TOEIC_Writing- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>