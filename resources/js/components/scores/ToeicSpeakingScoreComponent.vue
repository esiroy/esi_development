<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEIC-Speaking- -->
    <div id="examination-score-TOEIC_Speaking" class="section examScoreHolder">

        <div class="row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Speaking Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TOEIC_Speaking-speakingScore" name="speakingScore" v-model="examScore.TOEIC_Speaking.speaking" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 201">{{ item - 1 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TOEIC_totalScore" v-model="examScore.TOEIC_Speaking.total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>

        
    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "ToeicSpeakingScoreComponent",
  data() {
    return {                 
     
    };
  },
  props: {
    examScore: Object,
    size: Object,    
  },
  methods: {
    getTotal() {
        let total =  this.$parent.$parent.$parent.$options.methods.getTotalScore('TOEIC_Speaking');
        this.examScore.TOEIC_Speaking.total  = total;

        if (this.$parent.$parent.$parent.submitted === true) {
            this.$parent.$parent.$parent.$options.methods.highlightExamElement();
        }        
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