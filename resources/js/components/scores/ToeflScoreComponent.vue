<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEFL-iBT -->
    <div id="examination-score-TOEFL" class="section examScoreHolder">

      

        <div class="row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Speaking Score </div>             
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TOEFLspeakingScore" name="writingBandScore" v-model="examScore.TOEFL.speakingScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Writing Score </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TOEFLwritingScore" name="writingScore" v-model="examScore.TOEFL.writingScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TOEFLreadingScore" name="readingScore" v-model="examScore.TOEFL.readingScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TOEFLlisteningScore" name="listeningScore" v-model="examScore.TOEFL.listeningScore" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">
                <input type="text" id="total" disabled name="TOEFLtotal" v-model="examScore.TOEFL.total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>
    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "ToeflScoreComponent",
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
        let total =  this.$parent.$parent.$parent.$options.methods.getTotalScore('TOEFL');
        this.examScore.TOEFL.total  = total;

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
    //console.log("toefl added")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>