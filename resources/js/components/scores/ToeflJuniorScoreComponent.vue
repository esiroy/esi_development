<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEFL_Junior_ -->
    <div id="examination-score-TOEFL_Junior" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-10">
                <select id="TOEFL_Junior_listeningScore" name="listeningScore" v-model="examScore.TOEFL_Junior.listening" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item + 199" :key="item" class="mx-0 px-0" v-for="item in 101">{{ item + 199 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-2">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Language Form & Meaning</div>                
            </div>
            <div class="col-10">
                <select id="TOEFL_Junior_languageFormAndMeaningScore" name="languageFormAndMeaningScore" v-model="examScore.TOEFL_Junior.languageFormAndMeaningScore" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-">Language Form & Meaning Score</option>
                    <option :value="item + 199" :key="item" class="mx-0 px-0" v-for="item in 101">{{ item + 199 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-2">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div class="col-10">            
                <select id="TOEFL_Junior_readingScore" name="readingScore" v-model="examScore.TOEFL_Junior.reading" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item + 199" :key="item" class="mx-0 px-0" v-for="item in 101">{{ item + 199 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-2">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div class="col-10">
                <select id="TOEFL_Junior_total" name="total" v-model="examScore.TOEFL_Junior.total" class="form-control form-control-sm pl-0 col-md-3 mb-2">
                    <option value="" class="mx-0 px-0">Select Total Score</option>
                    <option :value="item + 599" :key="item" class="mx-0 px-0" v-for="item in 301">{{ item + 599 }}</option>
                </select>
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
    examScore: Object
  },
  methods: {
    getTotalScore: function(index) {

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