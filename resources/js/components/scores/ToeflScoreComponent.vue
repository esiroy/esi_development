<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEFL-iBT -->
    <div id="examination-score-TOEFL" class="section examScoreHolder">

        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small  mb-2"> <span class="text-danger">*</span> Speaking Score </div>             
            </div>
            <div class="col-10">            
                <select id="TOEFLspeakingScore" name="writingBandScore" v-model="examScore.TOEFL.speakingScore" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Speaking Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-2">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Writing Score </div>                
            </div>
            <div class="col-10">            
                <select id="TOEFLwritingScore" name="writingScore" v-model="examScore.TOEFL.writingScore" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Writing Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-2">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div class="col-10">
                <select id="TOEFLreadingScore" name="readingScore" v-model="examScore.TOEFL.readingScore" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>

        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-10">
                <select id="TOEFLlisteningScore" name="listeningScore" v-model="examScore.TOEFL.listeningScore" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 31">{{ item -1 }}</option>                     
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-2">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div class="col-10">
                <select id="TOEFLtotal" name="total" v-model="examScore.TOEFL.total" class="form-control form-control-sm pl-0 col-md-3 mb-2">
                    <option value="" class="mx-0 px-0">Total Score</option>
                    <option :value="item -1" :key="item -1" class="mx-0 px-0" v-for="item in 121">{{ item -1 }}</option>                     
                </select>
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
    //console.log("toefl added")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>