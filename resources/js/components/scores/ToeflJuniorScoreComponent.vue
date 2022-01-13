<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEFL_Junior_ -->
    <div id="examination-score-TOEFL_Junior" class="section examScoreHolder">
        <div class="row">
            <div :class="this.size.leftColumn">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TOEFL_Junior_listeningScore" name="listeningScore" v-model="examScore.TOEFL_Junior.listening" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item + 199" :key="item" class="mx-0 px-0" v-for="item in 101">{{ item + 199 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Language Form & Meaning</div>                
            </div>
            <div :class="this.size.rightColumn">
                <select @change="getTotal" id="TOEFL_Junior_languageFormAndMeaningScore" name="languageFormAndMeaningScore" v-model="examScore.TOEFL_Junior.languageFormAndMeaning" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-">Language Form & Meaning Score</option>
                    <option :value="item + 199" :key="item" class="mx-0 px-0" v-for="item in 101">{{ item + 199 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div :class="this.size.rightColumn">            
                <select @change="getTotal" id="TOEFL_Junior_readingScore" name="readingScore" v-model="examScore.TOEFL_Junior.reading" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item + 199" :key="item" class="mx-0 px-0" v-for="item in 101">{{ item + 199 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div :class="this.size.leftColumn">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div :class="this.size.rightColumn">

                <!--
                <select @change="getTotal" id="TOEFL_Junior_total" name="total" v-model="examScore.TOEFL_Junior.total" :class="this.size.select +' form-control form-control-sm pl-0'">
                    <option value="" class="mx-0 px-0">Select Total Score</option>
                    <option :value="item + 599" :key="item" class="mx-0 px-0" v-for="item in 301">{{ item + 599 }}</option>
                </select>-->

                <input type="text" id="TOEFL_Junior_total" disabled name="total" v-model="examScore.TOEFL_Junior.total" :class="this.size.select +' form-control form-control-sm '"> 
            </div>
        </div>
    </div>
    <!--[end]-->

    
  </div>
</template>

<script>

export default {
  name: "TOEFL_Junior_JuniorScoreComponent",
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
        let total =  this.$parent.$parent.$parent.$options.methods.getTotalScore('TOEFL_Junior');
        this.examScore.TOEFL_Junior.total  = total;

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
    //.log("TOEFL_Junior_ junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>