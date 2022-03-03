<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEIC-Listening-And-Reading- -->
    <div id="examination-score-TOEIC_Listening_and_Reading" class="section examScoreHolder">
        <div class="row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TOEIC_Listening_and_Reading-listeningScore" name="listeningScore" v-model="examScore.TOEIC_Listening_and_Reading.listening" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item + 4" :key="item + 4" class="mx-0 px-0" v-for="item in 491">{{ item + 4 }}</option>
                </select>
            </div>
            
        </div>



        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TOEIC_Listening_and_Reading-readingScore" name="vScore" v-model="examScore.TOEIC_Listening_and_Reading.reading" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item + 4" :key="item + 4" class="mx-0 px-0" v-for="item in 491 ">{{ item + 4 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TOEIC_Listening_and_Readingtotal" v-model="examScore.TOEIC_Listening_and_Reading.total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>
    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "TOEIC_Listening_and_ReadingScoreComponent",
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
        let total =  this.$parent.$parent.$parent.$options.methods.getTotalScore('TOEIC_Listening_and_Reading');
        this.examScore.TOEIC_Listening_and_Reading.total  = total;

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
    //console.log("TOEIC_Listening_and_Reading- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>