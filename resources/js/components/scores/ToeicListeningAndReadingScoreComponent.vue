<template>

  <div id="ScoresComponent" class="ScoresComponent">

    <!--[start] TOEIC-Listening-And-Reading- -->
    <div id="examination-score-TOEIC_Listening_and_Reading" class="section examScoreHolder">
        <div class="row pt-2">
            <div class="col-2">                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Listening Score </div>                
            </div>
            <div class="col-10">
                <select id="TOEIC_Listening_and_Reading-listeningScore" name="listeningScore" v-model="examScore.TOEIC_Listening_and_Reading.listening" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Listening Score</option>
                    <option :value="item + 4" :key="item + 4" class="mx-0 px-0" v-for="item in 491">{{ item + 4 }}</option>
                </select>
            </div>
        </div>



        <div class="row pt-2">
            <div class="col-2">                                       
                <div class="pl-2 small "> <span class="text-danger">*</span> Reading Score </div>                
            </div>
            <div class="col-10">            
                <select id="TOEIC_Listening_and_Reading-readingScore" name="vScore" v-model="examScore.TOEIC_Listening_and_Reading.reading" class="form-control form-control-sm pl-0 col-md-3">
                    <option value="" class="mx-0 px-0">Select Reading Score</option>
                    <option :value="item + 4" :key="item + 4" class="mx-0 px-0" v-for="item in 491 ">{{ item + 4 }}</option>
                </select>
            </div>
        </div>


        <div class="row pt-2">
            <div class="col-2">
                <div class="pl-2 small "> <span class="text-danger">*</span> Total Score </div>
            </div>
            <div class="col-10">
                <select id="TOEIC_Listening_and_Reading-total" name="total" v-model="examScore.TOEIC_Listening_and_Reading.total" class="form-control form-control-sm pl-0 col-md-3 mb-2">
                    <option value="" class="mx-0 px-0">Select Total Score</option>
                    <option :value="item + 9" :key="item + 4" class="mx-0 px-0" v-for="item in 981">{{ item + 9 }}</option>
                </select>
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
    //console.log("TOEIC_Listening_and_Reading- junior mounted")
  },
};

</script>

<style scoped>
    .scores-container {
        width: 100%
    }
</style>