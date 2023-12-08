<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEFL_Primary_Step_1- -->
    <div id="examination-score-TOEFL_Primary_Step_1" class="section examScoreHolder">
        <div class="row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TOEFL_Primary_Step_1-listeningScore" name="listeningScore" v-model="examScore.TOEFL_Primary_Step_1.listening" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 110">{{ item -1 }}</option>
                </select>
            </div>
        </div>



        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TOEFL_Primary_Step_1-writingScore" name="writingScore" v-model="examScore.TOEFL_Primary_Step_1.reading" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item - 1" :key="item - 1" class="mx-0 px-0" v-for="item in 110">{{ item -1 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TOEFL_Primary_Step_1-total" v-model="examScore.TOEFL_Primary_Step_1.total" :class="this.size.select +' form-control form-control-sm '">               
            </div>
        </div>
    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "TOEFL_Primary_Step_1ScoreComponent",
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
        let total =  this.$parent.$parent.$parent.$options.methods.getTotalScore('TOEFL_Primary_Step_1');
        this.examScore.TOEFL_Primary_Step_1.total  = total;

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
    //console.log("TOEFL_Primary_Step_1- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>