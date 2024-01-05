<template>

  <div id="ScoresComponent" class="ScoresComponent">       
        
    <!--[start] TOEFL_ITP_Level_2 -->
    <div id="examination-score-TOEFL_ITP_Level_2" class="section examScoreHolder">

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Listening </div>
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="listening" name="listening" 
                    v-model="examScore.TOEFL_ITP_Level_2.listening" 
                    :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item + 30" :key="item + 30" class="mx-0 px-0" v-for="item in 20">{{ item + 30 }}</option>
                </select>
            </div>
        </div> 


        <div class="row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Structure and Written Expression </div>
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="structure_and_written_expression" name="structure_and_written_expression" 
                    v-model="examScore.TOEFL_ITP_Level_2.structure_and_written_expression" 
                    :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Structure and Written Expression</option>
                    <option :value="item + 30" :key="item + 30" class="mx-0 px-0" v-for="item in 20">{{ item + 30 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Reading Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="reading" name="reading" 
                    v-model="examScore.TOEFL_ITP_Level_2.reading" 
                    :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Reading  Score</option>
                    <option :value="item + 30" :key="item + 30" class="mx-0 px-0" v-for="item in 20">{{ item + 30 }}</option>
                </select>
            </div>
        </div>

    
        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small"> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="total" v-model="examScore.TOEFL_ITP_Level_2.total" 
                :class="this.size.select +' form-control form-control-sm '">         
            </div>
        </div>
        
                
    </div>
    <!--[end]-->

   
    
  </div>
</template>

<script>

export default {
  name: "ToeflITPLevel2ScoreComponent",
 
  data() {
    return {                 
        
    };
  },
  props: {
    examScore: Object,
    size: Object,
  },
  methods: {
    getTotal: function(total) 
    { 
        let listening = this.examScore.TOEFL_ITP_Level_2.listening;
        let structure_and_written_expression = this.examScore.TOEFL_ITP_Level_2.structure_and_written_expression;      
        let reading = this.examScore.TOEFL_ITP_Level_2.reading;


        console.log("total", listening, structure_and_written_expression, reading);


        if (structure_and_written_expression !== '' && listening !== '' &&  reading !== '') 
        {
            let sum =  parseFloat(structure_and_written_expression) + parseFloat(listening) +  parseFloat(reading);
            this.examScore.TOEFL_ITP_Level_2.total = sum;
        }

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
    //console.log("IELTS scores component mounted");
    //console.log(this.size)
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>